<?php

namespace imbaa\Affilipus\Core\Affiliates;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Entities;

class affiliateProduct{

	public $product_ean = null;
	public $product_name = null;
	public $product_affiliate = null;
	public $product_affiliate_identifier = null;
	public $product_description = null;
	public $product_manufacturer = null;
	public $product_type = null;

	public $product_prices = array(

		'list_price' => null,
		'offering_price' => null,
		'lowest_new_price' => null,
		'lowest_used_price' => null

	);
	public $product_features = null;
	public $product_pictures = null;
	public $product_affiliate_links = null;
	public $product_identification = null;
	public $product_dimensions = null;
	public $product_siblings = null;
	public $product_related = false;
	public $product_parent = null;

	public $product_import_values = array(

		'name' => 1,
		'description' => 1,
		'features' => 1,
		'package_sizes' => 1,
		'product_sizes' => 1,
		'affiliate_links' => 1,
		'comment_links' => 1,
		'pictures' => 0,
		'cover_picture' => 1,
		'pictures_change_name' => 1

	);

	public $selected_brand = null;
	public $selected_type = null;
	public $selected_price = 'offering_price';

	public $product_publish = false;

	public $product_raw = null;
	public $product_already_imported = false;
	public $product_processed = false;

	public $product_star_rating = null;
	public $product_shipping_detail = array();

	public $reviews_iframe_link = null;

    public $ASIN = false;
    public $product_id = false;
    public $shop_id = false;

    public $product_image_name_pattern = 'afp-product';

	public function __construct(){


	}

	public function getProductData() {

		return $this;

	}

	public function sanitizeProduct(){

		foreach($this -> product_prices as $key => $price){

			if($price == null){

				unset($this->product_prices[$key]);

			}

		}

        $this -> product_prices = array_values($this->product_prices);

        if(count($this->product_pictures) != 0){
			foreach($this->product_pictures as &$pictureset){


				foreach($pictureset as &$picture){
					$picture = urldecode($picture);
				}


			}
		}


		$this->product_related = false;


        if($this->product_manufacturer == null) {

            $this -> product_manufacturer = 'Unbekannte Marke';

        }

		return $this;

	}


	public function preisvergleich($product){


	    $cheapest_product = $product;

	    foreach($product['children'] as $child){



            if($child['_imbaf_display_price']['price'] < $cheapest_product['_imbaf_display_price']['price']){

                $cheapest_product = $child;

            }


        }


        foreach($product['children'] as $key => &$child){


            if($child['id'] == $cheapest_product['id']){

                $child['_imbaf_display_price'] = $product['_imbaf_display_price'];
                $child['_imbaf_affiliate_links'] = $product['_imbaf_affiliate_links'];
                $child['_imbaf_affiliate'] = $product['_imbaf_affiliate'];

                if(!isset($product['_imbaf_partner_logo_url'])){

                    $child['_imbaf_partner_logo_url'] = null;

                } else {

                    $child['_imbaf_partner_logo_url'] = $product['_imbaf_partner_logo_url'];

                }


                if(!isset($product['_imbaf_affiliate_identifier'])){

                    $child['_imbaf_affiliate_identifier'] = null;

                } else {

                    $child['_imbaf_affiliate_identifier'] = $product['_imbaf_affiliate_identifier'];

                }

                $child['_imbaf_product_shipping_detail'] = $product['_imbaf_product_shipping_detail'];
                $child['_imbaf_last_price_update'] = $product['_imbaf_last_price_update'];

                if(!isset($product['_imbaf_affiliate_display_name'])){

                    $child['_imbaf_affiliate_display_name'] = null;

                } else {

                    $child['_imbaf_affiliate_display_name'] = $product['_imbaf_affiliate_display_name'];

                }



            }


        }


	    $product['_imbaf_display_price'] = $cheapest_product['_imbaf_display_price'];
	    $product['_imbaf_affiliate_links'] = $cheapest_product['_imbaf_affiliate_links'];


	    if(!isset($product['_imbaf_affiliate_identifier'])){

            $product['_imbaf_affiliate_identifier'] = null;

        } else {

            $product['_imbaf_affiliate_identifier'] = $cheapest_product['_imbaf_affiliate_identifier'];

        }




	    $product['_imbaf_affiliate'] = $cheapest_product['_imbaf_affiliate'];


        if(!isset($product['_imbaf_affiliate_identifier'])){

            $product['_imbaf_partner_logo_url'] = null;

        } else {

            $product['_imbaf_partner_logo_url'] = $cheapest_product['_imbaf_partner_logo_url'];

        }




	    $product['_imbaf_product_shipping_detail'] = $cheapest_product['_imbaf_product_shipping_detail'];
	    $product['_imbaf_last_price_update'] = $cheapest_product['_imbaf_last_price_update'];


        if(!isset($product['_imbaf_affiliate_identifier'])){

            $product['_imbaf_affiliate_display_name'] = null;

        } else {

            $product['_imbaf_affiliate_display_name'] = $cheapest_product['_imbaf_affiliate_display_name'];

        }




        return $product;


    }

