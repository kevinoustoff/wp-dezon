<?php
include "rest-api-function.php";
add_action( 'rest_api_init', function () {
	register_rest_route( 'monplugin/v1', '/demo', array(
	  'methods' => 'GET',
	  'callback' => function(){
		  return new WP_Error('rien','rien a dire',['status'=> 404]);
	  },
	) );
	register_rest_route( 'hello', '/demo', array(
		'methods' => 'GET',
		'callback' => 'hello'
	  ) );
	register_rest_route('api','/login',array(
		'methods' => 'POST',
		'callback' => 'api_login'
	));
	register_rest_route('api','/register',array(
		'methods' => 'POST',
		'callback' => 'api_register'
	));
	register_rest_route('api','/profile',array(
		'methods' => 'GET',
		'callback' =>  'profile',
	));
	register_rest_route('api','/services/last',array(
		'methods' => 'GET',
		'callback' =>  'lastServices',
	));
	register_rest_route('api','/services/last',array(
		'methods' => 'GET',
		'callback' =>  'lastServices',
	));
	register_rest_route('api','/sexes',array(
		'methods' => 'GET',
		'callback' =>  'getSexes',
	));
	register_rest_route('api','/english-levels',array(
		'methods' => 'GET',
		'callback' =>  'getEnglishLevel',
	));

	register_rest_route('api','/locations',array(
		'methods' => 'GET',
		'callback' =>  'getLocations',
	));

	register_rest_route('api','/types-prestataires',array(
		'methods' => 'GET',
		'callback' =>  'getTypePrestataire',
	));
	register_rest_route('api','/langues-prestataires',array(
		'methods' => 'GET',
		'callback' =>  'getLanguesPrestataires',
	));
  } );

function my_awesome_func( $data ) {
$posts = get_posts( array(
	'author' => $data['id'],
) );

if ( empty( $posts ) ) {
	return null;
}

return $posts[0]->post_title;
}

?>