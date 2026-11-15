<?php
/**
 * Bongersonline thema functies.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BO_VERSION', '1.6.3' );

require_once get_template_directory() . '/inc/projects.php';
require_once get_template_directory() . '/inc/clients.php';

/* ------------------------------------------------------------------
 * Thema-instellingen
 * ---------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'bongersonline' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 84, 'width' => 300, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', 'editor-style.css' ) );

	register_nav_menus( array(
		'primary' => 'Hoofdmenu',
		'footer'  => 'Footermenu',
	) );
} );

/* ------------------------------------------------------------------
 * Scripts & styles
 * ---------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), '5.3.3' );
	wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3' );
	wp_enqueue_style( 'bongersonline', get_stylesheet_uri(), array( 'bootstrap' ), BO_VERSION );
	wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), '5.3.3', true );
	wp_enqueue_script( 'bongersonline', get_template_directory_uri() . '/assets/site.js', array(), BO_VERSION, true );
} );

/* ------------------------------------------------------------------
 * Berichttype "Handleiding" + taxonomie "Onderwerp"
 * ---------------------------------------------------------------- */
add_action( 'init', function () {
	register_post_type( 'handleiding', array(
		'labels' => array(
			'name'               => 'Handleidingen',
			'singular_name'      => 'Handleiding',
			'add_new'            => 'Nieuwe handleiding',
			'add_new_item'       => 'Nieuwe handleiding toevoegen',
			'edit_item'          => 'Handleiding bewerken',
			'new_item'           => 'Nieuwe handleiding',
			'view_item'          => 'Handleiding bekijken',
			'search_items'       => 'Handleidingen zoeken',
			'not_found'          => 'Geen handleidingen gevonden',
			'all_items'          => 'Alle handleidingen',
			'menu_name'          => 'Handleidingen',
		),
		'public'        => true,
		'has_archive'   => 'handleidingen',
		'rewrite'       => array( 'slug' => 'handleiding', 'with_front' => false ),
		'menu_icon'     => 'dashicons-book-alt',
		'menu_position' => 5,
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
		'template'      => array(
			array( 'core/paragraph', array( 'placeholder' => 'Korte inleiding: wat gaat de klant in deze handleiding doen?' ) ),
			array( 'core/group', array( 'className' => 'is-style-info' ), array(
				array( 'core/paragraph', array( 'content' => '<strong>Wat heb je nodig?</strong> Je e-mailadres en wachtwoord.' ) ),
			) ),
			array( 'core/heading', array( 'content' => 'Stappen' ) ),
			array( 'core/list', array( 'ordered' => true, 'className' => 'is-style-stappen' ), array(
				array( 'core/list-item', array( 'placeholder' => 'Eerste stap…' ) ),
				array( 'core/list-item', array( 'placeholder' => 'Tweede stap…' ) ),
				array( 'core/list-item', array( 'placeholder' => 'Derde stap…' ) ),
			) ),
			array( 'core/group', array( 'className' => 'is-style-tip' ), array(
				array( 'core/paragraph', array( 'placeholder' => 'Handige tip (of verwijder dit blok).' ) ),
			) ),
			array( 'core/heading', array( 'content' => 'Werkt het niet?' ) ),
			array( 'core/list', array(), array(
				array( 'core/list-item', array( 'placeholder' => 'Veelvoorkomend probleem en oplossing…' ) ),
			) ),
		),
	) );

	register_taxonomy( 'onderwerp', 'handleiding', array(
		'labels' => array(
			'name'          => 'Onderwerpen',
			'singular_name' => 'Onderwerp',
			'add_new_item'  => 'Nieuw onderwerp toevoegen',
			'edit_item'     => 'Onderwerp bewerken',
			'search_items'  => 'Onderwerpen zoeken',
			'all_items'     => 'Alle onderwerpen',
			'menu_name'     => 'Onderwerpen',
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'handleidingen/onderwerp', 'with_front' => false ),
	) );

	register_term_meta( 'onderwerp', 'bo_icon', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_key' ) );
	register_term_meta( 'onderwerp', 'bo_order', array( 'type' => 'integer', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'absint' ) );
} );

add_action( 'after_switch_theme', function () {
	flush_rewrite_rules();
} );

/* Rewrite-regels verversen na een thema-update */
add_action( 'init', function () {
	if ( get_option( 'bo_rewrite_version' ) !== BO_VERSION ) {
		flush_rewrite_rules();
		update_option( 'bo_rewrite_version', BO_VERSION );
	}
}, 99 );

/* Icoon + volgorde velden bij onderwerpen */
function bo_term_fields( $term = null ) {
	$icon  = $term ? get_term_meta( $term->term_id, 'bo_icon', true ) : '';
	$order = $term ? get_term_meta( $term->term_id, 'bo_order', true ) : '';
	$is_edit = (bool) $term;
	$open  = $is_edit ? '<tr class="form-field"><th scope="row"><label for="%1$s">%2$s</label></th><td>' : '<div class="form-field"><label for="%1$s">%2$s</label>';
	$close = $is_edit ? '</td></tr>' : '</div>';
	wp_nonce_field( 'bo_term_meta', 'bo_term_meta_nonce' );
	printf( $open, 'bo_icon', 'Icoon' );
	printf( '<input type="text" name="bo_icon" id="bo_icon" value="%s" placeholder="bijv. envelope, wifi, camera-video">', esc_attr( $icon ) );
	echo '<p class="description">Naam van een <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">Bootstrap icoon</a>, zonder "bi-".</p>' . $close;
	printf( $open, 'bo_order', 'Volgorde' );
	printf( '<input type="number" name="bo_order" id="bo_order" value="%s" min="0" style="width:100px">', esc_attr( $order ) );
	echo '<p class="description">Lager getal = eerder getoond op de homepage.</p>' . $close;
}
add_action( 'onderwerp_add_form_fields', function () { bo_term_fields(); } );
add_action( 'onderwerp_edit_form_fields', function ( $term ) { bo_term_fields( $term ); } );
function bo_save_term_fields( $term_id ) {
	if ( ! isset( $_POST['bo_term_meta_nonce'] ) || ! wp_verify_nonce( $_POST['bo_term_meta_nonce'], 'bo_term_meta' ) ) {
		return;
	}
	if ( isset( $_POST['bo_icon'] ) ) {
		update_term_meta( $term_id, 'bo_icon', sanitize_key( wp_unslash( $_POST['bo_icon'] ) ) );
	}
	if ( isset( $_POST['bo_order'] ) ) {
		update_term_meta( $term_id, 'bo_order', absint( $_POST['bo_order'] ) );
	}
}
add_action( 'created_onderwerp', 'bo_save_term_fields' );
add_action( 'edited_onderwerp', 'bo_save_term_fields' );

function bo_topic_icon( $term ) {
	$icon = get_term_meta( $term->term_id, 'bo_icon', true );
	return $icon ? $icon : 'journal-text';
}

function bo_get_topics( $args = array() ) {
	$terms = get_terms( array_merge( array( 'taxonomy' => 'onderwerp', 'hide_empty' => false, 'parent' => 0 ), $args ) );
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	usort( $terms, function ( $a, $b ) {
		$oa = (int) get_term_meta( $a->term_id, 'bo_order', true );
		$ob = (int) get_term_meta( $b->term_id, 'bo_order', true );
		return $oa === $ob ? strcasecmp( $a->name, $b->name ) : $oa - $ob;
	} );
	return $terms;
}

/* ------------------------------------------------------------------
 * Blokstijlen voor de editor
 * ---------------------------------------------------------------- */
add_action( 'init', function () {
	register_block_style( 'core/list', array( 'name' => 'stappen', 'label' => 'Stappen (genummerd)' ) );
	foreach ( array( 'core/group', 'core/paragraph' ) as $block ) {
		register_block_style( $block, array( 'name' => 'tip', 'label' => 'Tip (groen)' ) );
		register_block_style( $block, array( 'name' => 'let-op', 'label' => 'Let op (oranje)' ) );
		register_block_style( $block, array( 'name' => 'info', 'label' => 'Info (blauw)' ) );
	}
} );

/* ------------------------------------------------------------------
 * Inhoudsopgave: ids toevoegen aan h2/h3 in handleidingen
 * ---------------------------------------------------------------- */
function bo_heading_ids( $content ) {
	if ( ! is_singular( 'handleiding' ) || ! in_the_loop() ) {
		return $content;
	}
	$used = array();
	return preg_replace_callback( '/<h([23])([^>]*)>(.*?)<\/h\1>/is', function ( $m ) use ( &$used ) {
		if ( preg_match( '/\sid=["\']/', $m[2] ) ) {
			return $m[0];
		}
		$slug = sanitize_title( wp_strip_all_tags( $m[3] ) );
		if ( '' === $slug ) {
			$slug = 'sectie';
		}
		$base = $slug;
		$i    = 2;
		while ( isset( $used[ $slug ] ) ) {
			$slug = $base . '-' . $i++;
		}
		$used[ $slug ] = true;
		return sprintf( '<h%1$s id="%2$s"%3$s>%4$s</h%1$s>', $m[1], esc_attr( $slug ), $m[2], $m[3] );
	}, $content );
}
add_filter( 'the_content', 'bo_heading_ids', 20 );

function bo_toc( $content ) {
	preg_match_all( '/<h2[^>]*id="([^"]+)"[^>]*>(.*?)<\/h2>/is', $content, $m, PREG_SET_ORDER );
	return $m;
}

function bo_reading_time( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/* ------------------------------------------------------------------
 * Zoeken: alleen handleidingen en pagina's
 * ---------------------------------------------------------------- */
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_search() ) {
		$q->set( 'post_type', array( 'handleiding', 'project', 'page' ) );
	}
	if ( $q->is_post_type_archive( 'handleiding' ) || $q->is_tax( 'onderwerp' ) ) {
		$q->set( 'posts_per_page', 100 );
		$q->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}
} );

