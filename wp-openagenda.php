<?php
/**
 * Plugin Name: WP Openagenda
 * Plugin URI: https://openagenda4wp.com/
 * Description: Easily display an OpenAgenda.com in your WordPress website
 * Version: 1.6.1
 * Author: Sébastien Serre
 * Author URI: http://www.thivinfo.com
 * Tested up to: 5.1
 * Text Domain: wp-openagenda
 * License: GPLv3
 *
 * @package         openagenda-wp
 * @fs_premium_only /pro/, /.idea/
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! function_exists( 'openagenda_fs' ) ) {

// Create a helper function for easy SDK access.
	function openagenda_fs() {
		global $openagenda_fs;

		if ( ! isset( $openagenda_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$openagenda_fs = fs_dynamic_init( array(
				'id'              => '2279',
				'slug'            => 'wp-openagenda',
				'type'            => 'plugin',
				'public_key'      => 'pk_ab0021b682585d81e582568095957',
				'is_premium'      => true,
				'has_addons'      => false,
				'has_paid_plans'  => true,
				'trial'           => array(
					'days'               => 30,
					'is_require_payment' => false,
				),
				'has_affiliation' => 'customers',
				'menu'            => array(
					'slug'   => 'openagenda-settings',
					'parent' => array(
						'slug' => 'options-general.php',
					),
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'      => 'sk_^FhYDtZ;KihDaYCX}LTf80_o}-Zf!',
			) );
		}

		return $openagenda_fs;
	}

// Init Freemius.
	openagenda_fs();
// Signal that SDK was initiated.
	do_action( 'openagenda_fs_loaded' );


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
			define( 'THFO_OPENWP_VERSION', '1.6.1' );
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

			if ( openagenda_fs()->is__premium_only() ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'openwp_pro_load_style__premium_only' ) );
				add_action( 'plugins_loaded', array( $this, 'openwp_load_pro_files__premium_only' ) );
				add_action( 'plugins_loaded', array( $this, 'openwp_load_textdomain__premium_only' ) );
				add_action( 'plugins_loaded', array( $this, 'openwp_register_script__premium_only' ), 999 );
				add_action( 'admin_print_styles', array( $this, 'openwp_admin_style__premium_only') );
				register_activation_hook( __FILE__, array( $this, 'openwp_activation__premium_only' ) );
			}
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

			/**
			 * If Visual Composer activated, load VC elements.
			 */
			if ( class_exists( 'Vc_Manager' ) ) {
				include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/openagenda-vc-main.php';
				include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-vc-events.php';
				include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-openagenda-slider.php';
				include_once THFO_OPENWP_PLUGIN_PATH . '/pro/vc/class-openagenda-search.php';
			}

			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/admin/settings.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/cpt.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/venues.php';
			//include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/categories.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/keywords.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/custom-fields.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/inc/agenda.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/widget/class-openagenda-main-widget.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/widget/class-openagenda-slider-widget.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/shortcodes/class-openagenda-embed-shortcode.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/shortcodes/class-openagendaslidershortcode.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/shortcodes/class-openagenda-search-shortcode.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/blocks/class-openwp-block-embed.php';
			include_once THFO_OPENWP_PLUGIN_PATH . '/pro/blocks/class-openwp-block-slider.php';
		}


		/**
		 * Load plugin textdomain.
		 *
		 * @since 1.0.0
		 */
		public
		function openwp_load_textdomain__premium_only() {
			load_plugin_textdomain( 'wp-openagenda', false, basename( dirname( __FILE__ ) ) . '/pro/languages' );
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
			wp_enqueue_style( 'openwp-pro', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/openwp-pro.css' );
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

		public function openwp_admin_style__premium_only(){
			wp_enqueue_style( 'openwp-pro', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/openwp-pro.css' );
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
		 * Load light style CSS
		 */
		public
		function openwp_pro_load_style__premium_only() {
			wp_enqueue_style( 'jquery-ui-dp', THFO_OPENWP_PLUGIN_URL . 'pro/assets/css/jquery-ui.min.css' );
		}

		/**
		 * Load Admin Styles.
		 */
		public
		function openwp_load_admin_style() {
			wp_enqueue_style( 'openawp-admin-style', THFO_OPENWP_PLUGIN_URL . 'admin/assets/openwp-admin-styles.css' );
		}
	}

	new Openagenda_WP_Main();
}
