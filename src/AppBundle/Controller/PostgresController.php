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

// Klasse PostgresController (Kommentar für GIT)
// Zweiter Kommentar
// Zweiter Kommentar

class PostgresController extends Controller
{
    /**
     * @Route("/postgres/", name="homepage_postgres")
     */
    public function indexAction(Request $request)
    { echo "Postgres";
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
    * @Route("/postgres/model")
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

    /**
    * @Route("/postgres/pg")
    */
    public function pgAction()
    {
        $em = $this->getDoctrine()->getManager('pg');

        $time_start = $this->microtime_float();

        $products = $em
            ->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Product', 'p')
            ->where('p.id < 5')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;
    
        Debug::dump($products, 3, false);
        echo "Verbrauchte Zeit: " . $time . "<br />";
        return new Response (
            'Ausgabe'
        );
    }

    /**
    * @Route("/postgres/pg_pdo")
    */
    public function pgPdoAction()
    {
        try {
            $em = $this->getDoctrine()->getEntityManager('pg');
            $db = $em->getConnection();
            $stmt = $db->prepare("UPDATE products SET created=:created, model=:model WHERE id=:id;");
            $ds = [
                'id' => '4',
                'created' => '2016-12-11 11:11:11',
                'model' => '41111',
            ];

            foreach ($ds as $key => &$value)
            {
                $stmt->bindParam(":".$key, $ds[$key]);
            }

            $stmt->execute();
            foreach ($db->query('SELECT * FROM products', \PDO::FETCH_ASSOC) as $row) {
                foreach ($row as $key => $value) {
                    echo $key . '=' . $value . '<br />';
                }
                echo "<hr>";
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return new Response (
            'Ausgabe'
        );
    }

    /**
    * @Route("/postgres/attributes")
    */
    public function attributesAction()
    {
        $em = $this->getDoctrine()->getEntityManager('pg');

        $products = $em->createQuery('
            SELECT p, pa
            FROM AppBundle:Product p
            LEFT JOIN AppBundle:ProductAttribute pa WITH pa.productId=p.id
        ')->getResult();

        Debug::dump($products, 3, false);

        return new Response (
            ''
        );       
    }

    protected function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
