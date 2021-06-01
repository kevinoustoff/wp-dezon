<?php 
 /* Template Name: Freelancer Search */ 
/**
 * The template for displaying Pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Exertio
 */
?>
<?php get_header(); ?>
<?php
if(in_array('exertio-framework/index.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
	$order ='ASC';
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' );
	} else if ( get_query_var( 'page' ) ) {
		/*This will occur if on front page.*/
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}
	$title ='';
	if (isset($_GET['title']) && $_GET['title'] != "") {
		$title = $_GET['title'];
	}


	$freelance_type = '';
	if (isset($_GET['freelance-type']) && $_GET['freelance-type'] != "") {
		$freelance_type = array(
			array(
				'taxonomy' => 'freelance-type',
				'field' => 'term_id',
				'terms' => $_GET['freelance-type'],
			),
		);
	}
	$location = '';
	if (isset($_GET['location']) && $_GET['location'] != "") {
		$location = array(
			array(
				'taxonomy' => 'freelancer-locations',
				'field' => 'term_id',
				'terms' => $_GET['location'],
			),
		);
	}
	$english_level = '';
	if (isset($_GET['english-level']) && $_GET['english-level'] != "") {
		$english_level = array(
			array(
				'taxonomy' => 'freelancer-english-level',
				'field' => 'term_id',
				'terms' => $_GET['english-level'],
			),
		);
	}
	$language = '';
	if (isset($_GET['language']) && $_GET['language'] != "") {
		$language = array(
			array(
				'taxonomy' => 'freelancer-languages',
				'field' => 'term_id',
				'terms' => $_GET['language'],
			),
		);
	}
	$skill = '';
	if (isset($_GET['skill']) && $_GET['skill'] != "") {
		$skill = array(
			array(
				'taxonomy' => 'freelancer-skills',
				'field' => 'term_id',
				'terms' => $_GET['skill'],
			),
		);
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
			'key' => '_freelancer_hourly_rate',
			'value' => array($price_min, $price_max),
			'type' => 'numeric',
			'compare' => 'BETWEEN',
		);
	}
	$tagline ='';
	if (isset($_GET['title']) && $_GET['title'] != "")
	{
		$tagline = array(
			'key'       => '_freelancer_tagline',
			'value'     => $_GET['title'],
			'compare'   => 'LIKE',
		);
	}
	$dispaly_name ='';
	if (isset($_GET['title']) && $_GET['title'] != "")
	{
		$dispaly_name = array(
			'key'       => '_freelancer_dispaly_name',
			'value'     => $_GET['title'],
			'compare'   => 'LIKE',
		);
	}
	$email_verified = '';
	if(fl_framework_get_options('freelancer_show_non_verified') != null && fl_framework_get_options('freelancer_show_non_verified') == true)
	{
		$email_verified =  array(
					 'key' => 'is_freelancer_email_verified',
					 'value' => '1',
					'compare'   => '=',
					);
	}


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
	
		$args	=	array
		(
			'author__not_in' => array( 1 ),
			's' => $title,
			'post_type' => 'freelancer',
			'post_status' => 'publish',
			'posts_per_page' => get_option('posts_per_page'),
			'paged' => $paged,
			'meta_key'  => '_freelancer_is_featured',
			'meta_query'    => array(
				'relation' => 'OR',
				$price,
				$tagline,
				$dispaly_name,
				$email_verified,
			),
			'tax_query' => array(
				$freelance_type,
				$english_level,
				$location,
				$language,
				$skill
			),
			'orderby'  => array(
				'meta_value' => 'DESC',
				'title'      => $order,
			),

		);
		$results = new WP_Query( $args );

		require trailingslashit(get_template_directory()) . 'template-parts/search/freelancer/search-freelancers.php';
}
else
{
	wp_redirect(home_url());
}
?>
<?php get_footer(); ?>