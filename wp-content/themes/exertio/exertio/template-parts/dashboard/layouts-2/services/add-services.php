<?php global $exertio_theme_options;
$current_user_id = get_current_user_id();

$freelancer_id = get_user_meta( $current_user_id, 'freelancer_id' , true );
if(isset($_GET['sid']) == '' )
{
	$is_service_paid = fl_framework_get_options('is_services_paid');
	if(isset($is_service_paid) && $is_service_paid == 1)
	{
		$simple_services = get_post_meta($freelancer_id, '_simple_services', true);
		$freelancer_package_expiry_date = get_post_meta($freelancer_id, '_freelancer_package_expiry_date', true);
		$today_date = date("d-m-Y");

		if($simple_services < 1 && $simple_services != -1 || strtotime($freelancer_package_expiry_date) < strtotime($today_date))
		{
			echo exertio_redirect(get_the_permalink(fl_framework_get_options('freelancer_package_page')));
		}
	}
}
/*GETTING ADONS DETAILS*/

$args = array( 
			'author__in' => array( $current_user_id ) ,
			'post_type' =>'addons',
			'meta_query' => array(
				array(
					'key' => '_addon_status',
					'value' => 'active',
					'compare' => '=',
					),
				),
			'paged' => $paged,	
			'post_status'     => 'publish'													
			);

$addons = get_posts($args);

$fid = get_user_meta( $current_user_id, 'freelancer_id' , true );
$is_update = '';
	if(isset($_GET['sid']))
	{
		$sid = $_GET['sid'];
		$is_update = $sid;
	}
	else
	{
		$sid = get_user_meta( $current_user_id, '_processing_services_id' , true );
		if(isset($sid) && $sid =='')
		{
			$my_post = array(
				'post_title' => '',
				'post_status' => 'pending',
				'post_author' => $current_user_id,
				'post_type' => 'services'
			);
			$sid = wp_insert_post($my_post);
			update_user_meta( $current_user_id, '_processing_services_id', $sid );
				
		}
	}
