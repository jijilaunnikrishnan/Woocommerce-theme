<?php
/*
 Template Name: XML Testseite Artikel ACTETRE
 Template Post Type: page
*/
/* get_header(); */ ?>
<!-- main content-->
<?php
/* phpinfo();die(); */
set_time_limit(-1);
ini_set('fastcgi_read_timeout', '30');
ini_set('memory_limit', '1024M');
$api_key = "ck_9ed0ba52cae1bc4dd934bc344cc8c3908c2ea4b9";
$api_secret = "cs_95f7227b9409b1fb3526d51359384fad51580f89";

$base_url = "https://actetre.enpr.de/";

$fileIndex = [];
/////test split
$url = $base_url."wp-json/wp/v2/product_cat_search/search_by_name/?category_name=";//.urlencode($splitString);
$ch_get_cat = curl_init();
curl_setopt($ch_get_cat, CURLOPT_URL, $url);
curl_setopt($ch_get_cat, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_get_cat, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch_get_cat, CURLOPT_USERPWD, "$api_key:$api_secret");

$json_current_category = curl_exec($ch_get_cat);
curl_close($ch_get_cat);

$all_cats = json_decode($json_current_category,true);

//echo "<pre style='color:rebeccapurple;'>".var_export($all_cats,true)."</pre>";
//die();
//echo "<pre style='color:rebeccapurple;'>".var_export($all_cats,true)."</pre>";
//die();

/* function get_taxonomy_hierarchy( $taxonomy, $parent = 0 ) {
	// only 1 taxonomy
	$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;

	// get all direct decendants of the $parent
	$terms = get_terms( $taxonomy, array( 'hide_empty' => false,'parent' => $parent ) );
  
	// prepare a new array.  these are the children of $parent
	// we'll ultimately copy all the $terms into this new array, but only after they
	// find their own children
	$children = array();
$children2 = [];
	// go through all the direct decendants of $parent, and gather their children
	foreach ( $terms as $term ){
		// recurse to get the direct decendants of "this" term
		$term->children = get_taxonomy_hierarchy( $taxonomy, $term->term_id );
    $children2[$term->name] = get_taxonomy_hierarchy( $taxonomy, $term->term_id );
    $children2[$term->name]['id'] = $term->term_id;
		// add the term to our new array
    //echo $term->name."<br>";
		$children[ $term->name ] = (array)$term;
	}

	// send the results back to the caller
	return (array)$children2;
} */
$cattest = 'Grußkarten,Grußkarten_Postkarten "Christmas",Grußkarten_Postkarten "Christmas"_Lali';
$arrtest = explode("_",$cattest);
/* echo "<pre style='color:rebeccapurple;'>".var_export($arrtest,true)."</pre>";
echo strpos($cattest,",")."<br>";
$first = strpos($cattest,",")+1;
$second = substr($cattest,$first);
echo strpos($second."_",",");
$third = strpos($second."_",",")+1;
echo "test: ".substr($second,$third)."<br>";
//echo substr($cattest,23+1)."<br>";

echo substr($cattest,0,$length_first)."<br>"; */



//$mid = substr($tmp_mid_string,$length_first+1,$length_second);

/* $length_first = strpos($cattest,",")+1; //23
$tmp_mid_string = substr($cattest,$length_first);
$length_second = strpos($tmp_mid_string."_",",")+1;//33

$last_string_length = $length_first+$length_second;
$last_string = substr($cattest,$last_string_length)."<br>";
$arr_last_string = explode("_",$last_string);
echo "<pre style='color:tomato;'>".var_export($arr_last_string,true)."</pre>";

echo substr($cattest,24,33)."<br>";
echo substr($cattest,24+34)."<br>";


$tmpProducts2["categories"][0]['id'] = $all_cats[$arr_last_string[0]]['id'];
$tmpProducts2["categories"][1]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]]['id'];
$tmpProducts2["categories"][2]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]][str_replace(["<br>",";"],["",","],$arr_last_string[2])]['id'];

echo "<pre style='color:rebeccapurple;'>".var_export($all_cats[$arr_last_string[0]][$arr_last_string[1]],true)."</pre>";
//strrpos($category[$key2],"_") ? substr($category[$key2],strrpos($category[$key2],"_")+1) : $category[$key2];
//echo "<pre style='color:rebeccapurple;'>".var_export(get_taxonomy_hierarchy('product_cat'),true)."</pre>";
//die();
echo "<pre style='color:rebeccapurple;'>".var_export($fileIndex,true)."</pre>"; */


