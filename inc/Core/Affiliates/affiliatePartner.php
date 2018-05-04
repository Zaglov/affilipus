<?php

namespace imbaa\Affilipus\Core\Affiliates;

class affiliatePartner {


	public $response = null;

	public function __construct(){

		add_action( 'wp_ajax_imbaf_search_product', array($this,'searchProduct') );
		add_action( 'wp_ajax_imbaf_import_product', array($this,'importProduct') );
		add_action( 'wp_ajax_imbaf_remove_product', array($this,'removeProduct') );
		add_action( 'wp_ajax_imbaf_import_cdn_picture', array($this,'importCDNPicture') );

		add_action('admin_enqueue_scripts',array($this,'register_scripts'));

		add_action( 'imbaf_garbage_collection', array( $this, 'garbageCollection' ) );

	}

	public function register_scripts(){

		wp_register_script( 'knockout', IMBAF_PLUGIN_URL  . 'js/libs/knockout.js', null, '3.4.0', false);
		wp_register_script( 'knockout-viewmodel', IMBAF_PLUGIN_URL  . 'js/libs/knockout.viewmodel.js',array('knockout'), '3.4.0', false);
		wp_register_script( 'imbaf_search', IMBAF_PLUGIN_URL  . 'js/search.js', array('jquery','knockout','knockout-viewmodel'), '1.0', false);
		wp_register_style( 'imbaf-admin',IMBAF_PLUGIN_URL.'css/admin/searchpage.css',false,IMBAF_VERSION);

	}

	public function setup(){





	}

	// Hole Einstellungen des Partners

	public function getSettings(){



	}

	// Suche Produkte über die API

	public function searchProduct($partner=null,$format='json',$return=false){

		if($partner == null){

			$partner = $_POST['partner'];

		}

		switch($partner){


			case 'amazon':

				$partner = new Amazon\partnerAmazon();

				break;

			case 'affilinet':

				$partner = new Affilinet\Affilinet();

				break;


            case 'zanox':

                $partner = new Zanox\Zanox();

                break;


            case 'webgains':

                $partner = new Webgains\Webgains();
                break;

		}


		$this->response = @$partner -> productSearch($_POST);

		$this->response['token'] = md5(serialize($_POST));

		$this->response['taxonomies'] = array(

			'brands' => get_terms('imbafbrands',array('hide_empty'=>0)),
			'types' => get_terms('imbaftypes',array('hide_empty'=>0)),

		);


        foreach($this->response['taxonomies']['brands'] as &$tax){


            $tax->name = html_entity_decode($tax->name);

        }

        foreach($this->response['taxonomies']['types'] as &$tax){


            $tax->name = html_entity_decode($tax->name);

        }



        if(count($this->response['products']) > 0){

            foreach($this -> response['products'] as &$product){

                $product -> product_image_name_pattern = sanitize_title($product->product_name);

            }

        }


		$p = new AffiliateProduct;

		$this -> checkBrands();
		$this -> checkTypes();
		$this -> response['allProducts'] = $p -> listAllProducts();

		unset($this->response['raw']);

		set_transient($this->response['token'], $this->response, 60*60 );

		if($format == 'json'){

			header('Content-type: text/json');
			echo json_encode($this->response);
			wp_die();

		}

		if($return == true){

			return $this -> response;

		}




	}

	// Überprüfe ob es neue Marken gibt

	protected function checkBrands(){


	    if(count($this->response['products']) > 0){

            foreach($this->response['products'] as &$product){

                $term = term_exists($product->product_manufacturer, 'imbafbrands');

                if(!isset($term['term_id'])) {

                    //$product-> selected_brand = $term['term_id'];

                    $brand = array('term_id' => $product->product_manufacturer, 'name' => $product->product_manufacturer);

                    if (!in_array($brand, $this->response['taxonomies']['brands'])) {

                        array_push($this->response['taxonomies']['brands'], array('term_id' => $product->product_manufacturer, 'name' => $product->product_manufacturer));

                    }

                }

                $product -> selected_brand = $product -> product_manufacturer;


            }

        }



	}

	// Überprüft ob es neue Typen gibt

	protected function checkTypes(){

	    if(count($this -> response['products']) > 0){

		foreach($this->response['products'] as &$product){


			$term = term_exists(htmlentities($product->product_type), 'imbaftypes');



			if(!isset($term['term_id'])){

				$type = array('term_id' => $product->product_type,'name' => $product->product_type);

				if(!in_array($type,$this -> response['taxonomies']['types'])){

					array_push($this -> response['taxonomies']['types'],array('term_id' => $product->product_type,'name' => $product->product_type));

				}

			}

            
			$product-> selected_type = $product->product_type;


		}

        }



	}

