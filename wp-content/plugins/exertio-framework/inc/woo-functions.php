<?php
/*CREATING WOO COMMERCE PRODUCT TYPE AND HIDING TABS*/
function exertio_category_pricing_custom_js() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>
			jQuery( document ).ready( function() {
				jQuery('#general_product_data .pricing').addClass('show_if_wallet');
				jQuery('#product-type').trigger( 'change' );
			});
			
	</script><?php
}
add_action( 'admin_footer', 'exertio_category_pricing_custom_js' );
if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{
	// #1 Add New Product Type to Select Dropdown
	 
	add_filter( 'product_type_selector', 'fl_add_custom_product_type' );
	 
	function fl_add_custom_product_type( $types )
	{
		$types[ 'wallet' ] = 'Exertio Wallet';
		return $types;
	}
	 
	// --------------------------
	// #2 Add New Product Type Class
	 
	add_action( 'init', 'fl_create_custom_product_type' );
	 
	function fl_create_custom_product_type(){
		class WC_Product_Custom extends WC_Product {
		  public function get_type() {
			 return 'wallet';
		  }
		}
	}
	 
	// --------------------------
	// #3 Load New Product Type Class
	 
	add_filter( 'woocommerce_product_class', 'fl_woocommerce_product_class', 10, 2 );
	 
	function fl_woocommerce_product_class( $classname, $product_type ) {
		if ( $product_type == 'wallet' ) { 
			$classname = 'WC_Product_Custom';
		}
		return $classname;
	}
	add_filter('woocommerce_product_data_tabs', 'remove_woo_product_data_tab', 11, 1);
	function remove_woo_product_data_tab($tabs){
		
		$tabs['attribute']['class'][] = 'hide_if_wallet';
		$tabs['shipping']['class'][] = 'hide_if_wallet';
		$tabs['linked_product']['class'][] = 'hide_if_wallet';
		$tabs['advanced']['class'][] = 'hide_if_wallet';
		
		?>
		<script>
			jQuery( document ).ready( function() {
				jQuery('#general_product_data .pricing').addClass('show_if_wallet');
				jQuery('#product-type').trigger( 'change' );
			});
		</script>
		<?php
		return($tabs);
	}
}
// Get Products
if ( ! function_exists( 'fl_get_products' ) )
{
	function fl_get_products()
	{
		$args	=	array(
		'post_type' => 'product',
		'tax_query' => array(
			array(	
			   'taxonomy' => 'product_type',
			   'field' => 'slug',
			   'terms' => 'wallet'
			),
		),	
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'order'=> 'ASC',
		'orderby' => 'title'
		);
		$packages = new WP_Query( $args );
		$html ='';
		$html .= '<select name="funds_amount" class="general_select form-control" required data-smk-msg="'.esc_html__('Select amount to deposit','exertio_theme').'"><option value="">'.esc_html__("Select amount to deposit","exertio_framework").'</option>';
		while ( $packages->have_posts() )
		{
			$packages->the_post();
			$products_id	=	get_the_ID();
			$product	=	wc_get_product( $products_id );
			//$product_title = $product->get_title();
			$product_price = $product->get_price();
			$html .= '<option value="'.$products_id.'">'.fl_price_separator($product_price).'</option>';
		}
		$html .=  '</select>';
		return $html;
	}
}

/*DEPOSIT FUNDS CALLBACK*/
add_action('wp_ajax_fl_deposit_funds_callback', 'fl_deposit_funds_callback');
if ( ! function_exists( 'fl_deposit_funds_callback' ) )
{ 
	function fl_deposit_funds_callback()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_deposit_funds_secure', 'security' );
		//fl_authenticate_check();
		parse_str($_POST['deposit_fund_data'], $params);
		$products_id = $params['funds_amount'];
		
		if ( class_exists( 'WooCommerce' ) )
		{
				global $woocommerce;
				$qty = 1;
				if( $woocommerce->cart->add_to_cart($products_id, $qty) )
				{

					$cart_url = $woocommerce->cart->get_cart_url();
					$return = array('message' => esc_html__( 'Redirecting to cart page', 'exertio_framework' ),'cart_page' => $cart_url);
					wp_send_json_success($return);
					
				}
		}
		else
		{
			$return = array('message' => esc_html__( 'WooCommerce plugin is not active', 'exertio_framework' ));
			wp_send_json_error($return);
			exit();
		}
	}
}

if ( ! function_exists( 'fl_woocommerce_order_status_completed' ) )
{
	function fl_woocommerce_order_status_completed( $order_id )
	{

		$order = wc_get_order( $order_id );
		$items = $order->get_items();
	
		foreach ( $items as $item )
		{
			$product = wc_get_product( $item['product_id'] );
		  
		  
			$prduct_type = $product->get_type();
			if($prduct_type == 'wallet')
			{
				$user_id = $order->get_user_id();
				$amount = $order->get_total();
				$ex_amount = get_user_meta( $user_id, '_fl_wallet_amount', true );
			
				if(isset($ex_amount) && $ex_amount != '')
				{
					$new_amount = $ex_amount+$amount;
					update_user_meta($user_id, '_fl_wallet_amount',$new_amount);
				}
				else
				{
					update_user_meta($user_id, '_fl_wallet_amount',$amount);
				}
				update_user_meta($user_id, 'is_payment_verified', 1);
			}
		}
	}
}
add_action( 'woocommerce_order_status_completed', 'fl_woocommerce_order_status_completed', 10, 1 );

if ( ! function_exists( 'fl_woocommerce_auto_complete_order' ) )
{
	function fl_woocommerce_auto_complete_order( $order_id )
	{ 
		if ( ! $order_id ) {
			return;
		}
		if(fl_framework_get_options('wallet_amount_aproval') == 1)
		{
			$order = wc_get_order( $order_id );
			$items = $order->get_items();
	
			foreach ( $items as $item )
			{
				$product = wc_get_product( $item['product_id'] );
			  
				$prduct_type = $product->get_type();
				if($prduct_type == 'wallet')
				{
					$order->update_status( 'completed' );
				}
			}
		}
	}
}
add_action( 'woocommerce_thankyou', 'fl_woocommerce_auto_complete_order' );
?>