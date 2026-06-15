<?php 
defined( 'ABSPATH' ) || exit;
get_header('shop'); ?>

    <?php
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
    
    if ( have_posts() ) : 


        do_action('woocommerce_before_shop_loop');
		woocommerce_product_loop_start();
        /* Start the Loop */
        while ( have_posts() ) : the_post();

            wc_get_template_part('content', 'product');

        endwhile;?>
        
        <?php wc_get_template_part('loop/result-count.php');
        woocommerce_product_loop_end();
		do_action('woocommerce_after_shop_loop');
        
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => __( '←', 'textdomain' ),
            'next_text' => __( '→', 'textdomain' ),
        ) );
        //the_posts_navigation();
    else :
        do_action('woocommerce_no_products_found');
    endif; ?>

<?php


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
get_footer('shop');
