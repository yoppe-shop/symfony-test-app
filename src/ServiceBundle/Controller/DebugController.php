<?php

namespace ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DebugController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ServiceBundle:Default:index.html.twig');
    }
    
    function pr(&$var, $layers = 3, $stripHtml = false)
    {
        echo "<pre>";
        \Doctrine\Common\Util\Debug::dump($var, $layers, $stripHtml);
        echo "</pre>";
    }
}
