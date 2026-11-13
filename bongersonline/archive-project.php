<?php
get_header();
$is_tax = is_tax( 'installatietype' );
$term   = $is_tax ? get_queried_object() : null;
$types  = get_terms( array( 'taxonomy' => 'installatietype', 'hide_empty' => true ) );
?>
<section class="page-hero">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
				<?php if ( $is_tax ) : ?>
					<li class="breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Projecten</a></li>
					<li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( $term->name ); ?></li>
				<?php else : ?>
					<li class="breadcrumb-item active" aria-current="page">Projecten</li>
				<?php endif; ?>
			</ol>
		</nav>
		<h1 class="mb-0"><?php echo $is_tax ? esc_html( $term->name ) : 'Projecten'; ?></h1>
		<p class="lead text-secondary mt-2 mb-0">
			<?php echo ( $is_tax && $term->description ) ? esc_html( $term->description ) : 'Een greep uit uitgevoerde installaties.'; ?>
		</p>
	</div>
</section>

<section class="py-5">
	<div class="container">
		<?php if ( ! is_wp_error( $types ) && count( $types ) > 1 ) : ?>
			<div class="project-filter d-flex flex-wrap gap-2 mb-4">
				<a class="btn btn-sm <?php echo $is_tax ? 'btn-outline-primary' : 'btn-primary'; ?>" href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>">Alle projecten</a>
				<?php foreach ( $types as $t ) : ?>
					<a class="btn btn-sm <?php echo ( $term && $term->term_id === $t->term_id ) ? 'btn-primary' : 'btn-outline-primary'; ?>" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="row g-4">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/project-card' );
				endwhile;
				?>
			</div>
			<div class="mt-5"><?php the_posts_pagination( array( 'prev_text' => 'Vorige', 'next_text' => 'Volgende' ) ); ?></div>
		<?php else : ?>
			<div class="p-5 text-center rounded-4 bg-soft">
				<i class="bi bi-images fs-1 text-primary"></i>
				<h2 class="h5 mt-3">Hier komen binnenkort projecten</h2>
				<p class="text-secondary mb-0">Benieuwd wat er mogelijk is? Neem gerust contact op.</p>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
