<?php

class WHATSO_Scripts_And_Styles {
	
	public function __construct () {
		
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
		}
		
	}
	
	/**
	 * Enqueue scripts and styles only for our plugin.
	 */
	public function adminEnqueueScripts () {
		
		global $pagenow;
		
		$settings_pages = array(
			WHATSO_PREFIX . '_settings',
			WHATSO_PREFIX . '_floating_widget',
			WHATSO_PREFIX . '_woocommerce_button'
		);
		
		$plugin_data = get_file_data( WHATSO_PLUGIN_BOOTSTRAP_FILE, array( 'version' ) );
		$plugin_version = isset( $plugin_data[0] ) ? $plugin_data[0] : false;
		
		if ( ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && in_array( strtolower(sanitize_text_field( $_GET['page'] )), $settings_pages ) ) || 
				'whatso_accounts' === get_post_type() ) {
			
			wp_enqueue_media();
			
			wp_enqueue_style( 'jquery-minicolors', WHATSO_PLUGIN_URL . 'assets/css/jquery-minicolors.css', array(), $plugin_version );
			wp_enqueue_style( 'whatso-admin', WHATSO_PLUGIN_URL . 'assets/css/admin.css', array(), $plugin_version );
			
			wp_enqueue_script( 'jquery-minicolors', WHATSO_PLUGIN_URL . 'assets/js/vendor/jquery.minicolors.min.js', array( 'jquery' ), $plugin_version, true );
			wp_enqueue_script( 'whatso-admin', WHATSO_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), $plugin_version, true );

			wp_localize_script( 'whatso-admin', 'whatso_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		}
		
	}
	
}

?>