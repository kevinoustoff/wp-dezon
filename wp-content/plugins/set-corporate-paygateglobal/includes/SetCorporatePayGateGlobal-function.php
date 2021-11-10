<?php

defined('ABSPATH') or exit;

global $wpdb;
$table_name = $wpdb->prefix . "SetCorp_data";
$charset_collate = $wpdb->get_charset_collate();


// Make sure WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	return;
}

/**
 * remove the plugin from the available payment gateway if the trial expired and is not activate
 */
function setcorp_conditional_pay_gateways($available_gateways)
{
	if (is_admin())
		return $available_gateways;


	unset($available_gateways['paygateglobal_gateway']);
	return $available_gateways;
}


/**
 * Add the gateway to WC Available Gateways
 * 
 * @since 1.0.0
 * @param array $gateways all available WC gateways
 * @return array $gateways all WC gateways + offline gateway
 */
function setcorp_add_paygateglobal_to_gateways($gateways)
{
	$gateways[] = 'SETCORP_PayGateGlabal_Gateway';
	return $gateways;
}


/**
 * Adds plugin page links
 * 
 * @since 1.0.0
 * @param array $links all plugin links
 * @return array $links all plugin links + our custom links (i.e., "Settings")
 */
function setcorp_paygateglobal_gateway_plugin_links($links)
{

	$plugin_links = array(
		'<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=paygateglobal_gateway') . '">' . __('Configure', 'wc-paygateglobal_gateway') . '</a>'
	);
	return array_merge($plugin_links, $links);
}



add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'setcorp_paygateglobal_gateway_plugin_links');

/**
 * PayGateGlobal Payment Gateway
 *
 * Provides an Online Payment by Flooz and Tmoney .
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class 		WC_PayGateGlobal_Payment
 * @extends		WC_Payment_Gateway
 * @version		2.1.0
 * @package		WooCommerce/Classes/Payment
 * @author 		SET Corporate
 */
add_action('plugins_loaded', 'setcorp_PayGateGlobal_init', 11);

//add_action('admin_notices','setcorp_show_notice');
Notify($table_name,$wpdb);

add_filter('woocommerce_payment_gateways', 'setcorp_add_paygateglobal_to_gateways');

//remove payment mehod
function setcorp_RemovePaymentMethod()
{
	add_filter('woocommerce_available_payment_gateways', 'setcorp_conditional_pay_gateways');
}

function Notify($table_name,$wpdb){
	$expire = setcorp_HasExpired($table_name, 1, $wpdb);
						if ($expire) {
							//the trial period has expided
							// we removed the plugin from the payment method
							add_action('admin_notices','setcorp_show_notice');

							
						} else {
							//the trial period still valid
						}
}




//create table
setcorp_PayGateTable($table_name);

//get table values
$info = setcorp_GetData($table_name, 1, $wpdb);

if ($info == null) {
	//table is empty
	setcorp_InsertDefault($table_name, $wpdb);
}

