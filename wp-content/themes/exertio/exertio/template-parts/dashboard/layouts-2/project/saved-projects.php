<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();
$alt_id = '';
if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} else if ( get_query_var( 'page' ) ) {
	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}

	if( is_user_logged_in() )
	 {
		global $wpdb;
		$uid	=	get_current_user_id();
		$rows = $wpdb->get_results( "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$uid' AND meta_key LIKE '_pro_fav_id_%'" );
		$pids	=	array(0);
		foreach( $rows as $row )
		{
			$pids[]	=	$row->meta_value;	
		}
		$args	=	array(
					'post_type' => 'projects',
					'post__in' => $pids,
					'post_status' => 'publish',
					'paged' => $paged,
					'order'=> 'DESC',
					'orderby' => 'date'
				);

		$the_query = new WP_Query($args);
		
		$total_count = $the_query->found_posts;

		?>
<div class="content-wrapper">
  <div class="notch"></div>
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="d-flex justify-content-between flex-wrap">
        <div class="d-flex align-items-end flex-wrap">
          <div class="mr-md-3 mr-xl-5">
            <h2><?php echo esc_html__('Saved Projects','exertio_theme').esc_html(' ('. $total_count.')');?></h2>
            <div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
				<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Dashboard', 'exertio_theme' ); ?>&nbsp;</p>
				<?php echo exertio_dashboard_extention_return(); ?>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card mb-4">
        <div class="card-body">
          <div class="pro-section">
              <div class="pro-box heading-row">
                <div class="pro-coulmn pro-title">
                </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Category', 'exertio_theme' ) ?> </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Type/Cost', 'exertio_theme' ) ?> </div>
                <div class="pro-coulmn"><?php echo esc_html__( 'Action', 'exertio_theme' ) ?> </div>
              </div>
				<?php
					if ( $the_query->have_posts() )
					{
						while ( $the_query->have_posts() ) 
						{
							$the_query->the_post();
							$pid = get_the_ID();
							$posted_date = get_the_date(get_option( 'date_format' ), $pid );
							?>
							  <div class="pro-box">
								<div class="pro-coulmn pro-title">
									<h4 class="pro-name">
										<a href="<?php  echo esc_url(get_permalink()); ?>"><?php echo	esc_html(get_the_title()); ?></a>
									</h4>
									<span class="pro-meta-box">
										<span class="pro-meta">
											<i class="fal fa-clock"></i> <?php echo	esc_html($posted_date); ?>
										</span>
										<span class="pro-meta">
											<?php
											$level = get_term( get_post_meta($pid, '_project_level', true));
											if(!empty($level) && ! is_wp_error($level))
											{
												?>
												<i class="fal fa-layer-group"></i> <?php echo esc_html($level->name); ?>
												<?php
											}
											?>
										</span>
									</span>
								</div>
								<div class="pro-coulmn">
									<?php 

										$category = get_term( get_post_meta($pid, '_project_category', true));
										if(!empty($category) && ! is_wp_error($category))
										{
											echo esc_html($category->name);
										}
									 ?>
								</div>
								<div class="pro-coulmn">
									<?php 
										$type = get_post_meta($pid, '_project_type', true);
										if($type == 'fixed')
										{
											echo esc_html(fl_price_separator(get_post_meta($pid, '_project_cost', true))).'/'.esc_html__( 'Fixed ', 'exertio_theme' );
										}
										else if($type == 'hourly')
										{
											echo esc_html(fl_price_separator(get_post_meta($pid, '_project_cost', true))).' '.esc_html__( 'Hourly ', 'exertio_theme' );
											echo '<small class="estimated-hours">'.esc_html__( 'Estimated Hours ', 'exertio_theme' ).get_post_meta($pid, '_estimated_hours', true).'</small>';
										}
									 ?>
								</div>
								<div class="pro-coulmn"><a href="javascript:void(0)" class="btn btn-danger delete_fav_project" data-post-id="<?php echo esc_html($pid); ?>"> <?php echo esc_html__( 'Remove ', 'exertio_theme' ); ?> </a></div>
							  </div>
						  
							<?php
						}
						
						fl_pagination($the_query);
						wp_reset_postdata();
					}
					else
					{
						?>
                        <div class="nothing-found">
                            <h3><?php echo esc_html__( 'Sorry!!! No Record Found', 'exertio_theme' ) ?></h3>
                            <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
                        </div>
                        <?php	
					}
				?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-4 col-lg-12 col-md-12 grid-margin stretch-card"> </div>
  </div>
</div>
<?php
	}
	else
	{
		echo exertio_redirect(home_url('/'));
	?>
<?php
	}
	?>