	// Entferne Produkt

	public function removeProduct(){


		wp_trash_post( $_POST['id'] );
		wp_die();


	}

	public function importCDNPicture(){


		$pictures = get_post_meta( $_POST['post_id'], '_imbaf_cdn_pictures', true);

        wp_cache_delete( 'imbaf_product_'. $_POST['post_id'] ,'imbaf_products');

		$image = $pictures[$_POST['id']];


		$post = array(

			'id' => $_POST['post_id'],
			'post_title' => get_the_title($_POST['post_id']),
			'images' => array(),
			'affiliate_partner' => get_post_meta($_POST['post_id'],'_imbaf_affiliate',true)

		);

		array_push($post['images'],array('url' => $image['large'],'title'=>$post['post_title']));


		echo $this -> importPicture($post, true);

		wp_die();
	}

	/* @deprecated */

	private function shouldSleepMore($vals){

		foreach($vals as &$val){

			if($val == 'true' || $val == '1'){ $val = true;}
			elseif($val == 'false' ||$val == '0'){$val = false;}

		}

		return $vals;

	}

	// Importiere Produkt

	public function importProduct($args){

		ignore_user_abort(true);

		if(isset($args['product'])){

			$product = $args['product'];

		}

		else {

			$products = get_transient( $_POST['token'] );
			$product = $products['products'][$_POST['index']];


			if(isset($_POST['select_price'])){$product -> selected_price = $_POST['selected_price'];}
			if(isset($_POST['select_brand'])){$product -> selected_price = $_POST['selected_brand'];}
			if(isset($_POST['select_type'])){$product -> selected_price = $_POST['selected_type'];}
			if(isset($_POST['publish'])){$product -> product_publish = $_POST['publish'];}
			if(isset($_POST['product_name'])){$product -> product_name = $_POST['product_name'];}

			if(isset($_POST['product_image_name_pattern'])){$product -> image_pattern = $_POST['product_image_name_pattern'];}

			if(isset($_POST['product_parent'])){$product -> product_parent = $_POST['product_parent'];}


		}



		if(isset($_POST['product_import_values'])){
			$product -> product_import_values = $this->shouldSleepMore($_POST['product_import_values']);
		}

		// Importiere Basics

		if($product -> product_import_values['name'] == ''){

			$post['title'] = 'Neues Produkt';

		}

		if($product->product_publish == true){

		    $post_status = 'publish';

		}

		else {

		    $post_status = 'draft';

		}

		$post_type = 'imbafproducts';

		if(isset($args['type']) && $args['type'] == 'temporary'){

			$post_status = 'hidden';

			$product -> product_import_values['pictures'] = false;
			$product -> product_import_values['cover_picture'] = false;
			$product -> product_import_values['features'] = true;

		}

		$post = array(

			'id' => -1,
			'post_title' => $product -> product_name,
			'post_content' => '',
			'post_type' => $post_type,
			'post_status' => $post_status

		);

		if ( get_post_status ($product->product_parent ) ) {


			$post['post_parent'] = $product->product_parent;
			$post['post_status'] = 'hidden';
            $product -> product_import_values['pictures'] = false;
            $product -> product_import_values['cover_picture'] = false;
            $product -> product_import_values['features'] = false;

		}

		$post["id"] = wp_insert_post($post);

		$post['affiliate_partner'] = $product->product_affiliate;

		$post['images'] = array();

		if(isset($product->image_pattern)){

		    $post['image_pattern'] = $product->image_pattern;

        }

		// Weise Kategorie und Marke zu

		wp_set_object_terms( $post['id'],$product->selected_type, 'imbaftypes');
		wp_set_object_terms( $post['id'], $product->selected_brand, 'imbafbrands' );

		$brand = term_exists($product -> selected_brand,'imbafbrands');
		wp_set_object_terms($post['id'], array( (int) $brand['term_id']), 'imbafbrands' );

		$type = term_exists($product -> selected_type,'imbaftypes');
		wp_set_object_terms($post['id'], array( (int) $type['term_id']), 'imbaftypes' );

		// Setze den Affiliate Partner für Produkt

		add_post_meta( $post['id'], '_imbaf_affiliate', $product->product_affiliate);
		add_post_meta( $post['id'], '_imbaf_affiliate_identifier', $product->product_affiliate_identifier);

		add_post_meta( $post['id'], '_imbaf_price', $product -> product_prices);

		add_post_meta( $post['id'], '_imbaf_selected_price', $product->selected_price);
		add_post_meta( $post['id'], '_imbaf_last_price_update', date('Y-m-d H:i:s',time()));
		add_post_meta( $post['id'], '_imbaf_product_shipping_detail', $product->product_shipping_detail);


		add_post_meta($post['id'],'_imbaf_reviews_iframe_link',$product->reviews_iframe_link);

		//Identifizierende Merkmale importieren

		foreach($product -> product_identification as $id){

		    if(!isset($id['unique'])) {$id['unique'] = false;}

			add_post_meta( $post['id'], '_imbaf_'.strtolower($id['name']), $id['value'],$id['unique']);

		}

		// Features importieren

		if($product->product_import_values['features'] == 1){

			add_post_meta( $post['id'], '_imbaf_features', $product -> product_features );

		}

		if(isset($args['type']) && $args['type'] == 'temporary'){

			add_post_meta( $post['id'], '_imbaf_cdn_pictures', $product->product_pictures);

		}

		if($post['post_status'] != 'hidden'){

			// Importiere Beschreibung
			add_post_meta( $post['id'], '_imbaf_description', $product->product_description);

			// Produktmaße importieren

			if(isset($product->product_import_values['package_sizes']) && $product->product_import_values['package_sizes'] == 1){

				// Hier Import für Verpackungsgröße

				add_post_meta( $post['id'], '_imbaf_package_dimensions_width', $product->product_dimensions['package_dimensions']['width']);
				add_post_meta( $post['id'], '_imbaf_package_dimensions_height', $product->product_dimensions['package_dimensions']['height']);
				add_post_meta( $post['id'], '_imbaf_package_dimensions_length', $product->product_dimensions['package_dimensions']['length']);
				add_post_meta( $post['id'], '_imbaf_package_dimensions_unit', $product->product_dimensions['package_dimensions']['unit']);


				add_post_meta( $post['id'], '_imbaf_package_weight', $product->product_dimensions['package_weight']['weight']);
				add_post_meta( $post['id'], '_imbaf_package_weight_unit', $product->product_dimensions['package_weight']['unit']);

			}


			// Verpackungsmaße importieren

			if($product->product_import_values['product_sizes'] == 1){

				// Hier Import für Produktgröße

				add_post_meta( $post['id'], '_imbaf_item_dimensions_width', $product->product_dimensions['item_dimensions']['width']);
				add_post_meta( $post['id'], '_imbaf_item_dimensions_height', $product->product_dimensions['item_dimensions']['height']);
				add_post_meta( $post['id'], '_imbaf_item_dimensions_length', $product->product_dimensions['item_dimensions']['length']);
				add_post_meta( $post['id'], '_imbaf_item_dimensions_unit', $product->product_dimensions['item_dimensions']['unit']);


				add_post_meta( $post['id'], '_imbaf_item_weight', $product->product_dimensions['item_weight']['weight']);
				add_post_meta( $post['id'], '_imbaf_item_weight_unit', $product->product_dimensions['item_weight']['unit']);


			}

			// Bilder importieren

			if($product->product_import_values['pictures'] == true || $product->product_import_values['cover_picture'] == true){


				foreach($product->product_pictures as $image){


					if($product->product_import_values['pictures_change_name'] == false){

						$title = pathinfo($image['large']);
						//$title = $product -> image_name_pattern;

					} else {

						$title = $post['post_title'];
						//$title = $product -> image_name_pattern;
					}

					array_push($post['images'],array('url' => $image['large'],'title'=>$title));


				}


				if($product->product_import_values['pictures'] != false){

					// ALLE BILDER


				} else if ($product->product_import_values['cover_picture'] != false){

					// EIN BILD

					$post['images'] = array_slice($post['images'],0 ,1);


				} else {

					// KEIN BILD

					$post['images'] = array();

				}


				if(count($post['images']) > 0){

					$this -> importPicture($post);

				}

			}


			add_post_meta( $post['id'], '_imbaf_cdn_pictures', $product->product_pictures);

		}

		// Affiliate Links importieren

		add_post_meta( $post['id'], '_imbaf_affiliate_links', $product->product_affiliate_links);


		if(isset($args['type']) && $args['type'] == 'temporary'){

		    if(isset($args['topseller_term']) && $args['topseller_term'] != null){

                add_post_meta( $post['id'], '_imbaf_expires', time()+60*60*12);

            } else {

                add_post_meta( $post['id'], '_imbaf_expires', time()+60*60*24*14);

            }

			add_post_meta( $post['id'], '_imbaf_group', $args['topseller_group']);

		}

		else {

			wp_die();
		}


	}