	public function loadProductById($id){


		global $wpdb;

        $id = trim($id);


        $product = wp_cache_get( 'imbaf_product_'.$id , 'imbaf_products' );



        if(!$product) {


            $product = get_post_meta($id);

            if($product == false){

                return $product;

            }

            $product['id'] = $id;


            $product = $this -> sanitizeLoadedProduct($product);


            $children = $wpdb -> get_results('SELECT ID FROM '.$wpdb->prefix.'posts WHERE post_parent = '.$id.' AND post_type = "imbafproducts";');


            $product['children'] = array();


            foreach($children as $child){


                $chld = get_post_meta($child->ID);

                $chld['id'] = $child->ID;

                $chld = $this -> sanitizeLoadedProduct($chld);

                array_push($product['children'],$chld);

            }

            wp_cache_add( 'imbaf_product_'.$id , $product , 'imbaf_products' , 60*60 );

        } else {


        }




		return $product;


	}

	public function sanitizeLoadedProduct($product){

		$product['product_name'] = get_the_title ( $product['id'] );

		$product['_imbaf_price'] = @unserialize($product['_imbaf_price'][0] );

		if(isset( $product['_imbaf_custom_property_values'] )) {

			$product['_imbaf_custom_property_values'] = unserialize($product['_imbaf_custom_property_values'][0]);

		}

		else {

			$product['_imbaf_custom_property_values'] = null;

		}

		if(isset( $product['_imbaf_product_shipping_detail'] )) {

			$product['_imbaf_product_shipping_detail'] = (array) unserialize($product['_imbaf_product_shipping_detail'][0]);


			if(isset($product['_imbaf_product_shipping_detail']['AvailabilityAttributes'])){

				$product['_imbaf_product_shipping_detail']['AvailabilityAttributes'] = (array) $product['_imbaf_product_shipping_detail']['AvailabilityAttributes'];

			} else {

				$product['_imbaf_product_shipping_detail']['AvailabilityAttributes'] = null;

			}
            

		} else {

			$product['_imbaf_product_shipping_detail'] = null;

		}

		$prices = [];


		if($product['_imbaf_price'] && count($product['_imbaf_price']) > 0){

			foreach($product['_imbaf_price'] as $price){


				if($price['price'] != ''){

					//$price['price'] = @number_format($price['price'],2,',','.');

				}

				$prices[$price['name']] = $price;

			}

		}


		$prices = CORE\Utilities\Utilities::translate_prices($prices);

		$product['_imbaf_price'] = $prices;



		if(isset($product['_imbaf_selected_price'])){

			if($product['_imbaf_selected_price'][0] == null && $product['_imbaf_price'] != null){

				$product['_imbaf_selected_price'][0] = 'offering_price';

			}

			if(isset( $product['_imbaf_price'][$product['_imbaf_selected_price'][0]])){

				$product['_imbaf_display_price'] = $product['_imbaf_price'][$product['_imbaf_selected_price'][0]];

			}

			else {

				if(isset($product['_imbaf_price']['list_price'])) {
					$product['_imbaf_selected_price'][0] = 'list_price';
					$product['_imbaf_display_price'] = $product['_imbaf_price']['list_price'];
				} else {

                    $product['_imbaf_selected_price'][0] = array_keys($product['_imbaf_price'])[0];
                    $product['_imbaf_display_price'] = $product['_imbaf_price'][$product['_imbaf_selected_price'][0]];

                }

			}

		}




		if(
            get_option('imbaf_enable_cross_price') &&
			isset($product['_imbaf_price']['list_price']['price']) &&
			$product['_imbaf_price']['list_price']['price'] > $product['_imbaf_display_price']['price']

		){

			$product['_imbaf_display_price']['cross_price'] = number_format($product['_imbaf_price']['list_price']['price'],2,',','.');
			$product['_imbaf_display_price']['savings'] = number_format($product['_imbaf_price']['list_price']['price']-$product['_imbaf_display_price']['price'],2);
			$product['_imbaf_display_price']['savings_percent'] = round($product['_imbaf_display_price']['savings']/$product['_imbaf_price']['list_price']['price']*100);

		}

		if(isset($product['_imbaf_display_price']) && $product['_imbaf_display_price'] != null){

		    $product['_imbaf_display_price']['price'] = @number_format($product['_imbaf_display_price']['price'],2,',','.');

        } else {

            $product['_imbaf_display_price']['price'] = 'nicht verfügbar';

        }

		if(isset($product['_imbaf_affiliate_links']) && count($product['_imbaf_affiliate_links']) > 0){

			foreach($product['_imbaf_affiliate_links'] as &$links){



				$links = unserialize($links);
				$temp = array();


				if($links){

					foreach($links as &$link){

						$temp[$link['type']] = $link;
					}

				}




				$links = $temp;


			}

		}


		if(isset($product['_imbaf_features']) && count($product['_imbaf_features']) > 0){

			foreach($product['_imbaf_features'] as &$features){

				$features = @unserialize($features);

			}

			$product['_imbaf_features'] = $product['_imbaf_features'][0];

		}

		$product['parent_id'] = wp_get_post_parent_id( $product['id'] );

		if($product['parent_id'] == 0){ $product['thumbnail_post_id'] = $product['id']; }

		else { $product['thumbnail_post_id'] = $product['parent_id']; }

		if( isset($product['_imbaf_cdn_pictures']) && count($product['_imbaf_cdn_pictures']) > 0 ){

			foreach($product['_imbaf_cdn_pictures'] as &$pictures){

				$pictures = unserialize($pictures);

			}



			$product['_imbaf_cdn_pictures'] = $product['_imbaf_cdn_pictures'][0];


			# Amazon Wokaround, sollte wo anders hingepackt werden später. Bitte auch beachten, dass das bei der Metabox cdn_pictures zu Tragen kommt

			if(count($product['_imbaf_cdn_pictures']) > 0 && is_array($product['_imbaf_cdn_pictures'])){


				$pictures = $product['_imbaf_cdn_pictures'];


				foreach($pictures as &$pictureset){

					# https://images-na.ssl-images-amazon.com/images/I/61XcdKXBAXL.jpg

					# http://ecx.images-amazon.com/images/I/61XcdKXBAXL.jpg

					foreach($pictureset as &$picture){

						$picture = str_replace('http://ecx.images-amazon.com/images/','https://images-na.ssl-images-amazon.com/images/',$picture);


					}

				}

				$product['_imbaf_cdn_pictures'] = $pictures;

			}

			

            //$product['product_picture'] = $product['_imbaf_cdn_pictures'][0]['large'];

			if($product['_imbaf_cdn_pictures'] && count($product['_imbaf_cdn_pictures']) > 0){

			    foreach($product['_imbaf_cdn_pictures'] as $set){


			        if(isset($set['large'])) {


                        $product['product_picture'] = $set['large'];


                    }


                }

            } else {

                $product['product_picture'] = false;
            }


		}

		$thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id($product['id']),'medium', true)[0];

