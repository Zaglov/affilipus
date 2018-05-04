<?php

namespace imbaa\Affilipus\Admin\Utilities;


/**
 *
 * Handles Output of Backend Templates in Affilipus
 *
 * Class adminTemplates
 * @package imbaa\Affilipus\Admin\Utilities
 *
 *
 *
 *
 */

class adminTemplates {

    var $smarty = false;

    public function __construct(){


        require_once(IMBAF_LIBRARY.'/smarty/' . 'Smarty.class.php');

        $this->smarty = new \Smarty();

        $this->compile_path = IMBAF_TEMPLATE_COMPILE_PATH;



        if(AFP_DEBUG == true){

            $this->smarty->error_reporting = true;

        } else {

            $this->smarty->error_reporting = false;

        }

        $this->smarty->setTemplateDir(IMBAF_PLUGIN_PATH.'admin_templates/');

        if(is_writable($this->compile_path)){

            $this->smarty->setCompileDir($this->compile_path);
            $this->smarty->force_compile = true;


        }

        else {

            $this -> smarty = false;

        }




    }




}