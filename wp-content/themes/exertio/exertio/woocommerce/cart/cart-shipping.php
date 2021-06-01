<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
<div class="woocommerce-shipping-totals shipping border-top pt-4 mt-4">
	<h3 class="pb-2"><?php echo wp_kses_post( $package_name ); ?></h3>
	<div data-title="<?php echo esc_attr( $package_name ); ?>">
		<?php if ( $available_methods ) : ?>
			<ul id="shipping_method" class="woocommerce-shipping-methods list-unstyled">
				<?php foreach ( $available_methods as $method ) : ?>
					<li>
						<?php if ( 1 < count( $available_methods ) ) : ?>
							<div class="custom-control custom-radio">
								<?php printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method custom-control-input" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok. ?>
								<?php printf( '<label for="shipping_method_%1$s_%2$s" class="custom-control-label">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) ); // WPCS: XSS ok. ?>
							</div>
						<?php else : ?>
							<?php printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok. ?>
							<?php printf( '<label for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ), wc_cart_totals_shipping_method_label( $method ) ); // WPCS: XSS ok. ?>
						<?php endif;
						do_action( 'woocommerce_after_shipping_rate', $method, $index );
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( is_cart() ) : ?>
				<p class="woocommerce-shipping-destination font-size-sm">
					<?php
					if ( $formatted_destination ) {
						// Translators: $s shipping destination.
						printf( esc_html__( 'Shipping to %s.', 'exertio_theme' ) . ' ', '<span class="font-weight-medium">' . esc_html( $formatted_destination ) . '</span>' );
						$calculator_text = esc_html__( 'Change address', 'exertio_theme' );
					} else {
						echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', esc_html__( 'Shipping options will be updated during checkout.', 'exertio_theme' ) ) );
					}
					?>
				</p>
			<?php endif; ?>
			<?php
		elseif ( ! $has_calculated_shipping || ! $formatted_destination ) : ?>
			<p class="font-size-sm">
				<?php echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', esc_html__( 'Enter your address to view shipping options.', 'exertio_theme' ) ) ); ?>
			</p>
		<?php elseif ( ! is_cart() ) : ?>
			<p class="font-size-sm">
				<?php echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', esc_html__( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'exertio_theme' ) ) ); ?>
			</p>
		<?php else : ?>
			<p class="font-size-sm">
				<?php
				// Translators: $s shipping destination.
				echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'exertio_theme' ) . ' ', '<span class="font-weight-medium">' . esc_html( $formatted_destination ) . '</span>' ) ) );
				?>
			</p>
			<?php
			$calculator_text = esc_html__( 'Enter a different address', 'exertio_theme' );
		endif;
		?>

		<?php if ( $show_package_details ) : ?>
			<p class="woocommerce-shipping-contents font-size-sm"><?php echo esc_html( $package_details ); ?></p>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<?php woocommerce_shipping_calculator( $calculator_text ); ?>
		<?php endif; ?>
	</div>
</div>
