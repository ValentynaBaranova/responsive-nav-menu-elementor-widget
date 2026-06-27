<?php
/**
 * Plugin Name:       Responsive Nav Menu for Elementor
 * Plugin URI:        https://github.com/ValentynaBaranova/responsive-nav-menu-elementor-widget
 * Description:       Adds a "Responsive Nav Menu" Elementor widget that renders any WordPress menu as a responsive navigation with an accessible fullscreen mobile burger and collapsible submenus.
 * Version:           1.0.4
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Valentyna Baranova
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       responsive-nav-menu-elementor
 * Elementor tested up to: 3.25.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RNME_VERSION', '1.0.4' );
define( 'RNME_URL', plugin_dir_url( __FILE__ ) );
define( 'RNME_PATH', plugin_dir_path( __FILE__ ) );

function rnme_init() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'rnme_missing_elementor_notice' );
		return;
	}

	add_action( 'elementor/widgets/register', 'rnme_register_widget' );
	add_action( 'elementor/elements/categories_registered', 'rnme_register_category' );
	add_action( 'wp_enqueue_scripts', 'rnme_register_assets' );
	add_action( 'elementor/preview/enqueue_styles', 'rnme_enqueue_preview_assets' );
}
add_action( 'plugins_loaded', 'rnme_init' );

function rnme_missing_elementor_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'Responsive Nav Menu for Elementor requires Elementor to be installed and active.', 'responsive-nav-menu-elementor' );
	echo '</p></div>';
}

function rnme_register_assets() {
	wp_register_style(
		'responsive-nav-menu',
		RNME_URL . 'assets/css/responsive-nav-menu.css',
		array(),
		RNME_VERSION
	);

	wp_register_script(
		'responsive-nav-menu',
		RNME_URL . 'assets/js/responsive-nav-menu.js',
		array(),
		RNME_VERSION,
		true
	);
}

function rnme_enqueue_preview_assets() {
	rnme_register_assets();
	wp_enqueue_style( 'responsive-nav-menu' );
	wp_enqueue_script( 'responsive-nav-menu' );
}

function rnme_register_category( $elements_manager ) {
	$elements_manager->add_category(
		'responsive-nav-menu',
		array(
			'title' => esc_html__( 'Responsive Nav Menu', 'responsive-nav-menu-elementor' ),
			'icon'  => 'eicon-nav-menu',
		)
	);
}

function rnme_register_widget( $widgets_manager ) {
	require_once RNME_PATH . 'includes/class-responsive-nav-menu-widget.php';
	$widgets_manager->register( new \RNME_Menu_Widget() );
}

function rnme_render_menu( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'menu'           => '',
			'theme_location' => '',
			'aria_label'     => __( 'Main menu', 'responsive-nav-menu-elementor' ),
			'open_label'     => __( 'Open menu', 'responsive-nav-menu-elementor' ),
			'close_label'    => __( 'Close menu', 'responsive-nav-menu-elementor' ),
			'layout'         => 'horizontal',
			'icon_html'      => '',
			'icon_position'  => 'before',
		)
	);

	$walker = '';
	if ( '' !== $args['icon_html'] ) {
		$walker = rnme_get_nav_walker( $args['icon_html'], $args['icon_position'] );
	}

	$menu_html = wp_nav_menu(
		array(
			'menu'           => $args['menu'],
			'theme_location' => $args['theme_location'],
			'fallback_cb'    => false,
			'container'      => false,
			'menu_class'     => 'menu rnm-menu__list',
			'echo'           => false,
			'walker'         => $walker,
		)
	);

	if ( ! $menu_html ) {
		return '';
	}

	static $instance = 0;
	++$instance;
	$nav_id = 'rnm-menu-nav-' . $instance;

	$wrapper_classes = array( 'rnm-menu' );

	if ( 'vertical' === $args['layout'] ) {
		$wrapper_classes[] = 'rnm-menu--vertical';
	}

	if ( '' !== $args['icon_html'] && 'after' === $args['icon_position'] ) {
		$wrapper_classes[] = 'rnm-menu--icon-after';
	}

	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-rnm-menu>
		<div class="rnm-menu__inner">
			<button
				class="rnm-menu__burger"
				type="button"
				aria-expanded="false"
				aria-controls="<?php echo esc_attr( $nav_id ); ?>"
				aria-label="<?php echo esc_attr( $args['open_label'] ); ?>"
				data-open-label="<?php echo esc_attr( $args['open_label'] ); ?>"
				data-close-label="<?php echo esc_attr( $args['close_label'] ); ?>"
			>
				<span class="rnm-menu__burger-line"></span>
				<span class="rnm-menu__burger-line"></span>
				<span class="rnm-menu__burger-line"></span>
			</button>

			<nav id="<?php echo esc_attr( $nav_id ); ?>" class="rnm-menu__nav" aria-label="<?php echo esc_attr( $args['aria_label'] ); ?>">
				<?php echo $menu_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
		</div>
	</div>
	<?php

	return ob_get_clean();
}


function rnme_get_nav_walker( $icon_html, $icon_position = 'before' ) {
	if ( ! class_exists( 'RNME_Nav_Walker' ) ) {
		class RNME_Nav_Walker extends Walker_Nav_Menu {
			public $rnme_icon          = '';
			public $rnme_icon_position = 'before';

			public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
				$item_output = '';
				parent::start_el( $item_output, $data_object, $depth, $args, $current_object_id );

				if ( '' !== $this->rnme_icon && 0 === $depth ) {
					if ( 'after' === $this->rnme_icon_position ) {
						$item_output = preg_replace( '#(</a>)#', $this->rnme_icon . '$1', $item_output, 1 );
					} else {
						$item_output = preg_replace( '#(<a\b[^>]*>)#', '$1' . $this->rnme_icon, $item_output, 1 );
					}
				}

				$output .= $item_output;
			}
		}
	}

	$walker                     = new RNME_Nav_Walker();
	$walker->rnme_icon          = $icon_html;
	$walker->rnme_icon_position = ( 'after' === $icon_position ) ? 'after' : 'before';

	return $walker;
}
