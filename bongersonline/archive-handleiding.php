<?php
get_header();
$is_tax = is_tax( 'onderwerp' );
$term   = $is_tax ? get_queried_object() : null;
?>
<section class="page-hero">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<?php if ( $is_tax ) : ?>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'handleiding' ) ); ?>">Helpdesk</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( $term->name ); ?></li>
				<?php else : ?>
					<li class="breadcrumb-item active" aria-current="page">Helpdesk</li>
				<?php endif; ?>
			</ol>
		</nav>
		<div class="d-flex align-items-center gap-3">
			<?php if ( $is_tax ) : ?>
				<span class="topic-icon"><i class="bi bi-<?php echo esc_attr( bo_topic_icon( $term ) ); ?>"></i></span>
			<?php endif; ?>
			<h1 class="mb-0"><?php echo $is_tax ? esc_html( $term->name ) : 'Helpdesk'; ?></h1>
		</div>
		<?php if ( $is_tax && $term->description ) : ?>
			<p class="lead text-secondary mt-2 mb-0"><?php echo esc_html( $term->description ); ?></p>
		<?php elseif ( ! $is_tax ) : ?>
			<p class="lead text-secondary mt-2 mb-0">Handleidingen en uitleg voor je e-mail, netwerk en camera's. Kies een onderwerp of filter direct.</p>
		<?php endif; ?>
	</div>
</section>

<section class="py-5">
	<div class="container">
		<div class="row g-4 g-lg-5">
			<aside class="col-lg-3">
				<div class="list-group rounded-4 shadow-sm">
					<a href="<?php echo esc_url( get_post_type_archive_link( 'handleiding' ) ); ?>" class="list-group-item list-group-item-action <?php echo $is_tax ? '' : 'active'; ?>"><i class="bi bi-grid me-2"></i>Alle onderwerpen</a>
					<?php foreach ( bo_get_topics() as $t ) : ?>
						<a href="<?php echo esc_url( get_term_link( $t ) ); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php echo ( $term && $term->term_id === $t->term_id ) ? 'active' : ''; ?>">
							<span><i class="bi bi-<?php echo esc_attr( bo_topic_icon( $t ) ); ?> me-2"></i><?php echo esc_html( $t->name ); ?></span>
							<span class="badge rounded-pill text-bg-light"><?php echo (int) $t->count; ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</aside>
			<div class="col-lg-9">
				<?php if ( have_posts() ) : ?>
					<div class="position-relative mb-3">
						<i class="bi bi-funnel position-absolute text-secondary" style="left:1rem;top:50%;transform:translateY(-50%)"></i>
						<input type="search" class="form-control form-control-lg ps-5 rounded-4" placeholder="Filter op trefwoord…" data-guide-filter aria-label="Filter handleidingen">
					</div>
					<?php get_template_part( 'template-parts/guide-list', null, array( 'show_topic' => ! $is_tax ) ); ?>
					<p class="text-secondary mt-3 d-none" data-guide-empty>Geen handleidingen gevonden voor dit trefwoord.</p>
				<?php else : ?>
					<div class="p-5 text-center rounded-4 bg-soft">
						<i class="bi bi-hourglass-split fs-1 text-primary"></i>
						<h2 class="h5 mt-3">Hier komen binnenkort handleidingen</h2>
						<p class="text-secondary mb-3">We zijn ermee bezig. Heb je nu al een vraag? Dan helpen we je graag verder.</p>
						<?php $bo_c = bo_contact_url(); if ( $bo_c ) : ?>
							<a class="btn btn-primary" href="<?php echo esc_url( $bo_c ); ?>"><i class="bi bi-chat-text me-1"></i>Neem contact op</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
