<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
 
class FormsController extends Controller
{

    /**
    * @Route("/forms/add_product")
    */
    public function addProductAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();

        $products = $em 
            ->getRepository('AppBundle:Product')
            ->findAll()
        ;

        if ($_POST) {
            $session = new Session();
            $session->getFlashBag()->add('notice', 'ES LIEGEN POST-DATEN VOR!');
        }

        $product = new Product();
        // $product->setName('Produktname');
        // $product->setModel('Artikelnummer');

        $form = $this->createFormBuilder($product)
            ->add('model', TextType::class)
            ->add('name', TextType::class)
            ->add('Speichern', SubmitType::class, ['label' => 'Speichere Produkt'])
            ->getForm()
        ;

        return $this->render('AppBundle:Forms:add_product.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function getProducts($em)
    {
        $products = $em
            ->getRepository('AppBundle:Product')
            ->findAll()
        ;
        return $products;
    }
}
