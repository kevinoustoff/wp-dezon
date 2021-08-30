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
			$freelancer['reviews'] = "0 reviews";
					array_push($customToReturn,$freelancer);

				}
				
				return new WP_REST_Response($customToReturn); 

    }



?>