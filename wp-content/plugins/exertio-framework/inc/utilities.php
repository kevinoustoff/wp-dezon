<?php
if (!function_exists('fl_authenticate_check')) {

    function fl_authenticate_check() {
        if (get_current_user_id() == "") {
            $return = array('message' => esc_html__( 'Please login first', 'exertio_framework' ));
			wp_send_json_error($return);
        }
    }
}
// Bad word filter
if (!function_exists('fl_badwords_filter')) {

	function fl_badwords_filter($words = array(), $string = '' , $replacement = '') {
		foreach ($words as $word) {
			$string = str_replace($word, $replacement, $string);
		}
		return $string;
	}
}

if (!function_exists('get_icon_for_attachment')) {
	function get_icon_for_attachment($post_id, $size = '' ) {
	  $base = get_template_directory_uri() . "/images/dashboard/";
	  $type = get_post_mime_type($post_id);
	  $img = wp_get_attachment_image_src( $post_id, $size );
	  switch ($type) {
		case 'application/pdf':
			return $base . "pdf.png"; break;
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			return $base . "doc.png"; break;
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			return $base . "ppt.png"; break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			return $base . "xls.png"; break;
		case 'application/zip':
			return $base . "zip.png"; break;
		case 'image/png':
		case 'image/jpg':
		case 'image/jpeg':
			return $img[0];  break;
		default:
			return $base . "file.png";
	  }
	}
}

if (!function_exists('get_icon_for_attachment_type')) {
	function get_icon_for_attachment_type($file_type, $post_id = ''  ) {
	  $base = get_template_directory_uri() . "/images/dashboard/";
	  
	  $img = wp_get_attachment_image_src( $post_id, $size );
	  switch ($file_type) {
		case 'application/pdf':
			return $base . "pdf.png"; break;
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			return $base . "doc.png"; break;
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			return $base . "ppt.png"; break;
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			return $base . "xls.png"; break;
		case 'application/zip':
			return $base . "zip.png"; break;
		case 'image/png':
		case 'image/jpg':
		case 'image/jpeg':
			return $img[0];  break;
		default:
			return $base . "file.png";
	  }
	}
}



add_action( 'wp_ajax_sign_up', 'sign_up' );
add_action( 'wp_ajax_nopriv_sign_up', 'sign_up' );
if (! function_exists ( 'sign_up' )) {
	function sign_up() {
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		check_ajax_referer( 'fl_register_secure', 'security' );
		$params = array();
		parse_str($_POST['signup_data'], $params);
		global $exertio_theme_options;
		
		
		if( email_exists($params['fl_email']) == false )
		{
			$user_args = array(
				'user_pass'             => $params['fl_password'],  
				'user_nicename'            => sanitize_text_field($params['fl_username']),
				'user_login'            => sanitize_text_field($params['fl_username']),
				'display_name' 			=> sanitize_text_field($params['fl_full_name']),
				'user_email'			=> sanitize_text_field($params['fl_email']),
			);
			$uid =	wp_insert_user($user_args);
			if ( function_exists( 'exertio_generate_code_registeration' ) )
			{
				exertio_generate_code_registeration($uid);
			}

			$page = get_the_permalink($exertio_theme_options['user_dashboard_page']);
			fl_auto_login($params['fl_email'], $params['fl_password'], $remember ); 
			setcookie('active_profile', 'employer', time() + (86400 * 365), "/");
			echo '1|' . __("Inscription effectuée avec succès. Redirection en cours ...", 'exertio_framework')."|".$page;
			die;
		}
		else
		{
			echo '0|' .__( 'Cet email est déjà inscrit sur le site. Essayer avec un autre.', 'exertio_framework' );
			die;
		}
	}
}
add_action('user_register','exertion_on_registration_funtion');
if (! function_exists ( 'exertion_on_registration_funtion' )) {
	function exertion_on_registration_funtion($uid){
		

		$user_info = get_userdata($uid);

		$my_post = array(
			'post_title' => sanitize_text_field($user_info->user_login),
			'post_status' => 'publish',
			'post_author' => $uid,
			'post_type' => 'employer'
		);

		$company_id = wp_insert_post($my_post);
		update_post_meta( $company_id, '_employer_dispaly_name', sanitize_text_field($user_info->display_name));
		update_user_meta( $uid, 'employer_id', $company_id );
		
		update_post_meta( $company_id, '_is_employer_verified', 0);
		update_post_meta( $company_id, '_employer_is_featured', 0);
		update_post_meta( $company_id, 'is_employer_email_verified', 0 );
		update_post_meta( $company_id, 'is_employer_profile_completed', 0 );

		$my_post_2 = array(
			'post_title' => sanitize_text_field($user_info->user_login),
			'post_status' => 'publish',
			'post_author' => $uid,
			'post_type' => 'freelancer'
		);
		$freelancer_id = wp_insert_post($my_post_2);
		update_post_meta( $freelancer_id, '_freelancer_dispaly_name', sanitize_text_field($user_info->display_name));
		update_user_meta( $uid, 'freelancer_id', $freelancer_id );
		
		update_post_meta( $freelancer_id, '_is_freelancer_verified', 0);
		update_post_meta( $freelancer_id, '_freelancer_is_featured', 0);
		update_post_meta( $freelancer_id, 'is_freelancer_email_verified', 0 );
		update_post_meta( $company_id, 'is_freelancer_profile_completed', 0 );

		if(fl_framework_get_options('fl_email_onregister') == true)
		{
			fl_framework_new_user_email($uid);
		}

		update_user_meta( $uid, 'is_phone_verified', 0 );
		update_user_meta( $uid, 'is_payment_verified', 0 );
		//update_user_meta( $uid, 'is_profile_completed', 0 );
		update_user_meta( $uid, 'is_email_verified', 0 );
		
		if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
		{
			/*ASSIGNING PACKAGES*/
			echo exertio_freelancer_pck_on_registeration($freelancer_id);
			echo exertio_employer_pck_on_registeration($company_id);
		}
	}
}

// Ajax handler for Login User
add_action( 'wp_ajax_fl_sign_in', 'fl_sign_in' );
add_action( 'wp_ajax_nopriv_fl_sign_in', 'fl_sign_in' );
if (! function_exists ( 'fl_sign_in' )) {
	function fl_sign_in()
	{
		global $exertio_theme_options;
		
		$page = get_the_permalink($exertio_theme_options['user_dashboard_page']);
		// Getting values
		$params = array();
		parse_str($_POST['signin_data'], $params);
		$remember = false;
		if( $params['is_remember'] )
		{
			$remember = true;
		}

		$user  = wp_authenticate( $params['fl_email'], $params['fl_password'] );
		if( !is_wp_error($user) )
		{
			if( count((array) $user->roles ) == 0 )
			{
				echo '0|'. __( 'Votre compte n\'est pas encore vérifié.', 'exertio_framework' );
				die();
			}
			else
			{
				$res = fl_auto_login($params['fl_email'], $params['fl_password'], $remember ); 
				if( $res == 1 )
				{
					$exertio_theme_options['user_dashboard_page'];
					echo "1|". __( 'Login successful. Redirecting....', 'exertio_framework' )."|".$page;
				}
			}
		}
		else
		{
			echo '0|'.__( 'Email ou mot de passe invalide.', 'exertio_framework' );
		}
		die();
	}
}


if (! function_exists ( 'fl_auto_login' )) {
	function fl_auto_login($username, $password, $remember )
	{
		$creds = array();
		$creds['user_login'] = $username;
		$creds['user_password'] = $password;
		$creds['remember'] = $remember;
		$user = wp_signon( $creds, false );
		if ( is_wp_error($user) )
		{
			return false;
		}
		else
		{
			if( count((array) $user->roles ) > 0 )
			{
				return 1;
			}
			else
			{
				return 2;
			}
		}
	}
}
// Ajax handler for Forgot Password
add_action( 'wp_ajax_fl_forget_pwd', 'fl_forget_pwd' );
add_action( 'wp_ajax_nopriv_fl_forget_pwd', 'fl_forget_pwd' );
if (!function_exists ( 'fl_forget_pwd' ))
{
	function fl_forget_pwd()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_forget_pwd_secure', 'security' );
		$params = array();
		parse_str($_POST['forget_pwd_data'], $params);

		$email = trim(sanitize_email($params['fl_forget_email']));
		if(empty($email))
		{
			$return = array('message' => esc_html__( 'Veuillez saisir votre email.', 'exertio_framework' ));
			wp_send_json_error($return);
		}
		else if ( !is_email( $email ) )
		{
			$return = array('message' => esc_html__( 'Veuillez saisir un email valide.', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
		else if(!email_exists($email)) {
			$return = array('message' => esc_html__( 'Cet email n\'existe pas sur ce site.', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
		else
		{
			$user = get_user_by('email', $email);
			$user_email = $user->user_login;
			$reset_key = get_password_reset_key($user);
			$signinlink = get_the_permalink(fl_framework_get_options('login_page'));
			update_user_meta( $user->ID, '_reset_password_key', $reset_key );
			
			$reset_link = esc_url($signinlink.'?action=rp&key='.$reset_key.'&login='.rawurlencode($user_email));
			

			fl_forgotpass_email($user->ID,$reset_link);
			$return = array('message' => esc_html__( 'Vérifier le lien de confirmation dans votre mail.', 'exertio_framework' ));
			wp_send_json_success($return);
			die();
		}
	}
}
// Ajax handler for Reset New Password
add_action( 'wp_ajax_fl_forgot_pass_new', 'fl_forgot_pass_new' );
add_action( 'wp_ajax_nopriv_fl_forgot_pass_new', 'fl_forgot_pass_new' );
if (!function_exists ( 'fl_forgot_pass_new' ))
{
	function fl_forgot_pass_new()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_forget_new_psw_secure', 'security' );
		$params = array();
		parse_str($_POST['forget_pwd_data'], $params);
		
		if(!empty($params['requested_user_id']))
		{
			$user_id = $params['requested_user_id'];
			$stored_reset_key = get_user_meta( $user_id, '_reset_password_key' , true );

			$reset_key = $params['reset_key'];
			if($stored_reset_key == $reset_key)
			{
				$password = trim(sanitize_text_field( $params['password'] ));
				if(empty($password)){
					$return = array('message' => esc_html__( 'Veuillez choisir un mot de passe avec au moins 3-12 caractères.', 'exertio_framework' ));
					wp_send_json_error($return);	
				}
				wp_set_password($password, $user_id);
				update_user_meta( $user_id, '_reset_password_key', '' );
				$return = array('message' => esc_html__( 'Votre mot de passe a été changé. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.', 'exertio_framework' ));
				wp_send_json_success($return);
			}
			else
			{
				$return = array('message' => esc_html__( 'Vous n\'êtes pas autorisé à faire cette action.', 'exertio_framework' ));
				wp_send_json_error($return);
			}
		}
		else
		{
			$return = array('message' => esc_html__( 'L\'identifiant de l\'utilisateur n\'existe pas. Veuillez contacter l\'équipe de Dezon.', 'exertio_framework' ));
			wp_send_json_error($return);
		}
	}
}


/* EMPLOYER PROFILE PICTURE UPLOAD */
add_action('wp_ajax_emp_profile_pic', 'freelance_emp_profile_pic');

if ( ! function_exists( 'freelance_emp_profile_pic' ) ) 
{ 
	function freelance_emp_profile_pic()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		global $exertio_theme_options;
		$pid = $_POST['post-id'];
		
		$post_meta = $_POST['post-meta'];
		$field_name =  $_FILES[$_POST['field-name']];
		/* img upload */
		$condition_img=7;
		$img_count = count((array) explode( ',',$_POST["image_gallery"] )); 
	
		if(!empty($field_name))
		{
		
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			
			
			$files = $field_name;
			   
			$attachment_ids=array();
			$attachment_idss='';
			
			if($img_count>=1)
			{
			 $imgcount=$img_count;
			}
			else
			{
			 $imgcount=1;
			}
			$ul_con='';
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("emp_profile_picture" => $file); 
					
					// Allow certain file formats
					$imageFileType	=	end( explode('.', $file['name'] ) );
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
					{
						echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG files are allowed.", 'exertio_framework' );
						die();
					}
					
					// Check file size
					$image_size = $exertio_theme_options['user_attachment_size'];
					if ($file['size']/1000 > $image_size) {
						echo '0|' . esc_html__( "Max allowd image size is ".$image_size." KB", 'exertio_framework' );
						die();
					}
					
					foreach ($_FILES as $file => $array) 
					{              
					  
					  if($imgcount>=$condition_img){ break; } 
					 $attach_id = media_handle_upload( $file, $pid );
					 
					  $attachment_ids[] = $attach_id; 
					
					  $image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					  
					}
					if($imgcount>$condition_img){ break; } 
					$imgcount++;
				} 
			}
		} 
		/*img upload */
		$attachment_idss = array_filter( $attachment_ids  );
		$attachment_idss =  implode( ',', $attachment_idss );  
	
	
		$arr = array();
		$arr['attachment_idss'] = $attachment_idss;
		$arr['ul_con'] =$ul_con; 
		
		
		//update_user_meta($uid, '_profile_pic_attachment_id', $attach_id );
		update_post_meta( $pid, $post_meta, $attach_id);
		echo '1|'.esc_html__( "Image changed Successfully", 'exertio_framework' ).'|' . $image_link[0].'|'.$attach_id;
		 die();
	
	}
}

add_action('wp_ajax_fl_delete_image', 'fl_delete_image');

if ( ! function_exists( 'fl_delete_image' ) ) 
{ 
	function fl_delete_image()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		$pid = $_POST['post_id'];
		$attachment_id = $_POST['attachment_id'];
		$post_meta = $_POST['post_meta'];
		
		if($pid != "" && $post_meta != "")
		{
			update_post_meta( $pid, $post_meta, '');
			wp_delete_attachment( $attachment_id, true );
			echo '1|'.esc_html__( "Image Removed", 'exertio_framework' );
		}
		else
		{
			echo '0|'.esc_html__( "Something went wrong!!!", 'exertio_framework' );
		}
		die();
	}
}		

