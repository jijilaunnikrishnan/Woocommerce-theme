<?php
	/*-----------------------------------------------------------------------------------*/
	/* This file will be referenced every time a template/page loads on your Wordpress site
	/* This is the place to define custom fxns and specialty code
	/*-----------------------------------------------------------------------------------*/

// Define the version so we can easily replace it throughout the theme
define( 'AT_VERSION', 1.0 );

/*-----------------------------------------------------------------------------------*/
/*  Set the maximum allowed width for any content in the theme
/*-----------------------------------------------------------------------------------*/
if ( ! isset( $content_width ) ) $content_width = 900;

/*-----------------------------------------------------------------------------------*/
/* Add Rss feed support to Head section
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------*/
/* Add post thumbnail/featured image support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'post-thumbnails' );

/*-----------------------------------------------------------------------------------*/
/* Register main menu for Wordpress use
/*-----------------------------------------------------------------------------------*/
register_nav_menus( 
	array(
		'primary'	=>	__( 'Primary Menu', 'AT' ), // Register the Primary menu
		// Copy and paste the line above right here if you want to make another menu, 
		// just change the 'primary' to another name
	)
);

/*-----------------------------------------------------------------------------------*/
/* Activate sidebar for Wordpress use
/*-----------------------------------------------------------------------------------*/
function AT_register_sidebars() {
	register_sidebar(array(				// Start a series of sidebars to register
		'id' => 'sidebar', 					// Make an ID
		'name' => 'Sidebar',				// Name it
		'description' => 'Take it on the side...', // Dumb description for the admin side
		'before_widget' => '<div>',	// What to display before each widget
		'after_widget' => '</div>',	// What to display following each widget
		'before_title' => '<h3 class="side-title">',	// What to display before each widget's title
		'after_title' => '</h3>',		// What to display following each widget's title
		'empty_title'=> '',					// What to display in the case of no title defined for a widget
		// Copy and paste the lines above right here if you want to make another sidebar, 
		// just change the values of id and name to another word/name
	));
} 
// adding sidebars to Wordpress (these are created in functions.php)
//add_action( 'widgets_init', 'AT_register_sidebars' );

/*-----------------------------------------------------------------------------------*/
/* Enqueue Styles and Scripts
/*-----------------------------------------------------------------------------------*/

function AT_scripts()  { 

	// get the theme directory style.css and link to it in the header
	wp_enqueue_style('AT_styles.css', get_stylesheet_directory_uri() . '/styles/AT_styles.css');
	wp_enqueue_style('new.css', get_stylesheet_directory_uri() . '/styles/new.css');
	
	// add fitvid
	//wp_enqueue_script( 'naked-fitvid', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), AT_VERSION, true );
	
	// add theme scripts
	wp_enqueue_script( 'app', get_template_directory_uri() . '/js/app.js', array(), AT_VERSION, true );
	if(is_front_page())
		wp_enqueue_script( 'slick', get_template_directory_uri() . '/js/slick.min.js', array(), AT_VERSION, true );
  
}
add_action( 'wp_enqueue_scripts', 'AT_scripts' ); // Register this fxn and allow Wordpress to call it automatcally in the header


function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


/**
 * Show the product title in the product loop. By default this is an H2.
 */
function woocommerce_template_loop_product_title() {
	echo '<span class="item__name ' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10);

/**
 * Funktionen für Kategorieseite
 */
function has_Children($cat_id){
    $children = get_terms(array( 'taxonomy'=>'product_cat','parent' => $cat_id, 'hide_empty' => false ));
    
    if ($children){
        return true;
    }
    return false;
}

function has_Parents($cur_cat){
    
    if($cur_cat->parent == 0){
        return false;
    }else{
        return true;
    }
}

function is_Parent($cur_cat_id, $item_cat_id){

	if($cur_cat_id == $item_cat_id)	return true;
	else return false;
}


//add_filter('acf/load_field/name=wawi_cat_id', 'disable_wawi_cat_id');
function disable_wawi_cat_id( $field ){ $field['disabled']='1'; return $field; }
add_filter('acf/load_field/name=wawi_parent_id', 'disable_wawi_parent_id');
function disable_wawi_parent_id( $field ){ $field['disabled']='1'; return $field; }


add_action('acf/save_post', 'my_acf_save_post', 5);
function my_acf_save_post( $post_id ) {
	echo var_export($_POST['acf'],true);
    // Get previous values.
    $prev_values = get_fields( $post_id );

    // Get submitted values.
    $values = $_POST['acf'];

    // Check if a specific value was updated.
    if( isset($_POST['acf']['term_id_for_cat']) ) {
        // Do something.
		echo var_export($_POST['acf']['term_id_for_cat'],true);
    }
}
function my_acf_update_value( $value, $post_id, $field, $original ) {
	/* echo var_export($value,true); */
    if( is_string($value) ) {
        $value = str_replace( 'Old Company Name', 'New Company Name',  $value );
    }
	$value = "ttttttx".$post_id/* .var_export($field,true) */;
    return $value;
}

// Apply to all fields.
//add_filter('acf/update_value', 'my_acf_update_value', 10, 4);


// API Route für Metavalue Suche (Kategorie IDs vom Warenwirtschaftssystem)
add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/product_cat_search', '/meta_search/', array(
            'methods' => 'GET', 
            'callback' => 'wawi_id_search_meta_query' 
    ) );
});

// Do the actual query and return the data
function wawi_id_search_meta_query(){
    if(isset($_GET['meta_key']) && isset($_GET['meta_value'])) {
		remove_all_filters('terms_clauses');
		add_filter('terms_clauses','wc_terms_clauses',99,3);
        // Run a custom query
			$args = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'meta_query' => array(
					 array(
						'key'       => $_GET['meta_key'],
						'value'     => $_GET['meta_value'],
						'compare'   => '='
					 )
				)
			);
			$data = get_terms($args);
			$return['term_id'] = $data[0]->term_id;
			$return['wawi_cat_id'] = get_field("wawi_cat_id",$data[0]);
			$return['wawi_parent_id'] = get_field("wawi_parent_id",$data[0]);

			$args_parent = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'meta_query' => array(
					 array(
						'key'       => "wawi_cat_id",
						'value'     => $return['wawi_parent_id'],//"__EN_ID__6F4D91D8-F533-85DB-1CD0-0601E27503CD",//"__EN_ID__07B637DB-8A05-265B-83DF-4DCD7A58DA06",
						'compare'   => '='
					 )
				)
			);
			$data_parent = get_terms($args_parent);
			$return['parent_term_id'] = $data_parent[0]->term_id;
			if(empty($data_parent[0]->term_id))$return['parent_term_id'] = 0;

/* 
			$term_query = new WP_Term_Query();
			$testreturn = $term_query->query($args_parent); */

		return $return;//wp_parse_args2($args);//$term_query;

    }
}
/* global $wp_filter;
unset( $wp_filter['terms_clauses']->callbacks[10]['00000000000006a90000000000000000terms_clauses'] );
unset( $wp_filter['terms_clauses']->callbacks[99]['wc_terms_clauses'] ); */
/* $term_query = new WP_Term_Query();
remove_filter('terms_clauses', array($term_query,'00000000000006a90000000000000000terms_clauses'),10);
remove_filter('terms_clauses', array($term_query,'wc_terms_clauses'),99); */
/* remove_all_filters('terms_clauses');
add_filter('terms_clauses','wc_terms_clauses',99,3); */
////////

/* SELECT * FROM `2Egh2_term_taxonomy` TT 
LEFT JOIN 2Egh2_terms T 
ON TT.term_id=T.term_id 
LEFT JOIN 2Egh2_termmeta TM
ON T.term_id=TM.term_id
WHERE TT.taxonomy="product_cat"; */
/* function print_filters_for( $hook = 'terms_clauses' ) {
    global $wp_filter;
    if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
        return;

    
    return  $wp_filter[$hook] ;
    
}
function wp_parse_args2( $args, $defaults = array() ) {
	if ( is_object( $args ) ) {
		$parsed_args = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$parsed_args =& $args;
	} else {
		wp_parse_str( $args, $parsed_args );
	}

	if ( is_array( $defaults ) && $defaults ) {
		return array_merge( $defaults, $parsed_args );
	}
	return $args;
} */
/* add_filter('term_clauses',"testfunction");
function testfunction($test){
	return $test;
} */
/* remove_filter('term_clauses','00000000000007010000000000000000terms_clauses',10);
add_action('wp_head', function(){
	
	echo "<pre>".var_export(print_filters_for(),true)."</pre>";
	$args_parent = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'meta_query' => array(
		   array(
			'key'       => "wawi_cat_id",
			'value'     => "6F4D91D8-F533-85DB-1CD0-0601E27503CD",//$return['wawi_parent_id'],
			'compare'   => '='
		   )
		)
	  );
	  $data_parent = get_terms($args_parent);
	  
	  
	  $term_query = new WP_Term_Query();
	  $testreturn = $term_query->query($args_parent);
	  echo "<pre style='color:rebeccapurple;'>".var_export($data_parent,true)."</pre>";
}); */

/* add_filter( 'get_terms', function($terms, $query_vars_taxonomy, $query_vars, $term_query){
	$testreturn = [
		"terms" => $terms,
		"vars_tax" => $query_vars_taxonomy,
		"vars" =>$query_vars,
		"query" => $term_query
	];
	return $testreturn;
}, 10, 4 ); */
////////
remove_action('woocommerce_single_product_summary','woocommerce_template_single_title',5);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);

remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart',10);
/* remove_action('woocommerce_single_product_summary','woocommerce_template_single_excerpt',20);
remove_action('woocommerce_single_product_summary','woocommerce_template_single_meta',40);
 */
remove_action('woocommerce_after_single_product_summary','woocommerce_output_product_data_tabs',10);


remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 30 );
    //add_action( 'woocommerce_single_product_summary', 'woocommerce_single_variation', 5 );

add_action('outline_single_product', 'woocommerce_template_single_title', 5);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 5);

add_action('woocommerce_single_product_summary', 'woocommerce_template_single_artist', 25);
if(is_user_logged_in()){
	add_action('outline_after_single_product', 'woocommerce_template_single_price', 20);
	add_action('woocommerce_before_add_to_cart_button', 'woocommerce_template_single_vpe', 2);
	add_action('outline_after_single_product','woocommerce_template_single_add_to_cart',10);
}else{
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
	remove_action('woocommerce_before_add_to_cart_button', 'woocommerce_template_single_vpe', 2);
	remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
}

add_filter('woocommerce_product_related_products_heading',function(){return 'Weitere Artikel dieser Kategorie';});

function woocommerce_template_single_artist() {
	wc_get_template( 'single-product/artist.php' );
}

function woocommerce_template_single_vpe() {
	wc_get_template( 'single-product/vpe.php' );
}

add_filter( 'wc_add_to_cart_message_html', 'message_quantity',10,3);

function message_quantity($message, $products, $show_qty){


	return $message;//var_export($products,true);
}


/* 
array (  'ID' => 129,
  'key' => 'field_65df4526cec81',
    'label' => 'term_id_for_cat',
	  'name' => 'group_wawi_id_term_id_for_cat',
	    'aria-label' => '',
		  'prefix' => 'acf',
		    'type' => 'text',
			  'value' => NULL, 
			   'menu_order' => 0,
			     'instructions' => '',
				   'required' => 0,
				     'id' => '',
					   'class' => '',
					     'conditional_logic' => 0,
						   'parent' => 128,
						     'wrapper' =>   array (    
								 'width' => '',
								     'class' => '', 
									    'id' => '',  ), 
										 'default_value' => '',
										   'maxlength' => '',
										     'placeholder' => '',
											   'prepend' => '', 
											    'append' => '', 
												 '_name' => 'term_id_for_cat', 
												  '_valid' => 1,) */
/**
 * ============================
 * MANUAL TOP-LEVEL MENU URLS
 * ============================
 */
function my_get_manual_parent_menu_url($slug, $lang = 'de') {
    $urls = [
        'de' => [
            'grusskarten'              => '/grusskarten/',
            'kunstdrucke'              => '/kunstdrucke/',
            'originalgrafik'           => '/originalgrafik/',
            'papeterie-und-sonstiges'  => '/papeterie-und-sonstiges/',
        ],
        'fr' => [
             'grusskarten'              => '/fr/grusskarten/',
            'kunstdrucke'              => '/fr/kunstdrucke/',
            'originalgrafik'           => '/fr/originalgrafik/',
            'papeterie-und-sonstiges'  => '/fr/papeterie-und-sonstiges/',
        ],
      
        'en' => [
            'grusskarten'              => '/en/grusskarten/',
            'kunstdrucke'              => '/en/kunstdrucke/',
            'originalgrafik'           => '/en/originalgrafik/',
            'papeterie-und-sonstiges'  => '/en/papeterie-und-sonstiges/',
        ],
    ];

    if (!empty($urls[$lang][$slug])) {
        return home_url($urls[$lang][$slug]);
    }

    return '';
}
/**
 * ============================
 * CONFIG
 * ============================
 */
function my_menu_allowed_parent_slugs() {
    return [
        'grusskarten',
        'kunstdrucke',
        'originalgrafik',
        'papeterie-und-sonstiges', // keep original slug
    ];
}

/**
 * ============================
 * MAIN SHORTCODE
 * ============================
 */
function product_categories_hierarchy_shortcode() {
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'de';

    $cache_key = 'my_cached_header_menu_' . $lang;
    $menu_html = get_transient($cache_key);

    if ($menu_html === false) {
        $menu_html = my_build_cached_category_menu_html($lang);

        // Cache for 7 days
        set_transient($cache_key, $menu_html, 7 * DAY_IN_SECONDS);
    }

    return $menu_html;
}
add_shortcode('product_categories_hierarchy', 'product_categories_hierarchy_shortcode');

/**
 * ============================
 * BUILD MENU HTML (ONLY WHEN CACHE MISSES)
 * ============================
 */
function my_build_cached_category_menu_html($lang = 'de') {
    if (function_exists('pll_switch_language')) {
        pll_switch_language($lang);
    }

    $allowed_parent_slugs = my_menu_allowed_parent_slugs();

    // STEP 1: get all categories in current language in ONE query
    $all_terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'lang'       => $lang,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    if (is_wp_error($all_terms) || empty($all_terms)) {
        return '';
    }

    // STEP 2: build lookup arrays
    $terms_by_id = [];
    $children_map = [];

    foreach ($all_terms as $term) {
        $terms_by_id[$term->term_id] = $term;
        $children_map[$term->parent][] = $term->term_id;
    }

    // STEP 3: preload which categories actually have products
    $categories_with_products = my_get_categories_with_products($lang);

    // STEP 4: recursively determine which categories should be shown
    $visible_cache = [];
    foreach ($terms_by_id as $term_id => $term) {
        my_category_has_products_or_visible_children(
            $term_id,
            $children_map,
            $categories_with_products,
            $visible_cache
        );
    }

    // STEP 5: build only your allowed top-level categories in exact order
    $html = '<ul class="page-nav__menu megamenu">';

    foreach ($allowed_parent_slugs as $slug) {
        $parent_term = get_term_by('slug', $slug, 'product_cat');

        if (!$parent_term || is_wp_error($parent_term)) {
            continue;
        }

        // Translate DE source term to current language
        if (function_exists('pll_get_term') && $lang !== 'de') {
            $translated_id = pll_get_term($parent_term->term_id, $lang);
            if ($translated_id) {
                $parent_term = get_term($translated_id, 'product_cat');
            }
        }

        if (!$parent_term || is_wp_error($parent_term)) {
            continue;
        }

            if (empty($visible_cache[$parent_term->term_id])) {
            continue;
        }

        $display_name = ($parent_term->name === 'Papeterie und Sonstiges') ? 'Papeterie' : $parent_term->name;

        $parent_url = my_get_manual_parent_menu_url($slug, $lang);

        // fallback to normal category URL if no manual URL exists
        if (empty($parent_url)) {
            $parent_url = get_term_link($parent_term);
        }

        $html .= '<li class="megamenu__item">';
        $html .= '<a class="megamenu__link" href="' . esc_url($parent_url) . '">'
              . esc_html(function_exists('pll__') ? pll__($display_name) : $display_name)
              . '</a>';

        $html .= my_render_category_children(
            $parent_term->term_id,
            $children_map,
            $terms_by_id,
            $visible_cache,
            1,
            $lang
        );

        $html .= '</li>';
    }

    // Add extra static menu links
    $html .= my_render_extra_menu_links($lang);

    $html .= '</ul>';

    return $html;
}

/**
 * ============================
 * GET CATEGORIES THAT HAVE PRODUCTS (ONE QUERY)
 * ============================
 */
