<?php
$alt_id = '';
if ( get_query_var( 'paged' ) )
{
  $paged = get_query_var( 'paged' );
}
else if ( get_query_var( 'page' ) )
{
  $paged = get_query_var( 'page' );
}
else
{
  $paged = 1;
}
$customer_orders1 = $customer_orders = array();
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
	$customer_orders1 = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
		'numberposts' => -1,
		'meta_key' => '_customer_user',
		'meta_value' => get_current_user_id(),
		'post_type' => wc_get_order_types('view-orders'),
		'post_status' => 'all',
	)));
	$total_posts = count($customer_orders1);


	$posts_per_page = get_option('posts_per_page');
	$total_pages = ceil($total_posts / $posts_per_page);
	$customer_orders = get_posts(array(
		'meta_key' => '_customer_user',
		'meta_value' => get_current_user_id(),
		'post_type' => wc_get_order_types('view-orders'),
		'posts_per_page' => $posts_per_page,
		'paged' => $paged,
		'post_status' => 'all'
	));
}

$order_array = [];
    foreach ($customer_orders as $customer_order) 
	{
        $order = wc_get_order($customer_order);
		$order_items = $order->get_items();
		foreach ( $order_items as $item )
		{
			$product_name = $item->get_name();
			$product = wc_get_product( $item['product_id'] );

			$product_type = $product->get_type();
		}

			$product_name_text = '';
			if(isset($product_type) && $product_type == 'wallet')
			{
				$product_name_text = esc_html__( 'Montant du portefeuille', 'exertio_theme' );
			}
			else if(isset($product_type) && $product_type == 'employer-packages' || $product_type == 'freelancer-packages')
			{
				$product_name_text = $product_name;
			}
        $order_array[] = [
            "ID" => $order->get_id(),
            "price" => $order->get_total(),
			"product_name" => $product_name_text,
            "date" => $order->get_date_created()->date_i18n('Y-m-d'),
			"status" => $order->get_status(),
        ];
    }

?>
<div class="content-wrapper">
        <div class="notch"></div>
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2><?php echo esc_html__('Portefeuille','exertio_theme'); ?></h2>
					<div class="d-flex"> <i class="fas fa-home text-muted d-flex align-items-center"></i>
						<p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;<?php echo esc_html__('Tableau de bord', 'exertio_theme' ); ?>&nbsp;</p>
						<?php echo exertio_dashboard_extention_return(); ?>
					</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
			<div class="col-xl-4 col-lg-12 col-md-12 grid-margin  stretch-card">
            <?php get_template_part( 'template-parts/dashboard/invoices/deposit-funds'); ?>
            </div>
			  <div class="col-xl-8 col-lg-12 col-md-12 grid-margin stretch-card">
                  <div class="card mb-4">
                    <div class="card-body">
                      <div class="pro-section">
                          <div class="pro-box heading-row">
                            <div class="pro-coulmn no-flex-grow"><?php echo esc_html__( 'Facture N°', 'exertio_theme' ) ?> </div>
                            <div class="pro-coulmn"><?php echo esc_html__( 'Montant', 'exertio_theme' ) ?> </div>
                            <div class="pro-coulmn"><?php echo esc_html__( 'Statut', 'exertio_theme' ) ?> </div>
                            <div class="pro-coulmn"><?php echo esc_html__( 'Description', 'exertio_theme' ) ?> </div>
                          </div>
                            <?php
                                if ( !empty($order_array) )
                                {
                                    foreach ( $order_array as $array ) 
                                    {
                                        
                                        ?>
                                          <div class="pro-box">
                                            <div class="pro-coulmn pro-title  no-flex-grow">
                                                <h4 class="pro-name"><a href="<?php get_template_part('');?>?ext=invoice-detail&invoice-id=<?php echo esc_attr($array['ID']); ?>"><?php echo esc_html($array['product_name']); ?></a></h4>
												<span class="date"><?php  echo esc_html(date_i18n( get_option( 'date_format' ), strtotime( $array['date'] ) )); ?></span>
                                            </div>
                                            <div class="pro-coulmn">
                                                <?php echo esc_html(fl_price_separator($array['price'])); ?>
                                            </div>
                                            <div class="pro-coulmn">
                                            	<?php
												$badge_color ='';
                                                if( $array['status'] == 'completed') { $badge_color = 'btn-inverse-success';}
												else if($array['status'] == 'processing'){ $badge_color = 'btn-inverse-primary';}
												else if($array['status'] == 'pending'){ $badge_color = 'btn-inverse-warning';}
												else if($array['status'] == 'on-hold'){ $badge_color = 'btn-inverse-dark';}
												else if($array['status'] == 'cancelled'){ $badge_color = 'btn-inverse-secondary';}
												else if($array['status'] == 'refunded'){ $badge_color = 'btn-inverse-info';}
												else if($array['status'] == 'failed'){ $badge_color = 'btn-inverse-danger';}
												?>
                                            	<span class="badge btn <?php echo esc_html($badge_color); ?>">
                                            	<?php 
                                              switch ($array['status']) {
                                                case 'processing':
                                                   echo esc_html('en traitement');
                                                  break;
                                                case 'pending':
                                                   echo esc_html('en cours');
                                                  break;
                                                case 'on-hold':
                                                   echo esc_html('en attente');
                                                  break;
                                                case 'cancelled':
                                                   echo esc_html('annulé');
                                                  break;
                                                case 'refunded':
                                                   echo esc_html('remboursé');
                                                  break;
                                                case 'failed':
                                                   echo esc_html('echoué');
                                                  break;
                                                case 'completed':
                                                   echo esc_html('terminé');
                                                  break;
                                                default:
                                                  echo esc_html('en traitement');
                                                  break;
                                              }
                                              ?>
                                                </span>
                                            </div>
                                            <div class="pro-coulmn"><a href="<?php get_template_part('');?>?ext=invoice-detail&invoice-id=<?php echo esc_html($array['ID']); ?>" class="btn btn-secondary"><?php echo esc_html__( 'Détails', 'exertio_theme' ); ?></a></div>
                                          </div>
                                      
                                        <?php
                                    }
									echo custom_pagination_invoices($total_posts, $paged);
                                }
                                else
                                {
                                    ?>
                                    <div class="nothing-found">
                                        <h3><?php echo esc_html__( 'Désolé!! Aucune facture trouvée', 'exertio_theme' ) ?></h3>
                                        <img src="<?php echo get_template_directory_uri() ?>/images/dashboard/nothing-found.png" alt="<?php echo get_post_meta($alt_id, '_wp_attachment_image_alt', TRUE); ?>">
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