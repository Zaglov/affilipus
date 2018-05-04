<?php

namespace imbaa\Affilipus\Core\Affiliates\Affilinet;

/**
 * Logon Class that returns an authentication token
 */
class Logon
{
    // Logon Webservice endpoint
    private $wsdl = "http://product-api.affili.net/Authentication/Logon.svc?wsdl";

    // Credentials
    private $username;
    private $password;

    // Soap Client instance
    private $soapClient;

    /**
     * Class constructor. Expects username and password
     *
     * @param int $username your publisher Id
     * @param string $password your publisher web services password
     */
    public function __construct() {

        $this->username = get_option('imbaf_affilinet_publisher_id'); // the publisher ID
        $this->password = get_option('imbaf_affilinet_product_webservice_password');  // the product web services password

        $this->soapClient = new \SoapClient($this->wsdl);
    }

    /**
     * Get authentication token
     *
     * @return string
     */
    public function getToken() {

        if(!get_transient('imbaf_affilinet_token') or $this->tokenHasExpired()) {

            set_transient('imbaf_affilinet_token',$this->createToken(),20*60);
            set_transient('imbaf_affilinet_expiration_date',$this->getTokenExpirationDate(),20*60);

        }

        // Return token

        return get_transient('imbaf_affilinet_token');
    }

    /**
     * Checks if token is expired
     *
     * @return boolean
     */
    private function tokenHasExpired() {
        // If expiration date is not available, return true
        if (!get_transient('imbaf_affilinet_expiration_date')) {
            return true;
        }

        // Check if the token has already expired
        return date(DATE_ATOM) > get_transient('imbaf_affilinet_expiration_date');
    }

    /**
     * Create a new authentication token
     *
     * @return string
     */
    private function createToken() {
        // Send a request to the Affilinet Product Logon Service to get an authentication token

        $token = false;

        try {

            $token = $this->soapClient->Logon(array(
                'Username' => $this->username,
                'Password' => $this->password,
                'WebServiceType' => 'Product'
            ));

        } catch (\SoapFault $e){

            echo $e->getMessage()."<br>";

        }

        return $token;

    }

    /**
     * Get token expiration date
     *
     * @return string
     */
    private function getTokenExpirationDate() {
        // Send a request to the Affilinet Logon Service to get the token expiration date

        if(get_transient('imbaf_affilinet_token')){

            return $this->soapClient->GetIdentifierExpiration(get_transient('imbaf_affilinet_token'));

        } else {

            return false;

        }

    }
}