$fileIndex = array (
  0 => 100,
  /* 1 => 200,
  2 => 300,
  3 => 400,
  4 => 500,
  5 => 600,
  6 => 700,
  7 => 800,
  8 => 900,
  9 => 1000,
  10 => 1100,
  11 => 1200,
  12 => 1300,
  13 => 1400,
  14 => 1500,
  15 => 1600,
  16 => 1700,
  17 => 1800,
  18 => 1900,
  19 => 2000,
  20 => 2100,
  21 => 2200,
  22 => 2300,
  23 => 2400,
  24 => 2500,
  25 => 2600,
  26 => 2700,
  27 => 2800,
  28 => 2900,
  29 => 3000,
  30 => 3100,
  31 => 3200,
  32 => 3300,
  33 => 3400,
  34 => 3500,
  35 => 3600,
  36 => 3700,
  37 => 3800,
  38 => 3900,
  39 => 4000,
  40 => 4100,
  41 => 4200,
  42 => 4300,
  43 => 4400,
  44 => 4500,
  45 => 4600,
  46 => 4700,
  47 => 4800,
  48 => 4900,
  49 => 5000,
  50 => 5100,
  51 => 5200,
  52 => 5300,
  53 => 5400,
  54 => 5500,
  55 => 5600,
  56 => 5700,
  57 => 5800,
  58 => 5900,
  59 => 6000,
  60 => 6100,
  61 => 6200,
  62 => 6300,
  63 => 6400,
  64 => 6500,
  65 => 6600,
  66 => 6700,
  67 => 6800,
  68 => 6900,
  69 => 7000,
  70 => 7100,
  71 => 7200,
  72 => 7300,
  73 => 7400,
  74 => 7500,
  75 => 7600,
  76 => 7700,
  77 => 7800,
  78 => 7900,
  79 => 8000,
  80 => 8100,
  81 => 8200,
  82 => 8300,
  83 => 8400,
  84 => 8500,
  85 => 8600,
  86 => 8700,
  87 => 8800,
  88 => 8899, */
);
/* $test2 = 'Grußkarten,Grußkarten_Postkarten "Everyday",Grußkarten_Postkarten "Everyday"_Spicy Hill';
$test3 = explode(",",$test2);
echo "<pre style='color:rebeccapurple;'>".var_export($test3,true)."</pre>";
echo "<pre style='color:rebeccapurple;'>".var_export(strrpos($test3[2],"_"),true)."</pre>";
echo "<pre style='color:rebeccapurple;'>".var_export(substr($test3[2],strrpos($test3[2],"_")+1),true)."</pre>"; */
/* foreach($test3 as $key => $value){

} */
foreach($fileIndex as $key => $value){
  //$value = 100;
  /* echo "<pre style='color:lime;'>File Index:".var_export('https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/articles-_x_'.$value.'.xml',true)."</pre>"; */
  /* creating the DomDocument and set it clean */
  $domPart = new DOMDocument;
  $domPart->preserveWhiteSpace = false;

  /* loading the file 
  https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
  categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
  categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
  categories-level0.xml
  */
  $domPart->Load('https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/articles-_x_'.$value.'.xml');//('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

  /* preparing the xpath for the dom doc */
  $xpathPart = new DOMXPath($domPart);

  $queryPart = '//Products//Product';
  $itemsPart = $xpathPart->query($queryPart);

  // XML komplett durchlaufen, Array mit allen Kategorien erstellen

  /* query just the attributes from the xml document */
  /* $query = '//Categories//Category';
  $items = $xpath->query($query); */
  
  unset($xpathPart);
  unset($domPart);
  /* going thru each item */
  //$articles[] = [];
  $search_cat_url = $base_url."wp-json/wp/v2/product_cat_search/search_by_name/?category_name=";
  $categories = [];
  $products = [];
  $index;
  $tmpProducts = [];
  $tmpProducts2 = [];
  $tmpProductsDE = [];
  $tmpProductsFR = [];
  $vg_preise = [];
  $i=0;$x=0;$option_index = 0;
  $strCSV = "\"Artikelnummer\";\"Name\";\"Beschreibung Original\";\"Beschreibung Deutsch\";\"Beschreibung Englisch\";Beschreibung Französisch\"\r\n";

  foreach ($itemsPart as $item) {        
      $nodes = $item->childNodes;
      
      //$j= 0;
      foreach ($nodes as $node) {
       /*  echo "node: ".$node->nodeName." | ".$node->nodeValue."<br>"; */
        if($node->nodeName == "#text")continue;   
        
        $tmpCats[$node->nodeName] = $node->nodeValue;

        if($node->nodeName == "Key"){
          $index = $node->nodeValue;
        }


        if($node->nodeName == "SKU"){
          $tmpProducts["sku"] = $node->nodeValue;
          //$tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue).".jpg";
          //if(file_exists(__DIR__.'/'."import_error.txt"))echo __DIR__.'/'."import_error.txt existiert";
          if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue).".jpg")){
            $tmp = "124";
            //echo "<p style='color:green' >File existiert:"."https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue).".jpg</p>";
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue).".jpg";
          }
          else if(file_exists(__DIR__."/Actetre Bilder/".str_replace("fr","",strtolower($node->nodeValue)).".jpg")){
            $tmp = "124";//echo "<p style='color:lime' >File existiert nicht:".__DIR__."/Actetre Bilder/".str_replace("fr","",strtolower($node->nodeValue)).".jpg</p>";
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".str_replace("fr","",strtolower($node->nodeValue)).".jpg";
          }else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."fr.jpg")){
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue)."fr.jpg";
            $tmp = "124";//echo "<p style='color:blue' >File existiert nicht:".__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."fr.jpg</p>";
          }else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."de.jpg")){
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue)."de.jpg";
            $tmp = "124";//echo "<p style='color:rebeccapurple' >File existiert nicht:".__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."de.jpg</p>";
          }else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."aa.jpg")){
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue)."aa.jpg";
            $tmp = "124";//echo "<p style='color:aqua' >File existiert nicht:".__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."aa.jpg</p>";
          }else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."-a5.jpg")){
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue)."-a5.jpg";
            $tmp = "124";//echo "<p style='color:yellow' >File existiert nicht:".__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."-a5.jpg</p>";
          }else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."_.jpg")){
            $tmpProducts["images"][]['src'] = "https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue)."_.jpg";
            $tmp = "124";//echo "<p style='color:orange' >File existiert nicht:".__DIR__."/Actetre Bilder/".strtolower($node->nodeValue)."_.jpg</p>";
          }else{
            unset($tmpProducts["images"]);
            //echo "<p style='color:red' >File existiert nicht:"."https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Actetre%20Bilder/".strtolower($node->nodeValue).".jpg</p>";
          }
          $strCSV .= "\"".$node->nodeValue."\";";
          /* echo "node: ".$node->nodeName." | ".$node->nodeValue."<br>"; */
        }
        if($node->nodeName == "Bezeichnung"){
          $tmpProducts["name"] = $node->nodeValue;
          $tmpProducts["type"] = "simple";
          $strCSV .= "\"".$node->nodeValue."\";";
        }
        if($node->nodeName == "Beschreibung"){
          $tmpProducts["description"] = $node->nodeValue;
          $tmpProducts["short_description"] = $node->nodeValue;
          $strCSV .= "\"".$node->nodeValue."\";;;\r\n";
        }
        if($node->nodeName == "Listenpreis"){
          $tmpProducts["regular_price"] = str_replace(",",".",$node->nodeValue);
          $tmpProducts['meta_data'][8] = [
            "key" => "wcfad_original_price",
            "value" => $tmpProducts["regular_price"]
          ];
        }

        // Vertriebsgebiete Preise
        

        if($node->nodeName == "Option"){
          $vertriebsgebiete = $node->childNodes;
          
          foreach ($vertriebsgebiete as $gebiet){
            $vg_preise[$option_index]["produktname"] = $tmpProducts["name"];
            $vg_preise[$option_index]["nummer"] = $tmpProducts["sku"];
            if($gebiet->nodeName == "Value"){
              $vg_preise[$option_index]["Vertriebsgebiet"] = $gebiet->nodeValue; 
            }
            if($gebiet->nodeName == "Preis"){
              $vg_preise[$option_index]["Preis"] = $gebiet->nodeValue;
            }
            
          }
          if($vg_preise[$option_index]["Vertriebsgebiet"] == "D"){
            $tmpProducts['meta_data'][0] = [
              "key" => "_regular_price_customer_de",
              "value" => str_replace(",",".",$vg_preise[$option_index]["Preis"])
            ];
            $tmpProducts['meta_data'][8] = [
              "key" => "_regular_price_administrator",
              "value" => str_replace(",",".",$vg_preise[$option_index]["Preis"])
            ];
          }elseif($vg_preise[$option_index]["Vertriebsgebiet"] == "FR"){
            $tmpProducts['meta_data'][1] = [
              "key" => "_regular_price_customer_fr",
              "value" => str_replace(",",".",$vg_preise[$option_index]["Preis"])
            ];
          }
          
            
          
          
        }
        $option_index++;


        if($node->nodeName == "VPE"){
          $tmpProducts['meta_data'][2] = [
            "key" => "verpackungseinheit",
            "value" => $node->nodeValue
          ];
        }
        if($node->nodeName == "Vertriebsgebiet"){
          $tmpProducts['meta_data'][3] = [
            "key" => "vertriebsgebiet",
            "value" => $node->nodeValue == "D" ? "DE" : $node->nodeValue
          ];
        }
        if($node->nodeName == "Category"){

          $cat = $node->nodeValue;
          $length_first = strpos($cat,",")+1; //23
          $tmp_mid_string = substr($cat,$length_first);
          $length_second = strpos($tmp_mid_string."_",",")+1;//33
          
          $last_string_length = $length_first+$length_second;
          $last_string = substr($cat,$last_string_length)."<br>";
          $arr_last_string = explode("_",$last_string);

          $tmpProductsDE["categories"][0]['id'] = $all_cats[$arr_last_string[0]]['id'];
          $tmpProductsDE["categories"][1]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]]['id'];
          $tmpProductsDE["categories"][2]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]][str_replace(["<br>",";"],["",","],$arr_last_string[2])]['id'];


          $tmpProductsFR["categories"][0]['id'] = $all_cats[$arr_last_string[0]]['translations']['fr'];
          $tmpProductsFR["categories"][1]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]]['translations']['fr'];
          $tmpProductsFR["categories"][2]['id'] = $all_cats[$arr_last_string[0]][$arr_last_string[1]][str_replace(["<br>",";"],["",","],$arr_last_string[2])]['translations']['fr'];
          /* if(trim($arr_last_string[2]) == trim('Spicy Hill')) echo "ja";
          else echo "nein";
          echo var_dump('Spicy Hill');
          echo var_dump(str_replace("<br>","",$arr_last_string[2]));
          echo "<pre style='color:deeppink'>".var_export($arr_last_string[2],true)."</pre>";
          echo "<pre style='color:gray'>".var_export($all_cats[$arr_last_string[0]][$arr_last_string[1]][str_replace("<br>","",$arr_last_string[2])],true)."</pre>"; */
          /* $category = explode(",", $node->nodeValue);
          foreach($category as $key2 => $value2){
            $splitString = strrpos($category[$key2],"_") ? substr($category[$key2],strrpos($category[$key2],"_")+1) : $category[$key2];
            $categories[] = $splitString;

//echo "test567: ".$splitString."<br>";
$url = $base_url."wp-json/wp/v2/product_cat_search/search_by_name/?category_name=".urlencode($splitString);
            $ch_get_cat = curl_init();
            curl_setopt($ch_get_cat, CURLOPT_URL, $url);
            curl_setopt($ch_get_cat, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch_get_cat, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch_get_cat, CURLOPT_USERPWD, "$api_key:$api_secret");
            
            $json_current_category = curl_exec($ch_get_cat);
            curl_close($ch_get_cat);
            //echo "<pre style='color:rebeccapurple;'>test1234 ".var_export(json_decode($json_current_category)->term_id,true)."</pre>";
            $tmpProducts["categories"][]['id'] = json_decode($json_current_category)->term_id;
          } */
          /* $categories = explode(",", $node->nodeValue);
          $splitString = substr($categories[1],strrpos($categories[1],"_")+1); */


          //unset($categories);


