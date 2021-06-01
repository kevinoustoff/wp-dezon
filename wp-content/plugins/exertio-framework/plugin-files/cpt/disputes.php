<?php // Register post  type and taxonomy
add_action('init', 'fl_dispute_themes_custom_types', 0);
function fl_dispute_themes_custom_types() {
	 $args = array(
			'public' => true,
			'labels' => array(
							'name' => __('Disputes', 'exertio_framework'),
							'singular_name' => __('Disputes', 'exertio_framework'),
							'menu_name' => __('Disputes', 'exertio_framework'),
							'name_admin_bar' => __('Disputes', 'exertio_framework'),
							'add_new' => __('Add New Dispute', 'exertio_framework'),
							'add_new_item' => __('Add New Dispute', 'exertio_framework'),
							'new_item' => __('New Disputes', 'exertio_framework'),
							'edit_item' => __('Edit Disputes', 'exertio_framework'),
							'view_item' => __('View Disputes', 'exertio_framework'),
							'all_items' => __('All Disputes', 'exertio_framework'),
							'search_items' => __('Search Disputes', 'exertio_framework'),
							'not_found' => __('No Dispute Found.', 'exertio_framework'),
							),
			'supports' => array('title', 'editor'),
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => true,
			'has_archive' => true,
			'menu_icon'           => FL_PLUGIN_URL.'/images/law.png',
			'rewrite' => array('with_front' => false, 'slug' => 'disputes'),
			'capabilities' => array(
				'create_posts' => false,
			),
			'map_meta_cap' => true,
		);
	register_post_type('disputes', $args);


	add_filter('manage_edit-disputes_columns', 'disputes_columns_id');
    add_action('manage_disputes_posts_custom_column', 'disputes_custom_columns', 5, 2);
 
 
	function disputes_columns_id($defaults){
		unset($defaults['date']);

		$defaults['project_name'] =  __('Project Name', 'exertio_framework');
		$defaults['price'] =  __('Price', 'exertio_framework');
		$defaults['author'] =  __('Author', 'exertio_framework');
		$defaults['date'] =  __('Date', 'exertio_framework');

		return $defaults;
		
	}
	function disputes_custom_columns($column_name, $id){
		$project_id = get_post_meta( $id, '_project_id', true ); 
		if($column_name === 'project_name')
		{
			echo '<a href="'.get_the_permalink($project_id).'">'.get_the_title($project_id).'</a>'; 
		}
		if($column_name === 'price')
		{
			//echo get_post_meta( $id, '_dispute_price', true );
			$type = get_post_meta($project_id, '_project_type', true);
			if($type == 'fixed')
			{
				echo esc_html(fl_price_separator(get_post_meta($project_id, '_project_cost', true)).'/'.$type);
			}
			else if($type == 'hourly')
			{
				echo esc_html(fl_price_separator(get_post_meta($project_id, '_project_cost', true)).' '.$type);
				//echo '<small class="estimated-hours">'.esc_html__( 'Estimated Hours ', 'exertio_theme' ).get_post_meta($project_id, '_estimated_hours', true).'</small>';
			}  
		}
	}


	add_action( 'load-post.php', 'disputes_post_meta_boxes_setup' );
	add_action( 'load-post-new.php', 'disputes_post_meta_boxes_setup' );
	
	
	function disputes_post_meta_boxes_setup() {
	
	  /* Add meta boxes on the 'add_meta_boxes' hook. */
	  add_action( 'add_meta_boxes', 'disputes_add_post_meta_boxes' );
	  
	  /* Save post meta on the 'save_post' hook. */
	  add_action( 'save_post', 'disputes_save_post_class_meta', 10, 2 );
	  
	}
	
	/* Create one or more meta boxes to be displayed on the post editor screen. */
	function disputes_add_post_meta_boxes() {
	
	  add_meta_box(
		'dispute-post-class',      // Unique ID
		esc_html__( 'Add Disputes Detail', 'exertio_framework' ),    // Title
		'disputes_post_class_meta_box',   // Callback function
		'disputes',
		'normal',         // Context
		'default'         // Priority
	  );
	}
	
	function disputes_post_class_meta_box( $post ) { ?>
		
	  <?php wp_nonce_field( basename( __FILE__ ), 'disputes_post_class_nonce' ); 
		//print_r($post);
		$post_id =  $post->ID;
		?>
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Project Price", 'exertio_framework' ); ?></label></div>
            <div class="col-3">
            <?php 
				$project_id = get_post_meta( $post_id, '_project_id', true ); 
				$type = get_post_meta($project_id, '_project_type', true);
				if($type == 'fixed')
				{
					echo esc_html(fl_price_separator(get_post_meta($project_id, '_project_cost', true)).'/'.$type);
				}
				else if($type == 'hourly')
				{
					echo esc_html(fl_price_separator(get_post_meta($project_id, '_project_cost', true)).' '.$type);
					//echo '<small class="estimated-hours">'.esc_html__( 'Estimated Hours ', 'exertio_theme' ).get_post_meta($project_id, '_estimated_hours', true).'</small>';
				}
			?>
            </div>
        </div> 
        
        
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Project", 'exertio_framework' ); ?></label></div>
            <div class="col-3">
            <a href="<?php  echo esc_url(get_permalink($project_id)); ?>"><?php echo esc_html(get_the_title($project_id)); ?></a>
            </div>
        </div>
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Dispute Status", 'exertio_framework' ); ?></label></div>
            <div class="col-3">
            <?php
				$badge_color ='';
				$status = get_post_meta($post_id,'_dispute_status',true);
				if( $status == 'ongoing') { $badge_color = 'btn-inverse-warning';}
				else if($status == 'resolved'){ $badge_color = 'btn-inverse-success';}
			?>
            <span class="badge btn <?php echo esc_html($badge_color); ?>">
				<?php echo esc_html($status); ?>
            </span>
            </div>
        </div>
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Dispute Conversation", 'exertio_framework' ); ?></label></div>
            <div class="col-9">
            	<div class="project-history">
                    <div class="history-body">
                        <div class="history-chat-body">
                            <?php
                            $messages = exertio_get_dispute_msgs($post_id);
                            if($messages)
                            {
                                foreach($messages as $message)
                                {
                                    //$msg_author = get_user_meta( $current_user_id, 'employer_id' , true );
									$dispute_author = get_post_field( 'post_author', $post_id );
									$msg_author = get_user_meta( $dispute_author, 'employer_id' , true );
                                    
                                    $project_id = get_post_meta($dispute_id,'_project_id', true);
                                    $project_owner = get_post_field( 'post_author', $project_id );
                                    if($project_owner == $current_user_id)
                                    {
										
                                        $msg_author_name = exertio_get_username('employer',$message->msg_author_id);
										$msg_receiver_name = exertio_get_username('freelancer',$message->msg_receiver_id);
                                        
                                        $msg_author_pic = get_profile_img($message->msg_author_id, "employer");
                                        $msg_receiver_pic = get_profile_img($msg_author, "freelancer");
                                        
                                    }
                                    else
                                    {
                                        
										$msg_author_name = exertio_get_username('freelancer',$message->msg_receiver_id);
										$msg_receiver_name = exertio_get_username('employer',$message->msg_author_id);
                                        
                                        $msg_author_pic = get_profile_img($msg_author, "freelancer");
                                        $msg_receiver_pic = get_profile_img($message->msg_author_id, "employer");
                                    }
                                    if($msg_author == $message->msg_author_id)
                                    {
                                        ?>
                                        <div class="chat-single-box">
                                            <div class="chat-single chant-single-right">
                                                <div class="history-user">
                                                    <span class="history-datetime"><?php echo time_ago_function($message->timestamp); ?></span>
                                                    <a href="#" class="history-username"><?php echo $msg_author_name; ?></a>
                                                    <span><?php echo $msg_author_pic; ?></span>
                                                </div>
                                                <p class="history-text">
                                                    <?php echo esc_html(wp_strip_all_tags($message->message)); ?>
                                                </p>
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
                                                        <?php echo $msg_receiver_pic; ?>
                                                    </span>
                                                    <a href="#" class="history-username"><?php echo $msg_receiver_name; ?></a>
                                                    <span class="history-datetime"><?php echo time_ago_function($message->timestamp); ?></span>
                                                </div>
                                                <p class="history-text">
                                                    <?php echo esc_html(wp_strip_all_tags($message->message)); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            else
                            {
                                ?>
                                <p class="text-center"><?php echo esc_html__( 'No messgae found', 'exertio_theme' ); ?></p>
                                <?php	
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Admin Response", 'exertio_framework' ); ?></label></div>
            <div class="col-9">
            	<?php 
					$admin_feedback ='';
					$admin_feedback = get_post_meta($post_id, '_admin_feedback', true);
				?>
            	<textarea name="admin_feedback" rows="8" ><?php echo $admin_feedback; ?></textarea>
            <p><?php echo __( "This will be visible to both users on their dispute detail page", "exertio_framework" ); ?></p>
            </div>
        </div>
        <div class="custom-row">
            <div class="col-3"><label><?php echo __( "Mark Dispute as resolved", 'exertio_framework' ); ?></label></div>
            <div class="col-9">
            	<?php 
					$dispute_status ='';
					$checked ='';
					$dispute_status = get_post_meta($post_id, '_dispute_status', true);
					
					if(isset($dispute_status) && $dispute_status == 'resolved')
					{
						$checked =" checked='checked'";	
					}
				?>
            	<input type="checkbox" name="dispute_status" <?php echo esc_attr($checked); ?> >
                <p><?php echo __( "Check this to mark it resolved", "exertio_framework" ); ?></p>
            </div>
        </div>
        
    <?php }

	
	/* Save the meta box's post metadata. */
	function disputes_save_post_class_meta( $post_id, $post ) {
	
	  /* Verify the nonce before proceeding. */
	  if ( !isset( $_POST['disputes_post_class_nonce'] ) || !wp_verify_nonce( $_POST['disputes_post_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;
	
	  /* Get the post type object. */
	  $post_type = get_post_type_object( $post->post_type );
	
	  /* Check if the current user has permission to edit the post. */
	  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
		
		if(isset($_POST['dispute_status']))
		{
			update_post_meta($post_id,'_dispute_status','resolved');
		}
		else
		{
			update_post_meta($post_id,'_dispute_status','ongoing');	
		}
		if(isset($_POST['admin_feedback']))
		{
			update_post_meta( $post_id, '_admin_feedback', $_POST['admin_feedback']);
		}
	}
}