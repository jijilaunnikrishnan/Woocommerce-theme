<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

//echo "<style>.main-navigation{display:none;}<(style>";
$current_cat = get_queried_object();
//echo "<pre style='color:tomato';>".var_export($current_cat,true)."sadfsdf</pre>";
wc_get_template( 'archive-category.php' );
$taxonomy     = 'product_cat';
  $orderby      = 'name';  
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 0;      // 1 for yes, 0 for no  
  $title        = '';  
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
 $all_categories = get_categories( $args );
 /* echo "<pre>".var_export($all_categories,true)."</pre>"; */
 /* foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;       
        echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';

        $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
        );
        $sub_cats = get_categories( $args2 );
        if($sub_cats) {
            foreach($sub_cats as $sub_category) {
                echo  "<div style='background:tomato'>".$sub_category->name."</div>" ;
            }   
        }
    }       
}


echo "----------------------------";

hierarchical_category_tree( 0 ); // the function call; 0 for all categories; or cat ID  

function hierarchical_category_tree( $cat ) {
    // wpse-41548 // alchymyth // a hierarchical list of all categories //

  $next = get_categories('hide_empty=false&orderby=name&order=ASC&parent=' . $cat);

  if( $next ) :    
    foreach( $next as $cat ) :
    echo '<ul><li><strong>' . $cat->name . '</strong>';
    echo ' / <a href="' . get_category_link( $cat->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $cat->name ) . '" ' . '>View ( '. $cat->count . ' posts )</a>  '; 
    echo ' / <a href="'. get_admin_url().'edit-tags.php?action=edit&taxonomy=category&tag_ID='.$cat->term_id.'&post_type=post" title="Edit Category">Edit</a>'; 
    hierarchical_category_tree( $cat->term_id );
    endforeach;    
  endif;

  echo '</li></ul>'; echo "\n";
}  

function has_Childrenx($cat_id)
{
    $children = get_terms(array( 'taxonomy'=>'product_cat','parent' => $cat_id, 'hide_empty' => false ));
    echo "<pre style='color:tomato';>".var_export($children,true)."</pre>";
    if ($children){
        return true;
    }
    return false;
}

function has_Parentsx($cur_cat){
    
    if($cur_cat->parent == 0){
        return true;
    }else{
        return false;
    }
}

if(has_Children(17)){
    echo "ja";
}else{
    echo "nein";
}
    // get the thumbnail id using the queried category term_id
    $thumbnail_id = get_term_meta( $current_cat->term_id, 'thumbnail_id', true ); 

    // get the image URL
    $image = wp_get_attachment_url( $thumbnail_id ); 

    // print the IMG HTML
    echo "<img src='{$image}' alt='' width='76' height='36' />"; */
?>

