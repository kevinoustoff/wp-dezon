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

		
        return new WP_REST_RESPONSE(
            $customResults
        );

    }

    function listeDelaiLivraisons(){

        $taxonomies  = get_terms( array(
			'taxonomy' => 'delivery-time',
			'hide_empty' => true,
			'orderby'      => 'name',
			'parent' => 0
        ));

        $index = 0;
        $tax = [];

        foreach($taxonomies as $taxonomie){
            $tax[$index]['name'] = $taxonomie->name;
            $tax[$index]['term_id'] = $taxonomie->term_id;


            $index++;
        }
        
        return new WP_REST_RESPONSE(
            $tax
        );

    }

    function listeEnglishLevels(){

        $taxonomies  = get_terms( array(
			'taxonomy' => 'services-english-level',
			'hide_empty' => true,
			'orderby'      => 'name',
			'parent' => 0
        ));

        $index = 0;
        $tax = [];

        foreach($taxonomies as $taxonomie){
            $tax[$index]['name'] = $taxonomie->name;
            $tax[$index]['term_id'] = $taxonomie->term_id;


            $index++;
        }
        
        return new WP_REST_RESPONSE(
            $tax
        );

    }
    function listeServicesLocations(){

        $taxonomies  = get_terms( array(
			'taxonomy' => 'services-locations',
			'hide_empty' => true,
			'orderby'      => 'name',
			'parent' => 0
        ));

        $index = 0;
        $tax = [];

        foreach($taxonomies as $taxonomie){
            $tax[$index]['name'] = $taxonomie->name;
            $tax[$index]['term_id'] = $taxonomie->term_id;


            $index++;
        }
        
        return new WP_REST_RESPONSE(
            $tax
        );

    }

    function searchServices(){
        
    }

    


















?>