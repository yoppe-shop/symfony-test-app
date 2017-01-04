<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DbTestsController extends Controller
{
    /**
     * @Route("/db_tests/products")
     */
    public function productsAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $products = $this->getProducts($this->getDoctrine()->getManager());

        $debug->pr($products, 4);
        
        return new Response (
            ''
        );       
    }

    public function getProducts(ObjectManager $em)
    {
        $productsRepository = $em
            ->getRepository('AppBundle:Product');
        $products = $productsRepository->findAll();
        return $products;  
    }
}
