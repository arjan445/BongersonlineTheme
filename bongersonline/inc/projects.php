<?php
/**
 * Projecten: berichttype, type installatie, projectgegevens en fotogalerij.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'project', array(
		'labels'        => array(
			'name'                  => 'Projecten',
			'singular_name'         => 'Project',
			'add_new'               => 'Nieuw project',
			'add_new_item'          => 'Nieuw project toevoegen',
			'edit_item'             => 'Project bewerken',
			'new_item'              => 'Nieuw project',
			'view_item'             => 'Project bekijken',
			'all_items'             => 'Alle projecten',
			'search_items'          => 'Projecten zoeken',
			'not_found'             => 'Geen projecten gevonden',
			'menu_name'             => 'Projecten',
			'featured_image'        => 'Hoofdfoto',
			'set_featured_image'    => 'Hoofdfoto instellen',
			'remove_featured_image' => 'Hoofdfoto verwijderen',
			'use_featured_image'    => 'Als hoofdfoto gebruiken',
		),
		'public'        => true,
		'has_archive'   => 'projecten',
		'rewrite'       => array( 'slug' => 'project', 'with_front' => false ),
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 6,
		'show_in_rest'  => true,
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
		'template'      => array(
			array( 'core/paragraph', array( 'placeholder' => 'Korte beschrijving: wat was de vraag en wat is er gedaan?' ) ),
		),
	) );

	register_taxonomy( 'installatietype', 'project', array(
		'labels'            => array(
			'name'          => 'Type installatie',
			'singular_name' => 'Type installatie',
			'add_new_item'  => 'Nieuw type toevoegen',
			'edit_item'     => 'Type bewerken',
			'all_items'     => 'Alle types',
			'search_items'  => 'Types zoeken',
			'menu_name'     => 'Type installatie',
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'projecten/type', 'with_front' => false ),
	) );

	foreach ( array( 'bo_client', 'bo_place', 'bo_gallery' ) as $key ) {
		register_post_meta( 'project', $key, array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}
} );

/* Standaard installatietypes eenmalig aanmaken */
add_action( 'init', function () {
	if ( get_option( 'bo_installatietypes_seeded' ) ) {
		return;
	}
	foreach ( array( 'Netwerk & wifi', 'Camerabeveiliging', 'Microsoft 365', 'Websites & hosting' ) as $name ) {
		if ( ! term_exists( $name, 'installatietype' ) ) {
			wp_insert_term( $name, 'installatietype' );
		}
	}
	update_option( 'bo_installatietypes_seeded', 1 );
}, 20 );

/* ------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------- */
function bo_project_gallery_ids( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$ids     = array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, 'bo_gallery', true ) ) );
	return array_values( array_filter( $ids, 'wp_attachment_is_image' ) );
}

function bo_project_types( $post_id = null ) {
	$terms = get_the_terms( $post_id ? $post_id : get_the_ID(), 'installatietype' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms : array();
}

/* ------------------------------------------------------------------
 * Metabox: opdrachtgever, plaats, foto's
 * ---------------------------------------------------------------- */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'bo_project', 'Projectgegevens', 'bo_project_metabox', 'project', 'normal', 'high' );
} );

