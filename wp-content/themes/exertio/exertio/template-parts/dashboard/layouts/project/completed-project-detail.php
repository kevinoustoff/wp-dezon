<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();
$project_id = $_GET['project-id'];

$msg_author = get_user_meta( $current_user_id, 'employer_id' , true );
$alt_id = '';
$limit = get_option( 'posts_per_page' );
$start_from ='1';
if (isset($_GET["pageno"])) 
{  
  $pageno  = $_GET["pageno"];  
}  
else {  
  $pageno=1;  
}
$start_from = ($pageno-1) * $limit; 
	if( is_user_logged_in() )
	 {
		if(get_post_status ( $project_id ) == 'completed')
		{ 
			$results_count = get_project_bids($project_id);
			$total_count =0;
			if(isset($results_count))
			{
				$total_count = count($results_count);	
			}	 
				 
			$project_author_id = get_post_field( 'post_author', $project_id );
			if($project_author_id == $current_user_id)
			{
			$post = get_post($project_id);
			?>
				<div class="content-wrapper ">
				  <div class="notch"></div>
				  <div class="row">
					<div class="col-md-12 grid-margin">
					  <div class="d-flex justify-content-between flex-wrap">
						<div class="d-flex align-items-end flex-wrap">
						  <div class="mr-md-3 mr-xl-5">
							<h2><?php echo esc_html__('Détail du projet','exertio_theme'); ?></h2>
							<div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
								<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Tableau de bord', 'exertio_theme' ); ?>&nbsp;</p>
								<?php echo exertio_dashboard_extention_return(); ?>
							</div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <?php
				?>
				  <div class="row">
					<div class="col-md-12 grid-margin stretch-card">
					  <div class="card mb-4">
						<div class="card-body">
						  <div class="pro-section project-details">
							<div class="pro-box">
							  <div class="pro-coulmn pro-title">
								<h4 class="pro-name"> <a href="<?php  echo esc_url(get_permalink()); ?>"><?php echo	esc_html($post->post_title); ?></a> </h4>
								<span class="pro-meta-box"> <span class="pro-meta"> <i class="fal fa-clock"></i>
								<?php 
									$posted_date = get_the_date(get_option( 'date_format' ), $post->ID );
									echo esc_html($posted_date); 
								?>
								</span> <span class="pro-meta">
								<?php
									$level = get_term( get_post_meta($post->ID, '_project_level', true));
									if(!empty($level) && ! is_wp_error($level))
									{
										?>
										<i class="fal fa-layer-group"></i> <?php echo esc_html($level->name); ?> </span> </span> </div>
										<?php
									}
									?>
							  <div class="pro-coulmn">
								<?php 
										$category = get_term( get_post_meta($post->ID, '_project_category', true));
										if(!empty($category) && ! is_wp_error($category))
										{
											echo esc_html($category->name);
										}
									 ?>
							  </div>
							  <div class="pro-coulmn">
								<?php 
									$type =get_post_meta($post->ID, '_project_type', true);
									if($type == 'fixed')
									{
										echo esc_html(fl_price_separator(get_post_meta($post->ID, '_project_cost', true)));
									}
									else if($type == 'hourly')
									{
										echo esc_html(fl_price_separator(get_post_meta($post->ID, '_project_cost', true))).' / '.esc_html__( 'par heure ', 'exertio_theme' );
										echo '<small class="estimated-hours">'.esc_html__( 'Heures estimées ', 'exertio_theme' ).get_post_meta($post->ID, '_estimated_hours', true).'</small>';
									}
								 ?>
							  </div>
								<div class="pro-coulmn completed-status">
                                	<i class="fas fa-check-circle"></i>
                                    <div>
                                        <span class="">
											<?php echo esc_html__( 'Terminé ', 'exertio_theme' ); ?>
                                        </span>
                                        <small>
                                            <?php echo esc_html__( 'le ', 'exertio_theme' ).date_i18n( get_option( 'date_format' ), strtotime( get_post_meta($post->ID, '_project_completed_date', true) ) ); ?>
                                        </small>
                                    </div>
								</div>
							</div>
						  </div>
						  <?php
							$hired_fler = get_post_meta( $project_id, '_freelancer_assigned', true );
							$awarded_result = project_awarded($project_id, $hired_fler);
						  ?>
						  <div class="fr-project-bidding proposals-dashboard selcted">
							<?php
								$results = get_project_bids($project_id, $start_from, $limit);
								$count_bids =0;
								if(isset($results))
								{
									$count_bids = count($results);	
								}
				
							?>
							<div class="fr-project-box">
							  <h3><?php echo esc_html__( 'Jobeur embauché', 'exertio_theme' ); ?></h3>
							</div>
							<div class="project-proposal-box">
							  <?php
								if($awarded_result)
								{
									foreach($awarded_result as $awarded_results)
									{
										$fl_id = $awarded_results->freelancer_id;
										$pro_img_id = get_post_meta( $fl_id, '_profile_pic_freelancer_id', true );
										if(wp_attachment_is_image($pro_img_id))
										{
											$pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
											$profile_image = $pro_img[0];
										}
										else
										{
											$profile_image = $exertio_theme_options['freelancer_df_img']['url'];
										}
										?>
									  <div class="fr-project-inner-content">
										<div class="fr-project-profile">
										  <div class="fr-project-profile-details">
											<div class="fr-project-img-box"> <a href="<?php echo get_the_permalink($fl_id); ?>"><img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></a> </div>
											<div class="fr-project-user-details"> <a href="<?php echo get_the_permalink($fl_id); ?>">
											  <div class="h-style2"><?php echo exertio_get_username('freelancer',$fl_id, 'badge', 'right'); ?></div>
											  </a>
											  <ul>
												<li> <i class="fal fa-clock"></i> <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $awarded_results->timestamp ) ); ?></span> </li>
												<li><span> <?php echo get_rating($fl_id); ?></span> </li>
											  </ul>
											</div>
											<div class="fr-project-content-details"> 
											  <ul>
												<li> <span> <?php echo esc_html(fl_price_separator($awarded_results->proposed_cost)); ?> <small>
												  <?php
														if($type == 'fixed')
														{
															echo wp_sprintf(__('en %s jours', 'exertio_theme'), $awarded_results->day_to_complete);
														}
														else if($type == 'hourly')
														{
															echo wp_sprintf(__('Heures estimées %s', 'exertio_theme'), $awarded_results->day_to_complete);
														}
													
													?>
												  </small> </span> </li>
											  </ul>
											</div>
										  </div>
										</div>
										<div class="fr-project-assets">
										  <h5><?php echo esc_html__( 'Offre du jobeur', 'exertio_theme' ); ?></h5>
										  <p>
											<?php ?>
											<?php echo esc_html($awarded_results->cover_letter); ?></p>
										</div>
									  </div>
									  <?php
									}
								}
						  ?>
							</div>
						  </div>
							<?php
							if( fl_framework_get_options('turn_project_messaging') == true)
							{
							?>
								<!--PROJECT HISTORY-->
								<div class="project-history">
																<h3><?php echo esc_html__( 'Historique du projet', 'exertio_theme' ); ?></h3>
																<div class="history-body">
																	<div class="history-chat-body">
																		<?php
																		$messages = get_history_msg($project_id);
																		if($messages)
																		{
																			foreach($messages as $message)
																			{
																				$msg_author = get_user_meta( $current_user_id, 'employer_id' , true );
																				if($msg_author == $message->msg_author)
																				{
																					?>
																					<div class="chat-single-box">
																						<div class="chat-single chant-single-right">
																							<div class="history-user">
																								<span class="history-datetime"><?php echo time_ago_function($message->timestamp); ?></span>
																								<a href="#" class="history-username"><?php echo exertio_get_username('employer',$message->msg_author ); ?></a>
																								<span><?php echo get_profile_img($message->msg_author, "employer"); ?></span>
																							</div>
																							<p class="history-text">
																								<?php echo esc_html(wp_strip_all_tags($message->message)); ?>
																							</p>
																							<?php
																							if($message->attachment_ids >0)
																							{
																								?>
																								<!--<a class="history_attch_dwld btn btn-black" href="javascript:void(0)" id="download-files" > Download</a>-->
																								<div class="history_attch_dwld btn-loading" id="download-files" data-id="<?php echo esc_attr($message->attachment_ids); ?>">
																									<i class="fal fa-arrow-to-bottom"></i>
																									<?php echo esc_html__( 'Fichiers', 'exertio_theme' ); ?>
																									<div class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div>
																								</div>
																								<?php
																							 }
																							 ?>
																						</div>
																					</div>
																					<?php	
																				}
																				else
																				{
																					?>
																					<div class="chat-single-box">
																						<div class="chat-single success">
																							<div class="history-user">
																								<span>
																									<?php echo get_profile_img($msg_author, "freelancer"); ?>
																								</span>
																								<a href="#" class="history-username"><?php echo exertio_get_username('freelancer',$message->freelancer_id, 'badge', 'right'); ?></a>
																								<span class="history-datetime"><?php echo time_ago_function($message->timestamp); ?></span>
																							</div>
																							<p class="history-text">
																								<?php echo esc_html(wp_strip_all_tags($message->message)); ?>
																							</p>
																							<?php
																							if($message->attachment_ids >0)
																							{
																								?>
																								<div class="history_attch_dwld btn-loading" id="download-files" data-id="<?php echo esc_attr($message->attachment_ids); ?>">
																									<i class="fal fa-arrow-to-bottom"></i>
																									<?php echo esc_html__( 'Fichiers', 'exertio_theme' ); ?>
																									<div class="bubbles bubbles-<?php echo esc_attr($message->attachment_ids); ?>"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div>
																								</div>
																								<?php
																							 }
																							 ?>
																						</div>
																					</div>
																					<?php
																				}
																			}
																		}
																		else
																		{
																			?>
																			<p class="text-center"><?php echo esc_html__( 'Aucune historique pour le moment', 'exertio_theme' ); ?></p>
																			<?php	
																		}
																		?>
																	</div>
																</div>
														  </div>
							<?php
							}
							?>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			<?php
			}
			else
			{
				get_template_part( 'template-parts/dashboard/layouts/dashboard');
			}
		}
		else
		{
			get_template_part( 'template-parts/dashboard/layouts/dashboard');
		}
	}
	else
	{
		echo exertio_redirect(home_url('/'));
	?>
<?php
	}
	?>
