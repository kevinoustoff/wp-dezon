<?php
// New User Registration Email For All
if (!function_exists('fl_framework_new_user_email'))
{
    function fl_framework_new_user_email($user_id, $password = '')
	{
		if(!empty($user_id))
		{
			if(fl_framework_get_options('fl_email_sendto_admin') == true)
			{
				$to = get_option('admin_email');
				$subject = fl_framework_get_options('fl_new_user_admin_sub');
				$from = get_option('admin_email');
				$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
				// Get User info
				$user_info = get_userdata($user_id);

				$keywords = array('%site_name%', '%display_name%', '%email%');
				$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_info->display_name, $user_info->user_email);
				$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_new_user_admin_email_body'));
				wp_mail($to, $subject, $body, $headers);
				
			}
			//For User Welcome Email
			if(fl_framework_get_options('fl_email_sendto_user') == true)
			{
				$user_infos = get_userdata($user_id);
				$to = $user_infos->user_email;
				$subject = fl_framework_get_options('fl_new_user_welcome_sub');
				$from = get_option('admin_email');
				$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
				$keywords = array('%site_name%', '%display_name%', '%email%' );
				$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name, $user_infos->user_email);
				$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_new_user_welcome_message_body'));

				wp_mail($to, $subject, $body, $headers);
			}
		}
	}
}


if (!function_exists('fl_account_activation_email'))
{
    function fl_account_activation_email($user_id, $verification_link)
	{
		if(!empty($user_id) && !empty($verification_link))
		{
			$user_infos = get_userdata($user_id);
			$to = $user_infos->user_email;
			//$to = 'jssols76@gmail.com';
			$subject = fl_framework_get_options('fl_user_email_verification_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array('%site_name%', '%display_name%', '%verification_link%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name,$verification_link);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_user_email_verification_message'));
			wp_mail($to, $subject, $body, $headers);
		}
	}
}

// Send New Reset Password On Email
if (!function_exists('fl_forgotpass_email'))
{
    function fl_forgotpass_email($user_id, $reset_link)
	{
		if(!empty($user_id) && !empty($reset_link))
		{
			$user_infos = get_userdata($user_id);
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_user_reset_pwd_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array('%site_name%', '%display_name%', '%reset_link%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name,$reset_link);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_user_reset_message'));
			wp_mail($to, $subject, $body, $headers);
		}
	}
}

function mobile_forgotpass_email($user_id,$reset_link,$key){
	if(!empty($user_id) && !empty($reset_link))
	{
		$user_infos = get_userdata($user_id);
		$to = $user_infos->user_email;
		$subject = fl_framework_get_options('fl_user_reset_pwd_sub');
		$from = get_option('admin_email');
		$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
		$keywords = array('%site_name%', '%display_name%', '%reset_link%');
		$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name,$reset_link);
		$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_user_reset_message'));
		
		$last ="<p><h1> Votre code de récupération:</h1>".$key."</p><br/>";
		$body = $last.$body;

		wp_mail($to, $subject, $body, $headers);
	}
}



