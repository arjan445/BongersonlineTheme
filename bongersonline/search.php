<?php
get_header();
?>
<section class="page-hero">
	<div class="container">
		<p class="text-secondary mb-1">Zoekresultaten voor</p>
		<h1 class="mb-3">&ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;</h1>
		<form class="d-flex gap-2" style="max-width:560px" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" class="form-control form-control-lg" value="<?php echo esc_attr( get_search_query() ); ?>" aria-label="Zoeken">
			<button class="btn btn-primary px-4" type="submit">Zoeken</button>
		</form>
	</div>
</section>
<section class="py-5">
	<div class="container">
		<div class="row">
			<div class="col-lg-9">
				<?php if ( have_posts() ) : ?>
					<p class="text-secondary"><?php echo esc_html( sprintf( _n( '%d resultaat', '%d resultaten', $wp_query->found_posts, 'bongersonline' ), $wp_query->found_posts ) ); ?></p>
					<?php get_template_part( 'template-parts/guide-list' ); ?>
					<div class="mt-4"><?php the_posts_pagination(); ?></div>
				<?php else : ?>
					<div class="p-5 rounded-4 bg-soft">
						<h2 class="h5">Niets gevonden</h2>
						<p class="text-secondary">Probeer een ander zoekwoord, of bekijk <a href="<?php echo esc_url( get_post_type_archive_link( 'handleiding' ) ); ?>">alle handleidingen</a>.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