		// Beitragsbild vorhanden

		if(pathinfo($thumb_url)['basename'] != 'default.png') {

			// Beitragsbild verwenden, ansonsten CDN-Bild lassen

			if(get_option('imbaf_prefer_cdn_pictures') != 1 || (isset($product['product_picture']) && $product['product_picture'] == null))  {

				$product['product_picture'] = $thumb_url;

			}

			else {


				if(!isset($product['product_picture']) || $product['product_picture'] == null ||$product['product_picture'] == false){


					$product['product_picture'] = $thumb_url;

				}

			}

			if(!isset($product['product_picture'])){

				$product['product_picture'] = null;

			}

		}

		if(isset($product['_imbaf_external_review'])){

			$product['_imbaf_external_review'] = unserialize($product['_imbaf_external_review'][0]);

		}

		if(!isset($product['_imbaf_external_review'])){

			$product['_imbaf_external_review'] = array(

				'link' => null,
				'rel' => 'follow'

			);

		}

		if(!isset($product['_imbaf_external_review']['rel'])){


			$product['_imbaf_external_review']['rel'] = 'follow';


		}

		if(get_post_status($product['id']) == 'hidden'){
			$product['permalink'] = null;

		}

		else {

			if(isset($product['_imbaf_external_review']['link']) && $product['_imbaf_external_review']['link'] != null){

				$product['permalink'] = $product['_imbaf_external_review']['link'];


			} else {

				$product['permalink'] = get_permalink($product['id']);

			}



		}

