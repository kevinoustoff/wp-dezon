<?php
$tab = isset( $_GET['tab'] ) ? strtolower( sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) : '';
if( $tab == "" or $tab == "selected_accounts" ) {
	include_once( 'floating_widget_selected_accounts.php');
}
elseif( $tab == 'display_settings' ) {
	include_once( 'floating_widget_display_settings.php' );
}
elseif( $tab == 'auto_display' ) {
	include_once( 'floating_widget_auto_display.php' );
}
elseif( $tab == 'consent_confirmation' ) {
	include_once( 'floating_widget_consent_confirmation.php' );
}
else{
	wp_die();
}
?>