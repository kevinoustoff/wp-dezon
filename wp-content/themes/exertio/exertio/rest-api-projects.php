<?php 

function filtersProjects(){
    $price_type = '';
    $title ='';
    $paged = 1;
    if (isset($_GET['title']) && $_GET['title'] != "") {
        $title = $_GET['title'];
    }

    $price = '';
	$price_min = '';
	$price_max = '';
	if (isset($_GET['price-min']) && $_GET['price-min'] != "") {
		$price_min = $_GET['price-min'];
	}

	if (isset($_GET['price-max']) && $_GET['price-max'] != "") {
		$price_max = $_GET['price-max'];
	}

	if ($price_min != "" && $price_max != "") {
		$price = array(
			'key' => '_project_cost',
			'value' => array($price_min, $price_max),
			'type' => 'numeric',
			'compare' => 'BETWEEN',
		);
    }
    if (isset($_GET['price-type']) && $_GET['price-type'] != "")
	{

		$price_type = array(
			'key'       => '_project_type',
			'value'     => $_GET['price-type'],
			'compare'   => '=',
		);
    }
    $category = '';
	
	if (isset($_GET['category']) && $_GET['category'] != "") {
	
		$category = array(
			array(
				'taxonomy' => 'project-categories',
				'field' => 'term_id',
				'terms' => $_GET['category'],
			),
		);
	}
	$freelancer_type = '';
	if (isset($_GET['freelancer-type']) && $_GET['freelancer-type'] != "") {
		$freelancer_type = array(
			array(
				'taxonomy' => 'freelancer-type',
				'field' => 'term_id',
				'terms' => $_GET['freelancer-type'],
			),
		);
	}
	$project_duration = '';
	if (isset($_GET['project-duration']) && $_GET['project-duration'] != "") {
		$project_duration = array(
			array(
				'taxonomy' => 'project-duration',
				'field' => 'term_id',
				'terms' => $_GET['project-duration'],
			),
		);
	}
	$project_leve = '';
	if (isset($_GET['project-level']) && $_GET['project-level'] != "") {
		$project_leve = array(
			array(
				'taxonomy' => 'project-level',
				'field' => 'term_id',
				'terms' => $_GET['project-level'],
			),
		);
	}
	$english_level = '';
	if (isset($_GET['english-level']) && $_GET['english-level'] != "") {
		$english_level = array(
			array(
				'taxonomy' => 'english-level',
				'field' => 'term_id',
				'terms' => $_GET['english-level'],
			),
		);
	}
	$location = '';
	if (isset($_GET['location']) && $_GET['location'] != "") {
		$location = array(
			array(
				'taxonomy' => 'locations',
				'field' => 'term_id',
				'terms' => $_GET['location'],
			),
		);
	}
	$skill = '';
	if (isset($_GET['skill']) && $_GET['skill'] != "") {
		$skill = array(
			array(
				'taxonomy' => 'skills',
				'field' => 'term_id',
				'terms' => $_GET['skill'],
			),
		);
	}

	$language = '';
	if (isset($_GET['language']) && $_GET['language'] != "") {
		$language = array(
			array(
				'taxonomy' => 'languages',
				'field' => 'term_id',
				'terms' => $_GET['language'],
			),
		);
	}

	$order ='DESC';
	if (isset($_GET['sort']) && $_GET['sort'] != "")
	{
		if($_GET['sort'] == 'desc')
		{
			$order ='DESC';
		}
		else if($_GET['sort'] == 'asc')
		{
			$order ='ASC';
		}
	}
	$show_expired = '';
	$expired_projects = fl_framework_get_options('expired_project_search');
	if (isset($expired_projects) && $expired_projects == 0) {
		$show_expired = array(
			'key'       => '_project_status',
			'value'     => 'active',
			'compare'   => '=',
		);
	}

		$args	=	array
		(
			's' => $title,
			'post_type' => 'projects',
			'post_status' => 'publish',
			'posts_per_page' => get_option('posts_per_page'),
			'paged' => $paged,
			'order'   => $order,
			'meta_key'          => '_project_is_featured',
			'meta_query'    => array(
				$show_expired,
				$price,
				$price_type,
			),

			'tax_query' => array(
				$category,
				$freelancer_type,
				$project_duration,
				$project_leve,
				$english_level,
				$location,
				$skill,
				$language,
			),
			'orderby'  => array(
				'meta_value' => 'DESC',
				'post_date'      => $order,
			),
		);
        $results = new WP_Query( $args ); 
        $index = 0;
        /* return new WP_REST_RESPONSE(
            $results->posts
        ); */
        $customResults = [];

        foreach($results->posts as $post){
            $author_id = get_post_field( 'post_author', $post->ID );
            $employer_id = get_user_meta( $author_id, 'employer_id' , true );
			$customProject['employer_is_verified'] = userVerificationStatus($author_id);
            $type = get_post_meta($post->ID, '_project_type', true);
            if($type == 'fixed')
            {
                $customProject['estimated_hours'] = null;
                $customProject['hourly_price'] = null;
                $customProject['cost'] = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
            }
            else if($type == 'hourly')
            {
                $customProject['cost'] = null;
                $customProject['hourly_price'] = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
                $hourly_price = fl_price_separator(get_post_meta($post->ID, '_project_cost', true));
                $estimated_hours = get_post_meta($post->ID, '_estimated_hours', true);
                $customProject['estimated_hours'] = $estimated_hours;
            
            }
            $customProject['id'] = $post->ID;
            $customProject['employer_name'] = get_post_meta( $employer_id, '_employer_dispaly_name' , true );
            $customProject['duration']= get_term_names('project-duration', '_project_duration', $post->ID );
            $customProject['level'] = get_term_names('project-level', '_project_level', $post->ID );
            $customProject['freelancer_type'] = get_term_names('freelancer-type', '_project_freelancer_type', $post->ID );
            $customProject['title'] = $post->post_title;
            /* $customProject['saved_skills'] */
            $skills = wp_get_post_terms($post->ID, 'skills', array( 'fields' => 'all' ));
            $customProject['project_expiry'] = project_expiry_calculation3($post->ID);
            $customProject['description'] = $post->post_content;
            /* $customProject['bid_results'] = get_project_bids($post->ID); */
            $j= 0;
			$customProject['author_id'] = intval($author_id);
            foreach($skills as $skill){
                $customProject['saved_skills'][$j]['name'] = $skill->name;
                $customProject['saved_skills'][$j]['term_id'] = $skill->term_id;
                $customProject['saved_skills'][$j]['term_taxonomy_id'] = $skill->term_taxonomy_id;
                $customProject['saved_skills'][$j]['taxonomy'] = $skill->taxonomy;  
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
            $customProject['offres'] = $results;
            /* $customProject['description'] = exertio_get_excerpt(25,$post->ID); */
            
            $project_location = get_term( get_post_meta($post->ID, '_project_location', true));
            if(!empty($project_location) && ! is_wp_error($project_location))
            {
                $location_remote = get_post_meta($post->ID, '_project_location_remote', true);
                if(isset($location_remote) && $location_remote == 1)
                {
                    $customProject['location'] = 'remote';
                }
                else
                {
                    $customProject['location'] = $project_location->name;
                }

            }
            else{
                $customProject['location'] = null;
			}
			
			array_push($customResults,$customProject);

            $index++;
        }

		
        return new WP_REST_RESPONSE(
            $customResults
        );

    }

    

    function searchServices(){
        
	}

	function searchFiltersProjects(){
		/* $locations = */ 
	}

	
	function getSingleProject(WP_REST_Request $request){
		global $exertio_theme_options;

		$id = $request->get_param('id');
		$project = get_post($id); 
		$author_id = get_post_field( 'post_author', $project->ID );
		 
        $employer_id = get_user_meta( $author_id, 'employer_id' , true );
		$customProject = [];
		$customProject["id"] = $project->id;
		$type = get_post_meta($project->ID, '_project_type', true);

            if($type == 'fixed')
            {
                $customProject['estimated_hours'] = null;
                $customProject['hourly_price'] = null;
                $customProject['cost'] = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
            }
            else if($type == 'hourly')
            {
                $customProject['cost'] = null;
                $customProject['hourly_price'] = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
                $hourly_price = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
                $estimated_hours = get_post_meta($project->ID, '_estimated_hours', true);
                $customProject['estimated_hours'] = $estimated_hours;
            
			}
			
			$proj_author = get_userdata($project->post_author);
			$customProject['id'] = $project->ID;
            $customProject['employer_name'] = get_post_meta( $employer_id, '_employer_dispaly_name' , true );
			$customProject['employer_member_since'] =  esc_html__('','exertio_theme').date_i18n( get_option( 'date_format' ), strtotime( $proj_author->user_registered ) );
            $meta_query = '';
			$the_query = fl_get_projects('',$project->post_author, $meta_query, 'completed');
			$cp_count = $the_query->found_posts;
			$customProject['employer_projects_completed'] = $cp_count;
			if($cp_count !==null && $cp_count > 0){
				$customProject['employer_projects_completed_checked'] = true;
			} else {
				$customProject['employer_projects_completed_checked'] = false;
			}
			$is_payment = get_user_meta( $project->post_author, 'is_payment_verified' , true );

			if($is_payment !== null && $is_payment == 1){
				$customProject['employer_payment_verified'] = "actif";
				$customProject['employer_payment_verified_checked'] = true;
			} else{
				$customProject['employer_payment_verified'] = "";
				$customProject['employer_payment_verified_checked'] = false;
			}

			$is_email = get_user_meta( $project->post_author, 'is_email_verified' , true );
			if($is_email !== null && $is_email== 1 ){
				$customProject['employer_email_verified'] = 'actif';
				$customProject['employer_email_verified_checked'] = true;
			} else {
				$customProject['employer_email_verified'] = '';
				$customProject['employer_email_verified_checked'] = false;
			}
			$customProject['employer_is_verified'] = userVerificationStatus($project->post_author);
			$customProject['duration']= get_term_names('project-duration', '_project_duration', $project->ID );
            $customProject['level'] = get_term_names('project-level', '_project_level', $project->ID );
            $customProject['freelancer_type'] = get_term_names('freelancer-type', '_project_freelancer_type', $project->ID );
            $customProject['title'] = $project->post_title;
            /* $customProject['saved_skills'] */
            $skills = wp_get_post_terms($project->ID, 'skills', array( 'fields' => 'all' ));
            $customProject['project_expiry'] = project_expiry_calculation3($project->ID);
            $customProject['description'] = $project->post_content;
            /* $customProject['bid_results'] = get_project_bids($project->ID); */
            $j= 0;

            foreach($skills as $skill){
                $customProject['saved_skills'][$j]['name'] = $skill->name;
                $customProject['saved_skills'][$j]['term_id'] = $skill->term_id;
                $customProject['saved_skills'][$j]['term_taxonomy_id'] = $skill->term_taxonomy_id;
                $customProject['saved_skills'][$j]['taxonomy'] = $skill->taxonomy;  
                $j++;
			}
			$results = get_project_bids($id);
            if (is_countable($results) && count($results) > 0)
            {
                $results = count(get_project_bids($project_id));
            }
            else
            {
                $results = 0;
            }
            $customProject['offres'] = $results;
            /* $customProject['description'] = exertio_get_excerpt(25,$project->ID); */
            $customProject['author_id'] = intval($author_id);
            $project_location = get_term( get_post_meta($project->ID, '_project_location', true));
            if(!empty($project_location) && ! is_wp_error($project_location))
            {
                $location_remote = get_post_meta($project->ID, '_project_location_remote', true);
                if(isset($location_remote) && $location_remote == 1)
                {
                    $customProject['location'] = 'remote';
                }
                else
                {
                    $customProject['location'] = $project_location->name;
                }

            }
            else{
                $customProject['location'] = null;
			}

			$customProject['location'] = null;
			
			$saved_languages = wp_get_post_terms($id, 'languages', array( 'fields' => 'all' ));
			$langIndex = 0;
			$customProject["languages"] = [];
			if(!empty($saved_languages) && ! is_wp_error($saved_languages))
			{
				foreach($saved_languages as $saved_language)
				{
					$customProject["languages"][$langIndex]["name"] = $saved_language->name;
					$langIndex++;
				}
			}
			$customProject["publish-date"] = date_i18n( get_option( 'date_format' ), strtotime( get_the_date($id) ) );
			
			$project_category = get_term( get_post_meta($id, '_project_category', true));
			$customProject['category'] = null;
			if(!empty($project_category) && ! is_wp_error($project_category))
			{
				$customProject['category'] = $project_category->name;
			}
			$project_ref_id = get_post_meta($id, '_project_ref_id', true);
			if(isset($project_ref_id) && $project_ref_id != '')
			{
				$project_ref_id = $project_ref_id;
			}
			else
			{
				$project_ref_id = $id;
			}

			$customProject['reference'] = $project_ref_id; 

			return new WP_REST_RESPONSE($customProject);
		


	}

	function myProjects(WP_REST_REQUEST $request){
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) {
		
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$user_id = $request->get_param('user_id');
		if($user_id != null){
			$the_query = new WP_Query( 
				array( 
						'author__in' => array( $user_id) ,
						'post_type' =>'projects',
						'paged' => $paged,	
						'post_status'     => 'publish',
						'order' => 'DESC',													
						)
					);
			$total_count = $the_query->found_posts;

		}
		$index = 0;
		$customResults = [];
		

		foreach($the_query->posts as $project){
			$author_id = get_post_field( 'post_author', $project->ID );
			$employer_id = get_user_meta( $author_id, 'employer_id' , true );
			$type = get_post_meta($project->ID, '_project_type', true);
			$customProject['author_id'] = intval($author_id);
			if($type == 'fixed')
            {
                $customProject['estimated_hours'] = null;
                $customProject['hourly_price'] = null;
                $customProject['cost'] = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
            }
			else if($type == 'hourly')
            {
                $customProject['cost'] = null;
                $customProject['hourly_price'] = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
                $hourly_price = fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
                $estimated_hours = get_post_meta($project->ID, '_estimated_hours', true);
                $customProject['estimated_hours'] = $estimated_hours;
            }

			$customProject['id'] = $project->ID;
            $customProject['employer_name'] = get_post_meta( $employer_id, '_employer_dispaly_name' , true );
            $customProject['duration']= get_term_names('project-duration', '_project_duration', $project->ID );
            $customProject['level'] = get_term_names('project-level', '_project_level', $project->ID );
            $customProject['freelancer_type'] = get_term_names('freelancer-type', '_project_freelancer_type', $project->ID );
            $customProject['title'] = $project->post_title;
            /* $customProject['saved_skills'] */
            $skills = wp_get_post_terms($project->ID, 'skills', array( 'fields' => 'all' ));
            $customProject['project_expiry'] = project_expiry_calculation3($project->ID);
            $customProject['description'] = $project->post_content;
            /* $customProject['bid_results'] = get_project_bids($post->ID); */
            $j= 0;

			foreach($skills as $skill){
                $customProject['saved_skills'][$j]['name'] = $skill->name;
                $customProject['saved_skills'][$j]['term_id'] = $skill->term_id;
                $customProject['saved_skills'][$j]['term_taxonomy_id'] = $skill->term_taxonomy_id;
                $customProject['saved_skills'][$j]['taxonomy'] = $skill->taxonomy;  
                $j++;
            }

			if (is_countable($the_query) && count($the_query) > 0)
            {
                $results = count(get_project_bids($project->ID));
            }
			else{
				$results = 0;
			}
			$customProject['offres'] = $results;

			$project_location = get_term( get_post_meta($project->ID, '_project_location', true));
            if(!empty($project_location) && ! is_wp_error($project_location))
            {
                $location_remote = get_post_meta($project->ID, '_project_location_remote', true);
                if(isset($location_remote) && $location_remote == 1)
                {
                    $customProject['location'] = 'remote';
                }
                else
                {
                    $customProject['location'] = $project_location->name;
                }

            }
            else{
                $customProject['location'] = null;
			}

			array_push($customResults,$customProject);

			$index++;


		}

		return new WP_REST_RESPONSE(
			$customResults
		);



	}

	function savedProjects(WP_REST_Request $request){
		global $exertio_theme_options;

		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		$user_id = $request->get_param("user_id");
		if($user_id !== null){
			global $wpdb;
			$rows = $wpdb->get_results( "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key LIKE '_pro_fav_id_%'" );
			$pids = array(0);

			foreach($rows as $row){
				$pids[] = $row->meta_value;
			}

			$args	=	array(
				'post_type' => 'projects',
				'post__in' => $pids,
				'post_status' => 'publish',
				'paged' => $paged,
				'order'=> 'DESC',
				'orderby' => 'date'
			);

			$the_query = new WP_Query($args);
			
			$total_count = $the_query->found_posts;
				$customProjects = [];			
			foreach($the_query->posts as $project){
				$myProj["id"] = $project->ID;
				$myProj["title"] = get_the_title($project->ID);
				
				$myProj["level"] = get_term( get_post_meta($project->ID, '_project_level', true));
				$category = get_term( get_post_meta($project->ID, '_project_category', true));

				if(!empty($category) && ! is_wp_error($category))
				{
					$myProj["category"] = $category->name;
				}
				$author_id = get_post_field( 'post_author', $project->ID );
				$myProj['author_id'] = intval($author_id); 
				$myProj["date"] = get_the_date(get_option( 'date_format' ), $project->ID );
				$type = get_post_meta($project->ID, '_project_type', true);
				if($type == 'fixed')
				{
					$myProj["cost"] =fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
					$myProj["type"] = "fixe";
				}
				else if ($type == "hourly"){
					$myProj["cost"] =fl_price_separator(get_post_meta($project->ID, '_project_cost', true));
					$myProj["type"] = "par heure";
				}

				array_push($customProjects, $myProj);

			}

		}

		return new WP_REST_Response(
			$customProjects
		);



	}

	function saveSingleProject(WP_REST_Request $request){
		$user_id = $request->get_param("user_id");
		$project_id = $request->get_param("project_id");

		if($project_id !== null || $project_id!=='' || $user_id !==null || $user_id= '')
		{
			if(get_user_meta( $user_id, '_pro_fav_id_'.$project_id, true ) == $project_id)
			{
				$return = array('message' => esc_html__( 'Ce projet est déjà enregistré', 'exertio_framework' ));
				wp_send_json_error($return);
			}
			else{
				update_user_meta( $user_id, '_pro_fav_id_' . $project_id, $project_id );
				
				$return = array('message' => esc_html__( 'Projet enregistré avec succès', 'exertio_framework' ));
				wp_send_json_success($return);

			}
		}
		else {
			$return = array('message' => esc_html__( 'erreur d\'ID de projet', 'exertio_framework' ));
			wp_send_json_error($return);
		}

		die();
	}

    


















?>