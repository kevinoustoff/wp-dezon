<?php
global $exertio_theme_options; 

if (is_page_template('page-profile.php'))
{
}
else if ( is_page_template( 'page-login.php' ) && $exertio_theme_options['login_footer_show'] == 0)
{
}
else if ( is_page_template( 'page-register.php' ) && $exertio_theme_options['register_footer_show'] == 0)
{
}
else 
{
	get_template_part( 'template-parts/footer/footer','1' );
}

	if ( is_page_template( 'page-login.php' ))
	{
		get_template_part( 'template-parts/auth/password','reset' ); 
	}
	if ( is_singular( 'freelancer' ) || is_singular( 'services' ) || is_singular( 'projects' ) || is_singular( 'employer' ) )
	{
		get_template_part( 'template-parts/auth/report','' ); 
	}
wp_footer();
?>
<input type="hidden" id="freelance_ajax_url" value="<?php echo admin_url('admin-ajax.php'); ?>" />
<input type="hidden" id="gen_nonce" value="<?php echo wp_create_nonce('fl_gen_secure'); ?>" />
<input type="hidden" id="nonce_error" value="<?php echo esc_html__('Something went wrong','exertio_theme'); ?>" />
</body>
</html>