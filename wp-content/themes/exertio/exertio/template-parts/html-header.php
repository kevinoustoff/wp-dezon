<?php
if(in_array('redux-framework/redux-framework.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
	global $exertio_theme_options;
	$preloader = $exertio_theme_options['website_preloader'];
}
else
{
	$preloader = '';
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="loader-outer">
	<div class="loading-inner">
		<div class="loading-inner-meta">
			<div> </div>
			<div></div>
		</div>
	</div>
</div>
<?php
if(isset($preloader) && $preloader == 1)
{
	?>
	<div class="exertio-loader-container">
		<div class="exertio-loader">
		  <span class="exertio-dot"></span>
		  <div class="exertio-dots">
			<span></span>
			<span></span>
			<span></span>
		  </div>
		</div>
	</div>
	<?php
}
?>
<?php

if (is_page_template('page-profile.php')) 
{

}
else if ( is_page_template( 'page-login.php' ) && $exertio_theme_options['login_header_show'] == 0)
{
}
else if ( is_page_template( 'page-register.php' ) && $exertio_theme_options['register_header_show'] == 0)
{
}
else
{
	get_template_part( 'template-parts/headers/header','1' );
}