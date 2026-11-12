<?php
get_header();
?>
<section class="page-hero">
	<div class="container">
		<h1 class="mb-0">
			<?php
			if ( is_home() ) {
				echo 'Nieuws';
			} elseif ( is_archive() ) {
				the_archive_title();
			} else {
				wp_title( '' );
			}
			?>
		</h1>
	</div>
</section>
<section class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php if ( have_posts() ) : ?>
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="mb-5">
							<h2 class="h4 fw-bold"><a class="text-reset" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="small text-secondary"><?php echo esc_html( get_the_date() ); ?></p>
							<div class="guide-content"><?php is_singular() ? the_content() : the_excerpt(); ?></div>
						</article>
					<?php endwhile; ?>
					<?php the_posts_pagination(); ?>
				<?php else : ?>
					<p>Er is hier nog niets te zien.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
