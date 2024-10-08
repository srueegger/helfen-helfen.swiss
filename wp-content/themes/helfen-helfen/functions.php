<?php
/***************************************
 *	 CREATE GLOBAL VARIABLES
 ***************************************/
define( 'HOME_URI', home_url() );
define( 'THEME_URI', get_template_directory_uri() );
define( 'THEME_IMAGES', THEME_URI . '/dist-assets/images' );
define( 'DEV_CSS', THEME_URI . '/dev-assets/css' );
define( 'DEV_JS', THEME_URI . '/dev-assets/js' );
define( 'DIST_CSS', THEME_URI . '/dist-assets/css' );
define( 'DIST_JS', THEME_URI . '/dist-assets/js' );
define( 'FILES_DIR', THEME_URI . '/dist-assets/files' );


/***************************************
 * Include helpers
 ***************************************/
require_once 'inc/custom-gutenberg-blocks.php';
require_once 'inc/gravityforms.php';

/***************************************
 * 		Theme Support and Options
 ***************************************/
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'script', 'style' ) );
add_theme_support( 'title-tag' );
add_theme_support( 'menus' );
add_theme_support( 'responsive-embeds' );
add_filter('show_admin_bar', '__return_false');

/***************************************
 * Custom Image Size
 ***************************************/
add_image_size( 'imgsize-1920', 1920, 9999, false );
add_image_size( 'kopf', 635, 9999, false );
add_image_size( 'kopf2x', 1270, 9999, false );
add_image_size( 'presse', 768, 9999, false );
add_image_size( 'presse2x', 1536, 9999, false );

/***************************************
 * Add Wordpress Menus
 ***************************************/
function register_hh_menu() {
	register_nav_menu( 'main-menu', 'Hauptmenü' );
	register_nav_menu( 'sub-menu', 'Untermenü' );
	register_nav_menu( 'socialmedia-menu', 'Social Media Menü' );
	register_nav_menu( 'footer-menu', 'Footer Menü' );
}
add_action( 'after_setup_theme', 'register_hh_menu' );

/***************************************
 * 		Enqueue scripts and styles.
 ***************************************/
function hh_startup_scripts() {
	wp_enqueue_style( 'hh-fonts', 'https://fonts.googleapis.com/css?family=Ubuntu:400,500,700&display=swap', null, false );
	wp_enqueue_script( 'hh-counterup', DIST_JS . '/jquery.rcounterup.min.js', array('jquery'), '1.0', false );
	/* Raisenow Widget JS Code */
	wp_enqueue_script( 'hh-raisenow', 'https://widget.raisenow.com/widgets/lema/helfe-077a/js/dds-init-widget-de.js', array(), false, true );
	/* Swiper Script */
	wp_enqueue_script( 'swiper', DIST_JS . '/swiper.min.js', array( 'jquery' ), '9.1.0', true );
	if (WP_DEBUG) {
		$modificated_css = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/dev-assets/css/theme.css' ) );
		$modificated_js = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/dev-assets/js/theme.js' ) );
		wp_register_style( 'hh-style', DEV_CSS . '/theme.css', array('hh-fonts'), $modificated_css );
		wp_register_script( 'hh-script', DEV_JS . '/theme.js', array('jquery', 'hh-counterup', 'hh-raisenow', 'swiper'), $modificated_js, true );
	} else {
		$modificated_css = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/dist-assets/css/theme.min.css' ) );
		$modificated_js = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/dist-assets/js/theme.min.js' ) );
		wp_register_style( 'hh-style', DIST_CSS . '/theme.min.css', array('hh-fonts'), $modificated_css );
		wp_register_script( 'hh-script', DIST_JS . '/theme.min.js', array('jquery', 'hh-counterup', 'hh-raisenow', 'swiper'), $modificated_js, true );
	}
	$global_vars = array(
		'home_url' => HOME_URI,
		'ajax_url' => admin_url('admin-ajax.php'),
		'ajax_secure' => wp_create_nonce('jtidb-check-ajax-secure'),
		'txt' => array(
			'arrow_bottom' => '<div class="fullpageArrowBottom"><i class="fas fa-angle-down fa-3x"></i></div>',
			'gf_change_upload' => 'Ziehe Dateien hier hin oder',
		),
		'slider_options' => array(
			'infinite' => false,
			'slidesToShow' => 2,
			'slidesToScroll' => 1,
			'arrows' => true,
			'dots' => false,
			'prevArrow' => '<button type="button" class="slick-prev"><i class="fa fa-arrow-left"></i></button>',
			'nextArrow' => '<button type="button" class="slick-next"><i class="fa fa-arrow-right"></i></button>',
			'responsive' => array(
				array(
					'breakpoint' => 1081,
					'settings' => array(
						'slidesToShow' => 1,
						'arrows' => false,
						'dots' => true
					)
				)
			)
		)
	);
	wp_localize_script( 'hh-script', 'global_vars', $global_vars );
	wp_enqueue_style( 'hh-style' );
	wp_enqueue_script( 'hh-script' );
}
add_action( "wp_enqueue_scripts", "hh_startup_scripts" );