/*SAVE EMPLOYER PROFILE*/
add_action( 'wp_ajax_employer_profile', 'fl_employer_profile' );
function fl_employer_profile() {
	/*DEMO DISABLED*/
	exertio_demo_disable('echo');

	check_ajax_referer( 'fl_save_pro_secure', 'security' );
	$uid = get_current_user_id();
	$post_id = $_POST['post_id'];
	$params = array();
    parse_str($_POST['emp_data'], $params);
	global $exertio_theme_options;
	
	$post_author = get_post_field( 'post_author', $post_id );
	if( $post_author == $uid )
	{
		$new_slug =  preg_replace('/\s+/', '', $params['emp_name']);
		
		
		$words = explode(',', $exertio_theme_options['bad_words_filter']);
		$replace = $exertio_theme_options['bad_words_replace'];
		$desc = fl_badwords_filter($words, $params['emp_desc'], $replace);
		$my_post = array(
			'ID' => $post_id,
			'post_title' => sanitize_text_field($params['emp_name']),
			'post_name' => sanitize_text_field($new_slug),
            'post_content' => wp_kses_post($desc),
            'post_type' => 'employer'
        );
		
		$result = wp_update_post($my_post, true);
		
        if (is_wp_error($result)){
			echo '0|' .__( 'Data is not saved', 'exertio_framework' );
            wp_die();
        }


		if(isset($params['employer_employees']))
		{
			$employer_employees_terms = array((int)$params['employer_employees']); 
			update_post_meta( $post_id, '_employer_employees', sanitize_text_field($params['employer_employees']));
			wp_set_post_terms( $post_id, $employer_employees_terms, 'employees-number', false );
		}
		if(isset($params['employer_location']))
		{
			update_post_meta( $post_id, '_employer_location', sanitize_text_field($params['employer_location']));
			set_hierarchical_terms('employer-locations', $params['employer_location'], $post_id);
		
		}
		if(isset($params['employer_department']))
		{
			$department_terms = array((int)$params['employer_department']); 
			update_post_meta( $post_id, '_employer_department', sanitize_text_field($params['employer_department']));
			wp_set_post_terms( $post_id, $department_terms, 'departments', false );
		}
		
		if(isset($params['emp_tagline']))
		{
			update_post_meta( $post_id, '_employer_tagline', sanitize_text_field($params['emp_tagline']));
			
		}
		if(isset($params['emp_display_name']))
		{
			update_post_meta( $post_id, '_employer_dispaly_name', sanitize_text_field($params['emp_display_name']));
			
		}
		if(isset($params['emp_contact']))
		{
			update_post_meta( $post_id, '_employer_contact_number', sanitize_text_field($params['emp_contact']));
			
		}
		
		if(isset($params['emp_address']))
		{
			update_post_meta( $post_id, '_employer_address', sanitize_text_field($params['emp_address']));
			
		}
		if(isset($params['emp_lat']))
		{
			update_post_meta( $post_id, '_employer_latitude', sanitize_text_field($params['emp_lat']));
			
		}
		if(isset($params['emp_long']))
		{
			update_post_meta( $post_id, '_employer_longitude', sanitize_text_field($params['emp_long']));
			
		}
		if(isset($params['facebook_url']))
		{
			update_post_meta( $post_id, '_employer_facebook_url', sanitize_text_field($params['facebook_url']));
			
		}
		if(isset($params['twitter_url']))
		{
			update_post_meta( $post_id, '_employer_twitter_url', sanitize_text_field($params['twitter_url']));
			
		}
		if(isset($params['linkedin_url']))
		{
			update_post_meta( $post_id, '_employer_linkedin_url', sanitize_text_field($params['linkedin_url']));
			
		}
		if(isset($params['instagram_url']))
		{
			update_post_meta( $post_id, '_employer_instagram_url', sanitize_text_field($params['instagram_url']));
			
		}
		if(isset($params['dribble_url']))
		{
			update_post_meta( $post_id, '_employer_dribble_url', sanitize_text_field($params['dribble_url']));
			
		}
		if(isset($params['behance_url']))
		{
			update_post_meta( $post_id, '_employer_behance_url', sanitize_text_field($params['behance_url']));
			
		}
		
		
		echo '1|' . __("Profile updated", 'exertio_framework');
		die;
		
	}
	else
	{
		echo '0|' .__( 'You are not allowed to do that', 'exertio_framework' );
		die;
	}
}

/* CHANGE PASSWORD */

add_action('wp_ajax_fl_change_password', 'fl_change_password');

if ( ! function_exists( 'fl_change_password' ) ) 
{ 
	function fl_change_password()
	{
		exertio_demo_disable('echo');
		check_ajax_referer( 'fl_change_psw_secure', 'security' );
		global $exertio_theme_options;
		fl_authenticate_check();
		$params = array();
		parse_str($_POST['pass_data'], $params);

		
		$current_pass	=	$params['old_password'];
		$new_pass	=	sanitize_text_field( $params['new_password'] );
		$con_new_pass	=	sanitize_text_field( $params['confirm_password']);
		if( $current_pass == "" || $new_pass == "" || $con_new_pass == "" )
		{
			echo '0|' . esc_html__( "All fields are required.", 'exertio_framework' );
			die();
		}
		if( $new_pass == $current_pass )
		{
			echo '0|' . esc_html__( "Sorry, you can not set the same password again", 'exertio_framework' );
			die();
		}
		if( $new_pass != $con_new_pass )
		{
			echo '0|' . esc_html__( "New password Mismatched", 'exertio_framework' );
			die();
		}
		$user = get_user_by( 'ID', get_current_user_id() );
		if( $user && wp_check_password( $current_pass, $user->data->user_pass, $user->ID) )
		{
			wp_set_password( $new_pass, $user->ID );
			$page = get_home_url();
			echo '1|' . esc_html__( "Password changed successfully.", 'exertio_framework' ).'|'.$page;
		}
		else
		{
		   echo '0|' . esc_html__( "Wrong current password", 'exertio_framework' );
		}
		
		die();
	}
}	


/*DELETE USER ACCOUNT*/	
// Delete user
add_action('wp_ajax_fl_delete_account', 'fl_delete_my_account');
if ( ! function_exists( 'fl_delete_my_account' ) )
{ 
	function fl_delete_my_account()
	{
		exertio_demo_disable('echo');
		
		check_ajax_referer( 'fl_delete_pro_secure', 'security' );
		fl_authenticate_check();
		if(is_super_admin())
		{
			echo '0|' . __( "Admin can not delete his account.", 'exertio_framework' );
			die();
		}
		else
		{
			$user_id		= get_current_user_id();
			// delete comment with that user id
			$c_args = array ('user_id' => $user_id,'post_type' => 'any','status' => 'all');
			$comments = get_comments($c_args);
			if(count((array) $comments) > 0 )
			{
				foreach($comments as $comment) :
					wp_delete_comment($comment->comment_ID, true);
				endforeach;
			}
			// delete user posts
			 $args = array ('numberposts' => -1,'post_type' => 'any','author' => $user_id);
			 $user_posts = get_posts($args);
			 // delete all the user posts
			 if(count((array) $user_posts) > 0 )
			 {
				 foreach ($user_posts as $user_post) {
					wp_delete_post($user_post->ID, true);
				 }
			 }
			 //now delete actual user
			 wp_delete_user($user_id);
			 echo '1|' . __( "Account deleted successfully", 'exertio_framework' ).'|'.get_home_url();
			 die();
		}
	}
}



/* PROJECT ATTACHMENTS UPLOAD */

add_action('wp_ajax_project_attachments', 'freelance_project_attachments');

