<?php
/*PAYMENT METHOD SAVE*/
add_action( 'wp_ajax_exertio_save_payment_method', 'exertio_save_payment_method' );
if ( ! function_exists( 'exertio_save_payment_method' ) ) 
{
	function exertio_save_payment_method()
	{

		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_gen_secure', 'security' );
		$current_user_id = get_current_user_id();
		$payment_method = $_POST['payment_method'];
		$default_payout = $_POST['default_payout'];
		parse_str($_POST['payment_method_data'], $params);
		
		update_user_meta( $current_user_id, '_default_payout_method', $default_payout );
		if($payment_method == 'paypal')
		{
			$payment_data[] = array(
						"payment_method" => sanitize_text_field($payment_method),
						"paypal_email" => sanitize_text_field($params['paypal_email']),
					);
				$encoded_payment_data =  wp_json_encode($payment_data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				update_user_meta( $current_user_id, '_paypal_details', $encoded_payment_data );
				
				$return = array('message' => esc_html__( 'Payout detail updated', 'exertio_framework' ));
				wp_send_json_success($return);
		}
		else if($payment_method == 'payoneer')
		{
			$payment_data[] = array(
						"payment_method" => sanitize_text_field($payment_method),
						"payoneer_acc_name" => sanitize_text_field($params['payoneer_acc_name']),
						"payoneer_email" => sanitize_text_field($params['payoneer_email']),
						"payoneer_acc_country" => sanitize_text_field($params['payoneer_country']),
					);
					
				$encoded_payment_data =  wp_json_encode($payment_data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				update_user_meta( $current_user_id, '_payoneer_details', $encoded_payment_data );
				
				$return = array('message' => esc_html__( 'Payout detail updated', 'exertio_framework' ));
				wp_send_json_success($return);
		}
		else if($payment_method == 'bank')
		{
			$payment_data[] = array(
						"payment_method" => sanitize_text_field($payment_method),
						"bank_name" => sanitize_text_field($params['bank_name']),
						"bank_acc_number" => sanitize_text_field($params['bank_account_number']),
						"bank_acc_name" => sanitize_text_field($params['bank_account_name']),
						"bank_routing_no" => sanitize_text_field($params['bank_routing_number']),
						"bank_iban" => sanitize_text_field($params['bank_iban_number']),
						"bank_swift" => sanitize_text_field($params['bank_swift_code']),
					);
				$encoded_payment_data =  wp_json_encode($payment_data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				update_user_meta( $current_user_id, '_bank_account_details', $encoded_payment_data );
				
				$return = array('message' => esc_html__( 'Payout detail updated', 'exertio_framework' ));
				wp_send_json_success($return);
		}
		elseif($payment_method == 'mobilemoney')
		{
			$payment_data[] = array(
						"payment_method" => sanitize_text_field($payment_method),
						"mobilemoney_tel" => sanitize_text_field($params['mobilemoney_tel']),
					);
				$encoded_payment_data =  wp_json_encode($payment_data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				update_user_meta( $current_user_id, '_mobilemoney_details', $encoded_payment_data );
				
				$return = array('message' => esc_html__( 'Payout detail updated', 'exertio_framework' ));
				wp_send_json_success($return);
		}
		else
		{
			$return = array('message' => esc_html__( 'Payment method detail error', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
	}
}




add_action( 'exertio_payouts_cron', 'exertio_create_payouts' );

if ( ! function_exists( 'exertio_create_payouts' ) ) 
{
	function exertio_create_payouts()
	{		
		$users = get_users( array( 'fields' => array( 'ID' ) ) );
		foreach($users as $user)
		{
			$today = date("F j, Y");
			$user_id = $user->ID;
			$wallet_amount = get_user_meta($user_id,'_fl_wallet_amount', true);
			$payout_method = get_user_meta($user_id,'_default_payout_method', true);
			
			/*CHECK IF USER HAS ENABLED PAYOUT OR NOT*/
			$settings = get_user_meta( $user_id, '_freelancer_settings', true );
			$decoded_settings =  json_decode(stripslashes($settings), true);
			if(is_array($decoded_settings) && !empty($decoded_settings))
			{
				if(array_key_exists("_enable_payout", $decoded_settings[0]) && $decoded_settings[0]['_enable_payout'] == 1)
				{
					if(isset($wallet_amount) && $wallet_amount > fl_framework_get_options('payout_min_limit'))
					{
						$my_post = array(
							'post_title' => 'Wallet payout '.$today,
							'post_type' => 'payouts',
							'post_author' => $user_id,
							'post_status'   => 'pending',
						);
						$post_id = wp_insert_post($my_post);


						if ($post_id)
						{
							update_post_meta ( $post_id, '_payout_status', 'pending' );
							update_post_meta ( $post_id, '_payout_amount', $wallet_amount );
							update_post_meta ( $post_id, '_payout_method', $payout_method );
							update_user_meta($user_id,'_fl_wallet_amount','');
							/*EMAIL ON PAYOUT CREATED*/
							if(fl_framework_get_options('fl_email_payout_create') == true)
							{
								fl_payout_create_email($user_id,$wallet_amount);
							}
						}
					}
				}
			}
		}
	}
}

add_action( 'init', 'exertio_create_payouts_event');

// Function which will register the event
function exertio_create_payouts_event() {
	// Make sure this event hasn't been scheduled
	if( !wp_next_scheduled( 'exertio_payouts_cron' ) ) {
		// Schedule the event
		wp_schedule_event( time(), 'custon_days', 'exertio_payouts_cron' );
	}
}

add_filter( 'cron_schedules', 'exertio_add_schedule' ); 
function exertio_add_schedule( $schedules ) {
	$payout_days = fl_framework_get_options('payout_days_after');
	$total_days = $payout_days* 24 * 60 * 60;
	$schedules['custon_days'] = array(
		'interval' => $total_days , //7 days * 24 hours * 60 minutes * 60 seconds
		'display' => __( 'After '.$payout_days.' days', 'exertio_framework' )
	);
	return $schedules;
}

?>