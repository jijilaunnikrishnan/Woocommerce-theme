<?php
/*
 Template Name: Woocommerce Bilder Test
 Template Post Type: page
*/
/* $prod = wc_get_product(158019)->get_sku(); //9356186227
*/
/* 

foreach($list7 as $id => $nummer){
456550
} */
/* $args = array(
    $taxonomy => "product_cat"
);
$taxonomy     = 'product_cat';
$orderby      = 'name';  
$show_count   = 0;      // 1 for yes, 0 for no
$pad_counts   = 0;      // 1 for yes, 0 for no
$hierarchical = 1;      // 1 for yes, 0 for no  
$title        = '';  
$empty        = 0;

$args = array(
       'taxonomy'     => $taxonomy,
        'hide_empty'   => $empty
  );
$cats = get_categories($args);
foreach($cats as $i => $cat){
    $lang = pll_get_term_language($cat->term_id);

    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
    $cats[$i]->language = $lang;
    $cats[$i]->thumb_id = $thumbnail_id;
    $cats[$i]->translations = pll_get_term_translations($cat->term_id);

    update_field("kategorie_bild_en",$thumbnail_id,"category_".$cats[$i]->translations["en"]);

}

echo "<pre style='color:green;'>".var_export($cats,true)."</pre>";echo "fertig!"; */

include __DIR__."/image_en_arrays.php";
$products = wc_get_products(["lang" => "en", "return" => "ids","limit" => -1]);
/* echo "<pre style='color:red;'>".var_export($products,true)."</pre>";
die(); */
//die();
//$id = 428006;
foreach($list7 as $id){
    $post_lang = pll_get_post_language($id);
    $prod = wc_get_product($id);
    
    
    $thumb = get_post_thumbnail_id($id);
    $translations = pll_get_post_translations($id);
    
    
    $thumb_fr = get_post_thumbnail_id($translations["fr"]);
    $thumb_de = get_post_thumbnail_id($translations["de"]);
    
    
    /* echo "<pre style='color:red;'>".var_export($thumb_fr,true)."</pre>";
    echo "<pre style='color:green;'>".var_export($thumb_de,true)."</pre>";
    
    echo "<pre>".var_export($post_lang,true)."</pre>";
    echo "<pre>".var_export($thumb,true)."</pre>";
    echo "<pre>".var_export($translations,true)."</pre>";
    echo "<pre>".var_export($prod,true)."</pre>"; */
    
    $post_id = $id;
    if(isset($thumb_fr)){
        $imageID = $thumb_fr;
    }else{
        $imageID = $thumb_de;
    }
   
    
    set_post_thumbnail( $post_id, $imageID );
    
}
echo "fertig!";
function QuadLayers_add_featured_image($post_id,$imageID) {
    $imageID = 69; // Image ID
    $post_id = 198; //Product ID
    //set_post_thumbnail( $post_id, $imageID );
}
//add_action('init', 'QuadLayers_add_featured_image');

