<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$customer_vg = "";
$query_args['vertriebsgebiet'] = null;
if(is_user_logged_in()){
	//echo "logged in";
	$user = wp_get_current_user();
	$vertriebsgebiete_feld = get_field("vertriebsgebiete","user_".$user->data->ID);
//echo "<pre>".var_export($vertriebsgebiete_feld,true)."</pre>";
	foreach($vertriebsgebiete_feld as $gebiet){
		if($gebiet != "Q")$customer_vg = $gebiet;
	}
	if($customer_vg == "FR")$customer_vg = "fr";
	if($customer_vg == "D")$customer_vg = "de";
	if(empty($customer_vg) && !empty($vertriebsgebiete_feld))$customer_vg = "en";
	//echo "<pre>".var_export($customer_vg,true)."</pre>";
/* 	echo "<pre>".var_export($gebiet,true)."</pre>";
	
	echo "<pre>".var_export($user->data->ID,true)."</pre>"; */
	$query_args['vertriebsgebiet'] = $customer_vg;
}else{
	$query_args['vertriebsgebiet'] = pll_current_language();
}

//echo "<pre>".var_export($query_args,true)."</pre>";
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

	/**
	 * Hook: woocommerce_archive_description.
	 *
	 * @hooked woocommerce_taxonomy_archive_description - 10
	 * @hooked woocommerce_product_archive_description - 10
	 */
	do_action( 'woocommerce_archive_description' );
	?>

<?php

$current_lang = pll_current_language();
//echo "<pre>".var_export($current_lang,true)."</pre>";
$args = array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'name' => 'Postkarten "Everyday"'
);
$data = get_terms($args);
//echo "<pre style='color:tomato';>".var_export($data[0]->term_id,true)."</pre>";
$current_cat = get_queried_object();
// Get the image ID from ACF
$dummy_image_id = get_field('kategorie-banner', 'product_cat_' . $current_cat->term_id);
//var_dump($dummy_image_id); // Should be an integer like 447080

// Get the actual image URL
$dummy_image_url = $dummy_image_id ? wp_get_attachment_url($dummy_image_id) : '';
//var_dump($dummy_image_url);
if(!has_Parents($current_cat)){
	?>

 
	<!-- <img  src="https://actetre.enpr.de/wp-content/uploads/2024/02/dummy_img.png"> -->

	<div id="cat_header">
		<h2><?php pll_e($current_cat->name)?></h2>
	 <img src="<?php echo esc_url($dummy_image_url); ?>" alt="<?php echo $current_cat->name; ?>">
	</div>

	<div class="container-fluid">

		<div class="productlist">
			<?php 
			
			if( is_product_category() ) {
				
			
				$args_query = array(
					'taxonomy' => 'product_cat', 
					'hide_empty' => true, 
					'child_of' => $current_cat->parent,
					'orderby'    => 'name', //  alphabetical order
        		     'order'      => 'ASC'
				);
			
				
				// Loop through WP_Term Objects
				foreach ( get_terms( $args_query ) as $term ) {
					if( $term->term_id != $current_cat->term_id ) {
						// $term->slug; // Slug
						if(!has_Children($term->term_id))continue;
						if($current_cat->term_id != $term->parent)continue;
						//if(has_Parents($term))continue;
						
						// get the thumbnail id using the queried category term_id
						
						$thumbnail_id = get_field( "kategorie_bild_".$current_lang, 'category_'.$term->term_id );
						if(empty($thumbnail_id))$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
						// get the image URL
						$image = wp_get_attachment_url( $thumbnail_id ); 
						if(str_contains($image, "placeholder"))$image = wp_get_attachment_url(409615);
						?>
							<a class="item" href="<?php echo get_category_link( $term->term_id );?>">
										<img class="item__image" title="Klappkarten &quot;Weihnachten&quot;" alt="Piet MERRY CHRISTMAS" src="<?php echo $image; ?>">
								<span class="item__name"><?php echo pll__($term->name)?></span>
								<span class="button hollow"><?php pll_e("Produktserie ansehen");?></span>
								<!-- <span class="button hollow">has Children:<?php echo (int)has_children( $term->term_id );?></span>
								<span class="button hollow">has Parents:<?php echo (int)has_Parents( $term->term_id );?></span>
								<span class="button hollow">Parents:<?php echo (int) $term->parent ;?></span> -->
							</a>
						<?php
						//echo sprintf( '<a href="%s">%s</a></br>', get_term_link( $term->term_id, 'product_cat' ), $term->name );
					}
				}
				
			}
			?>
		
		</div>
	</div>


	<div class="row align-center">
		<div class="column large-8">
				</div>
	</div>

	
	<?php
}elseif(has_Children($current_cat->term_id)){
?>
             <div class="container-fluid">


                <div class="productlist">

				<?php 
			
					if( is_product_category() ) {
						
					
						$args_query = array(
							'taxonomy' => 'product_cat', 
							'hide_empty' => true, 
							'child_of' => $current_cat->parent,
							'orderby'    => 'name', //  alphabetical order
        		            'order'      => 'ASC'
						);
					
						
						// Loop through WP_Term Objects
						foreach ( get_terms( $args_query ) as $term ) {
							if( $term->term_id != $current_cat->term_id ) {
								// $term->slug; // Slug 
								if(has_Children($term->term_id) || !is_Parent($current_cat->term_id,$term->parent))continue;
								
								// get the thumbnail id using the queried category term_id
								
								$thumbnail_id = get_field( "kategorie_bild_".$current_lang, 'category_'.$term->term_id );
								if(empty($thumbnail_id))$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true ); 
								// get the image URL
								$image = wp_get_attachment_url( $thumbnail_id ); 
								if(str_contains($image, "placeholder"))$image = wp_get_attachment_url(409615);
								$cat_args = array('category' => $term->slug/* , "include" => [87117], */,"return" => "ids");
								if(!empty($query_args['vertriebsgebiet']))$cat_args['vertriebsgebiet'] = $query_args['vertriebsgebiet'];
								$products = wc_get_products($cat_args);
								 //echo var_export($products,true);
								 if(!empty($products)):
								?>
									<a class="item" href="<?php echo get_category_link( $term->term_id );?>">
										<img class="item__image" src="<?php echo $image; ?>">
										<span class="item__name"><?php echo pll__($term->name)?></span>
										<span class="button hollow"><?php pll_e("Produktserie ansehen");?></span>
									</a>
								<?php
								endif;
								//echo sprintf( '<a href="%s">%s</a></br>', get_term_link( $term->term_id, 'product_cat' ), $term->name );
							}
						}
						
					}
				?>

  
                </div>
            </div>


            <div class="row align-center">
                <div class="column large-8">
                </div>
            </div>

