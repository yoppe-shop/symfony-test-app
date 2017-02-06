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
        //echo "Dir-Separator: " . DIR_SEP . "<br />";
        return $this->render('ShopBundle:ProductsImport:csv_import.html.twig');
    }

    /**
     * @Route("products_import/csv_import", name="products_csv_import")
     */
    public function csvImportAction()
    {
        $utils = $this->get('utils');
        $debug = $this->get('debug');

        $em = $this->getDoctrine()->getManager();
        $this->getCsvProductData($csv);



        return $this->render('ShopBundle:ProductsImport:csv_import.html.twig', [
        ]);
    }

    protected function getCsvProductData(&$csv)
    {
        /**
        * Returns Arrays $product, $productsDescription, $productsImage, $attributes
        */
        $this->initializeCsvFile($csv);
        $lines = getLines($csv);
        $titleOfIndexes = $this->titleOfIndexes($lines[0]);

        unset($lines[0]);

        return $this->createDataArrays($line[1], $titleOfIndexes);
    }

    protected function initializeCsvFile(&$csv)
    {
        $csv = str_replace("\r", "", $csv);
    }

    protected function getLines(&$csv)
    {
        $lines = explode("\n", $csv);
        foreach ($lines as $key => $line) {
            if (empty($line)) {
                unset($lines[$key]);
            }
        }
        return $lines;
    }

    protected function titleOfIndexes($line0)
    {
        return explode($this->csvFieldSep, $line0);
    }

    protected function createDataArray($em, &$line, $titleOfIndexes)
    {
        // Produkt- und Produktdescription-Daten nach DB-Feldnamen rauslesen
        // Attribute mit substr rausfiltern

        $product = array();
        $productsDescription = array();
        $attributes = array();
        $rawData = explode($this->csvFieldSep, $line);
        $productsFields = $this->productsFields($em);
        $productsDescriptionFields = $this->productsDescriptionFields($em);
    
        foreach($rawData as $index => $value)
        {
            if ($value != '')
            {
                echo $titleOfIndexes[$index] . " => " . $value . "\n";

                if(substr($value, 0, 3) == 'a+.') {
                    $attribute = substr($titleOfIndexes[$index], 3);
                    $langItem = langItem($attribute);
                    $attributes[$langItem['item']] = ['action' => '+', 'value' => [$langItem['lang'] => $value]];
                }
                elseif(substr($value, 0, 2) == 'a.') {
                    $attribute = substr($titleOfIndexes[$index], 2);
                    $langItem = langItem($attribute);
                    $attributes[$langItem['item']] = ['action' => '+', 'value' => [$langItem['lang'] => $value]];
                }
                elseif(substr($value, 0, 3) == 'a-.') {
                    $attribute = substr($titleOfIndexes[$index], 3);
                    $langItem = langItem($attribute);
                    $attributes[$langItem['item']] = ['action' => '-', 'value' => [$langItem['lang'] => $value]];              
                }
                elseif(in_array($titleOfIndexes[$index], $productsFields)) {
                    $product[$titleOfIndexes[$index]] = $value;
                }
                elseif(in_array($titleOfIndexes[$index], $productsDescriptionFields)) {
                    $langItem = langItem($titleOfIndexes[$index]);
                    $productsDescription[$langItem['item']] = ['value' => [$langItem['lang'] => $value]];
                }
            }
        }
        return ['product' => $product, 'productDescription' => $productsDescription, 'attributes' => $attributes];
    }

    protected function productsFields($em)
    {
        $productsFields = $em->getClassMetadata('ShopBundle:Product')->getColumnNames();

        return array_map(function($el) {
            return str_replace('products_', '', $el);
        }, $productsFields);
    }

    protected function productsDescriptionFields($em)
    {
        $productsDescriptionFields = $em->getClassMetadata('ShopBundle:ProductsDescription')->getColumnNames();
        return array_map(function($el) {
            return str_replace('products_', '', $el);
        }, $productsDescriptionFields);
    }

    protected function langItem($key)
    {
        $pos = strpos($key, '.');
        return $pos !== false ? 
            [ 'lang' => substr($key, 0, $pos), 'item' => substr($key, $pos + 1)] : 
            [ 'lang' => 'de', 'item' => $key];
    }
}
