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
    protected $errors = array();

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

        $em = $this->getDoctrine()->getManager('mysql');
        $this->productsOptions($em);
        // $this->getCsvProductData($csv);
$products = $em->createQuery('
    SELECT p, pa, po 
    FROM ShopBundle:Product p 
    LEFT JOIN p.productsAttributes pa 
    LEFT JOIN pa.productsOptions po 
')
->getResult();
//$debug->pr($products,6);


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
        $productsOptions = $this->productsOptions($em);

        foreach($rawData as $index => $value)
        {
            if ($value != '') {
                if(substr($titleOfIndexes[$index], 0, 3) == 'a+.') {
                    $this->setAttributesField($attributes, $titleOfIndexes[$index], '+', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 2) == 'a.') {
                    $this->setAttributesField($attributes, $titleOfIndexes[$index], '', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 3) == 'a-.') {
                    $this->setAttributesField($attributes, $attributesFields, $titleOfIndexes[$index], '-', $value);           
                }
                elseif(strpos($titleOfIndexes[$index], '.') !== false) {
                    $this->setProductsDescriptionField($productsDescription, $productsDescriptionFields, $titleOfIndexes[$index], $value);
                }
                else {
                    $this->setProductsField($product, $productsFields, $titleOfIndexes[$index], $value);
                }
            }
        }
        return ['product' => $product, 'productsDescription' => $productsDescription, 'attributes' => $attributes];
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

    protected function productsOptions($em) {
        $productsOptions = $em
            ->createQuery('
                SELECT po
                FROM ShopBundle:ProductsOption po 
                WHERE po.languageId = 2
                ORDER BY po.productsOptionsId ASC
            ')
            ->getResult();
            $debug = $this->get('debug');
            $debug->pr($productsOptions); exit;
        return $productsOptions;
    }

    protected function setProductsField(&$product, &$productsFields, $key, $value) {
        if (in_array($key, $productsFields)) {
            $product[$key] = $value;
        }
        else {
            $this->errors[]= 'Products: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function setProductsDescriptionField(&$productsDescription, &$productsDescriptionFields, $key, $value)
    {
        $langItem = $this->langItem($key);
        if (in_array($langItem['item'], $productsDescriptionFields)) {
            $productsDescription[$langItem['item']][$langItem['lang']] = $value;
        }
        else {
            $this->errors[]= 'ProductsDescription: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function setAttributesField(&$attributes, $key, $action, $value)
    {
        $attribute = substr($key, 2 + strlen($action));
        $langItem = $this->langItem($attribute);
        $attributes[$langItem['item']][$langItem['lang']] = ['action' => $action, 'value' => $value];
    }

    protected function langItem($key)
    {
        $pos = strpos($key, '.');
        return $pos !== false ? 
            [ 'lang' => substr($key, $pos + 1), 'item' => substr($key, 0, $pos)] : 
            [ 'lang' => 'de', 'item' => $key];
    }
}
