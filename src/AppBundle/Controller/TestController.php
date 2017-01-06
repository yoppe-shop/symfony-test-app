<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Entity\Product;
use AppBundle\Repository\TagRepository;
use \Doctrine\Common\Util\Debug;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

// Klasse TestController (Kommentar für GIT)

class TestController extends Controller
{
    /**
     * @Route("/test/", name="homepage_test")
     */
    public function indexAction(Request $request)
    { echo "Test";
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
    * @Route("/test/model")
    */
    public function modelAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');
        $em = $this->getDoctrine()->getManager();

        $sql = "
            SELECT p.id as product_id, model, name, pa.product_option_id 
            FROM products p
            INNER JOIN product_attributes pa ON pa.product_id = p.id
            WHERE p.id > '1'
        ";

        //$rsm = new ResultSetMapping();
        //$rsm->addEntityResult('AppBundle:Product', 'p');
        //$rsm->addJoinedEntityResult('AppBundle:ProductAttribute', 'pa', 'p', 'productAttributes');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('AppBundle:Product', 'p', ['id' => 'product_id']);
        $rsm->addJoinedEntityFromClassMetadata('AppBundle:ProductAttribute', 'pa', 'p', 'productAttribute');

        $query = $em->createNativeQuery($sql, $rsm);
        $products = $query->getResult();
        foreach ($products as $product) {
            echo "Produkt: " . $product->getModel() . " " . $product->getName() . "<br />";
        }

        $debug->pr($products);

        return new Response (
            'Das ist die Testausgabe!'
        );
    }

    /**
    * @Route("/test/service_test")
    */
    public function serviceTest()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();

        $article = new Article();

        $data = [
            'title' => 'Testtitel',
            'teaser' => 'Testteaser',
            'news' => 'Testnews',
            'createdAt' => 'now',
        ];

        $utils->map($article, $data);
        $debug->pr($data);

        return new Response (
            ''
        );
    }
}