$post	=	get_post($sid);
if($post == '')
{
	$my_post = array(
		'post_title' => '',
		'post_status' => 'pending',
		'post_author' => $current_user_id,
		'post_type' => 'services'
	);
	$sid = wp_insert_post($my_post);
	update_user_meta( $current_user_id, '_processing_services_id', $sid );
	$post	=	get_post($sid);
}

	if( $current_user_id == $post->post_author )
	{
		?>
		<div class="content-wrapper">
        <div class="notch"></div>
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2><?php echo esc_html__('Publier un job ','exertio_theme'); ?></h2>
                    <div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
						<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Tableau de bord', 'exertio_theme' ); ?>&nbsp;</p>
						<?php echo exertio_dashboard_extention_return(); ?>
					</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="services_form">
          <div class="row">
            <div class="col-xl-8 col-lg-12 col-md-12 grid-margin stretch-card">
            	<?php 
					if(isset($_GET['sid']) && get_post_status($sid) == 'pending')
					{
				?>
            	<div class="alert alert-warning fade show" role="alert">
                    <div class="alert-icon"><i class="fal fa-exclamation-triangle"></i></div>
                    <div class="alert-text"><?php echo esc_html__('En attente d\'approbation par l\'équipe de Dezon','exertio_theme'); ?></div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                </div>
				<?php
                }
                ?>
                <div class="card mb-4">
                    <div class="card-body">
                      <h4 class="card-title"><?php echo esc_html__('Informations de base','exertio_theme'); ?></h4>
                      
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label><?php echo esc_html__('Titre du job ','exertio_theme'); ?></label>
                          <input type="text" class="form-control" name="services_title" value="<?php echo esc_attr($post->post_title); ?>" <?php if(fl_framework_get_options('service_title') == 1){ ?>required data-smk-msg="<?php echo esc_html__('Veuillez donner un titre assez clair','exertio_theme'); }?>">
                        </div>
                        <div class="form-group col-md-6">
                          <label><?php echo esc_html__('Prix','exertio_theme').' ('.$exertio_theme_options['fl_currency'].')'; ?></label>
                          <input type="text" class="form-control" name="service_price" value="<?php echo esc_attr(get_post_meta( $sid, '_service_price', true )); ?>"  <?php if(fl_framework_get_options('service_price') == 1){ ?>required data-smk-msg="<?php echo esc_html__('Veuillez saisir le prix','exertio_theme'); }?>" data-smk-type="number">
                        </div>
                      <input type="hidden" id="is_update" name="is_update" value="<?php echo esc_attr($is_update); ?>" />

                    </div>
                    <div class="form-row">
                    	<?php
							if(fl_framework_get_options('service_category') == 3)
							{
								
							}
							else
							{
								$cat_check = '';
								if(fl_framework_get_options('service_category') == 1)
								{
									$cat_check = 'required data-smk-msg="'.esc_html__('Veuillez sélectionner une catégorie','exertio_theme').'"';	
								}
								?>
								<div class="form-group col-md-6">
									  <label><?php echo esc_html__('Catégorie ','exertio_theme'); ?></label>
									  <?php
									  $category_taxonomies = exertio_get_terms('service-categories');
										if ( !empty($category_taxonomies) )
										{
											echo '<select name="service_category" class="form-control general_select" '.$cat_check.'>'.get_hierarchical_terms('service-categories', '_service_category', $sid ).'</select>';
										}
									?>
								</div>
								<?php
							}
							?>

							<?php
								if(fl_framework_get_options('services_delivery_time') == 3)
								{
									
								}
								else
								{
									$delivery_time_check = '';
									if(fl_framework_get_options('services_delivery_time') == 1)
									{
										$delivery_time_check = 'required data-smk-msg="'.esc_html__('Veuillez sélectionner le délai de livraison','exertio_theme').'"';	
									}
									?>
									<div class="form-group col-md-6">
									  <label><?php echo esc_html__('Délai de livraison','exertio_theme'); ?></label>
									  <?php
									  $delivery_taxonomies = exertio_get_terms('delivery-time');
										if ( !empty($delivery_taxonomies) )
										{
											$delivery_time = get_post_meta($sid, '_delivery_time', true);
											$delivery = '<select name="delivery_time" class="form-control general_select" '.$delivery_time_check.'>';
											$delivery .= '<option value=""> '. __( "Délai de livraison", "exertio_theme" ) .'</option>';
											foreach( $delivery_taxonomies as $delivery_taxonomy ) {
												if($delivery_taxonomy->term_id == $delivery_time){ $selected = 'selected ="selected"';}else{$selected = ''; }
												if( $delivery_taxonomy->parent == 0 ) {
													 $delivery .= '<option value="'. esc_attr( $delivery_taxonomy->term_id ) .'" '.$selected.'>
															'. esc_html( $delivery_taxonomy->name ) .'</option>';
												}
											}
											$delivery.='</select>';
											echo wp_return_echo($delivery);
										}
									?>
									</div>
									<?php
								}
								?>
                      </div>
                     
                      <div class="form-row">
                      	<?php
						if(fl_framework_get_options('services_location') == 3)
						{
							
						}
						else
						{
							$location_check = '';
							if(fl_framework_get_options('services_location') == 1)
							{
								$location_check = 'required data-smk-msg="'.esc_html__('Veuillez saisir la localisation','exertio_theme').'"';	
							}
							?>
							<div class="form-group col-md-6">
								  <label> <?php echo esc_html__('Localisation','exertio_theme'); ?></label>
								  <?php
								  $location_taxonomies = exertio_get_terms('services-locations');
									if ( !empty($location_taxonomies) )
									{
										echo '<select name="service_location" class="form-control general_select" '.$location_check.'>'.get_hierarchical_terms('services-locations','_service_location', $sid ).'</select>';
									}
								?>
								</div>
							<?php
						}
						?>
						<?php
							

							if(fl_framework_get_options('services_english_level') == 3)
							{
								
							}
							else
							{

							
								$eng_level_check = '';
								if(fl_framework_get_options('services_english_level') == 1)
								{
									$eng_level_check = 'required data-smk-msg="'.esc_html__('Veuillez choisir le niveau de compétence','exertio_theme').'"';	
								}
								?>
								<div class="form-group col-md-6">
								  <label><?php echo esc_html__('Niveau de compétence','exertio_theme'); ?></label>
								  <?php
								  $english_taxonomies = exertio_get_terms('services-english-level');
									if ( !empty($english_taxonomies) )
									{
										$english_level = get_post_meta($sid, '_service_eng_level', true);
										$english = '<select name="english_level" class="form-control general_select" '.$eng_level_check.'>';
										$english .= '<option value=""> '. __( "Sélectionner le niveau", "exertio_theme" ) .'</option>';
										foreach( $english_taxonomies as $english_taxonomy ) {
											if($english_taxonomy->term_id == $english_level){ $selected = 'selected ="selected"';}else{$selected = ''; }
											if( $english_taxonomy->parent == 0 ) {
												 $english .= '<option value="'. esc_attr( $english_taxonomy->term_id ) .'" '.$selected.'>
														'. esc_html( $english_taxonomy->name ) .'</option>';
											}
										}
										$english.='</select>';
										echo wp_return_echo($english);
									}
								?>
								</div>
                            <?php
							}
							?>
                      </div>

                       <div class="form-row">
                    	<?php
						if(fl_framework_get_options('services_response_time') == 3)
						{
							
						}
						else
						{
							$response_time_check = '';
							if(fl_framework_get_options('services_response_time') == 1)
							{
								$response_time_check = 'required data-smk-msg="'.esc_attr__('Veuillez nous en dire plus sur votre disponibilité','exertio_theme').'"';	
							}
							?>
							<div class="form-group col-md-6">
							  <label><?php echo esc_html__('Disponibilité ','exertio_theme'); ?></label>
							  <?php
							  $response_taxonomies = exertio_get_terms('response-time');
								if ( !empty($response_taxonomies) )
								{
									$response_time = get_post_meta($sid, '_response_time', true);
									$response = '<select name="response_time" class="form-control general_select" '.$response_time_check.'>';
									$response .= '<option value=""> '. __( "Disponibilité ", "exertio_theme" ) .'</option>';
									foreach( $response_taxonomies as $response_taxonomy ) {
										if($response_taxonomy->term_id == $response_time){ $selected = 'selected ="selected"';}else{$selected = ''; }
										if( $response_taxonomy->parent == 0 ) {

											 $response .= '<option value="'. esc_attr( $response_taxonomy->term_id ) .'" '.$selected.'>
													'. esc_html( $response_taxonomy->name ) .'</option>';
										}
									}
									$response.='</select>';
									echo wp_return_echo($response);
								}
							?>
							<p><?php echo esc_html__('Votre disponibilité déterminera la vitesse d\'exécution du job.','exertio_theme'); ?></p>
							</div>
							<?php
						}
						
						?>
                      </div>
                    
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                    	<h4 class="card-title"><?php echo esc_html__('Détail du job','exertio_theme'); ?></h4>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label><?php echo esc_html__('Description','exertio_theme'); ?></label>
                              <textarea name="services_desc" id="" class="form-control fl-textarea"><?php echo esc_html($post->post_content); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                  <?php  $pro_img_id = get_post_meta( $sid, '_service_attachment_ids', true );?>
                    <div class="card-body project-attachments">
                      <h4 class="card-title"><?php echo esc_html__('Joindre des pièces jointes','exertio_theme'); ?></h4>
                      <div class="form-row">
                            <div class="form-group col-md-12"> 
                                <label><?php echo __( "Souhaitez vous joindre des pièces jointes?", "exertio_theme" ); ?></label> 
                                <div class="pretty p-switch p-fill ">
                                	<?php $ser_attactment_show = get_post_meta( $sid, '_service_attachment_show', true ); ?>
                                    <input type="checkbox" name="is_show_service_attachments" class="attachment_switch" <?php if(isset($ser_attactment_show) && $ser_attactment_show =='yes') { echo 'checked';} ?> value="<?php echo esc_attr($ser_attactment_show); ?>"/>
                                    <div class="state p-info"><label></label> </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        
						$display = '';
						if(isset($ser_attactment_show) && $ser_attactment_show =='yes')
						{
							$display = 'style=display:block;';	
						}
						?>
                        <div class="form-row img-wrapper" <?php echo esc_attr($display); ?>>
                        	<div class="form-group col-md-12">
                                <div class="upload-btn-wrapper">
                                    <button class="btn btn-theme-secondary mt-2 mt-xl-0" type="button"><?php echo esc_html__('Joindre des pièces jointes','exertio_theme'); ?></button>
                                    <input type="file" id="services_attachments" multiple name="services_attachments[]" accept = "image/pdf/doc/docx/ppt/pptx*" data-post-id="<?php echo esc_attr($sid) ?>"/>
                                    <input type="hidden" name="attachment_ids" value="<?php echo esc_attr($pro_img_id); ?>" id="attachments_ids">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row" >
                        	 <div class="form-group col-md-12 attachment-box sortable attachment-box-services" <?php echo esc_attr($display); ?>> 
                             <?php
                             	if(isset($pro_img_id) && $pro_img_id != '')
									{
										$atatchment_arr = explode( ',', $pro_img_id );
										foreach ($atatchment_arr as $value)
										{
											$icon = get_icon_for_attachment($value);
											echo wp_attachment_is_image($icon);

												echo '<div class="attachments ui-state-default pro-atta-'.$value.'">
														<img src="'.$icon.'" alt="'.get_post_meta( $value, '_wp_attachment_image_alt', true ).'" class="img-fluid" data-img-id="'.$value.'">
														<span class="attachment-data">
															<h4>'.get_the_title($value).'</h4>
															<p>'.esc_html__('taille du fichier','exertio_theme').' '.size_format(filesize(get_attached_file( $value ))).'</p>
															<a href="javascript:void(0)" class="btn_delete_services_attachment" data-id="'.$value.'" data-sid="'.$sid.'"> <i class="fal fa-times-circle"></i></a>
														</span>
													</div>';
										}
									}
								?>
                            </div>
							<input type="hidden" class="services_attachment_ids" name="services_attachment_ids" value="<?php echo esc_attr($pro_img_id); ?>">
							<p class="attachment_note"><?php echo esc_html__('Faites glisser et déposez pour réorganiser et appuyez sur le bouton Enregistrer pour appliquer. La première image sera l\'image d\'affichage principale.','exertio_theme'); ?></p>
                        </div>


                    </div>
                  </div>
				<?php
				if(fl_framework_get_options('services_address') == 3)
				{
					
				}
				else
				{
					?>
                    <div class="card mb-4">
                        <div class="card-body">
                          <h4 class="card-title"><?php echo esc_html__('Informations sur la localisation','exertio_theme'); ?></h4>
                          
                        <?php 
                        
                            $latitude = get_post_meta( $sid, '_services_latitude', true );	
                            $longitude = get_post_meta( $sid, '_services_longitude', true );
                            if($latitude == "" || $longitude == "")
                            {
                                $latitude = $exertio_theme_options['default_lat'];
                                $longitude = $exertio_theme_options['default_long'];									
                            }								
                        ?>
                        <script>
                            (function ($) {
                                'use strict';
                                $( document ).ready(function() {
                                    var markers = [
                                                    {
                                                        'title': '',
                                                        'lat': <?php echo esc_html($latitude); ?>,
                                                        'lng': <?php echo esc_html($longitude); ?>,
                                                    },
                                                ];
                                    var mapOptions = {
                                                center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                                                zoom: 12,
                                                mapTypeId: google.maps.MapTypeId.ROADMAP
                                            };
                                    var map = new google.maps.Map(document.getElementById('google_canvas'), mapOptions);
                                    var latlngbounds = new google.maps.LatLngBounds();
                                    var geocoder = geocoder = new google.maps.Geocoder();
                                    var data = markers[0]
                                    var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                                    var marker = new google.maps.Marker({
                                                    position: myLatlng,
                                                    map: map,
                                                    title: data.title,
                                                    draggable: true,
                                                    animation: google.maps.Animation.DROP
                                                });
                                    (function (marker, data) {
                                        google.maps.event.addListener(marker, 'click', function (e) {
                                            infoWindow.setContent(data.description);
                                            infoWindow.open(map, marker);
                                        });
                        
                                        google.maps.event.addListener(marker, 'dragend', function (e) {
                                            // document.getElementById('sb_loading').style.display	= 'block';
                                            var lat, lng, address;
                                            geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                        
                                                if (status == google.maps.GeocoderStatus.OK) {
                                                    lat = marker.getPosition().lat();
                                                    lng = marker.getPosition().lng();
                                                    address = results[0].formatted_address;
                        
                                                    document.getElementById('searchMapInput').value = address;
                                                    document.getElementById('loc_lat').value = lat;
                                                    document.getElementById('loc_long').value = lng;
                                                    //document.getElementById('sb_loading').style.display	= 'none';
                                                }
                                            });
                                        });
                                    })(marker, data);
                                    latlngbounds.extend(marker.position);
                                    function initMap() {
                                        var input = document.getElementById('searchMapInput');
                                        var autocomplete = new google.maps.places.Autocomplete(input);
                                        autocomplete.addListener('place_changed', function() {
                                            var place = autocomplete.getPlace();
                                            $('#location-snap').val(place.formatted_address); 
                                            $('#loc_lat').val(place.geometry.location.lat());
                                            $('#loc_long').val(place.geometry.location.lng());
                                            
                                            var markers = [
                                                            {
                                                                'title': '',
                                                                'lat': place.geometry.location.lat(),
                                                                'lng': place.geometry.location.lng(),
                                                            },
                                                        ];
                                            var mapOptions = {
                                                            center: new google.maps.LatLng(markers[0].lat, markers[0].lng),
                                                            zoom: 12,
                                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                                        };
                                                        var infoWindow = new google.maps.InfoWindow();
                                                        var latlngbounds = new google.maps.LatLngBounds();
                                                        var geocoder = geocoder = new google.maps.Geocoder();
                                                        var map = new google.maps.Map(document.getElementById('google_canvas'), mapOptions);
                                                        var data = markers[0]
                                                        var myLatlng = new google.maps.LatLng(data.lat, data.lng);
                                                        var marker = new google.maps.Marker({
                                                            position: myLatlng,
                                                            map: map,
                                                            title: data.title,
                                                            draggable: true,
                                                            animation: google.maps.Animation.DROP
                                                        });
                                            
                                            var map = new google.maps.Map(document.getElementById('google_canvas'), mapOptions);
                                            var marker = new google.maps.Marker({
                                                        position: myLatlng,
                                                        map: map,
                                                        title: data.title,
                                                        draggable: true,
                                                        animation: google.maps.Animation.DROP
                                                    });
                                            (function (marker, data) {
                                                                            google.maps.event.addListener(marker, 'click', function (e) {
                                                                                infoWindow.setContent(data.description);
                                                                                infoWindow.open(map, marker);
                                                                            });
                                                                            google.maps.event.addListener(marker, 'dragend', function (e) {
                                                                                // document.getElementById('sb_loading').style.display	= 'block';
                                                                                var lat, lng, address;
                                                                                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                        
                                                                                    if (status == google.maps.GeocoderStatus.OK) {
                                                                                        lat = marker.getPosition().lat();
                                                                                        lng = marker.getPosition().lng();
                                                                                        address = results[0].formatted_address;
                        
                                                                                        document.getElementById('searchMapInput').value = address;
                                                                                        document.getElementById('loc_lat').value = lat;
                                                                                        document.getElementById('loc_long').value = lng;
                                                                                        //document.getElementById('sb_loading').style.display	= 'none';
                                                                                    }
                                                                                });
                                                                            });
                                                                        })(marker, data);
                                                                        latlngbounds.extend(marker.position);
                                            
                                        });
                                    }
                                    initMap();
                                });
                                })(jQuery);
                            </script>
                          
                          <div class="form-row">
                            <div class="form-group col-md-12">
                              <label><?php echo esc_html__('Adresse','exertio_theme'); ?></label>
                              <input type="text" class="form-control" name="services_address" id="searchMapInput" value="<?php echo get_post_meta($sid, '_service_address', true); ?>" <?php if(fl_framework_get_options('services_address') == 1){ ?>required data-smk-msg="<?php echo esc_html__('Please provide address','exertio_theme'); }?>">
                              <i class=" mdi mdi-target" id="abc"></i>
                            </div>
                            <div class="form-group col-md-12">
                              <div id="google_canvas" style="width:100%; height:400px;"></div>
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label><?php echo esc_html__('Latitude','exertio_theme'); ?></label>
                              <input type="text" class="form-control" name="services_lat" id="loc_lat" value="<?php echo get_post_meta($sid, '_service_latitude', true); ?>">
                            </div>
                            <div class="form-group col-md-6">
                              <label><?php echo esc_html__('Longitude','exertio_theme'); ?></label>
                              <input type="text" class="form-control" name="services_long" id="loc_long" value="<?php echo get_post_meta($sid, '_service_longitude', true); ?>">
                            </div>
                          </div>
                        </div>
                    </div>
					<?php
				}
				?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                            	<div class="submit-button-box">
                                    <div class="featured-box">
                                    	<?php
                                    	$featured_btn_disable = $featured_btn_checked = '';
                                    	$featured_service = get_post_meta($sid, '_service_is_featured', true);
                                    	$feature_tooltip = '';
                                    	$feature_tooltip_class = '';
										if(isset($featured_service) && $featured_service == 1)
										{
											$featured_btn_disable = 'disabled=disabled';
											$featured_btn_checked = 'checked=checked';
											$feature_tooltip_class = "protip";
										}
										$featured_services = get_post_meta( $freelancer_id, '_featured_services', true );
										if($featured_services >0 || $featured_services == -1)
										{
											?>
											<div class="pretty p-switch p-fill <?php echo esc_attr($feature_tooltip_class) ?>" <?php echo esc_attr($feature_tooltip); ?>  data-pt-position="top" data-pt-scheme="black" data-pt-title="<?php echo esc_html__('Déjà sponsorisé','exertio_theme'); ?>">
												<input type="checkbox" name="featured_service" <?php echo esc_attr($featured_btn_disable.' '.$featured_btn_checked); ?> />
												<div class="state p-info"><label></label> </div>
											</div>
											<?php
										}
										?>
                                    	<h4><?php echo esc_html(fl_framework_get_options('service_featured_title'));  ?></h4>
                                        <p><?php echo esc_html(fl_framework_get_options('featured_services_detail'));  ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                            	<div class="submit-button-box">
                                    <button type="button" class="btn btn-theme btn-loading" id="fl_services_btn" data-post-id="<?php echo esc_attr($sid) ?>">
                                            <?php echo esc_html__('Publier','exertio_theme'); ?>
                                            <div class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </div>
                                            <input type="hidden" id="save_service_nonce" value="<?php echo wp_create_nonce('fl_save_service_secure'); ?>"  />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
          </div>
            <div class="col-xl-4 col-lg-12 col-md-12 grid-margin stretch-card">
                <div class="card mb-4">
                        <div class="card-body side-addons">
                            <h4 class="card-title"><?php echo esc_html__('Options supplémentaires','exertio_theme'); ?></h4>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <?php
									if(isset($addons) && empty($addons))
									{
										echo '<p>'.esc_html__('Aucune option disponible pour le moment','exertio_theme').'</p>';
									}
                                    $slected_id = json_decode(get_post_meta( $sid, '_services_addon', true ));
                                    foreach ( $addons as $addon ) {
                                       $addon_id =  $addon->ID;
                                       
                                       $selected ='';
                                       if($slected_id !='')
                                       {
                                            if(in_array($addon_id, $slected_id) )
                                            {
                                                $selected ='checked="checked"';   
                                            }
                                       }
                                    ?>
                                        <div class="addon-box">
                                            <div class="pretty p-default">
                                                <input type="checkbox" name="services_addon[]" value="<?php echo esc_attr( $addon_id ) ?>" <?php echo esc_attr($selected) ?> />
                                                <div class="state p-success-o">
                                                    <label></label>
                                                </div>
                                            </div>
                                            <div class="addon-meta">
                                                <label class="addon-heading"><?php echo esc_html(get_the_title($addon_id)); ?></label>
                                                <span class="addon-price"> <span><?php echo esc_html__('Prix ','exertio_theme'); ?></span> <?php echo fl_price_separator(get_post_meta( $addon_id, '_addon_price', true )); ?></span>
                                                <p><?php echo esc_html($addon->post_content); ?></p>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
					
					if($exertio_theme_options['service-faqs'])
					{
					?>
						<div class="card mb-4">
							<div class="card-body side-faqs">
								<h4 class="card-title"><?php echo esc_html__('Ajouter des FAQ','exertio_theme'); ?></h4>
								<div class="form-row">
									<div class="col-md-12">
										<div class="faqs-wrapper sortable">
                                        	<?php
												$service_faqs ='';
												$service_faqs = get_post_meta($sid, '_service_faqs', true);
												if(!empty($service_faqs))
												{
													$faqs = json_decode($service_faqs);

													foreach($faqs as $faq)
													{
														?>
														<div class="faqs-box">
															<h4><?php echo esc_html__('FAQ N°','exertio_theme'); ?></h4>
															<ul><li><i class="fal fa-arrows"></i></li><li class="faq_more_remove"><i class="fal fa-times-circle"></i></li></ul>
															<div class="faqs-box-meta">
																<div class="form-group">
																	<input type="text" name="faqs-title[]"  class="form-control" value="<?php echo esc_html($faq->faq_title); ?>">
																</div>
																<div class="form-group">
																	<textarea name="faq-answer[]" id="" class="form-control"><?php echo esc_html($faq->faq_answer); ?></textarea>
																</div>
															</div>
														</div>
													<?php
													}
												}
												?>
                                        </div>
                                        <a href="javascript:void(0);" class="add_faq_more btn btn-theme" title="Add field">
											<?php echo __( "Ajouter", 'exertio_theme' ); ?>
                                        </a>
                                        <p><?php echo __( "Vous pouvez réorganiser et ajouter plusieurs FAQ", "exertio_theme" ); ?></p>
									</div>
								</div>
							</div>
						</div>
                    <?php
					}
					?>
            </div>
        </div>
        </form>
        </div>
	<?php
    }
	else
	{
				get_template_part( 'template-parts/dashboard/layouts/dashboard');
	}
	?>