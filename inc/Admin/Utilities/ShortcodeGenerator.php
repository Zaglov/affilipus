<?php

namespace imbaa\Affilipus\Admin\Utilities;
use imbaa\Affilipus\Core\Affiliates as AFFILIATES;
use imbaa\Affilipus\Core\Output as OUTPUT;


class ShortcodeGenerator {




	public function __construct(){



		add_action( 'wp_ajax_imbaf_codegen_list_products', array($this,'listAllProducts') );
		add_action( 'wp_ajax_imbaf_codegen_get_shortcodes', array($this,'getPossibleShortcodes') );
		add_action( 'wp_ajax_imbaf_codegen_get_templates', array($this,'getPossibleTemplates') );


	}



	public function register_scripts(){




	}


	public function answer($data){


		$data['post'] = $_POST;

		if(isset($_POST['context']) && $_POST['context'] == 'api') {

			header('Content-type: text/json');
			echo json_encode($data);
			wp_die();

		}

		else {
			print_r($data);
		}
	}

	// Listet alle Produkte auf

	public function listAllProducts(){

		$p = new AFFILIATES\affiliateProduct();


		$products = $p -> listAllProducts();



		$data = array('products' => $products);

		$this -> answer($data);

	}

	// Produkt nach Namen suchen

	public function searchProductByName(){

		$p = new AFFILIATES\affiliateProduct();

		$products = $p -> searchProductByName('division');


		return $products;



	}

	// Mögliche Shortcodes und mögliche Parameter ermitteln

	public function getPossibleShortcodes($product_id = null){

		$data = array('shortcodes' => OUTPUT\imbafShortcodes::$shortcodes);

        $api = new \imbaa\Affilipus\Core\API\affilipusAPI();
        $license = $api -> checkLicense(['cached' => true]);

		foreach($data['shortcodes'] as $key => $shortcode){


			if($shortcode['generator'] == false){
				unset($data['shortcodes'][$key]);

			}

			if(($shortcode['public'] == false)){

                if(isset($license['beta']) && $license['beta'] == 1){




                } else if (AFP_DEBUG == true){



                }

                else {

                    unset($data['shortcodes'][$key]);

                }

            }




		}

		usort($data['shortcodes'],function($a,$b){

			return strcmp($a["hr_name"], $b["hr_name"]);

		});

		$data['shortcodes'] = array_values( $data['shortcodes'] );




		$this -> answer($data);


	}

	// Mögliche Templates für einen Shortcode ermitteln

	public function getPossibleTemplates($shortcode = null,$product= null){

		$t = new \imbaa\Affilipus\Admin\imbafTemplates();
		$s = new OUTPUT\imbafShortcodes();

		$templates = array();

		if(isset($_POST['shortcode'])){

			$shortcode = $_POST['shortcode'];

		}

		if($shortcode != null){

			$shortcodes = $s::$shortcodes;
			$shortcode = $shortcodes[$shortcode];
			$shortcode['args'] = $this->getParameters($shortcode['code']);

		} else {

			$shortcode = array('templates' => false);

		}

		if($shortcode['templates'] == true){

			$templates = $t -> get_templates();

			foreach($templates as $key => &$template){


				if(isset($shortcode['args'])) {

					if (isset($shortcode['args']['default_template']) && $template['basename'] == $shortcode['args']['default_template'] ) {

						// Lösche das Template aus der Liste

					}

				}


			}

		} else {

			$templates = array();

		}


		$data = array('templates' => $templates,'shortcodeInfo' => $shortcode);

		$this -> answer($data);

	}


	function getParameters($shortcode = null, $product = null){

		$s = new OUTPUT\imbafShortcodes();

		$shortcodes = $s::$shortcodes;
		$shortcode = $shortcodes[$shortcode];

		$params = call_user_func_array(array($s,$shortcode['callback']), array(array('get_params' => true)));


		foreach($params as $key => &$param){



				$value = $param;


				if(!isset($value['type'])){
					$value['type'] = 'input';
				}
			
			
			
				if(isset($value['wp_option']) && get_option($value['wp_option']) != null){

					$value['default_value'] = get_option($value['wp_option']);
					
				} else {

					$value['default_value'] = $value['value'];
					
				}

				
				$value['value'] = null;


				if($value['type'] == 'select'){

					$value['value'] = $value['default_value'];

				}


				$param = array('name' => $key, 'value' => $value);

			if(isset($param['value']['internal']) && $param['value']['internal'] == 'true'){

				unset($params[$key]);

			}







		}

		if(array_key_exists('default_template',$params)){unset($params['default_template']);}
		if(array_key_exists('template',$params)){unset($params['template']);}

		$params = array_values($params);

		return $params;


	}

}