<?php
get_header();
while ( have_posts() ) :
	the_post();
	$client  = get_post_meta( get_the_ID(), 'bo_client', true );
	$place   = get_post_meta( get_the_ID(), 'bo_place', true );
	$types   = bo_project_types();
	$thumb   = get_post_thumbnail_id();
	$gallery = array_values( array_diff( bo_project_gallery_ids(), array( $thumb ) ) );
	$all     = $thumb ? array_merge( array( $thumb ), $gallery ) : $gallery;
	$offset  = $thumb ? 1 : 0;
	$contact = bo_contact_url();
	?>
	<section class="page-hero">
		<div class="container">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Projecten</a></li>
				</ol>
			</nav>
			<h1 class="mb-3"><?php the_title(); ?></h1>
			<div class="project-meta">
				<?php if ( $client ) : ?>
					<span><i class="bi bi-building me-1"></i><?php echo esc_html( $client ); ?></span>
				<?php endif; ?>
				<?php if ( $place ) : ?>
					<span><i class="bi bi-geo-alt me-1"></i><?php echo esc_html( $place ); ?></span>
				<?php endif; ?>
				<?php foreach ( $types as $t ) : ?>
					<span><i class="bi bi-tools me-1"></i><a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></span>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="py-5">
		<div class="container">
			<div class="row g-4 g-lg-5">
				<div class="col-lg-8">
					<?php if ( $thumb ) : ?>
						<a class="project-hero-img mb-4" href="<?php echo esc_url( wp_get_attachment_image_url( $thumb, 'full' ) ); ?>" data-bo-lightbox="0">
							<?php echo wp_get_attachment_image( $thumb, 'large', false, array( 'loading' => 'eager' ) ); ?>
						</a>
					<?php endif; ?>
					<div class="guide-content">
						<?php the_content(); ?>
					</div>
					<?php if ( $gallery ) : ?>
						<h2 class="h4 fw-bold mt-5 mb-3">Foto's</h2>
						<div class="row g-3 project-gallery">
							<?php foreach ( $gallery as $n => $id ) : ?>
								<div class="col-6 col-md-4">
									<a href="<?php echo esc_url( wp_get_attachment_image_url( $id, 'full' ) ); ?>" data-bo-lightbox="<?php echo (int) ( $n + $offset ); ?>">
										<?php echo wp_get_attachment_image( $id, 'medium_large', false, array( 'loading' => 'lazy' ) ); ?>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<aside class="col-lg-4">
					<div class="project-facts">
						<h2 class="h5 fw-bold mb-3">Projectgegevens</h2>
						<dl class="mb-0">
							<?php if ( $client ) : ?>
								<dt>Opdrachtgever</dt><dd><?php echo esc_html( $client ); ?></dd>
							<?php endif; ?>
							<?php if ( $place ) : ?>
								<dt>Plaats</dt><dd><?php echo esc_html( $place ); ?></dd>
							<?php endif; ?>
							<?php if ( $types ) : ?>
								<dt>Type installatie</dt>
								<dd><?php echo implode( ', ', array_map( function ( $t ) { return '<a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>'; }, $types ) ); // phpcs:ignore ?></dd>
							<?php endif; ?>
						</dl>
						<?php if ( $contact ) : ?>
							<hr>
							<p class="small text-secondary mb-2">Ook zoiets nodig?</p>
							<a class="btn btn-primary w-100" href="<?php echo esc_url( $contact ); ?>">Contact opnemen</a>
						<?php endif; ?>
					</div>
				</aside>
			</div>

			<?php
			$more = new WP_Query( array(
				'post_type'      => 'project',
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
				'no_found_rows'  => true,
			) );
			if ( $more->have_posts() ) :
				?>
				<div class="mt-5 pt-3">
					<div class="d-flex justify-content-between align-items-end mb-3">
						<h2 class="section-title h4 mb-0">Meer projecten</h2>
						<a class="fw-semibold" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Alle projecten</a>
					</div>
					<div class="row g-4">
						<?php
						while ( $more->have_posts() ) :
							$more->the_post();
							get_template_part( 'template-parts/project-card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $all ) : ?>
		<div class="modal fade" id="projectLightbox" tabindex="-1" aria-label="Foto's" aria-hidden="true">
			<div class="modal-dialog modal-xl modal-dialog-centered">
				<div class="modal-content bg-transparent border-0">
					<button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal" aria-label="Sluiten"></button>
					<div id="projectCarousel" class="carousel slide">
						<div class="carousel-inner">
							<?php foreach ( $all as $n => $id ) : ?>
								<div class="carousel-item<?php echo 0 === $n ? ' active' : ''; ?>">
									<?php echo wp_get_attachment_image( $id, 'full', false, array( 'class' => 'd-block mx-auto', 'loading' => 'lazy' ) ); ?>
								</div>
							<?php endforeach; ?>
						</div>
						<?php if ( count( $all ) > 1 ) : ?>
							<button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Vorige</span></button>
							<button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Volgende</span></button>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
endwhile;
get_footer();
