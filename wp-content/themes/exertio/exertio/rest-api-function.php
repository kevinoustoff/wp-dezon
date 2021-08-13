<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    
    function hello()
    {
        return new WP_ERROR(401,'hello','hello');
    }
    //authentification
    function api_login( WP_REST_Request $request)
    {
        global $exertio_theme_options;
        /* var_dump($request->get_param('email'));
        die(); */
        $username = $request->get_param('email');
        $password = $request->get_param('password');

        $user  = wp_authenticate( $username, $password );

        if (!is_wp_error($user))
        {
            if( count((array) $user->roles ) == 0 )
			{
				
                return new WP_ERROR(401,'Votre compte n\'est pas encore vérifié','no');
            }
            else
			{
                
				/* $res = fl_auto_login($username, $params['fl_password'], $remember ); */ 
                $user = (array) $user;
                 /* wp_die($user['data']->ID); */
                 
                $pid = get_user_meta( $user['data']->ID, 'freelancer_id' , true );

				$pro_img_id = get_post_meta($pid, '_profile_pic_freelancer_id', true );
                $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                
                if(wp_attachment_is_image($pro_img_id)){
                    $user['freelance-photo-profile'] = esc_url($pro_img[0]);
                    /* wp_die(esc_url($pro_img[0])); */
                } else {
                    $user['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
                    /* wp_die(esc_url($exertio_theme_options['freelancer_df_img']['url'])); */
                }  
					/* echo "1|". __( 'Login successful. Redirecting....', 'exertio_framework' )."|".$page; */
                return new WP_REST_Response(
                    array(
                        'status' => 200,
                        'response' => 'message',
                        'body_response' => $user
                    )
                    );
			}
            

        }
        else{
            return new WP_ERROR(401,'Identifiant ou mot de passe incorrect','no');
        }
    }

    //inscription des utilisateurs

    function api_register(WP_REST_Request $request)
    {
        global $exertio_theme_options;
        /* $email = sanitize_text_field($request->get_param('email'));
        parse_str($request->get_param('email'), $email); 
        $email = sanitize_text_field($email); */
         /* var_dump($request->get_param('email'));
        die();
 */        exertio_demo_disable('echo');
		/* check_ajax_referer( 'fl_register_secure', 'security' ); */
		$params = $request->get_json_params();
		/* parse_str($_POST['signup_data'], $params); */
        global $exertio_theme_options;
        /* $params = json_decode($params); */
        /* print_r(parse_str($params["email"])); 
         die(); */
		
       /*  var_dump(email_exists($request->get_param('email')));
        die(); */
		if(!email_exists($request->get_param('email') ))
		{
			$user_args = array(
                'user_pass'             => $params["password"],  
				'user_nicename'         => sanitize_text_field($params["username"]),
				'user_login'            => sanitize_text_field($params["username"]),
				'display_name' 			=> sanitize_text_field($params["fullname"]),
				'user_email'			=> sanitize_text_field($params["email"]),
			);
            $uid =	wp_insert_user($user_args);

            if(is_wp_error($uid))
            {
                return new WP_ERROR(409,'Identifiant existant. Veuillez modifier','no');
            }
            /* echo $uid->get_error_message();
            die();    */
            $user_info = get_userdata($uid);
            $user_info = (array) $user_info;
            $pid = get_user_meta( $user_info['data']->ID, 'freelancer_id' , true );

            $pro_img_id = get_post_meta($pid, '_profile_pic_freelancer_id', true );
            $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
            
            if(wp_attachment_is_image($pro_img_id)){
                $user_info['freelance-photo-profile'] = esc_url($pro_img[0]);
                /* wp_die(esc_url($pro_img[0])); */
            } else {
                $user_info['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
                /* wp_die(esc_url($exertio_theme_options['freelancer_df_img']['url'])); */
            }  
            
            exertion_on_registration_funtion($uid);
			if ( function_exists( 'exertio_generate_code_registeration' ) )
			{
				exertio_generate_code_registeration($uid);
			}

			$username = sanitize_text_field($params["email"]);
            $password = $params["password"];

            
			fl_auto_login($params['fl_email'], $params['fl_password'], $remember ); 
			setcookie('active_profile', 'employer', time() + (86400 * 365), "/");
            
            return new WP_REST_Response(
                array(
                    'status' => 200,
                    'response' => 'message',
                    'body_response' => $user_info
                )
                );
			
		}
		else
		{
            
			return new WP_ERROR(401,'L\'e-mail saisi existe déjà. Veuillez saisir un autre','no');
		}
    }

    function profile(WP_REST_Request $request)
    {
        global $exertio_theme_options;

        $uid = $request->get_param('uid');
        $pid = get_user_meta( $uid, 'freelancer_id' , true );

        $post	=	get_post($pid);
        $user_info = get_userdata($uid);
        
        $post_meta_sexe = get_post_meta( $pid, '_freelancer_gender' , true );
        $userGeneral["user_info"] = $user_info;
        if( $post_meta_sexe =="1")
        {
            $userGeneral["sexe"] = "Féminin";
        } else if($post_meta_sexe =="0") {
            $userGeneral["sexe"] = "Masculin";
        } else {
            $userGeneral["sexe"] = "Autres";
        }

        //photo de profil
        $pro_img_id = get_post_meta( $pid, '_profile_pic_freelancer_id', true );
        $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
        
        if(wp_attachment_is_image($pro_img_id)){
            $userGeneral['freelance-photo-profile'] = esc_url($pro_img[0]);
        } else {
            $userGeneral['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
        } 

        
        $userGeneral["contact"] = get_post_meta($pid, '_freelancer_contact_number' , true );
        $userGeneral["freelance-type"] = get_post_meta($pid, '_freelance_type' , true );
        $userGeneral["freelancer-language"] = get_post_meta($pid, '_freelancer_language', true);
        $userGeneral["freelancer-skills"] =  json_decode(stripslashes(get_post_meta($pid, '_freelancer_skills', true)), true);
        $skills_taxonomies = exertio_get_terms('freelancer-skills');
        //$userGeneral["freelancer-locations"] =  get_hierarchical_terms('freelancer-locations', '_freelancer_location', $pid );
        $location_id =  get_post_meta($pid, '_freelancer_location' , true );
        $taxonomies = exertio_get_terms("freelancer-locations");
        $hierarchy = _get_term_hierarchy("freelancer-locations");
        
        foreach($taxonomies as $term)
        {
            /* if($term->parent)
			{
				continue;
            } */
            
            if($term->term_id == intval($location_id))
            { 
              $userGeneral["freelancer-locations"] = $term->name;
            }
            

        }
        /* foreach($i=0;$i<count($user))
        {


        } */
        $project_jsons =  json_decode(stripslashes(get_post_meta($pid, '_freelancer_projects', true)), true);
        $languages_taxonomies = exertio_get_terms('freelancer-languages');

        foreach($languages_taxonomies as $languages_taxonomy)
        {
            if($languages_taxonomy->term_id == $userGeneral["freelancer-language"])
            {
                $userGeneral["freelancer-language"] = $languages_taxonomy->name;

            }

        }


        return new WP_REST_Response(array(
            'status' => 200,
            'response' => 'message',
            'body_response' => $userGeneral
        ));

    }

    function lastServices($params) {
        /* $exertio_serv = new Exertio_Services();

        $settings = Exertio_Services::parametersToJson(); */
        global $exertio_theme_options;
        $services_grid_style = $params[ 'services_grid_style' ];
        $services_type = $params[ 'services_type' ];
        $services_count = $params[ 'services_count' ];
        $services_slider_grids = $params[ 'services_slider_grids' ];
        $services_grids_cols = $params[ 'services_grids_cols' ];

        $featured = '';
			if ( $services_type == 'featured' ) {
			$featured = array(
			  'key' => '_service_is_featured',
			  'value' => '1',
			  'compare' => '=',
			);
			} else if ( $services_type == 'simple' ) {
			$featured = array(
			  'key' => '_service_is_featured',
			  'value' => '0',
			  'compare' => '=',
			);
        }

        $args = array(
			'post_type' => 'services',
			'post_status' => 'publish',
			'posts_per_page' => $services_count,
			'orderby' => 'date',
			'order' => 'ASC',
			'meta_query' => array(
			  array(
				'key' => '_service_status',
				'value' => 'active',
				'compare' => '=',
			  ),
			  $featured,
			),
            );
            
        $results = new WP_Query( $args );
        $services = [];

        if($results->have_posts()){
            while($results->have_posts()){
                $results->the_post();
                $service_id = get_the_ID();
                $author_id = get_post_field( 'post_author', $service_id );
                $posted_date = get_the_date(get_option( 'date_format' ), $service_id );
                $fid = get_user_meta( $author_id, 'freelancer_id' , true );
                $serv['image'] = exertio_get_service_image_url($service_id);
                $serv['title'] = get_the_title($service_id);
                $serv['rating'] = get_service_rating($service_id);
                $serv['queued'] = exertio_queued_services($service_id);
                $serv['price']  = get_post_meta($service_id, '_service_price', true);
                $serv['freelancer-name'] = exertio_get_username('freelancer', $fid);
                $serv['id'] = $service_id;
                $pro_img_id = get_post_meta( $fid, '_profile_pic_freelancer_id', true );
                $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                
                if(wp_attachment_is_image($pro_img_id)){
                    $serv['freelance-photo-profile'] = esc_url($pro_img[0]);
                } else {
                    $serv['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
                }
                
                array_push($services,$serv);
            }

        }

        return $services;

    }

    function getSexes(){
        $sexes = [];

        $sexes[0]["id"] ="0";
        $sexes[0]["libelle"] ="Masculin";

        $sexes[1]["id"] ="1";
        $sexes[1]["libelle"] ="Feminin";

        $sexes[2]["id"] ="2";
        $sexes[2]["libelle"] ="Autres";

        return new WP_REST_Response(
           $sexes
        );

    }

    function getEnglishLevel() {
        $english_level_taxonomies = exertio_get_terms('freelancer-english-level');
        $index =0;
        $levels = [];
        foreach($english_level_taxonomies as $level){
            $levels[$index]['term_id'] = $level->term_id;
            $levels[$index]['name'] = $level->name;

            $index++;
        }
        return new WP_REST_Response(
            $levels
         );
    }

    function getLocations(){
        $location_taxonomies = exertio_get_terms('freelancer-locations');
        $indexPays = 0;
        $locations = [];
        foreach($location_taxonomies as $location)
        {
            $indexVilles = 0;
            if($location->parent ==0){
                $locations[$indexPays]['id'] = $location->term_id;
            $locations[$indexPays]['name'] = $location->name;

            $villes = [];

            foreach($location_taxonomies as $loc){
                if($loc->parent == $location->term_id)
                {
                    $villes[$indexVilles]['id'] = $loc->term_id;
                    $villes[$indexVilles]['name'] = $loc->name;
                }
                $indexVilles++;


            }
            $locations[$indexPays]['villes'] = array_values($villes);
            $indexPays++;
            }
            
        }
        return new WP_REST_Response(
            $locations
         );

    }

    function getTypePrestataire(){
        $freelance_taxonomies = exertio_get_terms('freelance-type');

        $typePrestataires = [];
        $index = 0;
        foreach($freelance_taxonomies as $typePrest){

            $typePrestataires[$index]['id'] = $typePrest->term_id;
            $typePrestataires[$index]['name'] = $typePrest->name; 
            $index++;
        }
        return new WP_REST_Response(
            $typePrestataires
         );
    }

    function getLanguesPrestataires(){
        $languages_taxonomies = exertio_get_terms('freelancer-languages');
        $index = 0;
        $langs = [];
        foreach($languages_taxonomies as $lang){
            $langs[$index]['id'] = $lang->term_id;
            $langs[$index]['name'] = $lang->name;

            $index++;
        }
        return new WP_REST_Response(
            $langs
         );

    }

    function forgetPwd(WP_REST_Request $request)
    {
/*         wp_die('coucou');
 */        /* exertio_demo_disable('json'); */
		
/* 		check_ajax_referer( 'fl_forget_pwd_secure', 'security' );
 */		$params = array();
		parse_str($_POST['forget_pwd_data'], $params);
       
		$email = trim(sanitize_email($request->get_param('email')));
		if(empty($email))
		{
			/* $return = array('message' => esc_html__( 'Please type your e-mail address.', 'exertio_framework' ));
            wp_send_json_error($return); */
            return new WP_REST_Response(array(
                
                'response' => 'Veuillez saisir votre adresse e-mail',
                
            ));
            
		}
		else if ( !is_email( $email ) )
		{
			/* $return = array('message' => esc_html__( 'Please enter a valid e-mail address.', 'exertio_framework' ));
            wp_send_json_error($return); */
            return new WP_REST_Response(array(
                
                'response' => 'Veuillez saisir votre adresse e-mail valide',
                
            ));
            	
		}
		else if(!email_exists($email)) {
			/* $return = array('message' => esc_html__( 'This email address does not exist on website.', 'exertio_framework' ));
            wp_send_json_error($return); */	
            return new WP_REST_Response(array(
                
                'response' => 'l\'e-mail que vous avez saisi est inexistant',
                
            ));
            
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
            
			/* $return = array('message' => esc_html__( 'Check your email for the confirmation link.', 'exertio_framework' ));
            wp_send_json_success($return); */
            
            return new WP_REST_Response(array(
                
                'response' => 'Verifiez votre e-mail pour le lien de confirmation',
                
            ));
		/* 	die(); */
		}

    }

    function changePassword(WP_REST_Request $request){
        
        global $exertio_theme_options;
        
        $params = array();
        
        $current_pass = $request->get_param('old_password');
        
        $new_pass = sanitize_text_field($request->get_param('new_password'));
        $con_new_pass = sanitize_text_field($request->get_param('confirm_password'));
        
        
        if($current_pass =="" || $new_pass=="" || $con_new_pass==""){
            return new WP_ERROR(401,'Tous les champs sont obligatoires','no');
        } 
        if($new_pass == $current_pass){
            return new WP_ERROR(401,'Désolé l\'ancien et le nouveau mot de passe sont identiques','no');
        }
       
        if( $new_pass != $con_new_pass )
		{
            
            return new WP_ERROR(401,'Désolé les champs du nouveau mot de passe doivent etre identiques','no');
        }
        
        $user =  get_user_by('ID',$request->get_param('user_id'));
        
        if($user && wp_check_password($current_pass, $user->data->user_pass)){
            wp_set_password($new_pass,$user->ID);
            return new WP_REST_Response(array(
                'status' => 200,
                'response' => 'message',
                'body_response' => 'Mot de passe changé avec succès'
            ));
        }
        else{
            return new WP_ERROR(401,'l\'ancien mot de passe que vous saisi est incorrect','no');

        }




        

    }
    /* 
        Fonction pour récupérer la liste des projets
    */
    function getListeProjets()
    {
        $projects_count = $projects_type = $project_grid_style = $col = $projects_list_cols = '';

        $featured = '';
			if ( $projects_type == 'featured' ) {
			  $featured = array(
				'key' => '_project_is_featured',
				'value' => '1',
				'compare' => '=',
			  );
			} else if ( $projects_type == 'simple' ) {
			  $featured = array(
				'key' => '_project_is_featured',
				'value' => '0',
				'compare' => '=',
			  );
			}
			$args = array(
			  'post_type' => 'projects',
			  'post_status' => 'publish',
			  'posts_per_page' => $projects_count,
			  'orderby' => 'date',
			  'order' => 'DESC',
			  'meta_query' => array(
				$featured,
			  ),
			);
            $results = new WP_Query( $args );

            $customResults = [];
            $index = 0;

            foreach($results->posts as $post){
                $author_id = get_post_field( 'post_author', $post->ID );
                $employer_id = get_user_meta( $author_id, 'employer_id' , true );
                
                $type = get_post_meta($post->ID, '_project_type', true);
                if($type == 'fixed')
                {
                    $customResults[$index]['estimated_hours'] = null;
                    $customResults[$index]['hourly_price'] = null;
                    $customResults[$index]['cost'] = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
                }
                else if($type == 'hourly')
                {
                    $customResults[$index]['cost'] = null;
                    $customResults[$index]['hourly_price'] = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
                    $hourly_price = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
                    $estimated_hours = get_post_meta($post->ID, '_estimated_hours', true);
                    $customResults[$index]['estimated_hours'] = $estimated_hours;
                
                }
                $customResults[$index]['id'] = $post->ID;
                $customResults[$index]['employer_name'] = get_post_meta( $employer_id, '_employer_dispaly_name' , true );
                $customResults[$index]['duration']= get_term_names('project-duration', '_project_duration', $post->ID );
                $customResults[$index]['level'] = get_term_names('project-level', '_project_level', $post->ID );
                $customResults[$index]['freelancer_type'] = get_term_names('freelancer-type', '_project_freelancer_type', $post->ID );
                $customResults[$index]['title'] = $post->post_title;
                /* $customResults[$index]['saved_skills'] */
                $skills = wp_get_post_terms($post->ID, 'skills', array( 'fields' => 'all' ));
                $customResults[$index]['project_expiry'] = project_expiry_calculation3($post->ID);
                $customResults[$index]['description'] = $post->post_content;
                /* $customResults[$index]['bid_results'] = get_project_bids($post->ID); */
                $j= 0;

                foreach($skills as $skill){
                    $customResults[$index]['saved_skills'][$j]['name'] = $skill->name;
                    $customResults[$index]['saved_skills'][$j]['term_id'] = $skill->term_id;
                    $customResults[$index]['saved_skills'][$j]['term_taxonomy_id'] = $skill->term_taxonomy_id;
                    $customResults[$index]['saved_skills'][$j]['taxonomy'] = $skill->taxonomy;  
                    $j++;
                }
                if (is_countable($results) && count($results) > 0)
                {
                    $results = count(get_project_bids($project_id));
                }
                else
                {
                    $results = 0;
                }
                $customResults[$index]['offres'] = $results;
                /* $customResults[$index]['description'] = exertio_get_excerpt(25,$post->ID); */
                
                $project_location = get_term( get_post_meta($post->ID, '_project_location', true));
                if(!empty($project_location) && ! is_wp_error($project_location))
				{
                    $location_remote = get_post_meta($post->ID, '_project_location_remote', true);
                    if(isset($location_remote) && $location_remote == 1)
                    {
                        $customResults[$index]['location'] = 'remote';
                    }
                    else
                    {
                        $customResults[$index]['location'] = $project_location->name;
                    }

                }
                else{
                    $customResults[$index]['location'] = null;
                }

                $index++;
            }
            
            return new WP_REST_Response(array(
                
                'response' => $customResults,
                
            ));
    }

    include 'rest-api-projects.php';
    include 'rest-api-services.php';








?>