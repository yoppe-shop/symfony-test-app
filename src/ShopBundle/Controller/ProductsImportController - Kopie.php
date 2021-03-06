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

$csv = file_get_contents($this->get('kernel')->getRootDir() . DIR_SEP . ".." . DIR_SEP . "src" . DIR_SEP . "ShopBundle" . DIR_SEP . "Resources" . DIR_SEP . "testFiles" . DIR_SEP . "product_test.csv");
        $em = $this->getDoctrine()->getManager('mysql');
        $this->productsOptions($em);
        $this->getCsvProductData($em, $csv);
exit;
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

    protected function getCsvProductData($em, &$csv)
    {
        /**
        * Initialize error array
        */
        $this->errors = array();

        /**
        * Returns Arrays $product, $productsDescription, $productsImage, $attributes
        */
        $fieldArrays = array();

        /**
        * Replace '\r' by ''
        */
        $this->initializeCsvFile($csv);

        /**
        * Get an array of lines:
        */
        $lines = $this->getLines($csv);

        $titlesOfIndexes = $this->titlesOfIndexes($lines[0]);

        $numIndexes = count($titlesOfIndexes);

        /**
        * Create an array of the structure of the titles:
        */
        $structureArray = $this->createStructureArray($em, $titlesOfIndexes);

        if(!empty($this->errors))
        {
            print_r($this->errors);
            return;
        }
        unset($lines[0]);

        $products = array();
        $lineNo = 1;

        foreach($lines  as $line) {
            $products[]= $this->createProductsDataArray($em, $structureArray, $line, $numIndexes, $lineNo++);
        }
$utils = $this->get('debug')->pr($products, 5, false); exit;
        return $products;
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

    protected function createProductsDataArray($em, &$fields, &$line, $numIndexes, $lineNo)
    {
        $values = explode($this->csvFieldSep, $line);

        if (count($values) != $numIndexes) {
            $this->errors[] = 'Zeile ' . $lineNo . ': Die Anzahl der Werte stimmt nicht mit der Schlüsselanzahl überein.';
            return false;
        }
        $product = array();

        foreach ($fields['product'] as $index => $field) {
            if ($values[$index] != '') {
                $product[$field['key']] = $values[$index];
            }
        }
        foreach ($fields['productsDescription'] as $index => $field) {
            if ($values[$index] != '') {
                $productsDescription[$field['key']][$field['langId']] = $values[$index]; 
            }
        }
        foreach ($fields['attributes'] as $index => $field) {
            if ($values[$index] != '') {
                $attributes[$field['key']][$field['subKey']][$field['langId']] = $values[$index]; 
            }
        }
// Nur testweise hier schon zurückgeben:
return ['product' => $product, 'productsDescription' => $productsDescription, 'attributes' => $attributes];

        $product = array();
        $productsDescription = array();
        $attributes = array();
        $rawData = explode($this->csvFieldSep, $line);

        $this->productsFields = $this->productsFields($em);
        $this->productsDescriptionFields = $this->productsDescriptionFields($em);
        $this->productsOptions = $this->productsOptions($em);
        $this->languages = $this->languages($em);
        $poCsvNames = $this->getPoCsvNames($em);
        $langIds = $this->getLangIds();

        foreach($rawData as $index => $value)
        {
            if ($value != '') {
                if(substr($titleOfIndexes[$index], 0, 3) == 'a+.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langIds, $titleOfIndexes[$index], '+', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 2) == 'a.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langIds, $titleOfIndexes[$index], '', $value);
                }
                elseif(substr($titleOfIndexes[$index], 0, 3) == 'a-.') {
                    $this->setAttributesField($attributes, $poCsvNames, $langIds, $titleOfIndexes[$index], '-', $value);           
                }
                elseif(strpos($titleOfIndexes[$index], '.') !== false) {
                    $this->setProductsDescriptionField($productsDescription, $this->productsDescriptionFields, $langIds, $titleOfIndexes[$index], $value);
                }
                else {
                    $this->setProductsField($product, $this->productsFields, $titleOfIndexes[$index], $value);
                }
            }
        }
        return ['product' => $product, 'productsDescription' => $productsDescription, 'attributes' => $attributes];
    }

    protected function titlesOfIndexes($line0) {
        return explode($this->csvFieldSep, $line0);
    }

    protected function createStructureArray($em, $titleOfIndexes)
    {
        /**
        * Title of indexes:
        * ['model',
        * 'ean',
        * 'image', 
        * 'price',
        * 'weight',
        * 'status',
        * 'manufacturers_id',
        * 'manufacturers_model',
        * 'manufacturers_ean',
        * 'name.de',
        * 'description.de',
        * 'short_description.de',
        * 'a.farbe.de',
        * 'a.farbe.en',
        * 'a.material.de',
        * 'a.marke.de',
        * 'a.groesse.de',
        * 'a.gewicht.de',
        * 'a.einsatzart.de',
        * 'a.weitere_informationen.de',
        * 'a.gewicht_mit_verpackung.de']
        */

        /**
        * Three sub arrays for the structure array:
        */
        $product = array();
        $productsDescription = array();
        $attributes = array();

        /**
        * Following results as entity:
        */
        $this->productsFields = $this->productsFields($em);
        $this->productsDescriptionFields = $this->productsDescriptionFields($em);
        $this->productsOptions = $this->productsOptions($em);
        $this->languages = $this->languages($em);

        /**
        * Not the real products options name, but the option names for the CSV list 
        * ['farbe', 'material', ...]
        */
        $poCsvNames = $this->getPoCsvNames($em);

        /**
        * ['en' => 1, 'de' => 2]
        */
        $this->langIds = $this->getlangIds();

        foreach ($titleOfIndexes as $key => $fieldString) {
            if(substr($fieldString, 0, 3) == 'a+.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '+');
            }
            elseif(substr($fieldString, 0, 2) == 'a.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '');
            }
            elseif(substr($fieldString, 0, 3) == 'a-.') {
                $attributes[$key]= $this->createAttributesField($poCsvNames, $fieldString, '-');           
            }
            elseif(strpos($fieldString, '.') !== false) {
                $productsDescription[$key]= $this->createProductsDescriptionField($this->productsDescriptionFields, $fieldString);
            }
            else {
                $product[$key]=$this->createProductsField($this->productsFields, $fieldString);
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

    protected function createProductsField(&$productsFields, $key) {
        $product = array();
        if (in_array($key, $productsFields)) {
            $product = ['key' => $key, 'value' => ''];
        }
        else {
            $this->errors[]= 'Products: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $product;
    }

    protected function createProductsDescriptionField(&$productsDescriptionFields, $key)
    {
        $productsDescription = array();
        $langItem = $this->langItem($key);
        if (in_array($langItem['item'], $productsDescriptionFields)) {
            $productsDescription = ['key' => $langItem['item'], 'langId' => $langItem['lang'], 'value' => ''];
        }
        else {
            $this->errors[]= 'ProductsDescription: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $productsDescription;
    }

    protected function createAttributesField(&$poCsvNames, $key, $action)
    {
        /**
        * Initialize return array
        */
        $attributeArray = array();

        /**
        * Cut the beginning 'a+.' off:
        */
        $attribute = substr($key, 2 + strlen($action));

        /**
        * Make an array $langKeyItem = ['lang' => <langCode>, 'item' => <attributeName>]
        */
        $langKeyItem = $this->langKeyItem($attribute);
        if (in_array($langKeyItem['item'], $poCsvNames)) {
            $attributeArray = ['key' => $langKeyItem['item'], 'subKey' => $langKeyItem['subKey'], 'langId' => $langKeyItem['lang'], 'action' => $action, 'value' => ''];
        }
        else {
            $this->errors[]= 'Attribut: \'' . $langItem['item'] . '\' ist als Schlüssel nicht zugelassen.';
        }
        return $attributeArray;
    }

    protected function setProductsField(&$product, &$productsFields, $key, $value) {
        if (in_array($key, $productsFields)) {
            $product[$key] = $value;
        }
        else {
            $this->errors[]= 'Products: \'' . $key . '\' ist als Schlüssel nicht zugelassen.';
        }
    }

    protected function setProductsDescriptionField(&$productsDescriptionFields, $key) {
        $langItem = $this->langItem($key);
        if (in_array($langItem['item'], $productsDescriptionFields)) {
            if (in_array($langItem['lang'], $langIds)) {
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

    protected function setAttributesField(&$attributes, &$poCsvNames, &$langIds, $key, $action, $value)
    {
        $attribute = substr($key, 2 + strlen($action));
        $langItem = $this->langItem($attribute);
        if (in_array($langItem['item'], $poCsvNames)) {
            if (in_array($langItem['lang'], $langIds)) {
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
        if ($pos !== false) {
            if (!isset($this->langIds[substr($key, $pos + 1)])) {
                $this->errors[] = 'Die Sprache mit dem Code \'' . substr($key, $pos + 1) . '\' gibt es nicht.';
            }
            else {
                return [ 'lang' => $this->langIds[substr($key, $pos + 1)], 'item' => substr($key, 0, $pos)];
            }
        }
        return [ 'lang' => '2', 'item' => $key];
    }

    protected function langKeyItem($key)
    {
        if (substr_count($key, '.') > 1) {
            $splitKey = explode('.', $key);
            $result = $this->langItem($splitKey[0] . '.' . $splitKey[2]);
            return array_merge($result, ['subKey' => $splitKey[1]]);
        }
        else {
            return array_merge($this->langItem($key), ['subKey' => '0']);
        }
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

    protected function getLangIds()
    {
        $langIds = array();
        foreach ($this->languages as $language) {
            $langIds[$language->getCode()] = $language->getLanguagesId();
        }
        return $langIds;
    }
}