/***************************************
 * 	CSS und JS Dateien für den Administrationsbereich hinzufügen
 ***************************************/
function hh_admin_style_and_script() {
	$modificated_css = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/dist-assets/css/admin.css' ) );
	wp_enqueue_style('hh-admin-style', DIST_CSS . '/admin.css', null, $modificated_css);
}
 add_action('admin_enqueue_scripts', 'hh_admin_style_and_script');

/***************************************
 * 		jtidb ACF Init
 ***************************************/
function hh_acf_init() {
	/* SMTP Einstellungen */
	 $args = array(
		'page_title' => 'SMTP Einstellungen',
		'menu_title' => 'SMTP',
		'menu_slug' => 'hh-smtp-settings',
		'parent_slug' => 'options-general.php',
	);
	acf_add_options_sub_page($args);
	/* Footer Einstellungen */
	$args = array(
		'page_title' => 'Menü Footer Inhalte',
		'menu_title' => 'Footer',
		'menu_slug' => 'hh-footer-settings',
		'parent_slug' => 'themes.php',
	);
	acf_add_options_sub_page($args);
	/* Error 404 Seite Einstellungen */
	$args = array(
		'page_title' => 'Error 404: Einstellungen',
		'menu_title' => '404',
		'menu_slug' => 'hh-404-settings',
		'parent_slug' => 'options-general.php',
	);
	acf_add_options_sub_page($args);
	/* Mitstreiter Einstellungen */
	$args = array(
		'page_title' => 'Einstellungen zu den Mitstreitern',
		'menu_title' => 'Einstellungen',
		'menu_slug' => 'hh-mitstreiter-settings',
		'parent_slug' => 'edit.php?post_type=hh_mitstreiter',
	);
	acf_add_options_sub_page($args);
	/* Infofenster Einstellungen */
	$args = array(
		'page_title' => 'Einstellungen für die Infofenster',
		'menu_title' => 'Infofenster',
		'menu_slug' => 'hh-modalwindow-settings',
		'parent_slug' => 'themes.php',
	);
	acf_add_options_sub_page($args);
	/* Jobs Einstellungen */
	$args = array(
		'page_title' => 'Einstellungen für Jobs',
		'menu_title' => 'Einstellungen',
		'menu_slug' => 'hh-jobs-settings',
		'parent_slug' => 'edit.php?post_type=hh_jobs',
	);
	acf_add_options_sub_page($args);
	/* Cookie Banner Einstellungen */
	$args = array(
		'page_title' => 'Einstellungen für den Cookie Banner',
		'menu_title' => 'Cookie Banner',
		'menu_slug' => 'hh-cookiebanner-settings',
		'parent_slug' => 'themes.php',
	);
	acf_add_options_sub_page($args);
}
add_action( 'acf/init', 'hh_acf_init' );

/***************************************
 * ACF Menüpunkt verstecken - wenn Webseite im "nicht Debug Modus" läuft
 ***************************************/
if(!WP_DEBUG) {
	//add_filter('acf/settings/show_admin', '__return_false');
}

/***************************************
 * Remove Menus from Backend
 ***************************************/
function hh_remove_menus() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'hh_remove_menus' );


/***************************************
 * 	 Gutenberg Custom Farben hinterlegen
 ***************************************/
function hh_gutenberg_colors() {
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => esc_html__( 'Primary', 'theme-slug' ),
			'slug'  => 'primary',
			'color' => '#193a72',
		),
		array(
			'name'  => esc_html__( 'Secondary', 'theme-slug' ),
			'slug'  => 'secondary',
			'color' => '#ffd500',
		)
	) );
}
add_action( 'after_setup_theme', 'hh_gutenberg_colors', 11 );

