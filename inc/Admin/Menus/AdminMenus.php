<?php
namespace imbaa\Affilipus\Admin\Menus;

class AdminMenus{


    function __construct(){

        add_action('admin_menu', array($this,'register_admin_menus'));
        add_action('admin_menu', array($this,'add_import_product_to_admin_menu'));

    }


    function register_admin_menus() {

        add_menu_page('Affilipus Partner', 'Affilipus Partner', 'administrator', 'imbaf_partner', [\imbaa\Affilipus\Admin\AdminPages\AffilipusPartner::class,'page'],'dashicons-groups');

    }

    /**
     *
     * Adds Import Product Menu Point to Products Menu
     *
     */

    function add_import_product_to_admin_menu() {
        global $submenu;



        $submenu['edit.php?post_type=imbafproducts'][11] = array(

            __('Produkt importieren','imb_affiliate'),
            'edit_pages',
            'admin.php?page=imbaf_partner',

        );


        ksort($submenu['edit.php?post_type=imbafproducts']);

    }



}