/* ------------------------------------------------------------------
 * Customizer: contactgegevens
 * ---------------------------------------------------------------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'bo_contact', array( 'title' => 'Contactgegevens', 'priority' => 30 ) );
	$fields = array(
		'bo_email'    => array( 'E-mailadres', 'sanitize_email' ),
		'bo_phone'    => array( 'Telefoonnummer', 'sanitize_text_field' ),
		'bo_whatsapp' => array( 'WhatsApp-nummer (internationaal, bijv. 31612345678)', 'sanitize_text_field' ),
		'bo_hero'     => array( 'Tekst onder de kop op de homepage', 'sanitize_text_field' ),
	);
	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => $f[1] ) );
		$wp_customize->add_control( $id, array( 'label' => $f[0], 'section' => 'bo_contact', 'type' => 'text' ) );
	}
} );

function bo_contact_links( $class = '' ) {
	$out   = array();
	$email = get_theme_mod( 'bo_email' );
	$phone = get_theme_mod( 'bo_phone' );
	$wa    = preg_replace( '/\D/', '', (string) get_theme_mod( 'bo_whatsapp' ) );
	if ( $email ) {
		$out[] = sprintf( '<a class="%s" href="mailto:%s"><i class="bi bi-envelope me-2"></i>%s</a>', esc_attr( $class ), esc_attr( antispambot( $email ) ), esc_html( antispambot( $email ) ) );
	}
	if ( $phone ) {
		$out[] = sprintf( '<a class="%s" href="tel:%s"><i class="bi bi-telephone me-2"></i>%s</a>', esc_attr( $class ), esc_attr( preg_replace( '/[^\d+]/', '', $phone ) ), esc_html( $phone ) );
	}
	if ( $wa ) {
		$out[] = sprintf( '<a class="%s" href="https://wa.me/%s" target="_blank" rel="noopener"><i class="bi bi-whatsapp me-2"></i>WhatsApp</a>', esc_attr( $class ), esc_attr( $wa ) );
	}
	return $out;
}

/* ------------------------------------------------------------------
 * Bootstrap navigatie (eenvoudige walker)
 * ---------------------------------------------------------------- */
