<?php

namespace imbaa\Affilipus\Admin;
use imbaa\Affilipus\Core as CORE;



class imbafAdminPages {

	function __construct() {


		add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
		add_action( 'admin_init', array( $this, 'imbaf_register_admin_settings' ) );
		add_action( 'admin_init', array( $this, 'imbaf_register_shortcode_settings' ) );


        add_filter( 'pre_update_option_imbaf_products_slug', array( $this, 'flush_rewrites' ), 1, 2 );
        add_filter( 'pre_update_option_imbaf_enable_product_pages', array( $this, 'flush_rewrites' ), 1, 2 );
        add_filter( 'pre_update_option_imbaf_brands_slug', array( $this, 'flush_rewrites' ), 1, 2 );
        add_filter( 'pre_update_option_imbaf_types_slug', array( $this, 'flush_rewrites' ), 1, 2 );
        add_filter( 'pre_update_option_imbaf_tags_slug', array( $this, 'flush_rewrites' ), 1, 2 );


	}

	function register_admin_menus() {


		$api = new CORE\API\affilipusAPI();

		if ( $api->allowAction() ) {

			add_menu_page(
				'Affilipus',
				'Affilipus Einstellungen',
				'administrator',
				'imbaf_settings_page',
				array( $this, 'page_common_settings' ),
				IMBAF_IMAGES.'/internal/linecon-affilipus.svg'
			);

			if(get_option( 'imbaf_enable_product_pages' ) == 1) {

				add_submenu_page(
					'imbaf_settings_page',
					'Standard Template',
					'Standard Template',
					'administrator',
					'imbaf_template_settings',
					array( $this, 'page_template_settings' )
				);

			}

			add_submenu_page(
				'themes.php',
				'Affilipus Templates',
				'Affilipus Templates',
				'administrator',
				'imbaf_template_editor',
				array( $this, 'page_template_editor' )
			);

			add_submenu_page(
				'imbaf_settings_page',
				'Shortcode Einstellungen',
				'Shortcode Einstellungen',
				'administrator',
				'imbaf_shortcode_settings',
				array( $this, 'page_shortcode_settings' )
			);

			add_submenu_page(
				'tools.php',
				'Affilipus Werkzeuge',
				'Affilipus Werkzeuge',
				'administrator',
				'imbaf_tools_page',
				array( $this, 'page_tools' )
			);

			add_submenu_page(
				'imbaf_settings_page',
				'Affilipus Lizenz',
				'Lizenz',
				'administrator',
				'imbaf_settings_license',
				array($this,'page_license_settings')
			);


		}
		else {


			add_menu_page(
				'Affilipus Lizenz',
				'Affilipus Lizenz',
				'administrator',
				'imbaf_settings_license',
				array($this,'page_license_settings'),
				IMBAF_IMAGES.'/internal/linecon-affilipus.svg'
			);

		}


	}

