<?php

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

if(!defined('AFP_DEBUG')){ define('AFP_DEBUG',false);}
define('IMBAF_TEXT_DOMAIN','imb_affiliate');
define('IMBAF_PLUGIN_URL',plugin_dir_url(__FILE__));
define('IMBAF_PLUGIN_RELATIVE_URL',str_replace(get_option('siteurl'),'',plugin_dir_url(__FILE__)));
define('IMBAF_PLUGIN_PATH',plugin_dir_path(__FILE__));



define('IMBAF_CONTENT_FOLDER',WP_CONTENT_DIR.'/affilipus');
define('IMBAF_CONTENT_URL',content_url().'/affilipus');
define('IMBAF_CONTENT_RELATIVE_URL',str_replace(get_option('siteurl'),'',content_url().'/affilipus'));

define('IMBAF_TEMPLATES',IMBAF_PLUGIN_PATH.'templates');
define('IMBAF_CUSTOM_TEMPLATES',WP_CONTENT_DIR.'/affilipus/templates_custom');

define('IMBAF_TEMPLATE_COMPILE_PATH',WP_CONTENT_DIR.'/affilipus/templates_c');
define('IMBAF_TEMPLATE_CACHE_PATH',WP_CONTENT_DIR.'/affilipus/templates_cache');

define('IMBAF_IMAGES',IMBAF_PLUGIN_URL.'images');
define('IMBAF_IMAGES_PATH',IMBAF_PLUGIN_PATH.'images');

define('IMBAF_LIBRARY',IMBAF_PLUGIN_PATH.'library');
define('IMBAF_ROUTINES',IMBAF_PLUGIN_PATH.'library/routines');
define('IMBAF_AFFILIATES',IMBAF_PLUGIN_PATH.'library/affiliates');

define('IMBAF_API_SERVER','https://api.affilipus.com');
define('IMBAF_SHOP_SERVER','https://affilipus.com');

define("IMBAF_WSDL_LOGON", "http://product-api.affili.net/Authentication/Logon.svc?wsdl");
define("IMBAF_WSDL_PRODUCT", "https://product-api.affili.net/V3/WSDLFactory/Product_ProductData.wsdl");