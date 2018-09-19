<?php
/**
 * The template for displaying search results pages.
 *
 */
get_header();
?>
	<main>
		<a id="main-content" tabindex="-1"></a>

		<?php
	            $path = Path::join(get_template_directory(),  . 'page-parts', '_breadcrumbs.php');
	            if(is_readable($path)){
	                include $path;
	            }
		?>

		<div class="main-content">
			<div class="region main-content-region">

				<?php //******* LOOP
				if ( have_posts() ){
					while ( have_posts() ){

						the_post();

						echo '<div class="search-result">';

							?>

							<h2 class="search-title">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</h2>

							<?php
								the_excerpt();
						echo '</div>';
					}
				}else{
					echo '<p>No results were found. Please search by another keyword</p>';
				}
				?>

			</div>
        </div>

	</main>
<?php
get_footer();
