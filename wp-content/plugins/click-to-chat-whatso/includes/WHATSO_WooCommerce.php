<?php

class WHATSO_WooCommerce {
	
	public function __construct () {
		
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'addMetaBoxes' ) );
			add_action( 'save_post', array( $this, 'saveMetaBoxes' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
		}
		else {
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'showBeforeATC' ) );
			add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'showAfterATC' ) );
			
			if ( 'after_long_description' === WHATSO_Utils::getSetting( 'wc_button_position' ) ) {
				add_filter( 'the_content', array( $this, 'showAfterLongDescription' ) );
			}
			if ( 'after_short_description' === WHATSO_Utils::getSetting( 'wc_button_position' ) ) {
				add_filter( 'woocommerce_short_description', array( $this, 'showAfterShortDescription' ), 10, 1 );
			}
		}

		add_action('woocommerce_checkout_order_processed', array( $this, 'order_processed' ), 99, 4);
		
	}
	
	public function showBeforeATC () {
		
		if ( 'before_atc' !== WHATSO_Utils::getSetting( 'wc_button_position' ) || 'on' == get_post_meta( get_the_ID(), 'whatso_remove_button', true ) ) {
			return;
		}
		echo esc_html($this)->setContainer();
	}
	
	public function showAfterATC () {
		
		if ( 'after_atc' !== WHATSO_Utils::getSetting( 'wc_button_position' ) || 'on' == get_post_meta( get_the_ID(), 'whatso_remove_button', true ) ) {
			return;
		}
		echo esc_html($this)->setContainer();
	}
	
	public function showAfterLongDescription ( $content ) {
		if ( 'product' !== sanitize_text_field (get_post_type()) 
				|| ! is_single() 
				|| 'on' === get_post_meta( get_the_ID(), 'whatso_remove_button', true )
			) {
			return $content;
		}
		
		return $content . $this->setContainer();
	}
	
	public function showAfterShortDescription ( $post_excerpt ) {
		
		if ( 'after_short_description' !== WHATSO_Utils::getSetting( 'wc_button_position' ) 
				|| 'on' === get_post_meta( get_the_ID(), 'whatso_remove_button', true ) 
				|| ! is_single()
			) {
			return $post_excerpt;
		}
		return $post_excerpt . $this->setContainer();
	}
	
	private function setContainer () {
		
		$selected_accounts = json_decode( WHATSO_Utils::getSetting( 'selected_accounts_for_woocommerce', '[]' ), true );
		$selected_accounts = is_array( $selected_accounts ) ? $selected_accounts : array();
		
		$custom_accounts = json_decode ( get_post_meta( get_the_ID(), 'whatso_selected_accounts', true ) );
		$custom_accounts = is_array( $custom_accounts ) ? $custom_accounts : array();
		if ( count( $custom_accounts ) > 0 ) {
			$selected_accounts = $custom_accounts;
		}
		
	
		$page_title = esc_html(get_the_title());
		$page_url = esc_url(get_permalink());
		
		return '<div class="whatso-wc-buttons-container" data-ids="' . implode( ',', $selected_accounts ) . '" data-page-title="' . $page_title . '" data-page-url="' . $page_url . '"></div>';
		
	
		
	}
	
	public function addMetaBoxes () {
		
		add_meta_box(
			'whatso_wc_button',
			esc_html__( 'WhatsApp Contact Button', 'whatso' ),
			array( $this, 'showMetaBox' ),
			array( 'product' )
		);
		
	}
	
	public function showMetaBox ( $post ) {
		
		?>
		<p class="description"><?php esc_html_e( 'You can set a custom WhatsApp button for this product. Leave the following fields blank if you wish to use the default values.', 'whatso' ); ?></p>
		<table class="form-table">
		<caption>Remove Whatso Button</caption>
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Remove Button', 'whatso' ); ?></th>
					<td>
						<input type="checkbox" name="whatso_remove_button" id="whatso_remove_button" value="on" <?php echo esc_html('on') === strtolower( sanitize_text_field(get_post_meta( $post->ID, 'whatso_remove_button', true ) )) ? 'checked' : ''; ?> /> <label for="whatso_remove_button"><?php esc_html_e( 'Remove WhatsApp button for this product', 'whatso' ); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		
		<table class="form-table" id="whatso-custom-wc-button-settings">
		<caption>Select Account to Display</caption>
			<tbody>
				<tr>
					<th scope="col"><label for="whatso_account_number"><?php esc_html_e( 'Selected Accounts', 'whatso' ); ?></label></th>
					<td><?php WHATSO_Templates::displaySelectedAccounts( 'selected_accounts_for_product', sanitize_text_field(get_the_ID()) ); ?></td>
				</tr>
			</tbody>
		</table>
		
		<?php
		
		wp_nonce_field( 'whatso_wc_meta_box', 'whatso_wc_meta_box_nonce' );
		
	}
	
	public function saveMetaBoxes ( $post_id ) {
		
		/* Check if our nonce is set. */
		if ( ! isset ($_POST['whatso_wc_meta_box_nonce'] ) ) {
			return;
		}
		
		$nonce = sanitize_text_field (wp_unslash($_POST['whatso_wc_meta_box_nonce']));
		
		/* Verify that the nonce is valid. */
		if ( ! wp_verify_nonce( $nonce, 'whatso_wc_meta_box' ) ) {
			return;
		}
		
		$remove_button = isset( $_POST['whatso_remove_button'] ) ? 'on' : 'off';
		$ids = array();
		$the_posts = isset( $_POST['whatso_selected_account'] ) ? array_values( sanitize_text_field(wp_unslash($_POST['whatso_selected_account']) )) : array();
		foreach ( $the_posts as $v ) {
			$ids[] = ( int ) $v;
		}
		
		update_post_meta( $post_id, 'whatso_selected_accounts', wp_json_encode( $ids ));
		update_post_meta( $post_id, 'whatso_remove_button', $remove_button);
		
	}
	
	public function adminEnqueueScripts ( $hook ) {
		
		if ( 'post.php' != $hook || 'product' != sanitize_text_field (get_current_screen()->post_type )) {
			return;
		}
		wp_enqueue_script( 'whatso-public', WHATSO_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), false, true );
		wp_enqueue_style( 'whatso-admin', WHATSO_PLUGIN_URL . 'assets/css/admin.css' );
	}

	public function order_processed($order_id, $posted_data, $order){
		$execute_flag = true;
		
		if(is_a($order, 'WC_Order_Refund')) {
			$execute_flag = false;
		}

		if($order == false){
			$execute_flag = false;
		}

		if($execute_flag){

			if ( ! empty( get_option( 'whatso_notifications' ) ) ) {
				$data = get_option( 'whatso_notifications' );
				$data = json_decode( $data );
				$whatso_username = $data->whatso_username;
				$whatso_password = $data->whatso_password;
				$whatso_mobileno = $data->whatso_mobileno;
				$whatso_message = $data->whatso_message;

				$store_name = get_bloginfo('name');
				$billing_email = $order->get_billing_email();
				$order_currency = $order->get_currency();
				$order_amount = $order->get_total();
				$order_date = $order->get_date_created();

				$items = $order->get_items();
				$products_array = array();
				
				foreach ($items as $item) {
					$quantity = $item->get_quantity();
					$product = $item->get_product();
					$product_name = '';
					if(!is_object($product)) {
						$product_name = $item->get_name();
					} else {
						
						$product_name = $product->get_title();
					}
					array_push($products_array, $product_name);
				}

				$countryCode = $order->get_billing_country();
				if(empty($countryCode)) {
					$countryCode = $order->get_shipping_country();
				}
				$city = $order->get_billing_city();
				if(empty($city)) {
					$city = $order->get_shipping_city();
				}
				$stateCode = $order->get_billing_state();
				if(empty($stateCode)) {
					$stateCode = $order->get_shipping_state();
				}

				$customernumber = $order->get_billing_phone();

				$exploded_names = implode(",", $products_array);

				$order_date_formatted = $order_date->date("d M Y H:i");

				$whatso_message = str_replace('{storename}',$store_name,$whatso_message);
				$whatso_message = str_replace('{orderdate}',$order_date_formatted,$whatso_message);
				$whatso_message = str_replace('{productname}',$exploded_names,$whatso_message);
				$whatso_message = str_replace('{amountwithcurrency}',$order_currency.' '.$order_amount,$whatso_message);
				$whatso_message = str_replace('{customeremail}',$billing_email,$whatso_message);
				$whatso_message = str_replace('{billingcity}',$city,$whatso_message);
				$whatso_message = str_replace('{billingstate}',$stateCode,$whatso_message);
				$whatso_message = str_replace('{billingcountry}',$countryCode,$whatso_message);
				$whatso_message = str_replace('{customernumber}',$customernumber,$whatso_message);

				$data_decoded = array("Username"=>$whatso_username,"Password"=>$whatso_password,"MessageText"=>$whatso_message,"MobileNumbers"=>$whatso_mobileno,"ScheduleDate"=>'');

				$data = json_encode($data_decoded);

				$url = "https://api.whatso.net/WhatsAppApi/V1/SendMessage";


				$response = wp_remote_post($url, array(
					'method' => 'POST',
					'headers' => array('Content-Type' => 'application/json; charset=utf-8','WPRequest' => 'abach34h4h2h11h3h'
				),
					'body' => $data
				));

			}
		}	

	}


	
}
?>