	function page_shortcode_settings(){


		$s = new CORE\Output\imbafShortcodes();

		$shortcodes = $s -> get_configurable_shortcodes();

		?>

		<div class="wrap">

			<h1>Shortcode Einstellungen</h1>

			<p style="max-width:600px;">
				<?php

				_e('Hier hast du die Möglichkeit die Standard-Werte von Affilipus Shortcodes anzupassen. Sämtliche werte, die du hier definierst, werden global auf deiner gesamten Webseite wirksam.' ,'imb_affiliate');

				?>
			</p>

			<p style="max-width:600px;">
				<?php

				_e('Du kannst die hier gemachten Einstellungen auch jederzeit im Shortcode selbst überschreiben.' ,'imb_affiliate');

				?>
			</p>

			<form method="post" action="options.php">

				<?php settings_fields( 'imbaf_shortcode_settings' ); ?>
				<?php do_settings_sections( 'imbaf_shortcode_settings' ); ?>

				<?php



				foreach($shortcodes as $shortcode){

					?>


					<table class="wp-list-table widefat striped" style="margin: 10px 0; max-width: 600px;">


						<thead>

						<tr>

							<th colspan="2"><strong><?php echo $shortcode['hr_name']; ?> [<?php echo $shortcode['alias']; ?>]</strong></th>

						</tr>

						</thead>

						<tbody>


						<?php



						foreach($shortcode['params'] as $param_name => $param){

							$param['user_value'] = get_option($param['wp_option']);

							?>

							<tr>

								<td>

									<p> <?php echo $param['description']; ?> </p>

								</td>

								<td width="150">

									<?php

									if($param['type'] == 'select'){

										?>

										<select name="<?php echo $param['wp_option']; ?>" style="width: 100%;">

											<?php

											foreach($param['options'] as $option){



												?> <option value="<?php echo $option['option_value'] ?>"
													<?php

													if( $param['user_value'] != '' ) { if( $option['option_value'] == $param['user_value'] ) { echo "selected"; }}
													else { if( $option['option_value'] == $param['value'] ) { echo "selected"; } }

													?>
												>
													<?php echo $option['option_text']; ?>
												</option>
												<?php

											}

											?>

										</select>

										<?php

									}


									else { ?>

										<input

											type="text" style="width: 100%;"
											placeholder="<?php echo $param['value']; ?>"
											name="<?php echo $param['wp_option']; ?>"
											value="<?php echo $param['user_value']; ?>">

									<?php }

									?>






								</td>

							</tr>

							<?php

						}


						?>

						</tbody>


					</table>




					<?php

				}


				submit_button();

				?>



			</form>

		</div>

		<?php



	}

	function page_feedback(){


		?>


		<div class="wrap">

			<h1>Affilipus Feedback</h1>
			<p style="width: 100%; max-width: 800px;">Vielen Dank, dass du Affilipus testest! Wir wollen ein Plugin schreiben, das deinen Anforderungen entspricht und sind hierfür auf dein Feedback angewiesen.
				Sag uns wie du Affilipus findest. Wenn dir Fehler auffallen, du Probleme mit dem Plugin oder Anregungen für die Weiterentwicklung hast, dann schreib uns. Bitte überprüfe, bevor du einen Fehler meldest, ob deine Installation die <a href="<?php echo IMBAF_PLUGIN_URL.'debug.php'; ?>">Mindestanforderungen</a> erfüllt.</p>


			<form method="post">


				<?php

				if(isset($_POST['send'])){

					$send = 1;

					if(!isset($_POST['betreff']) || $_POST['betreff'] == ''){


						?>

						<div class="notice notice-error">
							<p>Bitte fülle das Feld „Betreff" aus.</p>
						</div>

						<?php

						$send = 0;

					}

					if(!isset($_POST['text']) || $_POST['text'] == ''){

						?>

						<div class="notice notice-error">
							<p>Bitte fülle das Feld „Nachricht" aus.</p>
						</div>

						<?php

						$send = 0;

					}

					if(!isset($_POST['email']) || $_POST['email'] == ''){

						?>

						<div class="notice notice-error">
							<p>Bitte fülle das Feld „E-Mail" aus.</p>
						</div>

						<?php

						$send = 0;

					}


					if($send == 1){


						$_POST['text'].="\r\n\r\n".'eMail: '.$_POST['email'].' Affilipuis Version: '.IMBAF_VERSION;
						$_POST['text'] .= "\r\n\r\n Debug Info:".IMBAF_PLUGIN_URL.'debug.php?key='.get_option('imbaf_license_key');

						if(wp_mail ('info@affilipus.com', 'AFP Feedback: '.$_POST['betreff'], $_POST['text'])){

							?>

							<div class="notice notice-success">
								<p>Danke für dein Feedback!</p>
							</div>

							<?php

							$_POST['betreff'] = null;
							$_POST['text'] = null;

						} else {

							?>

							<div class="notice notice-error">
								<p>Deine Nachricht konnte leider nicht verschickt werden. Schreib uns doch direkt an <a href="mailto:info@affilipus.com">info@affilipus.com</a></p>
							</div>

							<?php

						}




					}

				}


				?>




				<table class="wp-list-table widefat striped" style="width: 100%; max-width: 800px;">


					<tr>

						<td>E-Mail Adresse</td>
						<td><input type="email" name="email" placeholder="Deine E-Mail Adresse" value="<?php if(isset($_POST['email'])){ echo $_POST['email']; } ?>"></td>

					</tr>


					<tr>

						<th style="width:100px; vertical-align:top;"><label for="imbaf_feedback_betreff">Betreff</label></th>
						<td><input style="width: 100%;" type="text" id="imbaf_feedback_betreff" name="betreff" value="<?php if(isset($_POST['betreff'])){ echo $_POST['betreff']; } ?>"></td>

					</tr>

					<tr>

						<th style="width:100px; vertical-align:top;"><label for="imbaf_feedback_betreff">Nachricht</label></th>
						<td><textarea id="imbaf_feedback_text" name="text" rows="10" style="width:100%;"><?php if(isset($_POST['text'])){ echo $_POST['text']; } ?></textarea></td>
					</tr>

					<tr>

						<td colspan="2" style="text-align:right;"><input type="submit" class="button button-primary" name="send" value="Feedback senden"></td>

					</tr>


				</table>





			</form>

		</div>

		<?php


		


	}

