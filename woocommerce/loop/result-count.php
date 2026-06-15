<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.7.0
 */

/**
 * WooCommerce Result Count (Polylang compatible)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php if ( ! is_search() ) : ?>
    <p class="woocommerce-result-count">
        <?php
        if ( 1 === intval( $total ) ) {
            // Single result
            pll_e( 'Showing the single result' );
        } elseif ( $total <= $per_page || -1 === $per_page ) {
            // All results
            echo sprintf( pll__( 'Showing all <strong>%d</strong> results' ), $total );
        } else {
            // Range of results
            echo sprintf( pll__( 'Showing all <strong>%d</strong> results' ), $total );
        }
        ?>
    </p>
<?php endif; ?>