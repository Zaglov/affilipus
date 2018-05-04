<?php
namespace imbaa\Affilipus\Admin\Utilities;

class PluginMeta{


    function __construct(){

        add_filter( 'plugin_row_meta', [$this,'plugin_row_meta'], PHP_INT_MAX, 2 );

    }

    /**
     * Adds Information about license status to plugin page
     *
     * @param $links
     * @param $file
     * @return array
     *
     */

    function plugin_row_meta( $links, $file ){


        if ( strpos( $file, 'affilipus.php' ) !== false ) {


            $api = new \imbaa\Affilipus\Core\API\affilipusAPI();

            $license = $api -> checkLicense(['cached' => true]);

            $new_links = array();

            if(isset($license['item_name']) && $license['item_name'] == 'Affilipussy'){


                $expires = (strtotime($license['expires'])-time())/60/60;
                $expires_unit = 'Stunden';

                if($expires > 24){

                    $expires = round($expires/24);
                    $expires_unit = 'Tage';

                }

                if($expires < 0){$expires = 0;}

                $new_links['buy'] = '<a href="https://affilipus.com/afp/25/" target="_blank"><strong>Jetzt kaufen</strong></a>';
                $new_links['expires'] = 'Noch '.round($expires,2).' '.$expires_unit;

            }

            if(isset($license['beta']) && $license['beta'] == 1){


                $links[0] .= ' (<strong>Early Access</strong>)';

            }

            $links = array_merge( $links, $new_links );
        }

        return $links;


    }


}