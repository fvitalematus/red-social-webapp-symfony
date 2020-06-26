<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class GetUserExtension extends \Twig_Extension {

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('get_user', array($this, 'getUserFilter'))
        );
    }

    public function getUserFilter($user_id) {
        $user_repo = $this->doctrine->getRepository('BackendBundle:User');
        $user = $user_repo->findOneBy(array(
            "id" => $user_id            
        ));

        if (!empty($user) && is_object($user)) {
            $result = $user;
        } else {
            $result = false;
        }

        return $result;
    }

    public function getName() {
        return 'get_user_extension';
    }

}
