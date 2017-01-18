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
        $em = $this->getDoctrine()->getManager();
        
        $article = new Article();
        $article->setTitle("Symfony");
        $article->setTeaser("Symfony Teaser");
        $article->setNews("Die neuesten Symfony News");
        $article->setCreatedAt("now");
        $article->setPublishAt("now");

        $user = $em
            ->getRepository('AppBundle:User')
            ->find(1)
        ;

        $article->setUser($user);
        $em->persist($article);
        $em->flush();

        $articles = $em->createQuery('
            SELECT a, u 
            FROM Entities\Article a 
            LEFT JOIN a.users u
        ');

        // Debug::dump($articles, 5, false);
        $tag = new Tag();
        $tag->setTitle("HTML");

        $tags = $em
            ->getRepository('AppBundle:Tag')
            ->findDuplicates($tag)
        ;
        Debug::dump($tags, 5, false);

        return new Response (
            'Das ist die Testausgabe!'
        );
    }
}
