<?php
/**
 * Single Product Artist
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>


	<?php do_action( 'woocommerce_product_artist_start' ); ?>
    <?php if ( !empty(get_field('kunstlername') ) ) : ?>
		<dt class="productdetails__label"><?php pll_e("Künstler") ?></dt>
	
		<dd class="productdetails__value">
			<span class="sku_wrapper"><span class="sku"><?php the_field('kunstlername') ?></span></span>
		</dd>
	<?php endif; ?>
    <?php if ( !empty(get_field('papiergrose') ) ) : ?>
		<dt class="productdetails__label"><?php pll_e("Maße (H x B)") ?></dt>
	
		<dd class="productdetails__value">
			<span class="sku_wrapper"><span class="sku"><?php the_field('papiergrose') ?></span></span>
		</dd>
	<?php endif; ?>


	<?php do_action( 'woocommerce_product_artist_end' ); ?>

