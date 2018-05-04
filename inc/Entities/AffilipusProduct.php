<?php

namespace imbaa\Affilipus\Entities;
use \DateTime;
use \Exception;

class AffilipusProduct {


    private $productId = -1;
    private $title = 'Affilipus Produkt';
    private $description = null;
    private $lastUpdate = null;
    private $asin = null;
    private $isPrime = false;
    private $eans = null;
    private $brand = null;
    private $productType = null;
    private $pictures = null;
    private $affiliate = null;
    private $affiliateIdentifier = null;
    private $displayPrice = null;
    private $displayPriceCurrency = null;
    private $availablePrices = null;
    private $selectedPriceName = null;
    private $children = null;



    public function __construct($productId = 0){


        if($productId != 0){

            $this -> findOneById($productId);

        }


    }

    public function getProductId(){


        return $this -> productId;

    }

    public function getChildren(){


        return $this -> children;

    }

    public function setChildren($children){


        $this -> children = $children;

        return $this;

    }

    private function setProductId($productId){

        $this -> productId = $productId;


        return $this;

    }

    public function getTitle(){

        return $this -> title;

    }

    public function setTitle($title){


        $this -> title = $title;

        return $this;

    }

    public function getDiplayPrice(){


        return $this -> displayPrice;

    }

    private function setDisplayPrice($displayPrice){


        $this -> displayPrice = $displayPrice;

        return $this;

    }

    public function getDiplayPriceCurrency(){


        return $this -> displayPriceCurrency;

    }

    private function setDisplayPriceCurrency($displayPriceCurrency){


        $this -> displayPriceCurrency = $displayPriceCurrency;

        return $this;

    }

    public function getAvailablePrices(){

        return $this -> availablePrices;

    }

    public function getSelectedPriceName(){

        return $this -> selectedPriceName;

    }

    public function setSelectedPriceName($selectedPriceName){


        $this -> selectedPriceName = $selectedPriceName;

        return $this;

    }

    public function setAvailablePrices($availablePrices){


        $this -> availablePrices = $availablePrices;

        return $this;

    }

    public function getDescription(){

        $this -> getDescription();

    }

    public function setDescription($description){


        $this -> description = $description;

        return $this;


    }

    public function getLastUpdate(){

        return $this -> lastUpdate;

    }

    public function setLastUpdate(DateTime $lastUpdate){

        return $this -> lastUpdate;

    }

    public function getAsin(){

        return $this -> asin;

    }

    public function setAsin($asin){

        $this -> asin = $asin;

        return $this;

    }

    public function getIsPrime(){

        return $this -> isPrime();

    }

    public function setIsPrime($isPrime){

        $this -> isPrime = $isPrime;

        return $this;

    }

    public function getEans(){

        return $this -> eans;

    }

    public function setEans($eans){

        $this -> eans = $eans;

        return $this;

    }

    public function getProductType(){

        return $this -> productType;

    }

    public function setProductType($type){


        $this -> productType = $type;


        return $this;


    }

    public function getBrand(){

        return $this -> brand;

    }

    public function setBrand($brand){

        $this -> brand = $brand;

        return $this;

    }

    public function getPictures(){

        return $this -> pictures;

    }

    public function setPictures($pictures){


        $this -> pictures = $pictures;


        return $pictures;


    }

    public function getAffiliate(){

        return $this -> affiliate;

    }

    public function setAffiliate($affiliate){

        $this -> affiliate = $affiliate;


        return $this;

    }

    public function getAffiliateIdentifier(){

        return $this -> affiliateIdentifier;

    }

    public function setAffiliateIdentifier($affiliateIdentifier){

        $this -> affiliateIdentifier = $affiliateIdentifier;

        return $this;

    }

    private function autoSetPrice(){

        foreach($this -> getAvailablePrices() as $price){


            if($price['name'] == $this -> selectedPriceName){

                $this -> setDisplayPrice($price['price']);
                $this -> setDisplayPriceCurrency($price['currency']);

                break;

            }

        }

    }

    public function findOneById($productId){


        global $wpdb;

        try {

            if(!is_integer($productId) || $productId == 0){

                throw new Exception(__METHOD__.' expects <strong>$productId</strong> to be integer, <strong>'.gettype($productId).'</strong> given');

            }


            $product = get_post($productId);

            if($product == null){

                throw new Exception(__METHOD__.' found no Product with ID <strong>'.$productId.'</strong>.');

            }

            $this   -> setProductId($product->ID)
                    -> setTitle($product->post_title)
                    -> setDescription($product->post_content)
                    -> setAffiliate(get_post_meta($productId,'_imbaf_affiliate',true))
                    -> setAffiliateIdentifier(get_post_meta($productId,'_imbaf_affiliate_identifier',true))
                    -> setEans(get_post_meta($productId,'_imbaf_ean'))
                    -> setASIN(get_post_meta($productId,'_imbaf_asin',true))
                    -> setAvailablePrices(get_post_meta($productId,'_imbaf_price',true))
                    -> setSelectedPriceName(get_post_meta($productId,'_imbaf_selected_price',true))
            ;


            $children = $wpdb -> get_results(

                "SELECT ID FROM {$wpdb->posts} 
                        WHERE post_type = 'imbafproducts' 
                        AND post_parent = '{$productId}';",OBJECT_K);


            $this -> setChildren($children);

            $shipping = get_post_meta(7,'_imbaf_product_shipping_detail',true);




            if($shipping && array_key_exists('IsEligibleForPrime',$shipping) && $shipping['IsEligibleForPrime'] == 1){

                $this -> setIsPrime(true);

            }

            $this -> autoSetPrice();

        }

        catch(Exception $e){

            echo '<strong>Affilipus Exception:</strong> '.$e->getMessage();

        }


        return $this;


    }

    public function persist(){



    }

}