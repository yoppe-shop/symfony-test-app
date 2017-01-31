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

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        /**
        * This is the root method, invoked as html root (Git-Test)
        */
        $product = new Product();
        $product->setName("XProdukt");
        $product->setModel("70000");
        $product->setCreated(new \DateTime('now'));

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'names' => ['Ariane', 'Berta', 'Caesar', 'Detlef'],
            'product' => $product,
        ]);
    }

    /**
    * @Route("/index/model")
    */
    public function modelAction()
    {
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
  
        $products = $em->createQuery('
            SELECT p, pa
            FROM AppBundle:Product p 
            LEFT JOIN p.productAttributes pa
            ORDER BY p.id ASC
        ')
        ->getResult();
        // echo $products[1]['model'] . "<br /><br />";
        $debug->pr($products, 5);

        return new Response (
            'Das ist die Testausgabe!'
        );
    }
}