	// Importiere Produktbilder

	public function importPicture($post, $noThumb = false){


		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$wp_upload_dir = wp_upload_dir();

		foreach($post['images'] as &$image){


			$title = $image['title'];

			$title_file = sanitize_title( $title );

			$path = $image['url'];

			$info = pathinfo($path);
			$image = array(

				'path' => $path,
				'newpath' => $wp_upload_dir['path'].'/',
				'extension' => $info['extension'],
				'name' => $title_file,
				'name_original' => $title_file

			);


			if(isset($post['image_pattern'])){


			    $image['name'] = $post['image_pattern'];

            }

			if(!file_exists($image['newpath'])){

				mkdir($image['newpath']);

			}

			$i = 0;

			while(file_exists($image['newpath'].$image['name'].'.'.$image['extension'])){

				$i++;

				$suffix = '_'.$i;
				$image['name'] = $image['name_original'].'_'.$suffix;

			}

			$image['newpath'] = $image['newpath'].$image['name'].'.'.$image['extension'];
			$image['title'] = $title;


			try {

				if ( ini_get( 'allow_url_fopen' ) == 1 ) {

					file_put_contents( $image['newpath'], file_get_contents( $image['path'] ) );

				}

			} catch ( Exception $e ) {

			}





			if(!file_exists($image['newpath'])){


				$image['newpath'] = preg_replace("/.*(?<=src=[\"'])([^\"']*)(?=[\"']).*/", '$1',media_sideload_image($path, $post['id'], $post['post_title']));

				$info = pathinfo($image['newpath']);

				$image['newpath'] = $wp_upload_dir['path'].'/'.$info['basename'];



			}




			if(file_exists($image['newpath'])){

				// $filename should be the path to a file in the upload directory.
				$filename = $image['newpath'];


				// The ID of the post this attachment is for.
				$parent_post_id = $post['id'];

				// Check the type of file. We'll use this as the 'post_mime_type'.
				$filetype = wp_check_filetype( basename( $filename ), null );

				// Get the path to the upload directory.
				$wp_upload_dir = wp_upload_dir();

				// Prepare an array of post data for the attachment.
				$attachment = array(
					'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
					'post_mime_type' => $filetype['type'],
					'post_title'     => $title ,
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				// Insert the attachment.
				$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

				// Generate the metadata for the attachment, and update the database record.
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );


				wp_update_attachment_metadata( $attach_id, $attach_data );


				$image['parent_post_id'] = $parent_post_id;
				$image['attach_id'] = $attach_id;


				update_post_meta($attach_id, 'imbaf_source', $post['affiliate_partner']);
				update_post_meta($attach_id, 'imbaf_source_url', $path);



			}

		}



		if(file_exists($post['images'][0]['newpath']) && $noThumb == false){

		    if(get_option('imbaf_import_post_thumbnails') == 1){

			set_post_thumbnail( $post['images'][0]['parent_post_id'], $post['images'][0]['attach_id'] );

            }

            if(get_option('imbaf_import_product_pictures') == 1){


                update_post_meta($post['images'][0]['parent_post_id'],'_imbaf_product_image_id',$post['images'][0]['attach_id']);

            }
		}


		return 1;


	}

	public function translatePrices($prices){



		$display_names = array(

			'list_price' => __('Listenpreis',IMBAF_TEXT_DOMAIN),
			'offering_price' => __('Angebotspreis',IMBAF_TEXT_DOMAIN),
			'lowest_new_price' => __('Niedrigster Neupreis',IMBAF_TEXT_DOMAIN),
			'lowest_used_price' => __('Niedrigster Gebrauchtpreis',IMBAF_TEXT_DOMAIN)

		);


		foreach($prices as &$price){


			if(isset($price['name'])){
				$price['display_name'] = $display_names[$price['name']];
			}



		}


		return $prices;


	}

	public function searchResults(){

		return false;

	}

	public function refetchPrices(){


	}

	public function garbageCollection(){


		global $wpdb;


		$posts = $wpdb -> get_results("SELECT pm.post_id, pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.meta_key = '_imbaf_affiliate' AND meta_value IS NULL;");


		foreach($posts as $post){


			wp_delete_post($post->post_id,true);



		}



	}

}
