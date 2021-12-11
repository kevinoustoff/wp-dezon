<?php
$current_user_id = get_current_user_id();
$pid = get_user_meta( $current_user_id, 'employer_id' , true );
global $exertio_theme_options;

$user_info = get_userdata($current_user_id);
$page_name ='';
if(isset($_GET['ext']) && $_GET['ext'] !="")
{
	$page_name = $_GET['ext'];	
}
$alt_id ='';
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
    <li class="profile">
    	<div>
            <span class="pro-img">
            <?php
                $pro_img_id = get_post_meta( $pid, '_profile_pic_attachment_id', true );
                $pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
                

				if(wp_attachment_is_image($pro_img_id))
                {
                    ?>
                    <img src="<?php echo esc_url($pro_img[0]); ?>" alt="<?php echo esc_attr(get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid" loading="lazy">
                    <?php
                }
                else
                {
                    ?>
                    <img src="<?php echo esc_url($exertio_theme_options['employer_df_img']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE)); ?>" class="img-fluid" loading="lazy">
                    <?php	
                }
            ?>
            </span>
        </div>
        <h4 class="mt-4"><?php echo exertio_get_username('employer', $pid, 'badge', 'right'); ?></h4>
        <p><?php echo esc_html($user_info->user_email); ?></p>
      </li>
      <li class="nav-item <?php if($page_name == 'dashboard') { echo 'active';} ?>">
        <a class="nav-link" href="<?php echo get_the_permalink();?>?ext=dashboard">
          <i class="fas fa-home menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__('Tableau de bord','exertio_theme'); ?></span>
        </a>
      </li>
      <li class="nav-item <?php if($page_name == 'edit-profile') { echo 'active';} ?>">
        <a class="nav-link" data-toggle="collapse" aria-expanded="false"  href="#profile" aria-controls="profile">
          <i class="fas fa-user menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Mon compte', 'exertio_theme' ); ?></span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse <?php if($page_name == 'edit-profile' ){ echo 'show';} ?>" id="profile">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item <?php if($page_name == 'edit-profile') { echo 'active';} ?>"> <a class="nav-link" href="<?php  echo esc_url(get_permalink($pid)); ?>"> <?php echo esc_html__( 'Voir le profil', 'exertio_theme' ); ?> </a></li>
            <li class="nav-item"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=edit-profile"> <?php echo esc_html__( 'Modifier le profil', 'exertio_theme' ); ?> </a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item <?php if($page_name == 'create-project') { echo 'active';} ?>">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
          <i class="fas fa-briefcase menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Mes projets', 'exertio_theme' ); ?></span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse <?php if($page_name == 'create-project' || $page_name == 'projects' || $page_name == 'ongoing-projects' || $page_name == 'completed-projects' || $page_name == 'canceled-projects' || $page_name == 'pending-projects' || $page_name == 'project-propsals' || $page_name == 'ongoing-project' || $page_name == 'ongoing-project-detail' || $page_name == 'ongoing-project-proposals') { echo 'show';} ?>" id="ui-basic">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item <?php if($page_name == 'create-project') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=create-project"><?php echo esc_html__( 'Publier un projet', 'exertio_theme' ); ?></a></li>
            <li class="nav-item <?php if($page_name == 'projects') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=projects"><?php echo esc_html__( 'Projets publiés', 'exertio_theme' ); ?></a></li>
            <li class="nav-item <?php if($page_name == 'ongoing-project') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=ongoing-project"><?php echo esc_html__( 'Projets en cours', 'exertio_theme' ); ?></a></li>
            <li class="nav-item <?php if($page_name == 'completed-projects') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=completed-projects"><?php echo esc_html__( 'Projets terminés', 'exertio_theme' ); ?></a></li>
            <li class="nav-item <?php if($page_name == 'canceled-projects') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=canceled-projects"><?php echo esc_html__( 'Projets annnulés', 'exertio_theme' ); ?></a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#services" aria-expanded="false" aria-controls="services">
          <i class="fas fa-user-cog menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Jobs acceptés', 'exertio_theme' ); ?></span>
          <i class="menu-arrow"></i>
        </a>
        <div class="collapse <?php if($page_name == 'ongoing-services' || $page_name == 'completed-services' || $page_name == 'completed-service-detail' || $page_name == 'canceled-services' || $page_name == 'canceled-service-detail') { echo 'show';}?>" id="services">
          <ul class="nav flex-column sub-menu">
            <li class="nav-item <?php if($page_name == 'ongoing-services') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=ongoing-services"><?php echo esc_html__( 'Jobs en cours', 'exertio_theme' ); ?> </a></li>
            <li class="nav-item <?php if($page_name == 'completed-services') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=completed-services"><?php echo esc_html__( 'Jobs terminés', 'exertio_theme' ); ?> </a></li>
            <li class="nav-item <?php if($page_name == 'canceled-services') { echo 'active';} ?>"> <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=canceled-services"><?php echo esc_html__( 'Jobs annulés', 'exertio_theme' ); ?> </a></li>
          </ul>
        </div>
      </li>
		<?php
		if(in_array('whizz-chat/whizz-chat.php', apply_filters('active_plugins', get_option('active_plugins'))))
		{
			global $whizzChat_options;
			$dashboard_page = isset($whizzChat_options['whizzChat-dashboard-page']) && $whizzChat_options['whizzChat-dashboard-page'] != '' ? $whizzChat_options['whizzChat-dashboard-page'] : 'javascript:void(0)';
		}
		?>
      <li class="nav-item <?php if($page_name == 'saved-services') { echo 'active';} ?>">
        <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=saved-services">
          <i class="far fa-bookmark menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Jobs enregistrés', 'exertio_theme' ); ?> </span>
        </a>
      </li>
      <li class="nav-item <?php if($page_name == 'invoices') { echo 'active';} ?>">
        <a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=invoices">
          <i class="fas fa-receipt menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Portefeuille', 'exertio_theme' ); ?> </span>
        </a>
      </li>
	<li class="nav-item <?php if($page_name == 'identity-verification') { echo 'active';} ?>">
		<a class="nav-link" href="<?php echo esc_url(get_the_permalink());?>?ext=identity-verification">
			<i class="fas fa-user-shield menu-icon"></i>
			<span class="menu-title"><?php echo esc_html__( 'Vérification de l\'identité', 'exertio_theme' ); ?> </span>
		</a>
	</li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo wp_logout_url( get_the_permalink( $exertio_theme_options['login_page'] ) ); ?>">
          <i class="fas fa-sign-out-alt menu-icon"></i>
          <span class="menu-title"><?php echo esc_html__( 'Se déconnecter', 'exertio_theme' ); ?> </span>
        </a>
      </li>
    </ul>
</nav>
          