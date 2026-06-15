<?php
/*
 Template Name: XML Testseite Kunden ACTETRE
 Template Post Type: page
*/
$fileName = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/customers.xml";
$original = new XMLReader;
$original->open($fileName);
$path_parts = pathinfo($fileName);
$filePrefix = __DIR__.'/Customer Files/'.$path_parts['filename'].'-';
$nextRecord = 0;
$splitCount = 100;
$rootNodeName = "Customers";
/* echo "doc root: ".$_SERVER['DOCUMENT_ROOT']."<br>";
echo "doc root: ".__DIR__."<br>"; */
$doc = new DOMDocument('1.0', 'iso-8859-1');
$doc->loadXML("<$rootNodeName/>");
while ($original->read() && $original->name !== 'Customer');//{echo "<pre style='color:rebeccapurple;'>".$original->name.": ".var_export($original->readString(),true)."</pre>";}
while ($original->name === 'Customer')
{
  //echo "<pre style='color:rebeccapurple;'>".var_export($original->expand()->childNodes->item(0)/* ->getElementsByTagName('Category') */,true)."</pre>";
  //echo "<pre style='color:rebeccapurple;'>".var_export($doc/* ->getElementsByTagName('Category') */,true)."</pre>";
    $children = $original->expand()->childNodes;

    foreach ($children as $child){
      if($child->nodeType == XML_ELEMENT_NODE){
        /* echo "<pre style='color:tomato;'>".var_export($child->nodeName,true)."</pre>";
        echo "<pre style='color:tomato;'>".var_export($child->nodeValue,true)."</pre>"; */
       /*  echo "<pre style='color:tomato;'>".var_export($child->nodeType,true)."</pre>"; */
      }
      
    }
    /* echo "<pre style='color:tomato;'>nextrecord:".var_export($nextRecord,true)."</pre>"; */
    $newNode = $doc->importNode($original->expand(), true);
    $doc->documentElement->appendChild($newNode);
    $nextRecord++;
    

    if ( $nextRecord % $splitCount == 0 )   {
        $nextFileName = $filePrefix."_x_".$nextRecord.".".$path_parts['extension'];
        echo "<p style='border:1px solid tomato;'>filename: ".$nextFileName."<br>";
        echo "filePrefix: ".$filePrefix."<br>";
        echo "nextRecord: ".$nextRecord."<br>";
        echo "path_parts: ".$path_parts['extension']."<br></p>";
        $fileIndex[] = $nextRecord;
        $doc->save($nextFileName);
        $doc = new DOMDocument('1.0', 'iso-8859-1');
        $doc->loadXML("<$rootNodeName/>");
    }
    $original->next('Customer');
    //if($nextRecord > 105)break;
}
if ( $nextRecord % $splitCount != 0 )   {
    //$nextFileName = $filePrefix.$nextRecord.".".$path_parts['extension'];
    echo "<p style='border: 1px solid green'>filename: ".$nextFileName."<br>";
        echo "filePrefix: ".$filePrefix."<br>";
        echo "nextRecord: ".$nextRecord."<br>";
        echo "path_parts: ".$path_parts['extension']."<br></p>";
        $fileIndex[] = $nextRecord;
    $doc->save($nextFileName);
}