function my_get_categories_with_products($lang = 'de') {
    global $wpdb;

    $taxonomy = 'product_cat';

    $query = new WP_Query([
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'lang'                   => $lang,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if (empty($query->posts)) {
        return [];
    }

    $product_ids = array_map('intval', $query->posts);
    $product_ids_sql = implode(',', $product_ids);

    if (empty($product_ids_sql)) {
        return [];
    }

    $term_ids = $wpdb->get_col("
        SELECT DISTINCT tt.term_id
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE tr.object_id IN ($product_ids_sql)
        AND tt.taxonomy = '{$taxonomy}'
    ");

    return array_map('intval', $term_ids);
}

/**
 * ============================
 * DETERMINE IF CATEGORY OR DESCENDANTS SHOULD SHOW
 * ============================
 */
function my_category_has_products_or_visible_children($term_id, $children_map, $categories_with_products, &$visible_cache) {
    if (isset($visible_cache[$term_id])) {
        return $visible_cache[$term_id];
    }

    if (in_array($term_id, $categories_with_products, true)) {
        $visible_cache[$term_id] = true;
        return true;
    }

    if (!empty($children_map[$term_id])) {
        foreach ($children_map[$term_id] as $child_id) {
            if (my_category_has_products_or_visible_children($child_id, $children_map, $categories_with_products, $visible_cache)) {
                $visible_cache[$term_id] = true;
                return true;
            }
        }
    }

    $visible_cache[$term_id] = false;
    return false;
}

/**
 * ============================
 * RENDER CHILDREN RECURSIVELY (NO DB QUERIES HERE)
 * ============================
 */
function my_render_category_children($parent_id, $children_map, $terms_by_id, $visible_cache, $depth = 1, $lang = 'de') {
    if (empty($children_map[$parent_id])) {
        return '';
    }

    $children = $children_map[$parent_id];

    // sort alphabetically by current language term name
    usort($children, function($a, $b) use ($terms_by_id) {
        return strcasecmp($terms_by_id[$a]->name, $terms_by_id[$b]->name);
    });

    $output = '';

    if ($depth === 1) {
        $output .= '<ul class="megamenu__nested">';
    } else {
        $output .= '<ul class="megamenu__nested megamenu__nested--columned">';
    }

    foreach ($children as $child_id) {
        if (empty($visible_cache[$child_id])) {
            continue;
        }

        if (empty($terms_by_id[$child_id])) {
            continue;
        }

        $child = $terms_by_id[$child_id];

        // skip unwanted categories
        if (in_array($child->name, ['Unkategorisiert', 'Uncategorized'], true) || strpos($child->name, 'Testkategorie') !== false) {
            continue;
        }

        $display_name = ($child->name === 'Papeterie und Sonstiges') ? 'Papeterie' : $child->name;

        $output .= '<li class="megamenu__item">';
        $output .= '<a class="megamenu__link" href="' . esc_url(get_term_link($child)) . '">'
                . esc_html(function_exists('pll__') ? pll__($display_name) : $display_name)
                . '</a>';

        $output .= my_render_category_children(
            $child_id,
            $children_map,
            $terms_by_id,
            $visible_cache,
            $depth + 1,
            $lang
        );

        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;
}

/**
 * ============================
 * EXTRA LINKS
 * ============================
 */
function my_render_extra_menu_links($lang) {
    switch ($lang) {
        case 'de':
            $aktuelles = esc_url(get_permalink(80));
            $ueberuns  = esc_url(get_permalink(82));
            $kontakt   = esc_url(get_permalink(84));
            break;
        case 'fr':
            $aktuelles = esc_url(get_permalink(121169));
            $ueberuns  = esc_url(get_permalink(70348));
            $kontakt   = esc_url(get_permalink(121171));
            break;
        case 'en':
            $aktuelles = esc_url(get_permalink(439013));
            $ueberuns  = esc_url(get_permalink(440697));
            $kontakt   = esc_url(get_permalink(439627));
            break;
        default:
            return '';
    }

    $html  = '<li class="megamenu__item special-item"><a class="megamenu__link" href="' . $aktuelles . '">' . esc_html(function_exists('pll__') ? pll__('Aktuelles') : 'Aktuelles') . '</a></li>';
    $html .= '<li class="megamenu__item special-item"><a class="megamenu__link" href="' . $ueberuns . '">' . esc_html(function_exists('pll__') ? pll__('Über uns') : 'Über uns') . '</a></li>';
    $html .= '<li class="megamenu__item special-item"><a class="megamenu__link" href="' . $kontakt . '">' . esc_html(function_exists('pll__') ? pll__('Kontakt') : 'Kontakt') . '</a></li>';

    return $html;
}

/**
 * ============================
 * CLEAR CACHE WHEN PRODUCTS/CATEGORIES CHANGE
 * ============================
 */
function my_clear_cached_header_menus() {
    delete_transient('my_cached_header_menu_de');
    delete_transient('my_cached_header_menu_fr');
    delete_transient('my_cached_header_menu_en');
}
add_action('save_post_product', 'my_clear_cached_header_menus');
add_action('created_product_cat', 'my_clear_cached_header_menus');
add_action('edited_product_cat', 'my_clear_cached_header_menus');
add_action('delete_product_cat', 'my_clear_cached_header_menus');

/**
 * ============================
 * OPTIONAL WEEKLY REBUILD VIA CRON
 * ============================
 */
function my_rebuild_all_cached_menus() {
    foreach (['de', 'fr', 'en'] as $lang) {
        $html = my_build_cached_category_menu_html($lang);
        set_transient('my_cached_header_menu_' . $lang, $html, 7 * DAY_IN_SECONDS);
    }
}
add_action('my_weekly_menu_rebuild_event', 'my_rebuild_all_cached_menus');

function my_add_weekly_cron_schedule($schedules) {
    if (!isset($schedules['weekly'])) {
        $schedules['weekly'] = [
            'interval' => 7 * DAY_IN_SECONDS,
            'display'  => __('Once Weekly'),
        ];
    }
    return $schedules;
}
add_filter('cron_schedules', 'my_add_weekly_cron_schedule');

function my_schedule_weekly_menu_rebuild() {
    if (!wp_next_scheduled('my_weekly_menu_rebuild_event')) {
        wp_schedule_event(time(), 'weekly', 'my_weekly_menu_rebuild_event');
    }
}
add_action('init', 'my_schedule_weekly_menu_rebuild');


// Rest Api Route Produkt nach Name suchen

/* $args = array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'name' => 'Postkarten "Everyday"'
);
$data = get_terms($args);
echo "<pre style='color:tomato';>".var_export($data[0]->term_id,true)."</pre>"; */


add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/product_cat_search', '/search_by_name/', array(
            'methods' => 'GET', 
            'callback' => 'get_cat_id_by_name' 
    ) );
});

// Do the actual query and return the data
function get_cat_id_by_name(){
	return get_taxonomy_hierarchy('product_cat');
    if(isset($_GET['category_name'])) {

        // Run a custom query
			$args = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'name' => $_GET['category_name']
			);
			$data = get_terms($args);
			$return['term_id'] = $data[0]->term_id;

		return $return;

    }else{
		return false;
	}
}


function get_taxonomy_hierarchy( $taxonomy, $parent = 0 ) {
	// only 1 taxonomy
	$taxonomy = is_array( $taxonomy ) ? array_shift( $taxonomy ) : $taxonomy;

	// get all direct decendants of the $parent
	$terms = get_terms( $taxonomy, array( 'hide_empty' => false,'parent' => $parent,'lang' => 'de' ) );
  
	// prepare a new array.  these are the children of $parent
	// we'll ultimately copy all the $terms into this new array, but only after they
	// find their own children
	$children = array();
	$children2 = [];
	// go through all the direct decendants of $parent, and gather their children
	foreach ( $terms as $term ){
		// recurse to get the direct decendants of "this" term
		$term->children = get_taxonomy_hierarchy( $taxonomy, $term->term_id );
		$children2[str_replace("&amp;","&",$term->name)] = get_taxonomy_hierarchy( $taxonomy, $term->term_id );
		$children2[str_replace("&amp;","&",$term->name)]['id'] = $term->term_id;
		$children2[str_replace("&amp;","&",$term->name)]['language'] = pll_get_term_language($term->term_id);
		$children2[str_replace("&amp;","&",$term->name)]['translations'] = pll_get_term_translations($term->term_id);
		// add the term to our new array
    //echo $term->name."<br>";
		$children[ str_replace("&amp;","&",$term->name) ] = (array)$term;
	}

	// send the results back to the caller
	return (array)$children2;
}

 pll_register_string("Product-Category", "Grußkarten","WooCommerce");
pll_register_string("Product-Category", "Papeterie und Sonstiges","WooCommerce");
pll_register_string("Product-Category", "Papeterie","WooCommerce");

	pll_register_string("Product-Category", "Klappkarten \"Christmas\"","WooCommerce");
		pll_register_string("Product-Category", "Gutschein","WooCommerce");
		pll_register_string("Product-Category", "Kartenboxen","WooCommerce");
		pll_register_string("Product-Category", "Weihnachtsfreude","WooCommerce");

	pll_register_string("Product-Category", "Klappkarten \"Everyday\"","WooCommerce");
		pll_register_string("Product-Category", "Glücksbringer","WooCommerce");
	pll_register_string("Product-Category", "Postkarten \"Christmas\"","WooCommerce");
	pll_register_string("Product-Category", "Postkarten \"Everyday\"","WooCommerce");
		pll_register_string("Product-Category", "Alltagsparadies","WooCommerce");
	pll_register_string("Product-Category", "Künstler A - E","WooCommerce");
	pll_register_string("Product-Category", "Künstler F - J","WooCommerce");
	pll_register_string("Product-Category", "Künstler K - O","WooCommerce");
	pll_register_string("Product-Category", "Künstler P - T","WooCommerce");
	pll_register_string("Product-Category", "Künstler U - Z","WooCommerce");

	pll_register_string("Product-Category", "Sonstiges","WooCommerce");
		pll_register_string("Product-Category", "Fotorahmen","WooCommerce");

		pll_register_string("Product-Category", "Abreißblock","WooCommerce");
		pll_register_string("Product-Category", "Adressbücher","WooCommerce");
		pll_register_string("Product-Category", "Adventskalender","WooCommerce");
		pll_register_string("Product-Category", "Briefpapier","WooCommerce");
		pll_register_string("Product-Category", "Einkaufsblock","WooCommerce");
		pll_register_string("Product-Category", "Einkaufslisten","WooCommerce");
		pll_register_string("Product-Category", "Faltmappen","WooCommerce");
		pll_register_string("Product-Category", "Freundebücher","WooCommerce");
		pll_register_string("Product-Category", "Geschenkanhänger (Weihn.)","WooCommerce");
		pll_register_string("Product-Category", "Geschenkanhänger XXL","WooCommerce");
		pll_register_string("Product-Category", "Geschenkpapier","WooCommerce");
		pll_register_string("Product-Category", "Geschenkpapier (Weihn.)","WooCommerce");
		pll_register_string("Product-Category", "Girlande (Weihn.)","WooCommerce");
		pll_register_string("Product-Category", "Hefte im Oktav-Buchformat","WooCommerce");
		pll_register_string("Product-Category", "Hefte, DIN A5","WooCommerce");
		pll_register_string("Product-Category", "Hefte, DIN A6","WooCommerce");
		pll_register_string("Product-Category", "Hochzeitskollektion","WooCommerce");
		pll_register_string("Product-Category", "Kalender / Planer","WooCommerce");
		pll_register_string("Product-Category", "Notizblöcke, liniert","WooCommerce");
		pll_register_string("Product-Category", "Notizbücher, DIN A4","WooCommerce");
		pll_register_string("Product-Category", "Notizbücher, DIN A5","WooCommerce");
		pll_register_string("Product-Category", "Notizbücher, DIN A6","WooCommerce");
		pll_register_string("Product-Category", "Schmuckkuverts","WooCommerce");
		pll_register_string("Product-Category", "Spiralblöcke, DIN A5","WooCommerce");
		pll_register_string("Product-Category", "Spiralblöcke, DIN A6","WooCommerce");
		pll_register_string("Product-Category", "Splendid Notes, DIN A5","WooCommerce");
		pll_register_string("Product-Category", "Splendid Notes, DIN A6","WooCommerce");

pll_register_string("Product-Category", "Kunstdrucke","WooCommerce");
pll_register_string("Product-Category", "Originalgrafik","WooCommerce");

pll_register_string("Product-Category-Template", "Produktserie ansehen","WooCommerce");
pll_register_string("Product-Category-Template", "Produkt ansehen","WooCommerce");

pll_register_string("Menu-Item", "Aktuelles","Wordpress");
pll_register_string("Menu-Item", "Über uns","Wordpress");
pll_register_string("Menu-Item", "Kontakt","Wordpress");


pll_register_string("Cart-Page", "Einheiten","WooCommerce");
pll_register_string("Cart-Page", "St","WooCommerce");

pll_register_string("Page-Home", "Unser Vorschlag für den","WordPress");
pll_register_string("Page-Home", "Januar","WordPress");
pll_register_string("Page-Home", "Februar","WordPress");
pll_register_string("Page-Home", "März","WordPress");
pll_register_string("Page-Home", "April","WordPress");
pll_register_string("Page-Home", "Mai","WordPress");
pll_register_string("Page-Home", "Juni","WordPress");
pll_register_string("Page-Home", "Juli","WordPress");
pll_register_string("Page-Home", "August","WordPress");
pll_register_string("Page-Home", "September","WordPress");
pll_register_string("Page-Home", "Oktober","WordPress");
pll_register_string("Page-Home", "November","WordPress");
pll_register_string("Page-Home", "Dezember","WordPress");

pll_register_string("Page-Home", "Startseite","WordPress");

pll_register_string("Footer", "Datenschutz","WordPress");
pll_register_string("Footer", "Impressum","WordPress");
pll_register_string("Footer", "Agb","WordPress");

pll_register_string("Header", "Händlerservice","WordPress");
pll_register_string("Header", "Onboarding","WordPress");
pll_register_string("Header", "Anmelden","WordPress");
pll_register_string("Header-Search", "Suchen...","WordPress");
pll_register_string("Onboardingformular", "Sprache","WordPress");
pll_register_string("Onboardingformular", "Kundennummer","WordPress");
pll_register_string("Onboardingformular", "Postleitzahl","WordPress");
pll_register_string("Onboardingformular", "Account reaktivieren","WordPress");

pll_register_string("Onboardingformular", "Kundennummer fehlt.","WordPress");
pll_register_string("Onboardingformular", "Account nicht gefunden.","WordPress");
pll_register_string("Onboardingformular", "Postleitzahl fehlt.","WordPress");
pll_register_string("Onboardingformular", "Account erfolgreich reaktiviert.","WordPress");
pll_register_string("Onboardingformular", 'Account wurde bereits reaktiviert. Sie können sich nun auf der <a href="/mein-konto/">Login-Seite</a> anmelden.',"WordPress");

pll_register_string("Login", "Anmelden","WooCommerce");
pll_register_string("Login", "Konto","WooCommerce");
pll_register_string("Product-Page", "Weitere Artikel dieser Kategorie","WooCommerce");
//pll_register_string("Product-Page", "Artikelnummer","WooCommerce");
pll_register_string("Product-Page", "Beschreibung","WooCommerce");
pll_register_string("Product-Page", "Künstler","WooCommerce");
pll_register_string("Product-Page", "Maße (H x B)","WooCommerce");
pll_register_string("Product-Page", "Derzeit nicht lieferbar","WooCommerce");
pll_register_string("Cart-Page", "Artikelnummer","WooCommerce");
pll_register_string("Orders-Page", "Bild","WooCommerce");
pll_register_string("Orders-Page", "Product","WooCommerce");
pll_register_string("Orders-Page", "Total","WooCommerce");

remove_all_actions('woocommerce_after_main_content');
/**
 * Handle a custom 'customvar' query var to get products with the 'customvar' meta.
 * @param array $query - Args for WP_Query.
 * @param array $query_vars - Query vars from WC_Product_Query.
 * @return array modified $query
 */
function handle_custom_query_var( $query, $query_vars ) {
	if ( ! empty( $query_vars['vertriebsgebiet'] ) ) {
		
		$query['meta_query'][] = array(
			'relation' => 'OR',
				array(
					'key' => 'vertriebsgebiet',
					'value' => esc_attr( $query_vars['vertriebsgebiet'] ),
					'compare' => "="
				), array(
					'key' => 'vertriebsgebiet',
					'value' => "Q",
					'compare' => "="
				)
			);
	}

	return $query;
}
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'handle_custom_query_var', 10, 2 );



//add_action( 'woocommerce_register_form_start','onboard_info' );
function onboard_info(){
	echo "<p>Aufgrund einer Umstellung des Shops müssen bestehende Benutzeraccounts um E-Mail und ein neues Passwort ergänzt werden.</p>";
}

//add_action( 'woocommerce_register_form_notice', 'test_notice' );
function test_notice(){
	echo "<p>Wenn Sie bereits einen Account hatten, können Sie ihn hier durch Eingabe ihrer alten Zugangsdaten reaktivieren.</p>";
}
add_action( 'woocommerce_register_form_start', 'add_register_form_field' );
function add_register_form_field(){
	/* if(!is_account_page()){ */
		/* woocommerce_form_field(
			'phone_number',
			array(
				'type'        => 'tel',
				'required'    => true, // just adds an "*"
				'label'       => 'Alter Nutzername'
			),
			( isset( $_POST[ 'phone_number' ] ) ? $_POST[ 'phone_number' ] : '' )
		); */
		woocommerce_form_field(
			'kundennummer',
			array(
				'type'        => 'text',
				'required'    => is_account_page() ? false : true, // just adds an "*"
				'label'       => pll__('Kundennummer'),
			),
			( isset( $_POST[ 'kundennummer' ] ) ? $_POST[ 'kundennummer' ] : '' )
		);
		woocommerce_form_field(
			'plz',
			array(
				'type'        => 'text',
				'required'    => true, // just adds an "*"
				'label'       => pll__('Postleitzahl')
			),
			( isset( $_POST[ 'plz' ] ) ? $_POST[ 'plz' ] : '' )
		);
		woocommerce_form_field(
			'is_custom_form',
			array(
				'type'        => 'hidden'
			),
			( !is_account_page() ? 'custom-form' : 'regular-form' )
		);
		woocommerce_form_field(
			'account_aktiviert',
			array(
				'type'        => 'hidden'
			),
			true
		);
		woocommerce_form_field(
			'is_active',
			array(
				'type'        => 'hidden'
			),
			true
		);
		if(is_account_page()){
			woocommerce_form_field(
				'billing_company',
				array(
					'type'        => 'text',
					'required'    => false, // just adds an "*"
					'label'       => 'Firma<abbr class="required" title="erforderlich">*</abbr>'
				),
				( isset( $_POST[ 'billing_company' ] ) ? $_POST[ 'billing_company' ] : '' )
			);
			woocommerce_form_field(
				'billing_address_1',
				array(
					'type'        => 'text',
					'required'    => false, // just adds an "*"
					'label'       => 'Straße'
				),
				( isset( $_POST[ 'billing_address_1' ] ) ? $_POST[ 'billing_address_1' ] : '' )
			);
			
		}

		/* woocommerce_form_field(
				'locale',
				array(
					'type'        => 'select',
					'required'    => true, // just adds an "*"
					'label'       => 'Sprache',
					'options'	  => ["de_DE" => "Deutsch", "fr_FR" => "Französisch"]
				),
				( isset( $_POST[ 'locale' ] ) ? $_POST[ 'locale' ] : '' )
			); */
	/* } */
	
}
add_action( 'woocommerce_register_form', 'add_register_form_field_after' );
function add_register_form_field_after(){
	
	$lang_code = pll_current_language();
	$lang = "de_DE";
	switch($lang_code){
		case "de":
			$lang = "de_DE";
			break;
		case "fr":
			$lang = "fr_FR";
			break;
		case "en":
			$lang = "en_GB";
			break;
	}

		woocommerce_form_field(
				'locale',
				array(
					'type'        => 'select',
					'required'    => true, // just adds an "*"
					'label'       => pll__('Sprache'),
					'options'	  => ["de_DE" => "Deutsch", "fr_FR" => "Français", "en_GB" => "English"],
					'default'	  => $lang
				),
				( isset( $_POST[ 'locale' ] ) ? $_POST[ 'locale' ] : null )
			);
	/* } */
	
}
// save to database
add_action( 'woocommerce_created_customer', 'save_register_fields' );
function save_register_fields( $customer_id ){
	
	if ( isset( $_POST[ 'kundennummer' ] ) ) {
		update_user_meta( $customer_id, 'kundennummer', wc_clean( $_POST[ 'kundennummer' ] ) );
	}
	if ( isset( $_POST[ 'plz' ] ) ) {
		update_user_meta( $customer_id, 'plz', wc_clean( $_POST[ 'plz' ] ) );
	}
	if ( isset( $_POST[ 'locale' ] ) ) {
		update_user_meta( $customer_id, 'locale', wc_clean( $_POST[ 'locale' ] ) );
	}
	if ( isset( $_POST[ 'account_aktiviert' ] ) ) {
		update_user_meta( $customer_id, 'account_aktiviert', wc_clean( $_POST[ 'account_aktiviert' ] ) );
	}
/* 	if ( isset( $_POST[ 'is_custom_form' ] ) ) {
		update_user_meta( $customer_id, 'is_custom_form', wc_clean( $_POST[ 'is_custom_form' ] ) );
	}
	 */
}

