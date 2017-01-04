<?php

namespace ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UtilityController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ServiceBundle:Default:index.html.twig');
    }

    public function map($entity, $data)
    {
        foreach ($data as $key => $value) {
            $fn = 'set' . ucfirst($key);
            if (method_exists($entity, $fn)) {
                $entity->$fn($value);
            }
        }
        return true;
    }
}
