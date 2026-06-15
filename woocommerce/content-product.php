<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li class="item" style="" <?php wc_product_class( '', $product ); ?>>
		<?php
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf(
        '<a href="%s" class="add_to_cart_button ajax_add_to_cart" data-product_id="%s" rel="nofollow">Add to cart</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( $product->get_id() )
    ),
$product );
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	//echo "<pre>".var_export($product->get_id(),true)."</pre>";

	$product_id = $product->get_id();

	$product_cart_id = WC()->cart->generate_cart_id( $product_id );
	$in_cart = WC()->cart->find_product_in_cart( $product_cart_id );
   
	if ( $in_cart ) {
   
	   /* $notice = '(╯°□°）╯︵ ┻━┻) hat <span style="color:tomato;font-weight:bold">' . $product->get_name() . '</span> in deinen Warenkorb geworfen!'; 
	   $notice = 'in cart';*/
	   $notice = '<div class="incart"><svg class="incart_ico" version="1.1" id="ico" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	   viewBox="0 0 874.6 850.6" enable-background="new 0 0 874.6 850.6" xml:space="preserve">
  <path id="cart" d="M113.6,393c9.2,20.5,17.9,39.7,26.6,59c10.7,23.8,21.6,47.6,32.2,71.5c7.1,16.2,19.4,24,36.9,25.1
	  c58.9,3.8,117.7,7.8,176.6,11.7c46.2,3.1,92.4,6.2,138.7,9.1c4,0.3,4.7,1,3.3,4.8c-5.3,14.6-10.4,29.2-15.3,43.9
	  c-1.3,4-3.3,5.2-7.4,5.2c-114.5-0.1-229,0-343.5-0.2c-26.9,0-48.8,9.6-64.7,31.4c-18.4,25-18.8,59-1.6,85
	  c17.3,26,48.9,38.6,79.5,31.6c30-6.9,53-32,57.3-62.4c1.5-10.6,1.5-10.6,12.1-10.6c110.7,0,221.3,0,332-0.1c4.7,0,6.7,0.6,7.2,6.2
	  c3.4,38.5,33.3,66.9,72,68.7c36.4,1.7,69.2-24.3,76.3-60.5c8.8-44.8-24.2-87.2-69.8-89c-21.8-0.9-43.7-0.2-65.5-0.3
	  c-6.1,0-6.1,0-4.1-5.6c46.7-133.5,93.5-266.9,140.1-400.4c1.7-5,4-6.5,9.2-6.4c25.8,0.3,51.7,0.3,77.5,0.1
	  c20.4-0.1,35.1-12.3,38.5-31.5c3.6-20.1-11.4-40.4-31.8-42.8c-2.8-0.3-5.7-0.4-8.5-0.4c-35.3,0-70.7,0-106,0
	  c-20.3,0-32.8,8.8-39.6,28c-6.4,18-12.8,36.1-18.9,54.3c-1.3,4-3.3,5.2-7.5,5.2c-72.7-0.1-145.3-0.1-218-0.1c-6.8,0-6.8,0-7,7.1
	  c-0.6,21.2-4.7,41.7-12,61.5c-2.4,6.4-2.4,6.4,4.3,6.4c68.5,0,137,0,205.5,0c6.5,0,6.5,0,4.4,6.1c-21.8,62.1-43.6,124.2-65.1,186.3
	  c-1.6,4.5-3.5,5.6-8.2,5.2c-48-3.4-96.1-6.4-144.1-9.6c-47.4-3.2-94.8-6.4-142.1-9.6c-8.5-0.6-17-1.2-25.4-1.6
	  c-3.3-0.1-5-1.4-6.3-4.4c-6.5-14.8-13.2-29.5-19.8-44.3c-1-2.2-1.8-3.9-4.8-4.1c-32.2-2.1-62.1-11.6-89.7-28.3
	  C116.1,394,115.5,393.8,113.6,393z"/>
  <g id="check">
	  <path d="M384.5,205.9c0,12.9,0,25.9,0,38.8c-0.7-2.4-0.1-4.8-0.6-7.4C378,316,316.3,381.6,232.2,387.3
		  c-85.4,5.7-160-53.2-172.8-140.9c-5.9-40.6,3-78.6,25.9-112.7c28.9-42.9,70-66.1,121-72.4c-1.4-0.2-2.9,0.1-4.1-0.5
		  c12.3,0,24.7,0,37,0c-1,0.6-2.2,0.3-3.7,0.7c39.6,4,73.4,19.4,101.2,47.3c27.8,27.9,43.4,61.7,47.1,101.1
		  C384.3,208.5,384,207.1,384.5,205.9z M330.4,189.3c0-2.6-1.2-5.8-3.8-8.5c-5.3-5.4-10.6-10.8-16-16.1c-2.3-2.2-4.3-4.8-7.2-6.3
		  c-4.6-2.4-11.5-2.4-17.1,3.2c-20,20.3-40.3,40.3-60.4,60.5C215,233,204.1,244,193.3,254.9c-1.1,1.1-1.8,0.7-2.7-0.2
		  c-3-3.1-6-6.1-9.1-9.1c-10.7-10.7-21.5-21.5-32.3-32.2c-5.8-5.7-14.3-5.5-20,0.2c-5.9,5.9-11.7,11.7-17.6,17.6
		  c-0.8,0.8-1.5,1.6-2.2,2.4c-5.1,5.8-4.9,13.4,0.6,18.9c16.8,16.8,33.6,33.6,50.4,50.5c7.2,7.2,14.4,14.5,21.7,21.7
		  c4.8,4.7,10.7,5.5,16.6,2.3c2.1-1.1,3.7-2.7,5.3-4.4c36.5-36.4,72.9-72.9,109.3-109.3c4.6-4.6,9.3-9,13.6-13.9
		  C329.3,196.7,330.5,193.7,330.4,189.3z"/>
  </g>
  </svg></div>';


		echo $notice;
	   
   
	}
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	echo "<div class='item_info'>";
		echo '<span>'. $product->get_sku().'</span>';
		do_action( 'woocommerce_shop_loop_item_title' );
	echo "</div>";
	echo '<span class="item_link">'. pll__("Produkt ansehen").'</span>';

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