if ( ! function_exists( 'freelance_project_attachments' ) ) 
{ 
	function freelance_project_attachments()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		global $exertio_theme_options;
		$pid = $_POST['post-id'];
		$field_name =  $_FILES['project_attachments'];
		$condition_img=7;
		$attachment_size = '2000';
		$img_count = count(array_count_values($field_name['name']));

		if(isset($exertio_theme_options['project_attachment_count']))
		{
			$condition_img= $exertio_theme_options['project_attachment_count'];
		}
		
		if(isset($exertio_theme_options['project_attachment_size']))
		{
			$attachment_size= $exertio_theme_options['project_attachment_size'];
		}

		if(!empty($field_name))
		{
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			$files = $field_name;

			$files_array = array();
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("emp_profile_picture" => $file); 
					
						foreach ($_FILES as $file => $array) 
						{              
							$exist_data = get_post_meta( $pid, '_project_attachment_ids', true );
							
							$is_upload_file = true;
							$imageFileType	=	end( explode('.', $array['name'] ) );
							if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pptx" && $imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "ppt" && $imageFileType != "xls" && $imageFileType != "xlsx" && $imageFileType != "svg")
							{
								$is_upload_file = false;
								$attach_id = 0;
								$message =  esc_html__( "Sorry, only JPG, JPEG, PNG, docx, pptx, xlsx, SVG and pdf files are allowed.", 'exertio_framework' );
								
							}							
							else
							{
								
								$exist_data_count ='';
								if(isset($exist_data) && $exist_data != 0)
								{
									$exist_data_count = count(explode(",",$exist_data));
								}

								$is_upload_file = true;
								if($exist_data_count >= $condition_img)
								{
									$message = esc_html__( "Attachment upload limit reached", 'exertio_framework' );
									$is_upload_file = false;
									$attach_id = 0;
								}

								if($is_upload_file)
								{
								$is_upload_file = true;
								if ($array['size']/1000 > $attachment_size) {
									$is_upload_file = false;
									$attach_id = 0;
									$message = esc_html__( "Max allowd attachment size is ".$attachment_size.' Kb', 'exertio_framework' );
									
								}

								if($is_upload_file){
									
									$attach_id = media_handle_upload( $file, $pid );

									if( is_wp_error($attach_id ))
									{
										$is_upload_file = false;
										$message = $attach_id->get_error_message();
										$attach_id = 0;
									}
									else
									{								
										if(isset($exist_data) && $exist_data != 0)
										{
												$attach_id_store = $exist_data.','.$attach_id;
										}
										else
										{
											$attach_id_store = $attach_id;
										}
										update_post_meta( $pid, '_project_attachment_ids', $attach_id_store);
										$message = esc_html__( "File Uploaded", 'exertio_framework' );
									}	
								}
							}
							
							}
							
							$icon = get_icon_for_attachment_type($array['type'], $attach_id);
					
							$files_array[] = array(
								'name' => $array['name'],
								'icon' => $icon,
								'file-size' => $array['size'],
								'message' => $message,
								'data-id' => $attach_id,
								'data-pid' => $pid,
								'is-error' => (isset($is_upload_file) && $is_upload_file == true) ? '':'upload-error',
							);
						}
				} 
			}
		} 		
		foreach($files_array as $arr){
			$data .=  '<div class="attachments ui-state-default pro-atta-'.$arr['data-id'].' '.$arr['is-error'].'"> <img src="'.$arr['icon'].'" alt="'.get_post_meta($arr['data-id'], '_wp_attachment_image_alt', TRUE).'" data-img-id="'.$arr['data-id'].'"><span class="attachment-data"> <h4>'.$arr['name']. '<small class="'.$arr['is-error'].'">  - '. $arr['message'] .'</small> </h4> <p>'.esc_html__( "file size:", 'exertio_framework' ).'  '.$arr['file-size'].esc_html__( " Kb", 'exertio_framework' ).' </p> <a href="javascript:void(0)" class="btn-pro-clsoe-icon" data-id="'.$arr['data-id'].'" data-pid="'.$arr['data-pid'].'"> <i class="fal fa-times-circle"></i></a> </span></div>';
		}
		
		echo '1|'.esc_html__( "Attachments uploaded", 'exertio_framework' ).'|' .$data.'|'.$attach_id_store;
		die();
	}
}


/* PROJECT ATTACHMENTS DELETE */

add_action('wp_ajax_delete_project_attachment', 'fl_delete_project_attachment');

if ( ! function_exists( 'fl_delete_project_attachment' ) ) 
{
	function fl_delete_project_attachment()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		$attachment_id = $_POST['attach_id'];
		$pid = $_POST['pid'];

		if($attachment_id !='' && $pid != '')
		{
			$exist_data = get_post_meta( $pid, '_project_attachment_ids', true );
			
			$array1 = array($attachment_id);
			$array2 = explode(',', $exist_data);
			$array3 = array_diff($array2, $array1);
			wp_delete_attachment($attachment_id);
			$new_data = implode(',', $array3);
			update_post_meta( $pid, '_project_attachment_ids', $new_data);
			$return = array('message' => esc_html__( 'Attachment deleted', 'exertio_framework' ), 'newData' => $new_data);
			wp_send_json_success($return);
			
		}
		else
		{
			$return = array('message' => esc_html__( 'Error!!! attachment is not deleted', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
	}
}




add_action( 'wp_ajax_create_project', 'fl_create_project' );
if ( ! function_exists( 'fl_create_project' ) ) 
{
	function fl_create_project()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_create_project_secure', 'security' );
		$current_user_id = get_current_user_id();
		$employer_id = get_user_meta( $current_user_id, 'employer_id' , true );
		
		$post_id = $_POST['post_id'];
		$project_status = get_post_status ( $post_id );
		$params = array();
		parse_str($_POST['project_data'], $params);
		
		
		global $exertio_theme_options;
		if($params['is_update'] != '')
		{
			$status = 'publish';
			if(isset($exertio_theme_options['update_project_approval']) &&  $exertio_theme_options['update_project_approval'] == 0)
			{
				$status = 'pending';
			}
		}
		else
		{

			if(isset($exertio_theme_options['project_approval']) &&  $exertio_theme_options['project_approval'] == 0)
			{
				$status = 'pending';
			}
			else
			{
				if($project_status = 'publish')
				{
					$status = 'publish';
				}
			}
		}
		
		$words = explode(',', $exertio_theme_options['bad_words_filter']);
		$replace = $exertio_theme_options['bad_words_replace'];
		$project_name = fl_badwords_filter($words, $params['project_name'], $replace);
		$desc = fl_badwords_filter($words, $params['project_desc'], $replace);
		
		
		$my_post = array(
			'ID' => $post_id,
			'post_title' => sanitize_text_field($project_name),
			'post_content' => wp_kses_post($desc),
			'post_type' => 'projects',
			'post_author' => $current_user_id,
			'post_status'   => $status,
		);

		$result = wp_update_post($my_post, true);


		if (is_wp_error($result))
		{
			$return = array('message' => esc_html__( 'Error!!! Please contact admin', 'exertio_framework' ));
			wp_send_json_error($return);
		}


		if(isset($params['project_level']))
		{
			$project_level_terms = array((int)$params['project_level']); 
			update_post_meta( $post_id, '_project_level', sanitize_text_field($params['project_level']));
			wp_set_post_terms( $post_id, $project_level_terms, 'project-level', false );
		}
		/* herve script*/
		if(isset($params['project_date_debut']))
		{
			update_post_meta( $post_id, '_project_date_debut', sanitize_text_field($params['project_date_debut']));
			wp_set_post_terms( $post_id, $params['project_date_debut'], 'project-date-debut', false );
		}
		if(isset($params['project_date_fin']))
		{
			update_post_meta( $post_id, '_project_date_fin', sanitize_text_field($params['project_date_fin']));
			wp_set_post_terms( $post_id, $params['project_date_fin'], 'project-date-fin', false );
		}
		/*fin script*/
		if(isset($params['project_duration']))
		{
			$duration_terms = array((int)$params['project_duration']); 
			update_post_meta( $post_id, '_project_duration', sanitize_text_field($params['project_duration']));
			wp_set_post_terms( $post_id, $duration_terms, 'project-duration', false );
		}
		if(isset($params['project_type']))
		{
			update_post_meta( $post_id, '_project_type', sanitize_text_field($params['project_type']));
		}
		if($params['project_type'] == 'fixed')
		{
			if(isset($params['project_cost']))
			{
				update_post_meta( $post_id, '_project_cost', sanitize_text_field($params['project_cost']));

			}
		}
		else if($params['project_type'] == 'hourly')
		{
			if(isset($params['project_cost_hourly']) && isset($params['estimated_hours']))
			{
				update_post_meta( $post_id, '_project_cost', sanitize_text_field($params['project_cost_hourly']));
				update_post_meta( $post_id, '_estimated_hours', sanitize_text_field($params['estimated_hours']));
			}
		}


		if(isset($params['freelancer_typel']))
		{
			$type_terms = array((int)$params['freelancer_typel']); 
			update_post_meta( $post_id, '_project_freelancer_type', sanitize_text_field($params['freelancer_typel']));
			wp_set_post_terms( $post_id, $type_terms, 'freelancer-type', false );
		}
		if(isset($params['english_level']))
		{
			$type_terms = array((int)$params['english_level']); 
			update_post_meta( $post_id, '_project_eng_level', sanitize_text_field($params['english_level']));
			wp_set_post_terms( $post_id, $type_terms, 'english-level', false );
		}
		if(isset($params['project_skills']))
		{
			$integerIDs = array_map('intval', $params['project_skills']);
			$integerIDs = array_unique($integerIDs);
			wp_set_post_terms( $post_id, $integerIDs, 'skills' );
		}
		if(isset($params['project_languages']))
		{
			$integerIDs = array_map('intval', $params['project_languages']);
			$integerIDs = array_unique($integerIDs);
			wp_set_post_terms( $post_id, $integerIDs, 'languages' );
		}
		if(isset($params['project_location_remote']))
		{
			update_post_meta( $post_id, '_project_location_remote', 1);
		}
		else
		{
			update_post_meta( $post_id, '_project_location_remote', 0);
			if(isset($params['project_location']))
			{
				update_post_meta( $post_id, '_project_location', sanitize_text_field($params['project_location']));
				set_hierarchical_terms('locations', $params['project_location'], $post_id);
			}
		}
		if(isset($params['project_category']))
		{
			update_post_meta( $post_id, '_project_category', sanitize_text_field($params['project_category']));
			set_hierarchical_terms('project-categories', $params['project_category'], $post_id);
		}
		if(isset($params['project_address']))
		{
			update_post_meta( $post_id, '_project_address', sanitize_text_field($params['project_address']));
		}
		if(isset($params['project_lat']))
		{
			update_post_meta( $post_id, '_project_latitude', sanitize_text_field($params['project_lat']));
		}
		if(isset($params['project_long']))
		{
			update_post_meta( $post_id, '_project_longitude', sanitize_text_field($params['project_long']));
		}
		if(isset($params['is_show_project_attachments']) && $params['is_show_project_attachments'] == 'yes')
		{
			update_post_meta( $post_id, '_project_attachment_show', 'yes');
		}
		else
		{
			update_post_meta( $post_id, '_project_attachment_show', 'no');
		}
		update_user_meta( $current_user_id, '_processing_post_id', '' );
		update_post_meta( $post_id, '_project_status', 'active');
		/*ATTACHMENT UPDATED*/
		update_post_meta( $post_id, '_project_attachment_ids', $params['project_attachment_ids']);
		
		
		$selected_reference = '';
		if(isset($post_id) && $post_id !="")
		{
			$selected_reference = fl_framework_get_options('fl_project_id');
			if(isset($selected_reference) && $selected_reference !="")
			{
				$updated_id = preg_replace( '/{ID}/', $post_id, $selected_reference );
				update_post_meta($post_id, '_project_ref_id', sanitize_text_field($updated_id));
			}
			else
			{
				update_post_meta($post_id, '_project_ref_id', $post_id);
			}
		}

		$c_dATE = DATE("d-m-Y");
		if($params['is_update'] == '')
		{
			$is_prjects_paid = fl_framework_get_options('is_projects_paid');
			if(isset($is_prjects_paid) && $is_prjects_paid == 1)
			{
				$simple_projects = get_post_meta($employer_id, '_simple_projects', true);
				if(isset($simple_projects) && $simple_projects != -1)
				{
					if($simple_projects != -1)
					{
						$new_simple_project = $simple_projects - 1;	
						update_post_meta($employer_id, '_simple_projects', $new_simple_project);
					}
				}
				$simple_project_expiry_days = get_post_meta($employer_id, '_simple_project_expiry', true);
				if($simple_project_expiry_days == -1)
				{
					update_post_meta($post_id, '_simple_projects_expiry_date', -1);
				}
				else
				{
					if($simple_project_expiry_days != '' && $simple_project_expiry_days > 0 )
					{
						$simple_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $simple_project_expiry_days days"));

						update_post_meta($post_id, '_simple_projects_expiry_date', $simple_project_expiry_date);
					}
					else if($simple_project_expiry_days == '')
					{
						$default_project_expiry = fl_framework_get_options('project_default_expiry');
						$simple_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_project_expiry days"));
						update_post_meta($post_id, '_simple_projects_expiry_date', $simple_project_expiry_date);
					}
				}
			}
			else if(isset($is_prjects_paid) && $is_prjects_paid == 0)
			{
				$default_project_expiry = fl_framework_get_options('project_default_expiry');
				$simple_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_project_expiry days"));
				update_post_meta($post_id, '_simple_projects_expiry_date', $simple_project_expiry_date);
			}

		}
		$is_featured_projects = get_post_meta($post_id, '_project_is_featured', true);
		if($is_featured_projects == 1)
		{

		}
		else
		{
			if(isset($params['project_featured']))
			{
				$featured_projects = get_post_meta($employer_id, '_featured_projects', true);
				if($featured_projects == -1)
				{
					update_post_meta( $post_id, '_project_is_featured', 1);
				}
				else if($featured_projects > 0 && $featured_projects != '')
				{

					$new_featured_project = $featured_projects - 1;
					update_post_meta($employer_id, '_featured_projects', $new_featured_project);
					update_post_meta( $post_id, '_project_is_featured', 1);
				}

				$featured_project_expiry_days = get_post_meta($employer_id, '_featured_project_expiry', true);
				if($featured_project_expiry_days == -1)
				{
					update_post_meta($post_id, '_featured_project_expiry_date', '-1');
				}
				else
				{
					if($featured_project_expiry_days > 0 && $featured_project_expiry_days != '')
					{
						$featured_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $featured_project_expiry_days days"));
						update_post_meta($post_id, '_featured_project_expiry_date', $featured_project_expiry_date);
					}
					else if($featured_project_expiry_days == '')
					{
						$default_featured_project_expiry = fl_framework_get_options('default_featured_project_expiry');
						$featured_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_featured_project_expiry days"));
						update_post_meta($post_id, '_featured_project_expiry_date', $featured_project_expiry_date);
					}
				}
			}
			else
			{
				update_post_meta( $post_id, '_project_is_featured', 0);
			}
		}

		$page_link = get_the_permalink($exertio_theme_options['user_dashboard_page'])."?ext=create-project&pid=".$post_id;
		$return = array('message' => esc_html__( 'Project posted successfully', 'exertio_framework' ),'pid' => $page_link);
		wp_send_json_success($return);
		die;
	}
}

	
function fl_pagination($wp_query) {
 
    if( is_singular() )
        //return;
 
    //global $wp_query;
 
    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;
 
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );
 
    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;
 
    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
 
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
 
    echo '<div class="fl-navigation"><ul>' . "\n";
 
    /** Previous Post Link */
    if ( get_previous_posts_link() )
        printf( '<li>%s</li>' . "\n", get_previous_posts_link('<i class="far fa-chevron-left"></i>') );
 
    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';
 
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );
 
        if ( ! in_array( 2, $links ) )
            echo '<li>…</li>';
    }
 
    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }
 
    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li>…</li>' . "\n";
        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }
 
    /** Next Post Link */
    if ( get_next_posts_link() )
        printf( '<li>%s</li>' . "\n", get_next_posts_link('<i class="far fa-chevron-right"></i>', $wp_query->max_num_pages) );
 
    echo '</ul></div>' . "\n";
 
}

