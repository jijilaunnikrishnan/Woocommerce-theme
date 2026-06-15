<?php
/**
 * Single Product VPE
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/vpe.php.
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

echo "<div id='vpe_div'>".get_field("verpackungseinheit",$product->id)."</div>";
?>

<div class="AT-quantity-selector">
	<input class="vpe-select" type="number" step="1" min="1" max="9999" aria-label="Anzahl in deinem Warenkorb." value="1">
	<a aria-label="Menge verringern" class="quantity-selector__button quantity-selector__button--minus">－</a>
	<a aria-label="Menge erhöhen" class="quantity-selector__button quantity-selector__button--plus">＋</a>
</div>


