<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductDescription;
use AppBundle\Entity\ProductAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\Session;
 use AppBundle\Form\ProductType;

class FormsController extends Controller
{

    /**
    * @Route("/forms")
    */
    public function addProductAction(Request $request)
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();

        $result = $em 
            ->getRepository('AppBundle:Product')
            ->find(4)
        ;

        // Wenn man ein neues Produkt anlegen will: (Im Beispiel unnötig, da ja ein bestehendes Produkt
        // in Variable 'result' aktualisiert wird)
        $product = new Product();

        // $product->setName('');
        // $product->setModel('Artikelnummer');
        // $product->setCreated(new \DateTime('now'));

        /*
        * When constructing the form in the controller itself:

        $form = $this->createFormBuilder($product)
            ->add('model', TextType::class)
            ->add('name', TextType::class)
            ->add('created', DateType::class)
            ->add('Speichern', SubmitType::class, [
                'label' => 'Speichere Produkt'
                ])
            ->getForm()
        ;
        */
        /**
        * Use the form ProductType in AppBundle/Form/ProductType:
        */
        $form = $this->createForm(ProductType::class, $result);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Eigentlich ist folgende Zuweisung überflüssig, da sie sowieso automatisch geupdated wird:
            $product = $form->getData();
            foreach($product->getProductAttributes() as $productAttribute) {
                $productAttribute->setProduct($product);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $session = new Session();
            $session->getFlashBag()->add('notice', 'Das Produkt wurde erfolgreich gespeichert.');
            return $this->redirectToRoute('homepage');            
        }

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