/* EMPLOYER PROFILE PICTURE UPLOAD */
add_action('wp_ajax_upload_img_return_id', 'freelance_upload_img_return_id');

if ( ! function_exists( 'freelance_upload_img_return_id' ) ) 
{ 
	function freelance_upload_img_return_id()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		$pid = $_POST['post-id'];
		
		//$field_name = $_POST['field-name'];
		$field_name =  $_FILES[$_POST['field-name']];
		/* img upload */
	
		if(!empty($field_name))
		{
		
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			
			
			$files = $field_name;
			   
			$attachment_ids=array();
			$attachment_idss='';
			
			if($img_count>=1)
			{
			 $imgcount=$img_count;
			}
			else
			{
			 $imgcount=1;
			}
			$ul_con='';
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("upload_img_return_id" => $file); 
					
					
					// Allow certain file formats
					$imageFileType	=	end( explode('.', $file['name'] ) );
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
					{
						echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG files are allowed.", 'exertio_framework' );
						die();
					}
					
					// Check file size
					if ($file['size'] > 1300000) {
						echo '0|' . esc_html__( "Max allowd image size is 300KB", 'exertio_framework' );
						die();
					}

					foreach ($_FILES as $file => $array) 
					{              
					  
					 $attach_id = media_handle_upload( $file, $pid );
					  $attachment_ids[] = $attach_id; 
					
					  $image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					  
					}
				} 
			}
		} 
		/*img upload */
		$attachment_idss = array_filter( $attachment_ids  );
		$attachment_idss =  implode( ',', $attachment_idss );  
	
	
		$arr = array();
		$arr['attachment_idss'] = $attachment_idss;
		$arr['ul_con'] =$ul_con; 

		echo '1|'.esc_html__( "Image uploaded", 'exertio_framework' ).'|' . $image_link[0].'|'.$attach_id;
		 die();
	
	}
}


