<?php
get_header();
$slides   = bo_get_slides();
$topics   = bo_get_topics();
$helpdesk = get_post_type_archive_link( 'handleiding' );
$contact  = bo_contact_url();
?>

<?php if ( $slides ) : ?>
<?php $interval = max( 2, (int) get_theme_mod( 'bo_slider_interval', 6 ) ) * 1000; ?>
<section class="home-slider">
	<div id="homeSlider" class="carousel slide carousel-fade" <?php echo count( $slides ) > 1 ? 'data-bs-ride="carousel"' : ''; ?> data-bs-interval="<?php echo (int) $interval; ?>">
		<?php if ( count( $slides ) > 1 ) : ?>
			<div class="carousel-indicators">
				<?php foreach ( $slides as $n => $s ) : ?>
					<button type="button" data-bs-target="#homeSlider" data-bs-slide-to="<?php echo (int) $n; ?>" <?php echo 0 === $n ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo (int) $n + 1; ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="carousel-inner">
			<?php foreach ( $slides as $n => $s ) : ?>
				<div class="carousel-item<?php echo 0 === $n ? ' active' : ''; ?>">
					<?php
					echo wp_get_attachment_image( $s['id'], 'full', false, array(
						'class'         => 'slide-img',
						'loading'       => 0 === $n ? 'eager' : 'lazy',
						'fetchpriority' => 0 === $n ? 'high' : 'auto',
						'sizes'         => '100vw',
					) );
					?>
					<?php if ( $s['title'] || $s['text'] || $s['btn'] ) : ?>
						<div class="slide-overlay"></div>
						<div class="slide-caption">
							<div class="container">
								<?php if ( $s['title'] ) : ?>
									<<?php echo 0 === $n ? 'h1' : 'h2'; ?> class="slide-title"><?php echo esc_html( $s['title'] ); ?></<?php echo 0 === $n ? 'h1' : 'h2'; ?>>
								<?php endif; ?>
								<?php if ( $s['text'] ) : ?>
									<p class="slide-text"><?php echo nl2br( esc_html( $s['text'] ) ); ?></p>
								<?php endif; ?>
								<?php if ( $s['btn'] && $s['url'] ) : ?>
									<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $s['url'] ); ?>"><?php echo esc_html( $s['btn'] ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( count( $slides ) > 1 ) : ?>
			<button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Vorige</span></button>
			<button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Volgende</span></button>
		<?php endif; ?>
	</div>
</section>
<?php else : ?>
<section class="hero">
	<div class="container position-relative">
		<h1 class="mb-3">Netwerk, camera's en Microsoft 365</h1>
		<p class="lead mb-4"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
		<div class="d-flex flex-wrap gap-2 position-relative" style="z-index:1">
			<?php if ( $contact ) : ?>
				<a class="btn btn-light btn-lg fw-semibold" href="<?php echo esc_url( $contact ); ?>">Contact opnemen</a>
			<?php endif; ?>
			<a class="btn btn-outline-light btn-lg" href="<?php echo esc_url( $helpdesk ); ?>">Helpdesk</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
while ( have_posts() ) :
	the_post();
	if ( '' !== trim( get_the_content() ) ) :
		?>
		<section class="py-5 home-content">
			<div class="container">
				<div class="home-blocks">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;
?>

<?php
$bo_projects = new WP_Query( array_merge( array( 'post_type' => 'project', 'posts_per_page' => 4, 'no_found_rows' => true ), bo_project_order_args() ) );
if ( $bo_projects->have_posts() ) :
	?>
	<section class="pb-5 home-projects">
		<div class="container">
			<div class="d-flex justify-content-between align-items-end mb-4">
				<h2 class="section-title mb-0">Recente projecten</h2>
				<a class="fw-semibold" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Alle projecten</a>
			</div>
			<div class="row g-4">
				<?php
				while ( $bo_projects->have_posts() ) :
					$bo_projects->the_post();
					get_template_part( 'template-parts/project-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php echo bo_clients_html(); // phpcs:ignore ?>

<section class="home-helpdesk bg-soft py-5">
	<div class="container py-lg-3">
		<div class="row g-4 g-lg-5 align-items-center">
			<div class="col-lg-5">
				<h2 class="section-title mb-3">Helpdesk</h2>
				<p class="text-secondary mb-4">Handleidingen voor het instellen van je e-mail, wifi en camera's. Zoek op trefwoord of kies een onderwerp.</p>
				<form class="hero-search mw-100 mb-3" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<i class="bi bi-search"></i>
					<input type="search" name="s" class="form-control" placeholder="Bijv. mail toevoegen op iPhone" aria-label="Zoek een handleiding">
					<input type="hidden" name="post_type" value="handleiding">
					<button class="btn btn-primary" type="submit">Zoeken</button>
				</form>
				<a class="fw-semibold" href="<?php echo esc_url( $helpdesk ); ?>">Alle handleidingen</a>
			</div>
			<div class="col-lg-7">
				<div class="row g-3">
					<?php foreach ( $topics as $t ) : ?>
						<div class="col-sm-6">
							<div class="topic-card p-4 position-relative">
								<div class="d-flex align-items-center gap-3">
									<span class="topic-icon flex-shrink-0"><i class="bi bi-<?php echo esc_attr( bo_topic_icon( $t ) ); ?>"></i></span>
									<div>
										<h3 class="mb-0"><a class="stretched-link text-reset" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></h3>
										<span class="count"><?php echo $t->count ? esc_html( sprintf( _n( '%d handleiding', '%d handleidingen', $t->count, 'bongersonline' ), $t->count ) ) : 'Binnenkort'; ?></span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="py-5">
	<div class="container">
		<div class="help-cta p-4 p-md-5">
			<div class="row align-items-center g-3">
				<div class="col-md-7">
					<h2 class="h3 fw-bold mb-2">Vraag of offerte?</h2>
					<p class="mb-0">Stuur een bericht via het contactformulier. Je krijgt zo snel mogelijk antwoord.</p>
				</div>
				<div class="col-md-5 text-md-end">
					<?php
					if ( $contact ) {
						echo '<a class="btn btn-light btn-lg me-2 mb-2" href="' . esc_url( $contact ) . '">Contact opnemen</a>';
					}
					echo implode( '', bo_contact_links( 'btn btn-outline-light me-2 mb-2' ) ); // phpcs:ignore
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