		if(!isset($product['permalink_rel']) || $product['permalink_rel'] == null){
			$product['permalink_rel'] = 'follow';
		}

		$single_values = array(

			'_imbaf_affiliate',
			'_imbaf_affiliate_identifier',
			'_imbaf_selected_price',
			'_imbaf_asin',
			'_imbaf_description',
			'_imbaf_package_dimensions_width',
			'_imbaf_package_dimensions_height',
			'_imbaf_package_dimensions_length',
			'_imbaf_package_dimensions_unit',
			'_imbaf_package_weight',
			'_imbaf_package_weight_unit',
			'_imbaf_item_dimensions_width',
			'_imbaf_item_dimensions_height',
			'_imbaf_item_dimensions_length',
			'_imbaf_item_dimensions_unit',
			'_imbaf_item_weight',
			'_imbaf_item_weight_unit',
			'_imbaf_last_price_update',
			'_imbaf_review_star_rating',
			'_imbaf_review_text',
			'_thumbnail_id',
			'_edit_lock',
			'_edit_last',
			'_imbaf_affiliate_links',
			'_imbaf_affiliate_name',
			'_imbaf_review_count',
			'_imbaf_reviews_iframe_link'


		);

		foreach($single_values as $single){

			if(array_key_exists($single,$product) && is_array($product[$single])){
				$product[$single] = $product[$single][0];
			}

		}

		if(isset($product['_imbaf_affiliate_links'])){

			foreach($product['_imbaf_affiliate_links'] as &$link){


				if(isset($link['url']) && is_array($link['url'])){

					$link['url'] = $link['url']['url'];

				}

				if($product['_imbaf_affiliate'] == 'amazon'){



					switch($product['_imbaf_affiliate_identifier']){


						case 'de':

							$tag = get_option('AWS_ASSOCIATE_TAG_DE');

							break;

						case 'com':

							$tag = get_option('AWS_ASSOCIATE_TAG_COM');

							break;

						case 'co.uk':

							$tag = get_option('AWS_ASSOCIATE_TAG_CO_UK');

							break;

						case 'fr':

							$tag = get_option('AWS_ASSOCIATE_TAG_FR');


							break;


						case 'es':

							$tag = get_option('AWS_ASSOCIATE_TAG_ES');

							break;


					}

					if($link['type'] == 'product_page'){


						$link['url'] = parse_url(urldecode($link['url']));
						$link['url']['scheme'] .= '://';

						unset($link['url']['query']);

						$link['url'] = implode('',$link['url']).'?tag='.$tag;

					}
					else {

						$link['url'] = str_replace('{{AFP-AMAZON-ASSOCIATE-TAG}}',$tag,$link['url']);

					}

					$product['_imbaf_reviews_iframe_link'] = str_replace('{{AFP-AMAZON-ASSOCIATE-TAG}}',$tag,urldecode($product['_imbaf_reviews_iframe_link']));
					$product['_imbaf_reviews_iframe_link'] = str_replace('http://','https://',$product['_imbaf_reviews_iframe_link']);

				}

			}

		}

		/* Ab hier für Custom Products */

		if(!isset($product['_imbaf_affiliate'])){


			$product['_imbaf_affiliate'] = 'imbaf_custom';
			add_post_meta($product['id'],'_imbaf_affiliate','imbaf_custom');

		}

		if(!isset($product['_imbaf_item_dimensions_unit'])){

			$product['_imbaf_item_dimensions_unit'] = 'cm';
			add_post_meta($product['id'],'_imbaf_item_dimensions_unit','cm',true);

		}

