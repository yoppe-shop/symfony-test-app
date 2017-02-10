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
    protected $productsFields = array();
    protected $productsDescriptionFields = array();
    protected $productsOptions = array();
    protected $languages = array();

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

        return $this->createDataArray($line[1], $titleOfIndexes);
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

    protected function createDataArray($em, &$line, $titleOfIndexes, $lineNo)
    {
        // Produkt- und Produktdescription-Daten nach DB-Feldnamen rauslesen
        // Attribute mit substr rausfiltern

        $product = array();
        $productsDescription = array();
        $attributes = array();
        $rawData = explode($this->csvFieldSep, $line);
        if (count($rawData) != count($titleOfIndexes)) {
            $error = 'Zeile ' . $lineNo . ': Die Anzahl der Werte stimmt nicht mit der Schlüsselanzahl überein.';
            return false;
        }
        $this->productsFields = $this->productsFields($em);
        $this->productsDescriptionFields = $this->productsDescriptionFields($em);
        $this->productsOptions = $this->productsOptions($em);
        $this->languages = $this->languages($em);
        $poCsvNames = $this->getPoCsvNames($em);
        $langCodes = $this->getLangCodes();

        foreach($rawData as $index => $value)
        {
            if ($value != '') {
                if(substr($titleOfIndexes[$index], 0, 3) == 'a+.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langCodes, $titleOfIndexes[$index], '+', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 2) == 'a.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langCodes, $titleOfIndexes[$index], '', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 3) == 'a-.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langCodes, $titleOfIndexes[$index], '-', $value);           
                }
                elseif(strpos($titleOfIndexes[$index], '.') !== false) {
                    $this->setProductsDescriptionField($productsDescription, $this->productsDescriptionFields, $langCodes, $titleOfIndexes[$index], $value);
                }
                else {
                    $this->setProductsField($product, $this->productsFields, $titleOfIndexes[$index], $value);
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

    protected function productsOptions($em)
    {
        $productsOptions = $em
            ->createQuery('
                SELECT po
                FROM ShopBundle:ProductsOption po 
                WHERE po.languageId = 2
                ORDER BY po.productsOptionsId ASC
            ')
            ->getResult();
        return $productsOptions;
    }

    protected function languages($em)
    {
        $languages = $em
            ->createQuery('
                SELECT l 
                FROM ShopBundle:Language l 
                ORDER BY l.languagesId ASC
            ')
            ->getResult();
        return $languages;
    }

    protected function setProductsField(&$product, &$productsFields, $key, $value) {
        if (in_array($key, $productsFields)) {
            $product[$key] = $value;
        }
        else {
            $this->errors[]= 'Products: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function setProductsDescriptionField(&$productsDescription, &$productsDescriptionFields, &$langCodes, $key, $value)
    {
        $langItem = $this->langItem($key);
        if (in_array($langItem['item'], $productsDescriptionFields)) {
            if (in_array($langItem['lang'], $langCodes)) {
                $productsDescription[$langItem['item']][$langItem['lang']] = $value;
            }
            else {
                $this->errors[]= 'ProductsDescription: \'' . $langItem['lang'] . '\' ist als Sprachcode nicht zugelassen.';                
            }
        }
        else {
            $this->errors[]= 'ProductsDescription: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function setAttributesField(&$attributes, &$poCsvNames, &$langCodes, $key, $action, $value)
    {
        $attribute = substr($key, 2 + strlen($action));
        $langItem = $this->langItem($attribute);
        if (in_array($langItem['item'], $poCsvNames)) {
            if (in_array($langItem['lang'], $langCodes)) {
                $attributes[$langItem['item']][$langItem['lang']] = ['action' => $action, 'value' => $value];
            }
            else {
                $this->errors[]= 'Attribut: \'' . $langItem['lang'] . '\' ist als Sprachcode nicht zugelassen.';               
            }
        }
        else {
            $this->errors[]= 'Attribut: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function langItem($key)
    {
        $pos = strpos($key, '.');
        return $pos !== false ? 
            [ 'lang' => substr($key, $pos + 1), 'item' => substr($key, 0, $pos)] : 
            [ 'lang' => 'de', 'item' => $key];
    }

    protected function getPoCsvNames($em)
    {
        $result = $em
            ->createQuery('
                SELECT pocn.productsOptionsCsvName 
                FROM ShopBundle:ProductsOptionsCsvName pocn
            ')
            ->getResult();
        $poCsvNames = array_map(function($productsOption)
        {
            return $productsOption['productsOptionsCsvName'];
        }, $result);
        return $poCsvNames;
    }

    protected function getLangCodes()
    {
        $langCodes = array_map(function($language)
        {
            return $language->getCode();
        }, $this->languages);
        return $langCodes;
    }
}
