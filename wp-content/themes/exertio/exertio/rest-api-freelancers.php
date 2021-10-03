<?php 

    function freelancersTop(){
			global $exertio_theme_options;
        $freelancer_type = "featured";
        $featured = "";

        if ( $freelancer_type == 'featured' ) {
			$featured = array(
			  'key' => '_freelancer_is_featured',
			  'value' => '1',
			  'compare' => '=',
			);
			} else if ( $freelancer_type == 'simple' ) {
			$featured = array(
			  'key' => '_freelancer_is_featured',
			  'value' => '0',
			  'compare' => '=',
			);
		}
        $args = array(
			'author__not_in' => array( 1 ),
			'post_type' => 'freelancer',
			'post_status' => 'publish',
			'posts_per_page' => 6,
			'orderby' => 'date',
			'order' => 'ASC',
			'meta_query' => array(
			  $featured,
			),
        );
        
				$results = new WP_Query( $args);
				$customToReturn = [];
				foreach($results->posts as $post)
				{
					$author_id = get_post_field( 'post_author', $post->ID );
					$fid = get_user_meta( $author_id, 'freelancer_id' , true );
					$freelancer['id'] = intval($post->post_author) ;					
					$freelancer['freelancer-name'] = exertio_get_username('freelancer', $fid);
					$freelancer['tagline'] = get_post_meta( $post->ID, '_freelancer_tagline' , true );
				  $freelancer['description'] = strip_tags($post->post_content); 
					$pro_img_id = get_post_meta( $fid, '_profile_pic_freelancer_id', true );
					$pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
				  $saved_skills = json_decode( stripslashes( get_post_meta( $post->ID, '_freelancer_skills', true ) ), true );
					$skill_count = 0;
					$freelancer['skills'] = [];
					$freelancer["member-since"] = $post->post_date;
					foreach ( $saved_skills as $skills ) {
				
						$skillsObject = get_term_by( 'id', $skills[ 'skill' ], 'freelancer-skills' );
					 if(!empty($skillsObject) && ! is_wp_error($skillsObject))
					 {
						
						if ( $skill_count > 3 ) {
							$skill_hide = 'hide';
						} else{
							$freelancer['skills'][$skill_count]['name'] = $skillsObject->name; 
						  $skillsTermName = $skillsObject->name;
						}
	
						
						$skill_count++;
					 }
					}
					/* + */
					
					if(wp_attachment_is_image($pro_img_id)){
							$freelancer['freelance-photo-profile'] = esc_url($pro_img[0]);
					} else {
							$freelancer['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
					}
					$freelancer['freelance-photo-profile'] = get_profile_img( $post->ID, 'freelancer' );
					$img_size = '';
			if(isset($exertio_theme_options) && $exertio_theme_options != '')
			{
				$profile_img_url = $exertio_theme_options['freelancer_df_img']['url'];
			}
			else
			{
				$profile_img_url = get_template_directory_uri().'/images/emp_default.jpg';
			}
			$pro_img_id = get_post_meta( $post->ID, '_profile_pic_freelancer_id', true );
			if($img_size == '')
			{
				$img_size = 'thumbnail';
			}

			$pro_img = wp_get_attachment_image_src( $pro_img_id, $img_size );
			if(wp_attachment_is_image($pro_img_id))
			{
				
				$freelancer['freelance-photo-profile'] = esc_url($pro_img[0]);
			}
			else
			{
				$freelancer['freelance-photo-profile'] = esc_url($profile_img_url);
			}
			$freelancer['is_verified'] = userVerificationStatus($author_id);
			$freelancer['reviews'] = "0 reviews";
					array_push($customToReturn,$freelancer);

			}
				
				return new WP_REST_Response($customToReturn); 

    }

	function idVerificationProccess(WP_REST_Request $request){
		global $exertio_theme_options;
		
		$files = $request->get_file_params();
		
		$params = $request->get_params();
		
		$user_id = $params['user_id'];

		$user_data = get_user_meta(($user_id)); 

        /*print_r($params['user_id']);*/
		/*print_r(get_user_meta(($user_id)));*/
		$pid = intval($user_data['freelancer_id'][0]);
		
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
    	require_once( ABSPATH . 'wp-admin/includes/file.php' );
    	require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
		/*print_r($files);
		die();*/
		if(count($files)){

		}

		foreach($files as $key =>$value){
			if($value["name"] !== null){
			    $file = array(
					'name' => $value['name'],
					'type' => $value['type'],
					'tmp_name' => $value['tmp_name'],
					'error' => $value['error'],
					'size' => $value['size']
				);
				$_FILES = array ("emp_profile_picture" => $file);

				$image_size = $exertio_theme_options['user_attachment_size'];

				if($file['size']/1000 > $image_size){
					return new WP_Error(
						500,
						'La taille maximale du fichier'.$image_size.' KB',
						'no'
					);

				}

				foreach ($_FILES as $file => $array) 
				{              
					
					/*if($imgcount>=$condition_img){ break; }*/ 
					$attach_id = media_handle_upload( $file, $pid );
					$attachment_ids[] = $attach_id; 
				
					$image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					
				}
				

				//$attach_id = media_handle_upload($file, )

				

			}
			
			//die();
		}

		$status = "pending";

		$verification_post = array(
			'post_author' => $user_id,
			'post_title' => sanitize_text_field($params['name']),
			'post_type' => 'verification',
			'post_status'   => $status,

		);

		$result = wp_insert_post($verification_post, true);

		if(is_wp_error($result)){
			$return = array('message' => esc_html__( 'Verification document did not sent', 'exertio_framework' )); 
			wp_send_json_error($return);
		}

		if($params['contact_number'] !== null)
		{
			update_post_meta( $result, '_verification_contact', sanitize_text_field($params['contact_number']));
		}
		if($params['verification_number'] !==null)
		{
			update_post_meta( $result, '_verification_number', sanitize_text_field($params['verification_number']));
		}
		if($params['address'] !== null)
		{
			update_post_meta( $result, '_verification_address', sanitize_text_field($params['address']));
		}
		update_post_meta( $result, '_attachment_doc_id', $attach_id);
		update_user_meta($user_id,'_identity_verification_Sent', 1);

		$return = array('message' => esc_html__( 'Les informations de vérification envoyées avec succès', 'exertio_framework' ));
		wp_send_json_success($return);		
		//die();

	}

	function revoke_verification(WP_REST_Request $request){
		global $exertio_theme_options;
		$user_id = $request->get_param('user_id');

		if($user_id !==null){
			$args = array(
				'post_type' => 'verification',
				'post_status' => 'all',
				'posts_per_page' => -1,
				'author' => $user_id
				 );

			$current_user_posts = get_posts($args);

			foreach($current_user_posts as $current_user_post){
				wp_delete_post($current_user_post->ID);
			}
			update_user_meta($user_id,'_identity_verification_Sent', 0);
			$fid = get_user_meta( $user_id, 'freelancer_id' , true );
			$emp_id = get_user_meta( $user_id, 'employer_id' , true );

			update_post_meta( $fid, '_is_freelancer_verified', 0);
			update_post_meta( $emp_id, '_is_employer_verified', 0);
			$return = array('message' => esc_html__( 'verification detail sent', 'exertio_framework' ));
			wp_send_json_success($return);
		}
		else
		{
			$return = array('message' => esc_html__( 'Verification document did not revoke', 'exertio_framework' ));
			wp_send_json_error($return);
		}

	}


	function checkIfUserHasBeenRevoked(WP_REST_Request $request){
		$user_id = $request->get_param('user_id');
		
		//$verification_exist = get_user_meta($user_id, '_identification_verification_Sent', true);
		$verification_exist = get_user_meta($user_id,'_identity_verification_Sent', true);
		
		if($verification_exist !== null && $verification_exist == 1  ){

			return new  WP_REST_Response(
				["status" => true]
			);
		} else {
			return new  WP_REST_Response(
				["status" => false]
			);

		}
	}



?>