<?php 
$pid = get_the_ID();
$report_title = '';
if ( is_singular( 'freelancer' ) )
{
	$report_title = esc_html__('Signaler ce prestataire', 'exertio_theme');
}
else if (  is_singular( 'services' ))
{
	$report_title = esc_html__('Signaler ce service', 'exertio_theme');
}
else if ( is_singular( 'projects' ) )
{
	$report_title = esc_html__('Signaler ce projet', 'exertio_theme');
}
if (is_singular( 'employer' ) )
{
	$report_title = esc_html__('Signaler ce client', 'exertio_theme');
}
?>
<div class="modal fade forget_pwd" id="report-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="modal-from report-form" method="POST" id="report-form">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo esc_html($report_title); ?></h5>
          <button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
			<div class="fr-report-form">
				<div class="form-group">
					<label><?php echo esc_html__('Raison du signalement','exertio_theme'); ?></label>
					<?php
					  $report_category_taxonomies = exertio_get_terms('report-category');
						if ( !empty($report_category_taxonomies) )
						{
							$report_category = '<select name="report_category" class="form-control general_select">';

							foreach( $report_category_taxonomies as $report_category_taxonomy ) {
								if( $report_category_taxonomy->parent == 0 ) {
									 $report_category .= '<option value="'. esc_attr( $report_category_taxonomy->term_id ) .'">
											'. esc_html( $report_category_taxonomy->name ) .'</option>';
								}
							}
							$report_category.='</select>';
							echo wp_return_echo($report_category);
						}
					?>
				</div>
				<div class="form-group">
				  <label><?php echo esc_html__('Message complémentaire','exertio_theme'); ?></label>
				  <textarea name="report_desc" class="form-control" id="" class="form-control" required data-smk-msg="<?php echo esc_html__('Required field','exertio_theme');?>"></textarea>
				</div>
				<div class="form-group">
					<input type="hidden" id="fl_report_nonce" value="<?php echo wp_create_nonce('fl_report_secure'); ?>"  />
					<button type="button" id="btn-report" class="btn btn-theme btn-loading btn-report" data-post-id="<?php echo esc_attr($pid); ?> "><?php echo esc_html__("Envoyer", 'exertio_theme'); ?><span class="bubbles"> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> <i class="fa fa-circle"></i> </span></button>
				</div>
			</div>
        
        </div>
      </form>
    </div>
  </div>
</div>