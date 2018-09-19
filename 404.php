<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

	<main>
		<a id="main-content" tabindex="-1"></a>
		<div class="main-content" id="main-content">
			<div class="region main-content-region">
				<div class="column-layout">
					<div class="left-column">

						<?php
						            $path = Path::join(get_template_directory(),  . 'page-parts', '_breadcrumbs.php');
						            if(is_readable($path)){
						                include $path;
						            }
						?>

						<div class="content-block main-content-block 404-error">
							<h1 class="page-title"><?php echo 'Oops! That page can&rsquo;t be found.'; ?></h1>
							<p><?php echo 'It looks like nothing was found at this location.'; ?></p>
						</div><!-- .error-404 -->

					</div>
				</div>
			</div>
		</div>
	</main>
<?php get_footer(); ?>