add_action( 'woocommerce_register_post', 'misha_validate_fields', 10, 3 );
function misha_validate_fields( $username, $email, $errors ) {
	
	$test = get_users(array(
		'meta_key' => 'billing_postcode',
		'meta_value' => '66666'
	));
	$user = get_users(
		array(
			/* 'fields' => 'all_with_meta', */
			'role__in' => ['customer',"customer_fr","customer_de","customer_q"],
			'meta_query' => array(
				array(
					'key' => 'billing_postcode',
					'value' => $_POST[ 'plz' ],
					'compare' => '=='
				),
				array(
					'key' => 'kundennummer',
					'value' => $_POST[ 'kundennummer' ],
					'compare' => '=='
				)/* ,
				array(
					'key' => 'is_active',
					'value' => false,
					'compare' => '=='
				) */
			)
		)
	);
	$meta = get_user_meta($user[0]->ID,"account_aktiviert");
/* echo "<pre>".var_export($user[0]->ID).'</pre>';
echo "<pre>".var_export($errors->get_error_codes()).'</pre>'; */
	if($_POST[ 'is_custom_form' ] == 'custom-form'){
		if( empty( $_POST[ 'kundennummer' ] ) && !is_account_page()) {
			$errors->add( 'kundennummer_error', pll__('Kundennummer fehlt.') );
		}
	
	
		if( empty( $_POST[ 'plz' ] ) && !is_account_page()) {
			$errors->add( 'plz_error', pll__('Postleitzahl fehlt.') );
		}
		if( empty( $user ) && !is_account_page()) {
			$errors->add( 'account_error', pll__('Account nicht gefunden.') );
		}
		if( $meta[0] ) {
			$errors->add( 'account_error', pll__('Account wurde bereits reaktiviert. Sie können sich nun auf der <a href="/mein-konto/">Login-Seite</a> anmelden.') );
		}
	}	
	/* if( !empty( $user ) && !is_account_page() || true) {
		$errors->add( 'account_error', 'Account nicht gefunden.'."<pre>".var_export($user).'</pre>' );
	} */
	
}

add_filter( 'woocommerce_new_customer_data', "registration_update_user" );

function registration_update_user($userdata){
	/* echo "<pre>".var_export($_POST).'</pre>'; */
	$user = get_users(
		array(
			'role__in' => ['customer',"customer_fr","customer_de","customer_q"],
			'meta_query' => array(
				array(
					'key' => 'billing_postcode',
					'value' => $_POST[ 'plz' ],
					'compare' => '=='
				),
				array(
					'key' => 'kundennummer',
					'value' => $_POST[ 'kundennummer' ],
					'compare' => '=='
				)
			)
		)
	);

	if(isset( $_POST['is_custom_Form']) )
		$userdata['is_custom_form'] = $_POST['is_custom_form'];
	$userdata['user_pass'] = wp_hash_password($userdata['user_pass']);
	$userdata['ID'] = $user[0]->ID;
	$userdata['locale'] = $_POST["locale"];
	//echo var_export($userdata);
	/* if(email_exists() == $user[0]->ID){
		$user_data['user_email'] = "";
	} */
	//file_put_contents(__DIR__.'/'."testuser.txt",var_export($userdata,true),FILE_APPEND);
	return $userdata;
}

add_filter('is_custom_form_filter', function($custom_form){
	if(isset( $_POST['is_custom_form']) )
		$custom_form = $_POST/* ['is_custom_form'] */;
	return $custom_form;
});
function iconic_remove_password_strength() {

    wp_enqueue_script( 'wc-password-strength-meter','https://actetre.de/wp-content/plugins/woocommerce/assets/js/frontend/password-strength-meter.min.js' );
}
add_action( 'wp_enqueue_scripts', 'iconic_remove_password_strength', 10 );
/* add_action( 'woocommerce_register_form' , 'add_form_notice',10);
function add_form_notice(){
	echo "Info zum Account wiederherstellen.";
} */
/* 
add_filter( 'woocommerce_product_object_query', "test_args",10,2 );
function test_args($results, $args){
	echo "<pre style='color:green';>".var_export($args,true)."</pre>";
	return $result;
} */

/* Stop auto login */
add_filter( 'woocommerce_registration_auth_new_customer', '__return_false' );

add_filter('woocommerce_add_success', 'change_registration_message');
function change_registration_message($message){
	if($_POST[ 'is_custom_form' ] == 'custom-form'){
		if($message == __( 'Your account was created successfully. Your login details have been sent to your email address.', 'woocommerce' ) ){
			$message = pll__('Account erfolgreich reaktiviert.');
		}
	}else{
		return $message;
	}
	
	return $message;
}

add_action('woocommerce_registration_redirect', 'redirect_after_registration');

function redirect_after_registration(){
	return get_permalink(woocommerce_get_page_id('myaccount'));
}
/* 
function user_autologout(){
	if ( is_user_logged_in() ) {
			 $current_user = wp_get_current_user();
			 $user_id = $current_user->ID;
			 $approved_status = get_user_meta($user_id, 'wp-approve-user', true);
			 //if the user hasn't been approved yet by WP Approve User plugin, destroy the cookie to kill the session and log them out
	 if ( $approved_status == 1 ){
		 return $redirect_url;
	 }
			 else{
		 wp_logout();
					 return get_permalink(woocommerce_get_page_id('myaccount')) . "?approved=false";
			 }
	 }
}
add_action('woocommerce_registration_redirect', 'user_autologout', 2);
function registration_message(){
	 $not_approved_message = '<p class="registration">Send in your registration application today!<br /> NOTE: Your account will be held for moderation and you will be unable to login until it is approved.</p>';
	 if( isset($_REQUEST['approved']) ){
			 $approved = $_REQUEST['approved'];
			 if ($approved == 'false')  echo '<p class="registration successful">Registration successful! You will be notified upon approval of your account.</p>';
			 else echo $not_approved_message;
	 }
	 else echo $not_approved_message;
}
add_action('woocommerce_before_customer_login_form', 'registration_message', 2); */
/**
 * Remove WooCommerce notice when user registers new account
 */
function wcmo_remove_new_registration_notice( $notice_message ) {
	/* echo var_export($notice_message,true); */
	/* if( strpos( $notice_message, 'Your account was created successfully' ) !== false ) {
	return '';
	} */
	return $notice_message;
   }
   add_filter( 'woocommerce_add_success', 'wcmo_remove_new_registration_notice', 99, 1 );
 
add_shortcode('free_registration', 'test_free_registration');

function test_free_registration(){
	ob_start(); // Start output buffering
	wc_get_template('myaccount/form-login2.php');
	return ob_get_clean();
}

if(is_user_logged_in()){
	remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',20);
}
remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10);

function add_vg_customer_roles(){

	$default_customer_role = get_role('customer');
	
	remove_role('customer_de');
	remove_role('customer_fr');
	remove_role('customer_q');
	add_role("customer_de", "Kunde DE", $default_customer_role->capabilities);
	add_role("customer_fr", "Kunde FR", $default_customer_role->capabilities);
	add_role("customer_q", "Kunde Q", $default_customer_role->capabilities);
}

add_action('init', 'add_vg_customer_roles');

//add_filter('woocommerce_data_get_wcfad_original_price', 'remove_wcfad_price');

function remove_wcfad_price($value){
	$value = 1.00;
	return $value;
}
add_filter('update_product_metadata', 'remove_wcfad_price2');

function remove_wcfad_price2( $value, $object_id, $meta_key, $meta_value, $prev_value){
	
	if($meta_key = 'wcfad_original_price'){
		$value = false;
	}
return true;
	return $value;
}


add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/remove_meta_wcfad_price', '/remove_price/', array(
            'methods' => 'GET', 
            'callback' => 'remove_wcfad_price3' 
    ) );
});

function remove_wcfad_price3(){
	$return = false;
	if(isset($_GET['ID'])){
		$return = delete_metadata("product",$_GET['ID'],'wcfad_original_price','',true);
	}
	if($return){
		return $_GET["ID"]." erfolgreich!";
	}else{
		return $_GET["ID"]." erfolgreich!";
	}
}

add_filter('woocommerce_get_price',"remove_wcfad_original_price4",20,2);
function remove_wcfad_original_price4($price,$product){
	delete_metadata("product",$product->ID,'wcfad_original_price');
	$product->delete_meta_data('wcfad_original_price');
	$product->save_meta_data();
	return $price;
}

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/customers', '/change_role/', array(
            'methods' => 'POST', 
            'callback' => 'change_customer_role' 
    ) );
});

function change_customer_role($data){
	/* $result['email'] = $data['email'];
  return $posts; */
  if(isset($data['ID']) && isset($data['customer_role'])){
	if(!in_array($data['customer_role'],['customer_q','customer_de','customer_fr','customer']) ){
		$result['error'] = "Die Änderung der Rolle zu ".$data['customer_role']." ist nicht erlaubt.";
		return $result;
	}
	$u = new WP_User( $data['ID'] );

	// Replace the current role with 'editor' role
	$u->set_role( $data['customer_role'] );
	foreach($data['meta_data'] as $meta){
		update_user_meta($data['ID'],$meta['key'],$meta['value']);
		
	}
	$result["success"] = "Benutzerrolle für Benutzer-ID ".$data['ID']." zu ". $data['customer_role'] ." geändert" ;
	return $result;
  }else{
	$result["error"] = "ID oder Benutzerrolle nicht übergeben";
	return $result;
  }
	
}

function init_billing_fields(){
	register_meta('user', 'billing_company', [
		'type' => 'string',
		'single' => true,
		'show_in_rest' => true
	]);
}

add_action('rest_api_init','init_billing_fields');

// Test deaktivieren, prüfen ob notwendig ############
/* add_filter( 
	'rest_pre_dispatch', 
	function (mixed $result, WP_REST_Server $server, WP_REST_Request $request) { */

		//return $result;

		/* $request['meta_data'][5] =  [
			 "key" => "billing_company",
            "value"=> "Maison de la Presse Lanet"
		];
            */
/* 	$request_data = json_decode($request->get_body(),true); 
	  // Get the route being requested
	  if(isset($request_data['username'])){
		$request_data['username'] = wc_create_new_customer_username($request_data['username']);
		$request->set_body(json_encode($request_data,JSON_PRETTY_PRINT));
	  }
	  
	  
	  return $result;//json_encode($request_data,JSON_PRETTY_PRINT);
	  
  },10,3); */
/* 
  function lang_redirect(){
	$term = get_queried_object();  
	$user_language = get_user_locale();
	
	if($user_language == "de_DE")
		$lang = "de";
	if($user_language == "fr_FR")
		$lang = "fr";
	
	if(get_class($term) == "WP_Post" ){
		$translated_post = pll_get_post(get_the_id(),$lang);
		$target = get_permalink($translated_post);
		echo "POST <br>".$target."<br>";
		wp_redirect($target);
		exit;
	}else if(get_class($term) == "WP_Term"){
		$translated_term = pll_get_term($term->term_id,$lang);
		$target = get_term_link($translated_term);
		echo "TERM <br>".$target."<br>";
		wp_redirect($target);
		exit;
	}
  }

  add_action('template_redirect','lang_redirect'); */
add_filter('woocommerce_login_redirect', 'ui_wc_login_redirect', 99, 2);

function ui_wc_login_redirect($url, $user) {

	$user_language = get_user_locale($user);
	if($user_language == "de_DE")
		$lang = "de";
	if($user_language == "fr_FR")
		$lang = "fr";
	if($user_language == "en_GB")
		$lang = "en";

	$return_url = pll_get_post(wc_get_page_id( 'myaccount' ),$lang);
	$url = get_permalink($return_url);
	//echo var_export("Test: ".$url,true);
  return $url;
}

add_action( 'woocommerce_edit_account_form', 'add_locale_to_edit_account_form' );
function add_locale_to_edit_account_form() {
    $user = wp_get_current_user();
    ?>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="favorite_color"><?php _e( 'Sprache', 'woocommerce' ); ?></label>
        
        <select class="woocommerce-Input woocommerce-Input--text input-text" name="locale" id="locale" value="<?php echo esc_attr( $user->locale ); ?>">
			<option value="de_DE">Deutsch</option>
			<option value="fr_FR">Français</option>
			<option value="en_GB">English</option>
		</select>
    </p>
    <?php
}

// Save the custom field 'locale' 
add_action( 'woocommerce_save_account_details', 'save_locale_account_details', 12, 1 );
function save_locale_account_details( $user_id ) {
    // For Favorite color
    if( isset( $_POST['locale'] ) )
        update_user_meta( $user_id, 'locale', sanitize_text_field( $_POST['locale'] ) );

    /* // For Billing email (added related to your comment)
    if( isset( $_POST['account_email'] ) )
        update_user_meta( $user_id, 'billing_email', sanitize_text_field( $_POST['account_email'] ) ); */
}

add_action( 'rest_api_init', 'get_user_id_by_mail');


function get_user_id_by_mail() {
    //Path to meta query route
    register_rest_route( 'wp/v2/customers', '/get-id/', array(
            'methods' => 'POST', 
            'callback' => 'get_u_id' 
    ) );
}

function get_u_id($data){
	$user = get_user_by("email",$data['email']);
	$result['ID'] = $user->ID;
	return $result/* ['ID'] */;
}

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/categories', '/set-images/', array(
            'methods' => 'GET', 
            'callback' => 'set_category_images' 
    ) );
});


function set_category_images(){
	
	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false
	);
	$data = get_terms($args);
	//$test = array();$i=0;
	foreach($data as $cat){/* $i++;
		$values = [$cat->term_id => [
			"cat" => $cat->slug,
			pll_get_term_language($cat->term_id)
		]];
		//$test[$cat->term_id] = $values;
		array_push($test,$values); */
		$image_id = "";
		$featured_products/* [$cat->slug] */ = wc_get_products(array(
			'status'               => 'publish',
			'visibility'           => 'visible',
			'category'			   => $cat->slug,
			'return'               => 'ids'
		));
		//$featured_products[$cat->slug]['lang'] = pll_get_term_language($cat->term_id);

		foreach($featured_products as $index => $id_value){
			
			if(isset($featured_products[$index])){
				$product_id = $featured_products[$index];
			
				// Get the product object based on the product ID
				
				if(!empty($featured_products)){
			
					$product   = wc_get_product( $product_id );

					// Retrieve the image ID associated with the product
					$image_id  = $product->get_image_id();
					
					if(!empty($image_id)){
						update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
						break;
					}
					
					// Get the image URL using the image ID and specify the image size ('full' in this case)
					$image_url = wp_get_attachment_image_url( $image_id, 'full' );
					$arrLinks[$cat->term_id] = $image_id;
				}else{
					$arrLinks[$cat->term_id] = "no image found for " . $cat->slug;
				}
	
				if($index >= count( $featured_products ) - 1 ){
					
					$image_id = '4';
					update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
					break;
				} 
			}
			
		}

		
	}
	return "done!";
}
remove_all_filters('woocommerce_checkout_fields');
function disable_require_address_fields( $fields ) {
    //echo "<pre>".var_export($fields,true)."</pre>";
	//file_put_contents( __DIR__.'/'."checkout.txt",var_export($fields,true)."\r\n-------------------\r\n",FILE_APPEND);
    /* if(WC()->session){
       if(WC()->session->get("special_cat_in_cart")){
        $fields['address_1']['required'] = false;
        $fields['address_2']['required'] = false;
        $fields['city']['required'] = false;
        $fields['postcode']['required'] = false;
        $fields['phone']['required'] = false;
        } 
    } */
	/* $fields['billing-first_name']['placeholder'] = "༼ つ ◕_◕ ༽つ";
	$fields['billing_first_name']['placeholder'] = "༼ つ ◕_◕ ༽つ"; */
	$fields['billing_first_name']['required'] = false;
   
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_country']);
    //unset($fields['order']['order_comments']);

    $fields['billing']['billing_company']['custom_attributes'] = ['readonly'=>'readonly'];//"༼ つ ◕_◕ ༽つ";
    $fields['billing']['billing_email']['custom_attributes'] = ['readonly'=>'readonly'];//"༼ つ ◕_◕ ༽つ";
    $fields['billing']['billing_postcode']['custom_attributes'] = ['readonly'=>'readonly'];//"༼ つ ◕_◕ ༽つ";

	
	//unset($fields);
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'disable_require_address_fields' ,99999 );


