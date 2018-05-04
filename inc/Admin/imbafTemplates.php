<?php


namespace imbaa\Affilipus\Admin;



if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }


class imbafTemplates {


	var $template_list = null;
	var $default_template = null;
	var $hr_template_names = array();


	function __construct(){


		$this -> template_list = $this -> get_templates();

		// Die Namen sollten später etwas intelligenter gelöst werden

		$this -> hr_template_names = array(

			'shortcode_add2cart_button.tpl' => 'Button: add2cart',
			'shortcode_buy_button.tpl' => 'Button: Jetzt kaufen',
			'shortcode_feature_list.tpl' => 'Feature Liste',
			'shortcode_price_list.tpl' => 'Preis Liste',
			'shortcode_product_box.tpl' => 'Produktbox',
			'shortcode_topseller_list.tpl' => 'Topseller Liste'

		);

	}


	function template_editor(){

			if(isset($_POST['action'])) {


				switch ($_POST['action']){


					case 'save_template':

						$this -> save_template($_POST['selected_template'],$_POST['template_content'],$_POST['css_content']);
						$this -> secure_templates();

					break;


					case 'restore_template':


						$this -> restore_template($_POST['selected_template']);
						$this -> secure_templates();

					break;


					case 'create_new_template':


						$this -> create_new_template($_POST['new_template_name']);
						$_POST['selected_template'] = $_POST['new_template_name'].'.tpl';

					break;

				}

			}



			?>

		<?php

		if(!isset($_POST['selected_template'])){

			$selected_template = $this->default_template;

		} else {

			$selected_template = $_POST['selected_template'];

		}


		if($this -> template_list != false){











		$template_is_core = $this -> get_template_path($selected_template);


		$template_has_core = $template_is_core['has_core'];
		$template_is_core = $template_is_core['core'];

		$template = $this->load_template($selected_template);

		?>

		<h1>Template Editor</h1>

		<div style="float:right;">


			<form name="templatepicker" method="post" >

				<select name="selected_template">

					<?php

					foreach($this->template_list as $templ){


						?>

						<option
							value="<?php echo $templ['name']; ?>"
							<?php if(isset($_POST['selected_template']) && $_POST['selected_template'] == $templ['name']){ echo 'selected';} ?>>
							<?php echo $templ['name'];?>
						</option>

						<?php


					}

					?>

				</select>

				<input type="hidden" name="action" value="choose_template">
				<input type="submit" value="Wählen" class="button">

			</form>

			<form name="new_template_form" method="post" style="margin-top:5px;">


				<input type="text" name="new_template_name" value="" placeholder="Template Name">
				<input type="hidden" name="action" value="create_new_template">
				<input type="submit" value="Neues Template" class="button">

			</form>

		</div>

		<form name="template" method="post" id="imbaf_template_editor">

			<h2>Template  „<?php echo $selected_template; ?>“ bearbeiten</h2>


			<h2 class="nav-tab-wrapper" id="imbaf_editor_tab">

				<a class="nav-tab nav-tab-active" href="#html-editor">HTML</a>
				<a class="nav-tab " href="#css-editor">CSS</a>

			</h2>

			<textarea rows="40" id="css-editor" style="width:100%;" class="imbaf_editor_window"  name="css_content"><?php echo $template['stylesheet']; ?></textarea>
			<textarea rows="35" id="html-editor" style="width:100%;" class="imbaf_editor_window" name="template_content"><?php echo $template['template']; ?></textarea>


			<input type="hidden" name="action" value="save_template">
			<input type="hidden" name="selected_template" value="<?php echo $selected_template; ?>">
			<input type="submit" value="Änderungen Speichern" class="button button-primary">

			<?php

			if($template_is_core == true){



			} else {


				?>

				<a href='#' class="button" id="restoreTemplate"><?php if($template_has_core){ echo "System Template wiederherstellen";} else { echo "Template löschen"; } ?></a>


				<script>




					jQuery('#imbaf_template_editor #restoreTemplate').click(function(e){


						e.preventDefault();

						jQuery('#imbaf_template_editor input[name="action"]').val('restore_template');
						jQuery('#imbaf_template_editor').submit();


					});





				</script>


				<?php

			}

			?>



			<script>

				jQuery('#css-editor').hide();

				jQuery('#imbaf_editor_tab a').click(function(e){


					e.preventDefault();

					jQuery('.imbaf_editor_window').hide();
					jQuery('#imbaf_editor_tab a').removeClass('nav-tab-active');
					jQuery(jQuery(this).attr('href')).show();
					jQuery(this).addClass('nav-tab-active');


				});

			</script>


			<script>

				jQuery('#create_new_template').click(function(e){


					e.preventDefault();


					var name = prompt('Bitte gibt einen Namen für dein Template ein');


					jQuery('form[name="new_template_form"] input[name="new_template_name"]').val(name);

					jQuery('form[name="new_template_form"]').submit();

				});

			</script>






		</form>


		<?php
		} else {
			
			
			echo "<div class=\"error\"><p>Die Templates konnten leider nicht geladen werden. Bitte stelle sicher, dass der Ordner <i>".IMBAF_CUSTOM_TEMPLATES."</i> angelegt und vom Webserver beschreibbar ist.</p></div>";


			
		}
	}

