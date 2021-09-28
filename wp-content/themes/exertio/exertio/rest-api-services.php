<?php 
    function searchServicesApiVersion(){
    global $exertio_theme_options;
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
			'key' => '_service_price',
			'value' => array($price_min, $price_max),
			'type' => 'numeric',
			'compare' => 'BETWEEN',
		);
	}

	$category = '';
	if (isset($_GET['categories']) && $_GET['categories'] != "") {
		$category = array(
			array(
				'taxonomy' => 'service-categories',
				'field' => 'term_id',
				'terms' => $_GET['categories'],
			),
		);
	}
	$english_level = '';
	if (isset($_GET['english-level']) && $_GET['english-level'] != "") {
		$english_level = array(
			array(
				'taxonomy' => 'services-english-level',
				'field' => 'term_id',
				'terms' => $_GET['english-level'],
			),
		);
	}
	$response_time = '';
	if (isset($_GET['response-time']) && $_GET['response-time'] != "") {
		$response_time = array(
			array(
				'taxonomy' => 'response-time',
				'field' => 'term_id',
				'terms' => $_GET['response-time'],
			),
		);
	}
	$delivery_time = '';
	if (isset($_GET['delivery-time']) && $_GET['delivery-time'] != "") {
		$delivery_time = array(
			array(
				'taxonomy' => 'delivery-time',
				'field' => 'term_id',
				'terms' => $_GET['delivery-time'],
			),
		);
	}
	$location = '';
	if (isset($_GET['location']) && $_GET['location'] != "") {
		$location = array(
			array(
				'taxonomy' => 'services-locations',
				'field' => 'term_id',
				'terms' => $_GET['location'],
			),
		);
	}
    $order ='';
	if (isset($_GET['sort']) && $_GET['sort'] != "")
	{
		$order ='';
		if($_GET['sort'] == 'new-old')
		{
			$order ='DESC';
		}
		else if($_GET['sort'] == 'old-new')
		{
			$order ='ASC';
		}
	}
		$args	=	array
		(
			'author__not_in' => array( 1 ),
			's' => $title,
			'post_type' => 'services',
			'post_status' => 'publish',
			'posts_per_page' => get_option('posts_per_page'),
			'paged' => $paged,
			'meta_key'  => '_service_is_featured',
			'meta_query'    => array(
				array(
					'key'       => '_service_status',
					'value'     => 'active',
					'compare'   => '=',
				),
				$price,
			),
			'tax_query' => array(
				$category,
				$english_level,
				$response_time,
				$delivery_time,
				$location,
			),
			'orderby'  => array(
				'meta_value' => 'DESC',
				'post_date'      => $order,
			),
		);
        $results = new WP_Query( $args );
        $customResults = [];

        foreach($results->posts as $service){
            $service_id = $service->ID;
                $author_id = get_post_field( 'post_author', $service_id );
                $posted_date = get_the_date(get_option( 'date_format' ), $service_id );
                $fid = get_user_meta( $author_id, 'freelancer_id' , true );
                $serv['id'] = $service->ID;
                $serv['image'] = exertio_get_service_image_url($service_id);
                $serv['title'] = get_the_title($service_id);
                $serv['rating'] = get_service_rating($service_id);
                $serv['queued'] = exertio_queued_services($service_id);
                $serv['price']  = get_post_meta($service_id, '_service_price', true);
                $serv['freelancer-name'] = exertio_get_username('freelancer', $fid);
                
                $pro_img_id = get_post_meta( $fid, '_profile_pic_freelancer_id', true );
                $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                
                if(wp_attachment_is_image($pro_img_id)){
                    $serv['freelance-photo-profile'] = esc_url($pro_img[0]);
                } else {
                    $serv['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
                }
                
                array_push($customResults,$serv);

        }

        return new WP_REST_RESPONSE(
            $customResults
        );
        

	}
	
	function getServiceDetail(WP_REST_Request $request){
		global $exertio_theme_options;
		$id = $request->get_param('id');
		$customService = [];
		$service = get_post($id);
		$post_author = $service->post_author;
		$fid = get_user_meta( $post_author, 'freelancer_id', true );
		$services_addons_ids = json_decode( get_post_meta($service->ID, '_services_addon', true));
		$customAddons = [];
		if( $services_addons_ids !== null && $services_addons_ids != ''){
			$args = array(
				'post__in' => $services_addons_ids,
				'post_type' => 'addons',
				'meta_query' => array(
				  array(
					'key' => '_addon_status',
					'value' => 'active',
					'compare' => '=',
				  ),
				),
				'post_status' => 'publish'
			  );

			  $addons = get_posts( $args );
			  
			  foreach( $addons as $addon){
				  $myAddon["title"] = get_the_title($addon->ID);
				  $myAddon["price"] = get_post_meta( $addon->ID, '_addon_price', true );
				  $myAddon["content"] = $addon->post_content;
				  $myAddon["id"] = $addon->ID;

				  array_push($customAddons, $myAddon);

			  }
			  
			  //print_r($addons);

		}
		$delivery_time = get_term( get_post_meta( $service->ID, '_delivery_time', true ) );
		$customService["addonsServices"] = $customAddons;
		$customService["delivery-time"] = null;
		/* print_r($service); */
		if(!empty( $delivery_time ) && !is_wp_error( $delivery_time )){
			$customService["delivery-time"] = $delivery_time->name;
		}
		$response_time = get_term( get_post_meta( $service->ID, '_response_time', true ));
		$customService["response-time"] = null;
		if(!empty( $response_time ) && !is_wp_error( $response_time )){
			$customService["response-time"] = $response_time->name;
		}
		$customService["english_level"] = null;
		$service_eng_level = get_term( get_post_meta( $service->ID, '_service_eng_level', true ) );
		if ( !empty( $service_eng_level ) && !is_wp_error( $service_eng_level ) ) {
			$customService["english_level"] = $service_eng_level->name;
		}

		$customService['image'] = exertio_get_service_image_url($service->ID);
		$customService['price'] = get_post_meta($service->ID, '_service_price', true);
		$customService['title'] = get_the_title($service->ID);
		$customService['description'] = wp_kses($service->post_content, exertio_allowed_html_tags());
		$customService['rating'] = get_service_rating($service->ID);
		$customService['queued'] = exertio_queued_services($service->ID);
		$customService['freelancer-name'] = exertio_get_username('freelancer', $fid);
		$customService['freelancer-id'] = intval($fid);
		$customService['id'] = $service->ID;
		$customService['freelancer-rates-stars'] = get_freelancer_rating( $fid, 'stars', 'service' );;
		$customService['user_id'] = intval($post_author); 
		$pro_img_id = get_post_meta( $fid, '_profile_pic_freelancer_id', true );
		$pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
		
		if(wp_attachment_is_image($pro_img_id)){
			$customService['freelance-photo-profile'] = esc_url($pro_img[0]);
		} else {
			$customService['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
		}

		return new WP_REST_RESPONSE($customService);
		
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
        
        return $tax;
        

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
        
        return $tax;
        

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
        
        return $tax;
        

	}
	
	function getServiceCategories(){
		$hide_empty = false;
		/* 'service-categories','categories' */

		$taxonomies = get_terms( array(
			'taxonomy' => 'service-categories',
			'hide_empty' => $hide_empty,
			'orderby'      => 'name',
			'parent' => 0
		) );	


		$index = 0;
        $tax = [];

        foreach($taxonomies as $taxonomie){
            $tax[$index]['name'] = $taxonomie->name;
            $tax[$index]['term_id'] = $taxonomie->term_id;


            $index++;
        }
        
        return $tax;
		

	}
	function getFilters($filter_name)
	{
		$hide_empty = false;
		/* 'service-categories','categories' */

		$taxonomies = get_terms( array(
			'taxonomy' => $filter_name,
			'hide_empty' => $hide_empty,
			'orderby'      => 'name',
			'parent' => 0
		) );	


		$index = 0;
        $tax = [];

        foreach($taxonomies as $taxonomie){
			/* print_r($taxonomie); */
			$tax[$index]['name'] = $taxonomie->name;
            $tax[$index]['term_id'] = $taxonomie->term_id;


            $index++;
        }
        
        return $tax;

	}

	function getServicesSearchFilters(){

		$locations = listeServicesLocations();
		$livraison = listeDelaiLivraisons();
		$englishLevels = listeEnglishLevels();
		$servicesCategories = getServiceCategories();
		
		$filters = [];

		$filters["locations"] = listeServicesLocations();
		$filters["livraison"] = listeDelaiLivraisons();
		$filters["english-level"] = listeEnglishLevels();
		$filters["servicesCategories"] = getServiceCategories();

		$filters["projects"]["categories"] = getFilters("project-categories");
		$filters["projects"]["languages"] = getFilters("languages");
		$filters["projects"]["locations"] = getFilters("locations");
		
		return new WP_REST_RESPONSE($filters);
	}

	function getMyServices(WP_REST_REQUEST $request){
		
		global $exertio_theme_options;
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		  } else if ( get_query_var( 'page' ) ) {
			/*This will occur if on front page.*/
			$paged = get_query_var( 'page' );
		  } else {
			$paged = 1;
		  }

		if($request->get_param("user_id") !== null){
			$the_query = new WP_Query(
				array(
				  'author__in' => array( $request->get_param("user_id") ),
				  'post_type' => 'services',
				  'meta_query' => array(
					array(
					  'key' => '_service_status',
					  'value' => 'active',
					  'compare' => '=',
					),
				  ),
				  'paged' => $paged,
				  'post_status' => 'publish'
				)
			  );

			  $total_count = $the_query->found_posts;
			  $customResults = [];

			  foreach($the_query->posts as $service){
				$service_id = $service->ID;
                $author_id = get_post_field( 'post_author', $service_id );
                $posted_date = get_the_date(get_option( 'date_format' ), $service_id );
                $fid = get_user_meta( $author_id, 'freelancer_id' , true );
                $serv['id'] = $service->ID;
                $serv['image'] = exertio_get_service_image_url($service_id);
                $serv['title'] = get_the_title($service_id);
                $serv['rating'] = get_service_rating($service_id);
                $serv['queued'] = exertio_queued_services($service_id);
                $serv['price']  = get_post_meta($service_id, '_service_price', true);
                $serv['freelancer-name'] = exertio_get_username('freelancer', $fid);
                $serv['date_post'] = get_the_date( get_option( 'date_format' ), $service_id );
                $pro_img_id = get_post_meta( $fid, '_profile_pic_freelancer_id', true );
                $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                
                if(wp_attachment_is_image($pro_img_id)){
                    $serv['freelance-photo-profile'] = esc_url($pro_img[0]);
                } else {
                    $serv['freelance-photo-profile'] = esc_url($exertio_theme_options['freelancer_df_img']['url']);
                }
                
                array_push($customResults,$serv);


			  }

			  return new WP_REST_RESPONSE(
				$customResults
			);
			  


		}




		

	}







?>