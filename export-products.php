<?php
// start output buffering immediately
ob_start();

// disable debug output in XML feed
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// increase memory
ini_set('memory_limit','1024M');

define('DONOTCACHEPAGE', true);

// load WordPress
require_once dirname(__FILE__,4).'/wp-load.php';

/*
Clean any notices printed by plugins
like:
- wp-simple-rest-api-authentication
- wcfad
- WCPBC
*/
ob_clean();

/* FORCE DOWNLOAD */
header('Content-Type: application/xml; charset=utf-8');
header('Content-Disposition: attachment; filename="woocommerce-products.xml"');
header('Pragma: no-cache');
header('Expires: 0');

// create XML root
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><Products></Products>');

$page  = 1;
$limit = 200;

do {

    $products = wc_get_products([
        'limit' => $limit,
        'page'  => $page,
        'status'=> ['publish','draft']
    ]);

    foreach ($products as $product) {

        $node = $xml->addChild('Product');

        $node->addChild('Barcode', $product->get_meta('barcode'));
        $node->addChild('SKU', $product->get_sku());
        $node->addChild('Bezeichnung', htmlspecialchars($product->get_name()));

        // Beschreibung with CDATA
        $desc = $node->addChild('Beschreibung');
        $dom  = dom_import_simplexml($desc);
        $dom->appendChild(
            $dom->ownerDocument->createCDATASection($product->get_description())
        );

        $node->addChild('Zusatz','');

        $node->addChild('Listenpreis', $product->get_regular_price());

        /*
        -------------------
        Vertriebsgebiet Prices
        -------------------
        */

        $regions = ['AT','D'];

        foreach ($regions as $region) {

            $opt = $node->addChild('Option');

            $opt->addChild('Name','Vertriebsgebiet');
            $opt->addChild('Value',$region);

            $price = '';

            if($region == 'D'){
                $price = $product->get_meta('_regular_price_customer_de');
            }

            if($region == 'AT'){
                $price = $product->get_meta('_regular_price_customer_at');
            }

            $opt->addChild('Preis', $price);
        }

        /*
        -------------------
        Availability
        -------------------
        */

        $node->addChild('Available', $product->get_status() == 'publish' ? 'true' : 'false');
        $node->addChild('ShopAktiv', $product->is_in_stock() ? 'true' : 'false');

        /*
        -------------------
        Artist
        -------------------
        */

        $artist = $product->get_meta('kunstlername');

        if($artist){
            $parts = explode(" ", $artist);
            $node->addChild('Künstlervorname', $parts[0] ?? '');
            $node->addChild('Künstlernachname', $parts[1] ?? '');
        } else {
            $node->addChild('Künstlervorname','');
            $node->addChild('Künstlernachname','');
        }

        /*
        -------------------
        SEO
        -------------------
        */

        $node->addChild('Title', htmlspecialchars($product->get_name()));

        $tags = wp_get_post_terms(
            $product->get_id(),
            'product_tag',
            ['fields'=>'names']
        );

        $node->addChild('SEOKeywords', implode(",", $tags));

        $node->addChild(
            'SEODescription',
            htmlspecialchars($product->get_short_description())
        );

        /*
        -------------------
        Paper size
        -------------------
        */

        $size = $product->get_meta('papiergrose');

        if($size){
            preg_match('/(\d+).*?(\d+)/',$size,$m);
            $node->addChild('Papierbreite', $m[1] ?? 0);
            $node->addChild('Papierhöhe', $m[2] ?? 0);
        } else {
            $node->addChild('Papierbreite',0);
            $node->addChild('Papierhöhe',0);
        }

        /*
        -------------------
        VPE
        -------------------
        */

        $node->addChild('VPE', $product->get_meta('verpackungseinheit') ?: 6);

        /*
        -------------------
        Vertriebsgebiet
        -------------------
        */

        $node->addChild(
            'Vertriebsgebiet',
            $product->get_meta('vertriebsgebiet')
        );

        /*
        -------------------
        Category
        -------------------
        */

        $terms = wp_get_post_terms(
            $product->get_id(),
            'product_cat',
            ['fields'=>'names']
        );

        $node->addChild('Category', implode('_', $terms));

    }

    $page++;

} while(count($products) == $limit);


// output XML
echo $xml->asXML();
exit;