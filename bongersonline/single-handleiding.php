<?php
get_header();
while ( have_posts() ) :
	the_post();
	$topics  = get_the_terms( get_the_ID(), 'onderwerp' );
	$topic   = ( $topics && ! is_wp_error( $topics ) ) ? $topics[0] : null;
	$content = apply_filters( 'the_content', get_the_content() );
	$toc     = bo_toc( $content );
	?>
	<section class="page-hero">
		<div class="container">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'handleiding' ) ); ?>">Helpdesk</a></li>
					<?php if ( $topic ) : ?>
						<li class="breadcrumb-item"><a href="<?php echo esc_url( get_term_link( $topic ) ); ?>"><?php echo esc_html( $topic->name ); ?></a></li>
					<?php endif; ?>
				</ol>
			</nav>
			<h1 class="mb-3"><?php the_title(); ?></h1>
			<div class="d-flex flex-wrap gap-3 small text-secondary align-items-center">
				<span><i class="bi bi-clock me-1"></i><?php echo (int) bo_reading_time(); ?> min lezen</span>
				<span><i class="bi bi-arrow-repeat me-1"></i>Bijgewerkt <?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?></span>
				<button type="button" class="btn btn-sm btn-outline-primary no-print" onclick="window.print()"><i class="bi bi-printer me-1"></i>Afdrukken</button>
			</div>
		</div>
	</section>

	<section class="py-5">
		<div class="container">
			<div class="row g-5">
				<article class="col-lg-8 guide-content">
					<?php
					echo $content; // phpcs:ignore
					wp_link_pages();
					?>
				</article>
				<?php if ( count( $toc ) > 1 ) : ?>
					<aside class="col-lg-4 toc-col d-none d-lg-block">
						<nav class="toc" aria-label="Inhoud">
							<h6 class="mb-3">In deze handleiding</h6>
							<ol>
								<?php foreach ( $toc as $h ) : ?>
									<li><a href="#<?php echo esc_attr( $h[1] ); ?>"><?php echo esc_html( wp_strip_all_tags( $h[2] ) ); ?></a></li>
								<?php endforeach; ?>
							</ol>
						</nav>
					</aside>
				<?php endif; ?>
			</div>

			<?php
			if ( $topic ) :
				$related = new WP_Query( array(
					'post_type'      => 'handleiding',
					'posts_per_page' => 4,
					'post__not_in'   => array( get_the_ID() ),
					'no_found_rows'  => true,
					'tax_query'      => array( array( 'taxonomy' => 'onderwerp', 'terms' => $topic->term_id ) ),
				) );
				if ( $related->have_posts() ) :
					?>
					<div class="row mt-5 no-print">
						<div class="col-lg-8">
							<h2 class="section-title h4 mb-3">Meer over <?php echo esc_html( $topic->name ); ?></h2>
							<?php get_template_part( 'template-parts/guide-list', null, array( 'query' => $related, 'show_topic' => false ) ); ?>
						</div>
					</div>
					<?php
				endif;
			endif;
			?>

			<div class="mt-5">
				<?php get_template_part( 'template-parts/help-cta' ); ?>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