class BO_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="dropdown-menu">';
	}
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$active       = array_intersect( array( 'current-menu-item', 'current-menu-ancestor', 'current_page_item', 'current_page_parent' ), $classes );
		if ( 0 === $depth ) {
			$output .= '<li class="nav-item' . ( $has_children ? ' dropdown' : '' ) . ' ' . esc_attr( implode( ' ', $classes ) ) . '">';
			$attrs   = 'class="nav-link' . ( $has_children ? ' dropdown-toggle' : '' ) . ( $active ? ' active' : '' ) . '"';
			if ( $has_children ) {
				$attrs .= ' data-bs-toggle="dropdown" role="button" aria-expanded="false"';
			}
		} else {
			$output .= '<li>';
			$attrs   = 'class="dropdown-item' . ( $active ? ' active' : '' ) . '"';
		}
		if ( $active ) {
			$attrs .= ' aria-current="page"';
		}
		$target = $item->target ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$output .= '<a ' . $attrs . ' href="' . esc_url( $item->url ) . '"' . $target . '>' . esc_html( $item->title ) . '</a>';
	}
}

function bo_fallback_menu() {
	echo '<ul class="navbar-nav ms-auto">';
	echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
	echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( get_post_type_archive_link( 'project' ) ) . '">Projecten</a></li>';
	echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( get_post_type_archive_link( 'handleiding' ) ) . '">Helpdesk</a></li>';
	$contact = get_page_by_path( 'contact' );
	if ( $contact && 'publish' === $contact->post_status ) {
		echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( get_permalink( $contact ) ) . '">Contact</a></li>';
	}
	echo '</ul>';
}

