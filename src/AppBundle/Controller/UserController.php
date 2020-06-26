<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;
use AppBundle\Form\RegisterType;
use AppBundle\Form\UserType;

class UserController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function loginAction(Request $request) {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }


        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:User:login.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error
        ));
    }

    public function registerAction(Request $request) {
        if (is_object($this->getUser())) {
            return $this->redirect('home');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                //$user_repo = $em->getRepository("BackendBundle:User");

                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nick = :nick')
                        ->setParameter('email', $form->get("email")->getData())
                        ->setParameter('nick', $form->get("nick")->getData());

                $user_isset = $query->getResult();

                if (count($user_isset) == 0) {

                    $factory = $this->get("security.encoder_factory");
                    $encoder = $factory->getEncoder($user);

                    $password = $encoder->encodePassword($form->get("password")->getData(), $user->getSalt());

                    $user->setPassword($password);
                    $user->setRole("ROLE_USER");
                    $user->setImage(null);

                    $em->persist($user);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = "Te has registrado correctamente";

                        $this->session->getFlashBag()->add("status", $status);
                        return $this->redirect("login");
                    } else {
                        $status = "No te has registrado correctamente";
                    }
                } else {
                    $status = "El usuario ya existe !!";
                }
            } else {
                $status = "No te has registrado correctamente !!";
            }

            $this->session->getFlashBag()->add("status", $status);
        }

        return $this->render('AppBundle:User:register.html.twig', array(
                    "form" => $form->createView()
        ));
    }

    public function nickTestAction(Request $request) {
        $nick = $request->get("nick");

        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("BackendBundle:User");
        $user_isset = $user_repo->findOneBy(array("nick" => $nick));

        $result = "used";
        if (count($user_isset) >= 1 && is_object($user_isset)) {
            $result = "used";
        } else {
            $result = "unused";
        }
        return new Response($result);
    }

    public function editUserAction(Request $request) {

        $user = $this->getUser();
        $user_image = $user->getImage();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $query = $em->createQuery('SELECT u FROM BackendBundle:User u WHERE u.email = :email OR u.nick = :nick')
                        ->setParameter('email', $form->get("email")->getData())
                        ->setParameter('nick', $form->get("nick")->getData());

                $user_isset = $query->getResult();

                if (count($user_isset) == 0 || ($user->getEmail() == $user_isset[0]->getEmail() && $user->getNick() == $user_isset[0]->getNick())) {

                    // upload file
                    $file = $form["image"]->getData();

                    if (!empty($file) && $file != null) {
                        $ext = $file->guessExtension();
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $file_name = $user->getId() . time() . '.' . $ext;
                            $file->move("uploads/users", $file_name);

                            $user->setImage($file_name);
                        }
                    } else {
                        $user->setImage($user_image);
                    }

                    $em->persist($user);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = "Has modificado tus datos correctamente";
                    } else {
                        $status = "No has modificado tus datos correctamente";
                    }
                } else {
                    $status = "El usuario ya existe !!";
                }
            } else {
                $status = "No se han actualizado tus datos correctamente !!";
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirect('my-data');
        }

        return $this->render('AppBundle:User:edit_user.html.twig', array(
                    "form" => $form->createView()
        ));
    }

    public function usersAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $dql = "SELECT u FROM BackendBundle:User u ORDER BY u.id ASC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('AppBundle:User:users.html.twig', array(
                    'pagination' => $pagination
        ));
    }

    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $search = trim($request->query->get("search", null));

        if ($search == null) {
            return $this->redirect($this->generateURL('home_publication'));
        }

        $dql = "SELECT u FROM BackendBundle:User u "
                . "WHERE u.name LIKE :search OR u.surname LIKE :search "
                . "OR u.nick LIKE :search ORDER BY u.id ASC";
        $query = $em->createQuery($dql)->setParameter('search', "%$search%");

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('AppBundle:User:users.html.twig', array(
                    'pagination' => $pagination
        ));
    }

    public function profileAction(Request $request, $nickname = null) {
        $em = $this->getDoctrine()->getManager();

        if ($nickname != null) {
            $user_repo = $em->getRepository("BackendBundle:User");
            $user = $user_repo->findOneBy(array("nick" => $nickname));
        } else {
            $user = $this->getUser();
        }

        if (empty($user) || !is_object($user)) {
            return $this->redirect($this->generateUrl('home_publication'));
        }

        $user_id = $user->getId();
        $dql = "SELECT p FROM BackendBundle:Publication p WHERE p.user = $user_id ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $publications = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5
        );

        return $this->render('AppBundle:User:profile.html.twig', array(
                    'user' => $user,
                    'pagination' => $publications
        ));
    }

}
