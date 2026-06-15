<?php 
/**
 * 	Template Name: search Page
 *
 *	This page template has a sidebar built into it, 
 * 	and can be used as a home page, in which case the title will not show up.
 *
*/
get_header();  ?>



			<?php if ( have_posts() ) : 
			// Do we have any posts/pages in the databse that match our query?
			?>

				<?php while ( have_posts() ) : the_post(); 
				// If we have a page to show, start a loop that will display it
				?>


				
<div class="container">
    <nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php pll_e('Startseite'); ?></a>
        &nbsp;/&nbsp;
        <span><?php the_title(); ?></span>
    </nav>
</div>

<main class="page-content">
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
							<div class="search_btn"><p><?php pll_e( 'Such mit tags:', 'woocommerce' ); ?></p> <a class="button" href="<?php 
       echo $action_url. '?s=' . urlencode( pll__( 'Neuheiten', 'woocommerce' ) ) . '&post_type=product'; 
     ?>"><?php pll_e( 'Neuheiten', 'woocommerce' ); ?></a>
				</div>
  </div>
            </div>
        </div>
    </div><!-- .container -->
				</main><!-- .page-content -->



				<?php endwhile; // OK, let's stop the page loop once we've displayed it ?>

			<?php else : // Well, if there are no posts to display and loop through, let's apologize to the reader (also your 404 error) ?>
				
				<div class="container">
					<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">
						<a href="/">Home</a>&nbsp;/&nbsp;
					</nav>
				</div>


				<main class="page-content">
					<div class="container">

						<div class="row align-center">
							<div class="column large-8">
							
								<h1 class="heading">404</h1>
								
								<div class="the-content">
									<p>Der Inhalt dieser Seite konnte nicht gefunden werden.</p>
									
									
								</div><!-- the-content -->
								
							</div>
						</div>
					</div><!-- .container -->
				</main><!-- .page-content -->

			<?php endif; // OK, I think that takes care of both scenarios (having a page or not having a page to show) ?>

		
<?php get_footer(); // This fxn gets the footer.php file and renders it ?>