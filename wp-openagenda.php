<?php
/**
 * Plugin Name: WP Openagenda
 * Plugin URI: https://github.com/sebastienserre/wp-openagenda
 * Description: Easily display an OpenAgenda.com in your WordPress website
 * Version: 2.0.3
 * Author: Sébastien Serre
 * Author URI: http://www.thivinfo.com
 * Tested up to: 5.3
 * Text Domain: wp-openagenda-pro
 * Domain Path: /pro/languages
 * License: GPLv3
 *
 * @package         openagenda-wp
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class Openagenda_WP_Main
 */
class Openagenda_WP_Main {

	/**
	 * Openagenda_WP_Main constructor.
	 */
	public function __construct() {

		/**
		 * Define Constant
		 */
		define( 'THFO_OPENWP_VERSION', '2.0.3' );
		define( 'THFO_OPENWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'THFO_OPENWP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
		define( 'THFO_OPENWP_PLUGIN_DIR', untrailingslashit( THFO_OPENWP_PLUGIN_PATH ) );

		/**
		 * Load Files
		 */
		add_action( 'plugins_loaded', array( $this, 'thfo_openwp_load_files' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'thfo_openwp_load_style' ) );
		add_action( 'admin_print_styles', array( $this, 'openwp_load_admin_style' ) );
		add_action( 'plugins_loaded', array( $this, 'openwp_load' ), 400 );

		define( 'OPENWP_PRO_PATH', THFO_OPENWP_PLUGIN_PATH . 'pro/' );
		define( 'OPENWP_PRO_URL', THFO_OPENWP_PLUGIN_URL . 'pro/' );
		define( 'MY_ACF_PATH', OPENWP_PRO_PATH . '/3rd-party/acf/' );
		define( 'MY_ACF_URL', OPENWP_PRO_URL . '/3rd-party/acf/' );
		register_activation_hook( __FILE__, array( $this, 'openwp_activation__premium_only' ) );
		add_action( 'plugins_loaded', array( $this, 'openwp_load_pro_files__premium_only' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'openwp_pro_load_style__premium_only' ) );
		add_action( 'plugins_loaded', array( $this, 'openwp_load_textdomain__premium_only' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'openwp_register_script__premium_only' ) );
		add_action( 'admin_print_styles', array( $this, 'openwp_admin_style__premium_only' ) );

		add_filter( 'acf/settings/url', [ $this, 'my_acf_settings_url__premium_only' ] );
		//add_filter('acf/settings/show_admin', [ $this, 'my_acf_settings_show_admin__premium_only' ] );
	}

	public function openwp_activation__premium_only() {
		if ( ! wp_next_scheduled( 'openagenda_hourly_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'openagenda_hourly_event' );
		}
	}

	/**
	 * Load Pro Functions.
	 */
	public
	function openwp_load_pro_files__premium_only() {
		if ( class_exists( 'Vc_Manager' ) ) {
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/openagenda-vc-main.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-vc-events.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-openagenda-slider.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-openagenda-search.php';
		}

		include_once MY_ACF_PATH . 'acf.php';
		include_once OPENWP_PRO_PATH . 'inc/class-the-event-calendar.php';
		include_once OPENWP_PRO_PATH . 'admin/settings.php';
		include_once OPENWP_PRO_PATH . 'inc/cpt.php';
		include_once OPENWP_PRO_PATH . 'inc/venues.php';
		include_once OPENWP_PRO_PATH . 'inc/keywords.php';
		include_once OPENWP_PRO_PATH . 'inc/helpers.php';
		include_once OPENWP_PRO_PATH . 'inc/acf-fields.php';
		include_once OPENWP_PRO_PATH . 'inc/custom-fields.php';
		include_once OPENWP_PRO_PATH . 'inc/agenda.php';
		include_once OPENWP_PRO_PATH . 'widget/class-openagenda-main-widget.php';
		include_once OPENWP_PRO_PATH . 'widget/class-openagenda-slider-widget.php';
		include_once OPENWP_PRO_PATH . 'shortcodes/class-openagenda-embed-shortcode.php';
		include_once OPENWP_PRO_PATH . 'shortcodes/class-openagendaslidershortcode.php';
		include_once OPENWP_PRO_PATH . 'shortcodes/class-openagenda-search-shortcode.php';
		include_once OPENWP_PRO_PATH . 'blocks/class-openwp-block-embed.php';
		include_once OPENWP_PRO_PATH . 'blocks/class-openwp-agenda-list.php';
		include_once OPENWP_PRO_PATH . 'inc/class-import-oa.php';
		include_once OPENWP_PRO_PATH . 'shortcodes/class-openagenda-tec-shortcode.php';
	}

	/**
	 * Load Carbon-field v3
	 */
	public
	function openwp_load() {
		require_once THFO_OPENWP_PLUGIN_PATH . '/3rd-party/vendor/autoload.php';
		\Carbon_Fields\Carbon_Fields::boot();
	}


	/**
	 * Include all files needed to the plugin work
	 */
	public
	function thfo_openwp_load_files() {
		include_once THFO_OPENWP_PLUGIN_PATH . '/inc/helpers.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/admin/register-settings.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/class/class-openagendaapi.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/shortcodes/class-openagenda-shortcode.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/shortcodes/sc-main-agenda.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/3rd-party/vendor/erusev/parsedown/Parsedown.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/class/class-openagenda-wp-basic-widget.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/blocks/class-basicblock.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/views/main-agenda.php';
		include_once THFO_OPENWP_PLUGIN_PATH . '/blocks/class-mainagendablock.php';

	}

	/**
	 * Load light style CSS
	 */
	public
	function thfo_openwp_load_style() {
		wp_enqueue_style( 'openwp', THFO_OPENWP_PLUGIN_URL . 'assets/css/openwp.css' );
	}

	/**
	 * Load Admin Styles.
	 */
	public
	function openwp_load_admin_style() {
		wp_enqueue_style( 'openawp-admin-style', THFO_OPENWP_PLUGIN_URL . 'admin/assets/openwp-admin-styles.css' );
	}

	/**
	 * Load plugin textdomain.
	 *
	 * @since 1.0.0
	 */
	public
	function openwp_load_textdomain__premium_only() {
		load_plugin_textdomain( 'wp-openagenda-pro', false, basename( dirname( __FILE__ ) ) . '/pro/languages' );
	}

	public function my_acf_settings_url__premium_only( $url ) {
		return MY_ACF_URL;
	}

	public function my_acf_settings_show_admin__premium_only( $show_admin ) {
		return false;
	}


	public
	function openwp_register_script__premium_only() {
		wp_register_script( 'dateOA', THFO_OPENWP_PLUGIN_URL . 'pro/assets/js/datepickerOA.js',
			array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-datepicker',
			)
		);
		wp_enqueue_style( 'openwp-pro', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/openwp-pro.css', array( 'slickthemecss' ) );
		wp_register_script( 'IsotopeOA', THFO_OPENWP_PLUGIN_URL . 'pro/assets/js/isotope.pkgd.min.js',
			array(
				'jquery',
			)
		);
		wp_register_script( 'IsotopeInit', THFO_OPENWP_PLUGIN_URL . 'pro/assets/js/isotope-init.js',
			array(
				'IsotopeOA',
			)
		);
	}

	public function openwp_admin_style__premium_only() {
		wp_enqueue_style( 'openwp-pro', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/openwp-pro.css' );
	}

	/**
	 * Load light style CSS
	 */
	public
	function openwp_pro_load_style__premium_only() {
		wp_enqueue_style( 'jquery-ui-dp', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/jquery-ui.min.css' );
	}
}

new Openagenda_WP_Main();

