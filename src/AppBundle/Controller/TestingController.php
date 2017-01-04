<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestingController extends Controller
{
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