function bo_project_metabox( $post ) {
	wp_nonce_field( 'bo_project', 'bo_project_nonce' );
	$client = get_post_meta( $post->ID, 'bo_client', true );
	$place  = get_post_meta( $post->ID, 'bo_place', true );
	$ids    = bo_project_gallery_ids( $post->ID );
	?>
	<p>
		<label for="bo_client"><strong>Opdrachtgever</strong></label><br>
		<input type="text" id="bo_client" name="bo_client" value="<?php echo esc_attr( $client ); ?>" class="widefat" placeholder="Bijv. Bakkerij Jansen (leeg laten om niet te tonen)">
	</p>
	<p>
		<label for="bo_place"><strong>Plaats</strong></label><br>
		<input type="text" id="bo_place" name="bo_place" value="<?php echo esc_attr( $place ); ?>" class="widefat" placeholder="Bijv. Beesel">
	</p>
	<p style="margin-bottom:6px"><strong>Foto's</strong><br>
		<span class="description">De hoofdfoto stel je rechts in onder "Hoofdfoto". Hier kies je de extra foto's. Sleep om de volgorde te wijzigen.</span>
	</p>
	<ul id="bo-gallery-list" style="display:flex;flex-wrap:wrap;gap:8px;margin:0 0 10px">
		<?php foreach ( $ids as $id ) : ?>
			<li data-id="<?php echo (int) $id; ?>" style="margin:0;cursor:move"><?php echo wp_get_attachment_image( $id, array( 90, 90 ), false, array( 'style' => 'width:90px;height:90px;object-fit:cover;display:block' ) ); ?></li>
		<?php endforeach; ?>
	</ul>
	<input type="hidden" id="bo_gallery" name="bo_gallery" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>">
	<button type="button" class="button" id="bo-gallery-pick">Foto's kiezen</button>
	<button type="button" class="button-link button-link-delete" id="bo-gallery-clear" style="margin-left:10px">Alle foto's weghalen</button>
	<?php
}

add_action( 'save_post_project', function ( $post_id ) {
	if ( ! isset( $_POST['bo_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bo_project_nonce'] ) ), 'bo_project' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'bo_client', sanitize_text_field( wp_unslash( isset( $_POST['bo_client'] ) ? $_POST['bo_client'] : '' ) ) );
	update_post_meta( $post_id, 'bo_place', sanitize_text_field( wp_unslash( isset( $_POST['bo_place'] ) ? $_POST['bo_place'] : '' ) ) );
	$raw = sanitize_text_field( wp_unslash( isset( $_POST['bo_gallery'] ) ? $_POST['bo_gallery'] : '' ) );
	$ids = array_filter( array_map( 'absint', explode( ',', $raw ) ) );
	update_post_meta( $post_id, 'bo_gallery', implode( ',', $ids ) );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'project' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_add_inline_script( 'jquery-ui-sortable', <<<'JS'
jQuery(function ($) {
	var $list = $('#bo-gallery-list'), $input = $('#bo_gallery'), frame;
	function sync() {
		$input.val($list.children().map(function () { return $(this).attr('data-id'); }).get().join(','));
	}
	$list.sortable({ update: sync });
	$('#bo-gallery-pick').on('click', function (e) {
		e.preventDefault();
		if (!frame) {
			frame = wp.media({ title: "Foto's kiezen", button: { text: 'Gebruiken' }, library: { type: 'image' }, multiple: 'add' });
			frame.on('open', function () {
				var sel = frame.state().get('selection');
				sel.reset();
				($input.val() || '').split(',').forEach(function (id) {
					if (id) { var a = wp.media.attachment(id); a.fetch(); sel.add(a); }
				});
			});
			frame.on('select', function () {
				$list.empty();
				frame.state().get('selection').each(function (a) {
					a = a.toJSON();
					var url = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
					$list.append($('<li style="margin:0;cursor:move"></li>').attr('data-id', a.id)
						.append($('<img style="width:90px;height:90px;object-fit:cover;display:block" alt="">').attr('src', url)));
				});
				sync();
			});
		}
		frame.open();
	});
	$('#bo-gallery-clear').on('click', function (e) { e.preventDefault(); $list.empty(); sync(); });
});
JS
	);
} );

/* Kolom "Plaats" in het projectenoverzicht in wp-admin */
add_filter( 'manage_project_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['bo_place']  = 'Plaats';
			$new['bo_client'] = 'Opdrachtgever';
		}
	}
	return $new;
} );
add_action( 'manage_project_posts_custom_column', function ( $col, $post_id ) {
	if ( in_array( $col, array( 'bo_place', 'bo_client' ), true ) ) {
		echo esc_html( get_post_meta( $post_id, $col, true ) );
	}
}, 10, 2 );

/* Aantal projecten per pagina in het overzicht */
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && ( $q->is_post_type_archive( 'project' ) || $q->is_tax( 'installatietype' ) ) ) {
		$q->set( 'posts_per_page', 12 );
	}
} );
