<?php
/**
 * Opdrachtgevers (referenties): naam, logo en website, getoond als logoband.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'opdrachtgever', array(
		'labels'             => array(
			'name'                  => 'Opdrachtgevers',
			'singular_name'         => 'Opdrachtgever',
			'add_new'               => 'Nieuwe opdrachtgever',
			'add_new_item'          => 'Nieuwe opdrachtgever toevoegen',
			'edit_item'             => 'Opdrachtgever bewerken',
			'all_items'             => 'Alle opdrachtgevers',
			'search_items'          => 'Opdrachtgevers zoeken',
			'not_found'             => 'Geen opdrachtgevers gevonden',
			'menu_name'             => 'Opdrachtgevers',
			'featured_image'        => 'Logo',
			'set_featured_image'    => 'Logo instellen',
			'remove_featured_image' => 'Logo verwijderen',
			'use_featured_image'    => 'Als logo gebruiken',
		),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-groups',
		'menu_position'      => 7,
		'supports'           => array( 'title', 'thumbnail', 'page-attributes', 'custom-fields' ),
	) );

	register_post_meta( 'opdrachtgever', 'bo_url', array(
		'type'              => 'string',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	) );
} );

/* Metabox: website */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'bo_client_url', 'Website', function ( $post ) {
		wp_nonce_field( 'bo_client_url', 'bo_client_url_nonce' );
		printf(
			'<input type="url" name="bo_url" value="%s" class="widefat" placeholder="https://www.voorbeeld.nl"><p class="description">Optioneel. Als je dit invult, linkt het logo naar deze website.</p><p class="description">Volgorde: lager getal = eerder in de band (rechts onder "Pagina-attributen").</p>',
			esc_attr( get_post_meta( $post->ID, 'bo_url', true ) )
		);
	}, 'opdrachtgever', 'normal', 'high' );
} );

add_action( 'save_post_opdrachtgever', function ( $post_id ) {
	if ( ! isset( $_POST['bo_client_url_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bo_client_url_nonce'] ) ), 'bo_client_url' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'bo_url', esc_url_raw( wp_unslash( isset( $_POST['bo_url'] ) ? $_POST['bo_url'] : '' ) ) );
} );

/* Logo-kolom in het overzicht in wp-admin */
add_filter( 'manage_opdrachtgever_posts_columns', function ( $cols ) {
	return array_merge( array( 'cb' => $cols['cb'], 'bo_logo' => 'Logo' ), $cols );
} );
add_action( 'manage_opdrachtgever_posts_custom_column', function ( $col, $post_id ) {
	if ( 'bo_logo' === $col ) {
		echo get_the_post_thumbnail( $post_id, array( 120, 60 ), array( 'style' => 'max-width:120px;max-height:60px;width:auto;height:auto' ) );
	}
}, 10, 2 );

/**
 * HTML van de logoband. Leeg als er geen opdrachtgevers met logo zijn.
 *
 * @param string $title Kop boven de band (leeg = geen kop).
 */
function bo_clients_html( $title = 'Opdrachtgevers' ) {
	$q = new WP_Query( array(
		'post_type'      => 'opdrachtgever',
		'posts_per_page' => 50,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
		'meta_query'     => array( array( 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ) ),
	) );
	if ( ! $q->have_posts() ) {
		return '';
	}
	$items = array();
	foreach ( $q->posts as $p ) {
		$img = get_the_post_thumbnail( $p->ID, 'medium', array( 'alt' => get_the_title( $p ), 'loading' => 'lazy' ) );
		$url = get_post_meta( $p->ID, 'bo_url', true );
		$items[] = $url
			? sprintf( '<a class="client-logo" href="%s" target="_blank" rel="noopener" title="%s">%s</a>', esc_url( $url ), esc_attr( get_the_title( $p ) ), $img )
			: sprintf( '<span class="client-logo" title="%s">%s</span>', esc_attr( get_the_title( $p ) ), $img );
	}
	$count = count( $items );
	$out   = '<section class="clients py-5"><div class="container">';
	if ( $title ) {
		$out .= '<h2 class="section-title h4 text-center mb-4">' . esc_html( $title ) . '</h2>';
	}
	if ( $count < 5 ) {
		$out .= '<div class="clients-static">' . implode( '', $items ) . '</div>';
	} else {
		$dup = array_map( function ( $html ) {
			return str_replace( 'class="client-logo"', 'class="client-logo is-dup" aria-hidden="true" tabindex="-1"', $html );
		}, $items );
		$out .= sprintf(
			'<div class="clients-marquee" style="--bo-dur:%ds"><div class="clients-track">%s%s</div></div>',
			max( 20, $count * 4 ),
			implode( '', $items ),
			implode( '', $dup )
		);
	}
	return $out . '</div></section>';
}

add_shortcode( 'bo_opdrachtgevers', function ( $atts ) {
	$atts = shortcode_atts( array( 'titel' => 'Opdrachtgevers' ), $atts );
	return bo_clients_html( $atts['titel'] );
} );
