<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;
$text_direction = 'text-right';
if(is_rtl())
{
    $text_direction = 'text-left';
}
?>
<div class="cart_totals <?php echo esc_attr( ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : '' ); ?>">
	<?php do_action( 'woocommerce_before_cart_totals' ); ?>
	<h3 class="pb-2"><?php esc_html_e( 'Total', 'exertio_theme' ); ?></h3>
	<div class="shop_table shop_table_responsive pb-1">
		<div class="d-flex justify-content-between align-items-center font-size-md mb-2 pb-1 cart-subtotal">
			<span class="mr-2"><?php esc_html_e( 'Sous-total', 'exertio_theme' ); ?>:</span>
			<span class="<?php echo esc_attr($text_direction); ?>" data-title="<?php esc_attr_e( 'Subtotal', 'exertio_theme' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="d-flex justify-content-between align-items-center font-size-md mb-2 pb-1 cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<div class="prop-coupon-code">
					<span class="mr-1"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
					<span><?php exertio_wc_cart_coupon_remove( $coupon ); ?></span>
				</div>
				<span class="<?php echo esc_attr($text_direction); ?>" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php exertio_wc_cart_coupon( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="d-flex justify-content-between align-items-center font-size-md mb-2 pb-1 fee">
				<span class="mr-2"><?php echo esc_html( $fee->name ); ?></span>
				<span class="<?php echo esc_attr($text_direction); ?>" data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) :
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimé pour %s)', 'exertio_theme' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			endif;

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
					?>
					<div class="d-flex justify-content-between align-items-center font-size-md mb-2 pb-1 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<span class="mr-2"><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="<?php echo esc_attr($text_direction); ?>" data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
					<?php
				endforeach;
			else :
				?>
				<div class="d-flex justify-content-between align-items-center font-size-md mb-2 pb-1 tax-total">
					<span class="mr-2"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="<?php echo esc_attr($text_direction); ?>" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
				<?php
			endif;
		endif;
		?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<div class="shipping border-top pt-4 mt-4">
				<h3 class="h6 mb-3 pb-1 "><?php esc_html_e( 'Expédition', 'exertio_theme' ); ?></h3>
				<div data-title="<?php esc_attr_e( 'Shipping', 'exertio_theme' ); ?>"><?php woocommerce_shipping_calculator(); ?></div>
			</div>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<div class="order-total  border-top pt-4 mt-4">
			<h3 class="font-weight-normal mb-0" data-title="<?php esc_attr_e( 'Total', 'exertio_theme' ); ?>"><?php wc_cart_totals_order_total_html(); ?></h3>
		</div>
		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</div>
	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
	<?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>
