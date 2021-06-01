<?php 
function exertio_framework_hide_admin_bar($show)
{
	if ( ! current_user_can('administrator'))
	{
		return false;
	}
	return $show;
}
add_filter( 'show_admin_bar', 'exertio_framework_hide_admin_bar' );
function exertio_get_terms($term_name = '', $hide_empty = false)
{
	$terms = get_terms( array(
                'taxonomy' => $term_name,
                'hide_empty' => $hide_empty,
				'orderby'      => 'name',
            ) );
	return $terms;
}


/* ENQUEUE MEDIA LIBRARY AND SCRIPT */
function services_attachment_wp_admin_enqueue() {
	wp_enqueue_media();
	
	wp_enqueue_script( 'jquery-ui', FL_PLUGIN_URL. 'js/jquery-ui.js', array('jquery'), true, true );
	
	wp_enqueue_script( 'jquery-datetimepicker', FL_PLUGIN_URL. 'js/jquery.datetimepicker.full.js', array('jquery'), true, true );
	
	wp_enqueue_script( 'attachment_script', FL_PLUGIN_URL. 'js/attachment-upload.js', array('jquery'), true, true );
	
	wp_localize_script('attachment_script', 'exertio_localize_vars', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'selectAttachments' => esc_html__('Select Attachments', 'exertio_framework'),
		'attachmentAdd' => esc_html__('Ad Files', 'exertio_framework'),
		'selectImage' => esc_html__('Select Image', 'exertio_framework'),
		'pluginUrl' => FL_PLUGIN_URL,
		'maxTemUrlFields' => esc_html__('You can add max 10 new fields', 'exertio_framework'),
		'ConfirmText' => esc_html__('Are your sure?', 'exertio_framework'),
		'WentWorng' => esc_html__('Something went wrong', 'exertio_framework'),
		'awardDate' => esc_html__('Award Date', 'exertio_framework'),
		'awardName' => esc_html__('Award Name', 'exertio_framework'),
		'projectURL' => esc_html__('Project URL', 'exertio_framework'),
		'projectName' => esc_html__('Project Name', 'exertio_framework'),
		'expeName' => esc_html__('Experience Title', 'exertio_framework'),
		'expeCompName' => esc_html__('Company Name', 'exertio_framework'),
		'startDate' => esc_html__('Start Date', 'exertio_framework'),
		'endDate' => esc_html__('End Date', 'exertio_framework'),
		'endDatemsg' => esc_html__('Leave it empty to set it current job', 'exertio_framework'),
		'expeDesc' => esc_html__('Description', 'exertio_framework'),
		'eduName' => esc_html__('Education Title', 'exertio_framework'),
		'eduInstName' => esc_html__('Institute Name', 'exertio_framework'),
		'startDate' => esc_html__('Start Date', 'exertio_framework'),
		'endDate' => esc_html__('End Date', 'exertio_framework'),
		'eduEndDatemsg' => esc_html__('Leave it empty to set it current education', 'exertio_framework'),
		
			)
	);
}
add_action( 'admin_enqueue_scripts', 'services_attachment_wp_admin_enqueue' );
	
	


// define the function to be fired for logged in users
add_action("wp_ajax_get_my_terms", "get_my_terms");
function get_my_terms() {
	//echo $_POST['tax_name'];
	$tax_terms = exertio_get_terms($_POST['tax_name']);
	$terms .= '<select name="freelancer_skills[]">';
	foreach( $tax_terms as $tax_term ) {
		if( $tax_term->parent == 0 ) {
			 $terms .= '<option value="'. esc_attr( $tax_term->term_id ) .'">'. esc_html( $tax_term->name ) .'</option>';
		}
	}
	$terms .= '</select>';
	
	
	$html = '<div class="ui-state-default"><span class="dashicons dashicons-move"></span><div class="col-4">'.$terms.'</div><div class="col-4"><input type="number" name="skills_percent[]" placeholder="'.__( "Skills percentage", 'exertio_framework' ).'"></div><a href="javascript:void(0);" class="remove_button"><img src="'.FL_PLUGIN_URL.'/images/error.png" >	</a></div>';
	
	echo $html;
	die;
}


add_action("wp_ajax_get_my_skills_terms", "get_my_skills_terms");
if ( ! function_exists( 'get_my_skills_terms' ) )
{
	function get_my_skills_terms() {

		$tax_terms = exertio_get_terms($_POST['tax_name']);
		$terms .= '<select name="freelancer_skills[]" class="form-control general_select">';
		foreach( $tax_terms as $tax_term ) {
			if( $tax_term->parent == 0 ) {
				 $terms .= '<option value="'. esc_attr( $tax_term->term_id ) .'">'. esc_html( $tax_term->name ) .'</option>';
			}
		}
		$terms .= '</select>';
		
		
		$html = '<div class="ui-state-default"><i class="far fa-arrows"></i><div class="form-row"><div class="form-group col-md-6">'.$terms.'</div><div class="form-group col-md-6"><input type="number" name="skills_percent[]" placeholder="'.__( "Skills percentage", 'exertio_framework' ).'" class="form-control"></div></div><a href="javascript:void(0);" class="remove_button"><i class="fas fa-times-circle"></i></a></div>';
		
		echo $html;
		die;
	}
}

/*THEME OPTION FUNCTION PLACED HERE AGAINST THEEM CHECK ERROR*/
 if ( ! function_exists( 'remove_demo' ) )
 {
	function remove_demo() {
		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array(
				ReduxFrameworkPlugin::instance(),
				'plugin_metalinks'
			), null, 2 );
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}
}