/***************************************
*	Gutenberg Blöcke auf Startseite mit Container rendern
***************************************/
add_filter( 'render_block', function( $block_content, $block ) {
	if(!is_front_page() && !is_page('presse')) {
		// Target core/* and core-embed/* blocks.
		//print_r($block);
		//echo $block['blockName'] . '<br><br>';
		if ( preg_match( '~^core/|core-embed/~', $block['blockName'] ) && $block['blockName'] != 'core/cover' && $block['blockName'] != 'core/gallery' && $block['blockName'] != 'core/column' && $block['blockName'] != 'core/column' && $block['blockName'] != 'core/quote' && $block['blockName'] != 'core/media-text' && $block['blockName'] != 'core/image' && $block['attrs']['className'] != 'left' ) {
			$block_content = sprintf( '<div class="container"><div class="row justify-content-center"><div class="col-12 col-lg-9">%s</div></div></div>', $block_content );
		} elseif ($block['blockName'] == 'core/gallery') {
			$block_content = sprintf( '<div class="container-fluid"><div class="row justify-content-center"><div class="col-12 col-lg-9">%s</div></div></div>', $block_content );
		} elseif ($block['blockName'] == 'core/quote') {
			$block_content = sprintf( '<div class="container-fluid"><div class="row justify-content-center"><div class="col-12">%s</div></div></div>', $block_content );
		} elseif ($block['blockName'] == 'core/columns' && in_array( 'inline-columns', $block['innerBlocks'][0]['attrs'] )) {
			$block_content = sprintf( '<div class="container-fluid"><div class="row justify-content-center"><div class="col-12 col-lg-9">%s</div></div></div>', $block_content );
		} else if ($block['blockName'] == 'core/media-text') {
			$block_content = sprintf( '<div class="container"><div class="row justify-content-center"><div class="col-12">%s</div></div></div>', $block_content );
		}
		if( $block['attrs']['className'] == 'left' ) {
			$block_content = sprintf( '<div class="container"><div class="row"><div class="col-12">%s</div></div></div>', $block_content );
		}
		return $block_content;
	} else {
		return $block_content;
	}
}, PHP_INT_MAX - 1, 2 );

/***************************************
 * 	 Standard Taxonomien für Kategorien und Tags entfernen
 ***************************************/
function hh_remove_default_taxonomies() {
	global $pagenow;
	register_taxonomy( 'post_tag', array() );
	register_taxonomy( 'category', array() );
	$tax = array('post_tag','category');
	if($pagenow == 'edit-tags.php' && in_array($_GET['taxonomy'],$tax) ){
		wp_die('Invalid taxonomy');
	}
}
add_action('init', 'hh_remove_default_taxonomies');

/***************************************
 * 	 Beim "the_excerpt()" Conditional Tag das Ende aners darstellen
 ***************************************/
function hh_excerpt_more( $more ) {
	return '&#46;&#46;&#46;';
}
add_filter( 'excerpt_more', 'hh_excerpt_more' );

/***************************************
 * 	 Ändert in der URL den Slug "page" durch "Seite" für korrekte Lokalisierung
 ***************************************/
function hh_re_rewrite_rules() {
	global $wp_rewrite;
	$wp_rewrite->pagination_base = 'seite';
	$wp_rewrite->flush_rules();
}
add_action('init', 'hh_re_rewrite_rules');

/***************************************
 * 	 Erstellung einer Pagination Bar für das Newsarchiv
 ***************************************/
function hh_pagination_bar() {
	global $wp_query;
	$total_pages = $wp_query->max_num_pages;
	if ($total_pages > 1){
		$current_page = max(1, get_query_var('paged'));
		echo paginate_links(array(
			'base' => get_pagenum_link(1) . '%_%',
			'format' => '/seite/%#%',
			'current' => $current_page,
			'total' => $total_pages,
		));
	}
}

/***************************************
 * 	 ACF: Fill Select Menus with all GravityForms
 ***************************************/
function hh_acf_populate_gf_forms( $field ) {
	// reset choices
	$field['choices'] = array();
	/* Alle Formulare laden */
	$forms = GFAPI::get_forms(true, false);
	// loop through array and add to field 'choices'
	if( !empty($forms) ) {
		foreach( $forms as $form ) {
			$field['choices'][ $form['id'] ] = $form['title'];
		}
	}
	// return the field
	return $field;
}
add_filter('acf/load_field/name=block_lf_form', 'hh_acf_populate_gf_forms');
add_filter('acf/load_field/name=block_cf_form', 'hh_acf_populate_gf_forms');

/***************************************
 * 	 Limit Exceprt Length
 ***************************************/
function hh_custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'hh_custom_excerpt_length', 999 );