function puri_woocommerce_get_availability_text( $text, $product ) {
    if (!$product->is_in_stock()) {
        $text = pll__("Derzeit nicht lieferbar");
    } else {
    // You can add more conditions here. e.g if product is available.
    // $text = 'Available right now';
    }
    return $text;
}

add_filter( 'woocommerce_get_availability_text', 'puri_woocommerce_get_availability_text', 999, 2);


add_filter( 'gettext', 'wpdocs_translate_text', 10, 3 );
function wpdocs_translate_text( $translated_text, $untranslated_text, $domain ) {
	
	if ( str_contains($untranslated_text, "From your account dashboard you can view your") && pll_current_language() == "de") {
		return 'In Ihrer Kontoübersicht können Sie Ihre <a href=\"%1$s\">letzten Bestellungen</a> ansehen, Ihre <a href=\"%2$s\">Rechnungsadresse</a> verwalten und Ihre <a href=\"%3$s\">Passwort und die Kontodetails bearbeiten</a>.';
	}else{
		return $translated_text;
	}

}
add_filter( 'gettext', 'wpdocs_translate_text2', 10, 3 );
function wpdocs_translate_text2( $translated_text, $untranslated_text, $domain ) {

	if ( str_contains($untranslated_text, "This will be how your name will be displayed in the account section and in reviews") && pll_current_language() == "de") {
		return 'So wird Ihr Name im Konto-Bereich und in den Bewertungen angezeigt';
	}else{
		return $translated_text;
	}

}
add_filter( 'gettext', 'wpdocs_translate_text3', 10, 3 );
function wpdocs_translate_text3( $translated_text, $untranslated_text, $domain ) {

	if ( str_contains($untranslated_text, "Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.") && pll_current_language() == "de") {
		return 'Haben Sie Ihr Passwort vergessen? Bitte geben Sie Ihren Benutzernamen oder Ihre E-Mail-Adresse ein. Sie erhalten einen Link per E-Mail, womit Sie sich ein neues Passwort erstellen können.';
	}else{
		return $translated_text;
	}

}
add_filter( 'gettext', 'wpdocs_translate_text4', 10, 3 );
function wpdocs_translate_text4( $translated_text, $untranslated_text, $domain ) {

	if ( $untranslated_text == "Your order" && pll_current_language() == "de") {
		return 'Ihre Bestellung';
	}else{
		return $translated_text;
	}

}

add_filter( 'gettext', 'wpdocs_translate_text5', 10, 3 );
function wpdocs_translate_text5( $translated_text, $untranslated_text, $domain ) {

	if ( $untranslated_text == "Notes about your order, e.g. special notes for delivery." && pll_current_language() == "de") {
		return 'Anmerkungen zu Ihrer Bestellung, z.B. besondere Hinweise für die Lieferung.';
	}else{
		return $translated_text;
	}

}
add_filter( 'gettext', 'wpdocs_translate_text6', 10, 3 );
function wpdocs_translate_text6( $translated_text, $untranslated_text, $domain ) {

	if ( str_contains($untranslated_text, "Sorry, \"%s\" is not in stock. Please edit your cart and try again. We apologize for any inconvenience caused.") && pll_current_language() == "de") {
		return 'Leider ist „%s“ nicht vorrätig. Bitte entfernen Sie als nicht lieferbar markierte Artikel und versuchen es erneut. Wir entschuldigen uns für eventuelle Unannehmlichkeiten.';
	}else{
		return $translated_text;
	}

}
add_filter( 'gettext', 'wpdocs_translate_text8', 10, 3 );
function wpdocs_translate_text8( $translated_text, $untranslated_text, $domain ) {

	if ( str_contains($untranslated_text, "No products were found matching your selection.") && pll_current_language() == "de") {
		return 'Es wurden keine Produkte gefunden, die Ihrer Auswahl entsprechen.';
	}else{
		return $translated_text;
	}

}
add_filter( 'ngettext', 'wpdocs_translate_text7', 10, 5 );
function wpdocs_translate_text7( $translation, $single, $plural, $number, $domain ) {

	if ( str_contains($plural, "%s have been added to your cart.") && pll_current_language() == "de") {
		return '%s wurden Ihrem Warenkorb hinzugefügt.';
	}else{
		return $translation;
	}
	if ( str_contains($single, "%s has been added to your cart.") && pll_current_language() == "de") {
		return '%s wurde Ihrem Warenkorb hinzugefügt2.';
	}else{
		return $translation;
	}

}

/* 
add_action( 'woocommerce_email_header', 'add_customer_number_to_mail',10,2 );

function add_customer_number_to_mail($email_heading, $email){
 echo "test header 123";
} */

remove_action('woocommerce_checkout_terms_and_conditions','wc_terms_and_conditions_page_content',30);


/**
* Change the number of related products
*//**
* Change the number of related products
*/
/* function woo_related_products_limit() {
	global $product;
	
	$args['posts_per_page'] = 6;
	return $args;
	}

	add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
	function jk_related_products_args( $args ) {
	$args['posts_per_page'] = 1; // 4 related products
	 // arranged in 2 columns
	return $args;
	}


	add_filter('woocommerce_related_products', 'add_related_products');
function add_related_products($related_product_ids)
{
    // WC source code stores IDs as string in this array, so I did that too
    $related_product_ids[] = '414640';
    return $related_product_ids;
} */


add_filter( 'woocommerce_related_products', 'bbloomer_related_products_by_same_title', 9999, 3 ); 
 
function bbloomer_related_products_by_same_title( $related_posts, $product_id, $args ) {




	global $post;
	$terms = get_the_terms( $product_id, 'product_cat' );
	foreach ($terms as $term) {
		if(!get_term_children($term->term_id,'product_cat'))
			$termX = $term->slug;
	
	}


	$related_posts = wc_get_products( array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'category' => $termX,
		'return' => 'ids',
		'exclude' => array( $product_id ),
	));
	
   return $related_posts;
}



add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/categories', '/set-images-4/', array(
            'methods' => 'GET', 
            'callback' => 'set_category_images_4' 
    ) );
});


function set_category_images_4(){
	
	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false
	);
	$data = get_terms($args);
	
	foreach($data as $cat){
		
		$image_id = "";
		$featured_products = wc_get_products(array(
			'status'               => 'publish',
			'visibility'           => 'visible',
			'limit'           	   => -1,
			'category'			   => $cat->slug,
			'return'               => 'ids'
		));
//if($cat->term_id = 28508){
if(empty($featured_products))continue;

//$pr[] = $featured_products;
		foreach($featured_products as $index => $id_value){
			//$pr[] = $id_value;
			if(isset($featured_products[$index])){
				
				$product_id = $featured_products[$index];
			
				// Get the product object based on the product ID
				
				if(!empty($featured_products)){
			
					$product   = wc_get_product( $product_id );
					$lang = pll_get_post_language($product_id);
					
					$cat_lang = pll_get_term_language($cat->term_id);$pr[] = $id_value." | ".$lang." | ".$cat_lang." | ".count($featured_products);
					if($lang == "de" || $cat_lang == "de")continue;
					// Retrieve the image ID associated with the product
					$image_id  = $product->get_image_id();
					//$result[] = $index."trueX".$lang.$cat_lang;
					if(!empty($image_id) && $lang == "fr" && $cat_lang == "fr"){
						//$result[] = $index."true".$lang.$cat_lang;
						if(get_field("vertriebsgebiet",$product_id) == "FR"){
							$updated = update_field("kategorie_bild_fr",$image_id,"category_".$cat->term_id);
							$check_update[$product_id] = $updated;
						}else if(get_field("vertriebsgebiet",$product_id) == "Q"){
							if(!$check_update[$product_id]){
								$updated = update_field("kategorie_bild_fr",$image_id,"category_".$cat->term_id);
								$check_update[$product_id."Q"] = $updated;
							}else{
								continue;
							}
							
						}else{
							continue;
						}
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => $updated ? "erfolgreich" : "fehlgeschlagen",
						];
						//update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
						
					}else if($lang == "fr" && $cat_lang == "fr"){
						//$result[] = $index."false".$lang.$cat_lang;
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => "übersprungen",
						];
						//continue;
					}else{
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => "ignoriert",
						];
						continue;
					}
					
					// Get the image URL using the image ID and specify the image size ('full' in this case)
					/* $image_url = wp_get_attachment_image_url( $image_id, 'full' );
					$arrLinks[$cat->term_id] = $image_id; */
				}/* else{
					$arrLinks[$cat->term_id] = "no image found for " . $cat->slug;
				} */
	
				/* if($index >= count( $featured_products ) - 1 ){
					
					$image_id = '4';
					update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
					break;
				}  */
			}
			//wp_reset_postdata();
		}
	//}

		
	}//$result = $pr;
	return $check_update;
}

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/categories', '/set-images-5/', array(
            'methods' => 'GET', 
            'callback' => 'set_category_images_5' 
    ) );
});


function set_category_images_5(){
	
	$args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false
	);
	$data = get_terms($args);
	
	foreach($data as $cat){
		
		$image_id = "";
		$featured_products = wc_get_products(array(
			'status'               => 'publish',
			'visibility'           => 'visible',
			'limit'           	   => -1,
			'category'			   => $cat->slug,
			'return'               => 'ids'
		));
//if($cat->term_id = 28508){
if(empty($featured_products))continue;

//$pr[] = $featured_products;
		foreach($featured_products as $index => $id_value){
			//$pr[] = $id_value;
			if(isset($featured_products[$index])){
				
				$product_id = $featured_products[$index];
			
				// Get the product object based on the product ID
				
				if(!empty($featured_products)){
			
					$product   = wc_get_product( $product_id );
					$lang = pll_get_post_language($product_id);
					
					$cat_lang = pll_get_term_language($cat->term_id);$pr[] = $id_value." | ".$lang." | ".$cat_lang." | ".count($featured_products);
					if($lang == "fr" || $cat_lang == "fr")continue;
					// Retrieve the image ID associated with the product
					$image_id  = $product->get_image_id();
					//$result[] = $index."trueX".$lang.$cat_lang;
					if(!empty($image_id) && $lang == "de" && $cat_lang == "de"){
						//$result[] = $index."true".$lang.$cat_lang;
						if(get_field("vertriebsgebiet",$product_id) == "DE"){
							$updated = update_field("kategorie_bild_de",$image_id,"category_".$cat->term_id);
							$check_update[$product_id] = $updated;
						}else if(get_field("vertriebsgebiet",$product_id) == "Q"){
							if(!$check_update[$product_id]){
								$updated = update_field("kategorie_bild_de",$image_id,"category_".$cat->term_id);
								$check_update[$product_id] = $updated;
							}else{
								continue;
							}
							
						}else{
							continue;
						}
							
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => $updated ? "erfolgreich" : "fehlgeschlagen",
						];
						//update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
						
					}else if($lang == "de" && $cat_lang == "de"){
						//$result[] = $index."false".$lang.$cat_lang;
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => "übersprungen",
						];
						//continue;
					}else{
						$result[] = [
							"term" => $cat->term_id,
							"term_name" => $cat->name,
							"prod_id" => $product_id,
							"lang" => $lang,
							"img" => $image_id,
							"index" => $index,
							//"cat" => $cat,
							"update" => "ignoriert",
						];
						continue;
					}
					
					// Get the image URL using the image ID and specify the image size ('full' in this case)
					/* $image_url = wp_get_attachment_image_url( $image_id, 'full' );
					$arrLinks[$cat->term_id] = $image_id; */
				}/* else{
					$arrLinks[$cat->term_id] = "no image found for " . $cat->slug;
				} */
	
				/* if($index >= count( $featured_products ) - 1 ){
					
					$image_id = '4';
					update_term_meta($cat->term_id, 'thumbnail_id', $image_id);
					break;
				}  */
			}
			//wp_reset_postdata();
		}
	//}

		
	}//$result = $pr;
	return $check_update;
}



add_action( 'add_meta_boxes', 'order_meta_box' ,9999);
 
function order_meta_box() {
	add_meta_box( 'custom_box', 'Bestellung exportieren', 'order_export_meta_box', 'woocommerce_page_wc-orders', 'advanced', 'high' );
}

function order_export_meta_box($post) {
	//global $post; // OPTIONALLY USE TO ACCESS ORDER POST


   $api_key = "ck_9ed0ba52cae1bc4dd934bc344cc8c3908c2ea4b9";
   $api_secret = "cs_95f7227b9409b1fb3526d51359384fad51580f89";

   $base_url = "https://actetre.de/";
   $endpoint_url = "wp-json/wc/v3/orders/";

   $order_id = $post->get_id();

   $api_export_url = $base_url.$endpoint_url.$order_id;

   $curr_user = wp_get_current_user()->user_login;

   // Bestellung laden

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $api_export_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
 


   $xmlstr = curl_exec($ch);
   curl_close($ch);
   $curl_response = json_decode($xmlstr,true);

   $date_paid = date("d.m.Y",strtotime($curl_response['date_paid']));
   $time_paid = date("H:i:s",strtotime($curl_response['date_paid']));

   
   $export_file_name = "order_export_".$curl_response['id'].".csv";
   if(empty($curl_response['id']))
	   $export_file_name = "order_export_testresponse.csv";

   // Datei erstellen 
   $head_row = "K;1;".get_field("kundennummer","user_".$curl_response['customer_id']).";;;;;;;;;;;;;".$curl_response['id'].";".$date_paid.";".$time_paid.";".$time_paid.";;".$curl_response['billing']['company'].";".$curl_response['billing']['address_1'].";".$curl_response['billing']['address_2'].";".$curl_response['billing']['postcode'].";".$curl_response['billing']['city']."\r\n";


   file_put_contents(__DIR__.'/tmp_order_export_files/'.$export_file_name,$head_row);

   // Bestellpositionen durchlaufen
   $positions = $curl_response['line_items'];
   $positions_row = "";

   foreach($positions as $index => $single){
	   $positions_row .= "P;".$positions[$index]['sku'].";".$positions[$index]["quantity"].";".number_format($positions[$index]["price"],2,",","").";;;0"."\r\n";
   }

   file_put_contents(__DIR__.'/tmp_order_export_files/'.$export_file_name,$positions_row,FILE_APPEND);

   $upload_overrides = array( 
	   'test_form' => false ,
	   'action' => 'handle_order_export'
   );
 


   $title = $export_file_name;
   $filename = __DIR__.'/'.$export_file_name;
   // Check the type of file. We'll use this as the 'post_mime_type'.
   $filetype = wp_check_filetype( basename( $export_file_name ), null );

   add_filter('upload_dir', function ($upload) use ($order_id){

	   $upload['subdir'] = "/order_export/".$order_id;

	   $upload['path'] = $upload['basedir'] . $upload['subdir'];
	   $upload['url'] = $upload['baseurl'] . $upload['subdir'];

	   $upload['subdir2'] = "/order_export/".$order_id;
	   return $upload;
	 },6387,1);
   $wp_upload_dir = wp_upload_dir(null,true);
   $wp_upload_dir['custom_subdir'] = "order_export/" . $order_id;

   WP_Filesystem();
   global $wp_filesystem;
   $upload_path = $wp_upload_dir['basedir'] .$wp_upload_dir['subdir']."/".$export_file_name;
   $upload_path_exists = $wp_filesystem->exists( $upload_path ) ;

   if(!$upload_path_exists){
	   
   
   $file = array(
		   'name' => $export_file_name,
		   'type' => "text/csv",
		   'tmp_name' => __DIR__.'/tmp_order_export_files/'.$export_file_name,
		   'file' => $upload_path . '/' . basename( $filename ),
		   'size' => filesize(__DIR__.'/'.$export_file_name)
	   );
   // Prepare an array of post data for the attachment.
   $attachment = array(
	   'guid'           => $wp_upload_dir['baseurl'] . $wp_upload_dir['subdir'] . basename( $filename ), 
	   'post_mime_type' => $filetype['type'],
	   'post_title'     => sanitize_text_field( $title ),
	   'post_content'   => '',
	   'post_status'    => 'inherit',
	   'post_parent'    => $order_id,
	   'lang' 			 => 'de'
   );

   $insert = wp_insert_attachment($attachment,$wp_upload_dir['subdir']."/$export_file_name");
   pll_set_post_language($insert,'de');
   $attach_data = wp_generate_attachment_metadata( $insert, $export_file_name );
   $attach_data = [
					   "created_by" => $curr_user,
					   "creation_date" => current_time('mysql'),
					   "downloads" => 0,
				   ];
   wp_update_attachment_metadata( $insert, $attach_data );

   

   $movefile = wp_handle_upload( $file,$upload_overrides);
   

}
   $args = array(
	   'posts_per_page' => 1,
	   'order'          => 'ASC',
	   'post_mime_type' => 'text/csv',
	   'post_parent'    => $order_id,
	   'post_status'    => null,
	   'post_type'      => 'attachment',
   );
   $children = get_posts($args);

   $media = get_attached_media( 'text/csv' , $order_id );

   foreach($children as $child){
	   if($child->post_name == "order_export_".$order_id."-csv"){
		   $insert = $child->ID;
		   $attach_data = wp_get_attachment_metadata( $insert);
	   }
   }

   $last_download = strtotime($attach_data['last_download']) != 0 ? wp_date("d.m.Y H:i:s",strtotime($attach_data['last_download'])+3600) : " - ";

	echo "<p style='display:grid;'><a id='order_export_dl_link' onclick='ajaxDownloadCounter(".$order_id.");' style='max-width: 136px;' class='button' href='".wp_get_attachment_url($insert)."' download>Datei herunterladen</a>";
	echo "<span><b>Exportdatei erstellt: </b>".wp_date("d.m.Y H:i:s"/* get_option('date_format') */,strtotime($attach_data['creation_date'])+3600)."</span>";
	echo "<span><b>Erstellt von: </b>".$attach_data['created_by']."</span>";
	echo "<span><b>Downloads: </b><span id='dl_counter-".$order_id."'>".$attach_data['downloads']."</span></span>
   <span><b>Letzter Download: </b><span id='dl_time-".$order_id."'>".$last_download."</span></span>
	<div id='order_id' class='hidden'>".$insert."</div>
	<div id='attach_id_".$order_id."' class='hidden'>".$insert."</div>
	</p>";

	
}


