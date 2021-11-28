<?php
global $exertio_theme_options;
$fl_id = get_the_ID();
$author_id = get_post_field( 'post_author', $fl_id );
$banner_img_id = get_post_meta( $fl_id, '_freelancer_banner_id', true );
$banner_img = wp_get_attachment_image_src( $banner_img_id, 'full' );
$cover_img ='';
if(empty($banner_img ))
{
	$cover_img = "style='background-image:url(".$exertio_theme_options['freelancer_df_cover']['url'].")'";
}
else
{
	$cover_img = "style='background-image:url(".$banner_img[0].")'";
}
?> 
<section class="fr-hero-theme freelancer" <?php echo wp_return_echo($cover_img); ?>> </section>
<section class="fr-hero-detail style-1">
  <div class="container">
    <div class="row custom-product">
      <div class="col-lg-9 col-xl-9 col-xs-12 col-md-9 col-sm-12">
        <div class="fr-hero-details-content">
          <div class="fr-hero-details-products"> <?php echo get_profile_img($fl_id, 'freelancer', 'full'); ?></div>
          <div class="fr-hero-details-information">
            <span class="title"><?php echo exertio_get_username('freelancer', $fl_id, 'badge', 'left'); ?></span>
          	<h1 class="name"><?php echo esc_html(get_post_meta( $fl_id, '_freelancer_tagline' , true )); ?></h1>
            <div class="fr-hero-m-deails">
              <ul>
              	<?php
				if($exertio_theme_options['fl_location'] == 3)
				{
					
				}
				else
				{
				?>
                <li> <span><?php echo get_term_names('freelancer-locations', '_freelancer_location', $fl_id, '', ',' ); ?></span> </li>
                <?php
				}
				?>
                <li> <span> <?php echo esc_html__('Membre depuis ','exertio_theme').get_the_date(); ?></span> </li>
                <li><span><?php echo get_rating($fl_id, ''); ?></span> </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-xl-3 col-xs-12 col-md-3 col-sm-12">
        <div class="fr-hero-hire">
        	
          <div class="fr-hero-short-list-2">
            <div class="fr-hero-hire-content">
            	<?php
      					/*
                $current_user_id = get_current_user_id();
      					$saved_freelancer = get_user_meta($current_user_id, '_fl_follow_id_'.$fl_id, true);
      					$active_saved ='';
      					$save_text = esc_html__('Suivre ce prestataire','exertio_theme');
      					if(isset($saved_freelancer) && $saved_freelancer != '')
      					{
      						$active_saved = 'active';
      						$save_text = esc_html__('Déjà suivi','exertio_theme');	
      					}
                */
      				?>
              <?php
                    if( fl_framework_get_options('whizzchat_service_option') == true)
                    {
                      if(in_array('whizz-chat/whizz-chat.php', apply_filters('active_plugins', get_option('active_plugins'))))
                      {
                      ?>
                        <a href="javascript:void(0)" class="chat_toggler btn btn-theme" data-user_id="<?php echo esc_attr($author_id); ?>" data-page_id='<?php echo esc_attr($fl_id); ?>'>
                          <i class="far fa-comment-alt"></i>
                          <?php echo esc_html__( 'Contacter', 'exertio_theme' ); ?>
                        </a>
                  <?php
                      }
                    }
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
		if(isset($exertio_theme_options['freelancer_states']) && $exertio_theme_options['freelancer_states'] == 2)
		{
	?>
            <div class="row">
                <div class="col-lg-12 col-xl-12 col-xs-12 col-md-12 col-sm-12">
                    <div class="fr-hero-m-jobs-bottom">
                      <ul>
                      <?php
					  	$meta_query = array(
										'key'       => '_freelancer_assigned',
										'value'     => $fl_id,
										'compare'   => '=',
									);
					  ?>
                        <li> <span><small><?php echo exertio_get_posts_count('', 'projects', '', 'ongoing', $meta_query); ?></small> <?php echo esc_html__('Projets en cours','exertio_theme'); ?></span> </li>
                        <li> <span><small><?php echo exertio_get_posts_count('', 'projects', '', 'completed', $meta_query); ?></small> <?php echo esc_html__('Projets finalisés','exertio_theme'); ?></span> </li>
                        <li> <span><small><?php echo exertio_get_services_count($fl_id,'ongoing'); ?></small> <?php echo esc_html__('Jobs en cours','exertio_theme'); ?></span> </li>
                        <li> <span><small><?php echo exertio_get_services_count($fl_id,'completed'); ?></small> <?php echo esc_html__('Jobs terminés','exertio_theme'); ?></span> </li>
                      </ul>
                    </div>
                </div>
            </div>
    <?php
		}
	?>
  </div>
</section>
