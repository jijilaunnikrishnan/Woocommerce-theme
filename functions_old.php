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
						'key'       => $_GET['meta_key'],
						'value'     => $return['wawi_parent_id'],
						'compare'   => '='
					 )
				)
			);
			$data_parent = get_terms($args_parent);
			$return['parent_term_id'] = $data_parent[0]->term_id;
			if(empty($data_parent[0]->term_id))$return['parent_term_id'] = 0;
		return $return;

    }
}

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

function display_product_categories_hierarchy($parent_id = 0, $first = 0) {
	$args = array(
		'taxonomy'     => 'product_cat',
		'hide_empty'   => false,
		'parent'       => $parent_id
	);

	$categories = get_categories($args);
	if ($categories) {
		if ($first == 0){
			echo '<ul class="page-nav__menu megamenu">';
			$first++;
		} else if($first == 1){
			echo '<ul class="megamenu__nested ">';
			$first++;
		} else if($first == 2){
			echo '<ul class="megamenu__nested megamenu__nested--columned">';
			$first = 0;
		}
		
		foreach ($categories as $category) {
			if($category->name == "Unkategorisiert" || $category->name == "Uncategorized" || strstr($category->name, "Testkategorie") )continue;
			if($first == 1 && !in_array($category->name,['Grußkarten', 'Kunstdrucke','Originalgrafik','Papeterie und Sonstiges']))continue;
			$catName = $category->name;
			if($category->name == "Papeterie und Sonstiges")$catName = "Papeterie";
			echo '<li class="megamenu__item "><a class="megamenu__link" href="' . get_term_link($category) . '">' . pll__($catName) /* . pll_get_term($category->term_id,"de")  */. '</a>';
			display_product_categories_hierarchy($category->term_id, $first); // Recursive call
			echo '</li>';
		}
		if($first == 1){
			$get_lang_link = "";
			switch( pll_current_language()){
				case "de":
					$aktuelles = esc_url( get_permalink(80) );
					$ueberuns = esc_url( get_permalink(82) );
					$kontakt = esc_url( get_permalink(84) );
					break;
				case "fr":
					$aktuelles = esc_url( get_permalink(121169) );
					$ueberuns = esc_url( get_permalink(70348) );
					$kontakt = esc_url( get_permalink(121171) );
					break;
			}
			echo '<li class="megamenu__item special-item">
			<a class="megamenu__link" href="'.$aktuelles.'">'.pll__("Aktuelles").'</a>
		</li>
	
		<li class="megamenu__item special-item">
			<a class="megamenu__link" href="'.$ueberuns.'">'.pll__("Über uns").'</a>
		</li>
	
		<li class="megamenu__item special-item">
			<a class="megamenu__link" href="'.$kontakt.'">'.pll__("Kontakt").'</a>
		</li>';
		}
		echo '</ul>';
	}
}

function product_categories_hierarchy_shortcode() {
ob_start(); // Start output buffering
display_product_categories_hierarchy(); // Call the function
return ob_get_clean(); // Return the output
}
add_shortcode('product_categories_hierarchy', 'product_categories_hierarchy_shortcode');


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
    $children2[$term->name] = get_taxonomy_hierarchy( $taxonomy, $term->term_id );
    $children2[$term->name]['id'] = $term->term_id;
    $children2[$term->name]['language'] = pll_get_term_language($term->term_id);
    $children2[$term->name]['translations'] = pll_get_term_translations($term->term_id);
		// add the term to our new array
    //echo $term->name."<br>";
		$children[ $term->name ] = (array)$term;
	}

	// send the results back to the caller
	return (array)$children2;
}

pll_register_string("Product-Category", "Grußkarten","WooCommerce");
pll_register_string("Product-Category", "Papeterie und Sonstiges","WooCommerce");

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
	

		woocommerce_form_field(
				'locale',
				array(
					'type'        => 'select',
					'required'    => true, // just adds an "*"
					'label'       => pll__('Sprache'),
					'options'	  => ["de_DE" => "Deutsch", "fr_FR" => "Französisch"]
				),
				( isset( $_POST[ 'locale' ] ) ? $_POST[ 'locale' ] : '' )
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

    wp_enqueue_script( 'wc-password-strength-meter','https://actetre.enpr.de/wp-content/plugins/woocommerce/assets/js/frontend/password-strength-meter.min.js' );
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
add_filter( 
	'rest_pre_dispatch', 
	function (mixed $result, WP_REST_Server $server, WP_REST_Request $request) {

		//return $result;

		/* $request['meta_data'][5] =  [
			 "key" => "billing_company",
            "value"=> "Maison de la Presse Lanet"
		];
            */
	$request_data = json_decode($request->get_body(),true); 
	  // Get the route being requested
	  if(isset($request_data['username'])){
		$request_data['username'] = wc_create_new_customer_username($request_data['username']);
		$request->set_body(json_encode($request_data,JSON_PRETTY_PRINT));
	  }
	  
	  
	  return $result;//json_encode($request_data,JSON_PRETTY_PRINT);
	  
  },10,3);
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

	$return_url = pll_get_post(wc_get_page_id( 'myaccount' ),$lang);
	$url = get_permalink($return_url);
	echo var_export("Test: ".$url,true);
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
			<option value="fr_FR">Französisch</option>
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
	
	foreach($data as $cat){
		
		$image_id = "";
		$featured_products = wc_get_products(array(
			'status'               => 'publish',
			'visibility'           => 'visible',
			'category'			   => $cat->slug,
			'return'               => 'ids'
		));


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