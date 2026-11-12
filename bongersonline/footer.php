</main>
<footer class="site-footer pt-5 pb-4 mt-5">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-5">
				<?php if ( has_custom_logo() ) : ?>
					<a class="d-inline-block mb-3" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'medium_large', false, array( 'class' => 'footer-logo-img', 'alt' => get_bloginfo( 'name' ) ) ); ?></a>
				<?php else : ?>
					<div class="footer-brand mb-2">bongers<span>online</span></div>
				<?php endif; ?>
				<p class="mb-0"><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : 'Netwerk, camerabeveiliging en Microsoft 365 voor bedrijven en particulieren.' ); ?></p>
			</div>
			<div class="col-6 col-lg-3">
				<h6 class="mb-3">Helpdesk</h6>
				<ul>
					<?php foreach ( array_slice( bo_get_topics(), 0, 6 ) as $t ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
					<?php endforeach; ?>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'handleiding' ) ); ?>">Alle handleidingen</a></li>
				</ul>
			</div>
			<div class="col-6 col-lg-4">
				<h6 class="mb-3">Contact</h6>
				<ul>
					<?php $bo_cp = get_page_by_path( 'contact' ); ?>
					<?php if ( $bo_cp && 'publish' === $bo_cp->post_status ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $bo_cp ) ); ?>"><i class="bi bi-chat-text me-2"></i>Contactformulier</a></li>
					<?php endif; ?>
					<?php foreach ( bo_contact_links() as $link ) : ?>
						<li><?php echo $link; // phpcs:ignore ?></li>
					<?php endforeach; ?>
					<?php if ( bo_address_html() ) : ?>
						<li class="d-flex"><i class="bi bi-geo-alt me-2"></i><span><?php echo bo_address_html(); // phpcs:ignore ?></span></li>
					<?php endif; ?>
				</ul>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => false,
				) );
				?>
			</div>
		</div>
		<hr class="border-secondary my-4">
		<div class="small d-flex flex-wrap justify-content-between gap-2">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Bongersonline<?php foreach ( bo_company_ids() as $bo_id ) { echo ' &middot; ' . $bo_id; } // phpcs:ignore ?></span>
			<?php if ( function_exists( 'get_privacy_policy_url' ) && get_privacy_policy_url() ) : ?>
				<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacyverklaring</a>
			<?php endif; ?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
