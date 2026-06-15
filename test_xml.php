<?php
/*
 Template Name: XML Testseite ACTETRE
 Template Post Type: page
*/
/* get_header(); */ ?>
<!-- main content-->
<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1); 
// Actetre Woocommerce API 
echo "Start: ".time();
$api_key = "ck_9ed0ba52cae1bc4dd934bc344cc8c3908c2ea4b9";
$api_secret = "cs_95f7227b9409b1fb3526d51359384fad51580f89";

$base_url = "https://actetre.enpr.de/";//$base_url."";

/* $args_parent = array(
  'taxonomy'   => 'product_cat',
  'hide_empty' => false,
  'meta_query' => array(
     array(
      'key'       => 'wawi_cat_id',
      'value'     => '6C80D992-5BF0-896D-938E-E2D4DFF37726',
      'compare'   => '='
     )
  )
);
$data_parent = get_terms($args_parent);

echo "<pre style='color:blue'>".var_export($data_parent,true)."</pre>";
die(); */
/* $args = array(
  'hide_empty' => false, // also retrieve terms which are not used yet
  'meta_query' => array(
    array(
       'key'       => $_GET['meta_key'],
       'value'     => $_GET['meta_value'],
       'compare'   => '='
    )
  ),
  'taxonomy'  => 'product_cat',
  ); */
  /* $terms = get_categories(  );

  $term_args = array(
    'taxonomy' => 'product_cat',
    );
  
  
  $terms = get_terms( $term_args );
  
  $term_ids = array();
  
  foreach( $terms as $term ) {
    $key = get_term_meta( $term->ID, $_GET['meta_key'], true );
    if( $key == $_GET['meta_value'] ) {
      // push the ID into the array
      $term_ids[] = $term->ID;
    }
  } */

/*   $args = array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'meta_query' => array(
       array(
        'key'       => 'wawi_cat_id',
        'value'     => '8420CDCF-D595-EF65-66E7-DFF9F98764DA',
        'compare'   => '='
       )
    )
  );
  $data = get_terms($args); */
  /* echo "<pre style='color:blue'>".var_export($data,true)."</pre>"; */
  ////////////////////////////////////
/* CURLAUTH_BASIC pw+nutzer (-u)
CURLOPT_HTTPHEADER  header (-H)
CURLOPT_URL (-X)
CURLOPT_POSTFIELDS data json encoded (-d)

curl -X POST https://example.com/wp-json/wc/v3/products \
    -u consumer_key:consumer_secret \
    -H "Content-Type: application/json" \
    -d '{
  "name": "Premium Quality",
  "type": "simple",
  "regular_price": "21.99",
  "description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.",
  "short_description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
  "categories": [
    {
      "id": 9
    },
    {
      "id": 14
    }
  ],
  "images": [
    {
      "src": "http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg"
    },
    {
      "src": "http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg"
    }
  ]
}'
*/
//////////////////


/* $url= $base_url."wp-json/wp/v2/product_cat/";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");

$test_str = curl_exec($ch);
curl_close($ch);

$existing_categories = json_decode($test_str,true);
//echo "<pre style='color:tomato;'>".var_export($existing_categories,true)."</pre>";

foreach($existing_categories as $category){
    // echo "wawi_cat_id: ".$category['acf']['wawi_cat_id']."<br>"; /
    if($category['acf']['wawi_cat_id'] == "8420CDCF-D595-EF65-66E7-DFF9F98764DA"){
     //  echo "<pre style='color:tomato;'>".var_export($category,true)."</pre>"; /
    }
} */
  
$wp_api_cat_endpoint = $base_url."wp-json/wp/v2/product_cat/";

