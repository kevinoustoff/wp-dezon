<?php 
    function searchServicesApiVersion() {
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
				$serv['author_id'] = intval($author_id);
                $serv['image'] = exertio_get_service_image_url($service_id);
                $serv['title'] = get_the_title($service_id);
                $serv['rating'] = get_service_rating($service_id);
                $serv['queued'] = exertio_queued_services($service_id);
                $serv['price']  = get_post_meta($service_id, '_service_price', true);
                $serv['freelancer-name'] = exertio_get_username('freelancer', $fid);
				$serv['freelancer_is_verified'] = userVerificationStatus($author_id);
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
		$customService['freelancer_is_verified'] = userVerificationStatus($post_author);
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
		$customService['freelancer-location'] = get_term_names( 'freelancer-locations', '_freelancer_location', $fid, '', ',' );
		$customService['freelancer-member-since'] = date_i18n( get_option( 'date_format' ), strtotime( get_the_date('dS M Y', $fid)));
		$customService['id'] = $service->ID;
		$customService['freelancer-rates-stars'] = get_freelancer_rating( $fid, 'stars', 'service' );;
		$customService['author_id'] = intval($post_author); 
		if($exertio_theme_options['service_ad_1'] !=null || $exertio_theme_options['project_detail_ad1'] !=''){
			$dom = new DOMDocument();
			$dom->loadHTML( $exertio_theme_options['service_ad_1']);
			$customService['advert_first'] = $dom->getElementsByTagName("img")[0]->getAttributeNode('src')->value;	
		}
		else{
			$customService['advert_side_first'] = null;
		}
		if($exertio_theme_options['sidebar_service_ad_1'] !=null || $exertio_theme_options['project_detail_ad1'] !=''){
			$dom = new DOMDocument();
			$dom->loadHTML( $exertio_theme_options['sidebar_service_ad_1']);
			$customService['advert_side_first'] = $dom->getElementsByTagName("img")[0]->getAttributeNode('src')->value;	
		}
		else{
			$customService['advert_side_first'] = null;
		}
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

		function getServicesCategories(){

		}

		

		

	}

	function saveSingleService(WP_REST_Request $request){
		$user_id = $request->get_param("user_id");
		$service_id = $request->get_param("service_id");
	
		if($service_id !==null || $service_id !=='' || $user_id !==null || $user_id !== '' )
		{
			if(get_user_meta( $user_id, '_service_fav_id_'.$service_id, true ) == $service_id)
			{
				$return = array('message' => esc_html__( 'Ce service est déjà enregistré', 'exertio_framework' ));
				wp_send_json_error($return);
			}
			else{
				update_user_meta( $user_id, '_service_fav_id_' . $service_id, $service_id );
				
				$return = array('message' => esc_html__( 'Service enregistré avec succès', 'exertio_framework' ));
				wp_send_json_success($return);

			}
		}
		else {
			$return = array('message' => esc_html__( 'erreur d\'ID de service', 'exertio_framework' ));
			wp_send_json_error($return);
		}

		die();
	}

	function savedServices(WP_REST_Request $request)
	{
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
			$rows = $wpdb->get_results( "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key LIKE '_service_fav_id_%'" );
			$pids	=	array(0);

			foreach($rows as $row){
				$pids[]	=	$row->meta_value;
			}

			$args	=	array(
				'post_type' => 'services',
				'post__in' => $pids,
				'post_status' => 'publish',
				'paged' => $paged,
				'order'=> 'DESC',
				'orderby' => 'date'
			);

            $the_query = new WP_Query($args);
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
		else{
			return new WP_ERROR(401,'Veuillez vous connecter','no');
		} 



	}

	function createServiceData(WP_REST_Request $request){
		$services_categories = get_service_taxonomies_terms("service-categories");
		$services_delivery_time = get_service_taxonomies_terms("delivery-time");
		$competences_levels = get_service_taxonomies_terms("services-english-level");
		$services_locations = get_service_taxonomies_terms("services-locations");
		$response_time = get_service_taxonomies_terms("response-time");

		$data["categories"] = $services_categories;
		$data["delais_livraisons"] = $services_delivery_time;
		$data["competences_levels"] = $competences_levels;
		$data["locations"] = $services_locations;
		$data["disponibilites"] = $response_time;
		$data["addons_services"] = getServicesAddons($request->get_param("user_id"));
		 return new WP_REST_RESPONSE(
			$data
		);
	}

	function getServicesAddons($user_id){
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} else if ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		$args = array( 
			'author__in' => array( $user_id ) ,
			'post_type' =>'addons',
			'meta_query' => array(
				array(
					'key' => '_addon_status',
					'value' => 'active',
					'compare' => '=',
					),
				),
			'paged' => $paged,	
			'post_status'     => 'publish'													
			);

		 $addons = get_posts($args);
		 $customizedAddons = [];

		 foreach($addons as $addonService){
			$singleAddon["title"] = $addonService->post_title;
			$singleAddon["content"] = $addonService->post_content;
			$singleAddon["id"] = $addonService->ID;
			$singleAddon["price"] = fl_price_separator(get_post_meta( $addonService->ID,'_addon_price', true ));
			array_push($customizedAddons,$singleAddon);
		}

		return $customizedAddons;
	}

	function get_service_taxonomies_terms($the_term){
		$term_results =exertio_get_terms($the_term);
		
		$final_term_array = [];
		foreach($term_results as $result){
			$customTermResult["term_id"] = $result->term_id;
			$customTermResult["name"] = $result->name;
			array_push($final_term_array,$customTermResult);
		
		}
		return $final_term_array;

	}

	function createService(WP_REST_Request $request){
		global $exertio_theme_options;
		$user_id = $request->get_param('user_id');

		$my_post = array(
			
			'post_title' => $request->get_param("service_title"),
			'post_content' => $request->get_param("service_description"),
			'post_type' => 'services',
			'post_author' => $user_id,
			'post_status'   => 'publish',
		);
		$sid = wp_insert_post($my_post);
		if($request->get_param("service_price")){
			update_post_meta( $sid, '_service_price', sanitize_text_field($request->get_param("service_price")));
		}
		// update_post_meta
		if($request->get_param("response_time")){
			$response_terms = array((int)$request->get_param("response_time"));
			update_post_meta($sid,'_response_time', sanitize_text_field($request->get_param("response_time")));
			wp_set_post_terms( $sid, $response_terms, 'response-time', false );
		}
		if($request->get_param('delivery_time'))
		{
			$delivery_terms = array((int)$request->get_param('delivery_time')); 
			update_post_meta( $sid, '_delivery_time', sanitize_text_field($request->get_param('delivery_time')));
			wp_set_post_terms( $sid, $delivery_terms, 'delivery-time', false );
			
		}
		if($request->get_param('service_level'))
		{
			$service_english_level_term = array((int)$request->get_param('service_level')); 
			update_post_meta( $sid, '_service_eng_level', sanitize_text_field($request->get_param('service_level')));
			wp_set_post_terms( $sid, $service_english_level_term, 'services-english-level', false );
		}
		if($request->get_param('service_location'))
		{
			update_post_meta( $sid, '_service_location', sanitize_text_field($request->get_param('service_location')));
			set_hierarchical_terms('services-locations', $request->get_param('service_location'),$sid);
		}
		if($request->get_param('service_category'))
		{
			update_post_meta($sid, '_service_category', sanitize_text_field($request->get_param('service_category')));
			set_hierarchical_terms('service-categories', $request->get_param('service_category'), $sid);
		}
		if($request->get_param('service_address'))
		{
			update_post_meta( $sid, '_service_address', sanitize_text_field($request->get_param("service_address")));
		}
		if($request->get_param('service_latitude'))
		{
			update_post_meta( $sid, '_service_latitude', sanitize_text_field($request->get_param('services_latitude')));
		}
		if($request->get_param('service_longitude'))
		{
			update_post_meta( $sid, '_service_longitude', sanitize_text_field($request->get_param('services_longitude')));
		}
		if($request->get_param('addons_service'))
		{
			$services_addon =(array) json_decode($request->get_param('addons_service'));
			
			for($i=0; $i<count($services_addon); $i++)
			{
				$name = sanitize_text_field($services_addon[$i]);
				$addon[] = $name;
			}
			$encoded_addon =  json_encode($addon);
			update_post_meta( $sid, '_services_addon', $encoded_addon );
		}
		update_post_meta( $sid, '_service_status', 'active');
		$files = $request->get_file_params();
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
    	require_once( ABSPATH . 'wp-admin/includes/file.php' );
    	require_once( ABSPATH . 'wp-admin/includes/media.php' );
		if(count($files)>0){
			update_post_meta( $sid, '_service_attachment_show', 'yes');
		} else{
			update_post_meta( $sid, '_service_attachment_show', 'no');
		}
		$status = get_post_meta($sid, '_service_status', true);
		update_post_meta( $sid, '_service_is_featured', 0);
		$attachment_ids = [];

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

				$image_size = $exertio_theme_options['user_attachment_size']*2;

				if($file['size']/1000 > $image_size){
					return new WP_Error(
						500,
						'La taille maximale du fichier'.$image_size.' KB',
						'no'
					);

				}
				
				update_post_meta( $sid, '_service_attachment_show', 'no');
				foreach ($_FILES as $file => $array) 
				{       
					update_post_meta( $sid, '_service_attachment_show', 'yes');       
					
					/*if($imgcount>=$condition_img){ break; }*/ 
					$attach_id = media_handle_upload( $file, $sid );
					array_push($attachment_ids, $attach_id);
				
					$image_link = wp_get_attachment_image_src( $attach_id, 'thumbnail' );
					
				}
				// update_post_meta( $sid, '_project_is_featured', 0);

				//$attach_id = media_handle_upload($file, )

				foreach($attachment_ids as $attached_file_id){
					update_post_meta( $sid, '_service_attachment_ids', $attached_file_id);
				}
				// $c_dATE = DATE("d-m-Y");
				// $default_project_expiry = fl_framework_get_options('project_default_expiry');
				// $simple_project_expiry_date = date('d-m-Y', strtotime($c_dATE. " + $default_project_expiry days"));
				// update_post_meta($pid, '_simple_projects_expiry_date', $simple_project_expiry_date);
				

				


			}
			
			//die();
		}
		return new WP_REST_RESPONSE(
			["message" => "success"]
		);


	}







?>