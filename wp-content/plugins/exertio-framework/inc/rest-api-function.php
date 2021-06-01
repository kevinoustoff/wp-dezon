<?php
    function hello()
    {
        return new WP_ERROR(401,'hello','hello');
    }
    //authentification
    function api_login( WP_REST_Request $request)
    {
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
        
        $userGeneral["contact"] = get_post_meta($pid, '_freelancer_contact_number' , true );
        $userGeneral["freelance-type"] = get_post_meta($pid, '_freelance_type' , true );
        $userGeneral["freelancer-language"] = get_post_meta($pid, '_freelancer_language', true);
        $userGeneral["freelancer-skills"] =  json_decode(stripslashes(get_post_meta($pid, '_freelancer_skills', true)), true);
        $skills_taxonomies = exertio_get_terms('freelancer-skills');

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



?>