function export_download_counter_ajax(){
   global $post;
   wp_enqueue_script( 'download_counter_ajax', get_template_directory_uri() . '/js/order_export_dl.js', [] ,false,['in_footer' => false] );
   wp_localize_script( 'download_counter_ajax', 'download_counter_ajax_unique', array('ajaxurl' => admin_url( 'admin-ajax.php' ),'title' => 'sdfasdf' ) );
   wp_enqueue_script( 'order_export_create_ajax', get_template_directory_uri() . '/js/order_export_create.js', [] ,false,['in_footer' => false] );
   wp_localize_script( 'order_export_create_ajax', 'order_export_create_ajax_unique', array('ajaxurl' => admin_url( 'admin-ajax.php' ),'title' => 'sdfasdf' ) );
}

add_action( 'admin_enqueue_scripts', 'export_download_counter_ajax' );

function count_order_export_downloads(){
   $attach_data = wp_get_attachment_metadata( $_POST['attach_id']);
   
   $attach_data["downloads"] = $attach_data["downloads"]+1;
   $attach_data['last_download'] = current_time('mysql');
   wp_update_attachment_metadata( $_POST['attach_id'], $attach_data );

   $responseData = array("ID" => $_POST['attach_id'],'counter' => $attach_data['downloads'], 'dl_time' => wp_date("d.m.Y H:i:s",strtotime($attach_data['last_download'])+3600));
   echo wp_send_json($responseData);
   wp_die();
}
add_action('wp_ajax_count_order_export_downloads', 'count_order_export_downloads');


function create_order_export_file(){
	   //global $post; // OPTIONALLY USE TO ACCESS ORDER POST

	   $api_key = "ck_9ed0ba52cae1bc4dd934bc344cc8c3908c2ea4b9";
	   $api_secret = "cs_95f7227b9409b1fb3526d51359384fad51580f89";
	
	   $base_url = "https://actetre.de/";
	   $endpoint_url = "wp-json/wc/v3/orders/";
	
	   $order_id = $_POST['order_id'];
	
	   $api_export_url = $base_url.$endpoint_url.$order_id;
	
	   $curr_user = wp_get_current_user()->user_login;
	
	   // Bestellung laden
	
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $api_export_url);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
	   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	   curl_setopt($ch, CURLOPT_USERPWD, "$api_key:$api_secret");
	
	
	   $xmlstr = curl_exec($ch);
	   curl_close($ch);
	   $curl_response = json_decode($xmlstr,true);
	
	   $date_paid = date("d.m.Y",strtotime($curl_response['date_paid']));
	   $time_paid = date("H:i:s",strtotime($curl_response['date_paid']));
	
	   
	   $export_file_name = "order_export_".$curl_response['id'].".csv";
	
	   // Datei erstellen 
	   $head_row = "K;1;".get_field("kundennummer","user_".$curl_response['customer_id']).";;;;;;;;;;;;;".$curl_response['id'].";".$date_paid.";".$time_paid.";".$time_paid.";;".$curl_response['billing']['company'].";".$curl_response['billing']['address_1'].";".$curl_response['billing']['address_2'].";".$curl_response['billing']['postcode'].";".$curl_response['billing']['city']."\r\n";
	
	
	
	   file_put_contents(__DIR__.'/tmp_order_export_files/'.$export_file_name,$head_row);
	
	   // Bestellpositionen durchlaufen
	   $positions = $curl_response['line_items'];
	   $positions_row = "";
	
	   foreach($positions as $index => $single){
		   $positions_row .= "P;".$positions[$index]['sku'].";".$positions[$index]["quantity"].";".number_format($positions[$index]["price"],2,",","").";;;0"."\r\n";
	   }
	
	   file_put_contents(__DIR__.'/tmp_order_export_files/'.$export_file_name,$positions_row,FILE_APPEND);
	
	   $upload_overrides = array( 
		   'test_form' => false ,
		   'action' => 'handle_order_export'
	   );
	
	
	   $title = $export_file_name;
	   $filename = __DIR__.'/'.$export_file_name;
	   // Check the type of file. We'll use this as the 'post_mime_type'.
	   $filetype = wp_check_filetype( basename( $export_file_name ), null );
	
	   add_filter('upload_dir', function ($upload) use ($order_id){
	
		   $upload['subdir'] = "/order_export/".$order_id;
	
		   $upload['path'] = $upload['basedir'] . $upload['subdir'];
		   $upload['url'] = $upload['baseurl'] . $upload['subdir'];
	
		   $upload['subdir2'] = "/order_export/".$order_id;
		   return $upload;
		 },6387,1);
	   $wp_upload_dir = wp_upload_dir(null,true);
	   $wp_upload_dir['custom_subdir'] = "order_export/" . $order_id;
	
	
	   WP_Filesystem();
	   global $wp_filesystem;
	   $upload_path = $wp_upload_dir['basedir'] .$wp_upload_dir['subdir']."/".$export_file_name;
	   $upload_path_exists = $wp_filesystem->exists( $upload_path ) ;
	
	   if($upload_path_exists){
		   $responseData = array("ID" => $_POST['order_id'],"needs_action" => false);
		   echo wp_send_json($responseData);
		   wp_die();
	   }else{
	 
	   
	   $file = array(
			   'name' => $export_file_name,
			   'type' => "text/csv",
			   'tmp_name' => __DIR__.'/tmp_order_export_files/'.$export_file_name,
			   'file' => $upload_path . '/' . basename( $filename ),
			   'size' => filesize(__DIR__.'/'.$export_file_name)
		   );
	   // Prepare an array of post data for the attachment.
	   $attachment = array(
		   'guid'           => $wp_upload_dir['baseurl'] . $wp_upload_dir['subdir'] . basename( $filename ), 
		   'post_mime_type' => $filetype['type'],
		   'post_title'     => sanitize_text_field( $title ),
		   'post_content'   => '',
		   'post_status'    => 'inherit',
		   'post_parent'    => $order_id,
		   'lang' 			 => 'de'
	   );
	
	   $insert = wp_insert_attachment($attachment,$wp_upload_dir['subdir']."/$export_file_name");
	   pll_set_post_language($insert,'de');
	   $attach_data = wp_generate_attachment_metadata( $insert, $export_file_name );
	   $attach_data = [
						   "created_by" => $curr_user,
						   "creation_date" => current_time('mysql'),
						   "downloads" => 0,
					   ];
	   wp_update_attachment_metadata( $insert, $attach_data );
	
	   $movefile = wp_handle_upload( $file,$upload_overrides);
	   
	
	}
	   $args = array(
		   'posts_per_page' => 1,
		   'order'          => 'ASC',
		   'post_mime_type' => 'text/csv',
		   'post_parent'    => $order_id,
		   'post_status'    => null,
		   'post_type'      => 'attachment',
	   );
	   $children = get_posts($args);
	
	   $media = get_attached_media( 'text/csv' , $order_id );
	
	   foreach($children as $child){
		   if($child->post_name == "order_export_".$order_id."-csv"){
			   $insert = $child->ID;
			   $attach_data = wp_get_attachment_metadata( $insert);
		   }
	   }

	   $attach_data['creation_date'] = date("d.m.Y",strtotime($attach_data['creation_date'])+3600);

   $responseData = array('attach_data' => $attach_data, "ID" => $_POST['order_id'],"needs_action" => true,"dl_link" => wp_get_attachment_url($insert));
   echo wp_send_json($responseData);
   wp_die();
}
add_action('wp_ajax_create_order_export_file', 'create_order_export_file');


function add_export_to_orders_table( $columns ) {


	   // let's add our column before "Total"
	   $columns = array_slice( $columns, 0, 4, true ) // 4 columns before
	   + array( 'order_export' => 'Export' ) // our column is going to be 5th
	   + array_slice( $columns, 4, NULL, true );
   
	   return $columns;
}
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'add_export_to_orders_table' );
add_filter( 'manage_edit-shop_order_columns', 'add_export_to_orders_table' );

add_action('manage_shop_order_posts_custom_column', 'display_export_data', 20, 2);
add_action('manage_woocommerce_page_wc-orders_custom_column', 'display_export_data', 20, 2);


function display_export_data($column_name, $order_or_order_id) {
   // legacy CPT-based order compatibility
   $order = $order_or_order_id instanceof WC_Order ? $order_or_order_id : wc_get_order( $order_or_order_id );

   if( 'order_export' === $column_name ) {


$testarr = array (
   'numberposts' => -1,
   'post_type' => 'attachment',
   'post_status' => 'any',
   'post_parent' => $order->id,
   'post_mime_type' => 'text/csv',
   'posts_per_page' => -1,
   'orderby' => 'menu_order',
   'order' => 'ASC',
);
$testposts = get_posts($testarr);
$att_url = wp_get_attachment_url($testposts[0]->ID);
$meta = wp_get_attachment_metadata($testposts[0]->ID,true);

if(!empty($meta)){
   $link_id = "order_export_dl_link_".$order->ID;
   $link_class = "order_dl_link";
   $dl_link = wp_get_attachment_url($testposts[0]->ID)."' download><span class='dashicons dashicons-download' style='color:green;'></span>Download";
   $js_click = "onclick='ajaxDownloadCounter(".$order->ID.")'";

   $created = wp_date("d.m.Y",strtotime($meta['creation_date'])+3600);
   $by_user = $meta['created_by'];
   $downloads = $meta['downloads'];
   $last_dl = wp_date("d.m.Y H:i:s",strtotime($meta['last_download'])+3600);
}else{
   $link_id = "order_export_create_file_".$order->ID;
   $link_class = "order_create_file";
   $dl_link = "javascript:;'><span class='dashicons dashicons-plus-alt' style='color:red;'></span>Erstellen";
   $js_click = "onclick='ajaxCreateFile(".$order->ID.")'";

   $created = " - ";
   $by_user = " - ";
   $downloads = " - ";
   $last_dl = " - ";
}

$last_download = strtotime($meta['last_download']) != 0 ? wp_date("d.m.Y H:i:s",strtotime($meta['last_download'])+3600) : " - ";

echo "<div id='loading-".$order->ID."' style='display:none;' class='loading-state'><div class='loader'></div></div><p class='table_order_export' style='display:grid;'><a id='".$link_id."' ".$js_click." style='max-width: 136px;' class='button ".$link_class."' href='".$dl_link."</a></p>";
echo "<p style='display:grid;'><span><b>Erstellt: </b><span id='created-".$order->ID."'>".$created."</span></span>";
echo "<span><b>Von: </b><span id='by_user-".$order->ID."'>".$by_user."</span></span>";
echo "<span><b>Downloads: </b><span id='dl_counter-".$order->ID."'>".$downloads."</span></span>";
echo "<span><b>letzter Download: </b><span id='dl_time-".$order->ID."'>".$last_download."</span></span>
<div id='attach_id_".$order->ID."' class='hidden'>".$testposts[0]->ID."</div>
<div id='order_id_".$order->ID."' class='hidden'>".$order->ID."</div></p>";

   }


}

add_action('admin_footer', 'add_order_styles');

function add_order_styles(){
   echo "<style>

.table_order_export .dashicons{
   margin-top: 5px;
   margin-right:2px;
}

.table_order_export .dashicons.dashicons-plus-alt:before{
   margin-left: -3px;
   margin-right: 3px;

}

.table_order_export .button{
   font-size: 14px;
   border-color:transparent!important;
margin-bottom: 8px;
}


.table_order_export .button.order_dl_link{
   background: #c6e1c6;
   color: green;
}

.table_order_export .button.order_create_file{
   background:#ffd3d3;
   color:#be0101;
}

/* HTML:  */
.loader {
 width: 50px;
 padding: 8px;
 aspect-ratio: 1;
 border-radius: 50%;
 background: #25b09b;
 --_m: 
   conic-gradient(#0000 10%,#000),
   linear-gradient(#000 0 0) content-box;
 -webkit-mask: var(--_m);
		 mask: var(--_m);
 -webkit-mask-composite: source-out;
		 mask-composite: subtract;
 animation: l3 1s infinite linear;
}
@keyframes l3 {to{transform: rotate(1turn)}}

.loading-state {
 position: fixed;
 top: 0;
 left: 0;
 width: 100%;
 height: 100%;
 background-color: rgba(0, 0, 0, 0.04);
 z-index: 9999;
 display: flex;
 justify-content: center;
 align-items: center;
}
.loading {
 width: 100px;
 height: 100px;
 border-radius: 50%;
 border: 4px solid #ddd;
 border-top-color: blue;
 animation: loading 1s linear infinite;
}
@keyframes loading {
 to {
   transform: rotate(360deg);
 }
}

/* HTML: */
.loader {
 width: 50px;
 padding: 8px;
 aspect-ratio: 1;
 border-radius: 50%;
 background: #25b09b;
 --_m: 
   conic-gradient(#0000 10%,#000),
   linear-gradient(#000 0 0) content-box;
 -webkit-mask: var(--_m);
		 mask: var(--_m);
 -webkit-mask-composite: source-out;
		 mask-composite: subtract;
 animation: l3 1s infinite linear;
}
@keyframes l3 {to{transform: rotate(1turn)}}
</style>";
}
/**
* Add webp to allowed media types
*/
function wpdocs_add_csv( $wp_get_mime_types ) {
   $wp_get_mime_types['csv'] = 'text/csv';
   return $wp_get_mime_types;
}

add_filter( 'mime_types', 'wpdocs_add_csv' );


add_action('acf/input/admin_head', function(){
	echo "<div id='sprache_outline_".pll_current_language()."'>
		<style>

			#sprache_outline_de ~ #wpwrap #poststuff #beschreibung_fr,
			#sprache_outline_de ~ #wpwrap #poststuff #beschreibung_en,

			#sprache_outline_fr ~ #wpwrap #poststuff #beschreibung_de,
			#sprache_outline_fr ~ #wpwrap #poststuff #beschreibung_en,

			#sprache_outline_en ~ #wpwrap #poststuff #beschreibung_de,
			#sprache_outline_en ~ #wpwrap #poststuff #beschreibung_fr,

			#sprache_outline_de ~ #wpwrap #poststuff #title_fr,
			#sprache_outline_de ~ #wpwrap #poststuff #title_en,

			#sprache_outline_fr ~ #wpwrap #poststuff #title_de,
			#sprache_outline_fr ~ #wpwrap #poststuff #title_en,

			#sprache_outline_en ~ #wpwrap #poststuff #title_de,
			#sprache_outline_en ~ #wpwrap #poststuff #title_fr
			{
				display:none;
				visibility:hidden;
			}

			#beschreibung_de,
			#beschreibung_fr,
			#beschreibung_en,
			#title_de,
			#title_fr,
			#title_en
				{
					background: #135e9633;
				}

			#sprache_outline_de ~ #wpwrap #postdivrich,
			#sprache_outline_fr ~ #wpwrap #postdivrich,
			#sprache_outline_en ~ #wpwrap #postdivrich{
				display:none;
				visibility:hidden;
			}
			
			
			#sprache_outline_de ~ #wpwrap #postexcerpt,
			#sprache_outline_fr ~ #wpwrap #postexcerpt,
			#sprache_outline_en ~ #wpwrap #postexcerpt,
			#sprache_outline_de ~ #wpwrap #title,
			#sprache_outline_fr ~ #wpwrap #title,
			#sprache_outline_en ~ #wpwrap #title{
				opacity: 0.6;
			}
			#sprache_outline_de ~ #wpwrap #postexcerpt #wp-excerpt-wrap,
			#sprache_outline_fr ~ #wpwrap #postexcerpt #wp-excerpt-wrap,
			#sprache_outline_en ~ #wpwrap #postexcerpt #wp-excerpt-wrap{
				pointer-events: none;
			}
			#sprache_outline_de ~ #wpwrap #titlewrap #title,
			#sprache_outline_fr ~ #wpwrap #titlewrap #title,
			#sprache_outline_en ~ #wpwrap #titlewrap #title{
				pointer-events: none;
			}
		</style>
	</div>";
});


//add_filter( 'wc_product_pre_lock_on_sku', '__return_true' );

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/product_images', '/set_images/', array(
            'methods' => 'GET', 
            'callback' => 'set_product_images' 
    ) );
});

// Do the actual query and return the data
function set_product_images(){
	//global $product;
	/* $prod = wc_get_product(158019)->get_data();
	return $prod; */
	include __DIR__."/image_array.php";
	//return $list1;
	$list = $list6;
	file_put_contents( __DIR__.'/'."category_start_to_end.txt","Image Import started at ".date("d.m.Y H:i:s",time())."\r\n",FILE_APPEND);
	
	$i = 0;
	foreach($list as $id => $nummer){
		//echo "id: ". $id . " Nummer: " . $nummer;
	/* if($i > 100)break;
	$i++; */
	//die();
	$prod = wc_get_product($id)/* ->get_data() */; //9356186227
	$prod_sku = $prod->get_sku();//return $prod_sku;
	$target_url = "";
	
	
		
		if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku).".jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku).".jpg";
		}
		else if(file_exists(__DIR__."/Actetre Bilder/".str_replace("fr","",strtolower($prod_sku)).".jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".str_replace("fr","",strtolower($prod_sku)).".jpg";
		}else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku)."fr.jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku)."fr.jpg";
		}else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku)."de.jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku)."de.jpg";
		}else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku)."aa.jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku)."aa.jpg";
		}else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku)."-a5.jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku)."-a5.jpg";
		}else if(file_exists(__DIR__."/Actetre Bilder/".strtolower($prod_sku)."_.jpg")){
		  $target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/".strtolower($prod_sku)."_.jpg";
		}else{
			$target_url = "https://actetre.de/wp-content/themes/ACTEtre-WP/Actetre Bilder/missing.png";
		} 
		$target[] = $prod;
	  //file_put_contents( __DIR__.'/'."testImages.txt",$id." | ".strtolower($prod_sku)." | ".$target_url." \r\n",FILE_APPEND);
		/* $image_url = wc_rest_upload_image_from_url($target_url);
		$image_id = wc_rest_set_uploaded_image_as_attachment($image_url, $prod->get_id());
		$prod->set_image_id($image_id);
		$prod->save(); */
	}
	return $target;//"fertig!";
	file_put_contents( __DIR__.'/'."category_start_to_end.txt","Image Import finished at ".date("d.m.Y H:i:s",time())."\r\n",FILE_APPEND);
}

