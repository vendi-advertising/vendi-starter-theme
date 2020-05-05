<?php
// get the Testimonial Block
if( get_row_layout() == 'testimonial' ):
    $testimonialimage = get_sub_field('testimonial_background_image');
?>
<div class="testimonial" style="background-image: url(<?php echo $testimonialimage['url']; ?>); background-size: cover;">
	<div class="testimonial-container">
        <div class="container">
            <?php if (get_sub_field ('testimonial_copy' )): ?>
            <div class="testimonial-copy">
                <?php the_sub_field('testimonial_copy'); ?>
            </div>
            <?php endif; ?>
            <?php if (get_sub_field('testimonial_author')): ?>
            <div class="testimonial-author">
                – <?php the_sub_field('testimonial_author'); ?>
            </div>
            <?php endif; ?>
        </div>
	</div>
</div>
<?php
	endif;
	// end the Testimonial Block
?>