/* echo "<pre style='color:rebeccapurple;'>test123 ".var_export($categories,true)."</pre>";
echo "<pre style='color:red;'>test123 ".var_export($tmpProducts,true)."</pre>"; */
          
        }

        if($node->nodeName == "Available"){
          if($node->nodeValue == "true"){
            $tmpProducts["stock_status"] = "instock";
          }else{
            $tmpProducts["stock_status"] = "outofstock";
          }
            
        }
        if($node->nodeName == "ShopAktiv"){
          if($node->nodeValue == "true"){
            $tmpProducts["stock_status"] = "instock";
          }else{
            $tmpProducts["stock_status"] = "onbackorder";
          }
          
        }
        //$i++;
        //unset($categories);
        /* echo $node->nodeName. ": <br>";
        echo $node->nodeValue. "<br>"; */
        //$articles[$i][$node->nodeName] = $node->nodeValue;
        //echo $node->nodeValue. "<br>";//$j=0;
        
        $tmpProducts['meta_data'][4] = [
          "key" => "beschreibung_deutsch",
          "value" => ""
        ];
        $tmpProducts['meta_data'][5] = [
          "key" => "beschreibung_franzosisch",
          "value" => ""
        ];
        $tmpProducts['meta_data'][6] = [
          "key" => "beschreibung_englisch",
          "value" => ""
        ];
        $tmpProducts['meta_data'][7] = [
          "key" => "_de_price_method",
          "value" => "manual"
        ];
        
        
        //$j++;
        
      }

      //echo "<pre style='color:red'>".var_export($tmpProducts,true)."</pre>";
      $tmpProducts2['de'] = $tmpProducts;
      $tmpProducts2['fr'] = $tmpProducts;
      $tmpProducts2['de']['categories'] = $tmpProductsDE['categories'];
      $tmpProducts2['fr']['categories'] = $tmpProductsFR['categories'];
      $tmpProducts2['de']['lang'] = "de";
      $tmpProducts2['fr']['lang'] = "fr";
      
      $Products[] = $tmpProducts2;
      unset($tmpProducts);
      /* echo "<pre style='color:green;'>test123 ".var_export(json_encode($Products,JSON_PRETTY_PRINT),true)."</pre>";
      echo "<pre style='color:tomato;'>test123 ".var_export($Products,true)."</pre>"; */
      
      //echo "<br>---------<br>";
      /* echo "ITEM: ".$item->nodeName."<br>";
      echo "ITEM: ".$item->nodeValue."<br>"; */
      /* if($i >10)break;
      $i++; */
  }
  //echo "<pre style='color:deeppink'>".var_export($vg_preise,true)."</pre>";
  unset($vg_preise);
  //die();
  /* echo "<pre style='color:gray'>".var_export($categories,true)."</pre>";
  echo "Anzahl Kategorien: ".count($categories); */
  unset($itemsPart);
  /* $x++;
  if($x < 50){
    $data = mb_convert_encoding($strCSV, 'UCS-2LE', 'UTF-8');
    file_put_contents(__DIR__.'/'."actetre_produkte.csv",$data);
  } */
  //echo "<pre style='color:green'>".var_export(json_encode($Products,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
  echo "<pre style='color:gray'>--------------------------------------</pre>";
  //continue;
  //die();
  foreach($Products as $index => $product){
    //break;
    $api_insert_url = $base_url."wp-json/wc/v3/products";

    $json_Products = json_encode($product['de'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

    //echo "<pre style='color:red'>".var_export($json_Products,true)."</pre>";
    /* break; */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_insert_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_Products);
    
    
    $xmlstr = curl_exec($ch);
    curl_close($ch);
    $curl_response = json_decode($xmlstr,true);

    if(isset($curl_response['id'])){

      $product['fr']['translations']['de'] = $curl_response['id'];
      $json_ProductsFR = json_encode($product['fr'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
      $chFR = curl_init();
      curl_setopt($chFR, CURLOPT_URL, $api_insert_url);
      curl_setopt($chFR, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($chFR, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($chFR, CURLOPT_POST, 1);
      curl_setopt($chFR, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($chFR, CURLOPT_USERPWD, "$api_key:$api_secret");
      curl_setopt($chFR, CURLOPT_POSTFIELDS, $json_ProductsFR);
      
      
      $xmlstrFR = curl_exec($chFR);
      curl_close($chFR);
      $curl_responseFR = json_decode($xmlstrFR,true);
    }

    
    echo "<pre style='color:gray;'>test123 ".var_export($product['fr']['translations']['de'],true)."</pre>";
    echo "<pre style='color:lightblue;'>test123 ".var_export($json_ProductsFR,true)."</pre>";
    echo "<pre style='color:blue;'>test123 ".var_export(json_encode($curl_responseFR,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
    if(isset($curl_response['data']['status'])){

      // Update, wenn Produkt schon vorhanden
      if($curl_response['data']['status'] == 400 && $curl_response['code'] == 'product_invalid_sku'){

        $update_id = $curl_response['data']['resource_id'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_insert_url."/".$update_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_Products);
        
        
        $xmlstr = curl_exec($ch);
        curl_close($ch);
        $curl_responseDE_update = json_decode($xmlstr,true);


        echo "<pre style='color:red'>".var_export(json_encode($json_Products,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
        echo "<pre style='color:green'>".var_export(json_encode($curl_responseDE_update,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
      }

      if($curl_response['data']['status'] == 400){
        file_put_contents(__DIR__.'/'."import_error.txt","[".date("d.m.Y H:i:s",time())."][articles-_x_'.$value.'.xml][".$product['sku']."][".$product['name']."][".$cat."]".var_export($curl_response,true)."\r\n",FILE_APPEND);
        file_put_contents(__DIR__.'/'."import_error_log.txt","[".date("d.m.Y H:i:s",time())."][articles-_x_'.$value.'.xml][".$product['sku']."][".$product['name']."][".$cat."] ## ".$curl_response['message']."\r\n",FILE_APPEND);
      }
    }else{
      file_put_contents(__DIR__.'/'."import_error.txt","[".date("d.m.Y H:i:s",time())."][articles-_x_'.$value.'.xml][".$product['sku']."][".$product['name']."][".$cat."]".var_export($product['categories'],true).var_export($json_Products,true)."\r\n",FILE_APPEND);
    }

    
    //if(isset($curl_responseDE_update['id'])){
    // Update, wenn Produkt schon vorhanden
    if(isset($curl_responseDE_update['id'])){
      echo "<pre style='color:tomato;'>test if statement fr update</pre>";
      $product['fr']['translations']['de'] = $curl_responseDE_update['id'];
      $json_ProductsFR = json_encode($product['fr'],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

      $update_id_fr = $curl_responseDE_update['translations']['fr'];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_insert_url."/".$update_id_fr);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($ch, CURLOPT_PUT, 1);
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $json_ProductsFR);
      
      
      $xmlstr = curl_exec($ch);
      curl_close($ch);
      $curl_responseFR_update = json_decode($xmlstr,true);

    }
    
    echo "<pre style='color:tomato;'>test1234x ".var_export($curl_responseDE_update['id'],true)."</pre>";
    echo "<pre style='color:tomato;'>test1234 ".var_export($product['fr']['translations']['de'],true)."</pre>";
    echo "<pre style='color:tomato;'>test12345 ".var_export($json_ProductsFR,true)."</pre>";
    echo "<pre style='color:tomato;'>test12346 ".var_export($update_id_fr,true)."</pre>";
    echo "<pre style='color:blue;'>test12347 ".var_export(json_encode($curl_responseFR_update,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
    //if($index >= 5)break;
  }
  unset($Products);
}  
  

//}