$woo_api_cat_endpoint = $base_url."wp-json/wc/v3/products/categories/";
////////////////// test html laden
/* $img_path = '/html/body/main/div/div[1]/div[1]/div/img';
$dom2 = new DOMDocument;
$dom2->preserveWhiteSpace = false; */
/* echo "test1"; */
/* loading the file */
/* $dom2->Load('https://www.actetre.de/memoblock-19/'); */
/* echo "test2"; */
/* preparing the xpath for the dom doc */
/* $xpath2 = new DOMXPath($dom2); */
/* echo "test3";
$single_item2 = $xpath2->query($img_path);echo "<p style='color:hotpink;'>adsfasdf";
echo "test4";
echo "<pre style='color:tomato;'>".var_export($single_item2,true)."</pre>";
foreach ($single_item2 as $item) {        
  $nodes = $item->childNodes;
  echo "test5";
  foreach ($nodes as $node) {
    
   

    echo $node->nodeName. ": ";
 
    echo $node->nodeValue. "<br>";
    

    
  }

}
echo "</p>"; */

//////////////////
/* creating the DomDocument and set it clean */
$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;

/* loading the file 
https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
categories-level0.xml
*/
$dom->Load('https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml');//('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

/* preparing the xpath for the dom doc */
$xpath = new DOMXPath($dom);

$query = '//Categories//Category';
$items = $xpath->query($query);

/* going thru each item */
$rootNodeName = "Categories";
$index;
$level_0_index;
$tmp_level_0_index;
$tmp_level_0_name;

$level_1_index;
$tmp_level_1_index;
$tmp_level_1_name;

$level_2_index;
$tmp_level_2_index;
$tmp_level_2_name;

$level_0_name;
$level_1_name;
$level_2_name;
$tmpCats = [];
$catList = [];
$test = false;
$filenumber = 1;

$domdoc["level0"] = new DOMDocument('1.0', 'iso-8859-1');
$domdoc["level0"]->loadXML("<$rootNodeName/>");
$domdoc_index['level0']["filenumber"] = 0;

