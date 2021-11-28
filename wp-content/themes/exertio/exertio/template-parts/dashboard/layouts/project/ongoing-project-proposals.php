<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();
$project_id = $_GET['project-id'];
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
		 if(get_post_status ( $project_id ) == 'ongoing')
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
								<h2><?php echo esc_html__('Offres pour le projet','exertio_theme');?></h2>
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
									</span>
									<span class="pro-meta">
									<?php
										$level = get_term( get_post_meta($post->ID, '_project_level', true));
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
								  <div class="pro-coulmn">
									<?php
										if( fl_framework_get_options('turn_project_messaging') == true)
										{
											?>
											<span class="btn btn-theme-secondary"> <?php echo $total_count.' '.esc_html__( 'offre(s) au total', 'exertio_theme' ); ?></span>
											<?php
										}
										else
										{
											?>
										<form>                
											<select class="form-control general_select_2 prject_status">
												<option value=""><?php echo esc_html__( 'Statut du projet', 'exertio_theme' ); ?></option>
												<option value="complete"><?php echo esc_html__( 'Terminé', 'exertio_theme' ); ?></option>
												<option value="cancel"><?php echo esc_html__( 'Annulé', 'exertio_theme' ); ?></option>
											</select>
											<button type="button" class="btn btn-theme btn-loading" id="project_status" data-pid="<?php echo esc_attr($post->ID); ?>";><?php echo esc_html__( 'Modifier', 'exertio_theme' ); ?><div class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div></button>
										</form>									  		<?php
										}
									?>
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
											$freelancer_user_id = get_post_field( 'post_author', $awarded_results->freelancer_id );
											$fl_id = $awarded_results->freelancer_id;
											$pro_img_id = get_post_meta( $awarded_results->freelancer_id, '_profile_pic_freelancer_id', true );
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
												<div class="fr-project-img-box"> <a href="javascript:void(0)"><img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></a> </div>
												<div class="fr-project-user-details"> <a href="javascript:void(0)">
												  <div class="h-style2"><?php echo exertio_get_username('freelancer',$awarded_results->freelancer_id, 'badge', 'right'); ?></div>
												  </a>
												  <ul>
													<li> <i class="fal fa-clock"></i> <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $awarded_results->timestamp ) ); ?></span> </li>
													<li> <span> <?php echo get_freelancer_rating($awarded_results->freelancer_id, '', 'project'); ?></span> </li>
												  </ul>
												</div>
												<div class="fr-project-content-details">
													<?php
														if( fl_framework_get_options('whizzchat_project_option') == true)
														{
															
														<?php
														}
														if( fl_framework_get_options('turn_project_messaging') == true)
														{
															?>
															<a href="<?php get_template_part( 'project-propsals' );?>?ext=ongoing-project-detail&project-id=<?php echo esc_html($project_id); ?>" class="btn btn-theme-secondary"> <?php echo esc_html__( 'Voir le détail', 'exertio_theme' ); ?></a>
															<?php
														}
													?>
												  <ul>
													<li> <span> <?php echo esc_html(fl_price_separator($awarded_results->proposed_cost)); ?> <small>
													  <?php
															if($type == 'fixed')
															{
																echo wp_sprintf(__('en %s jours', 'exertio_theme'), $awarded_results->day_to_complete);
															}
															else if($type == 'hourly')
															{
																echo wp_sprintf(__('heures estimées %s', 'exertio_theme'), $awarded_results->day_to_complete);
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

							  <!--OTHER PROPOSALS-->
							  <div class="fr-project-bidding proposals-dashboard">
								<?php
									$results = get_project_bids($project_id, $start_from, $limit);
									$count_bids =0;
									if(isset($results))
									{
										$count_bids = count($results);	
									}
								?>
								<div class="fr-project-box">
								  <h3><?php echo esc_html__( 'Autres offres', 'exertio_theme' ); ?></h3>
								</div>
								<div class="project-proposal-box">
								  <?php
									if($results)
									{
										foreach($results as $result)
										{
											$pro_img_id = get_post_meta( $result->freelancer_id, '_profile_pic_freelancer_id', true );
											if(wp_attachment_is_image($pro_img_id))
											{
												$pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
												$profile_image = $pro_img[0];
											}
											else
											{
												$profile_image = $exertio_theme_options['freelancer_df_img']['url'];
											}
											$is_sealer ='';
											$is_featured = '';
											$is_top = '';

											if($result->is_featured == 1)
											{
												$is_featured = 'featured-proposal';	
											}
											if($result->is_top == 1)
											{
												$is_top = 'top-proposal';	
											}
											if($result->is_sealed == 1)
											{
												$is_sealer = 'sealed-proposal';

											}
											?>
											  <div class="fr-project-inner-content <?php echo esc_attr($is_sealer.' '.$is_featured.' '.$is_top); ?>">
												<div class="fr-project-profile">
												  <div class="fr-project-profile-details">
													<div class="fr-project-img-box"> <a href="javascript:void(0)"><img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></a> </div>
													<div class="fr-project-user-details"> <a href="javascript:void(0)">
													  <div class="h-style2"><?php echo exertio_get_username('freelancer',$result->freelancer_id, 'badge', 'right'); ?></div>
													  </a>
													  <ul>
														<li> <i class="fal fa-clock"></i> <span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $result->timestamp ) ); ?></span> </li>
														<li> <span> <?php echo get_freelancer_rating($result->freelancer_id, '', 'project'); ?> </span> </li>
														<li> <span> <a href="javascript:void(0)" class="cover-letter" data-prpl-id ='<?php echo esc_html($result->id); ?>'> <?php echo esc_html__( 'Voir le détail', 'exertio_theme' ); ?> </a></span> </li>
													  </ul>
													</div>
													<div class="fr-project-content-details">
													  <ul>
														<li> <span> <?php echo esc_html(fl_price_separator($result->proposed_cost)); ?> <small>
														  <?php
															if($type == 'fixed')
															{
																echo wp_sprintf(__('en %s jours', 'exertio_theme'), $result->day_to_complete);
															}
															else if($type == 'hourly')
															{
																echo wp_sprintf(__('Heures estimées %s', 'exertio_theme'), $result->day_to_complete);
															}

														?>
														  </small> </span> </li>
														<li>
														  <?php if($result->is_top == 1){ ?>
														  <i class="fal fa-medal"></i>
														  <?php } ?>
														  <?php if($result->is_featured == 1){ ?>
														  <i class="fal fa-star"></i>
														  <?php } ?>
														  <?php if($result->is_sealed == 1){ ?>
														  <i class="fal fa-lock"></i>
														  <?php } ?>
														</li>
													  </ul>
													</div>
												  </div>
												</div>
												<div class="fr-project-assets showhide_<?php echo esc_html($result->id); ?>">
												  <h5><?php echo esc_html__( 'Offre du prestataire', 'exertio_theme' ); ?></h5>
												  <p>
													<?php ?>
													<?php echo esc_html($result->cover_letter); ?></p>
												</div>
											  </div>
											<?php
										}
										?>
									  <span class="page-display"><?php echo esc_html__( 'Page ', 'exertio_theme' ).esc_html($start_from); ?> - <?php echo esc_html($start_from+$limit).esc_html__( ' sur ', 'exertio_theme' ).esc_html($total_count); ?> </span>
									  <?php	
										echo custom_pagination($project_id, $pageno, $limit);
									}
									else
									{
										?>
										<div class="nothing-found"> <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
										<h4><?php echo esc_html__( 'Aucune offre trouvée', 'exertio_theme' ); ?></h4>
										</div>
										<?php
									}
								  ?>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					</div>
				</div>
				<?php
				if( fl_framework_get_options('turn_project_messaging') == false)
				{
					?>
					<!-- Modal -->
					<div class="modal fade review-modal" id="review-modal" tabindex="-1" role="dialog" aria-labelledby="review-modal" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<small><?php echo esc_html__('Laisser un avis ','exertio_theme'); ?></small>
							<h4 class="modal-title" id="review-modal"><?php echo exertio_get_username('freelancer',$fl_id, 'badge', 'right'); ?></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true"><i class="fal fa-times"></i></span>
							</button>
						  </div>
						  <div class="modal-body">
							<form id="rating-form">
								<div class="reviews-star-box">
									<ul>
									  <li>
										<p><?php if(isset($exertio_theme_options['first_title'])){ echo esc_html($exertio_theme_options['first_title']); } ?></p>
										<div class="review stars-1"></div>
										<div class="form-group">
										<input type="text" id="stars-1" name="stars_1" value=""  required data-smk-msg="<?php echo esc_html__('Ce champ est requis','exertio_theme'); ?>">
										</div>
									  </li>
									  <li>
										<p><?php if(isset($exertio_theme_options['second_title'])){ echo esc_html($exertio_theme_options['second_title']); } ?></p>
										<div class="review stars-2"></div>
										<div class="form-group">
										<input type="text" id="stars-2" name="stars_2"  required data-smk-msg="<?php echo esc_html__('Ce champ est requis','exertio_theme'); ?>">
										</div>
									  </li>
									  <li>
										<p><?php if(isset($exertio_theme_options['third_title'])){ echo esc_html($exertio_theme_options['third_title']); } ?></p>
										<div class="review stars-3"></div>
										<div class="form-group">
										<input type="text" id="stars-3" name="stars_3"  required data-smk-msg="<?php echo esc_html__('Ce champ est requis','exertio_theme'); ?>">
										</div>
									  </li>
									</ul>
								</div>
								<div class="form-group">
									<label> <?php echo esc_html__('Avis ','exertio_theme'); ?> </label>
									<textarea class="form-control" name="feedback_text" rows="5" cols="10" required data-smk-msg="<?php echo esc_html__('Veuillez laisser un avis','exertio_theme'); ?>"></textarea>
								</div>
								<div class="form-group"> <button type="button" id="rating-btn" class="btn btn-theme btn-loading" data-pid="<?php echo esc_attr($project_id) ?>" data-status= "complete"><?php echo esc_html__('Envoyer','exertio_theme'); ?> <div class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div></button> </div>
							</form>
						  </div>
						</div>
					  </div>
					</div>
					<!--cancel PROJECT MODAL-->
					<div class="modal fade review-modal" id="review-modal-cancel" tabindex="-1" role="dialog" aria-labelledby="review-modal-cancel" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<small><?php echo esc_html__('Raisons d\'annulation du projet ','exertio_theme'); ?></small>
							<h4 class="modal-title" id="review-modal-cancel"><?php echo esc_html__('Annuler le projet','exertio_theme'); ?></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true"><i class="fal fa-times"></i></span>
							</button>
						  </div>
						  <div class="modal-body">
							<form id="review-modal-cancel">
								<div class="form-group">
									<label> <?php echo esc_html__('Commentaire ','exertio_theme'); ?> </label>
									<textarea class="form-control" name="feedback_text" rows="5" cols="10" required data-smk-msg="<?php echo esc_html__('Veuillez fournir la raison','exertio_theme'); ?>"></textarea>
									<p> <?php echo esc_html__('Fournissez des informations sur les raisons pour lesquelles vous annulez ce projet.','exertio_theme'); ?></p>
								</div>
								<div class="form-group"> <button type="button" id="cancel-btn" class="btn btn-theme btn-loading" data-pid="<?php echo esc_attr($project_id) ?>" data-status= "cancel"><?php echo esc_html__('Annuler le projet','exertio_theme'); ?> <div class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div></button> </div>
							</form>
						  </div>
						</div>
					  </div>
					</div>
					<?php
				}
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