function setcorp_PayGateGlobal_init()
{
	
	class SETCORP_PayGateGlabal_Gateway extends WC_Payment_Gateway
	{
		
		public $apikey;
		public $paygateglobal_description;
		public $activationdate;
		public $activationcode;
		public $failed;
		public $iconpath;

		/**
		 * Constructor for the gateway.
		 */
		public function __construct()
		{

			if(file_exists(plugins_url("../assets/PayGateGlobal_logo.png",__FILE__))){
				$this->iconpath=plugins_url("../assets/PayGateGlobal_logo.PNG", __FILE__);
			}else{
				$this->iconpath=plugins_url("PayGateGlobal_logo.PNG", __FILE__);
			}

			$this->id                 = 'paygateglobal_gateway';
			$this->icon               = $this->iconpath;
			$this->has_fields         = true;
			$this->method_title       = __('SET Corporate MobilePay', 'wc-paygateglobal_gateway');
			$this->method_description = __("Permet aux clients de payer avec Flooz ou Tmoney.<br/>Pour avoir le <b>code d'activation</b> appeler l'un des numéro suivants: <b>98 62 68 56</b> | <b>91 24 88 44</b>", "wc-paygateglobal_gateway");

			global $wpdb;
			$table_name = $wpdb->prefix . "SetCorp_data";

			// Load the settings.
			$this->setcorp_init_form_fields();
			$this->init_settings();


			// Define user set variables
			$this->title        = $this->get_option('title', 'Paiement sur PayGate Global');
			$this->description  = $this->get_option('description');
			$this->instructions = $this->get_option('instructions', $this->description);
			$this->apikey = $this->get_option('Api_Key');
			$this->paygateglobal_description = $this->get_option('Paygate_description', 'Paiement sur ' . setcorp_GetSiteTitle());
			$this->activationcode = $this->get_option('Code_Activation');
			$info = setcorp_GetData($table_name, 1, $wpdb);
			$datafin = new DateTime($info->DateFin);
			$this->activationdate = $this->get_option('Date_Activation', $datafin->format('d-m-Y'));


			// Actions
			if (strlen($this->activationcode) > 0) {
				// activation code entred
				$code = $this->activationcode;
				if (strlen($code) > 15) {
					//activate
					$rslt = setcorp_InfoDetails(setcorp_GetDataInfo($code));

					if ($info->Statut == '1993' && $info->SiteName == setcorp_GetSiteTitle() && $rslt->Statut == '1993' && $rslt->SiteName == setcorp_GetSiteTitle()) {
						// is paid
						//already saved
					} else if ($info->Statut == '9862' && $info->SiteName == setcorp_GetSiteTitle() && $rslt->Statut == '9862' && $rslt->SiteName == setcorp_GetSiteTitle()) {
						// NOT paid extented trial
						//already saved

						//check if the extented trial has'nt expired
						$expire = setcorp_HasExpired($table_name, 1, $wpdb);
						if ($expire) {
							//the trial period has expided
							// we removed the plugin from the payment method
							setcorp_RemovePaymentMethod();

							$this->failed = true;
						} else {
							//the trial period still valid
						}
					} else {
						// Not saved yet
						// check and save to database

						if ($rslt != false) {
							//check if the site name in the activation code and in the database are same
							if ($rslt->SiteName != setcorp_GetSiteTitle()) {
								//the activation code is not for this site
								$this->failed = true;
								setcorp_Update($table_name, $rslt, $wpdb);
								setcorp_RemovePaymentMethod();
							} else {
								//check validity
								if ($rslt->Statut == '1993') {
									//paid
									$this->failed = false;
								} else if ($rslt->Statut == '9862') {
									//extend trial
									//check if the extented trial has'nt expired
									$expire = setcorp_HasExpired_Code($rslt);
									if ($expire) {
										//the trial period has expided
										// we removed the plugin from the payment method
										setcorp_RemovePaymentMethod();

										$this->failed = true;
									} else {
										//the trial period still valid
									}
								} else {
									// invalid code
									$this->failed = true;
									setcorp_RemovePaymentMethod();
								}
								setcorp_Update($table_name, $rslt, $wpdb);
							}
						} else {
							$this->failed = true;
							setcorp_RemovePaymentMethod();
						}
					}
				} else {
					$this->failed = true;
					setcorp_RemovePaymentMethod();
				}
			} else {
				$expired = setcorp_HasExpired($table_name, 1, $wpdb);
				if ($expired) {
					//the trial period has expided
					// we removed the plugin from the payment method
					setcorp_RemovePaymentMethod();

					$this->failed = true;
				} else {
					// must be activate
					$ccodeinfo = $info; //GetData($table_name,1,$wpdb);
					if ($ccodeinfo->Statut == 1993 && $ccodeinfo->SiteName == setcorp_GetSiteTitle()) {
						$this->failed = false;
					} else if ($ccodeinfo->Statut == 1 && $ccodeinfo->SiteName == setcorp_GetSiteTitle()) {
						$this->failed = false;
					} else if ($ccodeinfo->Statut == 9862 && $ccodeinfo->SiteName == setcorp_GetSiteTitle()) {
						//extend trial
						//check if the extented trial has'nt expired
						$expire = setcorp_HasExpired($table_name, 1, $wpdb);
						if ($expire) {
							//the trial period has expided
							// we removed the plugin from the payment method
							setcorp_RemovePaymentMethod();

							$this->failed = true;
						} else {
							//the trial period still valid
							$this->failed = false;
						}
						
					} else {
						$code = $this->activationcode;
						if (strlen($code) > 15) {
							//activate
							$rslt = setcorp_InfoDetails(setcorp_GetDataInfo($code));
							if ($rslt != false) {
								//check if the site name in the activation code and in the database are same
								if ($ccodeinfo->SiteName != $rslt->SiteName) {
									//the activation code is not for that site
									$this->failed = true;
									//display a message to the admin
									
									setcorp_RemovePaymentMethod();
									setcorp_Update($table_name, $rslt, $wpdb);
								} else {
									setcorp_Update($table_name, $rslt, $wpdb);
									$this->failed = false;
								}
							} else {
								// the activation code is not valid
								$this->failed = true;
								
								setcorp_RemovePaymentMethod();
							}
						} else {
							// the activation code is not valid
							$this->failed = true;
							
							setcorp_RemovePaymentMethod();
						}
					}
				}
			}
			
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		}

		
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function setcorp_init_form_fields()
		{

			$this->form_fields = apply_filters('wc-paygateglobal_gateway_form_fields', array(

				'enabled' => array(
					'title'   => __('Activer/Desactiver', 'wc-paygateglobal_gateway'),
					'type'    => 'checkbox',
					'label'   => __('Activer Paiement PayGateGlobal', 'wc-paygateglobal_gateway'),
					'default' => 'yes'
				),

				'Api_Key' => array(
					'title'       => __('Clé API PayGate', 'wc-paygateglobal_gateway'),
					'type'        => 'text',
					'description' => __("La clé d'activation de l'api", 'wc-paygateglobal_gateway'),
					'default'     => __("Clé d'activation", 'wc-paygateglobal_gateway'),
					'desc_tip'    => true,
				),
				'Paygate_description' => array(
					'title'       => __('Description PayGate', 'wc-paygateglobal_gateway'),
					'type'        => 'text',
					'description' => __("Le message qui sera afficher au client lors du paiement sur PayGate", 'wc-paygateglobal_gateway'),
					'default'     => __("Paiement sur " . setcorp_GetSiteTitle(), 'wc-paygateglobal_gateway'),
					'desc_tip'    => true,
				),

				'title' => array(
					'title'       => __('Titre', 'wc-paygateglobal_gateway'),
					'type'        => 'text',
					'description' => __('Cela contrôle le titre du mode de paiement que le client voit lors du paiement.', 'wc-paygateglobal_gateway'),
					'default'     => __('Paiement sur PayGate Global', 'wc-paygateglobal_gateway'),
					'desc_tip'    => true,
				),

				'description' => array(
					'title'       => __('Description', 'wc-paygateglobal_gateway'),
					'type'        => 'textarea',
					'description' => __('Description du mode de paiement que le client verra lors de votre paiement.', 'wc-paygateglobal_gateway'),
					'default'     => __("Vous serez redirigé vers le portail de paiement de PayGate Global", 'wc-paygateglobal_gateway'),
					'desc_tip'    => true,
				),

				'instructions' => array(
					'title'       => __('Instructions', 'wc-paygateglobal_gateway'),
					'type'        => 'textarea',
					'description' => __('Vous serez redirigé vers le portail de paiement de PayGate Global', 'wc-paygateglobal_gateway'),
					'default'     => '',
					'desc_tip'    => true,
				),
				'Code_Activation' => array(
					'title'       => __("Code d'activation", 'wc-paygateglobal_gateway'),
					'type'        => 'textarea',
					'description' => __("Entrez la clé d'activation du produit", 'wc-paygateglobal_gateway'),
					'default'     => '',
					'desc_tip'    => true,
				),
				'Date_Activation' => array(
					'title'       => __("Date de fin d'activation", 'wc-paygateglobal_gateway'),
					'type'        => 'text',
					'disabled'	  => true,
					'description' => __("Date de fin d'activation du produit", 'wc-paygateglobal_gateway'),
					'default'     => '',
					'desc_tip'    => true,
				)
			));
		}

		public function setcorp_check_extented_trial($tbl, $id, $wpdb)
		{
			//check if the extented trial has'nt expired
			$expire = setcorp_HasExpired($tbl, $id, $wpdb);
			if ($expire) {
				//the trial period has expided
				// we removed the plugin from the payment method
				setcorp_RemovePaymentMethod();

				$this->failed = true;
			} else {
				//the trial period still valid
			}
		}


		/**
		 * Override process_payment of the main gateway
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment($order_id)
		{


			if ($this->failed) {
				
				return;
			}

			//get the currency
			//$currencies=get_woocommerce_currencies();
			$currency_code=get_woocommerce_currency();
			
			$order = wc_get_order($order_id);

			if($currency_code!="XOF"){
				wc_add_notice("Changer la devise en XOF (Franc de la BCEAO) et réessayez s'il vous plaît!",'error');
				$order->update_status('cancelled');
				return;
			}

			////////////////////////////////////////////////////
			
			//somme à payer
			$order_total_cost = $order->get_total();

			//transaction_interne_id
			$paygate_interne_transaction_id = setcorp_TransactionUniqueId();

			//orderid
			$data = $order_id;
			//woocommerce think you page url
			$url = $this->get_return_url($order);


			//callback url
			$callback_url = get_rest_url() . "SetCorporate/v1/payment?order_id=$data|$url|$paygate_interne_transaction_id";


			$urldata = "https://paygateglobal.com/v1/page?token=" . $this->apikey . "&amount=" . round($order_total_cost) . "&description=" . $this->paygateglobal_description . "&identifier=" . $paygate_interne_transaction_id . "&url=" . $callback_url;



			//  redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $urldata
			);
			
		}

		
		
	}
	
}


/**
 * Get the site base url
 * @return string
 */
function setcorp_BaseUrl()
{
	return sprintf(
		"%s://%s",
		isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		$_SERVER['HTTP_HOST']
	);
}

function setcorp_show_notice(){
	$screen=get_current_screen();
	if($screen->base =='plugins'){
		echo('<div class="notice notice-error is-dismissible">
			<p>
				Vos 10 jours d\'éssai du plugin <span style="font-weight:bolder">SET Corporate MobilePay</span> sont passés. Merci d\'entrer le code d\'activation.
			</p>
		</div>');
	}
}


/**
 * Get the site name
 * @return string
 */
function setcorp_GetSiteTitle()
{
	return get_bloginfo('name');
}


function setcorp_GetD()
{
	return base64_encode('paygateglobalees');
}


/**
 * Decript the coded string 'str'
 * 	@param string $str
 * @return string $rslt
 */
function setcorp_GetDataInfo($str)
{
	$rslt = openssl_decrypt(base64_decode($str), "AES-128-ECB", setcorp_GetD());
	return $rslt;
}


function setcorp_InfoDetails($data)
{
	if (strlen($data) > 20) {
		$dat = explode(';', $data);

		$info = new SETCORP_ActivationInfo();
		$info->DateDebut = ($dat[2]);
		$info->DateFin = ($dat[3]);
		$info->Id = 1;
		$info->Statut = $dat[5];
		$info->SiteName = $dat[4];

		return $info;
	}
	return false;
}


/**
 * Genarate a unique identify for the payment transaction
 */
function setcorp_TransactionUniqueId()
{
	return uniqid(true);
}


/**
 * get the current date on the plugin activation
 */
function setcorp_get_activation_date()
{
	return date("y-m-d");
}


function setcorp_PayGateTable($tbl)
{
	global $wpdb;
	$table_name = $wpdb->prefix . "SetCorp_data";
	$charset_collate = $wpdb->get_charset_collate();

	$date = setcorp_get_activation_date();
	$dated = new DateTime($date);
	$datef = date_add($dated, date_interval_create_from_date_string("10 days"));
	$sql = "CREATE TABLE IF NOT EXISTS $tbl (
		id int NOT NULL AUTO_INCREMENT,
		DateDebut DateTime ,
		DateFin DateTime ,
		Statut int DEFAULT 1,
		SiteName varchar(120),
		PRIMARY KEY (id)
	)$charset_collate";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


/**
 * Insert trial data to the database
 * 
 * @return Mixed
 */
function setcorp_InsertDefault($tbl, $wpdb)
{
	$dated = setcorp_get_activation_date();
	$dat = new DateTime($dated);
	date_add($dat, date_interval_create_from_date_string("10 days"));
	$wpdb->insert(
		$tbl,
		array(
			'DateDebut' => $dated,
			'DateFin' => $dat->format('y-m-d'),
			'Statut' => 1,
			'SiteName' => setcorp_GetSiteTitle(),
		)
	);
}


function setcorp_HasExpired($tbl, $id, $wpdb)
{
	$info = setcorp_GetData($tbl, $id, $wpdb);
	if ($info->Statut == 1993 && $info->SiteName == setcorp_GetSiteTitle())
		return false;
	$date1 = new DateTime($info->DateFin);
	$date2 = new DateTime(setcorp_get_activation_date());
	if ($date2 > $date1)
		return true;
	$left = $date1->diff($date2);
	//days<=0 =>expired
	//days>0 =>valid
	return !$left->days > 0;
}

function setcorp_HasExpired_Code(SETCORP_ActivationInfo $info )
{
	if ($info->Statut == 1993 && $info->SiteName == setcorp_GetSiteTitle())
		return false;
	$date1 = new DateTime($info->DateFin);
	$date2 = new DateTime(setcorp_get_activation_date());
	if ($date2 > $date1)
		return true;
	$left = $date1->diff($date2);
	//days<=0 =>expired
	//days>0 =>valid
	return !$left->days > 0;
}


function setcorp_Insert($tbl, SETCORP_ActivationInfo $info, $wpdb)
{
	$wpdb->insert(
		$tbl,
		array(
			'DateDebut' => $info->DateDebut,
			'DateFin' => $info->DateFin,
			'Statut' => $info->Statut,
			'SiteName' => $info->SiteName,
		)
	);
}


function setcorp_Updates($tbl, SETCORP_ActivationInfo $info)
{
	$sql = "UPDATE $tbl SET DateDebut=$info->DateDebut, DateFin=$info->DateFin, Statut=$info->Statut WHERE id=$info->Id";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


function setcorp_Update($tbl, SETCORP_ActivationInfo $info, $wpdb)
{
	$dd = new DateTime($info->DateDebut);
	$d = $dd->format('y-m-d');

	$ff = new DateTime($info->DateFin);
	$f = $ff->format('y-m-d');

	$sql = "UPDATE $tbl SET DateDebut='$d', DateFin='$f', Statut=$info->Statut ,SiteName='$info->SiteName' WHERE id=$info->Id";
	//$rslt=$wpdb->get_results($sql);
	$rslt = $wpdb->query($sql);
	return $rslt;
}


function setcorp_GetData($tbl, $id, $wpdb)
{
	$rslt = $wpdb->get_results("SELECT * FROM $tbl WHERE id=$id");
	if ($rslt[0] == null)
		return null;
	$info = new SETCORP_ActivationInfo();
	$array = get_object_vars($rslt[0]);
	$info->Id = $array['id'];
	$info->DateDebut = $array['DateDebut'];
	$info->DateFin = $array['DateFin'];
	$info->Statut = $array['Statut'];
	$info->SiteName = $array['SiteName'];

	return $info;
}


function setcorp_Currency_Notice(){
    wc_print_notice(__('aaaaaaaaa ddddddddddddddd','woocommerce'),'success');
}


class SETCORP_ActivationInfo
{
	public $Id;
	public $DateDebut;
	public $DateFin;
	public $Statut;
	public $SiteName;
}