foreach ($items as $item) {        
    $nodes = $item->childNodes;
    
    
    foreach ($nodes as $node){
      //echo "<pre style='color:tomato;'>".var_export($nodes->item(2)->nodeValue,true)."</pre>";
      //echo "<pre style='color:tomato;'>".var_export($node->getElementsByTagName("Key"),true)."</pre>";
      if($node->nodeType != XML_ELEMENT_NODE)continue;
      
      if($node->nodeName == "Key"){
        $tmp_level_0_index = $node->nodeValue;
      }
        
      if($node->nodeName == "Id"){
        $tmp_level_0_name = $node->nodeValue;
      }
      
      

      if($node->nodeName == "Parent"){
        if( $node->nodeValue == 0){
          /* $domdoc[$tmp_level_0_index."_level0"] = new DOMDocument('1.0', 'iso-8859-1');
          $domdoc[$tmp_level_0_index."_level0"]->loadXML("<$rootNodeName/>"); */
          $tmpCats[$tmp_level_0_index] = [];
          $level_0_index = $tmp_level_0_index;
          $level_0_name = $tmp_level_0_name;
        }

        /* $catList[$level_0_index]['level_0'] = $level_0_index;  
        $catList[$level_0_index]['level_0_name'] = $level_0_name; */
      }/* elseif($node->nodeName == "Parent" && $node->nodeValue != 0){
      
      } */
      if(!isset($tmp_level_0_index) || !isset($level_0_index))continue;
      $catList[$tmp_level_0_index]['level_0'] = $level_0_index;  
      $catList[$tmp_level_0_index]['level_0_name'] = $level_0_name;  
      
      //if($node->nodeType == XML_ELEMENT_NODE)echo "NodeType : ".$node->nodeType." ".$node->nodeName."<br>";
    }
    //if($test == false)
      /* echo "<pre style='color:green;'>tmp_level1 index: ".var_export($catList,true)."</pre><br>---------------<br>"; */
    foreach ($tmpCats as $cat_id => $value){//$test = true;
      foreach ($nodes as $node){
        echo "<pre style='color:green;'>tmp_level1 index: ".var_export($node,true)."</pre><br>---------------<br>";
        if($node->nodeType != XML_ELEMENT_NODE)continue;
        if($node->nodeName == "Key"){
          $tmp_level_1_index = $node->nodeValue;
          
        }
        if($node->nodeName == "Id"){
          $tmp_level_1_name = $node->nodeValue;
          
        }
        if($node->nodeName == "Parent"){
          $tmp_level_1_parentname = $node->nodeValue;
          
        }

        //echo "<pre style='color:rebeccapurple;'>tmp_level1 index: ".var_export($tmp_level_1_index,true)."</pre><br>---------------<br>";
        //echo "<pre style='color:rebeccapurple;'>cat_id: ".$tmp_level_1_parentname."|".$tmp_level_1_name."=".var_export($cat_id,true)."</pre><br>---------------<br>";
        if($node->nodeName == "ParentKey"){
          $tmp_level_1_parentkey = $node->nodeValue;
          if($node->nodeValue == $cat_id){

            $tmp_level_1_parentkey = $tmp_level_1_index;
            $tmp_level_1_parentname = $tmp_level_1_name;
            
            $tmpCats[$cat_id][$tmp_level_1_index] =[];
            $domdoc[$tmp_level_1_index] = new DOMDocument('1.0', 'iso-8859-1');
            $domdoc[$tmp_level_1_index]->loadXML("<$rootNodeName/>");
            $domdoc_index[$tmp_level_1_index]["filenumber"]=$filenumber;
            echo "<pre style='color:rebeccapurple;'>cat_id: ".var_export($cat_id,true)."</pre><br>---------------<br>";
            $filenumber++;
          }else{
            $tmp_level_1_parentkey = $node->nodeValue;
          }
          
          /* if( $node->nodeValue != 0){
            
          } */
          /* 
          $catList[$cat_id]['level_1'] = $level_1_index;
          $catList[$cat_id]['level_1_name'] = $level_1_name; */
          /* echo "<pre style='color:rebeccapurple;'>".var_export($node->nodeName,true)."</pre><br>---------------<br>";
          echo "<pre style='color:rebeccapurple;'>".var_export($node->nodeValue,true)."</pre><br>---------------<br>";
          echo "<pre style='color:rebeccapurple;'>".var_export($cat_id,true)."</pre><br>---------------<br>"; */
          
        }
        echo "<pre style='color:rebeccapurple;'>cat_id: ".$tmp_level_1_parentkey."|".$tmp_level_1_index."=".var_export($cat_id,true)."</pre><br>---------------<br>";
        /* if($node->nodeName == "ParentKey"){
          if( $node->nodeValue == 0){
            $level_1_index = $tmp_level_1_index;
            $level_1_name = $tmp_level_1_name;
          }
        } */
        if(!isset($tmp_level_1_index) /* || !isset($level_1_index) */)continue;
        //echo "<pre style='color:rebeccapurple;'>continue 1</pre><br>---------------<br>";
        $level_1_index = $tmp_level_1_parentkey;
        $level_1_name = $tmp_level_1_parentname;
        if($tmp_level_1_index != $catList[$tmp_level_1_index]['level_0']){
          $catList[$tmp_level_1_index]['level_1'] = $level_1_index;  
          $catList[$tmp_level_1_index]['level_1_name'] = $level_1_name; 
        }else{
          continue;
          //echo "<pre style='color:rebeccapurple;'>continue </pre><br>---------------<br>";

        }
        
       /*  unset($tmp_level_1_index);
        unset($level_1_index); */

      }
    }

    foreach($tmpCats as $cat_id_lvl1 => $tmpCat){
      foreach($tmpCat as $cat_id_lvl2 => $value){
        foreach ($nodes as $node){
          if($node->nodeType != XML_ELEMENT_NODE)continue;
          if($node->nodeName == "Key"){
            $tmp_level_2_index = $node->nodeValue;
            
          }
          if($node->nodeName == "Id"){
            $tmp_level_2_name = $node->nodeValue;
            
          }
          if($node->nodeName == "ParentKey" && $node->nodeValue == $cat_id_lvl2){
            $tmpCats[$cat_id_lvl1][$cat_id_lvl2][$tmp_level_2_index] =["parents" => $cat_id_lvl1.",".$cat_id_lvl2];
            $level_2_index = $tmp_level_2_index;
            $level_2_name = $tmp_level_2_name;
            /* $catList[$cat_id_lvl1]['level_2'] = $level_2_index;
            $catList[$cat_id_lvl1]['level_2_name'] = $level_2_name; */
          } 
          if(!isset($tmp_level_2_index) || !isset($level_2_index))continue;
          $catList[$tmp_level_2_index]['level_2'] = $level_2_index;  
          $catList[$tmp_level_2_index]['level_2_name'] = $level_2_name; 
          unset($tmp_level_2_index);
          unset($level_2_index);
  
        }
      }
    }
   /*  foreach ($nodes as $node){
      

      if($node->nodeName == "Key")
        $level_1_index = $node->nodeValue;

      foreach ($tmpCats as $cat_id => $value){
        if($nodes->item(4) == $cat_id)
          $level_1_index = $node->nodeValue;
        
        $tmpCats[$cat_id][$level_1_index] = [];
      }
      
    } */
    
    
    
    
    //foreach();
    /* foreach ($nodes as $node) {
      if($node->nodeType != XML_ELEMENT_NODE)continue;   

      $tmpCats[$node->nodeName] = $node->nodeValue;

      if($node->nodeName == "Key"){
        $index = $node->nodeValue;
      }


      
    }
    $categories[$index] = $tmpCats; */
}
/* echo "count: ".count($catList);

echo "<pre style='color:rebeccapurple;'>".var_export($domdoc,true)."</pre><br>---------------<br>";
echo "<pre style='color:deeppink;'>".var_export($catList,true)."</pre><br>---------------<br>"; */
//echo "<pre style='color:deeppink;'>".var_export($tmpCats,true)."</pre><br>---------------<br>";


