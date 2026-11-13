<?php
$bo_types = bo_project_types();
$bo_place = get_post_meta( get_the_ID(), 'bo_place', true );
?>
<div class="col-sm-6 col-lg-4">
	<article class="project-card h-100 position-relative">
		<div class="project-thumb">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) );
			} else {
				echo '<span class="project-thumb-empty"><i class="bi bi-image"></i></span>';
			}
			?>
		</div>
		<div class="p-3 p-lg-4">
			<?php if ( $bo_types ) : ?>
				<div class="mb-2">
					<?php foreach ( $bo_types as $t ) : ?>
						<span class="badge badge-topic me-1"><?php echo esc_html( $t->name ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<h3 class="h5 mb-1"><a class="stretched-link text-reset" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<?php if ( $bo_place ) : ?>
				<div class="small text-secondary"><i class="bi bi-geo-alt me-1"></i><?php echo esc_html( $bo_place ); ?></div>
			<?php endif; ?>
		</div>
	</article>
</div>
