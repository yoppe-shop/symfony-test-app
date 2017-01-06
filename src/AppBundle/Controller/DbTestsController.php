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
        $product = $em
            ->getRepository('AppBundle:Product')
            ->find(1);
        echo "Anfangs: " . $product->getName() . "\n";
        $product->setName("XXL - Salami - Pizza");
        $em->persist($product);
        $em->flush();
        $product = $em
            ->getRepository('AppBundle:Product')
            ->find(1);
        echo "Nach Saven: " . $product->getName() . "\n";
        return $product;
    }
}
