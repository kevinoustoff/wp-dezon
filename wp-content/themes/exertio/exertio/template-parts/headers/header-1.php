<?php
$main_logo = fl_framework_get_options('frontend_logo');
$img_id = $header_transparent_option = $header_size = $main_logo_url = '';
global $exertio_theme_options;

if(isset($main_logo) && $main_logo != '')
{
	$main_logo_url = $main_logo['url'];
}
else
{
	$main_logo_url = get_template_directory_uri().'/images/logo-dashboard.svg';
}


$img_id ='';
$current_user_id = get_current_user_id();
$emp_id = get_user_meta( $current_user_id, 'employer_id' , true );


$fre_id = get_user_meta( $current_user_id, 'freelancer_id' , true );
$header_width = fl_framework_get_options('header_size');

$header_size = 'container';
if(isset($header_width) && $header_width == 1)
{
	$header_size = 'container';
}
else if(isset($header_width) && $header_width == 0 )
{
	$header_size = 'container-fluid';
}
$header_transparent = fl_framework_get_options('header_transparent');
//$home_page3 = get_page_by_title('Home 3');
//print_r($home_page3);
$page_id = get_the_ID();
if(isset($header_transparent) && $header_transparent == 1 || $page_id == '589')
{
	$header_transparent_option = '';
}
else if(isset($header_transparent) && $header_transparent == 2 && is_page_template( 'page-home.php'  ))
{
	$header_transparent_option = 'transparent';
}
?>
    <!-- Header -->
    <div class="fr-menu sb-header header-shadow <?php echo esc_attr($header_transparent_option); ?>">
      <div class="<?php echo esc_html($header_size); ?>"> 
        <!-- sb header -->
        <div class="sb-header-container"> 
          <!--Logo-->
          <div class="logo" data-mobile-logo="<?php echo esc_url($main_logo_url); ?>" data-sticky-logo="<?php echo esc_url($main_logo_url); ?>"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url($main_logo_url); ?>" alt="<?php echo get_post_meta($img_id, '_wp_attachment_image_alt', TRUE); ?>"/></a> </div>
          
          <!-- Burger menu -->
          <div class="burger-menu">
            <div class="line-menu line-half first-line"></div>
            <div class="line-menu"></div>
            <div class="line-menu line-half last-line"></div>
          </div>
          
          <!--Navigation menu-->
          <nav class="sb-menu menu-caret submenu-top-border submenu-scale">
            <ul>
              <?php exertio_main_menu( 'main_theme_menu' ); ?>
              <?php
			  $btn_text = fl_framework_get_options('header_btn_text');
			  $Second_btn_text = fl_framework_get_options('secondary_btn_text');
			  $employer_btn_text_login = fl_framework_get_options('employer_btn_text_login');
			  $freelancer_btn_text_login = fl_framework_get_options('freelancer_btn_text_login');
                if( is_user_logged_in() )
                {
                    ?>
                    <li class="fr-list">
                        <?php
						if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'employer' || isset($_COOKIE["active_profile"]) == '')
						{
							if($employer_btn_text_login != '')
							{
								?>
								<a href="<?php echo fl_framework_get_options('employer_btn_page_login'); ?>" class="btn btn-theme-secondary style-1"> <?php echo esc_html($employer_btn_text_login); ?> </a>
								<?php
							}
						}
						if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'freelancer')
						{
							if($freelancer_btn_text_login != '')
							{
								?>
								<a href="<?php echo fl_framework_get_options('freelancer_btn_page_login'); ?>" class="btn btn-theme"><?php echo esc_html($freelancer_btn_text_login); ?></a>
								<?php
							}
						}
                        ?>
                    </li>
                    <li class="submenu-right dropdown_menu fr-list loggedin">
                    <?php
                        //echo isset($_COOKIE["active_profile"]).'asa';
                        if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'employer' || isset($_COOKIE["active_profile"]) == '')
                        {
                            $active_user = 	$emp_id;
                            $profile_image = get_profile_img($active_user, "employer");
                        }
                        else if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'freelancer')
                        {
                            $active_user = 	$fre_id;
                            $profile_image = get_profile_img($active_user, "freelancer");
                        }
                    
                    ?>
                    <a href="javascript:void(0)">
                        <?php echo wp_return_echo($profile_image); ?>
                    </a>
                    <ul>
                        <?php
                            $amount = get_user_meta( $current_user_id, '_fl_wallet_amount', true );
                        ?>
                        <li class="wallet-contanier">
                            <a href="javascript:void(0)" class="dropdown-item">
                                <div>
                                    <span class="text"> <?php echo esc_html__('Solde ','exertio_theme'); ?></span>
                                    <h4>
                                        <?php 
                                        if(empty($amount))
                                        {
                                            echo esc_html(fl_price_separator(0)); 
                                        }
                                        else
                                        {
                                            echo esc_html(fl_price_separator($amount));
                                        }
                                        
                                        ?>
                                    </h4>
                                    <span> Voir le détail <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </a>
                        </li>
                        <?php
                        $dashboard_page = fl_framework_get_options('user_dashboard_page');
                        ?>
                        <li> <a class="dropdown-item" href="<?php echo esc_url(get_the_permalink($dashboard_page)); ?>"><?php echo esc_html__('Tableau de bord','exertio_theme'); ?></a> </li>
                        <li> <a class="dropdown-item" href="<?php echo esc_url(get_the_permalink($dashboard_page));?>?ext=edit-profile"><?php echo esc_html__('Modifier le profil','exertio_theme'); ?></a> </li>
                        <li> <a class="dropdown-item" href="<?php echo wp_logout_url(get_the_permalink(fl_framework_get_options('login_page'))); ?>"><?php echo esc_html__('Se déconnecter','exertio_theme'); ?></a> </li>
                    </ul>
                    </li>
                    <?php
                }
                else
                {
					?>
                    <li class="fr-list">
                        <?php
                        if($Second_btn_text != '')
                        {
                            ?>
                            <a href="<?php echo get_the_permalink(fl_framework_get_options('secondary_btn_page')); ?>" class="btn-theme-secondary style-1"> <?php echo esc_html($Second_btn_text); ?> </a>
                            <?php
                        }
                        if($btn_text != '')
                        {
                            ?>
                            <a href="<?php echo get_the_permalink(fl_framework_get_options('header_btn_page')); ?>" class="btn btn-theme"><?php echo esc_html($btn_text); ?></a>
                            <?php
                        }
                        ?>
                    </li>
                    <?php
                }
			  ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>