/* FREELANCER PROFILE SAVE */
add_action( 'wp_ajax_fl_profile_save', 'fl_profile_save' );
if ( ! function_exists( 'fl_profile_save' ) )
{ 
	function fl_profile_save() 
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_save_pro_secure', 'security' );
		$uid = get_current_user_id();
		$post_id = $_POST['post_id'];
		$params = array();
		
		parse_str($_POST['fl_data'], $params);

		$new_slug =  preg_replace('/\s+/', '', $params['fl_username']);
		
		
		$words = explode(',', $exertio_theme_options['bad_words_filter']);
        $replace = $exertio_theme_options['bad_words_replace'];
        $desc = fl_badwords_filter($words, $params['fl_desc'], $replace);
		
		global $exertio_theme_options;
			$my_post = array(
				'ID' => $post_id,
				'post_title' => sanitize_text_field($params['fl_username']),
				'post_name' => sanitize_text_field($new_slug),
				'post_content' => wp_kses($desc, exertio_allowed_html_tags()),
				'post_type' => 'freelancer'
			);
			
			$result = wp_update_post($my_post, true);
			
			if (is_wp_error($result))
			{
				$return = array('message' => esc_html__( 'Profile not saved. Please contact admin', 'exertio_framework' ));
				wp_send_json_error($return);
			}
		
		if(isset($params['freelancer_tagline']))
		{
			update_post_meta( $post_id, '_freelancer_tagline', sanitize_text_field($params['freelancer_tagline']));
		}
		if(isset($params['freelancer_hourly_rate']))
		{
			update_post_meta( $post_id, '_freelancer_hourly_rate', sanitize_text_field($params['freelancer_hourly_rate']));
		}
		
		if(isset($params['freelancer_dispaly_name']))
		{
			update_post_meta( $post_id, '_freelancer_dispaly_name', sanitize_text_field($params['freelancer_dispaly_name']));
		}
		
		if(isset($params['freelancer_contact_number']))
		{
			update_post_meta( $post_id, '_freelancer_contact_number', sanitize_text_field($params['freelancer_contact_number']));
		}
		if(isset($params['freelancer_gender']))
		{
			update_post_meta( $post_id, '_freelancer_gender', sanitize_text_field($params['freelancer_gender']));
		}
		
       if(isset($params['freelance_type']))
		{
			$company_employees_terms = array((int)$params['freelance_type']); 

			update_post_meta( $post_id, '_freelance_type', sanitize_text_field($params['freelance_type']));
			wp_set_post_terms( $post_id, $company_employees_terms, 'freelance-type', false );
		}
		
		if(isset($params['english_level']))
		{
			$english_level = array((int)$params['english_level']); 
			update_post_meta( $post_id, '_freelancer_english_level', sanitize_text_field($params['english_level']));
			wp_set_post_terms( $post_id, $english_level, 'freelancer-english-level', false );
		}
		
		if(isset($params['freelancer_language']))
		{
			$freelancer_language = array((int)$params['freelancer_language']); 

			update_post_meta( $post_id, '_freelancer_language', sanitize_text_field($params['freelancer_language']));
			wp_set_post_terms( $post_id, $freelancer_language, 'freelancer-languages', false );
		}

		if(isset($params['freelancer_location']))
		{
			update_post_meta( $post_id, '_freelancer_location', sanitize_text_field($params['freelancer_location']));
			set_hierarchical_terms('freelancer-locations', $params['freelancer_location'], $post_id);
		}

		if(isset($params['profile_attachment_ids']))
		{
			update_post_meta( $post_id, '_profile_pic_freelancer_id', sanitize_text_field($params['profile_attachment_ids']));
		}
		
		if(isset($params['banner_img_id']))
		{
			update_post_meta( $post_id, '_freelancer_banner_id', sanitize_text_field($params['banner_img_id']));
		}
		
		if(isset($params['fl_address']))
		{
			update_post_meta( $post_id, '_freelancer_address', sanitize_text_field($params['fl_address']));
		}
		
		if(isset($params['fl_lat']))
		{
			update_post_meta( $post_id, '_freelancer_latitude', sanitize_text_field($params['fl_lat']));
		}
		
		if(isset($params['fl_long']))
		{
			update_post_meta( $post_id, '_freelancer_longitude', sanitize_text_field($params['fl_long']));
		}
		
		if($exertio_theme_options['fl_skills'] == 2)
		{
			if(isset($params['freelancer_skills']))
			{
				$skill_name = array_unique($params['freelancer_skills']);
				$skill_percent = array_unique($params['skills_percent']);
				$integerIDs = array_map('intval', $params['freelancer_skills']);
				$integerIDs = array_unique($integerIDs);
				
				for($i=0; $i<count($skill_name); $i++)
				{
					$skill_id = sanitize_text_field($skill_name[$i]);
					
					$percent = sanitize_text_field($skill_percent[$i]);
					$skills[] = array(
						"skill" => $skill_id,
						"percent" =>$percent
					);
				}
				 $encoded_skills =  wp_json_encode($skills, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				 
				wp_set_post_terms( $post_id, $integerIDs, 'freelancer-skills', false );
				update_post_meta( $post_id, '_freelancer_skills', $encoded_skills );
				
			}
			else if($params['freelancer_skills'] == '')
			{
				wp_set_post_terms( $post_id, '', 'freelancer-skills', false );
				update_post_meta( $post_id, '_freelancer_skills', '' );
			}
		}
		if($exertio_theme_options['fl_awards'] == 2)
		{
			if(isset($params['award_name']) && isset($params['award_date']))
			{
				$award_name = $params['award_name'];
				$award_date = $params['award_date'];
				$awar_img = $params['award_img_id'];
				
				for($i=0; $i<count($award_name); $i++)
				{
					$name = sanitize_text_field($award_name[$i]);
					$date = sanitize_text_field($award_date[$i]);
					$img = sanitize_text_field($awar_img[$i]);
					$awards[] = array(
						"award_name" => $name,
						"award_date" =>$date,
						"award_img" =>$img,
					);
				}
				$encoded_awards =  wp_json_encode($awards, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

				update_post_meta( $post_id, '_freelancer_awards', $encoded_awards );
			}
			else if($params['award_name'] == '' && $params['award_date'] == '')
			{
				update_post_meta( $post_id, '_freelancer_awards', '' );	
			}
		}
		if($exertio_theme_options['fl_projects'] == 2)
		{
			if(isset($params['project_name']) && isset($params['project_url']))
			{
				$project_name = $params['project_name'];
				$project_url = $params['project_url'];
				$project_img = $params['project_img_id'];
				
				for($i=0; $i<count($project_name); $i++)
				{
					$name = sanitize_text_field($project_name[$i]);
					$date = sanitize_text_field($project_url[$i]);
					$img = sanitize_text_field($project_img[$i]);
					$projects[] = array(
						"project_name" => $name,
						"project_url" =>$date,
						"project_img" =>$img,
					);
				}
				$encoded_projects =  wp_json_encode($projects, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	
				update_post_meta( $post_id, '_freelancer_projects', $encoded_projects );
			}
			else if($params['project_name'] == '' && $params['project_url'] =='')
			{
				update_post_meta( $post_id, '_freelancer_projects', '' );
			}
		}
		if($exertio_theme_options['fl_experience'] == 2)
		{
			if(isset($params['expe_name']))
			{
				$expe_name = $params['expe_name'];
				$expe_company_name = $params['expe_company_name'];
				$expe_start_date = $params['expe_start_date'];
				$expe_end_date = $params['expe_end_date'];
				$expe_details = str_replace(array('\'', '"'), '', $params['expe_details']);
				
				for($i=0; $i<count($expe_name); $i++)
				{
					$name = sanitize_text_field($expe_name[$i]);
					$inst_name = sanitize_text_field($expe_company_name[$i]);
					$start_date = sanitize_text_field($expe_start_date[$i]);
					$end_date = sanitize_text_field($expe_end_date[$i]);
					$desc = sanitize_text_field($expe_details[$i]);
					$experience[] = array(
						"expe_name" => $name,
						"expe_company_name" =>$inst_name,
						"expe_start_date" =>$start_date,
						"expe_end_date" =>$end_date,
						"expe_details" =>$desc,
					);
				}
				$encoded_experience =  wp_json_encode($experience, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
	
				update_post_meta( $post_id, '_freelancer_experience', $encoded_experience );
			}
			else if($params['expe_name'] == '')
			{
				update_post_meta( $post_id, '_freelancer_experience', '' );
			}
		}
		if($exertio_theme_options['fl_education'] == 2)
		{
			if(isset($params['edu_name']))
			{
				$edu_name = $params['edu_name'];
				$edu_inst_name = $params['edu_inst_name'];
				$edu_start_date = $params['edu_start_date'];
				$edu_end_date = $params['edu_end_date'];
				$edu_desc = str_replace(array('\'', '"'), '', $params['edu_details']);
				
				for($i=0; $i<count($edu_name); $i++)
				{
					$name = sanitize_text_field($edu_name[$i]);
					$inst_name = sanitize_text_field($edu_inst_name[$i]);
					$start_date = sanitize_text_field($edu_start_date[$i]);
					$end_date = sanitize_text_field($edu_end_date[$i]);
					$desc = sanitize_text_field($edu_desc[$i]);
					$education[] = array(
						"edu_name" => $name,
						"edu_inst_name" =>$inst_name,
						"edu_start_date" =>$start_date,
						"edu_end_date" =>$end_date,
						"edu_details" =>$desc,
					);
				}
				$encoded_education = wp_json_encode($education, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
				update_post_meta( $post_id, '_freelancer_education', $encoded_education );
			}
			else if($params['edu_name'] == '')
			{
				update_post_meta( $post_id, '_freelancer_education', '' );
			}
		}
		$return = array('message' => esc_html__( 'Profile updated', 'exertio_framework' ));
		wp_send_json_success($return);
	}
}


add_action( 'wp_ajax_fl_addon_save', 'fl_addon_save' );
if ( ! function_exists( 'fl_addon_save' ) )
{ 
	function fl_addon_save() 
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_save_pro_secure', 'security' );
		$uid = get_current_user_id();
		$post_id = $_POST['post_id'];
		$params = array();
		
		parse_str($_POST['fl_data'], $params);
		global $exertio_theme_options;
		if($params['is_update'] != '')
		{
			$status = "publish";
			if(isset($exertio_theme_options['addons_update_approval']) &&  $exertio_theme_options['addons_update_approval'] == 0)
			{
				$status = "pending";
			}
		}
		else
		{
			$status = "publish";
			if(isset($exertio_theme_options['addons_approval']) &&  $exertio_theme_options['addons_approval'] == 0)
			{
				$status = "pending";
			}
		}

		$words = explode(',', $exertio_theme_options['bad_words_filter']);
        $replace = $exertio_theme_options['bad_words_replace'];
        $desc = fl_badwords_filter($words, $params['addon_desc'], $replace);
		$title = fl_badwords_filter($words, $params['addon_title'], $replace);
			$my_post = array(
				'ID' => $post_id,
				'post_title' => sanitize_text_field($title),
				'post_content' => wp_kses_post($desc),
				'post_type' => 'addons',
				'post_status'   => $status,
			);
			
			$result = wp_update_post($my_post, true);
			
			if (is_wp_error($result))
			{
				$return = array('message' => esc_html__( 'Addon not saved. Please contact admin', 'exertio_framework' ));
				wp_send_json_error($return);
			}
	
		if(isset($params['addon_price']))
		{
			update_post_meta( $post_id, '_addon_price', sanitize_text_field($params['addon_price']));
			
		}
		
		if($params['is_update'] == '')
		{
			update_user_meta( $uid, '_processing_addon_id', '' );
		}
		update_post_meta( $post_id, '_addon_status', 'active');
		$page_link = get_the_permalink($exertio_theme_options['user_dashboard_page'])."?ext=create-addon&aid=".$post_id;
		$return = array('message' => esc_html__( 'Addon Created', 'exertio_framework' ),'pid' => $page_link);
		wp_send_json_success($return);

	}
}

add_action('wp_ajax_fl_remove_addon', 'fl_remove_addon');
if ( ! function_exists( 'fl_remove_addon' ) ) { 
	function fl_remove_addon()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_gen_secure', 'security' );
		
		$ad_id		=	$_POST['pid'];

		if( wp_trash_post( $ad_id ) )
		{
			$return = array('message' => esc_html__( 'Addon removed successfully', 'exertio_framework' ));
			wp_send_json_success($return);
		}
		else
		{
			$return = array('message' => esc_html__( 'There is some problem, please try again later', 'exertio_framework' ));
			wp_send_json_error($return);
		}
	
		
		die();
	}
}


add_action('wp_ajax_services_attachments', 'freelance_services_attachments');
if ( ! function_exists( 'freelance_services_attachments' ) ) 
{ 
	function freelance_services_attachments()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		global $exertio_theme_options;
		$pid = $_POST['post-id'];
		$field_name =  $_FILES['services_attachments'];
		$condition_img=7;
		$attachment_size = '2000';
		$img_count = count(array_count_values($field_name['name']));

		if(isset($exertio_theme_options['sevices_attachment_count']))
		{
			$condition_img= $exertio_theme_options['sevices_attachment_count'];
		}
		
		if(isset($exertio_theme_options['services_attachment_size']))
		{
			$attachment_size= $exertio_theme_options['services_attachment_size'];
		}

		if(!empty($field_name))
		{
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			$files = $field_name;

			$files_array = array();
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("emp_profile_picture" => $file); 
					
						foreach ($_FILES as $file => $array) 
						{              
							$exist_data = get_post_meta( $pid, '_service_attachment_ids', true );
							
							$is_upload_file = true;
							$imageFileType	=	end( explode('.', $array['name'] ) );
							if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
							{
								$is_upload_file = false;
								$attach_id = 0;
								$message =  esc_html__( "Sorry, only JPG, JPEG, and PNG files are allowed.", 'exertio_framework' );
								
							}							
							else
							{
								
								$exist_data_count ='';
								if(isset($exist_data) && $exist_data != 0)
								{
									$exist_data_count = count(explode(",",$exist_data));
								}

								$is_upload_file = true;
								if($exist_data_count >= $condition_img)
								{
									$message = esc_html__( "Attachment upload limit reached", 'exertio_framework' );
									$is_upload_file = false;
									$attach_id = 0;
								}

								if($is_upload_file)
								{
								$is_upload_file = true;
								if ($array['size']/1000 > $attachment_size) {
									$is_upload_file = false;
									$attach_id = 0;
									$message = esc_html__( "Max allowd attachment size is ".$attachment_size.' Kb', 'exertio_framework' );
									
								}

								if($is_upload_file){
									
									$attach_id = media_handle_upload( $file, $pid );

									if( is_wp_error($attach_id ))
									{
										$is_upload_file = false;
										$message = $attach_id->get_error_message();
										$attach_id = 0;
									}
									else
									{								
										if(isset($exist_data) && $exist_data != 0)
										{
												$attach_id_store = $exist_data.','.$attach_id;
										}
										else
										{
											$attach_id_store = $attach_id;
										}
										update_post_meta( $pid, '_service_attachment_ids', $attach_id_store);
										$message = esc_html__( "File Uploaded", 'exertio_framework' );
									}	
								}
							}
							
							}
							$file_size_kb = $array['size']/1000;
							$icon = get_icon_for_attachment_type($array['type'], $attach_id);
					
							$files_array[] = array(
								'name' => $array['name'],
								'icon' => $icon,
								'file-size' => $file_size_kb,
								'message' => $message,
								'data-id' => $attach_id,
								'data-pid' => $pid,
								'is-error' => (isset($is_upload_file) && $is_upload_file == true) ? '':'upload-error',
							);
						}
				} 
			}
		}

		foreach($files_array as $arr){
			
			$data .= '<div class="attachments ui-state-default pro-atta-'.$arr['data-id'].' '.$arr['is-error'].'"> <img src="'.$arr['icon'].'" alt="'.get_post_meta($arr['data-id'], '_wp_attachment_image_alt', TRUE).'" data-img-id="'.$arr['data-id'].'"><span class="attachment-data"> <h4>'.$arr['name'].'<small class="'.$arr['is-error'].'">  - '. $arr['message'] .'</small> </h4> <p>'.esc_html__( "file size:", 'exertio_framework' ).'  '.$arr['file-size'].esc_html__( " Kb", 'exertio_framework' ).'</p> <a href="javascript:void(0)" class="btn_delete_services_attachment" data-id="'.$arr['data-id'].'" data-pid="'.$arr['data-pid'].'"> <i class="fal fa-times-circle"></i></a> </span></div>';
		}
		
		echo '1|'.esc_html__( "Attachments uploaded", 'exertio_framework' ).'|' .$data.'|'.$attach_id_store;
		die();
	}
}