		if(!isset($product['_imbaf_package_dimensions_unit'])){

			$product['_imbaf_package_dimensions_unit'] = 'cm';
			add_post_meta($product['id'],'_imbaf_package_dimensions_unit','cm',true);

		}

		if(!isset($product['_imbaf_item_weight_unit'])){

			$product['_imbaf_item_weight_unit'] = 'kg';
			add_post_meta($product['id'],'_imbaf_item_weight_unit','kg',true);

		}

		if(!isset($product['_imbaf_package_weight_unit'])){

			$product['_imbaf_item_dimensions_unit'] = 'kg';
			add_post_meta($product['id'],'_imbaf_package_weight_unit','kg',true);

		}

		if(isset($product['_imbaf_features']) && $product['_imbaf_features'] != false && count($product['_imbaf_features']) > 0) {

			

			foreach($product['_imbaf_features'] as &$ftr){


				$ftr = do_shortcode($ftr);


			}


		}





		$product = $this->sanitize_by_affiliate_partner($product);



       if(array_key_exists('_imbaf_product_image_id',$product) && $product['_imbaf_product_image_id'][0] != ''){


           $picture = wp_get_attachment_image_src($product['_imbaf_product_image_id'][0],[500,null]);

           if($picture[0]){

               $product['product_picture'] = $picture[0];
           }

       }


		return $product;


	}

	public function sanitize_by_affiliate_partner($product){


	    $shop = new Entities\AffilipusShop($product['_imbaf_affiliate'],$product['_imbaf_affiliate_identifier']);






	    switch($product['_imbaf_affiliate']){

            case 'amazon':

                $product['_imbaf_partner_logo_url'] = IMBAF_PLUGIN_URL.'images/affiliates/amazon_'.$product['_imbaf_affiliate_identifier'].'.png';

                if(isset($product['_imbaf_product_shipping_detail']['IsEligibleForPrime']) && $product['_imbaf_product_shipping_detail']['IsEligibleForPrime'] && get_option('imbaf_amazon_hide_prime_logo')){

                    $product['_imbaf_product_shipping_detail']['IsEligibleForPrime'] = false;
                    
                }

                $product['_imbaf_affiliate_display_name'] = 'Amazon';

                break;


            case 'affilinet':

                $shop_info = get_option('_imbaf_'.$product['_imbaf_affiliate'].'_shop_info_'.$product['_imbaf_affiliate_identifier']);
                $product['_imbaf_partner_logo_url'] = $shop_info->Logo->URL;

                $product['_imbaf_affiliate_display_name'] = $shop_info->ShopTitle;

                break;

            
            case 'zanox':


                $shop_info = get_option('imbaf_zanox_shop_'.$product['_imbaf_affiliate_identifier']);
                $product['_imbaf_partner_logo_url'] = $shop_info['logo'];


                $product['_imbaf_affiliate_display_name'] = $shop_info['name'];

                break;

            case 'webgains':

                $product['_imbaf_partner_logo_url'] = 'https://www.webgains.de/image.html?file=program/logo/'.$product['_imbaf_shop_id'][0].'.jpg';

                $product['_imbaf_affiliate_display_name'] = 'Partnershop';

                break;


        }


        $product['_imbaf_affiliate_display_name'] = $shop -> getShopName();
        $product['_imbaf_partner_logo_url'] = $shop -> getLogoURL();

        return $product;

    }

	public function listAllProducts(){


		global $wpdb;

		$query = "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type = 'imbafproducts' AND post_status IN ('publish', 'draft') AND post_parent = 0 ORDER BY post_title ASC;";



		$posts = $wpdb -> get_results($query);

		return $posts;


	}

	public function listPublishedProducts(){

		global $wpdb;

		$query = "SELECT ID, post_title FROM {$wpdb->posts}
                        WHERE post_type = 'imbafproducts'
                        AND (post_status = 'publish');";



		$posts = $wpdb -> get_results($query);

		return $posts;


	}

	public function searchProductByName($name = null){

		global $wpdb;

		if($name != null){

			$query = "SELECT ID, post_title FROM {$wpdb->posts}
                        WHERE post_type = 'imbafproducts'
                        AND (post_status = 'publish'
                        OR post_status = 'draft'
                        OR post_status = 'hidden' AND post_parent != 0)
                        AND post_title LIKE '%{$name}%';";


			return $wpdb -> get_results($query);


		} else {

			return false;

		}

	}
}
