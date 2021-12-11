<?php
if(in_array('exertio-framework/index.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
	$actionbBar = fl_framework_get_options('action_bar');
	$actionbar_space = '';
	if(isset($actionbBar) && $actionbBar == 1)
	{
		$actionbar_space = 'actionbar_space';
	}
	get_template_part('header');
	global $exertio_theme_options;
	$current_user_id = get_current_user_id();
		$alt_id = '';
			global $post;
			$pid = get_the_ID();
			$post_author = $post->post_author;
			$user_info = get_userdata($post_author);
			$employer_id = get_user_meta( $post_author, 'employer_id' , true );
		$project_type = get_post_meta($pid, '_project_type', true);
		?>
		<section class="fr-project-details section-padding <?php echo esc_attr($actionbar_space); ?>">
		  <div class="container">
			<div class="row">
			  <div class="col-lg-8 col-xl-8 col-sm-12 col-md-12 col-xs-12">
				<div class="fr-project-content">
				  <div class="fr-project-list">
					<div class="fr-project-container">
						<?php
							$featured_projects = get_post_meta($pid, '_project_is_featured', true);
							if(isset($featured_projects) && $featured_projects == 1)
							{
								?>
								<div class="features-star"><i class="fa fa-star"></i></div>
								<?php
							}
							?>
						<ul class="fr-project-meta">
							<li> <i class="fal fa-folder"></i>
							  <?php
									$project_category = get_term( get_post_meta($pid, '_project_category', true));
									if(!empty($project_category) && ! is_wp_error($project_category))
									{
										echo esc_html($project_category->name);
									}
								?>
								</li>
							<li> <i class="fal fa-map-marker-alt"></i>
							  <?php
									$location_remote = get_post_meta($pid, '_project_location_remote', true);
									if(isset($location_remote) && $location_remote == 1)
									{
										echo esc_html__('A distance','exertio_theme');
									}
									else
									{
										$project_location = get_term( get_post_meta($pid, '_project_location', true));
										if(!empty($project_location) && ! is_wp_error($project_location))
										{
											echo esc_html($project_location->name);
										}
									}
								?>
							</li>
							<li> <i class="fal fa-clock"></i>
							  <?php
								echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date() ) );
							?>
							</li>
						</ul>
					  <h2><?php echo get_the_title(); ?></h2>
						<?php
							$marked_fav = $fav  = '';
							$fav_text = esc_html__('Sauvegarder le projet','exertio_theme');
							$marked_fav = get_user_meta( get_current_user_id(), '_pro_fav_id_'.$pid, true );
							if(isset($marked_fav) && $marked_fav != '' )
							{
								$fav = 'fav';
								$fav_text = esc_html__('Déjà sauvegardé','exertio_theme');
							}
						?>
						<div class="fr-project-style">
							<a href="javascript:void(0)" class="mark_fav protip <?php echo esc_attr($fav); ?>" data-post-id="<?php echo esc_attr($pid); ?>" data-pt-position="top" data-pt-scheme="black" data-pt-title="<?php echo esc_attr($fav_text); ?>"><i class="fal fa-heart"></i></a>
							<a href="#fr-bid-form" class="btn btn-theme scroll"> <?php echo esc_html__('Postuler','exertio_theme'); ?></a>
						</div>
					</div>
					<div class="fr-project-product-features">
						<div class="fr-project-product">
						  <ul class="">
							<li>
							  <div class="short-detail-icon"> <i class="fal fa-id-card-alt"></i> </div>
							  <div class="short-detail-meta"> <small><?php echo esc_html__('Type de prestataire ','exertio_theme'); ?></small> <strong>
								<?php
									$freelancer_type = get_term( get_post_meta($pid, '_project_freelancer_type', true));
									if(!empty($freelancer_type) && ! is_wp_error($freelancer_type))
									{
										echo esc_html($freelancer_type->name);
									}
								?>
								</strong> </div>
							</li>
							<li>
							  <div class="short-detail-icon"> <i class="fal fa-calendar-check"></i> </div>
							  <div class="short-detail-meta"> <small><?php echo esc_html__('Date début du projet','exertio_theme'); ?></small> <strong>
								<?php
									$project_date_debut = get_post_meta($pid, '_project_date_debut', true);
									if(!empty($project_date_debut) && ! is_wp_error($project_date_debut))
									{
										echo esc_html($project_date_debut);
									}
								?>
								</strong> </div>
							</li>
							<li>
							  <div class="short-detail-icon"> <i class="fal fa-calendar-check"></i> </div>
							  <div class="short-detail-meta"> <small><?php echo esc_html__('Date fin du projet','exertio_theme'); ?></small> <strong>
								<?php
									$project_date_fin = get_post_meta($pid, '_project_date_fin', true);
									if(!empty($project_date_fin) && ! is_wp_error($project_date_fin))
									{
										echo esc_html($project_date_fin);
									}
								?>
								</strong> </div>
							</li>
							
							<li>
							  <div class="short-detail-icon"> <i class="fal fa-language"></i> </div>
							  <div class="short-detail-meta"> <small><?php echo esc_html__('Langues','exertio_theme'); ?></small>
								<?php
									$saved_languages = wp_get_post_terms($pid, 'languages', array( 'fields' => 'all' ));
									if(!empty($saved_languages) && ! is_wp_error($saved_languages))
									{
										foreach($saved_languages as $saved_language)
										{
											echo  '<strong>'.$saved_language->name.'</strong> ';	
										}
									}
								?>
							  </div>
							</li>
							  </ul>
						</div>
					</div>
				  </div>
					<?php
						if ( isset($exertio_theme_options[ 'project_detail_ad1' ]) && $exertio_theme_options[ 'project_detail_ad1' ] !='' ) {
					?>
						<div class="fl-advert-box">
						<?php echo wp_return_echo( $exertio_theme_options[ 'project_detail_ad1' ] ); ?>
						</div>
						<?php
					}
					?>
				  <div class="fr-project-f-des">
					<div class="fr-project-des">
					  <h3><?php echo esc_html__('Détails du projet','exertio_theme'); ?></h3>
					  <?php echo stripslashes(wp_kses($post->post_content, exertio_allowed_html_tags())); ?>
					</div>
					
					<div class="fr-project-skills">
					  <h3> <?php echo esc_html__('Compétences requises','exertio_theme'); ?></h3>
					  <?php
							$saved_skills = wp_get_post_terms($pid, 'skills', array( 'fields' => 'all' ));
							if(!empty($saved_skills) && ! is_wp_error($saved_skills))
							{
								foreach($saved_skills as $saved_skill)
								{
									?>
								  <a href="javascript:void(0)"><?php echo esc_html($saved_skill->name); ?></a>
								  <?php
								}
							}
						?>
					</div>
					<?php
						$pro_img_id = get_post_meta( $pid, '_project_attachment_ids', true ); 
						if(isset($pro_img_id) && $pro_img_id != '')
						{	
						?>
							<div class="fr-project-attachments">
							  <h3> <?php echo esc_html__('Pièces jointes','exertio_theme'); ?></h3>
							  <div class="attacment-box">
								<?php
									$atatchment_arr = explode( ',', $pro_img_id );
									foreach ($atatchment_arr as $value)
									{
										$icon_thumbnail = get_icon_for_attachment($value, 'thumbnail');
										$full_link = wp_get_attachment_url($value);
											$ext = wp_check_filetype($full_link);
											$gallery = 'data-fancybox="gallery"';
											$download_btn = '';
											if($ext['ext'] == 'doc' || $ext['ext'] == 'docx' || $ext['ext'] == 'xls' || $ext['ext'] == 'xlsx' || $ext['ext'] == 'ppt' || $ext['ext'] == 'pptx')
											{
												$gallery = '';
												$download_btn = '<a href="'.$full_link.'" class="download-icon"><i class="fal fa-download"></i></a>';
											}
											else
											{
												$gallery = '';
												$download_btn = '<a href="javascript:void(0)" class="download-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 32 32"><path d="M24 14a5.99 5.99 0 0 0-4.885 9.471L14 28.586L15.414 30l5.115-5.115A5.997 5.997 0 1 0 24 14zm0 10a4 4 0 1 1 4-4a4.005 4.005 0 0 1-4 4z" fill="#626262"/><path d="M17 12a3 3 0 1 0-3-3a3.003 3.003 0 0 0 3 3zm0-4a1 1 0 1 1-1 1a1 1 0 0 1 1-1z" fill="#626262"/><path d="M12 24H4v-6.003L9 13l5.586 5.586L16 17.168l-5.586-5.585a2 2 0 0 0-2.828 0L4 15.168V4h20v6h2V4a2.002 2.002 0 0 0-2-2H4a2.002 2.002 0 0 0-2 2v20a2.002 2.002 0 0 0 2 2h8z" fill="#626262"/></svg></a>';
											}
											echo '<div class="attachments">
													<a href="'.$full_link.'" '.$gallery.'>
														<img src="'.$icon_thumbnail.'" alt="'.esc_attr(get_post_meta($value, '_wp_attachment_image_alt', TRUE)).'">
														<div class="attachment-data">
															<h6 title="'.get_the_title($value).'.'.$ext['ext'].'"> '.substr(get_the_title($value),0,15).'.'.$ext['ext'].'</h6>
															<p>'.esc_html__('taille du fichier','exertio_theme').' '.size_format(filesize(get_attached_file( $value ))).'</p>
														</div>
													</a>
													'.$download_btn.'
												</div>';
									}
								?>
							  </div>
							</div>
						<?php
						}
					?>
					<div class="fr-project-ids">
					  <p>
						  <?php 
							$project_ref_id = get_post_meta($pid, '_project_ref_id', true);
							if(isset($project_ref_id) && $project_ref_id != '')
							{
								$project_ref_id = $project_ref_id;
							}
							else
							{
								$project_ref_id = $pid;
							}
							echo esc_html__('Numéro du projet:','exertio_theme').$project_ref_id; 
						  ?>
					  </p>
					</div>
				  </div>
				</div>
				  <?php
					if ( isset($exertio_theme_options[ 'project_detail_ad2' ]) && $exertio_theme_options[ 'project_detail_ad2' ] != '' ) {
						?>
						<div class="fl-advert-box">
						<?php echo wp_return_echo( $exertio_theme_options[ 'project_detail_ad2' ] ); ?>
						</div>
						<?php
					}
				?>
				
					<?php
						$hired_fler = get_post_meta( $pid, '_freelancer_assigned', true );
						if(isset($hired_fler ) && $hired_fler  != '')
						{
							$awarded_results = project_awarded($pid, $hired_fler);
							if($awarded_results)
							{
							?>
				  				<div class="project_awarded-content awarded">
									<div class="crown"> <img src="<?php echo get_template_directory_uri(); ?>/images/crown.svg" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></div>
									<div class="fr-project-bidding">
										<div id='stars'></div>
										<div id='stars2'></div>
										<div id='stars3'></div>
										<div class="project-proposal-box">
										  <?php
												foreach($awarded_results as $awarded_result)
												{
													$pro_img_id = get_post_meta( $awarded_result->freelancer_id, '_profile_pic_freelancer_id', true );
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
													if($awarded_result->is_sealed == 1)
													{
														$is_sealer = 'sealed-proposal';
														if( $awarded_result->is_sealed == 1 && $current_user_id != $post_author){
															continue;
														}	
													}
													if($awarded_result->is_featured == 1)
													{
														$is_featured = 'featured-proposal';	
													}
													if($awarded_result->is_top == 1)
													{
														$is_top = 'top-proposal';	
													}
													?>
													<div class="fr-project-inner-content <?php echo esc_attr($is_sealer.' '.$is_featured.' '.$is_top); ?>">

													<div class="fr-project-profile">
													  <div class="fr-project-profile-details">
														<div class="fr-project-img-box"> <a href="<?php echo get_permalink($awarded_result->freelancer_id); ?>"><img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></a> </div>
														<div class="fr-project-user-details">
															<a href="<?php echo get_permalink($awarded_result->freelancer_id); ?>">
															  <div class="h-style2">
																<?php echo exertio_get_username('freelancer', $awarded_result->freelancer_id, 'badge', 'right'); ?>
															  </div>
														  </a>
														  <ul>
															<li> <i class="fal fa-clock"></i> <span><?php echo time_ago_function($awarded_result->timestamp ); ?></span> </li>
															<li><span> <?php echo get_rating($awarded_result->freelancer_id, ''); ?> </span> </li>
														  </ul>
														</div>
														<div class="fr-project-content-details">
														  <ul>

															<li> <span><?php echo esc_html(fl_price_separator($awarded_result->proposed_cost)); ?></span> </li>
															<li>
																<span class="xt">
																<?php 
																	if($project_type == 'fixed')
																	{
																		echo wp_sprintf(__('en %s jours', 'exertio_theme'), $awarded_result->day_to_complete);
																	}
																	else if($project_type == 'hourly')
																	{
																		echo wp_sprintf(__('en %s heures', 'exertio_theme'), $awarded_result->day_to_complete);
																	}
																?>
																</span>
															</li>
														  </ul>
														</div>
													  </div>
													</div>
													<div class="fr-project-assets">
													  <p>
														<?php ?>
														<?php echo esc_html($awarded_result->cover_letter); ?></p>
													</div>
													</div>
													<?php
												}
										  ?>
										</div>
									</div>
								</div>
								<?php
							}
						}
				if(isset($exertio_theme_options['allow_projects_proposal']) && $exertio_theme_options['allow_projects_proposal'] == true)
				{
					?>
					<div class="fr-project-lastest-product">
						<?php
							if(isset($exertio_theme_options['show_project_proposal']) && $exertio_theme_options['show_project_proposal'] == true)
							{
								$results = get_project_bids($pid);
								$count_bids =0;
								if(isset($results))
								{
									$count_bids = count($results);	
								}

								if($results)
								{
								?>
									<div class="fr-project-bidding">

										<div class="fr-project-box">
										  <h3><?php echo esc_html__( 'Les offres concernant ce projet', 'exertio_theme' ).' ('.$count_bids.')'; ?></h3>
										</div>
										<div class="project-proposal-box proposal-box-scrollable">
										  <?php
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
													if($result->is_sealed == 1)
													{
														$is_sealer = 'sealed-proposal';
														if( $result->is_sealed == 1 && $current_user_id != $post_author){
															continue;
														}	
													}
													if($result->is_featured == 1)
													{
														$is_featured = 'featured-proposal';	
													}
													if($result->is_top == 1)
													{
														$is_top = 'top-proposal';	
													}
													?>
													<div class="fr-project-inner-content <?php echo esc_attr($is_sealer.' '.$is_featured.' '.$is_top); ?>">
													<div class="fr-project-profile">
													  <div class="fr-project-profile-details">
														<div class="fr-project-img-box"> <a href="<?php echo get_permalink($result->freelancer_id); ?>"><img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo get_post_meta($pro_img_id, '_wp_attachment_image_alt', TRUE); ?>" class="img-fluid"></a> </div>
														<div class="fr-project-user-details">
															<a href="<?php echo get_permalink($result->freelancer_id); ?>">
															  <div class="h-style2">
																<?php echo exertio_get_username('freelancer', $result->freelancer_id, 'badge', 'right'); ?>
															  </div>
														  </a>
														  <ul>
															<li> <i class="fal fa-clock"></i> <span><?php echo time_ago_function($result->timestamp ); ?></span> </li>
															<li><span> <?php echo get_rating($result->freelancer_id, ''); ?> </span> </li>
														  </ul>
														</div>
														<div class="fr-project-content-details">
														  <ul>

															<li> <span><?php echo esc_html(fl_price_separator($result->proposed_cost)); ?></span> </li>
															<li>
																<span class="xt">
																<?php 
																	if($project_type == 'fixed')
																	{
																		echo wp_sprintf(__('en %s jours', 'exertio_theme'), $result->day_to_complete);
																	}
																	else if($project_type == 'hourly')
																	{
																		echo wp_sprintf(__('en %s heures', 'exertio_theme'), $result->day_to_complete);
																	}
																?>
																</span>
															</li>
															
														  </ul>
														</div>
													  </div>
													</div>
													<div class="fr-project-assets">
													  <p>
														<?php ?>
														<?php echo stripslashes($result->cover_letter); ?></p>
													</div>
													</div>
													<?php
												}
										  ?>
										</div>
									</div>
									<?php
								}
							}
						?>
					  <div class="fr-project-place" id="fr-bid-form">
						<h3> <?php echo esc_html__( 'Postuler', 'exertio_theme' ); ?></h3>
						<?php
						$project_expiry = get_post_meta($pid, '_simple_projects_expiry_date', true);
						$today = date('d-m-Y');
						if(strtotime($today) < strtotime($project_expiry))
						{
						?>
							<form id="bid_form" data-smk-icon="glyphicon-remove-sign">
							  <div class="row g-3">
								<div class="col">
									<?php
									if($project_type == 'fixed')
									{
										?>
										<div class="form-group">
											<label><?php echo esc_html__('Montant de l\'offre','exertio_theme'); ?></label>
											<div class="input-group">
											  <input type="text" class="form-control" id="bidding-price" name="bid_price" required data-smk-msg="<?php echo esc_html__('Indiquez votre prix en chiffres uniquement','exertio_theme'); ?>"  data-smk-type="number">
											  <div class="input-group-prepend">
												<div class="input-group-text"><?php echo esc_html($exertio_theme_options['fl_currency']); ?></div>
											  </div>
											</div>
										  </div>
										<?php

									}
									else if($project_type == 'hourly')
									{
										?>
										<div class="form-group">
											<label><?php echo esc_html__('Votre prix par heure','exertio_theme'); ?></label>
											<div class="input-group">
											  <input type="text" class="form-control" id="bidding_price" name="bid_price" required data-smk-msg="<?php echo esc_html__('Indiquez votre prix en chiffres uniquement','exertio_theme'); ?>"  data-smk-type="number">
											  <div class="input-group-prepend">
												<div class="input-group-text"><?php echo esc_html($exertio_theme_options['fl_currency']); ?></div>
											  </div>
											</div>
										  </div>
									  <?php
									}
									?>

								</div>
								<div class="col">
								<?php
									if($project_type == 'fixed')
									{
									?>
										<div class="form-group">
											<label> <?php echo esc_html__('Ce projet sera livré dans ','exertio_theme'); ?></label>
											<div class="input-group">
											  <input type="text" class="form-control" name="bid_days" required data-smk-msg="<?php echo esc_html__('Jours à compléter en chiffres seulement','exertio_theme'); ?>"  data-smk-type="number">
											  <div class="input-group-prepend">
												<div class="input-group-text"><?php echo esc_html__('Jours','exertio_theme'); ?></div>
											  </div>
											</div>
										  </div>
									<?php
									}
									else if($project_type == 'hourly')
									{
									?>
										<div class="form-group">
											<label> <?php echo esc_html__('Heures estimées','exertio_theme'); ?></label>
											<div class="input-group">
											  <input type="text" class="form-control" name="bid_days" id="bid-hours" required data-smk-msg="<?php echo esc_html__('Heures à compléter en chiffres seulement','exertio_theme'); ?>"  data-smk-type="number">
											  <div class="input-group-prepend">
												<div class="input-group-text"><?php echo esc_html__('Heures','exertio_theme'); ?></div>
											  </div>
											</div>
										  </div>
									<?php
									}
									?>
								</div>
								</div>
								<div class="row g-3">
							<?php
								$project_charges = $exertio_theme_options['project_charges'];
								if($project_charges > 0 && $project_charges != '')
								{
								?>
									<div class="col-12 price-section">
									  <div class="pricing-section">
										<ul>
										  <li>
											<div> <?php echo esc_html__('Coût total estimé','exertio_theme'); ?>
												<p class="pricing-desc"><?php echo esc_html__('Le coût total du projet.','exertio_theme'); ?></p>
											</div>
											<div>
												<?php
													if($project_type == 'fixed')
													{
														?>
														<p id="total-price"><?php echo esc_html(fl_price_separator(get_post_meta($pid, '_project_cost', true)));?></p>
														<?php	
													}
													else if($project_type == 'hourly')
													{
														$cost_hours = get_post_meta($pid, '_project_cost', true);
														$est_hours = get_post_meta($pid, '_estimated_hours', true);
														?>
														<p id="total-price"><?php echo esc_html( fl_price_separator($cost_hours*$est_hours));?></p>
														<?php
													}
												?>
											</div>
										  </li>
										  <li> <div> <?php echo esc_html__('Frais de service','exertio_theme').' <small>('.$project_charges.'%)</small>'; ?>
											<p class="pricing-desc"><?php echo esc_html__('Les frais de service seront déduits du montant proposé.','exertio_theme'); ?></p>
											</div> <div> <?php echo esc_html($exertio_theme_options['fl_currency']); ?>
											<p id="service-price"></p>
											</div> </li>
										  <li> <div> <?php echo esc_html__('Votre gain','exertio_theme'); ?>
											<p class="pricing-desc"><?php echo esc_html__('Montant total de votre gain.','exertio_theme'); ?></p>
											</div> <div> <?php echo esc_html($exertio_theme_options['fl_currency']); ?>
											<p id="earning-price"></p>
											</div> </li>
										</ul>
									  </div>
									</div>
								<?php
								}
								?>
								</div>
							  <div class="form-row">
								<div class="col-12">
									<?php
									$price_breakdown = '';
									if($project_charges > 0 && $project_charges != '')
									{
										$price_breakdown = '<a href="javascript:void(0)" class="price-breakdown">'.esc_html__('Répartition des prix','exertio_theme').'</a>';
									}
									?>
								  <label> <?php echo esc_html__('Décrivez votre offre','exertio_theme').$price_breakdown; ?> </label>
								  <textarea class="form-control" id="bid-textarea" name="bid_textarea" rows="3"></textarea>
								</div>
							  </div>
							  <div class="fr-project-ad-content">
								<?php 
									if($exertio_theme_options['project_top_bid_addon'] == 1)
									{
										?>
										<div class="form-row">
										  <div class="col-12">
											<div class="fr-project-adons w1">
											  <ul>
												<li>
												  <div class="pretty p-icon p-thick p-curve">
													<input type="checkbox" name="top_bid" />
													<div class="state p-warning">
														<i class="icon fa fa-check"></i>
													  <label></label>
													</div>
												  </div>
												</li>
												<li> <span><?php echo esc_html($exertio_theme_options['project_top_addon_title']); ?></span>
												  <p><?php echo esc_html($exertio_theme_options['project_top_addon_desc']); ?></p>
												</li>
												<li> <span><?php echo esc_html(fl_price_separator($exertio_theme_options['project_top_addon_price'])); ?></span> </li>
											  </ul>
												<div class="bottom-icon">
													<?php echo wp_return_echo($exertio_theme_options['project_top_addon_icon']); ?>
												</div>
											</div>
										  </div>
										</div>
										<?php	
									}
									if($exertio_theme_options['project_sealed_bid_addon'] == 1)
									{
										?>
										<div class="form-row">
										  <div class="col-12">
											<div class="fr-project-adons w2">
											  <ul>
												<li>
												  <div class="pretty p-icon p-thick p-curve">
													<input type="checkbox" name="sealed_bid" />
													<div class="state p-warning">
														<i class="icon fa fa-check"></i>
													  <label></label>
													</div>
												  </div>
												</li>
												<li> <span><?php echo esc_html($exertio_theme_options['project_sealed_addon_title']); ?></span>
												  <p><?php echo esc_html($exertio_theme_options['project_sealed_addon_desc']); ?></p>
												</li>
												<li> <span><?php echo esc_html(fl_price_separator($exertio_theme_options['project_sealed_addon_price'])); ?></span> </li>
											  </ul>
												<div class="bottom-icon">
													<?php echo wp_return_echo($exertio_theme_options['project_sealed_addon_icon']); ?>
												</div>
											</div>
										  </div>
										</div>
										<?php	
									}
									if($exertio_theme_options['project_featured_bid_addon'] == 1)
									{
										?>
										<div class="form-row">
										  <div class="col-12">
											<div class="fr-project-adons w3">
											  <ul>
												<li>
												  <div class="form-group">
													<div class="pretty p-icon p-thick p-curve">
													  <input type="checkbox" name="featured_bid" />
													  <div class="state p-warning">
														<i class="icon fa fa-check"></i>
														<label></label>
													  </div>
													</div>
												  </div>
												</li>
												<li> <span><?php echo esc_html($exertio_theme_options['project_featured_addon_title']); ?></span>
												  <p><?php echo esc_html($exertio_theme_options['project_featured_addon_desc']); ?></p>
												</li>
												<li> <span><?php echo esc_html(fl_price_separator($exertio_theme_options['project_featured_addon_price'])); ?></span> </li>
											  </ul>
												<div class="bottom-icon">
													<?php echo wp_return_echo($exertio_theme_options['project_featured_addon_icon']); ?>
												</div>
											</div>
										  </div>
										</div>
										<?php	
									}
								?>
								<div class="form-row">
								  <div class="col-12">
									<div class="button-bid">
									  <div class="bid-text-checkbox">
									  <div>
										<div class="form-group">
										  <div class="pretty p-icon p-thick p-curve">
											<input type="checkbox" name="privacy_policy" required data-smk-msg="<?php echo esc_html__('Veuillez cocher cette case pour continuer.','exertio_theme'); ?>"/>
											<div class="state p-warning">
												<i class="icon fa fa-check"></i>
											  <label></label>
											</div>
										  </div>
										</div>
										</div> <div><?php echo esc_html__('J\'ai lu les ','exertio_theme'); ?><a href="<?php echo esc_url($exertio_theme_options['bid_tems_link']); ?>"><?php echo esc_html__('conditions générales','exertio_theme'); ?></a></div> </div>
									  <button type="button" class="btn btn-theme btn-loading" id="btn_project_bid" data-post-id ='<?php echo esc_attr($pid); ?>'><?php echo esc_html__('Faire une offre','exertio_theme'); ?>
									  <span class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </span>
									  </button>
									</div>
								  </div>
								</div>
							  </div>
							</form>
						<?php
						}
						else
						{
							?>
							<div class="nothing-found"> <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
							<h4><?php echo esc_html__( 'Projet expiré', 'exertio_theme' ); ?></h4>
							</div>
							<?php
						}
						?>
					  </div>
					</div>
					<?php
				}
				?>
			  </div>
			  <div class="col-lg-4 col-xl-4 col-xs-12 col-sm-12 col-md-12">
				  <div class="project-sidebar position-sticky">
					<div class="project-price">
					  <div class="card-body">
						<div class="row">

						  <div class="col"> <span class="price-label"> <?php echo esc_html__('Budget','exertio_theme'); ?></span>
							<div class="price">
								<?php
									if($project_type == 'fixed')
									{ 
										echo fl_price_separator(get_post_meta($pid, '_project_cost', true));
									}
									else if($project_type == 'hourly')
									{
										echo fl_price_separator(get_post_meta($pid, '_project_cost', true));
									}
								?>
							</div>
						  </div>
						  <div class="feature"> <i class="fal fa-wallet"></i> </div>
						</div>
						<div class="price-bottom">
							<?php
								if($project_type == 'hourly')
								{
									$price = get_post_meta($pid, '_project_cost', true);
									$hours = get_post_meta($pid, '_estimated_hours', true);

									echo '<small class="price_type protip" data-pt-title="'.__('Pour ','exertio_theme').$hours.__(' le montant total sera  ','exertio_theme'). fl_price_separator($hours*$price).'" data-pt-position="top" data-pt-scheme="black">'.esc_html__('Par heure','exertio_theme').' <i class="fal fa-question-circle"></i></small>';
								}
								else if($project_type == 'fixed')
								{
									echo '<small class="price_type ">'.esc_html__('Fixe','exertio_theme').'</small>';
								}
								echo project_expiry_calculation($pid);
							?>

						</div>
					  </div>
					</div>
					<?php
					$banner_img_id = get_post_meta( $employer_id, '_employer_banner_id', true );
					$banner_img = wp_get_attachment_image_src( $banner_img_id, 'full' );

					$cover_img ='';
					if(empty($banner_img ))
					{
						$cover_img = "style='background-image:url(".$exertio_theme_options['employer_df_cover']['url']."); background-repeat: no-repeat; background-size: cover;'";
					}
					else
					{
						$cover_img = "style='background-image:url(".$banner_img[0]."); background-repeat: no-repeat; background-size: cover;'";
					}
					?>
					<div class="fr-project-f-profile">
					  <div class="fr-project-f-product" <?php echo wp_return_echo($cover_img); ?> >
						<?php
							$pro_img_id = get_post_meta( $employer_id, '_profile_pic_attachment_id', true );
							$pro_img = wp_get_attachment_image_src( $pro_img_id, 'thumbnail' );
					  ?>
						<div class="fr-project-f-fetured"> <a href="<?php echo get_permalink($employer_id); ?>"><?php echo get_profile_img($employer_id,'employer'); ?></a> </div>
					  </div>
					  <div class="fr-project-f-user-details">
						<a href="<?php echo get_permalink($employer_id); ?>">
							<h3><?php echo exertio_get_username('employer', $employer_id, 'badge'); ?></h3>
						</a>
						<span><?php echo esc_html__('Membre depuis ','exertio_theme').date_i18n( get_option( 'date_format' ), strtotime( $user_info->user_registered ) ); ?></span> </div>
					  <a href="<?php echo get_permalink($employer_id); ?>" class="btn-style"><?php echo esc_html__('Voir le profil','exertio_theme'); ?></a> </div>
					<div class="fr-project-f-employers">
					  <div class="fr-project-employer-details">
						<h3> <?php echo esc_html__('Concernant le prestataire','exertio_theme'); ?></h3>
					  </div>
					  <?php
								$meta_query = '';
								$the_query = fl_get_projects('',$post_author, $meta_query, 'completed');
								$cp_count = $the_query->found_posts;
							  ?>
					  <ul>
						<li>
						  <div class="fr-project-method"> <i class="fal fa-globe"></i> <span>
							<?php
								echo get_term_names('employer-locations', '_employer_location', $employer_id );
							?>
							</span> </div>
						</li>
						<li>
						  <div class="fr-project-method"> <i class="fal fa-check-square"></i> <span><?php echo esc_html($cp_count).esc_html__(' Projets finalisés','exertio_theme'); ?></span> </div>
						  <div class="fr-project-checked <?php if(isset($cp_count) && $cp_count > 0) { echo "active"; } ?>"> <i class="fas fa-check-circle"></i> </div>
						</li>
						<li>
						  <?php
									$is_payment = get_user_meta( $post_author, 'is_payment_verified' , true );
								?>
						  <div class="fr-project-method"> <i class="fal fa-shield-check"></i> <span><?php echo esc_html__('Méthode de paiement ','exertio_theme'); ?></span> </div>
						  <div class="fr-project-checked <?php if(isset($is_payment) && $is_payment == 1) { echo "active";} ?>"> <i class="fas fa-check-circle"></i> </div>
						</li>
						<li>
						  <?php
									$is_email = get_user_meta( $post_author, 'is_email_verified' , true );
								?>
						  <div class="fr-project-method"> <i class="fal fa-envelope"></i> <span><?php echo esc_html__('Email Verifié','exertio_theme'); ?></span> </div>
						  <div class="fr-project-checked <?php if(isset($is_email) && $is_email == 1) { echo "active";} ?>"> <i class="fas fa-check-circle"></i> </div>
						</li>
					  </ul>
					</div>
					  <?php
					if ( isset($exertio_theme_options[ 'project_detail_sidebar_ad1' ]) && $exertio_theme_options[ 'project_detail_sidebar_ad1' ] != '' )
					{
						?>
						<div class="fl-advert-box">
						<?php
						echo wp_return_echo( $exertio_theme_options['project_detail_sidebar_ad1'] );
						?>
						</div>
						<?php
					}
					$query_args = array( 
										'author__in' => array( $post_author ) ,
										'post_type' =>'projects',
										'posts_per_page'	=> 5,
										'post_status'     => 'publish',
										'post__not_in'           => array(get_the_ID())
										);
					$the_query = new WP_Query( $query_args );
					if ( $the_query->have_posts() )
					{
					?>
					<div class="custom-widget">
					  <h3 class="widget-custom-heading"> <?php echo esc_html__(' Autres projets de ','exertio_theme').exertio_get_username('employer', $employer_id, ''); ?></h3>
					  <div class="custom-widget-body">
					  <?php


							while ( $the_query->have_posts() ) 
							{
								$the_query->the_post();
								$related_pid = get_the_ID();
								?>
									<ul class="list-unstyled related-lists">
										<li class="listing-most-viewed">
										  <div class="listing-viewed-card">
											<div class="listing-viewed-detailz">
											  <h3 class="listing-viewed-title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo	esc_html(get_the_title()); ?></a></h3>
											  <ul class="listing-viewed-stats">
												<li>
												  <p class=""><?php echo fl_price_separator(get_post_meta($related_pid, '_project_cost', true));?>
												  <?php
												  $project_type = get_post_meta($related_pid, '_project_type', true);
												  switch ($project_type) {
												  	case 'fixed':
												  		echo "Fixe";
												  		break;
												  	
												  	default:
												  		echo "par heure";
												  		break;
												  }
													//echo esc_html($project_type);
													?>
													</p>
												</li>
												<li class="my-active-clr">
													<?php
													echo project_expiry_calculation($related_pid);
													?>
												</li>
											  </ul>
											</div>
										  </div>
										</li>
									  </ul>
								<?php
							}
						?>
						</div>
					</div>
					<?php
					}
					?>
					<p class="report-button text-center"> <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#report-modal"><i class="fal fa-exclamation-triangle"></i><?php echo esc_html__('Signaler ce projet','exertio_theme'); ?></a></p>
				  </div>
			  </div>
			</div>
		  </div>
		</section>
		<?php
}
else
{
	wp_redirect(home_url());
}
	get_template_part('footer');
?>