if ( ! function_exists( 'freelance_services_attachments1' ) ) 
{ 
	function freelance_services_attachments1()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		global $exertio_theme_options;
		$pid = $_POST['post-id'];

		$field_name =  $_FILES['services_attachments'];

		$condition_img=7;

		$img_count = count(array_count_values($field_name['name']));
		
		
		if(isset($exertio_theme_options['sevices_attachment_count']))
		{
			$condition_img= $exertio_theme_options['sevices_attachment_count'];
		}
		
		if(isset($exertio_theme_options['services_attachment_size']))
		{
			$attachment_size= $exertio_theme_options['services_attachment_size'];
		}
				
		if(!empty($field_name))
		{
		
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			
			
			$files = $field_name;
			   
			$attachment_ids=array();
			$attachment_idss='';
			
			if($img_count>=1)
			{
			 $imgcount=$img_count;
			}
			else
			{
			 $imgcount=1;
			}
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("emp_profile_picture" => $file); 
					
					// Allow certain file formats
					$imageFileType	=	end( explode('.', $file['name'] ) );
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" )
					{
						echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG, files are allowed.", 'exertio_framework' );
						die();
					}
					
					// Check file size
					
					
					
					foreach ($_FILES as $file => $array) 
					{              
					  $exist_data = get_post_meta( $pid, '_service_attachment_ids', true );
					  
					  $exist_data_count ='';
						if(isset($exist_data) && $exist_data != 0)
						{
							$exist_data_count = count(explode(",",$exist_data));
						}
						if($exist_data_count >= $condition_img)
						{
							
							echo '0|'.esc_html__( "Attachments uploaded limit reached", 'exertio_framework' ).'|' .$data;
							die;
							break;
						}
						
						if ($array['size']/1000 > $attachment_size) {
							echo '0|' . esc_html__( "Max allowd attachment size is ".$attachment_size.' Kb', 'exertio_framework' );
							die();
							break;
						}

					 $attach_id = media_handle_upload( $file, $pid );
						if(is_wp_error($attach_id))
						{
							echo '0|' . esc_html__( "Sorry, this type of image/file are not allowed.", 'exertio_framework' );
							die();						}
						else
						{

							$attachment_ids[] = $attach_id; 

							$image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );

							$new_data = $attach_id;

							if(isset($exist_data) && $exist_data != 0)
							{
										$new_data = $exist_data.','.$attach_id;
							}
							update_post_meta( $pid, '_service_attachment_ids', $new_data);

							$icon = get_icon_for_attachment($attach_id);
							$data .= '<div class="attachments pro-atta-'.$attach_id.'"> <img src="'.$icon.'" alt="'.get_post_meta($attach_id, '_wp_attachment_image_alt', TRUE).'"><span class="attachment-data"> <h4>'.get_the_title($attach_id).' </h4> <p>'.esc_html__( " file size:", 'exertio_framework' ).' '.size_format(filesize(get_attached_file( $attach_id ))).'</p> <a href="javascript:void(0)" class="btn-pro-clsoe-icon" data-id="'.$attach_id.'" data-pid="'.$pid.'"> <i class="fal fa-times-circle"></i></a> </span></div>';
						}
					}
					$imgcount++;
				} 
			}
		} 
		if($exist_data_count < $condition_img)
		{
			echo '1|'.esc_html__( "Attachments uploaded", 'exertio_framework' ).'|' .$data.'|'.$new_data;
			die;
		}
	
	}
}

add_action('wp_ajax_delete_service_attachment', 'fl_delete_service_attachment');

