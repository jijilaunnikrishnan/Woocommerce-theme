<?php
/*
 Template Name: XML Filesplitter ACTETRE
 Template Post Type: page
*/
$parent_id =0;
$args = array(
  'taxonomy'     => 'product_cat',
  'hide_empty'   => false,
  //'parent'       => $parent_id,
  'meta_query'   => [
    'key' => 'wawi_parent_id',
    'value' => '8420CDCF-D595-EF65-66E7-DFF9F98764DA',
    'compare' => "="
  ]
);

$categories = get_categories($args);
echo "<pre style='color:green;'>".var_export($categories,true)."</pre><br>---------------<br>";
foreach ($categories as $category) {
  /* echo $category->name." test2<br>"; */
  if(in_array($category->name,['Grußkarten', 'Kunstdrucke','Originalgrafik','Papeterie und Sonstiges']))echo $category->name." test<br>"; // Recursive call

}
die();
/* creating the DomDocument and set it clean */
$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;

/* loading the file 
https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
categories-level0.xml
*/
$dom->Load('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

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

      if($node->nodeType != XML_ELEMENT_NODE)continue;
      
      if($node->nodeName == "Key"){
        $tmp_level_0_index = $node->nodeValue;
      }
        
      if($node->nodeName == "Id"){
        $tmp_level_0_name = $node->nodeValue;
      }
      
      

      if($node->nodeName == "Parent"){
        if( $node->nodeValue == 0){

          $tmpCats[$tmp_level_0_index] = [];
          $level_0_index = $tmp_level_0_index;
          $level_0_name = $tmp_level_0_name;
        }

      }
      if(!isset($tmp_level_0_index) || !isset($level_0_index))continue;
      $catList[$tmp_level_0_index]['level_0'] = $level_0_index;  
      $catList[$tmp_level_0_index]['level_0_name'] = $level_0_name;  
      
    }
    
    foreach ($tmpCats as $cat_id => $value){//$test = true;
      foreach ($nodes as $node){
        
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


        if($node->nodeName == "ParentKey"){
          $tmp_level_1_parentkey = $node->nodeValue;
          if($node->nodeValue == $cat_id){

            $tmp_level_1_parentkey = $tmp_level_1_index;
            $tmp_level_1_parentname = $tmp_level_1_name;
            
            $tmpCats[$cat_id][$tmp_level_1_index] =[];
            $domdoc[$tmp_level_1_index] = new DOMDocument('1.0', 'iso-8859-1');
            $domdoc[$tmp_level_1_index]->loadXML("<$rootNodeName/>");
            $domdoc_index[$tmp_level_1_index]["filenumber"]=$filenumber;
            
            $filenumber++;
          }else{
            $tmp_level_1_parentkey = $node->nodeValue;
          }
          
        }
       

        if(!isset($tmp_level_1_index) )continue;

        $level_1_index = $tmp_level_1_parentkey;
        $level_1_name = $tmp_level_1_parentname;
        if($tmp_level_1_index != $catList[$tmp_level_1_index]['level_0']){
          $catList[$tmp_level_1_index]['level_1'] = $level_1_index;  
          $catList[$tmp_level_1_index]['level_1_name'] = $level_1_name; 
        }else{
          continue;

        }
        


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

          } 
          if(!isset($tmp_level_2_index) || !isset($level_2_index))continue;
          $catList[$tmp_level_2_index]['level_2'] = $level_2_index;  
          $catList[$tmp_level_2_index]['level_2_name'] = $level_2_name; 
          unset($tmp_level_2_index);
          unset($level_2_index);
  
        }
      }
    }
 
}


// Aufteilen der XML-Datei nach Kategoriehierarchien

$fileName = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml";//"https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml";

$original = new XMLReader;
$original->open($fileName);
$path_parts = pathinfo($fileName);
$filePrefix = __DIR__.'/'.$path_parts['filename'].'-';
$nextRecord = 0;
$rootNodeName = "Categories";
$doc = new DOMDocument('1.0', 'iso-8859-1');
$doc->loadXML("<$rootNodeName/>");



while ($original->read() && $original->name !== 'Category');
while ($original->name === 'Category')
{

    $children = $original->expand()->childNodes;
    

    foreach ($children as $child){
      if($child->nodeType == XML_ELEMENT_NODE){

        if($child->nodeName == "Key"){

          if(!isset($catList[$child->nodeValue]['level_1'])){
            $newNode = $domdoc["level0"]->importNode($original->expand(), true);
            $domdoc["level0"]->documentElement->appendChild($newNode);

  
          }else{
            $currKey = $catList[$child->nodeValue]['level_1'];
            $newNode = $domdoc[$currKey]->importNode($original->expand(), true);
            $domdoc[$currKey]->documentElement->appendChild($newNode);
            
          }
        }

      }
      
    }
    
    foreach($domdoc as $key => $value){
      $currentFileName = __DIR__."/Category XML Files/".$filePrefix.$domdoc_index[$key]["filenumber"].".".$path_parts["extension"];
      echo "<pre style='color:green;'>tmp_level1 index: ".var_export($currentFileName,true)."</pre><br>---------------<br>";
      //$domdoc[$key]->save($currentFileName);
    }

    $original->next('Category');
    
}