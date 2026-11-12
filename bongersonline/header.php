<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="visually-hidden-focusable" href="#main">Naar de inhoud</a>
<header class="site-header sticky-top">
	<nav class="navbar navbar-expand-lg py-3">
		<div class="container">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">bongers<span>online</span></a>
			<?php endif; ?>
			<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu openen">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="mainNav">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'navbar-nav ms-auto',
					'depth'          => 2,
					'walker'         => new BO_Nav_Walker(),
					'fallback_cb'    => 'bo_fallback_menu',
				) );
				?>
				<form class="d-flex ms-lg-3 mt-3 mt-lg-0" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input class="form-control form-control-sm" type="search" name="s" placeholder="Zoeken…" aria-label="Zoeken" value="<?php echo esc_attr( get_search_query() ); ?>">
				</form>
			</div>
		</div>
	</nav>
</header>
<main id="main">
