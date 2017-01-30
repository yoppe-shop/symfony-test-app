<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use ShopBundle\Entity\ProductsAttribute;
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
        $rep = $em->getRepository('ShopBundle:Product');

        $products = $rep
            ->findAll();

        $debug->pr($products, 5);

        exit;
/*
        $sql = "
            SELECT *
            FROM products p
            INNER JOIN product_attributes pa ON pa.product_id = p.id
            WHERE p.id > '1'
        ";

        //$rsm = new ResultSetMapping();
        //$rsm->addEntityResult('AppBundle:Product', 'p');
        //$rsm->addJoinedEntityResult('AppBundle:ProductAttribute', 'pa', 'p', 'productAttributes');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('ShopBundle:Product', 'p', []);
        $rsm->addJoinedEntityFromClassMetadata('ShopBundle:ProductsAttribute', 'pa', 'p', 'productsAttributes');
        $query = $em->createNativeQuery($sql, $rsm);

        $products = $query->getResult();
        foreach ($products as $product) {
            echo "Produkt: " . $product->getModel() . " " . $product->getName() . "<br />";
            $productAttributes = $product->getProductAttributes();
            $debug->pr($productAttributes);
        }
*/
        $debug->pr($products, 5);

        return new Response (
            'Controllerausgabe'
        );
    }
}
