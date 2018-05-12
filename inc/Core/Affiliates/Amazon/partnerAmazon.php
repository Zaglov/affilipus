<?php

namespace imbaa\Affilipus\Core\Affiliates\Amazon;
use imbaa\Affilipus\Core as CORE;

class partnerAmazon extends \imbaa\Affilipus\Core\Affiliates\affiliatePartner {

	var $logfile = '';

	var $priceHierarchy = array(

		'offering_price',
		'lowest_new_price',
		'list_price'

	);

	public function setup(){

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'imbaf_flush_cache', array( $this, 'flushTopsellerData' ) );

	}

	public function setup_menu(){

		//create new top-level menu

		add_submenu_page('imbaf_partner','Amazon Affiliate', 'Amazon', 'administrator','imbaf_partner_amazon', array($this,'admin_page'));

		//call register settings function

		add_action( 'admin_init', array($this,'register_settings') );

	}

	public function register_settings() {

		//register our settings

		register_setting( 'imbaf_partner_amazon', 'AWS_API_KEY' );
		register_setting( 'imbaf_partner_amazon', 'AWS_API_SECRET_KEY_NEW' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_OLD' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_CO_UK' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_COM' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_FR' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_ES' );
		register_setting( 'imbaf_partner_amazon', 'AWS_ASSOCIATE_TAG_DE' );



		register_setting( 'imbaf_partner_amazon', 'imbaf_amazon_global_asin_exclude' );
		register_setting( 'imbaf_partner_amazon', 'imbaf_amazon_hide_prime_logo' );

	}

	public function setKeys(){


		$key_old = esc_attr( get_option('AWS_API_SECRET_KEY') );
		$key_new = esc_attr( get_option('AWS_API_SECRET_KEY_NEW') );


		if($key_new != 'SECRET'){

			update_option('AWS_API_SECRET_KEY',$key_new);

		}

	}

	public function settings_page(){


		@$this -> reparseLinks();
		@$this -> setKeys();

		$api_key = esc_attr( get_option('AWS_API_KEY') );
		$api_secret = esc_attr( get_option('AWS_API_SECRET_KEY') );


		?>

		<form method="post" action="options.php">
			<?php  settings_fields( 'imbaf_partner_amazon' );?>
			<?php  do_settings_sections( 'imbaf_partner_amazon' );?>

			<table class="form-table widefat striped" style="max-width:600px;">
				<tr valign="top">
					<td scope="row">AWS API Key</td>
					<td>
						<input type="text" name="AWS_API_KEY" value="<?php echo $api_key; ?>" />
						<?php if(get_option('AWS_API_KEY') == ''){ ?>
							<p>Trage hier deinen Amazon AWS API Access Key ein</p>
						<?php  }?>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS API Secret Key</td>
					<td>



						<?php if($api_secret != null){

							$api_secret = 'SECRET';

							?>

							<input type="password" name="AWS_API_SECRET_KEY_NEW" value='<?php echo $api_secret; ?>'>


							<?php



						} else {

							?>


							<textarea name="AWS_API_SECRET_KEY_NEW"></textarea>


							<?php

						}
						?>





						<?php if(get_option('AWS_API_SECRET_KEY') == ''){ ?>
							<p>Trage hier deinen Amazon AWS API Secret Key ein.</p>
						<?php  }?>
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS Associate Tag (de)</td>
					<td>
						<input type="text" name="AWS_ASSOCIATE_TAG_DE" value="<?php echo  get_option('AWS_ASSOCIATE_TAG_DE'); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS Associate Tag (co.uk)</td>
					<td>
						<input type="text" name="AWS_ASSOCIATE_TAG_CO_UK" value="<?php echo get_option('AWS_ASSOCIATE_TAG_CO_UK'); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS Associate Tag (.com)</td>
					<td>
						<input type="text" name="AWS_ASSOCIATE_TAG_COM" value="<?php echo get_option('AWS_ASSOCIATE_TAG_COM'); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS Associate Tag (.fr)</td>
					<td>
						<input type="text" name="AWS_ASSOCIATE_TAG_FR" value="<?php get_option('AWS_ASSOCIATE_TAG_FR'); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">AWS Associate Tag (.es)</td>
					<td>
						<input type="text" name="AWS_ASSOCIATE_TAG_ES" value="<?php get_option('AWS_ASSOCIATE_TAG_ES'); ?>" />
					</td>
				</tr>

				<tr valign="top">
					<td scope="row">Prime Logo deaktivieren</td>
					<td>
						<input type="checkbox" name="imbaf_amazon_hide_prime_logo" value="1" <?php if(get_option('imbaf_amazon_hide_prime_logo') == 1){ echo "checked";} ?>>
					</td>
				</tr>

				<tr>

					<td scope="row"><label for="imbaf_amazon_global_asin_exclude" >Globaler ASIN Exclude</label></td>
					<td>



						<textarea rows="10" class="large-text code" name="imbaf_amazon_global_asin_exclude"><?php echo esc_attr( get_option('imbaf_amazon_global_asin_exclude') ); ?></textarea>
						<p class="description">Trage hier eine Liste von ASINs ein, die nicht bei der Ausgabe von Topseller Listen berücksichtigt werden sollen. Eine ASIN pro Zeile.</p>


					</td>

				</tr>

				<tr>


					<td></td>
					<td><a href="https://affilipus.com/amazon/" class="button" target="_blank">Hilfe</a></td>

				</tr>

			</table>




			<?php

			if(get_option('AWS_API_KEY') != '' && get_option('AWS_API_SECRET_KEY') != '') {

				//

			} else {


				?>

				<div class="notice notice-error is-dismissible">
					<p>Bitte trage deine Amazon AWS-API Zugangsdaten ein.</p>
				</div>


				<?php

			}


			?>

			<?php submit_button(); ?>


		</form>


		<?php

	}

	public function search_page(){


		wp_enqueue_script( 'imbaf_search');
		wp_localize_script(
			'imbaf_search',
			'ajax_object',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'partner' => 'amazon'
			)
		);


		wp_enqueue_style('imbaf-admin');


		$tpl = new \imbaa\Affilipus\Admin\Utilities\adminTemplates();

		$tpl -> smarty->display('amazon/search.tpl');

	}

	public function tabbed_navigation(){

		@$this -> setKeys();


		?>

		<h2>Amazon Affiliate Programm</h2>

		<h2 class="nav-tab-wrapper">

			<a
				class="nav-tab <?php if($_GET['action'] == 'settings' || !isset($_GET['action'])) {?>nav-tab-active <?php } ?>"
				href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_amazon&action=settings">
				Einstellungen
			</a>



			<a
				class="nav-tab <?php if($_GET['action'] == 'import_product') {?>nav-tab-active <?php } ?>"
				href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_amazon&action=import_product">
				Produktimport
			</a>


			<a
				class="nav-tab <?php if($_GET['action'] == 'import_price') {?>nav-tab-active <?php } ?>"
				href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_amazon&action=import_price">
				Preisimport
			</a>



			<a
				class="nav-tab <?php if($_GET['action'] == 'test_call') {?>nav-tab-active <?php } ?>"
				href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_amazon&action=test_call">
				API Test
			</a>

			<?php


			if(defined('AFP_DEBUG') && AFP_DEBUG == true){

				?>

				<a
					class="nav-tab <?php if($_GET['action'] == 'debug') {?>nav-tab-active <?php } ?>"
					href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_amazon&action=debug">
					Debug
				</a>

				<?php


			}


			?>



		</h2>


		<?php



	}

	public function import_price_page(){


		?> <p>
			Ich rufe automatisch und mehrmals Täglich so viele Preise,
			wie dein Server mich lässt, über den wp-cron für dich ab.
			Du kannst aber auch einen eigenen Cronjob einrichten, mit dem du die Preise häufiger abrufen kannst.
		</p>

		<?php

		$url = IMBAF_PLUGIN_URL.'cron.php?partner=amazon&secret='.md5(NONCE_KEY.$_SERVER['HTTP_HOST']);


		$cron_info = get_option('imbaf_cron_amazon_refetch_prices_status');


		if($cron_info != null){

			echo "<p>
							Der Cronjob wurde zuletzt 
							am ".date('d.m.Y',$cron_info['start'])." 
							um ".date('H:i',$cron_info['start'])." ausgeführt. 
							Ich habe {$cron_info['products_updated']} Produkt(e) aktualisiert. 
							Das hat {$cron_info['duration']} Sekunden gedauert.
							
				</p>
							
				";

		}

		echo "<p>Die Preise werden nächstes mal ";

		$next_cron = wp_next_scheduled('imbaf_hourly_event'); if($next_cron != 0){

			echo 'am '.date('d.m.Y G:i',$next_cron);
		} else {
			echo 'leider nicht aktualisiert werden.
			Bitte aktiviere und deaktiviere Affilipus. 
			Sollte das Problem weiterhin bestehen, überprüfe bitte, ob WP-CRON für deine Webseite aktiv ist, 
			oder wende dich an deinen Webhosting Anbieter.';
		}

		echo "</p>";


		echo "<pre>";

		echo 'Deine Cronjob URL lautet: '.$url;

		echo "</pre>";


	}

	public function log($text){


		$this -> logfile .= date('H:i:s',time()).": ".$text."\r\n";

	}

	public function test_call(){

		
		if(isset($_POST['term'])){
			
			
			$term = $_POST['term'];
			
		} else {
			$term = 'BlueRay';

		}

		$land = 'de';

		$beginn = microtime(true);

		$this -> log("Starte Testlauf mit dem Suchbegriff <strong>{$term}</strong> bei <strong>Amazon {$land}</strong>.");

		$results = @$this->productSearch(array('country' => $land,'page' => 1,'term' => $term));

		
		if($results['responseCode'] == 0){

			$this -> log("<strong style=\"color:red;\">Request fehlgeschlagen – {$results['responseMessage']}</strong>");

		}
		else {

			$this -> log("<strong style=\"color: green;\">Request erfolgreich.</strong>");

		}


		$dauer = round(microtime(true) - $beginn,2);




		$this -> log("Verarbeitungszeit {$dauer} Sek.");

		?>





		<div class="wrap">

			<h3>Amazon API Testabruf</h3>

			<pre style="white-space:pre-wrap;"><?php echo $this -> logfile; ?></pre>


			<form method="POST">


				<input type="text" placeholder="Suchbegriff" name="term" value="<?php if(isset($_POST['term'])){echo $_POST['term'];} else { echo "BlueRay";} ?>">

				<input type="submit">
			</form>

			<h4>Rohwerte</h4>
			<textarea style="width:100%;" rows="20"><?php print_r($results['raw']); ?></textarea>
			<textarea style="width:100%;" rows="20"><?php print_r($results['products']); ?></textarea>




			</pre>



		</div>



		<?php

	}

	public function admin_page() {


		?> <div class="wrap"> <?php

			$api = new CORE\API\affilipusAPI();
			if(!$api->allowAction(true)){die();}

			if(!isset($_GET['action'])){

				$_GET['action'] = null;

			}
			else {



			}


			if($_GET['action'] == null){




				if(get_option('AWS_API_KEY') == '' || get_option('AWS_API_SECRET_KEY') == '' || (get_option('AWS_ASSOCIATE_TAG_DE') == '' && get_option('AWS_ASSOCIATE_TAG_CO_UK') == '' && get_option('AWS_ASSOCIATE_TAG_COM') == '' && get_option('AWS_ASSOCIATE_TAG_FR') == '') && get_option('AWS_ASSOCIATE_TAG_ES') == ''){

					$_GET['action'] = 'settings';

				} else {

					$_GET['action'] = 'import_product';

				}

			}


			$action = $_GET['action'];


			$this -> tabbed_navigation();

			switch($action){


				case 'settings':

					$this->settings_page();


					break;

				case 'import_product':



					$this->search_page();


					break;


				case 'import_price':

					$this->import_price_page();

					break;

				case 'test_call':


					$this -> test_call();

					break;


				case 'debug':

					$this -> debug();

					break;

				default:


					$this->settings_page();

					break;


			}



			?> </div> <?php



	}

	//////////////////////////

	public function aws_signed_request( $region, $params, $public_key, $private_key, $associate_tag = NULL, $version = '2011-08-01' ) {

		/*
		Copyright (c) 2009-2012 Ulrich Mierendorff

		Permission is hereby granted, free of charge, to any person obtaining a
		copy of this software and associated documentation files (the "Software"),
		to deal in the Software without restriction, including without limitation
		the rights to use, copy, modify, merge, publish, distribute, sublicense,
		and/or sell copies of the Software, and to permit persons to whom the
		Software is furnished to do so, subject to the following conditions:

		The above copyright notice and this permission notice shall be included in
		all copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
		THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
		FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
		DEALINGS IN THE SOFTWARE.
		*/

		/*
		Parameters:
		$region - the Amazon(r) region (ca,com,co.uk,de,fr,co.jp)
		$params - an array of parameters, eg. array("Operation"=>"ItemLookup",
		"ItemId"=>"B000X9FLKM", "ResponseGroup"=>"Small")
		$public_key - your "Access Key ID"
		$private_key - your "Secret Access Key"
		$version (optional)
		*/

		// some paramters
		$method = 'GET';
		$host = 'webservices.amazon.' . $region;
		$uri = '/onca/soap';

		// additional parameters
		$params['Service'] = 'AWSECommerceService';
		$params['AWSAccessKeyId'] = $public_key;
		// GMT timestamp
		$params['Timestamp'] = gmdate( 'Y-m-d\TH:i:s\Z' );
		// API version
		$params['Version'] = $version;
		if ( $associate_tag !== NULL ) {
			$params['AssociateTag'] = $associate_tag;
		}

        // sort the parameters
		ksort( $params );

        // create the canonicalized query
		$canonicalized_query = array();
		foreach ( $params as $param => $value ) {
			$param = str_replace( '%7E', '~', rawurlencode( $param ) );
			$value = str_replace( '%7E', '~', rawurlencode( $value ) );
			$canonicalized_query[] = $param . '=' . $value;
		}
		$canonicalized_query = implode( '&', $canonicalized_query );

        // create the string to sign
		$string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;

        // calculate HMAC with SHA256 and base64-encoding
		$signature = base64_encode( hash_hmac( 'sha256', $string_to_sign, $private_key, TRUE ) );

        // encode the signature for the request
		$signature = str_replace( '%7E', '~', rawurlencode( $signature ) );

        // create request
		$request = 'http://' . $host . $uri . '?' . $canonicalized_query . '&Signature=' . $signature;

		return $request;
	}

	public function productSearch($args){

		$args = shortcode_atts(
			array(
				'SearchIndex' => 'All',
				'page' => 1,
				'term' => 'B00TFDDNNO',
				'country' => 'de',
				'ResponseGroup' => "Large,Reviews,SalesRank",
				'operation' => 'ItemSearch'
			), $args);

		$credentials = array(

			'AWS_API_KEY' => esc_attr(get_option('AWS_API_KEY')),
			'AWS_API_SECRET_KEY' =>  esc_attr(get_option('AWS_API_SECRET_KEY')),
			'AWS_ASSOCIATE_TAG' => '{{AFP-AMAZON-ASSOCIATE-TAG}}'

		);

		foreach($credentials as $key => $credential){

			if($credential == ''){

				return array('responseCode' => 0,'responseMessage' => "Fehlende Amazon API-Credentials: {$key}");

			}

		}

		try {

			if($args['operation'] == 'ItemLookup'){


				$request_args = array(
					"Operation"=>$args['operation'],
					"ResponseGroup"=>$args['ResponseGroup'],
					'IdType' => 'ASIN',
					'ItemId' => $args['term']
				);

			}

			else {

				$request_args = array(
					"Operation"=>$args['operation'],
					"SearchIndex"=>$args['SearchIndex'],
					"Keywords"=>$args['term'],
					"ItemPage"=>$args['page'],
					"ResponseGroup"=>$args['ResponseGroup']
				);

			}


			$request = $this->aws_signed_request($args['country'],

				$request_args,
				$credentials['AWS_API_KEY'],
				$credentials['AWS_API_SECRET_KEY'],
				$credentials['AWS_ASSOCIATE_TAG']
			);

			$ch = curl_init();

			curl_setopt($ch,CURLOPT_URL, $request);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			$result = curl_exec($ch);

			curl_close($ch);

			$result = simplexml_load_string($result);
			$response = json_encode($result);
			$response = json_decode($response);


			if(!$response){

				return false;

			}

			if(isset($response->Items->Request->IsValid) && $response->Items->Request->IsValid == 'False'){


                if(current_user_can('administrator')){

                    return array(
                        'responseCode'=> 0,
                        'responseMessage' => 'Invalid Request'."<br>".$response->Items->Request->Errors->Error->Message,
                        'raw' => $response
                    );

                } else {


                    return ['responseCode' => 0, 'responseMessage' => 'Es ist ein Fehler beim Abruf der Produktdaten aufgetreten.'];

                }

			} else if (isset($response->Error->Code)){

                if(current_user_can('administrator')) {

                    return array(
                        'responseCode' => 0,
                        'responseMessage' => 'Invalid Request' . "<br>" . $response->Error->Message,
                        'raw' => $response
                    );

                } else {

                    return ['responseCode' => 0, 'responseMessage' => 'Es ist ein Fehler beim Abruf der Produktdaten aufgetreten.'];

                }


            }

			if($request_args['Operation'] == 'ItemLookup' ){


				$items = $response->Items->Item;

				$totalResults = count($items);
				$maxPages = 1;

				if($totalResults == 1){

					$items = [$items];

				}

			}

			else {

				if($response->Items->TotalResults == 1){
					$items = array($response->Items->Item);
					$response->Items->TotalResults = 1;
					$response->Items->TotalPages = 1;
				}
				else if($response->Items->TotalResults > 1){

					$items = $response->Items->Item;
				}

				else { $items = null ;}

				$totalResults = $response->Items->TotalResults;
				$maxPages = $response->Items->TotalPages;


			}

			$products = array();


			if($items != null){

				$products = $this -> sanitizeSearchItems($items,$args);

			}

			return array('responseCode'=>1,'raw'=>$response,'products' => $products,'totalResults' => $totalResults, 'maxPages' => $maxPages);



		}

		catch(Exception $e)
		{
			echo "Exception";
			echo $e->getMessage();
		}

	}

	public function temporaryImportProducts($asins,$country='de',$group,$round=1){

		global $wpdb;

		foreach($asins as &$asin){

			$asin = trim($asin);

		}

        $product_query = "
            
            SELECT pm.post_id, pm2.meta_value AS ASIN
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->postmeta} pm2 ON pm.post_id = pm2.post_id 
            WHERE pm. meta_key = '_imbaf_group' 
            AND pm.meta_value = '{$group}' AND pm2.meta_key = '_imbaf_asin'
            ORDER BY pm.post_id;
            ";

		$products = $wpdb->get_results($product_query, ARRAY_A);

		if(count($products) != 0 && count($products) != count($asins)){

			$this -> flushTopsellerData($group);

		}

		if (count($products) > 0 && count($products) == count($asins)){

			$temp_products = [];

			foreach($products as $product){

				array_push($temp_products,$product['post_id']);

			}
			
			return $temp_products;
			
		}

		else {


			$asin_groups = array_chunk($asins,10);

			foreach($asin_groups as $asingroup){

				$products = $this->productSearch([

					'term' => implode(',',$asingroup),
					'country' => $country,
					'operation' => 'ItemLookup'

				]);

				if(count($asingroup) == 1){

					$this->importProduct(
						array(
							'product' => $products['products'][0],
							'type' => 'temporary',
							'topseller_group' => $group
						)
					);

				} else {

				    if($products & count($products['products']) != 0){

                        foreach($products['products'] as $product){


                            $this->importProduct(
                                array(
                                    'product' => $product,
                                    'type' => 'temporary',
                                    'topseller_group' => $group
                                )
                            );


                        }

                    } else {

				        return false;

                    }

				}

			}

			if($round > 1){return false;}

			$round = $round+1;

			return $this->temporaryImportProducts($asins,$country,$group,$round);

		}
		


	}

	public function extractPrices($item) {

		$product_prices = array();

		if(isset($item->ItemAttributes->ListPrice->Amount)){
			$product_prices['list_price'] = array('name' => 'list_price', 'price' => $item->ItemAttributes->ListPrice->Amount/100,'currency' => $item->ItemAttributes->ListPrice->CurrencyCode);
		}

		if(isset($item->Offers->Offer->OfferListing->Price->Amount)){
			$product_prices['offering_price'] = array('name' => 'offering_price','price' => $item->Offers->Offer->OfferListing->Price->Amount/100,'currency' => $item->Offers->Offer->OfferListing->Price->CurrencyCode);
		}


		if(isset($item->OfferSummary->LowestNewPrice->Amount)){
			$product_prices['lowest_new_price'] = array('name' => 'lowest_new_price','price' => $item->OfferSummary->LowestNewPrice->Amount/100,'currency' => $item->OfferSummary->LowestNewPrice->CurrencyCode);
		}

		if(isset($item->OfferSummary->LowestUsedPrice->Amount)){
			$product_prices['lowest_used_price'] = array('name' => 'lowest_used_price','price' => $item->OfferSummary->LowestUsedPrice->Amount/100,'currency' => $item->OfferSummary->LowestUsedPrice->CurrencyCode);
		}

		return $product_prices;

	}

	public function refetchPrices($ignoreLimit = false){


		$api = new CORE\API\affilipusAPI();
		if(!$api->allowAction()){die();}

		$cron_info = array(


			'start' => microtime(true),
			'end' => null,
			'duration' => null,
			'products_updated' => 0

		);

		global $wpdb;

		$query = 'SELECT pm.post_id, p.post_status, pm2.meta_value AS last_update
				  FROM '.$wpdb->prefix.'postmeta pm
				  JOIN '.$wpdb->prefix.'posts p ON p.ID = pm.post_id
				  JOIN '.$wpdb->prefix.'postmeta pm2 ON pm2.post_id = p.ID
				  WHERE pm.meta_key = "_imbaf_affiliate"
				  AND pm.meta_value = "amazon"
				  AND p.post_status != \'trash\'
				  AND pm2.meta_key = \'_imbaf_last_price_update\'
				  ORDER BY pm2.meta_value ASC
				  ;';

		$products = $wpdb -> get_results($query);

		echo "<pre>";

		if(get_option('imbaf_cron_amazon_refetch_prices_status') == null){


			add_option('imbaf_cron_amazon_refetch_prices_status','','',false);

		} else {


			$last_cron = get_option('imbaf_cron_amazon_refetch_prices_status');

			echo "Zuletzt ausgeführt: ".date('d.m.Y H:i:s',$last_cron['start'])."\r\n\r\n";

			echo "Zuletzt aktualisierte Produkte: {$last_cron['products_updated']}\r\n\r\n";

		}

		echo "Aktualisierbare Produkte: ".count($products)."\r\n";

		$temp_products = [];

		foreach($products as $productkey => &$product){

			$product->post = get_post($product->post_id);
			$product->asin = get_post_meta($product->post_id,'_imbaf_asin',true);
			$product->current_prices = get_post_meta($product->post_id,'_imbaf_price',true);
			$product->current_links = get_post_meta($product->post_id,'_imbaf_affiliate_links',true);
			$product->selected_price = get_post_meta($product->post_id,'_imbaf_selected_price',true);
			$product->last_update = get_post_meta($product->post_id,'_imbaf_last_price_update',true);
			$product->identifier = get_post_meta($product->post_id,'_imbaf_affiliate_identifier',true);


			if(AFP_DEBUG == true){

				$product->last_update_minutes = 120;

			} else {

				$product->last_update_minutes = round((time()-strtotime($product->last_update))/60);

			}

			if($product->last_update_minutes >= 70 || $ignoreLimit == true){

			if(!array_key_exists($product->identifier,$temp_products)){$temp_products[$product->identifier] = [];}


			$temp_products[$product->identifier][$product->asin] = $product;

			}



		}


		if(count($temp_products) == 0){

			die('Nichts zu tun.');

		}


		foreach($temp_products as $country => $country_pack){

			echo "Aktualisiere Produkte für Amazon {$country}\r\n";


			$products = array_chunk($country_pack,10,true);

			foreach($products as $pack_key => $pack){


				echo "Aktualisiere ASINS: ".implode(',',array_keys($pack))."\r\n";

				$results = $this -> productSearch(
					array(
						'term' => implode(',',array_keys($pack)),
						'country'=> $country,
						'operation' => 'ItemLookup'
					)
				)['products'];


				foreach($results as $result){


					$product = $pack[$result->ASIN];

					echo "\r\n";
					echo "Aktualisiere Preise für Produkt #{$product->post_id}  ASIN:{$product->asin} Titel:{$product->post->post_title} Land: {$product->identifier}";
					echo "\r\n";
					echo "Letztes Update: {$product->last_update} (vor {$product->last_update_minutes} Minuten)";

					update_post_meta($product->post_id,'_imbaf_reviews_iframe_link',$result->reviews_iframe_link);

					if(isset($result->product_prices)){


						$product->new_prices = $result->product_prices;

						echo "\r\nInsgesamt verfügbare Preise: ".count($product->new_prices);

						$price_temp = array();

						foreach($product->new_prices as $price){

							$price_temp[$price['name']] = $price;


						}

						$product->new_prices = $price_temp;

						if(array_key_exists($product->selected_price,$product->new_prices)){

							echo "\r\nGewählte Preisart '{$product->selected_price}' verfügbar.";

						}

						else {

							echo "\r\nGewählte Preisart '{$product->selected_price}' nicht verfügbar. Ersetze durch: ";

							foreach($this->priceHierarchy as $price_type){

								if(array_key_exists($price_type,$product->new_prices)){

									update_post_meta($product->post_id, '_imbaf_selected_price', $price_type, $product->selected_price);

									echo "'{$price_type}'.";

									break;

								}

							}

						}


						update_post_meta($product->post_id, '_imbaf_affiliate_links', $result->product_affiliate_links, $product->current_links);
						update_post_meta($product->post_id, '_imbaf_price', array_values($product->new_prices), $product->current_prices);
						update_post_meta($product->post_id, '_imbaf_last_price_update', date('Y-m-d H:i:s',time()), $product->last_update,true);
						update_post_meta($product->post_id, '_imbaf_product_shipping_detail', $result->product_shipping_detail);

						wp_cache_delete( 'imbaf_product_'.$product->post_id , 'imbaf_products');

						$cron_info['products_updated']++;

					}

					echo "\r\n\r\n";

				}


			}


			sleep(1);

		}


		/*
		if($notification_count > 0 && get_option( 'imbaf_enable_price_missing_email' ) == 1){

			$mail_subject = "Affilipus: Fehlende Preisinformationen auf ".get_option('blogname');
			//wp_mail( get_option('admin_email'), $mail_subject, $notification_text);

		}
		*/

		echo "\r\n";
		echo "Es wurden {$cron_info['products_updated']} Produkt(e) aktualisiert.";

		echo "\r\n\r\n";

		$cron_info['end'] = microtime(true);
		$cron_info['duration'] = round($cron_info['end']-$cron_info['start'],2);

		update_option('imbaf_cron_amazon_refetch_prices_status',$cron_info);

		echo "</pre>";

	}

	public function get_excluded_asins($asins = null){

		if($asins != null && !is_array($asins)){

			$asins = explode(',',$asins);

		} else {

			$asins = array();

		}


		$exclude_global = explode("\r\n",get_option('imbaf_amazon_global_asin_exclude',true));
		$exclude_total = array_merge($asins,$exclude_global);

		if(count($exclude_total)>0){

			foreach($exclude_total as $key => $value){

				if($value == null){unset($exclude_total[$key]);}

			}

		}


		return $exclude_total;

	}

	public function topsellerSearch($args){

		global $wpdb;

		$debug = false;
		$max_runs = 5;

		$args = shortcode_atts(
			array(
				'term' => null,
				'lang' => 'de',
				'limit' => 10,
				'exclude' => null,
				'page' => 1,
				'group_prefix' => 'topseller-by-term-'
			), $args);

		$term = $args['term'];
		
		$exclude_total = $this -> get_excluded_asins($args['exclude']);

		$group = $args['group_prefix'].'_'.$term.'_'.md5($term);

		$product_query = "
            
            SELECT pm.post_id, pm2.meta_value AS ASIN
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->postmeta} pm2 ON pm.post_id = pm2.post_id 
            WHERE pm. meta_key = '_imbaf_group' 
            AND pm.meta_value = '{$group}' AND pm2.meta_key = '_imbaf_asin'
            ORDER BY pm.post_id;
            ";

		$products = $wpdb->get_results($product_query, ARRAY_A);


        $products = array_map("unserialize", array_unique(array_map("serialize", $products)));


        if(count($products) != 0 && count($products) > $args['limit']){

			$this -> flushTopsellerData($group);

        }

		else if(count($products) != 0 && count($products) <= $args['limit']){

			$temp_products = [];

			foreach($products as $product){

				array_push($temp_products,$product['post_id']);

			}

			return $temp_products;

		}

		$final_products = [];
		$runs = 0;

		$break = false;

		while($break == false){

			$runs = $runs+1;

			$products = $this -> productSearch(

				array(
					'type' => 'topseller',
					'term' => $term,
					'country' => $args['lang'],
					'page' => $runs
				)

			);

			if($products['responseCode'] == 1){

				if($products['maxPages'] < $max_runs){

					$max_runs = $products['maxPages'];

				}

				$products = $products['products'];

				foreach($products as $product ){

					if(
						!in_array($product->ASIN,$exclude_total) &&
						count($final_products) < $args['limit']){

						array_push($final_products,$product);

					}

				}
				
			}

			else {

				$user = wp_get_current_user();
				$allowed_roles = array('editor', 'administrator', 'author');

				if( array_intersect($allowed_roles, $user->roles ) ) {

					echo "Fehler beim Aufruf von Amazon<br><br>";
					echo $products['responseMessage']."<br><br>";

				}

				return false;

			}

			if($runs>=$max_runs || count($final_products) >= $args['limit'] ){
				
				$break = true;

				break;


				
			}

		}

		if(count($final_products) != 0){

			foreach($final_products as $product){


				$this->importProduct(
					array(
						'product' => $product,
						'type' => 'temporary',
						'topseller_group' => $group,
                        'topseller_term' => $term
					)
				);

			}

			return $this -> topsellerSearch($args);

		} else {

			return false;

		}

	}

	public function reparseLinks(){

		global $wpdb;

		$oldtag = get_option('AWS_ASSOCIATE_TAG_OLD');
		$newtag = get_option('AWS_ASSOCIATE_TAG');

		$newtag = '{{AFP-AMAZON-ASSOCIATE-TAG}}';

		if($oldtag != $newtag && $newtag != null){



			$products = $wpdb -> get_results("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_imbaf_affiliate' AND meta_value = 'amazon';",ARRAY_A);

			$p = new CORE\Affiliates\affiliateProduct();

			foreach($products as $pr){

				$product = $p -> loadProductById($pr['post_id']);

				$product['_imbaf_affiliate_links_old'] = $product['_imbaf_affiliate_links'];

				foreach($product['_imbaf_affiliate_links'] as &$link){

					$link['url'] = str_replace($oldtag,$newtag,$link);

				}




				update_post_meta($pr['post_id'], '_imbaf_affiliate_links', $product['_imbaf_affiliate_links']);




			}

			update_option('AWS_ASSOCIATE_TAG_OLD',$newtag);




		}




	}

	public function debug(){


		$beginn = microtime(true);

		$partner = new CORE\Affiliates\affiliatePartner();

		$products = @$partner -> searchProduct('amazon',null,true);


		foreach($products['products'] as $product){


			echo '<h4>'.$product -> product_name.'</h4>';


			echo "<pre>";

			print_r($product->product_related);

			echo "</pre>";

		}

		$dauer = round(microtime(true) - $beginn,2);
		echo "Verarbeitung des Skripts: $dauer Sek.";




	}


	public function orphan_meta(){


	    global $wpdb;

        $wpdb -> query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts});");

    }

	public function flushTopsellerData($group = null){

		//echo "<br>Flush {$group}<br>";

        $this -> orphan_meta();


        global $wpdb;
		$product_query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_imbaf_group'";




		if($group != null){

			$product_query .= ' AND meta_value = "'.$group.'";';

		}

		
		$group = $wpdb -> get_results($product_query);
		


		foreach($group as $g){



			$delete = wp_delete_post( $g->post_id, true );

            if($delete == null) {


			    $wpdb -> query("DELETE FROM {$wpdb->postmeta} WHERE post_id = ".$g->post_id);

            }


		}

		wp_cache_flush();
		
		

		
		
	}

	public function sanitizeSearchItems($items,$args){

		$products = array();

		$credentials = array(

			'AWS_API_KEY' => esc_attr(get_option('AWS_API_KEY')),
			'AWS_API_SECRET_KEY' =>  esc_attr(get_option('AWS_API_SECRET_KEY')),
			'AWS_ASSOCIATE_TAG' => esc_attr(get_option('AWS_ASSOCIATE_TAG'))

		);

		foreach($items as $item){


			$this -> sanitize_ean($item);


			$product = new CORE\Affiliates\affiliateProduct();


			$product -> ASIN = $item -> ASIN;
			$product -> product_name = $item->ItemAttributes->Title;


			$this -> sanitize_reviews($item,$product);

			$product -> product_type = $item -> ItemAttributes -> ProductGroup;

			$this -> sanitize_dimensions($item,$product);
			$this -> sanitize_offers($item,$product);


			$this -> sanitize_identification($item,$product);
			$this -> sanitize_manufacturer($item,$product);


			$this -> sanitize_pictures($item,$product);


			$this -> sanitize_links($item,$product,$args,$credentials);

			$this -> sanitize_features($item,$product);


			array_push($products,$product);



		}

		usort($items,function($a,$b){

			if(isset($a->SalesRank) && isset($b->SalesRank)){
				return $a->SalesRank-$b->SalesRank;
			} else {

				return 1;

			}




		});

		global $wpdb;

		foreach($products as &$product){

			$product -> product_affiliate = 'amazon';
			$product -> product_affiliate_identifier = $args['country'];


			$query = '

              SELECT pm.meta_value, p.post_status 
              FROM '.$wpdb->prefix.'postmeta pm 
              JOIN '.$wpdb->posts.' p ON p.ID = pm.post_id
              WHERE pm.meta_key = "_imbaf_asin" 
              AND pm.meta_value = "'.$product->ASIN.'"
              AND p.post_status != "hidden"';


			$check = $wpdb->get_results($query);


			if(count($check) != 0){

				$product -> product_already_imported = true;

			}

			$product->product_prices = $this -> translatePrices($product->product_prices);

			$product = $product->sanitizeProduct();

		}

		return $products;

	}

	public function sanitize_ean(&$item){


		/*if(!isset($item->ItemAttributes->EAN) || $item->ItemAttributes->EAN != null){

			$item -> ItemAttributes->EAN = 'none';

		}*/


	}

	public function sanitize_reviews(&$item,&$product){


		if(isset($item->EditorialReviews)){


			if(isset($item->EditorialReviews->EditorialReview->Content)){
				$product -> product_description = $item->EditorialReviews->EditorialReview->Content;
			} else {
				$product -> product_description = $item->EditorialReviews->EditorialReview[1]->Content;
			}

		}

		if(isset($item->CustomerReviews) && $item->CustomerReviews->HasReviews == true) {


			$product -> reviews_iframe_link = $item -> CustomerReviews -> IFrameURL;

		}

	}

	public function sanitize_manufacturer(&$item,&$product){

		if(isset($item->ItemAttributes->Manufacturer)) {
			$product -> product_manufacturer = $item->ItemAttributes->Manufacturer;

		} else {

			$product -> product_manufacturer = null;


		}

	}

	public function sanitize_dimensions(&$item,&$product){


		$product -> product_dimensions = array(

			'item_dimensions' => array(

				'height' => 0,
				'width' => 0,
				'length' => 0,
				'unit' => 'cm'

			),

			'package_dimensions' => array(

				'height' => 0,
				'width' => 0,
				'length' => 0,
				'unit' => 'cm'

			)

		);


		if(isset($item -> ItemAttributes -> ItemDimensions -> Width)){

			$product -> product_dimensions['item_dimensions'] = array(

				'height' => round($item -> ItemAttributes -> ItemDimensions -> Height / 100*2.54,1),
				'width' => round($item -> ItemAttributes -> ItemDimensions -> Width / 100*2.55,1),
				'length' => round($item -> ItemAttributes -> ItemDimensions -> Length / 100*2.54,1),
				'unit' => 'cm'

			);




		}

		if(isset($item -> ItemAttributes -> PackageDimensions -> Width)){

			$product -> product_dimensions['package_dimensions'] = array(

				'height' => round($item -> ItemAttributes -> PackageDimensions -> Height / 100*2.54,1),
				'width' => round($item -> ItemAttributes -> PackageDimensions -> Width / 100*2.55,1),
				'length' => round($item -> ItemAttributes -> PackageDimensions -> Length / 100*2.54,1),
				'unit' => 'cm'

			);

		}


		if(isset($item -> ItemAttributes -> ItemDimensions -> Weight)){


			$product -> product_dimensions['item_weight'] = array(


				'weight' => round($item -> ItemAttributes -> ItemDimensions -> Weight / 100*0.45,1),
				'unit' => 'kg'

			);

		}


		if(isset($item -> ItemAttributes -> PackageDimensions -> Weight)){

			$product -> product_dimensions['package_weight'] = array(


				'weight' => round($item -> ItemAttributes -> PackageDimensions -> Weight / 100*0.45,1),
				'unit' => 'kg'

			);

		}


	}

	public function sanitize_offers(&$item,&$product){


		if(isset($item->Offers)){


			if(isset($item->Offers->Offer->OfferListing)) {

				$product->product_shipping_detail['IsEligibleForPrime'] = $item->Offers->Offer->OfferListing->IsEligibleForPrime;
				$product->product_shipping_detail['AvailabilityAttributes'] = (array) $item->Offers->Offer->OfferListing->AvailabilityAttributes;


			}

		}





		if(isset($item->ItemAttributes->ListPrice->Amount)){
			$product -> product_prices['list_price'] = array('name' => 'list_price', 'price' => $item->ItemAttributes->ListPrice->Amount/100,'currency' => $item->ItemAttributes->ListPrice->CurrencyCode);
		} else {

			if(isset($item->Offers->Offer->OfferListing->Price->Amount)){
				$product -> product_prices['list_price'] = array('name' => 'list_price','price' => $item->Offers->Offer->OfferListing->Price->Amount/100,'currency' => $item->Offers->Offer->OfferListing->Price->CurrencyCode);
			}

		}

		if(isset($item->Offers->Offer->OfferListing->Price->Amount)){
			$product -> product_prices['offering_price'] = array('name' => 'offering_price','price' => $item->Offers->Offer->OfferListing->Price->Amount/100,'currency' => $item->Offers->Offer->OfferListing->Price->CurrencyCode);
		}

		if(isset($item->Offers->Offer->OfferListing->SalePrice->Amount)){

			$product -> product_prices['offering_price'] = array('name' => 'offering_price','price' => $item->Offers->Offer->OfferListing->SalePrice->Amount/100,'currency' => $item->Offers->Offer->OfferListing->SalePrice->CurrencyCode);

		}

		if(isset($item->OfferSummary->LowestNewPrice->Amount)){
			$product -> product_prices['lowest_new_price'] = array('name' => 'lowest_new_price','price' => $item->OfferSummary->LowestNewPrice->Amount/100,'currency' => $item->OfferSummary->LowestNewPrice->CurrencyCode);
		}

		if(isset($item->OfferSummary->LowestUsedPrice->Amount)){
			$product -> product_prices['lowest_used_price'] = array('name' => 'lowest_used_price','price' => $item->OfferSummary->LowestUsedPrice->Amount/100,'currency' => $item->OfferSummary->LowestUsedPrice->CurrencyCode);
		}


	}

	public function sanitize_identification(&$item,&$product){

		$eans = array();

		$product -> product_identification = array();


		if(!isset($item->ItemAttributes->EAN) || $item->ItemAttributes->EAN != null){

			//$item -> ItemAttributes->EAN = 'none';

		} else {

			array_push($product->product_identification, array('name' => 'EAN', 'value' => $item->ItemAttributes->EAN));

		}

		array_push($product->product_identification, array('name' => 'ASIN', 'value' => $item->ASIN));

		//$product -> product_ean = $item->ItemAttributes->EAN;

		//array_push($eans,$item->ItemAttributes->EAN);

		if( isset($item -> ItemAttributes -> EANList) ) {


			foreach($item -> ItemAttributes -> EANList as $eanListElement){


				if(is_array($eanListElement)){

					foreach($eanListElement as $ean){

						if(!in_array($ean,$eans)){

							array_push($product->product_identification, array('name' => 'EAN', 'value' => $ean));

						}

					}

				}



			}


		}


	}

	public function sanitize_pictures(&$item,&$product){

		if(isset($item->LargeImage->URL)){


			$product -> product_pictures = array(array(

				'large' => $item->LargeImage->URL,
				'medium' => $item -> MediumImage->URL

			));




		}

		if(isset($item->ImageSets)){


			if(!isset($product -> product_pictures)) {

				$product -> product_pictures = array();

			}

			foreach($item->ImageSets as $images) {


				foreach ($images as $image) {

					if(isset($image->LargeImage)) {


						$imageset = array(
							'large' =>  $image->LargeImage->URL,
							'medium' => $image->MediumImage->URL,
							'small' => $image->SmallImage->URL,
							'swatch' => $image->SwatchImage -> URL
						);

						array_push($product -> product_pictures,$imageset );


						unset($imageset);

					}

				}


			}


		}

	}

	public function sanitize_links(&$item,&$product,&$args,&$credentials){

		$product -> product_affiliate_links = array(
			array(
				'type' => 'product_page',
				'description' => 'Product Page',
				'url' => $item->DetailPageURL)
		);


		$ignore_links = array('tell_a_friend');


		if(isset($item->ItemLinks->ItemLink) && count($item->ItemLinks->ItemLink) > 0){
			foreach($item->ItemLinks->ItemLink as $link){

				$l = array('type' => filter_var(mb_strtolower(preg_replace('/[^\w\-'. (TRUE ? '~_\.' : ''). ']+/u', '_', $link->Description)),FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),'description' => $link->Description,'url' => $link -> URL);

				if(!in_array($l['type'], $ignore_links)){

					array_push($product -> product_affiliate_links,$l);

				}

			}
		}


		array_push(
			$product -> product_affiliate_links,
			array(
				'type' => 'add_to_cart',
				'url' => 'http://www.amazon.'.
					$args['country'].
					'/gp/aws/cart/add.html?AssociateTag='.
					'{{AFP-AMAZON-ASSOCIATE-TAG}}'.
					'&SubscriptionId='.
					urlencode($credentials['AWS_API_KEY']).
					'&ASIN.1='.$product->ASIN.
					'&Quantity.1=1'
			)
		);


	}

	public function sanitize_features(&$item,&$product){

		if(isset($item->ItemAttributes->Feature)){


			if(!is_array($item->ItemAttributes->Feature)){

				$item->ItemAttributes->Feature = array($item->ItemAttributes->Feature);

			}

			$product -> product_features = $item->ItemAttributes->Feature;

		}

	}

}