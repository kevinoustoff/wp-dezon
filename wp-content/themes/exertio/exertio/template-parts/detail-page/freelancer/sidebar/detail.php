<?php
$fl_id = get_the_ID();
global $exertio_theme_options;
$post_author = get_post_field( 'post_author', $fl_id );
$user_info = get_userdata($post_author);
?>
<div class="fr-recent-employers sidebar-box">
	<?php
	if(isset($exertio_theme_options['detail_sidebar_about']) && $exertio_theme_options['detail_sidebar_about'] != '')
	{
		?>
		<div class="sidebar-heading">
			<h3>A propos de moi</h3>
		</div>
		<?php
	}
	?>
  <ul>
	<?php
	if($exertio_theme_options['freelancer_phone_number'] && $exertio_theme_options['freelancer_phone_number'] == 2)
	{
		?>
		<li>
			<i class="fas fa-mobile-alt"></i>
			<div class="meta">
				<span><?php echo esc_html__('Téléphone','exertio_theme'); ?></span>
				<p><?php echo esc_attr(get_post_meta( $fl_id, '_freelancer_contact_number' , true )); ?></p>
			</div>
		</li>
		<?php
	}
	else if($exertio_theme_options['freelancer_phone_number'] && $exertio_theme_options['freelancer_phone_number'] == 3)
	{
		if(!is_user_logged_in())
		{
			?>
            <li>
                <i class="fas fa-mobile-alt"></i>
                <div class="meta">
                    <span><?php echo esc_html__('Téléphone','exertio_theme'); ?></span>
                    <p><?php echo esc_html__('Connectez vous pour voir','exertio_theme'); ?></p>
                </div>
            </li>
            <?php
		}
		else
		{
			?>
			<li>
				<i class="fas fa-mobile-alt"></i>
				<div class="meta">
					<span><?php echo esc_html__('Téléphone','exertio_theme'); ?></span>
					<p><?php echo esc_attr(get_post_meta( $fl_id, '_freelancer_contact_number' , true )); ?></p>
				</div>
			</li>
			<?php
		}
	}
	/*EMAIL DETAIL*/
	if($exertio_theme_options['freelancer_email'] && $exertio_theme_options['freelancer_email'] == 2)
	{
		?>
		<li>
			<i class="far fa-envelope"></i>
			<div class="meta">
				<span><?php echo esc_html__('Email','exertio_theme'); ?></span>
				<p><?php echo esc_attr($user_info->user_email); ?></p>
			</div>
		</li>
		<?php
	}
	else if($exertio_theme_options['freelancer_email'] && $exertio_theme_options['freelancer_email'] == 3)
	{
		if(!is_user_logged_in())
		{
			?>
            <li>
                <i class="far fa-envelope"></i>
                <div class="meta">
                    <span><?php echo esc_html__('Email','exertio_theme'); ?></span>
                    <p><?php echo esc_html__('Connectez vous pour voir','exertio_theme'); ?></p>
                </div>
            </li>
            <?php
		}
		else
		{
			?>
			<li>
				<i class="far fa-envelope"></i>
				<div class="meta">
					<span><?php echo esc_html__('Email','exertio_theme'); ?></span>
					<p><?php echo esc_attr($user_info->user_email); ?></p>
				</div>
			</li>
			<?php
		}
	}
	
	if($exertio_theme_options['detail_page_gender'] && $exertio_theme_options['detail_page_gender'] == 2)
	{
		?>
		<li>
			<i class="fas fa-venus-mars"></i>
			<div class="meta">
				<span><?php echo esc_html__('Sexe','exertio_theme'); ?></span>
				<p>
					<?php
						$gender = '';
						$gender = get_post_meta( $fl_id, '_freelancer_gender' , true );
						if(isset($gender) && $gender == 0)
						{
							echo esc_html__('Masculin','exertio_theme');
						}
						else if(isset($gender) && $gender == 1)
						{
							echo esc_html__('Féminin','exertio_theme');;
						}
						else if(isset($gender) && $gender == 2)
						{
							echo esc_html__('Autre','exertio_theme');;
						}
					?>
				</p>
			</div>
		</li>
		<?php
	}
	if($exertio_theme_options['detail_page_type'] && $exertio_theme_options['detail_page_type'] == 2)
	{
	?>
    <li>
        <i class="fas fa-user-tie"></i>
        <div class="meta">
            <span><?php echo esc_html__('Type de prestataire','exertio_theme'); ?></span>
            <p><?php echo get_term_names('freelance-type', '_freelance_type', $fl_id, '', ',' ); ?></p>
        </div>
    </li>
    <?php
	}
	if($exertio_theme_options['detail_page_eglish_level'] && $exertio_theme_options['detail_page_eglish_level'] == 2)
	{
	?>
    <li>
        <i class="fas fa-tasks"></i>
        <div class="meta">
            <span>Localisation</span>
            <p><?php echo get_term_names('freelancer-english-level', '_freelancer_english_level', $fl_id, '', ',' ); ?></p>
        </div>
    </li>
    <?php
	}
	if($exertio_theme_options['detail_page_language'] && $exertio_theme_options['detail_page_language'] == 2)
	{
	?>
    <li>
        <i class="fas fa-language"></i>
        <div class="meta">
            <span><?php echo esc_html__('Langues','exertio_theme'); ?></span>
            <p><?php echo get_term_names('freelancer-languages', '_freelancer_language', $fl_id, '', ',' ); ?></p>
        </div>
    </li>
    <?php
	}
	?>
  </ul>
</div>