if ( ! function_exists( 'fl_delete_service_attachment' ) ) 
{
	function fl_delete_service_attachment()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		$attachment_id = $_POST['attach_id'];
		$sid = $_POST['sid'];

		if($attachment_id !='' && $sid != '')
		{
			$exist_data = get_post_meta( $sid, '_service_attachment_ids', true );
			
			$array1 = array($attachment_id);
			$array2 = explode(',', $exist_data);
			$array3 = array_diff($array2, $array1);
			wp_delete_attachment($attachment_id);
			$new_data = implode(',', $array3);
			update_post_meta( $sid, '_service_attachment_ids', $new_data);
			$return = array('message' => esc_html__( 'Attachment deleted', 'exertio_framework' ), 'returned_ids' => $new_data);
			wp_send_json_success($return);
			
		}
		else
		{
			$return = array('message' => esc_html__( 'Error!!! attachment is not deleted', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
	}
}
add_action( 'wp_ajax_fl_service_save', 'fl_service_save' );
if ( ! function_exists( 'fl_service_save' ) )
{ 
	function fl_service_save() 
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_save_service_secure', 'security' );
		$uid = get_current_user_id();
		$post_id = $_POST['post_id'];
		$params = array();
		
		parse_str($_POST['fl_data'], $params);
		;
		update_post_meta( $post_id, '_service_attachment_ids', $params['services_attachment_ids']);
		$freelancer_id = get_user_meta( $uid, 'freelancer_id' , true );
		global $exertio_theme_options;
		if($params['is_update'] != '')
		{
			$status = "publish";
			if(isset($exertio_theme_options['service_update_approval']) &&  $exertio_theme_options['service_update_approval'] == 0)
			{
				$status = "pending";
			}
		}
		else
		{
			if(isset($exertio_theme_options['service_approval']) &&  $exertio_theme_options['service_approval'] == 0)
			{
				$status = "pending";
			}
			else
			{
				$service_status = get_post_status ( $post_id );
				if($service_status = 'publish')
				{
					$status = 'publish';
				}
			}
		}

		$words = explode(',', $exertio_theme_options['bad_words_filter']);
        $replace = $exertio_theme_options['bad_words_replace'];
        $desc = fl_badwords_filter($words, $params['services_desc'], $replace);
		$title = fl_badwords_filter($words, $params['services_title'], $replace);
			$my_post = array(
				'ID' => $post_id,
				'post_title' => sanitize_text_field($title),
				'post_content' => wp_kses_post($desc),
				'post_type' => 'services',
				'post_status'   => $status,
			);
			
			$result = wp_update_post($my_post, true);
			
			if (is_wp_error($result))
			{
				$return = array('message' => esc_html__( 'Data did not save. Please contact admin', 'exertio_framework' ));
				wp_send_json_error($return);
			}
	
		if(isset($params['service_price']))
		{
			update_post_meta( $post_id, '_service_price', sanitize_text_field($params['service_price']));
			
		}
		if(isset($params['response_time']))
		{
			$response_terms = array((int)$params['response_time']); 
			update_post_meta( $post_id, '_response_time', sanitize_text_field($params['response_time']));
			wp_set_post_terms( $post_id, $response_terms, 'response-time', false );
			
		}
		if(isset($params['delivery_time']))
		{
			$delivery_terms = array((int)$params['delivery_time']); 
			update_post_meta( $post_id, '_delivery_time', sanitize_text_field($params['delivery_time']));
			wp_set_post_terms( $post_id, $delivery_terms, 'delivery-time', false );
			
		}
		if(isset($params['english_level']))
		{
			$service_english_level_term = array((int)$params['english_level']); 
			update_post_meta( $post_id, '_service_eng_level', sanitize_text_field($params['english_level']));
			wp_set_post_terms( $post_id, $service_english_level_term, 'services-english-level', false );
			
		}

		if(isset($params['service_location']))
		{
			update_post_meta( $post_id, '_service_location', sanitize_text_field($params['service_location']));
			set_hierarchical_terms('services-locations', $params['service_location'], $post_id);
		}
		if(isset($params['service_category']))
		{
			update_post_meta( $post_id, '_service_category', sanitize_text_field($params['service_category']));
			set_hierarchical_terms('service-categories', $params['service_category'], $post_id);
		}
		if(isset($params['services_address']))
		{
			update_post_meta( $post_id, '_service_address', sanitize_text_field($params['services_address']));
		}
		if(isset($params['services_lat']))
		{
			update_post_meta( $post_id, '_service_latitude', sanitize_text_field($params['services_lat']));
		}
		if(isset($params['services_long']))
		{
			update_post_meta( $post_id, '_service_longitude', sanitize_text_field($params['services_long']));
		}
		if(isset($params['video_urls']) && $params['video_urls'] != '')
		{
			if($params['video_urls'] !='')
			{
				$video_urls = $params['video_urls'];
				$urls = json_encode($video_urls);
				update_post_meta( $post_id, '_service_youtube_urls', sanitize_text_field($urls));
			}
		}
		else
		{
			update_post_meta( $post_id, '_service_youtube_urls', '');
		}
		if(isset($params['faqs-title']) && $params['faqs-title'] != '')
		{
			$faq_title = $params['faqs-title'];
			$faq_answer = $params['faq-answer'];

			for($i=0; $i<count($faq_title); $i++)
			{
				$title = sanitize_text_field($faq_title[$i]);
				$answer = sanitize_text_field($faq_answer[$i]);
				$faqs[] = array(
					"faq_title" => $title,
					"faq_answer" =>$answer,
				);
			}
			$encoded_faqs =  json_encode($faqs);
			update_post_meta( $post_id, '_service_faqs', $encoded_faqs );
		}
		else
		{
			update_post_meta( $post_id, '_service_faqs', '');
		}
		if(isset($params['services_addon']))
		{
			$services_addon = $params['services_addon'];
			
			for($i=0; $i<count($services_addon); $i++)
			{
				$name = sanitize_text_field($services_addon[$i]);
				$addon[] = $name;
			}
			$encoded_addon =  json_encode($addon);
			update_post_meta( $post_id, '_services_addon', $encoded_addon );
		}
		
		if(isset($params['is_show_service_attachments']) && $params['is_show_service_attachments'] == 'yes')
		{
			update_post_meta( $post_id, '_service_attachment_show', 'yes');
		}
		else
		{
			update_post_meta( $post_id, '_service_attachment_show', 'no');
		}
		
		
		if($params['is_update'] == '')
		{
			update_user_meta( $uid, '_processing_services_id', '' );
		}
		$status = get_post_meta($post_id, '_service_status', true);
		if($status == 'cancel')
		{
			
		}
		else
		{
			update_post_meta( $post_id, '_service_status', 'active');
		}
		$c_dATE = DATE("d-m-Y");
		if($params['is_update'] == '')
		{

			$is_service_paid = fl_framework_get_options('is_services_paid');
			$simple_service = get_post_meta($freelancer_id, '_simple_services', true);
			if(isset($simple_service) && $simple_service != -1 || $is_service_paid == 1)
			{
				if($simple_service != -1)
				{
					$new_simple_service = $simple_service - 1;	
					update_post_meta($freelancer_id, '_simple_services', $new_simple_service);
				}
			}
			
			$simple_service_expiry_days = get_post_meta($freelancer_id, '_simple_service_expiry', true);
			if($simple_service_expiry_days == -1)
			{
				update_post_meta($post_id, '_simple_service_expiry_date', -1);
			}
			else
			{
				if($simple_service_expiry_days != '' && $simple_service_expiry_days > 0 )
				{
					$simple_service_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $simple_service_expiry_days days"));
					
					update_post_meta($post_id, '_simple_service_expiry_date', $simple_service_expiry_date);
				}
				else if($simple_service_expiry_days == '')
				{
					$default_service_expiry = fl_framework_get_options('service_default_expiry');
					$simple_service_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_service_expiry days"));
					update_post_meta($post_id, '_simple_service_expiry_date', $simple_service_expiry_date);
				}
			}
		}

		$is_featured_service = get_post_meta($post_id, '_service_is_featured', true);
		if($is_featured_service == 1)
		{
			
		}
		else
		{
			if(isset($params['featured_service']))
			{
				$featured_services = get_post_meta($freelancer_id, '_featured_services', true);
				if($featured_services == -1)
				{
					update_post_meta( $post_id, '_service_is_featured', 1);
				}
				else if($featured_services > 0 && $featured_services != '')
				{
					$new_featured_service = $featured_services - 1;
					update_post_meta($freelancer_id, '_featured_services', $new_featured_service);
					update_post_meta( $post_id, '_service_is_featured', 1);
				}
				
				$featured_services_expiry_days = get_post_meta($freelancer_id, '_featured_services_expiry', true);
				if($featured_services_expiry_days == -1)
				{
					update_post_meta($post_id, '_featured_service_expiry_date', '-1');
				}
				else
				{
					if($featured_services_expiry_days > 0 && $featured_services_expiry_days != '')
					{
						$featured_service_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $featured_services_expiry_days days"));
						update_post_meta($post_id, '_featured_service_expiry_date', $featured_service_expiry_date);
					}
					else if($featured_services_expiry_days == '')
					{
						$default_featured_service_expiry = fl_framework_get_options('default_featured_service_expiry');
						$featured_service_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_featured_service_expiry days"));
						update_post_meta($post_id, '_featured_service_expiry_date', $featured_service_expiry_date);
					}
				}
			}
			else
			{
				update_post_meta( $post_id, '_service_is_featured', 0);
			}
		}
		
		$selected_reference = '';
		if(isset($post_id) && $post_id !="")
		{
			$selected_reference = fl_framework_get_options('fl_service_id');
			if(isset($selected_reference) && $selected_reference !="")
			{
				$updated_id = preg_replace( '/{ID}/', $post_id, $selected_reference );
				update_post_meta($post_id, '_service_ref_id', sanitize_text_field($updated_id));
			}
			else
			{
				update_post_meta($post_id, '_service_ref_id', $post_id);
			}
		}

		$page_link = get_the_permalink($exertio_theme_options['user_dashboard_page'])."?ext=add-services&sid=".$post_id;
		if($params['is_update'] == '')
		{
			$return = array('message' => esc_html__( 'New service has been created', 'exertio_framework' ),'pid' => $page_link);
		}
		else
		{
			$return = array('message' => esc_html__( 'Service updated', 'exertio_framework' ),'pid' => $page_link);
		}
		wp_send_json_success($return);

	}
}


/* CANCEL SERVICE*/

add_action('wp_ajax_fl_cancel_service', 'fl_cancel_service');

if ( ! function_exists( 'fl_cancel_service' ) ) 
{
	function fl_cancel_service()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		
		check_ajax_referer( 'fl_gen_secure', 'security' );
		$pid = $_POST['pid'];
		$status = $_POST['status'];
		if( $pid != '' && $status != '')
		{
			update_post_meta( $pid, '_service_status', $status);
			if($status == 'remove')
			{
				if( wp_trash_post( $pid ) )
				{
					$return = array('message' => esc_html__( 'Service removed', 'exertio_framework' ));
					wp_send_json_success($return);
				}
				else
				{
					$return = array('message' => esc_html__( 'Error!!! please contact Admin', 'exertio_framework' ));
					wp_send_json_error($return);	
				}
			}
			if($status == 'active')
			{
				$return = array('message' => esc_html__( 'Service Activated', 'exertio_framework' ),);
			}
			else
			{
				$return = array('message' => esc_html__( 'Service Canceled', 'exertio_framework' ));
			}
			wp_send_json_success($return);
		}
		else
		{
			$return = array('message' => esc_html__( 'Error!!! please contact Admin', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
	}
}

add_action('wp_ajax_fl_place_bid', 'fl_place_bid');
add_action( 'wp_ajax_nopriv_fl_place_bid', 'fl_place_bid' );
if ( ! function_exists( 'fl_place_bid' ) ) 
{
	function fl_place_bid()
	{
		if(is_user_logged_in())
		{
			/*DEMO DISABLED*/
			exertio_demo_disable('json');
		
			$today_date = date("d-m-Y");
			$pid = $_POST['post_id'];
			
			$project_expiry = get_post_meta($pid, '_simple_projects_expiry_date', true);
			if(strtotime($today_date) > strtotime($project_expiry))
			{
				$return = array('message' => esc_html__( 'Project Already Expired', 'exertio_framework' ));
				wp_send_json_error($return);
			}
			else
			{
				$post	=	get_post($pid);
				$current_user_id = get_current_user_id();
				$freelancer_id = get_user_meta( $current_user_id, 'freelancer_id' , true );
				$author_id = get_user_meta( $post->post_author, 'employer_id' , true );


				$freelancer_package_expiry_date = get_post_meta($freelancer_id, '_freelancer_package_expiry_date', true);
				if(isset($freelancer_package_expiry_date) && strtotime($freelancer_package_expiry_date) < strtotime($today_date))
				{
						$return = array('message' => esc_html__( 'Please purchase package to send proposal', 'exertio_framework' ));
						wp_send_json_error($return);
				}
				$project_status = get_post_meta( $pid, '_project_status', true );
				if(isset($project_status) && $project_status == 'expired')
				{
						$return = array('message' => esc_html__( 'Project is expired', 'exertio_framework' ));
						wp_send_json_error($return);
				}
				$project_crdeits = get_post_meta($freelancer_id, '_project_credits', true);
				if(isset($project_crdeits) && $project_crdeits > 0 ||  $project_crdeits == -1)
				{
					global $exertio_theme_options;
					check_ajax_referer( 'fl_gen_secure', 'security' );

					if($current_user_id != $post->post_author)
					{
						global $wpdb;
						$table = EXERTIO_PROJECT_BIDS_TBL;
						$query = "SELECT id FROM ".$table." WHERE `freelancer_id` = '" . $freelancer_id . "' AND `project_id` = '" . $pid . "'";
						$result = $wpdb->get_results($query);
						if(empty($result))
						{
							parse_str($_POST['bid_data'], $params);
							$p_charges = $exertio_theme_options['project_charges'];
							$admin_charges = $params['bid_price']/100*$p_charges;
							$earning = $params['bid_price'] - $admin_charges;

							$addon_status = array();

							$is_top = $is_sealed = $is_featured = 0;
							$top_bid_charges = $sealed_bid_charges = $featured_bid_charges = '';
							//this party of code should be deleted
							if(isset($params['top_bid']) || isset($params['sealed_bid']) || isset($params['featured_bid']))
							{
								$wallet_amount = get_user_meta( $current_user_id, '_fl_wallet_amount', true );
								if(isset($params['top_bid']))
								{
									$top_bid_charges = $exertio_theme_options['project_top_addon_price'];
									$is_top	= '1';

								}
								if(isset($params['sealed_bid']))
								{
									$sealed_bid_charges = $exertio_theme_options['project_sealed_addon_price'];
									$is_sealed	= '1';
								}
								if(isset($params['featured_bid']))
								{
									$featured_bid_charges = $exertio_theme_options['project_featured_addon_price'];
									$is_featured	= '1';
								}
								/*BID CHARGES DEDUCTION*/
								$bid_total_charges = $top_bid_charges+$sealed_bid_charges+$featured_bid_charges;

								if($bid_total_charges > $wallet_amount)
								{
									$return = array('message' => esc_html__( 'Please load balance in your wallet', 'exertio_framework' ));
									wp_send_json_error($return);
								}
								else
								{
									$new_wallet_amount = $wallet_amount - $bid_total_charges;
									update_user_meta( $current_user_id, '_fl_wallet_amount', $new_wallet_amount);
								}
							}
							$addon_status = $params['sealed_bid'];
							$current_time = current_time('mysql');


							$data = array(
										'timestamp' => $current_time,
										'updated_on' =>$current_time,
										'project_id' => $pid,
										'proposed_cost' => sanitize_text_field($params['bid_price']),
										'service_fee' => sanitize_text_field($admin_charges),
										'earned_cost' => sanitize_text_field($earning),
										'day_to_complete' => sanitize_text_field($params['bid_days']),
										'cover_letter' => sanitize_text_field($params['bid_textarea']),
										'freelancer_id' => $freelancer_id,
										'author_id' => $author_id,
										'is_top' => $is_top,
										'is_sealed' => $is_sealed,
										'is_featured' => $is_featured,
										);

							$wpdb->insert($table,$data);
							$bid_id = $wpdb->insert_id;
							if($bid_id)
							{
								if(isset($project_crdeits) && $project_crdeits == -1 )
								{ }
								else
								{
									$new_project_crdeits = $project_crdeits - 1;
									update_post_meta( $freelancer_id, '_project_credits', $new_project_crdeits);
								}
								/*EMAIL ON PROPOSAL SENT*/
								if(fl_framework_get_options('fl_email_project_proposal') == true)
								{
									fl_project_proposal_email($post->post_author,$pid);
								}
								$return = array('message' => esc_html__( 'Proposal sent successfully', 'exertio_framework' ));
								wp_send_json_success($return);
							}

							else
							{
								$return = array('message' => esc_html__( 'Error!!! please contact Admin', 'exertio_framework' ));
								wp_send_json_error($return);	
							}
						}
						else
						{
							$return = array('message' => esc_html__( 'You have already sent a proposal.', 'exertio_framework' ));
							wp_send_json_error($return);
						}


					}
					else
					{
						$return = array('message' => esc_html__( 'You can not send a proposal to your project', 'exertio_framework' ));
						wp_send_json_error($return);	
					}
				}
				else
				{
					$return = array('message' => esc_html__( 'Please purchase package to send proposal', 'exertio_framework' ));
					wp_send_json_error($return);	
				}
			}
		}
		else
		{
			$return = array('message' => esc_html__( 'Please login first to send a proposal', 'exertio_framework' ));
			wp_send_json_error($return);	
		}
		
	}
}

if ( ! function_exists( 'get_project_bids' ) ) 
{
	function get_project_bids($pid = '', $start_from = 0, $limit = 10)
	{
			global $wpdb;
			$table = EXERTIO_PROJECT_BIDS_TBL;
			if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table)
			{
				$query = "SELECT * FROM ".$table." WHERE `project_id` = '" . $pid . "' ORDER BY `is_top` DESC, `timestamp` DESC LIMIT ".$start_from.",".$limit."";
				$result = $wpdb->get_results($query);
				if($result)
				{
					return $result;
				}
			}
	}
}

if ( ! function_exists( 'project_awarded' ) ) 
{
	function project_awarded($pid = '', $fl_id = '' )
	{
		global $wpdb;
		$table = EXERTIO_PROJECT_BIDS_TBL;
		if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table)
		{
			$query = "SELECT * FROM ".$table." WHERE `project_id` = '" . $pid . "' AND `freelancer_id` ='".$fl_id."'";
			$result = $wpdb->get_results($query);
			if($result)
			{
				return $result;
			}
		}
	}
}
// Most Viewed Listings
if ( ! function_exists( 'exertio_fetch_most_viewed_listings' ) )
{	 
	function exertio_fetch_most_viewed_listings($owner_id, $post_type = 'projects', $key = 'project', $most_viewed = false, $todays_trending = false)
	{
		$order_by = 'date';
		if ($most_viewed == true) 
		{
             $order_by = 'exertio_'.$key.'_singletotal_views';
        }
		
		$args	=	array
			(
				'post_type' => $post_type,
				'author' => $owner_id,
				//'post_status' => 'publish',
				'posts_per_page' => 5,
                'fields' => 'ids',
				'meta_key' => $order_by,
				'order'=> 'DESC',
				'orderby' => 'meta_value_num',
				'meta_query'    => array( )
			);
			return $args;
	}
}
add_action( 'wp_ajax_fl_verification_save', 'fl_verification_save' );
if ( ! function_exists( 'fl_verification_save' ) )
{ 
	function fl_verification_save() 
	{
		
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		check_ajax_referer( 'fl_save_verification_secure', 'security' );
		$uid = get_current_user_id();
		$params = array();
		
		parse_str($_POST['fl_verification_data'], $params);

		global $exertio_theme_options;
		
		$status = "pending";

			$my_post = array(
				'post_author'   => $uid,
				'post_title' => sanitize_text_field($params['name']),
				'post_type' => 'verification',
				'post_status'   => $status,
			);
			
			$result = wp_insert_post($my_post, true);
			
			if (is_wp_error($result))
			{
				$return = array('message' => esc_html__( 'Verification document did not sent', 'exertio_framework' ));
				wp_send_json_error($return);
			}
	
		if(isset($params['contact_number']))
		{
			update_post_meta( $result, '_verification_contact', sanitize_text_field($params['contact_number']));
		}
		if(isset($params['verification_number']))
		{
			update_post_meta( $result, '_verification_number', sanitize_text_field($params['verification_number']));
		}
		if(isset($params['address']))
		{
			update_post_meta( $result, '_verification_address', sanitize_text_field($params['address']));
		}
		if(isset($params['attachment_id']))
		{
			update_post_meta( $result, '_attachment_doc_id', sanitize_text_field($params['attachment_id']));
		}
		
		
		update_user_meta($uid,'_identity_verification_Sent', 1);
		$page_link = get_the_permalink($exertio_theme_options['user_dashboard_page'])."?ext=identity-verification";
		$return = array('message' => esc_html__( 'Verification detail sent', 'exertio_framework' ),'pid' => $page_link);
		wp_send_json_success($return);

	}
}

add_action( 'wp_ajax_fl_revoke_verification', 'fl_revoke_verification' );
if ( ! function_exists( 'fl_revoke_verification' ) )
{ 
	function fl_revoke_verification() 
	{
		global $exertio_theme_options;
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		$uid = get_current_user_id();
		if(isset($uid) && $uid != '')
		{
			$args = array(
			 'post_type' => 'verification',
			 'post_status' => 'all',
			 'posts_per_page' => -1,
			 'author' => $uid
			  );

			$current_user_posts = get_posts( $args );
			foreach ( $current_user_posts as $current_user_post )
			{
				wp_delete_post( $current_user_post->ID, true); 
			}
			update_user_meta($uid,'_identity_verification_Sent', 0);
			$fid = get_user_meta( $uid, 'freelancer_id' , true );
			$emp_id = get_user_meta( $uid, 'employer_id' , true );
			
			update_post_meta( $fid, '_is_freelancer_verified', 0);
			update_post_meta( $emp_id, '_is_employer_verified', 0);
			
			$page_link = get_the_permalink($exertio_theme_options['user_dashboard_page'])."?ext=identity-verification";
			$return = array('message' => esc_html__( 'verification detail sent', 'exertio_framework' ),'pid' => $page_link);
			wp_send_json_success($return);
		}
		else
		{
			$return = array('message' => esc_html__( 'Verification document did not revoke', 'exertio_framework' ));
			wp_send_json_error($return);
		}
	}
}

add_action( 'wp_ajax_verification_doc', 'verification_doc' );
if ( ! function_exists( 'verification_doc' ) ) 
{ 
	function verification_doc()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('echo');
		
		global $exertio_theme_options;
		
		$field_name =  $_FILES[$_POST['field-name']];

		/* img upload */
		$condition_img=7;
		$img_count = count((array) explode( ',',$_POST["image_gallery"] )); 
	
		if(!empty($field_name))
		{
		
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
			
			
			$files = $field_name;
			   
			$attachment_ids=array();
			$attachment_idss='';
			
			if($img_count>=1)
			{
			 $imgcount=$img_count;
			}
			else
			{
			 $imgcount=1;
			}
			foreach ($files['name'] as $key => $value) 
			{            
				if ($files['name'][$key]) 
				{ 
					
					$file = array( 
					 'name' => $files['name'][$key],
					 'type' => $files['type'][$key], 
					 'tmp_name' => $files['tmp_name'][$key], 
					 'error' => $files['error'][$key],
					 'size' => $files['size'][$key]
					); 
					
					$_FILES = array ("emp_profile_picture" => $file); 
					
					// Allow certain file formats
					$imageFileType	=	end( explode('.', $file['name'] ) );
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
					{
						echo '0|' . esc_html__( "Sorry, only JPG, JPEG, PNG files are allowed.", 'exertio_framework' );
						die();
					}
					
					// Check file size
					$image_size = $exertio_theme_options['user_attachment_size'];
					if ($file['size']/1000 > $image_size) {
						echo '0|' . esc_html__( "Max allowd image size is ".$image_size." KB", 'exertio_framework' );
						die();
					}
					
					foreach ($_FILES as $file => $array) 
					{              
					  
					  if($imgcount>=$condition_img){ break; } 
					 $attach_id = media_handle_upload( $file, $pid );
					  $attachment_ids[] = $attach_id; 
					
					  $image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					  
					}
					if($imgcount>$condition_img){ break; } 
					$imgcount++;
				} 
			}
		} 
		/*img upload */
		$attachment_idss = array_filter( $attachment_ids  );
		$attachment_idss =  implode( ',', $attachment_idss );  
	 
	
		$arr = array();
		$arr['attachment_idss'] = $attachment_idss;

		echo '1|'.esc_html__( "Image changed Successfully", 'exertio_framework' ).'|' . $image_link[0].'|'.$attach_id;
		 die();
	
	}
}
// REPORT FEATURE
add_action( 'wp_ajax_nopriv_fl_report_call_back', 'fl_report_call_back' );
add_action( 'wp_ajax_fl_report_call_back', 'fl_report_call_back' );
if (!function_exists ( 'fl_report_call_back' ))
{
	function fl_report_call_back()
	{
		/*DEMO DISABLED*/
		exertio_demo_disable('json');
		if(is_user_logged_in())
		{
			$post_id = intval($_POST['post_id']);
			$c_user_id = get_current_user_id();
			if( get_post_meta( $post_id, '_post_report_id_'.$c_user_id, true ) == $c_user_id )
			{
				$return = array('message' => esc_html__( 'You have already reported', 'exertio_framework' ));
				wp_send_json_error($return);
			}
			else
			{
				check_ajax_referer( 'fl_report_secure', 'security' );



				$params = array();
				parse_str($_POST['report_data'], $params);

				$status = "publish";
				$my_post = array(
					'post_title' => sanitize_text_field(get_the_title($post_id)),
					'post_content' => sanitize_textarea_field($params['report_desc']),
					'post_type' => 'report',
					'post_status'   => $status,
				);

				$result = wp_insert_post($my_post, true);

				if (is_wp_error($result))
				{
					$return = array('message' => esc_html__( 'Error while reporting. Please contact Admin', 'exertio_framework' ));
					wp_send_json_error($return);
				}
				else if(!is_wp_error($result))
				{
					if(isset($params['report_category']))
					{
						$report_category = array((int)$params['report_category']); 
						update_post_meta( $result, '_report_category', sanitize_text_field($params['report_category']));
						wp_set_post_terms( $result, $report_category, 'report-category', false );
					}
					update_post_meta($result, '_reported_pid', $post_id);
					update_post_meta($result, '_reported_post_type', get_post_type($post_id));
					
					update_post_meta( $post_id, '_post_report_id_'.$c_user_id, $c_user_id );

					$is_reported = get_post_meta($post_id,'_is_reported', true);
					if(isset($is_reported ) && $is_reported  != '' &&  $is_reported > 0)
					{
						$is_reported = $is_reported  + 1;
						update_post_meta($post_id, '_is_reported', $is_reported );
					}
					else
					{
						update_post_meta($post_id, '_is_reported', 1 );
					}
					$return = array('message' => esc_html__( 'Reported successfully', 'exertio_framework' ));
					wp_send_json_success($return);
					die();
				}
			}
		}
		else
		{
			$return = array('message' => esc_html__( 'Please login to report', 'exertio_framework' ));
			wp_send_json_error($return);
		}
		
	}
}


add_action( 'transition_post_status', 'exertio_post_published_hook', 10, 3 );
function exertio_post_published_hook( $new_status, $old_status, $post )
{
		$post_type = $post->post_type;
		$user_id = $post->post_author;
		$post_id = $post->ID;

		
		if($post_type == 'projects' && 'publish' == $new_status)
		{
			if(fl_framework_get_options('fl_email_onproject_created') == true)
			{
				fl_project_post_email($user_id,$post_id);
			}
		}
		if($post_type == 'services' && 'publish' == $new_status)
		{
			if(fl_framework_get_options('fl_email_onservice_created') == true)
			{
				fl_service_post_email($user_id,$post_id);
			}
		}
		if($post_type == 'payouts' && 'publish' == $new_status)
		{
			if(fl_framework_get_options('fl_email_payout_processed') == true)
			{
				fl_payout_processed_email($user_id);
			}
		}
		if($post_type == 'verification' && 'publish' == $new_status)
		{
			if(fl_framework_get_options('fl_email_identity_verify') == true)
			{
				fl_identity_verify_email($user_id);
			}
		}
}