	function page_common_settings() {


		?>

		<div class="wrap">

			<h1><?php _e('Affilipus Einstellungen','imb_affiliate'); ?></h1>


			<h2><?php _e('Produktseiten','imb_affiliate'); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'imbaf_settings' ); ?>
				<?php do_settings_sections( 'imbaf_settings' ); ?>

				<table class="form-table" style="max-width: 800px;">

					<tbody>

					<tr>

						<th scope="row">

                            <label><?php _e('Affilipus Produktseiten','imb_affiliate'); ?></label>

                        </th>
						<td>

							<input type="checkbox" name="imbaf_enable_product_pages" <?php if ( get_option( 'imbaf_enable_product_pages' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Produktseiten sind spezielle Unterseiten, 
                            die für jedes Unterprodukt angelegt werden. Diese können auch 
                            von Suchmaschinen erfasst werden. Wenn du das nicht möchtest, 
                            kannst du Produktseiten deaktivieren. Wenn du Produktseiten deaktivierst, deaktivierst du auch die Archivseiten für Affilipus Marken, Affilipus Typen und Affilipus Tags.','_img_affiliate'); ?></p>

                        </td>

					</tr>


					<tr>

						<th scope="row">

							<label><?php _e('Produktbilder als Beitragsbild importieren','imb_affiliate'); ?></label>

						</th>
						<td>

							<input type="checkbox" name="imbaf_import_post_thumbnails" <?php if ( get_option( 'imbaf_import_post_thumbnails' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Wenn diese Option aktiviert ist, wird ein Produktbild beim Import als Beitragsbild für deine Produktseite gesetzt.','imb_affiliate'); ?></p>

                        </td>

					</tr>

					<tr>

						<th scope="row">

							<label><?php _e('Produktbilder als Produktbild importieren','imb_affiliate'); ?></label>


						</th>
						<td>

							<input type="checkbox" name="imbaf_import_product_pictures" <?php if ( get_option( 'imbaf_import_product_pictures' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Wenn diese Option aktiviert ist, wird ein Produktbild beim Import als Affilipus Produktbild gesetzt'); ?></p>


                        </td>

					</tr>


					<?php if(get_option( 'imbaf_enable_product_pages' ) == 1){ ?>

						<tr>

							<th scope="row">
								<label name="imbaf_products_slug"><?php _e('Produktseiten im WordPress Loop anzeigen','imb_affiliate'); ?> <strong style="color:red;">(<?php _e('experimentell','imb_affiliate'); ?>)</strong></label>


							</th>
							<td>

								<input type="checkbox"
								       name="imbaf_display_products_in_loop"
									<?php if ( get_option( 'imbaf_display_products_in_loop' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                                <p class="description"><?php _e('Wähle aus, ob Produktseiten innerhalb des WordPress Post-Loops eingebunden werden sollen, oder nicht.','imb_affiliate'); ?></p>
                                <p class="description"><strong><?php _e('Diese Funktion ist stark Theme-Abhängig. Bitte benutze sie vorsichtig.','imb_affiliate'); ?></strong></p>

							</td>

						</tr>

						<tr>

							<th scope="row"><label name="imbaf_products_slug"><?php _e('Slug für Produkte','imb_affiliate'); ?></label></th>
							<td>
								<input
									type="text"
									name="imbaf_products_slug"
									class="widefat"
									value="<?php echo get_option( 'imbaf_products_slug' ); ?>"
									placeholder="imbafproducts">
							</td>

						</tr>

						<tr>

							<th scope="row">

                                <label ><?php _e('Slug für Marken','imb_affiliate'); ?></label>

                            </th>
							<td>
								<input
									type="text"
									name="imbaf_brands_slug"
									class="widefat"
									value="<?php echo get_option( 'imbaf_brands_slug' ); ?>"
									placeholder = "imbafbrands"
								>
							</td>

						</tr>

						<tr>

							<th scope="row">

                                <label><?php _e('Slug für Produkt Typen','imb_affiliate'); ?></label>

                            </th>
							<td>
								<input
									type="text"
									name="imbaf_types_slug"
									class="widefat"
									value="<?php echo get_option( 'imbaf_types_slug' ); ?>"
									placeholder="imbaftypes"
								>
							</td>

						</tr>

						<tr>

							<th scope="row">

                                <label><?php _e('Slug für Produkt Tags','imb_affiliate'); ?></label>

                            </th>
							<td>
								<input
									type="text"
									name="imbaf_tags_slug"
									class="widefat"
									value="<?php echo get_option( 'imbaf_tags_slug' ); ?>"
									placeholder="imbaftags"
								>
							</td>

						</tr>

					<?php } ?>


					</tbody>

				</table>



                <h2><?php _e('Performance','imb_affiliate'); ?></h2>


                <table class="form-table">


                    <tr>

                        <th scope="row">

                            <label><?php _e('Smarty Caching','imb_affiliate'); ?></label>

                        </th>
                        <td>

                            <input type="checkbox" name="imbaf_enable_smarty_caching" <?php if ( get_option( 'imbaf_enable_smarty_caching' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Smarty Template Caching aktivieren','imb_affiliate'); ?></p>

                        </td>

                    </tr>


                    <tr>

                        <th sope="row">

                            <label><?php _e('Cache Lifetime','imb_affiliate'); ?></label>

                        </th>
                        <td>

                            <input type="text" name="imbaf_smarty_cache_lifetime" placeholder="30" value="<?php echo get_option('imbaf_smarty_cache_lifetime'); ?>"> <?php _e('Minuten','imb_affiliate');?>
                            <p class="description"><?php _e('Maximale Cache Zeit für Smarty Templates','imb_affiliate'); ?></p>

                        </td>

                    </tr>

                </table>

				<h2><?php _e('Ausgabe Einstellungen','imb_affiliate'); ?></h2>


				<table class="form-table" style="max-width:800px;">


					<tr>

						<th scope="row">
							<label><?php _e('Streichpreise zulassen','imb_affiliate'); ?></label>
						</th>
						<td>
							<input type="checkbox" name="imbaf_enable_cross_price" <?php if ( get_option( 'imbaf_enable_cross_price' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Streichpreise aktivieren oder deaktivieren.','imb_affiliate'); ?></p>


						</td>

					</tr>

					<tr>

						<th scope="row">
							<label><?php _e('CDN-Bilder bevorzugen','imb_affiliate'); ?></label>
						</th>
						<td>
							<input type="checkbox" name="imbaf_prefer_cdn_pictures" <?php if ( get_option( 'imbaf_prefer_cdn_pictures' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>

                            <p class="description"><?php _e('CDN-Bilder werden präferiert ausgegeben. 
                            Wird diese Funktion deaktiviert, werden Beitragsbilder der jeweiligen Produkte verwendet. 
                            Sollte ein Produktbild gewählt worden sein, wird dieses bevorzugt ausgeliefert.','imb_affiliate'); ?></p>

						</td>

					</tr>

					<tr>

						<th scope="row">
							<label><?php _e('Affilipus Shortcodes in Kommentaren','imb_affiliate'); ?></label>
						</th>
						<td>
							<input type="checkbox" name="imbaf_execute_shortcodes_in_comments"
								<?php if ( get_option( 'imbaf_execute_shortcodes_in_comments' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>


                            <p class="description"><?php _e('Ermöglicht die Ausführung von Affilipus Shortcodes innerhalb von Kommentaren. Bitte nur mit Vorsicht benutzen, da so jeder Benutzer die Shortcodes in den Kommentaren ausführen kann.','imb_affiliate'); ?></p>



                        </td>

					</tr>


					<tr>

						<th scope="row">

							<label><?php _e('Affilipus Stylesheets','imb_affiliate'); ?></label>

						</th>
						<td>

							<input type="checkbox" name="imbaf_display_styles" <?php if ( get_option( 'imbaf_display_styles' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Standard Styles aktivieren (empfohlen)','imb_affiliate'); ?></p>

                        </td>

					</tr>

					<tr>

						<th scope="row">

							<label><?php _e('Google Fonts','imb_affiliate'); ?></label>

						</th>
						<td>

							<input type="checkbox" name="imbaf_load_google_fonts" <?php if ( get_option( 'imbaf_load_google_fonts' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Von Affilipus benötigte Fonts laden (empfohlen)','imb_affiliate'); ?></p>

                        </td>

					</tr>

					<tr>

						<th scope="row">

							<label><?php _e('Schatten aktivieren','imb_affiliate'); ?></label>

						</th>

						<td>
							<input type="checkbox"
							       name="imbaf_display_shadows" <?php if ( get_option( 'imbaf_display_shadows' ) == 1 ) { echo 'checked'; } ?> value="1"> <?php _e('aktivieren','imb_affiliate'); ?>
                            <p class="description"><?php _e('Affilipus Shatteneffekte aktivieren (empfohlen, sieht einfach besser aus)','imb_affiliate'); ?></p>
						</td>

					</tr>

				</table>

				<?php submit_button(); ?>


			</form>

		</div>

		<?php

	}

	function page_template_editor() {


		echo "<div class='wrap'>";

		$editor = new imbafTemplates();

		$editor->template_editor();

		echo "</div>";

	}

	function page_template_settings() { ?>
		<div class="wrap">

			<h1>Affilipus Standard Template</h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'imbaf_defaults' ); ?>
				<?php do_settings_sections( 'imbaf_defaults' ); ?>

				<table style="width:100%;">

					<tr>
						<td style="width:50%;">

							<?php


							$value = get_option( 'imbaf_default_template' );

							wp_editor( htmlspecialchars_decode( $value ), 'mettaabox_ID_stylee', $settings = array( 'textarea_name' => 'imbaf_default_template' ) );


							?>

						</td>
						<td style="vertical-align:top;">


							<div style="padding: 0 10px;" id="imbaf_template_helper">

								<h1>Affilipus Standard Template</h1>

								<p>Hier kannst du die Grundausgabe für die Affilipus Produkte steuern. Sämtliche
									Inhalte, die du in dieses Template einträgst, werden auf jeder Produktseite
									automatisch ausgegeben.</p>

								<p>Mit dem Shortcode [affilipus_user_description] wird die individuelle
									Produktbeschreibung ausgegeben, die du für jedes Hauptprodukt angeben kannst.</p>

								<input style="width:100%;" type="text" value="[affilipus_user_description]" readonly>

								<p>Mit dem Shortcode [affilipus_feature_list] werden die Features, die einem
									Hauptprodukt zugeordnet wurden als Liste ausgegeben.</p>

								<input style="width:100%;" type="text" value='[affilipus_feature_list title="Features"]'
								       readonly>

								<p>Mit dem Shortcode [affilipus_price_list] werden die Preise aller Produkte mit
									Reflinks ausgegeben.</p>

								<input style="width:100%;" type="text" value="[affilipus_price_list]" readonly>

								<p>Mit dem Shortcode [affilipus_default_description] holst du dir die
									Standard-Beschreibung zum Produkt.</p>

								<input style="width:100%;" type="text"
								       value="[affilipus_default_description title = 'Standard Beschreibung']" readonly>

								<p>Mit dem Shortcode [affilipus_product_box] holst du dir die
									Produktbox zum Produkt.</p>

								<input style="width:100%;" type="text"
								       value="[affilipus_product_box]" readonly>

							</div>

							<script>


								jQuery("#imbaf_template_helper input[type='text']").on("click", function () {
									jQuery(this).select();
								});

							</script>


						</td>
					</tr>

				</table>

				<?php submit_button(); ?>

			</form>
		</div>


		<?php

	}

	function page_license_settings() {


		$api = new CORE\API\affilipusAPI();

		if ( isset( $_GET['action'] ) ) {

			switch ( $_GET['action'] ) {

				case 'activate':

					$api->activateLicense();

					break;

				case 'deactivate':

					$api->deactivateLicense();

					break;

			}
		}


		$api->allowAction( true );

		?>


		<div class="wrap">
			<h1>Affilipus Lizenz</h1>


			<form method="post" action="options.php">
				<?php settings_fields( 'imbaf_license' ); ?>
				<?php do_settings_sections( 'imbaf_license' ); ?>
				<?php $license_key = get_option( 'imbaf_license_key' ); ?>


				<?php


				if ( $license_key != '' ) {

					$api = new CORE\API\affilipusAPI();
					$license = $api->checkLicense();

					if(!$license){

					    $license = $api -> checkLicense();

                    }

					if ( $license['license'] != 'invalid' ) {

						?>

						<table class="wp-list-table widefat striped">


							<tr>

								<td style="width: 200px;">Aktuelle Lizenz:</td>
								<td><?php echo $license['item_name']; ?></td>

							</tr>

							<tr>

								<td>Lizenzstatus für diese Seite:</td>
								<td>
									<?php echo $license['license']; ?>

									<?php


									if ( $license['license'] == 'site_inactive' || $license['license'] == 'inactive' ) {


										?>
										<div style="padding: 10px 0;">
											<a href="<?php echo admin_url( 'admin.php?page=imbaf_settings_license&action=activate' ); ?>"
											   title="Lizenz aktivieren" class="button button-primary">Seite aktivieren</a>
										</div> <?php

									}

									?>

									<?php


									if ( $license['license'] == 'valid' || $license['license'] == 'expired' ) {


										?>

										<div style="padding: 10px 0;">
											<a href="<?php echo admin_url( 'admin.php?page=imbaf_settings_license&action=deactivate' ); ?>"
											   title="Lizenz deaktivieren" class="button">Seite deaktivieren</a>
										</div>

										<?php

									}

									?>

								</td>

							</tr>


							<tr>

								<td>Lizenziert von:</td>
								<td><?php echo $license['customer_name']; ?></td>
							</tr>




						</table>


						<?php

					}

				} else {

					update_option( 'imbaf_license_status', null, 1 );

				}

				?>

				<!--<h2>Lizenzschlüssel</h2>
				<input type="text" name="imbaf_license_key" placeholder="Lizenzschlüssel" style="width: 100%;"
				       value="<?php echo $license_key; ?>">-->

                <p>Affilipus ist Open Source Software. Die Eingabe eines Lizenzschlüssels ist nicht mehr notwendig.</p>

                <?php //submit_button(); ?>

			</form>


		</div>


		<?php

	}

	function page_mass_test(){


	    ?>

        <div class="wrap">


            <h1>Daten vorladen</h1>

            <?php


            $cron = new \imbaa\Affilipus\Admin\Config\imbafCron();
            $cron -> preload_affilipus_data(AFP_DEBUG);



            ?>

        </div>

        <?php



    }

	function page_tools() {

		?>

		<div class="wrap">

			<h1>Affilipus Werkzeuge</h1>


			<?php


			if ( isset( $_POST['actions'] ) ) {


				foreach ( $_POST['actions'] as $action => $value ) {


					if ( $value == 'on' ) {


						switch ( $action ) {


							case 'imbaf_flush_cache':

								$meldung = "Alle Affilipus Caches wurden geleert.";
								$type    = 'notice';

								break;

							case 'imbaf_garbage_collection':

								$meldung = 'Garbage collection durchgelaufen.';
								$type    = 'notice';

								break;

							case 'imbaf_delete_images':

								$meldung = 'Das Löschen von Bildern wird derzeit noch nicht untersützt.';
								$type    = 'error';

								break;

						}


						?>


						<div class="updated <?php echo $type; ?>">
							<p><?php echo $meldung ?></p>
						</div>


						<?php

						do_action( $action );

					}

				}

			}


			?>


			<form method="post">

				<table class="form-table">

					<tr>

						<th scope="row">
							<label for="actions[imbaf_flush_cache]">Caches Leeren</label>
						</th>
						<td>
							<input type="checkbox" name="actions[imbaf_flush_cache]">
							<p class="description">Leert alle Caches, die von Affilipus angelegt werden. Zum Beispiel
								„On The Fly" importierte Produkte für Toplisten.</p>
						</td>

					</tr>

					<tr>

						<th scope="row">
							<label for="actions[imbaf_garbage_collection]">Garbage Collection</label>
						</th>
						<td>
							<input type="checkbox" name="actions[imbaf_garbage_collection]">
							<p class="description">Löscht nicht mehr benötigte Daten und fehlerhafte
								Datensätze.<br><strong style="color:red;">Achtung, experimentell! Könnte importierte
									Produkte betreffen.</strong></p>
						</td>

					</tr>

					<tr>

						<th scope="row">
							<label for="actions[imbaf_delete_unused_images]">Bilder löschen</label>
						</th>
						<td>
							<input type="checkbox" name="actions[imbaf_delete_unused_images]" disabled>
							<p class="description">
								Löscht importierte Bilder von Produkten, die nicht mehr benötigt werden.<br>
								<strong style="color:red;">Achtung, experimentell!</strong>
							</p>
						</td>

					</tr>

				</table>


				<input type="submit" class="button button-primary" value="Aktionen durchführen">


			</form>


			<br>

			<h2>Kompatibilitätscheck</h2>


			<a href="<?php echo IMBAF_PLUGIN_URL.'debug.php'; ?>" target="_blank" title="Kompatibilitätscheck aufrufen">Kompatibilitätscheck aufrufen</a>




		</div>

		<?php

	}

	function imbaf_register_admin_settings() {





		$settings = array(


			'imbaf_defaults' => array('imbaf_default_template'),
			'imbaf_license' => array('imbaf_license_key'),
			'imbaf_settings' => array(


				'imbaf_enable_product_pages',
				'imbaf_display_products_in_loop',
				'imbaf_prefer_cdn_pictures',
				'imbaf_enable_cross_price',
				'imbaf_products_slug',
				'imbaf_brands_slug',
				'imbaf_types_slug',
				'imbaf_tags_slug',
				'imbaf_display_styles',
				'imbaf_display_shadows',
				'imbaf_load_google_fonts',
				'imbaf_execute_shortcodes_in_comments',
				'imbaf_enable_smarty_caching',
				'imbaf_smarty_cache_lifetime',
				'imbaf_enable_price_missing_email',
				'imbaf_enable_price_fallback',
				'imbaf_import_product_pictures',
				'imbaf_import_post_thumbnails',


			),





		);


		foreach($settings as $group => $set){


			foreach($set as $option){

				register_setting( $group, $option );

			}



		}








	}

	function imbaf_register_shortcode_settings(){

		$s = new CORE\Output\imbafShortcodes();

		$shortcodes = $s -> get_configurable_shortcodes();

		$settings = ['imbaf_shortcode_settings' => []];


		foreach($shortcodes as $shortcode){


			foreach($shortcode['params'] as $param){


				if(isset($param['wp_option'])){

					array_push($settings['imbaf_shortcode_settings'],$param['wp_option']);

				}

			}

		}




		foreach ( $settings as $group => $set ) {


			foreach ( $set as $option ) {

				register_setting( $group, $option );

			}


		}




	}

	function flush_rewrites( $value ) {


	    set_transient('_imbaf_slugs_changed','yes',3600);

		return $value;

	}

}