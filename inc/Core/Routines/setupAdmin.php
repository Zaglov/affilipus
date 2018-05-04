<?php

namespace imbaa\Affilipus\Core\Routines;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\API as API;

class setupAdmin {


	function __construct(){

	    new \imbaa\Affilipus\Admin\PostType\ProductPage();
	    new \imbaa\Affilipus\Admin\OverviewPages\ProductOverview();
	    new \imbaa\Affilipus\Admin\Menus\AdminMenus();
	    new \imbaa\Affilipus\Admin\Config\AffiliatePartner();

        new \imbaa\Affilipus\Admin\Utilities\affilipusAttachment();

		new \imbaa\Affilipus\Admin\Widgets\AffilipusDashboard();

        new \imbaa\Affilipus\Admin\Taxonomy\imbafproperties();
        new \imbaa\Affilipus\Admin\Utilities\Utilities();
        new \imbaa\Affilipus\Admin\Utilities\ShortcodeGenerator();
        new \imbaa\Affilipus\Admin\Utilities\PluginMeta();


        new \imbaa\Affilipus\Admin\imbafAdminPages();


        new \imbaa\Affilipus\Admin\AdminPages\AffilipusShops();

        require IMBAF_PLUGIN_PATH . 'update-checker/plugin-update-checker.php';

        \PucFactory::buildUpdateChecker(
            'https://update.affilipus.com/?action=get_metadata&slug=affilipus', //Metadata URL.
            IMBAF_PLUGIN_PATH.'affilipus.php', //Full path to the main plugin file.
            'affilipus' //Plugin slug. Usually it's the same as the name of the directory.
        );

    }

}