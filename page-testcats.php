
<?php
/*
 Template Name: Cat Testseite ACTETRE
 Template Post Type: page
*/
get_header(); ?>
<nav class="site-navigation main-navigation">
			<?php wp_nav_menu( array( 'menu' => 'test-kategorien' ) ); // Display the user-defined menu in Appearance > Menus ?>
		</nav><!-- .site-navigation .main-navigation -->
<?php

echo do_shortcode('[product_categories_hierarchy]');
/* $atts = shortcode_atts( array(
        'id' => get_the_id(),
    ), $atts, 'product_cat_list' ); */

    $output    = []; // Initialising
    $taxonomy  = 'product_cat'; // Taxonomy for product category

    // Get the product categories terms ids in the product:
    $terms_ids = get_terms( ['taxonomy' => 'product_cat', "hide_empty" => false] );

    // Loop though terms ids (product categories)
    foreach( $terms_ids as $term_id ) {
        $term_names = []; // Initialising category array

        // Loop through product category ancestors
        foreach( get_ancestors( $term_id, $taxonomy ) as $ancestor_id ){
            // Add the ancestors term names to the category array
            $term_names[] = get_term( $ancestor_id, $taxonomy )->name;
        }
        // Add the product category term name to the category array
        $term_names[] = get_term( $term_id, $taxonomy )->name;

        // Add the formatted ancestors with the product category to main array
        $output[] = implode(' > ', $term_names);
    }
    // Output the formatted product categories with their ancestors
    echo "<pre>".var_export($terms_ids,true)."</pre>";

    ?>