// Send EMAIL ON PROJECT POST
if (!function_exists('fl_project_post_email'))
{
    function fl_project_post_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_onproject_created_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name,get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_email_onproject_created_email_body'));
			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICE POST
if (!function_exists('fl_service_post_email'))
{
    function fl_service_post_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_onservice_created_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%service_link%', '%service_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_infos->display_name,get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_onservice_created_body'));
			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON ASSIGN PROJECT FREELANCER
if (!function_exists('fl_assign_project_freelancer_email'))
{
    function fl_assign_project_freelancer_email($user_id, $post_id, $cost)
	{
		if(!empty($user_id) && !empty($post_id) && !empty($cost))
		{
			$project_cost = fl_price_separator($cost);
			
			$user_infos = get_userdata($user_id);
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$user_name = exertio_get_username('freelancer', $freelancer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_assign_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%','%project_cost%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, get_the_permalink($post_id), get_the_title($post_id), $project_cost);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_assign_project_message_body'));
			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON ASSIGN PROJECT EMPLOYER
if (!function_exists('fl_assign_project_employer_email'))
{
    function fl_assign_project_employer_email($user_id, $post_id, $cost, $freelancer_user_id)
	{
		if(!empty($user_id) && !empty($post_id) && !empty($cost))
		{
			$project_cost = fl_price_separator($cost);
			
			$user_infos = get_userdata($user_id);
			
			$emp_id = get_user_meta( $user_id, 'employer_id' , true );
			$emp_user_name = exertio_get_username('employer', $emp_id, '');
			
			$freelancer_id = get_user_meta( $freelancer_user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_assign_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%','%project_cost%', '%freelancer_display_name%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $emp_user_name, get_the_permalink($post_id), get_the_title($post_id), $project_cost, $freelancer_user_name);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_emp_assign_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}

// Send EMAIL ON PROJECT COMPLETE FREELANCER
if (!function_exists('fl_project_completed_freelancer_email'))
{
    function fl_project_completed_freelancer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			
			
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$user_name = exertio_get_username('freelancer', $freelancer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_complete_project_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_complete_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}

// Send EMAIL ON PROJECT COMPLETE FREELANCER
if (!function_exists('fl_project_completed_freelancer_email'))
{
    function fl_project_completed_freelancer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			
			
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$user_name = exertio_get_username('freelancer', $freelancer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_complete_project_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_complete_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON PROJECT COMPLETE EMPLOYER
if (!function_exists('fl_project_completed_employer_email'))
{
    function fl_project_completed_employer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{

			$user_infos = get_userdata($user_id);
			
			$employer_id = get_user_meta( $user_id, 'employer_id' , true );
			$user_name = exertio_get_username('employer', $employer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_complete_project_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_emp_complete_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON PROJECT CANCELED FREELANCER
if (!function_exists('fl_project_canceled_freelancer_email'))
{
    function fl_project_canceled_freelancer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{

			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$user_name = exertio_get_username('freelancer', $freelancer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_cancel_project_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%admin_email%','%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, $from, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_cancel_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON PROJECT CANCELED EMPLOYER
if (!function_exists('fl_project_canceled_employer_email'))
{
    function fl_project_canceled_employer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{

			$user_infos = get_userdata($user_id);
			
			$employer_id = get_user_meta( $user_id, 'employer_id' , true );
			$user_name = exertio_get_username('employer', $employer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_cancel_project_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%admin_email%','%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, $from, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_emp_cancel_project_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON PROJECT PROPOSAL
if (!function_exists('fl_project_proposal_email'))
{
    function fl_project_proposal_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{

			$user_infos = get_userdata($user_id);
			
			$employer_id = get_user_meta( $user_id, 'employer_id' , true );
			$user_name = exertio_get_username('employer', $employer_id, '');

			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_project_proposal_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%', '%admin_email%','%project_link%', '%project_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, $from, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_project_proposal_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICES PURCHASE
if (!function_exists('fl_service_purchased_freelancer_email'))
{
    function fl_service_purchased_freelancer_email($user_id, $post_id, $cost)
	{
		if(!empty($user_id) && !empty($post_id) && !empty($cost))
		{

			$user_infos = get_userdata($user_id);
			
			$employer_id = get_user_meta( $user_id, 'employer_id' , true );
			$user_name = exertio_get_username('employer', $employer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_order_receive_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%', '%service_cost%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $user_name, get_the_permalink($post_id), get_the_title($post_id), $cost);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_order_receive_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICES PURCHASE EMPLOYER
if (!function_exists('fl_service_purchased_employer_email'))
{
    function fl_service_purchased_employer_email($user_id, $post_id, $cost, $freelancer_id)
	{
		if(!empty($user_id) && !empty($post_id) && !empty($cost))
		{

			$user_infos = get_userdata($user_id);
			
			$emp_id = get_user_meta( $user_id, 'employer_id' , true );
			$emp_user_name = exertio_get_username('employer', $emp_id, '');
			
			$freelancer_id = get_user_meta( $freelancer_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_order_created_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%', '%service_cost%', '%freelancer_display_name%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $emp_user_name, get_the_permalink($post_id), get_the_title($post_id), $cost, $freelancer_user_name);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_emp_order_created_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICES COMPLETED FREELANCER
if (!function_exists('fl_service_completed_freelancer_email'))
{
    function fl_service_completed_freelancer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_complete_order_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $freelancer_user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_complete_order_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICES COMPLETED EMPLOYER
if (!function_exists('fl_service_completed_employer_email'))
{
    function fl_service_completed_employer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			
			$emp_id = get_user_meta( $user_id, 'employer_id' , true );
			$emp_user_name = exertio_get_username('employer', $emp_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_complete_order_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $emp_user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_emp_complete_order_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}

// Send EMAIL ON SERVICES CANCELED FREELANCER
if (!function_exists('fl_service_canceled_freelancer_email'))
{
    function fl_service_canceled_freelancer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_freelancer_cancel_order_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $freelancer_user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_freelancer_cancel_order_message_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON SERVICES CANCELED EMPLOYER
if (!function_exists('fl_service_canceled_employer_email'))
{
    function fl_service_canceled_employer_email($user_id, $post_id)
	{
		if(!empty($user_id) && !empty($post_id))
		{
			$user_infos = get_userdata($user_id);
			
			$emp_id = get_user_meta( $user_id, 'employer_id' , true );
			$emp_user_name = exertio_get_username('employer', $emp_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_emp_cancel_order_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%service_link%', '%service_title%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $emp_user_name, get_the_permalink($post_id), get_the_title($post_id));
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_email_emp_cancel_order_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
// Send EMAIL ON PAYOUT CREATED
if (!function_exists('fl_payout_create_email'))
{
    function fl_payout_create_email($user_id, $payment_amount)
	{
		if(!empty($user_id)  && !empty($payment_amount))
		{
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_payout_create_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%','%payout_amount%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $freelancer_user_name, $payment_amount);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_email_payout_create_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
if (!function_exists('fl_payout_processed_email'))
{
    function fl_payout_processed_email($user_id)
	{
		if(!empty($user_id))
		{
			$user_infos = get_userdata($user_id);
			print_r($user_infos);
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_payout_processed_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $freelancer_user_name);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_email_payout_processed_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}
/*IDENTITY VERIFICATION EMAIL*/
if (!function_exists('fl_identity_verify_email'))
{
    function fl_identity_verify_email($user_id)
	{
		if(!empty($user_id))
		{
			$user_infos = get_userdata($user_id);
			
			$freelancer_id = get_user_meta( $user_id, 'freelancer_id' , true );
			$freelancer_user_name = exertio_get_username('freelancer', $freelancer_id, '');
			
			$to = $user_infos->user_email;
			$subject = fl_framework_get_options('fl_email_payout_create_sub');
			$from = get_option('admin_email');
			$headers = array('Content-Type: text/html; charset=UTF-8', "From: $from");
			$keywords = array( '%site_name%', '%display_name%');
			$replaces = array(wp_specialchars_decode(get_bloginfo('name'),ENT_QUOTES), $freelancer_user_name);
			$body = str_replace($keywords, $replaces, fl_framework_get_options('fl_email_payout_create_body'));

			wp_mail($to, $subject, $body, $headers);
		}
	}
}