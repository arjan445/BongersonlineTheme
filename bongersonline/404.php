<?php
get_header();
?>
<section class="py-5 my-5 text-center">
	<div class="container" style="max-width:640px">
		<i class="bi bi-signpost-split fs-1 text-primary"></i>
		<h1 class="fw-bold mt-3">Deze pagina bestaat niet (meer)</h1>
		<p class="text-secondary">Zoek hieronder naar wat je nodig hebt.</p>
		<form class="d-flex gap-2 mt-4" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s" class="form-control form-control-lg" placeholder="Zoek een handleiding" aria-label="Zoeken">
			<button class="btn btn-primary px-4" type="submit">Zoeken</button>
		</form>
		<a class="btn btn-link mt-3" href="<?php echo esc_url( home_url( '/' ) ); ?>">Terug naar home</a>
	</div>
</section>
<?php
get_footer();
