<?php
class WHATSO_Activation {
	
	/**
	 * Initialize constructor
	 */
	public function __construct () {
		if ( is_admin() ) {
			register_activation_hook( WHATSO_PLUGIN_BOOTSTRAP_FILE, array( $this, 'activation' ) );
		}
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
	}
	
	/**
	 * Function for activation
	 */
	 
	public function activation () {
                
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir.'/whatso';
			if ( ! is_dir( $upload_dir ) ) {
			   mkdir( $upload_dir, 0700 );
			}
		    $source = WHATSO_PLUGIN_DIR.'assets/css/auto-generated-whatso.css';
			$destination = wp_upload_dir( null,true,false )['basedir'].'/whatso/auto-generated-whatso.css';
			//rename( $source, $destination );
			/* Add options to WordPress specific for WHATSO */
		if ( ! get_option( WHATSO_SETTINGS_NAME ) ) {
			WHATSO_Utils::prepeareSettings();
			WHATSO_Utils::updateSetting( 'toggle_text', esc_html__( 'Chat with us', 'whatso' ) );
			WHATSO_Utils::updateSetting( 'toggle_text_color', 'rgba(255, 255, 255, 1)' );
			WHATSO_Utils::updateSetting( 'toggle_background_color', '#34aa91' );
			WHATSO_Utils::updateSetting( 'description', esc_html__( 'Hi, We are ready to help. Start a conversation by selecting a user below.', 'whatso' ) );
			WHATSO_Utils::updateSetting( 'mobile_close_button_text', esc_html__( 'Close and go back to page', 'whatso' ) );
			WHATSO_Utils::updateSetting( 'container_text_color', 'rgba(85, 85, 85, 1)' );
			WHATSO_Utils::updateSetting( 'container_background_color', 'rgba(255, 255, 255, 1)' );
			WHATSO_Utils::updateSetting( 'account_hover_background_color', 'rgba(245, 245, 245, 1)' );
			WHATSO_Utils::updateSetting( 'account_hover_text_color', 'rgba(85, 85, 85, 1)' );
			WHATSO_Utils::updateSetting( 'border_color_between_accounts', '#f5f5f5' );
			WHATSO_Utils::updateSetting( 'box_position', 'left' );
			
			WHATSO_Utils::updateSetting( 'consent_alert_background_color', 'rgba(255, 0, 0, 1)' );
			
			WHATSO_Utils::updateSetting( 'button_label', 'We are happy to help! Chat with us now.' );
			WHATSO_Utils::updateSetting( 'button_background_color', '#34aa91' );
			WHATSO_Utils::updateSetting( 'button_text_color', '#ffffff' );
			WHATSO_Utils::updateSetting( 'button_background_color_on_hover', '#34aa91' );
			WHATSO_Utils::updateSetting( 'button_text_color_on_hover', '#ffffff' );
			
			WHATSO_Utils::updateSetting( 'button_background_color_offline', '#a0a0a0' );
			WHATSO_Utils::updateSetting( 'button_text_color_offline', '#ffffff' );
			
			WHATSO_Utils::updateSetting( 'hide_on_large_screen', 'off' );
			WHATSO_Utils::updateSetting( 'hide_on_small_screen', 'off' );
			
			WHATSO_Utils::updateSetting( 'delay_time', '0' );
			WHATSO_Utils::updateSetting( 'inactivity_time', '0' );
			WHATSO_Utils::updateSetting( 'scroll_length', '0' );
	
			WHATSO_Utils::updateSetting( 'total_accounts_shown', '0' );
			WHATSO_Utils::generateCustomCSS();
		}
		else {
			WHATSO_Utils::generateCustomCSS();
		}
		
	}
	/**
	 * Function for load text domain
	 */
	public function loadTextDomain () {
		load_plugin_textdomain( 'whatso', false, plugin_basename( WHATSO_PLUGIN_DIR ).'/languages' );
	}
}