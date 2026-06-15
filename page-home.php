<?php 
/**
 * 	Template Name: Home Page
 *
 *	This page template has a sidebar built into it, 
 * 	and can be used as a home page, in which case the title will not show up.
 *
*/
get_header();  ?>
	<div id="primary" class="container">
		
			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content();?>

					<?php // Imageslider
						$images = get_field('slider');
						if( $images ):
						
						$first_image = $images[0]; // first image
						// ?>

						

							<div id="slider" class="teaser" style="background-image: url('<?php echo $first_image; ?>');background-size: cover;">
									<?php foreach( $images as $image ): ?>
										<div class="teaser__item">
											<img src="<?php echo $image?>"  />
										</div>
									<?php endforeach; ?>
							</div>
					 	<?php endif; // end Imageslider?> 

					
					
					<main class="page-content">
        				<div class="container">
						<section class="category">


						<?php 
						$aktuelles = pll_get_post_translations(80);
						/* $grusskarten = pll_get_term_translations(11671);
						$papeterie = pll_get_term_translations(11683);
						$kunstdrucke = pll_get_term_translations(11675);
						$originalgrafik = pll_get_term_translations(11679); */

						
						if(term_exists('grusskarten','product_cat'))$grusskarten = pll_get_term_translations(get_term_by('slug','grusskarten','product_cat')->term_id);
						if(term_exists('papeterie-und-sonstiges','product_cat'))$papeterie = pll_get_term_translations(get_term_by('slug','papeterie-und-sonstiges','product_cat')->term_id);
						if(term_exists('kunstdrucke','product_cat'))$kunstdrucke = pll_get_term_translations(get_term_by('slug','kunstdrucke','product_cat')->term_id);
						if(term_exists('originalgrafik','product_cat'))$originalgrafik = pll_get_term_translations(get_term_by('slug','originalgrafik','product_cat')->term_id);
						
						?>

						<?php if( have_rows('category_links') ): ?>
							<?php while( have_rows('category_links') ): the_row(); ?>
							
									<a class="item" href="<?php echo !empty($grusskarten) ? esc_url( get_term_link($grusskarten[pll_current_language()] ) ) : ""; ?>">
										<img class="item__image " title="Grußkarten" alt="Grußkarten" src="<?php the_sub_field("gruskarten");?>">
										<span class="item__name"><?php pll_e("Grußkarten"); ?></span>
									</a>
									<a class="item" href="<?php echo !empty($papeterie) ? esc_url( get_term_link($papeterie[pll_current_language()] ) ) : ""; ?>">
										<img class="item__image " title="Papeterie" alt="Papeterie" src="<?php the_sub_field("papeterie");?>">
										<span class="item__name"><?php pll_e("Papeterie"); ?></span>
									</a>
									<a id="aktuelles-link" class="item" href="<?php echo !empty($aktuelles) ? esc_url( get_permalink($aktuelles[pll_current_language()] ) ) : ""; ?>">
										<img class="item__image item--2x" title="Aktuelles" alt="Aktuelles" src="<?php the_sub_field("aktuelles");?>">
										<span class="item__name"><?php pll_e("Aktuelles"); ?></span>
									</a>
									<a class="item" href="<?php echo !empty($kunstdrucke) ? esc_url( get_term_link($kunstdrucke[pll_current_language()] ) ) : ""; ?>">
										<img class="item__image " title="Kunstdrucke" alt="Kunstdrucke" src="<?php the_sub_field("kunstdrucke");?>">
										<span class="item__name"><?php pll_e("Kunstdrucke"); ?></span>
									</a>
									<a class="item" href="<?php echo !empty($originalgrafik) ? esc_url( get_term_link($originalgrafik[pll_current_language()] ) ) : ""; ?>">
										<img class="item__image " title="Originalgrafik" alt="Originalgrafik" src="<?php the_sub_field("originalgrafik");?>">
										<span class="item__name"><?php pll_e("Originalgrafik"); ?></span>
									</a>
								
							<?php endwhile; ?>
						<?php endif;?>

						</section>

							<?php 
							$curr_month = date( "n",time() );
							$month = "";
							switch($curr_month){
								case "1":
									$month = "Januar";
									break;
								case "2":
									$month = "Februar";
									break;
								case "3":
									$month = "März";
									break;
								case "4":
									$month = "April";
									break;
								case "5":
									$month = "Mai";
									break;
								case "6":
									$month = "Juni";
									break;
								case "7":
									$month = "Juli";
									break;
								case "8":
									$month = "August";
									break;
								case "9":
									$month = "September";
									break;
								case "10":
									$month = "Oktober";
									break;
								case "11":
									$month = "November";
									break;
								case "12":
									$month = "Dezember";
									break;
							}


							$products = wc_get_products([
								'category' => get_term(get_field('monatsvorschlag'), 'product_cat')->slug,
								'post_status' => 'publish',
								'limit' => 500
							 ]);
							 $arrLinks = array();
							 $a = 0;
							 foreach($products as $product){
								
								if(!str_contains(wp_get_attachment_image_url($product->get_data()['image_id'] ) , "missing") && wp_get_attachment_image_url($product->get_data()['image_id'], "full")){
									$arrLinks[$a]['image'] = wp_get_attachment_image_url($product->get_data()['image_id'], "full");
									$arrLinks[$a]['permalink'] = get_permalink($product->get_id());
									$a++;
								}
								
							 }
							 if(!empty($arrLinks))$index = array_rand($arrLinks,1);
							?>
							<div class="row medium-unstack">
								<div id="empfehlung" class="column medium-6">
									<a href="<?php echo $arrLinks[$index]['permalink']?>">
										<h1 class="heading heading--alternative"><?php echo pll__("Unser Vorschlag für den")." ".pll__( $month )?></h1>
										<img alt="..." src="<?php echo $arrLinks[$index]['image']?>" title="...">
									</a>
								</div>

								<div class="column medium-6">
									<h2 class="heading heading--alternative"><?php pll_e("Über uns"); ?></h2>
									<?php the_field('uber_uns'); ?>

								</div>
							</div>

						</div>
					</main>
					
					


						
					

				<?php endwhile; //  stop the page loop  ?>

			<?php else : ?>
				
				<article class="post error">
					<h1 class="404">Nothing has been posted like that yet</h1>
				</article>

			<?php endif; ?>
		
		
	</div><!-- #primary -->
<?php get_footer(); // This fxn gets the footer.php file and renders it ?>