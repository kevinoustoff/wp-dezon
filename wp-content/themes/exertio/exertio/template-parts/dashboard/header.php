<?php get_header(); 
global $exertio_theme_options;
$img_id ='';
$current_user_id = get_current_user_id();
$emp_id = get_user_meta( $current_user_id, 'employer_id' , true );


$fre_id = get_user_meta( $current_user_id, 'freelancer_id' , true );

?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url($exertio_theme_options['dasboard_logo']['url']); ?>" alt="<?php echo get_post_meta($img_id, '_wp_attachment_image_alt', TRUE); ?>"/></a>
          <a class="navbar-brand brand-logo-mini" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url($exertio_theme_options['dasboard_logo']['url']); ?>" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <ul class="navbar-nav navbar-nav-right">
        	
          <li class="nav-item nav-profile dropdown">
          	<?php
				if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'employer' || isset($_COOKIE["active_profile"]) == '')
				{
					$active_user = 	$emp_id;
					$profile_image = get_profile_img($active_user, "employer");

					$user_name = exertio_get_username('employer',$active_user );
					$user_type = esc_html__('Client','exertio_theme');
                }
				else if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'freelancer')
				{
					$active_user = 	$fre_id;
					$profile_image = get_profile_img($active_user, "freelancer");

					$user_name = exertio_get_username('freelancer',$active_user );
					$user_type = esc_html__('Prestataire','exertio_theme');
				}
			
			?>
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <?php echo wp_return_echo($profile_image); ?>
              <div class="nav-profile-meta">
                  <span class="nav-profile-name"><?php echo esc_html($user_name); ?></span>
                  <small> <?php echo esc_html ($user_type); ?></small>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
            <ul>
            <?php
				$amount = get_user_meta( $current_user_id, '_fl_wallet_amount', true );
			?>
            <li class="dropdown-item wallet-contanier">
                <div>
                	<span class="text"> <?php echo esc_html__('Solde','exertio_theme'); ?></span>
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
                    <span> <a href=""><?php echo esc_html__('Retirer des fonds','exertio_theme'); ?> <i class="fas fa-arrow-right"></i></a></span>
                </div>
            </li>
            <li>
                <a class="dropdown-item profile_selection  <?php if(isset($_COOKIE["active_profile"]) && $_COOKIE["active_profile"] == 'freelancer') { echo 'selected'; } ?>" data-profile-active="freelancer" href="javascript:void(0)">
                    <span class="profile-img">
                        <?php
                            $pro_img_id = get_post_meta( $fre_id, '_profile_pic_freelancer_id', true );
                            $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                            
                            if(wp_attachment_is_image($pro_img_id))
                            {
                                ?>
                                <img src="<?php echo esc_url($pro_img[0]); ?>" alt="<?php echo esc_attr(get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid">
                                <?php
                            }
                            else
                            {
                                ?>
                                <img src="<?php echo esc_url($exertio_theme_options['freelancer_df_img']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid">
                                <?php	
                            }
                        ?>
                    </span>
                    <span>
                        <h4>
                            <?php
                                if (strlen(esc_attr(get_post_meta( $fre_id, '_freelancer_dispaly_name' , true ))) > 20)
                                {
                                   echo substr(exertio_get_username('freelancer',$fre_id ), 0, 20) . ' ...';
                                }
                                else
                                {
									echo exertio_get_username('freelancer',$fre_id );
                                }
                            ?>
                        </h4>
                        <p><?php echo esc_html__('Prestataire','exertio_theme'); ?></p>
                    </span>
                </a>
            </li>
            <li>
                <a class="dropdown-item <?php if(isset($_COOKIE["active_profile"]) &&  $_COOKIE["active_profile"] == 'employer') { echo 'selected'; } else if(empty($_COOKIE["active_profile"])) { echo 'selected'; } ?> profile_selection" data-profile-active="employer" href="javascript:void(0)">
                    <span class="profile-img">
                        <?php
                            $pro_img_id = get_post_meta( $emp_id, '_profile_pic_attachment_id', true );
                            $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                            
                            //if(!empty($pro_img_id))
                            if(wp_attachment_is_image($pro_img_id))
                            {
                                ?>
                                <img src="<?php echo esc_url($pro_img[0]); ?>" alt="<?php echo esc_attr(get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid">
                                <?php
                            }
                            else
                            {
                                ?>
                                <img src="<?php echo esc_url($exertio_theme_options['employer_df_img']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($img_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid">
                                <?php	
                            }
                        ?>
                    </span>
                    <span>
                        <h4>
                            <?php 
                                if (strlen(esc_attr(get_post_meta( $emp_id, '_employer_dispaly_name' , true ))) > 20)
                                {
                                   echo substr(exertio_get_username('employer',$emp_id ), 0, 20) . ' ...';
                                }
                                else
                                {
									echo exertio_get_username('employer',$emp_id );
                                }
                            ?>
                        </h4>
                        <p><?php echo esc_html__('Client','exertio_theme'); ?></p>
                    </span>
                </a>
            </li>
            <li>
              <a class="dropdown-item" href="<?php echo esc_url(get_the_permalink());?>?ext=edit-profile">
                <i class="mdi mdi-settings text-primary"></i>
                <?php echo esc_html__('Modifier le profil','exertio_theme'); ?>
              </a>
            </li>
            <li>
                <a href="<?php echo wp_logout_url( get_the_permalink( $exertio_theme_options['login_page'] ) ); ?>" class="dropdown-item">
                	<i class="mdi mdi-logout text-primary"></i>
					<?php echo esc_html__('Se déconnecter','exertio_theme'); ?>
                </a>
              </li>
              </ul>
            </div>
          </li>
          <li class="fr-list nav-item dropdown mr-4 nav-btn-post">
			<?php
			  $employer_btn_text_login = fl_framework_get_options('employer_btn_text_login');
			  $freelancer_btn_text_login = fl_framework_get_options('freelancer_btn_text_login');

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
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>