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














?>