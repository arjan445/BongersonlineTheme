<?php
$contact = bo_contact_url();
$links   = bo_contact_links( 'btn btn-outline-light me-2 mb-2' );
?>
<div class="help-cta p-4 p-md-5 no-print">
	<div class="row align-items-center g-3">
		<div class="col-md-7">
			<h2 class="h4 fw-bold mb-2">Kom je er niet uit?</h2>
			<p class="mb-0">Neem contact op, dan kijken we even mee.</p>
		</div>
		<div class="col-md-5 text-md-end">
			<?php if ( $contact && 0 !== strpos( $contact, 'mailto:' ) ) : ?>
				<a class="btn btn-light me-2 mb-2" href="<?php echo esc_url( $contact ); ?>">Contactformulier</a>
			<?php endif; ?>
			<?php echo implode( '', $links ); // phpcs:ignore ?>
		</div>
	</div>
</div>