//add_filter( 'wc_product_has_unique_sku', '__return_false');

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/product_lang', '/get_language/', array(
            'methods' => 'POST', 
            'callback' => 'get_product_translations' 
    ) );
});

function get_product_translations($data){
	$result = pll_get_post_translations($data["ID"]);
	return $result;
}

add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'wp/v2/product_id_by_sku', '/get_id/', array(
            'methods' => 'POST', 
            'callback' => 'get_product_translation_id' 
    ) );
});

function get_product_translation_id($data){
	$args = array(
		//'sku' => $data['sku'],
		'lang' => ['en','de','fr'],
		'return' => 'ids',
		'meta_key' => '_sku',
		'meta_value' => $data['sku']
		/* 'meta_query' => array(
			array(
				'key' => 'sku',
				'value' => $data['sku'],
				'compare' => "="
			)
		) */
	  );
	  $products = wc_get_products( $args );

	  $product_lang = [];

	  foreach($products as $id){
		
		$product_lang[pll_get_post_language($id)] = $id;
	  }

	return $product_lang;
}
/**
 * Show the product title in the product loop. By default this is an H2.
 */
remove_action("woocommerce_shop_loop_item_title","woocommerce_template_loop_product_title", 10);
add_action("woocommerce_shop_loop_item_title", "outline_template_loop_product_title",10);
function outline_template_loop_product_title() {

	$lang_code = pll_current_language();
	$title = get_the_title();

	if(get_field("titel_deutsch") || get_field("titel_franzosisch") || get_field("titel_englisch")){
		
		switch($lang_code){
			case "de":
				$title = get_field("titel_deutsch");
				break;
			case "fr":
				$title = get_field("titel_franzosisch");
				break;
			case "en":
				$title = get_field("titel_englisch");
				break;
		}
	}
	echo '<span class="item__name ' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . $title . '</span>';
}

