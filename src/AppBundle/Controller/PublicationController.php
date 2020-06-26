<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\Publication;
use AppBundle\Form\PublicationType;

class PublicationController extends Controller {

    private $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                //upload imagen o document

                $file = $form['image']->getData();

                if (!empty($file) && $file != null) {
                    $ext = $file->guessExtension();

                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'pdf' || $ext == 'txt') {
                        $file_name = $user->getId() . time() . "." . $ext;
                        $file->move("uploads/publications/archivos", $file_name);

                        $publication->setImage($file_name);
                    } else {
                        $publication->setImage(null);
                    }
                } else {
                    $publication->setImage(null);
                }

                $publication->setUser($user);
                $publication->setCreatedAt(new \DateTime("now"));

                $em->persist($publication);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = 'La publicación se ha creado correctamente !!';
                } else {
                    $status = 'Error al añadir la publicación !!';
                }
            } else {
                $status = 'La publicación no se ha creado. porque el formulario no es valido';
            }

            $this->session->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('home_publication');
        }

        $publications = $this->getPublications($request);

        return $this->render('AppBundle:Publication:home.html.twig', array(
                    'form' => $form->createView(),
                    'pagination' => $publications
        ));
    }

    public function getPublications($request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $publications_repo = $em->getRepository('BackendBundle:Publication');
        $following_repo = $em->getRepository('BackendBundle:Following');

        /*
          SELECT text FROM publications WHERE user_id = 1
          OR user_id IN (SELECT followed FROM following WHERE user = 1);
         */

        $following = $following_repo->findBy(array('user' => $user));

        $following_array = array();
        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        $query = $publications_repo->createQueryBuilder('p')
                ->where('p.user = (:user_id) OR p.user IN (:following)')
                ->setParameter('user_id', $user->getId())
                ->setParameter('following', $following_array)
                ->orderBy('p.id', 'DESC')
                ->getQuery();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 5
        );
        return $pagination;
    }

    public function removePublicationAction(Request $Request, $id = null) {
        $em = $this->getDoctrine()->getManager();
        $publication_repo = $em->getRepository('BackendBundle:Publication');
        $publication = $publication_repo->find($id);
        $user = $this->getUser();

        if ($user->getId() == $publication->getUser()->getId()) {
            $em->remove($publication);
            $flush = $em->flush();

            if ($flush == null) {
                $status = 'La Publicación se ha borrado correctamente';
            } else {
                $status = 'No se ha borrado correctamente la Publicación';
            }
        } else {
            $status = 'No se ha borrado correctamente la Publicación';
        }

        return new Response($status);
    }

}