/* ------------------------------------------------------------------
 * Homepage slider (Customizer > Homepage slider)
 * ---------------------------------------------------------------- */
define( 'BO_SLIDES', 5 );
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'bo_slider', array(
		'title'       => 'Homepage slider',
		'priority'    => 25,
		'description' => 'Upload per slide een foto van 1920 × 600 pixels. Slides zonder foto worden overgeslagen. Zonder slides toont de homepage de blauwe kop.',
	) );
	$wp_customize->add_setting( 'bo_slider_interval', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'bo_slider_interval', array( 'label' => 'Seconden per slide', 'section' => 'bo_slider', 'type' => 'number', 'input_attrs' => array( 'min' => 2, 'max' => 30 ) ) );
	for ( $i = 1; $i <= BO_SLIDES; $i++ ) {
		$wp_customize->add_setting( "bo_slide_{$i}_image", array( 'default' => '', 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, "bo_slide_{$i}_image", array(
			'label'       => "Slide {$i}: foto",
			'description' => '1920 × 600 pixels',
			'section'     => 'bo_slider',
			'width'       => 1920,
			'height'      => 600,
			'flex_width'  => false,
			'flex_height' => false,
		) ) );
		$fields = array(
			'title' => array( "Slide {$i}: titel", 'text', 'sanitize_text_field' ),
			'text'  => array( "Slide {$i}: tekst", 'textarea', 'sanitize_textarea_field' ),
			'btn'   => array( "Slide {$i}: knoptekst", 'text', 'sanitize_text_field' ),
			'url'   => array( "Slide {$i}: knoplink", 'url', 'esc_url_raw' ),
		);
		foreach ( $fields as $key => $f ) {
			$wp_customize->add_setting( "bo_slide_{$i}_{$key}", array( 'default' => '', 'sanitize_callback' => $f[2] ) );
			$wp_customize->add_control( "bo_slide_{$i}_{$key}", array( 'label' => $f[0], 'section' => 'bo_slider', 'type' => $f[1] ) );
		}
	}
} );

