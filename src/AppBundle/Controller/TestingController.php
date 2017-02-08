<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;
use AppBundle\Entity\ProductOption;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Doctrine\Common\Util\Debug;

class TestingController extends Controller
{
    /**
     * @Route("/testing/products")
     */
    public function productsAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
        $products = $this->products($em);

        $debug->pr($products, 4);
        
        return new Response (
            ''
        );
    }

    /**
     * @Route("/testing/product_options")
     */
    public function productOptionsAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
        $productOptions = $this->productOptions($em);

        $debug->pr($productOptions, 4);
        
        return new Response (
            ''
        );
    }

    /**
     * @Route("/testing/products_joined")
     */
    public function productsJoinedAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
        $products = $this->productsJoined($em);

        $debug->pr($products, 3);
        
        return new Response (
            ''
        );       
    }

    public function products(ObjectManager $em)
    {
        return $em
            ->createQuery('
                SELECT p, po 
                FROM AppBundle:Product p 
                LEFT JOIN p.productOptions po 
                ORDER BY p.id ASC
            ')
            ->getResult();
    }

    public function productOptions(ObjectManager $em)
    {
        return $em
            ->createQuery('
                SELECT po 
                FROM AppBundle:ProductOption po 
                ORDER BY po.id ASC
            ')
            ->getResult();
    }

    public function productsJoined(ObjectManager $em)
    {
        return $em
            ->createQuery('
                SELECT p, pa, po, pov 
                FROM AppBundle:Product p 
                LEFT JOIN AppBundle:ProductAttribute pa WITH pa.productId = p.id
                LEFT JOIN AppBundle:ProductOption po WITH po.id=pa.productOptionId 
                LEFT JOIN AppBundle:ProductOptionValue pov WITH pov.id=pa.productOptionValueId
                ORDER BY p.id ASC, po.id, pov.id ASC
            ')
            ->getResult();

        return $em
            ->createQuery('
                SELECT p.id AS Pid, p.model AS Artikelnummer, p.name AS Produktname, po.id AS POID, po.name AS Attributname, pov.id AS POVID, pov.name AS Wert 
                FROM AppBundle:PlainProduct p 
                LEFT JOIN AppBundle:PlainProductAttribute pa WITH pa.productId = p.id
                LEFT JOIN AppBundle:PlainProductOption po WITH po.id=pa.productOptionId 
                LEFT JOIN AppBundle:PlainProductOptionValue pov WITH pov.id=pa.productOptionValueId
                ORDER BY p.id ASC, po.id ASC
            ')
            ->getResult();
    }

    /**
     * @Route("/testing/get_value")
     */
    public function getValueAction()
    {
        $i = 1000;
        $i++;
        echo "<div>" . $i . "</div>";

        echo "<div>" . ($i + 1) . "</div>";

        $i++;

        echo "<div>" . $i . "</div>";

        return new Response (
            "Ausgabe von \$i: " . $i
        );
    }

    public function getNumber()
    {
        return 2000;
    }

    /**
     * @Route("/testing/get_html")
     */
    public function getHtml()
    {
        return new Response (
            "<html><head></head><body><h1>Begrüßung</h1><div>Hallo alle zusammen!</div><div>Nochn Div</div></body></html>"
        );        
    }
}