pll_register_string('Product-Category', 'Grußkarten','WooCommerce');
pll_register_string('Product-Category', 'Klappkarten \"Christmas\"','WooCommerce');
pll_register_string('Product-Category', 'Blue Bling','WooCommerce');
pll_register_string('Product-Category', 'Adventskalenderkarte','WooCommerce');
pll_register_string('Product-Category', 'Aqua Dolce','WooCommerce');
pll_register_string('Product-Category', 'Art Press','WooCommerce');
pll_register_string('Product-Category', 'Au Contraire','WooCommerce');
pll_register_string('Product-Category', 'Bellini','WooCommerce');
pll_register_string('Product-Category', 'Blue Slate','WooCommerce');
pll_register_string('Product-Category', 'Bontempi','WooCommerce');
pll_register_string('Product-Category', 'Botanic Bliss','WooCommerce');
pll_register_string('Product-Category', 'Clearwater','WooCommerce');
pll_register_string('Product-Category', 'Color Parade','WooCommerce');
pll_register_string('Product-Category', 'Colourround','WooCommerce');
pll_register_string('Product-Category', 'Copper Charm','WooCommerce');
pll_register_string('Product-Category', 'Delicatissimo','WooCommerce');
pll_register_string('Product-Category', 'Design x-mas','WooCommerce');
pll_register_string('Product-Category', 'Enfant Terrible','WooCommerce');
pll_register_string('Product-Category', 'Gutschein','WooCommerce');
pll_register_string('Product-Category', 'Heart of Gold','WooCommerce');
pll_register_string('Product-Category', 'Heartfelt','WooCommerce');
pll_register_string('Product-Category', 'Imperial Orange','WooCommerce');
pll_register_string('Product-Category', 'Impressive','WooCommerce');
pll_register_string('Product-Category', 'Jellybeans','WooCommerce');
pll_register_string('Product-Category', 'Kartenboxen','WooCommerce');
pll_register_string('Product-Category', 'Kelly Marie (Studio Mie)','WooCommerce');
pll_register_string('Product-Category', 'Kleine Glücksboten','WooCommerce');
pll_register_string('Product-Category', 'La Dame et les Filles','WooCommerce');
pll_register_string('Product-Category', 'Lemon Lou','WooCommerce');
pll_register_string('Product-Category', 'Lumen','WooCommerce');
pll_register_string('Product-Category', 'Mac Classic','WooCommerce');
pll_register_string('Product-Category', 'Mac Hil','WooCommerce');
pll_register_string('Product-Category', 'Mahogany','WooCommerce');
pll_register_string('Product-Category', 'Marianna','WooCommerce');
pll_register_string('Product-Category', 'Mini Cards','WooCommerce');
pll_register_string('Product-Category', 'PIET','WooCommerce');
pll_register_string('Product-Category', 'Pretty in Print','WooCommerce');
pll_register_string('Product-Category', 'Pure White','WooCommerce');
pll_register_string('Product-Category', 'Puzzlekarten','WooCommerce');
pll_register_string('Product-Category', 'Quicksilver','WooCommerce');
pll_register_string('Product-Category', 'Red Sparkle','WooCommerce');
pll_register_string('Product-Category', 'Reverso','WooCommerce');
pll_register_string('Product-Category', 'Rich White','WooCommerce');
pll_register_string('Product-Category', 'Rough Elegance','WooCommerce');
pll_register_string('Product-Category', 'Spicy Hill','WooCommerce');
pll_register_string('Product-Category', 'TMS Jamboree','WooCommerce');
pll_register_string('Product-Category', 'TMS Papillon','WooCommerce');
pll_register_string('Product-Category', 'Tool Cut','WooCommerce');
pll_register_string('Product-Category', 'Touch of Classic','WooCommerce');
pll_register_string('Product-Category', 'Tylkowski','WooCommerce');
pll_register_string('Product-Category', 'Weihnachtsfreude','WooCommerce');
pll_register_string('Product-Category', 'Wish and Click','WooCommerce');
pll_register_string('Product-Category', 'Wish and Give','WooCommerce');
pll_register_string('Product-Category', 'Wonderful White','WooCommerce');
pll_register_string('Product-Category', 'Wonderland','WooCommerce');
pll_register_string('Product-Category', 'Zauberwelt','WooCommerce');
pll_register_string('Product-Category', 'Klappkarten \"Everyday\"','WooCommerce');
pll_register_string('Product-Category', 'Aqua Dolce','WooCommerce');
pll_register_string('Product-Category', 'Copper Charm','WooCommerce');
pll_register_string('Product-Category', 'Julia Bergford','WooCommerce');
pll_register_string('Product-Category', 'Altstadthaus','WooCommerce');
pll_register_string('Product-Category', 'Archive','WooCommerce');
pll_register_string('Product-Category', 'Art Press','WooCommerce');
pll_register_string('Product-Category', 'Au Contraire','WooCommerce');
pll_register_string('Product-Category', 'BEA','WooCommerce');
pll_register_string('Product-Category', 'Bellini','WooCommerce');
pll_register_string('Product-Category', 'Black Classic','WooCommerce');
pll_register_string('Product-Category', 'Blue Bling','WooCommerce');
pll_register_string('Product-Category', 'Blue Slate','WooCommerce');
pll_register_string('Product-Category', 'Bontempi','WooCommerce');
pll_register_string('Product-Category', 'Botanic Bliss','WooCommerce');
pll_register_string('Product-Category', 'Brilliant&Wild','WooCommerce');
pll_register_string('Product-Category', 'Classic Ticket','WooCommerce');
pll_register_string('Product-Category', 'Clearwater','WooCommerce');
pll_register_string('Product-Category', 'Colourround','WooCommerce');
pll_register_string('Product-Category', 'Correspondances','WooCommerce');
pll_register_string('Product-Category', 'Delicatissimo','WooCommerce');
pll_register_string('Product-Category', 'Design Alpha','WooCommerce');
pll_register_string('Product-Category', 'Design Sport','WooCommerce');
pll_register_string('Product-Category', 'Dutch Gold','WooCommerce');
pll_register_string('Product-Category', 'Enfant Terrible','WooCommerce');
pll_register_string('Product-Category', 'Furry Tails','WooCommerce');
pll_register_string('Product-Category', 'Gigi','WooCommerce');
pll_register_string('Product-Category', 'Glücksbringer','WooCommerce');
pll_register_string('Product-Category', 'Gutschein','WooCommerce');
pll_register_string('Product-Category', 'Happy Nostalgia','WooCommerce');
pll_register_string('Product-Category', 'Heart of Gold','WooCommerce');
pll_register_string('Product-Category', 'Heartfelt','WooCommerce');
pll_register_string('Product-Category', 'Imperial Orange','WooCommerce');
pll_register_string('Product-Category', 'Impressive','WooCommerce');
pll_register_string('Product-Category', 'Ivory White','WooCommerce');
pll_register_string('Product-Category', 'Jellybeans','WooCommerce');
pll_register_string('Product-Category', 'Kelly Marie (Studio Mie)','WooCommerce');
pll_register_string('Product-Category', 'Kleine Glücksboten','WooCommerce');
pll_register_string('Product-Category', 'Kleine Zauberwelt','WooCommerce');
pll_register_string('Product-Category', 'Kunst Doppelkarte','WooCommerce');
pll_register_string('Product-Category', 'La Dame et les Filles','WooCommerce');
pll_register_string('Product-Category', 'Lali','WooCommerce');
pll_register_string('Product-Category', 'Lemon Lou','WooCommerce');
pll_register_string('Product-Category', 'Lovely Liv','WooCommerce');
pll_register_string('Product-Category', 'Lumen','WooCommerce');
pll_register_string('Product-Category', 'Mac Classic','WooCommerce');
pll_register_string('Product-Category', 'Mac Classic Relations','WooCommerce');
pll_register_string('Product-Category', 'Mac Classic XL','WooCommerce');
pll_register_string('Product-Category', 'Mac Classic Zahlengeburtstage','WooCommerce');
pll_register_string('Product-Category', 'Mac Hil','WooCommerce');
pll_register_string('Product-Category', 'Mahogany','WooCommerce');
pll_register_string('Product-Category', 'MAN OH MAN','WooCommerce');
pll_register_string('Product-Category', 'Marianna','WooCommerce');
pll_register_string('Product-Category', 'Meow and Purr','WooCommerce');
pll_register_string('Product-Category', 'Mini Cards','WooCommerce');
pll_register_string('Product-Category', 'New Baroque','WooCommerce');
pll_register_string('Product-Category', 'Numero','WooCommerce');
pll_register_string('Product-Category', 'OH MY GIRL','WooCommerce');
pll_register_string('Product-Category', 'Paper Statues','WooCommerce');
pll_register_string('Product-Category', 'Philip Townsend Archive','WooCommerce');
pll_register_string('Product-Category', 'PIET','WooCommerce');
pll_register_string('Product-Category', 'Pretty in Print','WooCommerce');
pll_register_string('Product-Category', 'Print Lover','WooCommerce');
pll_register_string('Product-Category', 'Pumpkin Red','WooCommerce');
pll_register_string('Product-Category', 'Pure White','WooCommerce');
pll_register_string('Product-Category', 'Purple Power','WooCommerce');
pll_register_string('Product-Category', 'Puzzlekarten','WooCommerce');
pll_register_string('Product-Category', 'Quicksilver','WooCommerce');
pll_register_string('Product-Category', 'Red Sparkle','WooCommerce');
pll_register_string('Product-Category', 'Reddish Design','WooCommerce');
pll_register_string('Product-Category', 'Religiöse Karten','WooCommerce');
pll_register_string('Product-Category', 'Rich White','WooCommerce');
pll_register_string('Product-Category', 'Romantic Affairs','WooCommerce');
pll_register_string('Product-Category', 'Rough Elegance','WooCommerce');
pll_register_string('Product-Category', 'Samt','WooCommerce');
pll_register_string('Product-Category', 'Sand Beige','WooCommerce');
pll_register_string('Product-Category', 'Say it with songs','WooCommerce');
pll_register_string('Product-Category', 'Silver Linings','WooCommerce');
pll_register_string('Product-Category', 'Simply Seventus','WooCommerce');
pll_register_string('Product-Category', 'Sonderangebot','WooCommerce');
pll_register_string('Product-Category', 'Spicy Hill','WooCommerce');
pll_register_string('Product-Category', 'Stay At Home','WooCommerce');
pll_register_string('Product-Category', 'Stickerkarte Marion Billet','WooCommerce');
pll_register_string('Product-Category', 'Surprise!','WooCommerce');
pll_register_string('Product-Category', 'Tante Door','WooCommerce');
pll_register_string('Product-Category', 'TMS Goldfever','WooCommerce');
pll_register_string('Product-Category', 'TMS Jamboree','WooCommerce');
pll_register_string('Product-Category', 'TMS Papillon','WooCommerce');
pll_register_string('Product-Category', 'TMS Sweet Cheeks','WooCommerce');
pll_register_string('Product-Category', 'Touch of Classic','WooCommerce');
pll_register_string('Product-Category', 'Touch of Neon','WooCommerce');
pll_register_string('Product-Category', 'Trauerkarten','WooCommerce');
pll_register_string('Product-Category', 'Tylkowski','WooCommerce');
pll_register_string('Product-Category', 'Urban Street','WooCommerce');
pll_register_string('Product-Category', 'Vermilion Fuchsia','WooCommerce');
pll_register_string('Product-Category', 'Wish and Click','WooCommerce');
pll_register_string('Product-Category', 'Wish and Give','WooCommerce');
pll_register_string('Product-Category', 'Wonderful White','WooCommerce');
pll_register_string('Product-Category', 'Wonderland','WooCommerce');
pll_register_string('Product-Category', 'XXL Cards','WooCommerce');
pll_register_string('Product-Category', 'Zauberwelt','WooCommerce');
pll_register_string('Product-Category', 'Postkarten \"Christmas\"','WooCommerce');
pll_register_string('Product-Category', '3-D-Städtekarten','WooCommerce');
pll_register_string('Product-Category', 'Alltagsparadies','WooCommerce');
pll_register_string('Product-Category', 'Edition Tausendschön \"Städte-Postkarten\"','WooCommerce');
pll_register_string('Product-Category', 'Edition Tausendschön \"Sweet Memories\"','WooCommerce');
pll_register_string('Product-Category', 'Quire','WooCommerce');
pll_register_string('Product-Category', 'Spicy Hill','WooCommerce');
pll_register_string('Product-Category', 'Weihnachtsbox TS Postkarten','WooCommerce');
pll_register_string('Product-Category', 'Postkarten \"Everyday\"','WooCommerce');
pll_register_string('Product-Category', '3-D-Städtekarten','WooCommerce');
pll_register_string('Product-Category', 'ACTEtre \"Glitzer-Postkarten\"','WooCommerce');
pll_register_string('Product-Category', 'Adams Art','WooCommerce');
pll_register_string('Product-Category', 'Alltagsparadies','WooCommerce');
pll_register_string('Product-Category', 'Anna Flores','WooCommerce');
pll_register_string('Product-Category', 'Anne-Sophie','WooCommerce');
pll_register_string('Product-Category', 'Cartolina','WooCommerce');
pll_register_string('Product-Category', 'Edition Tausendschön \"Round Sweeties\"','WooCommerce');
pll_register_string('Product-Category', 'Edition Tausendschön \"Städte-Postkarten\"','WooCommerce');
pll_register_string('Product-Category', 'Edition Tausendschön \"Sweet Memories\"','WooCommerce');
pll_register_string('Product-Category', 'Engolino','WooCommerce');
pll_register_string('Product-Category', 'Farmer Postkarten','WooCommerce');
pll_register_string('Product-Category', 'Hello Kaczi','WooCommerce');
pll_register_string('Product-Category', 'Lali','WooCommerce');
pll_register_string('Product-Category', 'Magic Meadow','WooCommerce');
pll_register_string('Product-Category', 'Markus Binz','WooCommerce');
pll_register_string('Product-Category', 'Metallbox TS (Postkarten)','WooCommerce');
pll_register_string('Product-Category', 'Mutterbalsam','WooCommerce');
pll_register_string('Product-Category', 'Ole West','WooCommerce');
pll_register_string('Product-Category', 'Panka','WooCommerce');
pll_register_string('Product-Category', 'Quire','WooCommerce');
pll_register_string('Product-Category', 'Spicy Hill','WooCommerce');
pll_register_string('Product-Category', 'Spicy Hill Einladungen','WooCommerce');
pll_register_string('Product-Category', 'Tausendschön','WooCommerce');
pll_register_string('Product-Category', 'Traumtänzer','WooCommerce');
pll_register_string('Product-Category', 'Troove','WooCommerce');
pll_register_string('Product-Category', 'Tylkowski','WooCommerce');
pll_register_string('Product-Category', 'Vergisstmannicht','WooCommerce');
pll_register_string('Product-Category', 'Kunstdrucke','WooCommerce');
pll_register_string('Product-Category', 'Künstler A - E','WooCommerce');
pll_register_string('Product-Category', 'Doisneau, Robert','WooCommerce');
pll_register_string('Product-Category', 'Abbott, Carl','WooCommerce');
pll_register_string('Product-Category', 'Ackermann, Max','WooCommerce');
pll_register_string('Product-Category', 'Addinall, Ruth','WooCommerce');
pll_register_string('Product-Category', 'Ancarani, Clothilde','WooCommerce');
pll_register_string('Product-Category', 'Bastin, Daniel','WooCommerce');
pll_register_string('Product-Category', 'Baugniet, Marcel-Louis','WooCommerce');
pll_register_string('Product-Category', 'Baumeister, Willi','WooCommerce');
pll_register_string('Product-Category', 'Bazzoni, Laetizia','WooCommerce');
pll_register_string('Product-Category', 'Belgeonne, Gabriel','WooCommerce');
pll_register_string('Product-Category', 'Benirschke, Max','WooCommerce');
pll_register_string('Product-Category', 'Bersou, Erik','WooCommerce');
pll_register_string('Product-Category', 'Bertelli, Enrico','WooCommerce');
pll_register_string('Product-Category', 'Beuler, Angelika','WooCommerce');
pll_register_string('Product-Category', 'Beuys, Joseph','WooCommerce');
pll_register_string('Product-Category', 'Bibaut, Alexandre','WooCommerce');
pll_register_string('Product-Category', 'Bissier, Julius','WooCommerce');
pll_register_string('Product-Category', 'Black, Alison','WooCommerce');
pll_register_string('Product-Category', 'Boissiere, Henri','WooCommerce');
pll_register_string('Product-Category', 'Braile, Deborah','WooCommerce');
pll_register_string('Product-Category', 'BulbFiction','WooCommerce');
pll_register_string('Product-Category', 'Calder, Alexander','WooCommerce');
pll_register_string('Product-Category', 'Capa, J.','WooCommerce');
pll_register_string('Product-Category', 'Caravaggio, Michelangelo','WooCommerce');
pll_register_string('Product-Category', 'Chagall, Marc','WooCommerce');
pll_register_string('Product-Category', 'Chauvelot, Cédric','WooCommerce');
pll_register_string('Product-Category', 'Clause, Marie-Cécile','WooCommerce');
pll_register_string('Product-Category', 'Clement, Nathalie','WooCommerce');
pll_register_string('Product-Category', 'Dali, Salvador','WooCommerce');
pll_register_string('Product-Category', 'Damm, Frank','WooCommerce');
pll_register_string('Product-Category', 'Dauchot, Francoise','WooCommerce');
pll_register_string('Product-Category', 'David, Jacques Louis','WooCommerce');
pll_register_string('Product-Category', 'De Man, Petrus','WooCommerce');
pll_register_string('Product-Category', 'De Maria, Nicola','WooCommerce');
pll_register_string('Product-Category', 'Debatty, Pierre','WooCommerce');
pll_register_string('Product-Category', 'Debuysère, Sonia','WooCommerce');
pll_register_string('Product-Category', 'Delahaut, Jo','WooCommerce');
pll_register_string('Product-Category', 'Delaunay, Robert','WooCommerce');
pll_register_string('Product-Category', 'Demaseure, Dominique','WooCommerce');
pll_register_string('Product-Category', 'Diebenkorn, Richard','WooCommerce');
pll_register_string('Product-Category', 'Dilorenzo, Shwan','WooCommerce');
pll_register_string('Product-Category', 'Doucet, Claudia','WooCommerce');
pll_register_string('Product-Category', 'Drygalski, Raymond','WooCommerce');
pll_register_string('Product-Category', 'Künstler F - J','WooCommerce');
pll_register_string('Product-Category', 'Feininger, Lyonel','WooCommerce');
pll_register_string('Product-Category', 'Felbermair, Heinz','WooCommerce');
pll_register_string('Product-Category', 'Ferrer, Mela','WooCommerce');
pll_register_string('Product-Category', 'Fieri, Vlado','WooCommerce');
pll_register_string('Product-Category', 'Fievet, Nadine','WooCommerce');
pll_register_string('Product-Category', 'Flandrin, Hippolyte','WooCommerce');
pll_register_string('Product-Category', 'Francis, Sam','WooCommerce');
pll_register_string('Product-Category', 'Francoise, Valerie','WooCommerce');
pll_register_string('Product-Category', 'Frankenthaler, Helen','WooCommerce');
pll_register_string('Product-Category', 'Freundlich, Otto','WooCommerce');
pll_register_string('Product-Category', 'Fusi, Walter','WooCommerce');
pll_register_string('Product-Category', 'Garnier, Clément','WooCommerce');
pll_register_string('Product-Category', 'Giacometti, Alberto','WooCommerce');
pll_register_string('Product-Category', 'Gitalis, Elaine','WooCommerce');
pll_register_string('Product-Category', 'Gnoli, Domenico','WooCommerce');
pll_register_string('Product-Category', 'Gottlieb, Adolph','WooCommerce');
pll_register_string('Product-Category', 'Groenhart, Jan','WooCommerce');
pll_register_string('Product-Category', 'Hassinger, Antje','WooCommerce');
pll_register_string('Product-Category', 'Hassinger, Sybille','WooCommerce');
pll_register_string('Product-Category', 'Heron, Patrick','WooCommerce');
pll_register_string('Product-Category', 'Hesse, Hermann','WooCommerce');
pll_register_string('Product-Category', 'Hopkins, Gordon','WooCommerce');
pll_register_string('Product-Category', 'Hopper, Edward','WooCommerce');
pll_register_string('Product-Category', 'Jacquier, Didier','WooCommerce');
pll_register_string('Product-Category', 'Jawlensky, Alexej','WooCommerce');
pll_register_string('Product-Category', 'Johns, Jasper','WooCommerce');
pll_register_string('Product-Category', 'Künstler K - O','WooCommerce');
pll_register_string('Product-Category', 'Grötschl, Manuel','WooCommerce');
pll_register_string('Product-Category', 'Kandinsky, Wassily','WooCommerce');
pll_register_string('Product-Category', 'Kausel, Thomas','WooCommerce');
pll_register_string('Product-Category', 'Kelly, Ellsworth','WooCommerce');
pll_register_string('Product-Category', 'Klaas, Uschi','WooCommerce');
pll_register_string('Product-Category', 'Klee, Paul','WooCommerce');
pll_register_string('Product-Category', 'Klein, Yves','WooCommerce');
pll_register_string('Product-Category', 'Klimt, Gustav','WooCommerce');
pll_register_string('Product-Category', 'Kline, Franz','WooCommerce');
pll_register_string('Product-Category', 'Kljun, Iwan','WooCommerce');
pll_register_string('Product-Category', 'Koch, T.','WooCommerce');
pll_register_string('Product-Category', 'Lawson, Sonia','WooCommerce');
pll_register_string('Product-Category', 'Le Beuan Benic, Nicolas','WooCommerce');
pll_register_string('Product-Category', 'Lecouturier, Jacky','WooCommerce');
pll_register_string('Product-Category', 'Lewitt, Sol','WooCommerce');
pll_register_string('Product-Category', 'Liesse, Nadine','WooCommerce');
pll_register_string('Product-Category', 'Loriaux, Christiane','WooCommerce');
pll_register_string('Product-Category', 'Louis, Morris','WooCommerce');
pll_register_string('Product-Category', 'Macke, August','WooCommerce');
pll_register_string('Product-Category', 'Mahieu, Pier','WooCommerce');
pll_register_string('Product-Category', 'Malevich, Kazimir','WooCommerce');
pll_register_string('Product-Category', 'Marc, Franz','WooCommerce');
pll_register_string('Product-Category', 'Marini, Marino','WooCommerce');
pll_register_string('Product-Category', 'Marose, Jürgen','WooCommerce');
pll_register_string('Product-Category', 'Masi, Paolo','WooCommerce');
pll_register_string('Product-Category', 'Matisse, Henri','WooCommerce');
pll_register_string('Product-Category', 'Melotti, Ivan','WooCommerce');
pll_register_string('Product-Category', 'Menocoboni','WooCommerce');
pll_register_string('Product-Category', 'Meraglia, Franco','WooCommerce');
pll_register_string('Product-Category', 'Mes, Han','WooCommerce');
pll_register_string('Product-Category', 'Modigliani, Amedeo','WooCommerce');
pll_register_string('Product-Category', 'Mondrian, Piet','WooCommerce');
pll_register_string('Product-Category', 'Monet, Claude','WooCommerce');
pll_register_string('Product-Category', 'Monti-Xhoffer, Didier','WooCommerce');
pll_register_string('Product-Category', 'Montiel, Anne','WooCommerce');
pll_register_string('Product-Category', 'Montigny, Thierry','WooCommerce');
pll_register_string('Product-Category', 'Moore, Chris','WooCommerce');
pll_register_string('Product-Category', 'Moser, Ingo','WooCommerce');
pll_register_string('Product-Category', 'Motherwell, Robert','WooCommerce');
pll_register_string('Product-Category', 'Newman, Barnett','WooCommerce');
pll_register_string('Product-Category', 'Nicholson, Ben','WooCommerce');
pll_register_string('Product-Category', 'Noland, Kenneth','WooCommerce');
pll_register_string('Product-Category', 'O\'Keefe, Georgia','WooCommerce');
pll_register_string('Product-Category', 'Künstler P - T','WooCommerce');
pll_register_string('Product-Category', 'Paladino, Mimmo','WooCommerce');
pll_register_string('Product-Category', 'Papastamos, Plato E.','WooCommerce');
pll_register_string('Product-Category', 'Paul, Olivier','WooCommerce');
pll_register_string('Product-Category', 'Pecci-Calvana, Marco','WooCommerce');
pll_register_string('Product-Category', 'Picasso, Pablo','WooCommerce');
pll_register_string('Product-Category', 'Polla, Davide','WooCommerce');
pll_register_string('Product-Category', 'Pollock, Jackson','WooCommerce');
pll_register_string('Product-Category', 'Puppo, Walter','WooCommerce');
pll_register_string('Product-Category', 'Ravet, Franca','WooCommerce');
pll_register_string('Product-Category', 'Redon, Odilon','WooCommerce');
pll_register_string('Product-Category', 'Remusat, Bernard','WooCommerce');
pll_register_string('Product-Category', 'Richter, Gerhard','WooCommerce');
pll_register_string('Product-Category', 'Riga, Ernesto','WooCommerce');
pll_register_string('Product-Category', 'Rodin, Auguste','WooCommerce');
pll_register_string('Product-Category', 'Rothko, Mark','WooCommerce');
pll_register_string('Product-Category', 'Rousseau, Henri','WooCommerce');
pll_register_string('Product-Category', 'Roziewski, Elke','WooCommerce');
pll_register_string('Product-Category', 'Schiele, Egon','WooCommerce');
pll_register_string('Product-Category', 'Schifano, Mario','WooCommerce');
pll_register_string('Product-Category', 'Scholz, Andreas','WooCommerce');
pll_register_string('Product-Category', 'Scott, William','WooCommerce');
pll_register_string('Product-Category', 'Scully, Sean','WooCommerce');
pll_register_string('Product-Category', 'Seck, Mechthild','WooCommerce');
pll_register_string('Product-Category', 'Spilliaert, Léon','WooCommerce');
pll_register_string('Product-Category', 'Sprumont, Andre','WooCommerce');
pll_register_string('Product-Category', 'Stähli, Susanne','WooCommerce');
pll_register_string('Product-Category', 'Stella, Frank','WooCommerce');
pll_register_string('Product-Category', 'Stevens, Allan','WooCommerce');
pll_register_string('Product-Category', 'Still, Clyfford','WooCommerce');
pll_register_string('Product-Category', 'Talbot, Chantal','WooCommerce');
pll_register_string('Product-Category', 'Tàpies, Antonio','WooCommerce');
pll_register_string('Product-Category', 'Tinguely, Jean','WooCommerce');
pll_register_string('Product-Category', 'Toulouse-Lautrec, Henri','WooCommerce');
pll_register_string('Product-Category', 'Turner, Joseph','WooCommerce');
pll_register_string('Product-Category', 'Künstler U - Z','WooCommerce');
pll_register_string('Product-Category', 'Van Doesburg, Theo','WooCommerce');
pll_register_string('Product-Category', 'Van Gogh, Vincent','WooCommerce');
pll_register_string('Product-Category', 'Vasarely, Victor','WooCommerce');
pll_register_string('Product-Category', 'Ver Elst, Marc','WooCommerce');
pll_register_string('Product-Category', 'Vermeer, Jan','WooCommerce');
pll_register_string('Product-Category', 'Wattin, Marie C.','WooCommerce');
pll_register_string('Product-Category', 'Wegner, Jürgen','WooCommerce');
pll_register_string('Product-Category', 'Zhu, Tianmeng','WooCommerce');
pll_register_string('Product-Category', 'Originalgrafik','WooCommerce');
pll_register_string('Product-Category', 'Künstler A - E','WooCommerce');
pll_register_string('Product-Category', 'Bohnenkamp, Ralf','WooCommerce');
pll_register_string('Product-Category', 'Bramsiepe, Gudrun','WooCommerce');
pll_register_string('Product-Category', 'Damm, Frank','WooCommerce');
pll_register_string('Product-Category', 'Künstler F - J','WooCommerce');
pll_register_string('Product-Category', 'Flores, Anna','WooCommerce');
pll_register_string('Product-Category', 'Hassinger, Antje','WooCommerce');
pll_register_string('Product-Category', 'Hassinger, Sybille','WooCommerce');
pll_register_string('Product-Category', 'Künstler K - O','WooCommerce');
pll_register_string('Product-Category', 'Koch, Ariane','WooCommerce');
pll_register_string('Product-Category', 'Köppeler, Bettina','WooCommerce');
pll_register_string('Product-Category', 'Kouldakidou, Sofia','WooCommerce');
pll_register_string('Product-Category', 'Kraft, Andrea','WooCommerce');
pll_register_string('Product-Category', 'Matijevic, Miriana','WooCommerce');
pll_register_string('Product-Category', 'Ostgathe, Ulli','WooCommerce');
pll_register_string('Product-Category', 'Künstler P - T','WooCommerce');
pll_register_string('Product-Category', 'Petschat, Ralph','WooCommerce');
pll_register_string('Product-Category', 'Rasch, Folkert','WooCommerce');
pll_register_string('Product-Category', 'Schäffer, Rainer','WooCommerce');
pll_register_string('Product-Category', 'Schneider, Yvonne','WooCommerce');
pll_register_string('Product-Category', 'Schwarz, Natascha','WooCommerce');
pll_register_string('Product-Category', 'Thiess, Ute','WooCommerce');
pll_register_string('Product-Category', 'Toliver, Jessica','WooCommerce');
pll_register_string('Product-Category', 'Künstler U - Z','WooCommerce');
pll_register_string('Product-Category', 'Varga, Sandra','WooCommerce');
pll_register_string('Product-Category', 'Papeterie und Sonstiges','WooCommerce');
pll_register_string('Product-Category', 'Papeterie','WooCommerce');
pll_register_string('Product-Category', 'Abreißblock','WooCommerce');
pll_register_string('Product-Category', 'Adressbücher','WooCommerce');
pll_register_string('Product-Category', 'Adventskalender','WooCommerce');
pll_register_string('Product-Category', 'Briefpapier','WooCommerce');
pll_register_string('Product-Category', 'Clipboards','WooCommerce');
pll_register_string('Product-Category', 'Einkaufsblock','WooCommerce');
pll_register_string('Product-Category', 'Einkaufslisten','WooCommerce');
pll_register_string('Product-Category', 'Faltmappen','WooCommerce');
pll_register_string('Product-Category', 'Freundebücher','WooCommerce');
pll_register_string('Product-Category', 'Geschenkanhänger (Weihn.)','WooCommerce');
pll_register_string('Product-Category', 'Geschenkanhänger XXL','WooCommerce');
pll_register_string('Product-Category', 'Geschenkpapier','WooCommerce');
pll_register_string('Product-Category', 'Geschenkpapier (Weihn.)','WooCommerce');
pll_register_string('Product-Category', 'Girlande (Weihn.)','WooCommerce');
pll_register_string('Product-Category', 'Hefte im Oktav-Buchformat','WooCommerce');
pll_register_string('Product-Category', 'Hefte, DIN A5','WooCommerce');
pll_register_string('Product-Category', 'Hefte, DIN A6','WooCommerce');
pll_register_string('Product-Category', 'Hochzeitskollektion','WooCommerce');
pll_register_string('Product-Category', 'Kalender / Planer','WooCommerce');
pll_register_string('Product-Category', 'Lesezeichen','WooCommerce');
pll_register_string('Product-Category', 'Notizblöcke, liniert','WooCommerce');
pll_register_string('Product-Category', 'Notizbücher, DIN A4','WooCommerce');
pll_register_string('Product-Category', 'Notizbücher, DIN A5','WooCommerce');
pll_register_string('Product-Category', 'Notizbücher, DIN A6','WooCommerce');
pll_register_string('Product-Category', 'Rollengeschenkpapier','WooCommerce');
pll_register_string('Product-Category', 'Schmuckkuverts','WooCommerce');
pll_register_string('Product-Category', 'Spiralblöcke, DIN A5','WooCommerce');
pll_register_string('Product-Category', 'Spiralblöcke, DIN A6','WooCommerce');
pll_register_string('Product-Category', 'Splendid Notes, DIN A5','WooCommerce');
pll_register_string('Product-Category', 'Splendid Notes, DIN A6','WooCommerce');
pll_register_string('Product-Category', 'Sonstiges','WooCommerce');
pll_register_string('Product-Category', 'Einkaufslisten','WooCommerce');
pll_register_string('Product-Category', 'Kartenboxen (Weihn.)','WooCommerce');
pll_register_string('Product-Category', 'Fotorahmen','WooCommerce');
pll_register_string('Product-Category', 'Geschenktaschen','WooCommerce');
pll_register_string('Product-Category', 'Geschenktaschen (Weihn.)','WooCommerce');
pll_register_string('Product-Category', 'Magnete groß','WooCommerce');
pll_register_string('Product-Category', 'Magnete klein','WooCommerce');
pll_register_string('Product-Category', 'Unkategorisiert','WooCommerce');
pll_register_string("Login", "oder","WooCommerce");
pll_register_string("Login", "Such mit tags:","WooCommerce");
pll_register_string("Login", "Suche nach Produkt oder Artikelnummer…","WooCommerce");
pll_register_string("Login", "Neuheiten","WooCommerce");
pll_register_string( "Login","Search","WooCommerce"); 
pll_register_string("Login","Product search","WooCommerce");
// Register strings early so they appear in Polylang > String translations
add_action( 'init', 'my_register_woocommerce_strings' );
function my_register_woocommerce_strings() {
    pll_register_string( 'wc_item_number',  'Item Number',  'WooCommerce', false );
    pll_register_string( 'wc_newest',       'Newest',       'WooCommerce', false );
    pll_register_string( 'wc_best_sellers', 'Best Sellers', 'WooCommerce', false );
}

