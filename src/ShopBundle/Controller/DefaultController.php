<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductsAttribute;
use ShopBundle\Entity\ProductsOption;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ShopBundle:Default:index.html.twig');
    }

    /**
     * @Route("shop/products")
     */
    public function getProducts()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager('mysql');
/*
        $products = $em->createQuery('
            SELECT p, pa, po, pov
            FROM ShopBundle:Product p 
            LEFT JOIN p.productsAttributes pa
            LEFT JOIN pa.productsOptions po WITH po.languageId=2
            LEFT JOIN pa.productsOptionsValues pov WITH pov.languageId=2
        ')
        ->getResult();

        $products = $em
            ->getRepository('ShopBundle:Product')
            ->findAll();

        $debug->pr($products, 6);

        exit;
*/
        $sql = "
            SELECT *
            FROM products p
            INNER JOIN product_attributes pa ON pa.products_id = p.products_id
        ";

        //$rsm = new ResultSetMapping();
        //$rsm->addEntityResult('AppBundle:Product', 'p');
        //$rsm->addJoinedEntityResult('AppBundle:ProductAttribute', 'pa', 'p', 'productAttributes');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('ShopBundle:Product', 'p');
        $rsm->addJoinedEntityFromClassMetadata('ShopBundle:ProductsAttribute', 'pa', 'p', 'productsAttributes');
        $query = $em->createNativeQuery($sql, $rsm);

        $products = $query->getResult();
        foreach ($products as $product) {
            echo "Produkt: " . $product->getModel() . " " . $product->getName() . "<br />";
            $productAttributes = $product->getProductAttributes();
            $debug->pr($productAttributes);
        }

        $debug->pr($products, 5);

        return new Response (
            'Controllerausgabe'
        );
    }
}
