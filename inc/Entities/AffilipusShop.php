<?php

namespace imbaa\Affilipus\Entities;
use \DateTime;
use \Exception;


class AffilipusShop {



    private $partnerNetwork;
    private $partnerIdentifier;
    private $networkName = null;
    private $shopName = null;
    private $defaultShopName = null;
    private $lastUpdate = null;
    private $logoURL = null;


    public function __construct($partnerNetwork = null,$partnerIdentifier = null){


        if($partnerNetwork != null && $partnerIdentifier != null){

            $this -> loadShop($partnerNetwork,$partnerIdentifier);

        }


    }

   public function getPartnerNetwork(){


        return $this -> partnerNetwork;

    }

   private function setPartnerNetwork($network){

        $this -> partnerNetwork = $network;

        return $this;

    }

   public function getPartnerIdentifier(){


        return $this -> partnerIdentifier;

    }

   private function setPartnerIdentifier($identifier){

        $this -> partnerIdentifier = $identifier;

        return $this;

    }

   public function getShopName(){

       return $this->shopName;

   }

   public function setShopName ($name){

       $this -> shopName = $name;

       return $this;

   }

    public function getDefaultShopName(){

        return $this->defaultShopName;

    }

    private function setDefaultShopName ($name){

        $this -> defaultShopName = $name;

        return $this;

    }

    public function getNetworkName(){

        return $this->networkName;

    }

    public function setNetworkName ($name){

        $this -> networkName = $name;

        return $this;

    }


   public function setLastUpdate($lastUpdate){

       $this -> lastUpdate = $lastUpdate;

       return $this;

   }

   public function getLastTupdate(){


       return $this -> lastUpdate();

   }


   public function getLogoURL(){


       return $this -> logoURL;

   }

   private function setLogoURL($logoURL){


       $this -> logoURL = $logoURL;

       return $this;

   }

   private function loadShop($partnerNetwork, $partnerIdentifier){



        $this   -> setPartnerIdentifier($partnerIdentifier)
                -> setPartnerNetwork($partnerNetwork);

            switch($partnerNetwork){

                case 'amazon':


                    $this -> setShopName('Amazon');
                    $this -> setDefaultShopName('Amazon');
                    $this -> setLogoURL(IMBAF_IMAGES.'/affiliates/amazon_'.$partnerIdentifier.'.png');

                    break;


                case 'affilinet':


                    $info = get_option('_imbaf_affilinet_shop_info_'.$partnerIdentifier,false);

                    $this -> setLogoURL($info->Logo->URL);
                    $this -> setShopName($info->ShopTitle);
                    $this -> setDefaultShopName($info->ShopTitle);
                    $this -> setNetworkName('Affilinet');
                    $this -> setLastUpdate(new DateTime($info->LastUpdate));



                    break;


                case 'zanox':


                    $info = get_option('imbaf_zanox_shop_'.$partnerIdentifier,false);
                    $this -> setLogoURL($info['logo']);
                    $this -> setShopName($info['name']);
                    $this -> setDefaultShopName($info['name']);
                    $this -> setNetworkName('Zanox');





                    break;

            }

       if(get_option($partnerNetwork.'_'.$partnerIdentifier.'_custom_name',false)){

           $this -> setShopName(get_option($partnerNetwork.'_'.$partnerIdentifier.'_custom_name'));

       }


    }

    public function persist(){


       if($this -> getShopname() != $this -> getDefaultShopName()){

        update_option($this->partnerNetwork.'_'.$this->partnerIdentifier.'_custom_name',$this->getShopName(),false);

       }



    }

}