pll_register_string("Orders-Page",'Minimum order amount is €%s. Please add €%s more.',"WooCommerce");
pll_register_string("Orders-Page",'Minimum order €%s required',"WooCommerce"); 
// Neue Spalte "Kundennummer" hinzufügen
add_filter('manage_users_columns', function($columns) {
    $columns['kundennummer'] = 'Kundennummer';
    return $columns;
});

// Spalteninhalt einfügen
add_action('manage_users_custom_column', function($value, $column_name, $user_id) {
    if ($column_name === 'kundennummer') {
        return esc_html(get_user_meta($user_id, 'kundennummer', true));
    }
    return $value;
}, 10, 3);










add_action('pre_get_users', function($query) {
    if (!is_admin() || empty($_GET['s'])) return;

    $search = sanitize_text_field($_GET['s']);
    global $wpdb;

    // Suche Benutzer mit exakt passender Kundennummer
    $user_id = $wpdb->get_var($wpdb->prepare(
        "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'kundennummer' AND meta_value = %s LIMIT 1",
        $search
    ));

    if ($user_id) {
        $query->set('include', [$user_id]);
        $query->set('search', ''); // verhindert SQL-Fehler bei leerem WHERE
        $query->set('meta_query', []); // Sicherheitshalber leeren
        $query->set('__filtering_by_kundennummer', true); // Marker für Filter
    }
});

// Nur wenn wir wirklich nach Kundennummer gefiltert haben, unterdrücken wir Standardsuche
add_filter('user_search_columns', function($search_columns) {
    global $wp_query;

    if (!empty($wp_query->query_vars['__filtering_by_kundennummer'])) {
        return []; // Suche unterdrücken
    }

    return $search_columns; // Standard-Suchverhalten
});

// new status
add_filter( 'woocommerce_product_stock_status_options', function( $statuses ) {
   $statuses['soldout'] = pll__( 'Ausverkauft' ); 
    return $statuses;
});

add_filter( 'woocommerce_product_get_stock_status', function( $status, $product ) {
    if ( get_post_meta( $product->get_id(), '_stock_status', true ) === 'soldout' ) {
        return 'soldout';
    }
    return $status;
}, 10, 2 );
// 1. Make 'soldout' products not purchasable
add_filter( 'woocommerce_is_purchasable', 'disable_purchase_for_soldout_status', 10, 2 );
function disable_purchase_for_soldout_status( $is_purchasable, $product ) {
    if ( $product->get_stock_status() === 'soldout' ) {
        return false;
    }
    return $is_purchasable;
}
//pll_register_string('ausverkauft', 'Ausverkauft','WooCommerce');

// search result
add_filter('posts_search', 'custom_product_search_by_title_sku_tag', 10, 2);
function custom_product_search_by_title_sku_tag($search, $wp_query) {
    global $wpdb;

    // Target only frontend product search
    if (
        !is_admin()
        && $wp_query->is_main_query()
        && $wp_query->is_search()
        && $wp_query->get('post_type') === 'product'
    ) {
        $search_term = $wp_query->get('s');

        if (!$search_term) {
            return $search;
        }

        $like = '%' . $wpdb->esc_like($search_term) . '%';

        // Prevent breaking default WHERE
        $search = " AND ( 
            ({$wpdb->posts}.post_title LIKE %s)
            OR EXISTS (
                SELECT 1
                FROM {$wpdb->postmeta}
                WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                AND {$wpdb->postmeta}.meta_key = '_sku'
                AND {$wpdb->postmeta}.meta_value LIKE %s
            )
            OR EXISTS (
                SELECT 1
                FROM {$wpdb->term_relationships} tr
                INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
                WHERE tr.object_id = {$wpdb->posts}.ID
                AND tt.taxonomy = 'product_tag'
                AND t.name LIKE %s
            )
        )";

        // Apply replacements for %s
        $search = $wpdb->prepare($search, $like, $like, $like);
    }

    return $search;
}

add_filter('the_posts', 'filter_product_search_results_by_polylang', 10, 2);
function filter_product_search_results_by_polylang($posts, $query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search() && $query->get('post_type') === 'product') {
        if (function_exists('pll_get_post_language')) {
            $lang = pll_current_language();
            $posts = array_filter($posts, function($post) use ($lang) {
                return pll_get_post_language($post->ID) === $lang;
            });
            $posts = array_values($posts); // reindex
        }
    }
    return $posts;
}

add_action( 'pre_get_posts', 'filter_woocommerce_search_by_polylang_language' );
function filter_woocommerce_search_by_polylang_language( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() && $query->get( 'post_type' ) === 'product' ) {
		if ( function_exists( 'pll_get_current_language' ) ) {
			$query->set( 'lang', pll_get_current_language() );
		}
	}
}
function get_language_root_url() {

    // Get the current full URL
    $url = home_url( add_query_arg( null, null ) );

    // Detect language segment dynamically
    if (preg_match('#/(fr|en)/#', $url, $match)) {
        $lang = $match[1];
        return trailingslashit( home_url( '/' . $lang . '/' ) );
    }

    // Default fallback (no language detected)
    return trailingslashit( home_url( '/' ) );
}

function get_search_base_url() {
    if ( function_exists( 'pll_get_current_language' ) ) {
        $lang = pll_get_current_language();

        // Get the search URL base for that language
        $search_url = home_url( '/' . $lang . '/' );
        
        // Optional: make sure it ends with a slash
        return trailingslashit( $search_url );
    }

    // Fallback for default language
    return home_url( '/' );
} 

remove_action('template_redirect', 'wc_template_redirect');
add_action('template_redirect', function () {
    // Call default WooCommerce redirect without SKU redirection
    if (is_search() && get_query_var('post_type') === 'product') {
        remove_action('template_redirect', 'wc_template_redirect', 10);
    }
});

add_filter('woocommerce_redirect_single_search_result', '__return_false');



/// Add pack-size attributes and quantity param to loop add-to-cart links
add_filter( 'woocommerce_loop_add_to_cart_link', 'myprefix_loop_add_to_cart_link_pack', 10, 2 );
function myprefix_loop_add_to_cart_link_pack( $html, $product ) {
    // get ACF field (your field name)
    $pack = 0;
    if ( function_exists( 'get_field' ) ) {
        $pack = intval( get_field( 'verpackungseinheit', $product->get_id() ) );
    }

    if ( $pack <= 1 ) {
        return $html; // nothing to change for single-unit products
    }

    // 1) Add data-pack-size and data-quantity attributes
    if ( strpos( $html, 'data-pack-size=' ) === false ) {
        $html = str_replace( '<a ', '<a data-pack-size="' . esc_attr( $pack ) . '" data-quantity="' . esc_attr( $pack ) . '" ', $html );
    }

    // 2) Ensure the href includes the quantity param for non-AJAX fallback
    if ( preg_match( '/href=[\'"]([^\'"]+)[\'"]/', $html, $m ) ) {
        $href = $m[1];
        if ( strpos( $href, 'quantity=' ) === false ) {
            $sep = ( strpos( $href, '?' ) === false ) ? '?' : '&';
            $new_href = $href . $sep . 'quantity=' . $pack;
            $html = preg_replace( '/href=[\'"][^\'"]+[\'"]/', 'href="' . esc_url( $new_href ) . '"', $html );
        }
    }

    // 3) Ensure the anchor has the usual classes so AJAX behavior remains (if theme removed them)
    if ( strpos( $html, 'ajax_add_to_cart' ) === false ) {
        // try to add to class attribute safely
        if ( preg_match( '/class=[\'"]([^\'"]*)[\'"]/', $html, $c ) ) {
            $classes = $c[1];
            $new_classes = 'add_to_cart_button ajax_add_to_cart ' . $classes;
            $html = preg_replace( '/class=[\'"][^\'"]*[\'"]/', 'class="' . esc_attr( $new_classes ) . '"', $html, 1 );
        } else {
            // no class attribute present, add one
            $html = str_replace( '<a ', '<a class="add_to_cart_button ajax_add_to_cart" ', $html );
        }
    }

    return $html;
}

// 4) Enqueue inline JS in footer to intercept archive add-to-cart and force pack quantity for AJAX adds
add_action( 'wp_footer', 'myprefix_pack_add_to_cart_js', 100 );
function myprefix_pack_add_to_cart_js() {
    // only print on frontend pages
    if ( is_admin() ) return;
    ?>
    <script type="text/javascript">
    (function($){
        $(document).on('click', 'a.add_to_cart_button', function(e){
            var $btn = $(this);

            // Only handle buttons that have pack data (we added data-pack-size on loop items)
            var pack = parseInt( $btn.attr('data-pack-size') || $btn.data('quantity') || 0, 10 );
            if ( ! pack || pack <= 1 ) {
                return; // not a pack product
            }

            // If button uses AJAX
            if ( $btn.hasClass('ajax_add_to_cart') ) {
                e.preventDefault();

                // get product id (many themes output data-product_id)
                var product_id = $btn.data('product_id') || $btn.attr('data-product_id');
                if ( ! product_id ) {
                    // try to parse from href ?add-to-cart=ID
                    var href = $btn.attr('href') || '';
                    var m = href.match(/add-to-cart=(\d+)/);
                    if ( m && m[1] ) product_id = m[1];
                }
                if ( ! product_id ) return;

                var data = {
                    product_id: product_id,
                    quantity: pack
                };

                // wc_add_to_cart_params.wc_ajax_url exists if WooCommerce scripts loaded
                var ajaxurl = '?wc-ajax=add_to_cart';
                if ( typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url ) {
                    ajaxurl = wc_add_to_cart_params.wc_ajax_url.replace( '%%endpoint%%', 'add_to_cart' );
                }

                // Post to WooCommerce AJAX add_to_cart
                $.post( ajaxurl, data, function( response ) {
                    // Trigger standard event to update fragments / mini-cart
                    try {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
                    } catch (ex) {
                        // fallback: reload the page to reflect cart
                        location.reload();
                    }
                }, 'json' ).fail(function(){
                    // fallback to default behaviour if AJAX fails
                    location.href = $btn.attr('href');
                });

            } else {
                // Non-AJAX: we already added quantity param to href so default navigation will include it
                // allow normal behavior
            }
        });
    })(jQuery);
    </script>
    <?php
}
// --- 1. Replace WooCommerce dropdown labels dynamically ---
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_sorting_labels' );
add_filter( 'woocommerce_catalog_orderby', 'custom_sorting_labels' );
function custom_sorting_labels( $options ) {
    if ( is_search() ) {
        return array(
            'relevance'  => pll__( 'Item Number' ),
            'date'       => pll__( 'Newest' ),
            'popularity' => pll__( 'Best Sellers' ),
        );
    }

    // Only show these three options
    return array(
        'menu_order'  => pll__( 'Item Number' ),
        'date'        => pll__( 'Newest' ),
        'popularity'  => pll__( 'Best Sellers' ),
    );
}

// --- 2. Set default catalog ordering to Item Number (SKU numeric ascending) ---
add_filter( 'woocommerce_default_catalog_orderby', function( $default ) {
    return 'menu_order';
});

// --- 3. Adjust ordering args for archives and search pages ---
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_catalog_ordering_args', 20 );
function custom_catalog_ordering_args( $args ) {
    $orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : 'menu_order';

    switch ( $orderby ) {
        case 'menu_order':
            // Sort by numeric SKU
            $args['meta_key'] = '_sku';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
            break;

        case 'date':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;

        case 'popularity':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'relevance':
        default:
            unset( $args['orderby'], $args['order'], $args['meta_key'] );
            break;
    }

    return $args;
}

// --- 4. Force consistent ordering on search pages ---
add_action( 'pre_get_posts', function( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_search() && isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) {
        $orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'menu_order';

        switch ( $orderby ) {
            case 'date':
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
                break;

            case 'popularity':
                $query->set( 'meta_key', '_wc_average_rating' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;

            case 'menu_order':
            default:
                // Numeric SKU sort
                $query->set( 'meta_key', '_sku' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'ASC' );
                break;
        }
    }
});

// --- 5. Force consistent ordering on product archives ---
add_action( 'woocommerce_product_query', function( $query ) {
    if ( ! is_admin() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
        $orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : 'menu_order';

        switch ( $orderby ) {
            case 'date':
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
                break;

            case 'popularity':
                $query->set( 'meta_key', '_wc_average_rating' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'DESC' );
                break;

            case 'menu_order':
            default:
                // Numeric SKU sort
                $query->set( 'meta_key', '_sku' );
                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'order', 'ASC' );
                break;
        }
    }
});
add_action( 'woocommerce_product_query', function( $query ) {
    if ( ! is_admin() && is_main_query() && is_product_category() ) {
        error_log( 'orderby: ' . print_r( $query->get( 'orderby' ), true ) );
        error_log( 'meta_key: ' . $query->get( 'meta_key' ) );
        error_log( 'order: ' . $query->get( 'order' ) );
    }
}, 99 );

// Ensure orderby and order are always defined to avoid PHP warnings on search page
add_filter( 'request', function( $vars ) {
    if ( is_search() && isset( $vars['post_type'] ) && 'product' === $vars['post_type'] ) {
        if ( empty( $vars['orderby'] ) ) {
            $vars['orderby'] = 'menu_order'; // default sort
        }
        if ( empty( $vars['order'] ) ) {
            $vars['order'] = 'ASC';
        }
    }
    return $vars;
});

function mytheme_enable_wc_gallery_features() {
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'mytheme_enable_wc_gallery_features' );
add_filter( 'woocommerce_single_product_carousel_options', 'mytheme_enable_wc_slider_arrows' );
function mytheme_enable_wc_slider_arrows( $options ) {
	$options['directionNav'] = true; // enable arrows
	// $options['controlNav']   = true; // keep thumbnails
	return $options;
}

add_action( 'woocommerce_check_cart_items', 'wc_minimum_order_by_language' );

function wc_minimum_order_by_language() {

$lang = pll_current_language(); // get current language
$total = WC()->cart->subtotal;

$minimum = 0;

if ( $lang == 'de' ) {
    $minimum = 50;
}

if ( $lang == 'fr' ) {
    $minimum = 100;
}

if ( $minimum > 0 && $total < $minimum ) {

    $remaining = $minimum - $total;
     $message = sprintf(
    pll__('Minimum order amount is €%s. Please add €%s more.'),
    $minimum,
    $remaining
);

    wc_add_notice($message,'error');

}

}
add_action( 'wp_footer', 'disable_checkout_button_min_order' );

function disable_checkout_button_min_order() {

if ( is_cart() ) {

$lang = pll_current_language(); // get current language

$minimum = 0;

if ( $lang == 'de' ) {
    $minimum = 50;
}

if ( $lang == 'fr' ) {
    $minimum = 100;
}

$current = WC()->cart->subtotal;

if ( $current < $minimum ) {
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
let checkoutButton = document.querySelector(".checkout-button");
if(checkoutButton){
checkoutButton.style.pointerEvents = "none";
checkoutButton.style.opacity = "0.5";
checkoutButton.innerHTML = "<?php 
    echo pll__ ('Minimum order €%s required', true); 
?>".replace('%s', <?php echo $minimum; ?>);
}
});
</script>

<?php
}

}

}

add_action('wp_enqueue_scripts', 'conditionally_remove_woocommerce_assets', 999);

function conditionally_remove_woocommerce_assets() {

    // If NOT a WooCommerce page → remove scripts
    if (
        !is_woocommerce() &&
        !is_cart() &&
        !is_checkout() &&
        !is_account_page()
    ) {

        /* ---------------------------
         * WooCommerce Core Scripts
         * --------------------------- */
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');
        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('js-cookie');
        wp_dequeue_script('wc-password-strength-meter');

        /* ---------------------------
         * WooCommerce Styles
         * --------------------------- */
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');

        /* ---------------------------
         * WooCommerce Blocks
         * --------------------------- */
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('wc-blocks-vendors-style');

        /* ---------------------------
         * Smart Coupons (WT)
         * --------------------------- */
        wp_dequeue_script('wt-smart-coupon-public');
        wp_dequeue_style('wt-smart-coupon-public');
        wp_deregister_script('wt-smart-coupon-public');

        /* ---------------------------
         * WooCommerce Fees & Discounts
         * --------------------------- */
        wp_dequeue_script('wcfad-script');
        wp_dequeue_script('wcfad_frontend');
        wp_deregister_script('wcfad-script');

        /* ---------------------------
         * Tracking / Attribution
         * --------------------------- */
        wp_dequeue_script('sourcebuster-js');
        wp_dequeue_script('wc-order-attribution');

    }
}

add_filter('script_loader_src', 'remove_wc_assets_by_url', 999, 2);
add_filter('style_loader_src', 'remove_wc_assets_by_url', 999, 2);

function remove_wc_assets_by_url($src, $handle) {

    if (
        !is_woocommerce() &&
        !is_cart() &&
        !is_checkout() &&
        !is_account_page()
    ) {

        // JS files
        if (
            strpos($src, 'wt-smart-coupon-public.js') !== false ||
            strpos($src, 'wcfad-script.js') !== false
        ) {
            return false;
        }

        // CSS files
        if (
            strpos($src, 'wc-blocks.css') !== false ||
            strpos($src, 'wcfad-style.css') !== false || strpos($src, 'wt-smart-coupon-public.css') != false
        ) {
            return false;
        }
    }

    return $src;
}

