<?php
/*
 Template Name: XML Testseite Kunden IMPORT ACTETRE
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

foreach($fileIndex as $key => $value){

  $domPart = new DOMDocument;
  $domPart->preserveWhiteSpace = false;

  /* loading the file 
  https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/categories.xml
  categories-6C80D992-5BF0-896D-938E-E2D4DFF37726.xml (klappkarten Christmas)
  categories-0B2CA26C-57AA-7AC8-3C25-10EFF502C73D.xml (Klappkarten Everyday)
  categories-level0.xml
  */
  $domPart->Load('https://actetre.enpr.de/wp-content/themes/ACTEtre-WP/Customer Files/customers-_x_'.$value.'.xml');//('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

  /* preparing the xpath for the dom doc */
  $xpathPart = new DOMXPath($domPart);

  $queryPart = '//Customers//Customer';
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
  $tmpCustomers = [];
  $tmpProducts2 = [];
  $tmpProductsDE = [];
  $tmpProductsFR = [];
  $vg_preise = [];
  $i=0;$x=0;$option_index = 0;
  
  foreach ($itemsPart as $item) {        
      $nodes = $item->childNodes;
      
      //$j= 0;
      foreach ($nodes as $node) {
       /*  echo "node: ".$node->nodeName." | ".$node->nodeValue."<br>"; */
        if($node->nodeName == "#text")continue;   
        
        $tmpCats[$node->nodeName] = $node->nodeValue;

        if($node->nodeName == "Nummer"){
            $tmpCustomers['meta_data'][0] = [
                "key" => "kundennummer",
                "value" => $node->nodeValue
              ];
              $tmpCustomerRoles['customer_number'] = $node->nodeValue;
              //$tmpCustomers['username'] = $node->nodeValue;
        }
        if($node->nodeName == "Email"){
          $tmpCustomers['email'] = $node->nodeValue;
          $tmpCustomers['username'] = sanitize_user($node->nodeValue);
        }
        if($node->nodeName == "Email"){
          $tmpCustomers['email'] = $node->nodeValue;
        }
        if($node->nodeName == "Sprache"){
          $language = "";
          if($node->nodeValue == "F")
            $language = "fr_FR";
          if($node->nodeValue == "D")
            $language = "de_DE";
            $tmpCustomers['meta_data'][1] = [
              "key" => "locale",
              "value" => $language
            ];
          $tmpCustomers['locale'] = $language;
        }
        if($node->nodeName == "Firma"){
          $tmpCustomers['meta_data'][2] = [
            "key" => "billing_company",
            "value" => $node->nodeValue
          ];
          $tmpCustomers['billing']['company'] = $node->nodeValue;
        }
        if($node->nodeName == "Land"){
          $tmpCustomers['meta_data'][3] = [
            "key" => "billing_country",
            "value" => $node->nodeValue
          ];
          $tmpCustomers['billing']['country'] = $node->nodeValue;
        }
        if($node->nodeName == "Sprache"){
          $index = $node->nodeValue;
        }


        if($node->nodeName == "Vertriebsgebiete"){
            $vertriebsgebiete = $node->childNodes;

            foreach($vertriebsgebiete as $gebiet){
              $arrGebiete[] = $gebiet->nodeValue;
              if(!isset($tmpCustomerRoles['customer_role'])){
                if($gebiet->nodeValue == "Q")
                    $tmpCustomerRoles['customer_role'] = 'customer_q';
                if($gebiet->nodeValue == "D")
                    $tmpCustomerRoles['customer_role'] = 'customer_de';
                if($gebiet->nodeValue == "FR")
                    $tmpCustomerRoles['customer_role'] = 'customer_fr';
              }
              
                
            }
            $tmpCustomers['meta_data'][4] = [
              "key" => "vertriebsgebiete",
              "value" => $arrGebiete
          
          ];
        }


        
        //$i++;
        //unset($categories);
        /* echo $node->nodeName. ": <br>";
        echo $node->nodeValue. "<br>"; */
        //$articles[$i][$node->nodeName] = $node->nodeValue;
        //echo $node->nodeValue. "<br>";//$j=0;
   
        //$j++;
        
      }

      //echo "<pre style='color:red'>".var_export($tmpProducts,true)."</pre>";
    
      $tmpCustomers['password'] = "Outline123!";
      $tmpCustomers['tmp_roles'] = $tmpCustomerRoles;
      $Customers[] = $tmpCustomers;
      $CustomersRoles[] = $tmpCustomerRoles;
      unset($tmpCustomers);
      unset($tmpCustomerRoles);
      /* echo "<pre style='color:green;'>test123 ".var_export(json_encode($Products,JSON_PRETTY_PRINT),true)."</pre>";
      echo "<pre style='color:tomato;'>test123 ".var_export($Products,true)."</pre>"; */
      
      //echo "<br>---------<br>";
      /* echo "ITEM: ".$item->nodeName."<br>";
      echo "ITEM: ".$item->nodeValue."<br>"; */
      /* if($i >10)break;
      $i++; */
  }
  //echo "<pre style='color:deeppink'>".var_export($Customers,true)."</pre>";
 
  //die();
  /* echo "<pre style='color:gray'>".var_export($categories,true)."</pre>";
  echo "Anzahl Kategorien: ".count($categories); */
  unset($itemsPart);
  /* $x++;
  if($x < 50){
    $data = mb_convert_encoding($strCSV, 'UCS-2LE', 'UTF-8');
    file_put_contents(__DIR__.'/'."actetre_produkte.csv",$data);
  } */
  
  $index = 0;
  //die();
  foreach($Customers as $index => $customer){
    echo "<pre style='color:green'>".var_export(json_encode($customer,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
    //break;
    $arrRole['customer_role'] = $customer['tmp_roles']['customer_role'];
    $arrRole['meta_data'] = $customer['meta_data'];
    $api_insert_url = $base_url."wp-json/wp/v2/users";//"wp-json/wc/v3/customers";

    $json_Customers = json_encode($customer,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    //echo "<pre style='color:tomato'>".var_export(json_encode($arrRole,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";die();
    //echo "<pre style='color:red'>".var_export($json_Products,true)."</pre>";
    /* break; */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_insert_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_Customers);
    
    
    $xmlstr = curl_exec($ch);
    curl_close($ch);
    $curl_response = json_decode($xmlstr,true);
    echo "<pre style='color:blue'>".var_export($curl_response,true)."</pre>";
  //continue;
  //die();
  
    if(isset($curl_response['id'])){
      $api_role_url = $base_url."wp-json/wp/v2/customers/change_role/";
      $arrRole['ID'] = $curl_response['id'];
      $json_Role = json_encode($arrRole,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
      echo "<pre style='color:red'>".var_export($json_Role,true)."</pre>";

        $chRole = curl_init();
        curl_setopt($chRole, CURLOPT_URL, $api_role_url);
        curl_setopt($chRole, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($chRole, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($chRole, CURLOPT_POST, 1);
        curl_setopt($chRole, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($chRole, CURLOPT_USERPWD, "$api_key:$api_secret");
        curl_setopt($chRole, CURLOPT_POSTFIELDS, $json_Role);
        
        
        $xmlstrRole = curl_exec($chRole);
        curl_close($chRole);
        $curl_responseRole = json_decode($xmlstrRole,true);
    }

    if(isset($curl_response['code']) && $curl_response['code'] == "existing_user_email"){
      echo "<pre style='color:green'>".var_export("Benutzer existiert schon",true)."</pre>";
      $api_url = $base_url.'wp-json/wp/v2/customers/get-id/';
      $mail = ["email" => $customer['email']];
      $mail = json_encode($mail);

      $ch_getid = curl_init();
      curl_setopt($ch_getid, CURLOPT_URL, $api_url);
      curl_setopt($ch_getid, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_getid, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($ch_getid, CURLOPT_POST, 1);
      curl_setopt($ch_getid, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch_getid, CURLOPT_USERPWD, "$api_key:$api_secret");
      curl_setopt($ch_getid, CURLOPT_POSTFIELDS, $mail);
      
      
      $xmlstr_getid = curl_exec($ch_getid);
      curl_close($ch_getid);
      $curl_response_getid = json_decode($xmlstr_getid,true);
      echo "<pre style='color:aqua'>".var_export($mail,true)."</pre>";
      echo "<pre style='color:aqua'>".var_export($api_url,true)."</pre>";
      echo "<pre style='color:lime'>".var_export(json_encode($curl_response_getid,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";

      $customer_update = $customer;
      unset($customer_update['username']);
      $json_Customers_update = json_encode($customer_update,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
      echo "<pre style='color:lime'>".var_export($json_Customers_update,true)."</pre>";
      $id = $curl_response_getid['ID'];
// get id by email hier
      $ch_update = curl_init();
      curl_setopt($ch_update, CURLOPT_URL, $api_insert_url."/".$id);
      curl_setopt($ch_update, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch_update, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($ch_update, CURLOPT_POST, 1);
      curl_setopt($ch_update, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch_update, CURLOPT_USERPWD, "$api_key:$api_secret");
      curl_setopt($ch_update, CURLOPT_POSTFIELDS, $json_Customers_update);
      
      
      $xmlstr_update = curl_exec($ch_update);
      curl_close($ch_update);
      $curl_response_update = json_decode($xmlstr_update,true);


      if(isset($curl_response_update['id'])){
        $api_role_url = $base_url."wp-json/wp/v2/customers/change_role/";
        $arrRole['ID'] = $curl_response_update['id'];
        $json_Role = json_encode($arrRole,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        echo "<pre style='color:red'>".var_export($json_Role,true)."</pre>";
  
          $chRole = curl_init();
          curl_setopt($chRole, CURLOPT_URL, $api_role_url);
          curl_setopt($chRole, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($chRole, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
          curl_setopt($chRole, CURLOPT_POST, 1);
          curl_setopt($chRole, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($chRole, CURLOPT_USERPWD, "$api_key:$api_secret");
          curl_setopt($chRole, CURLOPT_POSTFIELDS, $json_Role);
          
          
          $xmlstrRole = curl_exec($chRole);
          curl_close($chRole);
          $curl_responseRole = json_decode($xmlstrRole,true);
      }
    }
    unset($arrRole);
    echo "<pre style='color:green'>".var_export(json_encode($curl_responseRole,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
    echo "<pre style='color:rebeccapurple'>".var_export(json_encode($curl_response_update,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE),true)."</pre>";
    echo "<pre style='color:deeppink'>".var_export($customer,true)."</pre>";
    if($index >5)break;
    $index++;
    // Start API-Call
  }
  die();
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
  die();
  // Kategorie einfügen, falls noch nicht vorhanden
//continue;
  
  foreach($categories as $cat_id => $cat){
    $jsonCats = [
      "name" => /* str_replace('"','###', */$cat["Category"]/* ) */,
      "acf" => ["wawi_cat_id" => $cat_id, "wawi_parent_id" => $cat["ParentKey"]]
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

//}