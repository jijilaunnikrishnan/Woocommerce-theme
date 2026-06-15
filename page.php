<?php
/**
 * The template for displaying any single page.
 *
 */

get_header(); ?>



			<?php if ( have_posts() ) : 
			// Do we have any posts/pages in the databse that match our query?
			?>

				<?php while ( have_posts() ) : the_post(); 
				// If we have a page to show, start a loop that will display it
				?>


				<div class="container">
					<nav class="woocommerce-breadcrumb" aria-label="Breadcrumb">
						<a href="/">Home</a>&nbsp;/&nbsp;<span><?php the_title(); // Display the title of the page ?></span>
					</nav>
				</div>


				<main class="page-content">
					<div class="container">

						<div class="row align-center">
							<div class=<?php if(!is_user_logged_in() && (is_page(8) || is_page(86990) ) ): echo  "'column medium-6'"; else: echo "'column large-8'"; endif;?>>
							
								<h1 class="heading"><?php the_title(); // Display the title of the page ?></h1>
								
								<div class="the-content">
									<?php the_content(); 
									// This call the main content of the page, the stuff in the main text box while composing.
									// This will wrap everything in p tags
									?>
									
									<?php wp_link_pages(); // This will display pagination links, if applicable to the page ?>
								</div><!-- the-content -->
								
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