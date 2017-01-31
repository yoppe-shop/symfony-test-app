<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ShopBundle\Entity\Product;
use ShopBundle\Entity\ProductsAttribute;
use ShopBundle\Entity\ProductsOption;
use ShopBundle\Entity\ProductsOptionsValue;

class ProductsImportController extends Controller
{
    protected $csvFieldSep;

    public function __construct()
    {
        $this->csvFieldSep = ";";
    }

    /**
     * @Route("products_import")
     */
    public function indexAction()
    {
        return $this->render('ShopBundle:Default:index.html.twig');
    }

    /**
     * @Route("products_import/csv_import", name="products_csv_import")
     */
    public function csvImportAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
 
        return $this->render('ShopBundle:ProductsImport:csv_import.html.twig', [
        ]);
    }

    protected function getCsvProductData(&$csv)
    {
        /**
        * Returns Arrays $product, $productsDescription, $productsImage, $attributes
        */
        $this->initializeCsvFile($csv);

        $lines = explode("\n", $csv);
        $titleOfIndexes = $this->titleOfIndexes($lines[0]);

        unset($lines[0]);

        return $this->createDataArrays($line[1], $titleOfIndexes);
    }

    protected function initializeCsvFile(&$csv)
    {
        //$csv = str_replace("\r", "", $csv);
        $csv = str_replace("B", "P", $csv);
    }

    protected function titleOfIndexes($line0)
    {
        return explode($this->csvFieldSep, $line0);
    }

    protected function createDataArrays($em, &$line, $titleOfIndexes)
    {
        // Produkt- und Produktdescription-Daten nach DB-Feldnamen rauslesen
        // Attribute mit substr rausfiltern

        $product = array();
        $productDescription = array();
        $attributes = array();
        $rawData = explode($this->csvFieldSep, $line);

        $productsFields = $em->getClassMetadata('ShopBundle:Product')->getFieldNames();
        $productsDescriptionFields = $em->getClassMetadata('ShopBundle:ProductsDescription')->getFieldNames();
        
        $em->getClassMetadata('Entities\MyEntity')->getFieldNames();
        foreach($rawData as $index => $value)
        {
            if(substr($value, 0, 3) == 'a+.') {
                $attributes[substr($value, 3)] = ['action' => '+', $value];
            }
            elseif(substr($value, 0, 2) == 'a.') {
                $attributes[substr($value, 2)] = ['action' => '', $value];
            }
            elseif(substr($value, 0, 3) == 'a-.') {
                $attributes[substr($value, 3)] = ['action' => '-', $value];                
            }
            elseif(in_array($titleOfIndexes[$index], $productsFields)) {
                $product[$titleOfIndexes[$index]] = $value;
            }
            elseif(in_array($titleOfIndexes[$index], $productsDescriptionFields)) {
                $productsDescription[$titleOfIndexes[$index]] = $value;
            }
            return ['attributes', 'product', 'productDescription'];
        }
    }
}
