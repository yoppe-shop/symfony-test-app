<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductAttribute;
use AppBundle\Entity\ProductOption;
use AppBundle\Entity\ProductOptionValue;
use \Doctrine\Common\Util\Debug;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductsImportController extends Controller
{
    /**
     * @Route("/products_import/csv")
     */
    public function csvAction()
    {
        $product = new Product();
        $form = $this->createFormBuilder($product)
            ->add('model', TextType::class)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Absenden'))
            ->getForm();
        return $this->render('AppBundle:ProductsImport:csv.html.twig', [
            'form' => $form->createView(),
        ]);
        /**
        * Alte Datei:
        *
        * return $this->render('default/index.html.twig', [
        *    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        * ]);
        *
        */
    }

}
