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

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );


$search_query = trim(get_search_query());


	if (empty($search_query)) {
    global $wp_query; // To access the number of results
    $search_term = get_search_query();
    $found_posts = $wp_query->found_posts;
    ?>
    <div class="searchcontainer">
        <div class="row align-center">
            <div class=<?php 
                if ( !is_user_logged_in() && ( is_page(8) || is_page(86990) ) ) {
                    echo "'column medium-6'";
                } else {
                    echo "'column large-8'";
                }
            ?>>
                <?php if ( !empty($search_term)) : ?>
    <h1 class="product search heading">
        <?php
        printf(
            /* translators: %1$s: search term, %2$d: number of results */
             pll__( '%2$d Search results for “%1$s”' ),
            esc_html( $search_term ),
            intval( $found_posts )
        );
        ?>
    </h1>

                <?php else : ?>
                    <h1 class="product search heading">
                        <?php pll_e( 'Product search', 'woocommerce' ); ?>
                    </h1>
                <?php endif; ?>

          <?php 
$action_url = esc_url( home_url( '/' ) ); // or use get_permalink( wc_get_page_id( 'shop' ) ) for shop page
?>
			
<form role="search" method="get" class="woocommerce-product-search" action="<?php echo esc_url( get_language_root_url() ); ?>">
    <input type="search" name="s" placeholder="<?php pll_e( 'Suche nach Produkt oder Artikelnummer…' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
    <input type="hidden" name="post_type" value="product" />
    <button type="submit"></button>
</form>
				<div class="tag_section">
					<p><?php pll_e( 'oder', 'woocommerce' ); ?></p>
					<div class="search_btn"><p><?php pll_e( 'Such mit tags:', 'woocommerce' ); ?></p> <a class="button" href="?s=<?php pll_e( 'Neuheiten', 'woocommerce' ); ?>&post_type=product"><?php pll_e( 'Neuheiten', 'woocommerce' ); ?></a>
				</div>
  </div>
            </div>
        </div>
    </div><!-- .container -->
<?php }  else { ?>
<header class="woocommerce-products-header">
	<?php if (apply_filters("woocommerce_show_page_title", true)): ?>
		<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
	<?php endif; ?>

	<?php /**
  * Hook: woocommerce_archive_description.
  *
  * @hooked woocommerce_taxonomy_archive_description - 10
  * @hooked woocommerce_product_archive_description - 10
  */
 do_action("woocommerce_archive_description"); ?>
</header>
<?php
// Only on product category archive pages
if( is_product_category() ) {
    $main_term = get_queried_object();

    $args_query = array(
        'taxonomy' => 'product_cat', 
        'hide_empty' => false, 
        'child_of' => $main_term->parent
    );
	echo var_export($main_term,true);
    //if ( $main_term->parent != 0 ) {
        // Loop through WP_Term Objects
        foreach ( get_terms( $args_query ) as $term ) {
            if( $term->term_id != $main_term->term_id ) {
                // $term->slug; // Slug

                // Output each (linked) term name…
                echo sprintf( '<a href="%s">%s</a></br>', get_term_link( $term->term_id, 'product_cat' ), $term->name );
            }
        }
    //}
}

if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

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
do_action( 'woocommerce_sidebar' );
			  }
get_footer( 'shop' );
