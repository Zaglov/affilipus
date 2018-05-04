<?php

namespace imbaa\Affilipus\Core\API;
use imbaa\Affilipus\Core as CORE;



CLASS affilipusAPI {


    public function __construct(){




    }

    public function getDebugInfo(){

        global $wp_version;

        $info =  array(
            'wp_version' => $wp_version,
            'php_version' => phpversion(),
            'curl' => function_exists('curl_version'),
            'file_get_contents' => function_exists('file_get_contents')
        );

        return $info;

    }

    public function checkLicense($args = array())
    {


        $current_license = get_option('imbaf_license_status');
        $fails = get_option('imbaf_license_fails');
        $lastUpdate = get_option('imbaf_last_license_call');

        $response = [

            "success" => true,
            "license" => "active",
            "item_name" => "Affilipus",
            "expires" => strtotime("+100 years"),
            "customer_name" => "Affilipus Customer",
            "customer_email" => "-",
            "license_limit" => 999999,
            "siteCount" => null,
            "activations_left" => null,
            "beta" => false

        ];


        return $response;

    }

    public function allowAction($echo = false){


        return true;

    }

    function license_nag_front() {


    }

    public function deactivateLicense(){

        $license = get_option('imbaf_license_key');




        return $license;

    }

    public function activateLicense(){





    }

    public function license_call($url){

        $response = array();

        if(function_exists('curl_init')){

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_TIMEOUT,1000);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

            $result = curl_exec($ch);


            $response = json_decode($result,TRUE);

            if($response == NULL){ return $response;}

        } else {

            die('CURL Extension needs to be installed on the Server.');

        }


        return $response;



    }

    public function call($module,$endpoint,$data = null){

        if(!isset($module) || !isset($endpoint)){

            die('No Endpoint specified.');

        }

        $url = IMBAF_API_SERVER.'/jsonapi/'.$module.'/'.$endpoint.'';

        $fields = array(
            'SERVER_NAME' => urlencode($_SERVER['SERVER_NAME']),
            'HTTP_HOST' => urlencode($_SERVER['SERVER_NAME']),
            'REMOTE_ADDR' => urlencode($_SERVER['REMOTE_ADDR']),
            'SITE_URL' => urlencode(get_option('siteurl')),
            'LICENSE_KEY' => urlencode(get_option('imbaf_license_key')),
            'AFFILIPUS_VERSION' => IMBAF_VERSION,
            'DEBUG_INFO' => urlencode(serialize($this -> getDebugInfo())),
            'payload' => urlencode(serialize($data))
        );

        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        $info = curl_getinfo($ch);

        $return = array('raw'=> $result,'data' => json_decode($result,true),'info' => $info);

        curl_close($ch);

        return $return;

    }

}