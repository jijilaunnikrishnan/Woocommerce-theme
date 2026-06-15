<?php
/*
 Template Name: delete products
 Template Post Type: page
*/
get_header(); ?>


<?php
global $wpdb;
$product_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'product' AND post_status = 'publish'");

$products = array();
foreach ($product_ids as $product_id) {
    $products[] = wc_get_product($product_id);
}
$featured_products = wc_get_products(array(
    'status'               => 'any',
    'limit'                 => -1,
    'return'               => 'ids',
    'paginate' => false
));

//echo "<pre>".var_export($products,true)."</pre>";
$api_key = "ck_9ed0ba52cae1bc4dd934bc344cc8c3908c2ea4b9";
$api_secret = "cs_95f7227b9409b1fb3526d51359384fad51580f89";

$base_url = "https://actetre.enpr.de/";
  $i=0;
$api_insert_url = $base_url."wp-json/wc/v3/products/";
foreach($products as $id){
    //echo "<pre>".var_export($api_insert_url.$id->id."?force=true",true)."</pre>";
    $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $api_insert_url.$id->id."?force=true");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
      
      
      $xmlstr = curl_exec($ch);
      curl_close($ch);

      echo "<pre>".var_export($xmlstr,true)."</pre>";
      /* $i++;
      if($i <=10)break; */
}