<?php 
}else{
	$orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : 'menu_order';
$order   = 'ASC'; // default order
$meta_key = '';

switch ( $orderby ) {
   case 'menu_order':
default:
    $orderby = 'menu_order title';
    $order   = 'ASC';
    $meta_key = '';
    break;

    case 'date':
        $orderby = 'date';
        $order   = 'DESC';
        break;

    case 'popularity':
    $meta_key = 'total_sales';
    $orderby  = 'meta_value_num';
    $order    = 'DESC';
    break;

    case 'title':
        $orderby = 'title';
        $order   = 'ASC';
        break;

   
}
 $paged                   = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
	 /*  $ordering                = WC()->query->get_catalog_ordering_args();
	  $ordering['orderby']     = array_shift(explode(' ', $ordering['orderby']));
	  $ordering['orderby']     = stristr($ordering['orderby'], 'price') ? 'meta_value_num' : $ordering['orderby']; */
	$products_per_page       = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());
// Build WooCommerce product query
$paged = max( 1, get_query_var( 'paged' ) );

$prod_args = array(
    'status'     => 'publish',
    'visibility' => 'visible',
    'category'   => $current_cat ? $current_cat->slug : '',
    'paginate'   => true,
    'page'       => $paged,
    'limit'      => $products_per_page,
    'return'     => 'ids',
    'orderby'    => array(
        $orderby => $order,
        'ID'     => 'ASC', // ✅ critical for pagination stability
    ),
);
if ( ! empty( $meta_key ) ) {
    $prod_args['meta_key'] = $meta_key;
}

if ( ! empty( $query_args['vertriebsgebiet'] ) ) {
    $prod_args['vertriebsgebiet'] = $query_args['vertriebsgebiet'];
}

// Get products
$featured_products = wc_get_products( $prod_args );

	// Prevent 404 on custom template
if ( $featured_products->total > 0 ) {
    global $wp_query;
    $wp_query->is_404 = false;
}

	  
	  wc_set_loop_prop('current_page', $paged);
	  wc_set_loop_prop('is_paginated', true);
	  wc_set_loop_prop('page_template', get_page_template_slug());
	  wc_set_loop_prop('per_page', $products_per_page);
	  wc_set_loop_prop('total', $featured_products->total);
	  wc_set_loop_prop('total_pages', $featured_products->max_num_pages);
	  wc_set_loop_prop('orderby', $featured_products->orderby);
	  wc_set_loop_prop('order', $featured_products->order);
	
	  if($featured_products) {
		do_action('woocommerce_before_shop_loop');
		woocommerce_product_loop_start();
		  foreach($featured_products->products as $featured_product) {
			//echo "<pre>".var_export(get_post_meta($featured_product,'_sku',true),true)."</pre>";
			$post_object = get_post($featured_product);
			//echo get_field("vertriebsgebiet", $featured_product)." ";echo "<pre>".var_export($featured_product,true)."</pre>";
			//if(get_field('vertriebsgebiet') == "Q")
			setup_postdata($GLOBALS['post'] =& $post_object);
			wc_get_template_part('content', 'product');
		  }
		  wp_reset_postdata();
		woocommerce_product_loop_end();
		do_action('woocommerce_after_shop_loop');
	  } else {
		do_action('woocommerce_no_products_found');
	  }
	///////////////////////
}

// Only on product category archive pages

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
/////////////do_action( 'woocommerce_sidebar' );

//$featured_products       = wc_get_products(array('category' => $term->slug/* , "include" => [87117], */,"vertriebsgebiet" => $current_lang,"return" => "ids"));
  //echo "<pre style='color:tomato';>".var_export($featured_products,true)."</pre>";


get_footer( 'shop' );
