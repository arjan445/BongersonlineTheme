<?php
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-hero">
		<div class="container">
			<h1 class="mb-0"><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="py-5">
		<div class="container">
			<div class="row">
				<div class="<?php echo is_page( 'contact' ) ? 'col-12' : 'col-lg-8'; ?> guide-content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
