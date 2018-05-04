<?php
namespace imbaa\Affilipus\Admin\Utilities;

class Utilities{


    function __construct(){

        add_action( 'imbaf_flush_cache', array( $this, 'flushTemplates' ) );

    }


    function flushTemplates(){

        require_once(IMBAF_LIBRARY.'/smarty/' . 'Smarty.class.php');
        $smarty = new \Smarty();
        $smarty->setCacheDir(IMBAF_TEMPLATE_CACHE_PATH);
        $smarty->clearAllCache();
        $smarty->clearCompiledTemplate();

    }



}