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
	register_rest_route('api','/forgot-password',array(
		'methods' => 'POST',
		'callback' =>  'forgetPwd',
	));
	register_rest_route('api','/forgot-password/set-new',array(
		'methods' => 'POST',
		'callback' =>  'setPasswordAfterLoss',
	));
	register_rest_route('api','/recover-password',array(
		'methods' => 'POST',
		'callback' =>  'changePassword',
	));
	register_rest_route('api','/projets',array(
		'methods' => 'GET',
		'callback' =>  'getListeProjets',
	));
	register_rest_route('api','/projets/mine',array(
		'methods' => 'GET',
		'callback' =>  'myProjects',
	));

	register_rest_route('api','/projets/saved',array(
		'methods' => 'GET',
		'callback' =>  'savedProjects',
	));
	register_rest_route('api','/projets/save',array(
		'methods' => 'POST',
		'callback' =>  'saveSingleProject',
	));
	

	register_rest_route('api','/projets/search',array(
		'methods' => 'GET',
		'callback' =>  'filtersProjects',
	));
	register_rest_route('api','/delais-livraisons-services',array(
		'methods' => 'GET',
		'callback' =>  'listeDelaiLivraisons',
	));
	register_rest_route('api','/list-english-levels',array(
		'methods' => 'GET',
		'callback' =>  'listeEnglishLevels',
	));
	register_rest_route('api','/list-services-locations',array(
		'methods' => 'GET',
		'callback' =>  'listeServicesLocations',
	));
	register_rest_route('api','/services/search',array(
		'methods' => 'GET',
		'callback' =>  'searchServicesApiVersion',
	));
	register_rest_route('api','/projet',array(
		'methods' => 'GET',
		'callback' =>  'getSingleProject',
	));
	register_rest_route('api','/service',array(
		'methods' => 'GET',
		'callback' =>  'getServiceDetail',
	));
	register_rest_route('api','/services/search/filters',array(
		'methods' => 'GET',
		'callback' =>  'getServicesSearchFilters',
	));
	register_rest_route('api','/freelancers/top',array(
		'methods' => 'GET',
		'callback' =>  'freelancersTop',
	));
	register_rest_route('api','/services/mine',array(
		'methods' => 'GET',
		'callback' =>  'getMyServices',
	));
	register_rest_route('api','/identification/verification',array(
		'methods' => 'POST',
		'callback' =>  'idVerificationProccess',
	));
	register_rest_route('api','/identification/verification/revoke',array(
		'methods' => 'GET',
		'callback' =>  'revoke_verification',
	));
	register_rest_route('api','/identification/verification/status',array(
		'methods' => 'GET',
		'callback' =>  'checkIfUserHasBeenRevoked',
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