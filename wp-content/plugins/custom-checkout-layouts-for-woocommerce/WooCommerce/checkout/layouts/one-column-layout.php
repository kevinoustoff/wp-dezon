<div class="one-column-layout">
   	<h3 class="border_html"> <?php esc_html_e('Billing details', 'woocommerce' ); ?></h3>
	<?php include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/cclw_includes/cclw_billing_details_section.php';	?>
	
	<h3 class="border_html"><?php esc_html_e('Review your orders', 'woocommerce' ); ?></h3>
	 <?php include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/cclw_includes/cclw_review_order_section.php'; ?> 
	 
	<h3 class="border_html"><?php esc_html_e('Payments', 'woocommerce' ); ?></h3>
	<?php include_once CCLW_PLUGIN_DIR . 'WooCommerce/checkout/cclw_includes/cclw_payment_section.php'; ?>

</div>
   

<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>	