	function get_templates(){

		$template_dir_content = scandir(IMBAF_TEMPLATES);

		if(!file_exists(IMBAF_CONTENT_FOLDER)){

            wp_mkdir_p(IMBAF_CONTENT_FOLDER);


		}
		
		if(!file_exists(IMBAF_CUSTOM_TEMPLATES)){

            wp_mkdir_p(IMBAF_CUSTOM_TEMPLATES);

		}


		if(file_exists(IMBAF_CUSTOM_TEMPLATES)){

			$custom_template_dir_content = scandir(IMBAF_CUSTOM_TEMPLATES);

			$template_dir_content = array_merge($template_dir_content,$custom_template_dir_content);

			$names = array();

			$templates = array();

			foreach($template_dir_content as $template){

				$info = pathinfo($template);

				if(isset($info['extension']) && $info['extension'] == 'tpl' && !in_array($template,$names)){


					if(array_key_exists($template,$this -> hr_template_names)){

						$hr_name = $this->hr_template_names[$template];

					} else {

						$hr_name = $template;

					}

					array_push($templates,array('basename'=>str_replace('.tpl','',$template),'name'=>$template,'hr_name' => $hr_name, 'path' => IMBAF_TEMPLATES.'/'.$template));
					array_push($names,$template);
				}

			}

			$this->default_template = $templates[0]['name'];
		}

		else {

			$templates = false;

		}
		



		return $templates;

	}

	function get_template_path($template){




		$custom = IMBAF_CUSTOM_TEMPLATES.'/'.$template;
		$core = IMBAF_TEMPLATES.'/'.$template;


		if(file_exists($custom)){

			$path = array(
				'path' => $custom,
				'core' => false,
				'has_core' => file_exists($core),
				'core_version_path' => $core
			);

		} else if (file_exists($core)) {

			$path = array(
				'path' => $core,
				'core' => true,
				'has_core' => true,
				'custom_version_path' => $custom
			);

		} else {

			$path = false;

		}


		return $path;

	}

	function get_stylesheet_path($path){

		$path = str_replace('.tpl','',$path);

		$custom_path = IMBAF_CUSTOM_TEMPLATES.'/'.$path.'.css';

		if(file_exists($custom_path)){

			$fullpath = IMBAF_CUSTOM_TEMPLATES.'/'.$path.'.css';

		} else {

			$fullpath = IMBAF_TEMPLATES.'/'.$path.'.css';


		}





		return $fullpath;


	}

	function load_template( $template = null ) {


		$template_path = $this -> get_template_path( $template );

		$stylesheet_path = $this -> get_stylesheet_path( $template );

		$output = array(
			'template' => @file_get_contents($template_path['path']),
			'stylesheet' => @file_get_contents($stylesheet_path)
		);

		return $output;

	}

	function save_template($template,$content,$css_content){


		$path = $this -> get_template_path($template);

		$file = pathinfo($path['path']);

		$path = IMBAF_CUSTOM_TEMPLATES.'/'.$file['basename'];



		$path_css = $this -> get_stylesheet_path($template);

		$file = pathinfo($path_css);

		$path_css = IMBAF_CUSTOM_TEMPLATES.'/'.$file['basename'];

		$content = stripslashes($content);
		$css_content = stripslashes($css_content);


		if(strlen($css_content) == 0){

			$css_content = '/*CSS*/';

		}

		if(!@file_put_contents($path,$content)){


			?>


			<div class="error">
				<p>Die Template Datei konnte nicht geschrieben werden. Möglicherweise sind die Schreibrechte für den Ordner '<?php echo IMBAF_CUSTOM_TEMPLATES; ?>' nicht richtig gesetzt.</p>
			</div>


			<?php


		}

		if(!@file_put_contents($path_css,$css_content)){


			?>


			<div class="error">
				<p>Die CSS Datei konnte nicht geschrieben werden. Möglicherweise sind die Schreibrechte für den ordner '<?php echo IMBAF_CUSTOM_TEMPLATES; ?>' nicht richtig gesetzt.</p>
			</div>


			<?php


		}



		else {

			?>

			<div class="notice notice-success">
				<p>Die Änderungen wurden gespeichert.</p>
			</div>

			<?php

		}



	}

	function restore_template($template){

		$path = $this->get_template_path($template);

		$stylesheet_path = $this -> get_stylesheet_path( $template );




		if($path['core'] == false){

			unlink($path['path']);
			unlink($stylesheet_path);
			$this -> template_list = $this -> get_templates();

			?>

			<div class="notice notice-success">
				<p>Das Custom-Template wurde gelöscht.</p>
			</div>

			<?php

		} else {


			?>

			<div class="notice notice-success">
				<p>Das Core-Template wurde wiederhergestellt.</p>
			</div>


			<?php

		}


	}

	function secure_templates(){


		return null;

		$template_dir_content = scandir(IMBAF_CUSTOM_TEMPLATES);

		$templates = array();

		if(count($template_dir_content) > 0){

			foreach($template_dir_content as $template) {

				$template = IMBAF_CUSTOM_TEMPLATES.'/'.$template;

				$info = pathinfo($template);



				if($info['extension'] == 'tpl' || $info['extension'] == 'css'){


					array_push($templates,array('name' => $info['basename'], 'content' => file_get_contents($template)));



				}



			}



			update_option('imbaf_templates_save',$templates);

		}




	}

	function restore_secured_templates(){


		return null;

		$templates = get_option('imbaf_templates_save');

		if(count($templates) != 0 && $templates != false){

			foreach($templates as $template){

				file_put_contents(IMBAF_CUSTOM_TEMPLATES.'/'.$template['name'],$template['content']);

			}

		}

	}

	function create_new_template($template){


		$info = pathinfo($template);

		$template = $info['basename'];


		$path = $this->get_template_path($template);

		if(!$path){

			$css = IMBAF_CUSTOM_TEMPLATES.'/'.$template.'.css';
			$html = IMBAF_CUSTOM_TEMPLATES.'/'.$template.'.tpl';


			if(!file_exists($css)){
				file_put_contents($css,'/*Put your CSS here*/');
			}

			if(!file_exists($html)) {
				file_put_contents( $html, $template . '.tpl' );
			}


		}


		$this -> template_list = $this -> get_templates();





	}


}