function bo_get_slides() {
	$slides = array();
	for ( $i = 1; $i <= BO_SLIDES; $i++ ) {
		$id = absint( get_theme_mod( "bo_slide_{$i}_image" ) );
		if ( ! $id || ! wp_attachment_is_image( $id ) ) {
			continue;
		}
		$slides[] = array(
			'id'    => $id,
			'title' => get_theme_mod( "bo_slide_{$i}_title" ),
			'text'  => get_theme_mod( "bo_slide_{$i}_text" ),
			'btn'   => get_theme_mod( "bo_slide_{$i}_btn" ),
			'url'   => get_theme_mod( "bo_slide_{$i}_url" ),
		);
	}
	return $slides;
}

function bo_contact_url() {
	$p = get_page_by_path( 'contact' );
	if ( $p && 'publish' === $p->post_status ) {
		return get_permalink( $p );
	}
	$e = get_theme_mod( 'bo_email' );
	return $e ? 'mailto:' . antispambot( $e ) : '';
}
add_action( 'init', function () {
	register_block_style( 'core/list', array( 'name' => 'vinkjes', 'label' => 'Vinkjes' ) );
	register_block_style( 'core/paragraph', array( 'name' => 'intro', 'label' => 'Intro (groter)' ) );
} );

/* ------------------------------------------------------------------
 * Bedrijfsgegevens (Customizer > Contactgegevens)
 * ---------------------------------------------------------------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$fields = array(
		'bo_address' => array( 'Adres (meerdere regels)', 'textarea', 'sanitize_textarea_field' ),
		'bo_kvk'     => array( 'KVK-nummer', 'text', 'sanitize_text_field' ),
		'bo_btw'     => array( 'Btw-nummer', 'text', 'sanitize_text_field' ),
	);
	foreach ( $fields as $id => $f ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => $f[2] ) );
		$wp_customize->add_control( $id, array( 'label' => $f[0], 'section' => 'bo_contact', 'type' => $f[1] ) );
	}
}, 20 );

function bo_address_html() {
	$a = trim( (string) get_theme_mod( 'bo_address' ) );
	if ( '' === $a ) {
		return '';
	}
	$q = rawurlencode( preg_replace( '/\s+/', ' ', $a ) );
	return sprintf( '<a href="https://www.google.com/maps/search/?api=1&amp;query=%s" target="_blank" rel="noopener">%s</a>', $q, nl2br( esc_html( $a ) ) );
}

function bo_company_ids() {
	$out = array();
	if ( get_theme_mod( 'bo_kvk' ) ) {
		$out[] = 'KVK ' . esc_html( get_theme_mod( 'bo_kvk' ) );
	}
	if ( get_theme_mod( 'bo_btw' ) ) {
		$out[] = 'Btw ' . esc_html( get_theme_mod( 'bo_btw' ) );
	}
	return $out;
}

add_shortcode( 'bo_contactgegevens', function () {
	$out = '<ul class="bo-contactgegevens list-unstyled">';
	foreach ( bo_contact_links() as $l ) {
		$out .= '<li>' . $l . '</li>';
	}
	if ( bo_address_html() ) {
		$out .= '<li class="d-flex"><i class="bi bi-geo-alt me-2"></i><span>' . bo_address_html() . '</span></li>';
	}
	foreach ( bo_company_ids() as $id ) {
		$out .= '<li class="text-secondary small">' . $id . '</li>';
	}
	return $out . '</ul>';
} );

/* Excerpt */
add_filter( 'excerpt_length', function () { return 24; } );
add_filter( 'excerpt_more', function () { return '…'; } );