/////test split
$fileName = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml";
$original = new XMLReader;
$original->open($fileName);
$path_parts = pathinfo($fileName);
$filePrefix = __DIR__.'/'.$path_parts['filename'].'-';
$nextRecord = 0;
$splitCount = 100;
$rootNodeName = "Categories";
echo "doc root: ".$_SERVER['DOCUMENT_ROOT']."<br>";
echo "doc root: ".__DIR__."<br>";
$doc = new DOMDocument('1.0', 'iso-8859-1');
$doc->loadXML("<$rootNodeName/>");


/* echo "<pre style='color:orange;'>".var_export($domdoc,true)."</pre>";die(); */
while ($original->read() && $original->name !== 'Category');//{echo "<pre style='color:rebeccapurple;'>".$original->name.": ".var_export($original->readString(),true)."</pre>";}
while ($original->name === 'Category')
{
  echo "<pre style='color:rebeccapurple;'>".var_export($original->expand()->childNodes->item(0)/* ->getElementsByTagName('Category') */,true)."</pre>";
  echo "<pre style='color:rebeccapurple;'>".var_export($doc/* ->getElementsByTagName('Category') */,true)."</pre>";
    $children = $original->expand()->childNodes;
    

    foreach ($children as $child){
      if($child->nodeType == XML_ELEMENT_NODE){
        echo "<pre style='color:tomato;'>".var_export($child->nodeName,true)."</pre>";
        echo "<pre style='color:tomato;'>".var_export($child->nodeValue,true)."</pre>";

        if($child->nodeName == "Key"){
          /* echo "<pre style='color:green;'>".var_export($catList[$child->nodeValue],true)."</pre>"; */

          if(!isset($catList[$child->nodeValue]['level_1'])){
            $newNode = $domdoc["level0"]->importNode($original->expand(), true);
            $domdoc["level0"]->documentElement->appendChild($newNode);

            /* echo "<pre style='color:blue;'>".var_export($catList[$child->nodeValue],true)."</pre>"; */
          }else{
            if(isset($domdoc[$child->nodeValue])){
              echo "klappt<br>";
            }else{
              echo "klappt nicht. <br>";
            }
            $currKey = $catList[$child->nodeValue]['level_1'];
            $newNode = $domdoc[$currKey]->importNode($original->expand(), true);
            $domdoc[$currKey]->documentElement->appendChild($newNode);
            echo "<pre style='color:orange;'>".var_export($catList[$child->nodeValue],true)."</pre>";
          }
        }
       /*  echo "<pre style='color:tomato;'>".var_export($child->nodeType,true)."</pre>"; */
      }
      
    }
    
    foreach($domdoc as $key => $value){echo "<pre style='color:red;'>".var_export($key,true)."</pre>";
      $currentFileName = $filePrefix.$domdoc_index[$key]["filenumber"].".".$path_parts["extension"];
      echo "filename id: ".$currentFileName."<br>";
      $domdoc[$key]->save($currentFileName);
    }
    /* echo "<pre style='color:tomato;'>nextrecord:".var_export($nextRecord,true)."</pre>";
    $newNode = $doc->importNode($original->expand(), true);
    $doc->documentElement->appendChild($newNode);
    $nextRecord++;
    

    if ( $nextRecord % $splitCount == 0 )   {
        $nextFileName = $filePrefix."_x_".$nextRecord.".".$path_parts['extension'];
        echo "<p style='border:1px solid tomato;'>filename: ".$nextFileName."<br>";
        echo "filePrefix: ".$filePrefix."<br>";
        echo "nextRecord: ".$nextRecord."<br>";
        echo "path_parts: ".$path_parts['extension']."<br></p>";
        //$doc->save($nextFileName);
        $doc = new DOMDocument('1.0', 'iso-8859-1');
        $doc->loadXML("<$rootNodeName/>");
    } */
    $original->next('Category');
    //if($nextRecord > 105)break;
}
/* if ( $nextRecord % $splitCount != 0 )   {
    //$nextFileName = $filePrefix.$nextRecord.".".$path_parts['extension'];
    echo "<p style='border: 1px solid green'>filename: ".$nextFileName."<br>";
        echo "filePrefix: ".$filePrefix."<br>";
        echo "nextRecord: ".$nextRecord."<br>";
        echo "path_parts: ".$path_parts['extension']."<br></p>";
    //$doc->save($nextFileName);
} */
// Load XML and XSL
echo "test!";
foreach($domdoc_index as $fileIndex){
  echo "<pre style='color:lime;'>File Index:".var_export($fileIndex,true)."</pre>";
  /* creating the DomDocument and set it clean */
  $domPart = new DOMDocument;
  $domPart->preserveWhiteSpace = false;

  /* loading the file 
  https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
  categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
  categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
  categories-level0.xml
  */
  $domPart->Load('https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories-'.$fileIndex["filenumber"].'.xml');//('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

  /* preparing the xpath for the dom doc */
  $xpathPart = new DOMXPath($domPart);

  $queryPart = '//Categories//Category';
  $itemsPart = $xpathPart->query($queryPart);

  // XML komplett durchlaufen, Array mit allen Kategorien erstellen

  /* query just the attributes from the xml document */
  /* $query = '//Categories//Category';
  $items = $xpath->query($query); */
  
  unset($xpath);
  /* going thru each item */
  //$articles[] = [];
  $categories = [];
  $index;
  $tmpCats = [];
  foreach ($itemsPart as $item) {        
      $nodes = $item->childNodes;
      
      //$j= 0;
      foreach ($nodes as $node) {
        if($node->nodeName == "#text")continue;   
        //echo $node->nodeName. ": ";
        $tmpCats[$node->nodeName] = $node->nodeValue;

        if($node->nodeName == "Key"){
          $index = $node->nodeValue;
        }

        //$articles[$i][$node->nodeName] = $node->nodeValue;
        //echo $node->nodeValue. "<br>";//$j=0;
        
        
        //$j++;
        
      }
      $categories[$index] = $tmpCats;//echo "<br>Zähler: ".$index."<br>";
      //echo "<br>---------<br>";
      /* echo "ITEM: ".$item->nodeName."<br>";
      echo "ITEM: ".$item->nodeValue."<br>"; */
      /* if($i >10)break;
      $i++; */
  }/* echo "<pre style='color:gray'>".var_export($categories,true)."</pre>";
  echo "Anzahl Kategorien: ".count($categories); */
  $jsonCats = [];

  // Kategorie einfügen, falls noch nicht vorhanden

  $api_insert_url = $base_url."wp-json/wp/v2/product_cat/";
  foreach($categories as $cat_id => $cat){
    $jsonCats = [
      "name" => /* str_replace('"','###', */$cat["Category"]/* ) */,
      "acf" => ["wawi_cat_id" => $cat_id, "wawi_parent_id" => $cat["ParentKey"]],
      "lang" => "de"
    ];

    $json_Category = json_encode($jsonCats,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_insert_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_Category);
    
    
    $xmlstr = curl_exec($ch);
    curl_close($ch);
    $curl_response = json_decode($xmlstr,true);
    echo "<pre style='color:deeppink'>Response: ".var_export($curl_response,true)."</pre>";
    // Übersetzung einfügen
    $jsonCatsFR = [
      "name" => /* str_replace('"','###', */$cat["Category"]/* ) */,
      "lang" => "fr",
      "translations" => ["de" => $curl_response['id']]
    ];

    $json_CategoryFR = json_encode($jsonCatsFR,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    $chFR = curl_init();
    curl_setopt($chFR, CURLOPT_URL, $api_insert_url);
    curl_setopt($chFR, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($chFR, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($chFR, CURLOPT_POST, 1);
    curl_setopt($chFR, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($chFR, CURLOPT_USERPWD, "$api_key:$api_secret");
    curl_setopt($chFR, CURLOPT_POSTFIELDS, $json_CategoryFR);
    
    
    $xmlstrFR = curl_exec($chFR);
    curl_close($chFR);
    $curl_responseFR = json_decode($xmlstrFR,true);
    

    if(isset($curl_response['code']))
      echo "<pre style='color:deeppink'>".$cat["Category"].": ".var_export($curl_response['code'],true)."</pre>";
    // Update der Kategorie, falls bereits vorhanden
    if($curl_response["code"] == "term_exists"){
      $jsonCats['name'] = $jsonCats['name'];
      $id = $curl_response['data']['term_id'];
      $jsonCats['id'] = $id; 
      $json_Category_Update = json_encode($jsonCats,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
      

      $ch_update = curl_init();
      curl_setopt($ch_update, CURLOPT_URL, $api_insert_url.$id);
      curl_setopt($ch_update, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_update, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($ch_update, CURLOPT_POST, 1);
      curl_setopt($ch_update, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch_update, CURLOPT_USERPWD, "$api_key:$api_secret");
      curl_setopt($ch_update, CURLOPT_POSTFIELDS, $json_Category_Update);

      $xmlstr_update = curl_exec($ch_update);
      curl_close($ch_update);

      /* echo "Existiert bereits. ".$id."<br>"; */
    }

  /*   echo "<pre style='color:tomato'>".var_export(json_decode($xmlstr_update,true),true)."</pre>";
    echo "<pre style='color:gray'>".var_export(json_decode($xmlstr,true),true)."</pre>"; */
  }

  /* die(); */
  // Hierarchieverknüpfungen erstellen
  $search_url = $base_url."wp-json/wp/v2/product_cat_search/meta_search/?meta_key=wawi_cat_id&meta_value=";
  foreach ($categories as $id => $category){

    // Aktuelle Shop-ID der Kategorie mit Parent-ID laden
    $ch_get_cat = curl_init();
    curl_setopt($ch_get_cat, CURLOPT_URL, $search_url.$id);
    curl_setopt($ch_get_cat, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_get_cat, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_get_cat, CURLOPT_USERPWD, "$api_key:$api_secret");
    
    $json_current_category = curl_exec($ch_get_cat);
    curl_close($ch_get_cat);

    $reply = json_decode($json_current_category,true);
    $category_id = $reply['term_id'];
    $parent['parent'] = $reply['parent_term_id'];
    $parent_id = json_encode($parent,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    //Parent IDs einfügen
    $ch_update_parents = curl_init();
    curl_setopt($ch_update_parents, CURLOPT_URL, $api_insert_url.$category_id);
    curl_setopt($ch_update_parents, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_update_parents, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch_update_parents, CURLOPT_POST, 1);
    curl_setopt($ch_update_parents, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_update_parents, CURLOPT_USERPWD, "$api_key:$api_secret");
    curl_setopt($ch_update_parents, CURLOPT_POSTFIELDS, $parent_id);

    $xmlstr_update_parents = curl_exec($ch_update_parents);
    curl_close($ch_update_parents);
    // 
  /*   echo "<br>term id: ".$category_id."<br>";
    echo "<br>term id: ".$category_id."<br>";
    echo "<pre style='color:deeppink'>".var_export($parent_id,true)."</pre>";
    echo "<pre style='color:deeppink'>".var_export(json_decode($xmlstr_update_parents,true),true)."</pre>"; */
  }

}

die();
/* $xsl = new DOMDocument;
$xsl->load($xslstr);

$prop_total = $dom->getElementsByTagName('Category')->length + 100;

for($i=1; $i<=$prop_total; $i++){
  if ($i % 100 == 0) {         
    // Configure transformer
    $proc = new XSLTProcessor;
    $proc->importStyleSheet($xsl);

    // Binds loop variable to XSLT parameter
    $proc->setParameter('', 'splitnum', $i);

    // Transform XML source
    $newXML = new DOMDocument;
    $newXML = $proc->transformToXML($xml);
    
    // Output file
    file_put_contents('rentals_'.$i.'.xml', $newXML);
  }
} */


////////////


//////////////////

// Suche nach Wert in XML-Datei
/* $value_query = "//Categories//Category//Key[text()='A111510B-548D-4A5E-E958-6DB088001668']/parent::*";
$single_item = $xpath->query($value_query);echo "<p style='color:rebeccapurple;'>";

$single_category = [];
foreach ($single_item as $item) {        
  $nodes = $item->childNodes;
  
  foreach ($nodes as $node) {
    
    $single_category[$node->nodeName] = $node->nodeValue;

    echo $node->nodeName. ": ";
 
    echo $node->nodeValue. "<br>";
    

    
  }

} */
/* echo "</p><pre style='color:rebeccapurple;'>".var_export($single_category,true)."</pre>"; */




/* 
echo "<pre style='color:orange'>".var_export($jsonCats,true)."</pre>"; *///echo "<pre style='color:orange'>".var_export($jsonCats,true)."</pre>";
$jsonCats = /* str_replace('###', '\"', */wp_json_encode($jsonCats,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)/* ) */;

$test_cat = get_term_by("_wawi_cat_id","8420CDCF-D595-EF65-66E7-DFF9F98764DA" ,"product_cat");
/* echo "<pre style='color:blue'>".var_export($test_cat,true)."</pre>";
echo $jsonCats;
echo "<pre style='color:orange'>".var_export($jsonCats,true)."</pre>";
echo "<pre style='color:red'>".var_export($categories['6C80D992-5BF0-896D-938E-E2D4DFF37726'],true)."</pre>";
echo "<pre style='color:green'>".var_export($categories,true)."</pre>";

echo "base_url:".$base_url; */
// Kategorien einfügen curl aufruf
/* $api_insert_url = $base_url."wp-json/wp/v2/product_cat/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_insert_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$key:$secret");
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonCats);


$xmlstr = curl_exec($ch);
curl_close($ch);
echo "<pre style='color:green'>".var_export($xmlstr,true)."</pre>"; */
/* $args = array(
  'taxonomy'   => 'product_cat',
  'hide_empty' => false,
  'meta_query' => array(
     array(
      'key'       => "wawi_cat_id",
      'value'     => '6C80D992-5BF0-896D-938E-E2D4DFF37726',
      'compare'   => '='
     )
  )
);
$data = get_terms($args);
$data['test'] = get_field("wawi_cat_id",$data[0]); */
/* echo "<pre style='color:green'>".var_export($data,true)."</pre>"; */
echo "Ende: ".time();
/* get_footer(); */
?>