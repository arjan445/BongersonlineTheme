<?php
/**
 * Lijst met handleidingen. Verwacht $args['query'] (WP_Query) of gebruikt de hoofdloop.
 */
$q          = isset( $args['query'] ) ? $args['query'] : $GLOBALS['wp_query'];
$show_topic = isset( $args['show_topic'] ) ? $args['show_topic'] : true;
if ( ! $q->have_posts() ) {
	return;
}
?>
<div class="list-group guide-list shadow-sm rounded-4" data-guide-list>
	<?php
	while ( $q->have_posts() ) :
		$q->the_post();
		$topics = get_the_terms( get_the_ID(), 'onderwerp' );
		?>
		<a href="<?php the_permalink(); ?>" class="list-group-item list-group-item-action d-flex gap-3 align-items-start" data-search="<?php echo esc_attr( strtolower( get_the_title() . ' ' . get_the_excerpt() ) ); ?>">
			<i class="bi bi-file-earmark-text text-primary fs-4 lh-1 mt-1"></i>
			<div class="flex-grow-1">
				<div class="guide-title"><?php the_title(); ?></div>
				<?php if ( has_excerpt() ) : ?>
					<div class="guide-meta mt-1"><?php echo esc_html( get_the_excerpt() ); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( $show_topic && $topics && ! is_wp_error( $topics ) && get_post_type() === 'handleiding' ) : ?>
				<span class="badge badge-topic rounded-pill d-none d-md-inline-block align-self-center"><?php echo esc_html( $topics[0]->name ); ?></span>
			<?php endif; ?>
			<i class="bi bi-chevron-right text-secondary align-self-center"></i>
		</a>
	<?php endwhile; ?>
</div>
<?php
if ( isset( $args['query'] ) ) {
	wp_reset_postdata();
}
