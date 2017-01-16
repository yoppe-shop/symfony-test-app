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
use AppBundle\Entity\ProductAttribute;
use AppBundle\Repository\TagRepository;
use \Doctrine\Common\Util\Debug;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\HttpFoundation\Session\Session;

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
            SELECT p.id as pid, model, name, pa.product_option_id 
            FROM products p
            INNER JOIN product_attributes pa ON pa.product_id = p.id
            WHERE p.id > '1'
        ";

        //$rsm = new ResultSetMapping();
        //$rsm->addEntityResult('AppBundle:Product', 'p');
        //$rsm->addJoinedEntityResult('AppBundle:ProductAttribute', 'pa', 'p', 'productAttributes');

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('AppBundle:Product', 'p', ['id' => 'pid']);
        $rsm->addJoinedEntityFromClassMetadata('AppBundle:ProductAttribute', 'pa', 'p', 'productAttributes');
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

    /**
    * @Route("/test/unit_tests")
    */
    public function unitTests()
    {
        $debug = $this->get('debug');
        $em = $this->getDoctrine()->getManager();

        $result = $this->getNumAttr($em, 2);
        $debug->pr($result, 5);
        return new Response (
            ''
        );
    }

    /**
    * @Route("/test/test_pdo")
    */
    public function testPdo()
    {
        $debug = $this->get('debug');
        $em = $this->getDoctrine()->getManager();

        $result = $this->pdoQuery($em);
        $debug->pr($result, 5);
        return new Response (
            ''
        );
    }

    /**
    * @Route("/test/nto1_test")
    */
    public function nto1Test()
    {
        $debug = $this->get('debug');
        $em = $this->getDoctrine()->getManager();

        $result = $this->getNumProd($em);
        $debug->pr($result, 4);
        return new Response (
            ''
        );
    }


    public function getNumAttr($em, $id)
    {
        $debug = $this->get('debug');
        $result = $em
            ->createQuery("
               SELECT COUNT(pa.id)
               FROM AppBundle:Product p 
               INNER JOIN p.productAttributes pa
               WHERE p.id = :id
            ")
            ->setParameter('id', $id)
            ->getSingleScalarResult();
        return $result;
    }

    public function getNumProd($em)
    {
        $debug = $this->get('debug');
        $result = $em
            ->createQuery("
               SELECT pa, p
               FROM AppBundle:ProductAttribute pa 
               INNER JOIN pa.product p
            ")
            ->getResult();    
        return $result;
    }

    public function pdoQuery($em)
    {
        $conn = $em->getConnection();
        $statement = $conn->prepare("
	        SELECT *
            FROM products p 
            LEFT JOIN product_attributes pa ON pa.product_id = p.id;
        ");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    /**
    * @Route("/test/test_session")
    */
    public function testSessionAction()
    {
        $session = new Session();
        $session->getFlashBag()->add('notice', 'Das ist eine Warnung!');
        echo "Testvar: " . $session->get('testVar') . "<br>";
        echo "Testvar: " . $session->get('testVar2');
        $session->set('testVar', 'TestValue');
        $session->set('testVar2', 'TestValue2');

        // $session->remove('testVar');
        // $session->clear();
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
