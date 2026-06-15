<?php
/*
 Template Name: category images
 Template Post Type: page 304373
*/
get_header(); ?>


<?php


$post_object = get_post( 304373 );
echo "<pre style='color:green';>".var_export($post_object,true)."</pre>";
die();
$featured_products = wc_get_products(array(
    'status'               => 'publish',
    'visibility'           => 'visible',
    'category'			   => "klappkarten-christmas",
    'limit'                => 1,
    'paginate'             => true,
    'return'               => 'ids'
));
$product_id = $featured_products->products['0'];
// Get the product object based on the product ID
$product   = wc_get_product( $product_id );
echo "<pre style='color:green';>".var_export($featured_products,true)."</pre>";
// Retrieve the image ID associated with the product
$image_id  = $product->get_image_id();


$args = array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false
);
$data = get_terms($args);
$startindex = 0;

foreach($data as $cat){
    //echo $cat->slug." ".pll_get_term_language($cat->term_id)."<br>";
    $image_id = "";
    $featured_products = wc_get_products(array(
        'status'               => 'publish',
        'visibility'           => 'visible',
        'category'			   => $cat->slug,//"klappkarten-christmas",
        //'limit'                => 1,
        'return'               => 'ids'
    ));
    echo "<pre style='color:tomato';>".var_export($featured_products,true)."</pre>"; //die();
    echo "<pre style='color:tomato';>".var_export(count($featured_products),true)."</pre>"; //die();
    //while(empty($image_id)&&$startindex<20){
    foreach($featured_products as $index => $id_value){
        //echo "<pre style='color:black';>Test</pre>";
        echo "<pre style='color:black';>Cur Index: ".var_export($index,true)."</pre>";
        if(isset($featured_products[$index])){
            $product_id = $featured_products[$index];
        
            // Get the product object based on the product ID
            if($cat->term_id==12731)echo "<pre style='color:grey';>".var_export($featured_products,true)."</pre>";
            if($cat->term_id==12731)echo "<pre style='color:grey';>".var_export($product_id,true)."</pre>";
            if($cat->term_id==12731)echo "<pre style='color:grey';>Current index: ".var_export($index,true)."</pre>";
            if(!empty($featured_products)){
        
                $product   = wc_get_product( $product_id );
                //if($cat->term_id==12731)echo "<pre style='color:grey';>".var_export($product,true)."</pre>";
                // Retrieve the image ID associated with the product
                $image_id  = $product->get_image_id();
                if($cat->term_id==12731)echo "<pre style='color:black';>".var_export($image_id,true)."</pre>";
                //do{
                if(!empty($image_id)){
                    update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
                    echo "<pre style='color:black';>Set Image ID ".var_export($image_id,true)."</pre>";
                    $startindex = 0;
                    break;
                }/* else{
                    echo "<pre style='color:black';>No Image ID1 ".var_export($product_id,true)."</pre>";
                    echo "<pre style='color:black';>No Image ID2 ".var_export($image_id,true)."</pre>";
                    //echo "<pre style='color:black';>No Image ID3 ".var_export($product,true)."</pre>";
                    $startindex++;
                    continue;
                } */
                //}while(empty($image_id));
                
                // Get the image URL using the image ID and specify the image size ('full' in this case)
                $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                $arrLinks[$cat->term_id] = $image_id;
            }else{
                $arrLinks[$cat->term_id] = "no image found for " . $cat->slug;
            }

           echo "<pre style='color:black';>test index: ".var_export($index,true)."</pre>";
            if($index >= count( $featured_products ) - 1 ){
                echo "<pre style='color:orange';>Set default Image ".var_export($cat->term_id,true)."</pre>";
                echo "<pre style='color:orange';>Set default Image ".var_export($image_id,true)."</pre>";
                $image_id = '4';
                update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
                break;
            } 
        }
        
    }
        //$startindex++;
    //}
    
    
}
echo "<pre style='color:rebeccapurple';>".var_export($data,true)."</pre>";
echo "<pre style='color:green';>".var_export($arrLinks,true)."</pre>";
//echo "<pre style='color:lightgreen';>Anzahl: ".var_export(count($arrLinks),true)."</pre>";
echo "<pre style='color:rebeccapurple';>Anzahl: ".var_export(count($data),true)."</pre>";

echo "<pre style='color:tomato';>".var_export($featured_products,true)."</pre>"; 
echo "<pre style='color:lime';>".var_export($image_url,true)."</pre>"; 
?>



<?php
get_footer();
?>