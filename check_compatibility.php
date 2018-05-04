<?php


function check_imbaf_compatibility(){



	$checks = array(

		array(
			'name' => 'PHP-Version',
			'description' => 'Von Affilipus benötigte PHP-Version',
			'type' => 'version_compare',
			'value' => PHP_VERSION,
			'min' => '5.6',
			'hint' => 'Affilipus benötigt mindestens PHP 5.6 um ordnungsgemäß zu funktionieren. Bitte wende dich an deinen Provider, um die PHP-Version zu aktualisieren.',
			'critical_activation' => true
		),

		array(
			'name' => 'PHP max_execution_time',
			'description' => 'Maximale Skriptausführungszeit',
			'type' => 'number',
			'value' => ini_get('max_execution_time'),
			'value_unit' => 'Sekunden',
			'min' => 30,
			'recommended' => 120,
			'hint' => 'Mit einer längeren Skriptausführungszeit können beispielsweise mehr Produkte pro Durchlauf der Cronjob Skripts aktualisiert werden. Wir empfehlen eine Skriptausführungszeit von mindestens 120 Sekunden.',
			'critical_activation' => false
		),

		array(
			'name' => 'PHP memory_limit',
			'description' => 'Maximal verfügbarer Arbeitsspeicher für WordPress',
			'type' => 'number',
			'value' => ini_get('memory_limit'),
			'value_unit' => ' Megabyte',
			'min' => 64,
			'recommended' => 128,
			'hint' => 'Einige Operationen von Affilipus und WordPress können sehr speicherintensiv sein. Wir empfehlen mindestens 128 Megabyte für die Verarbeitung von Skripten bereitzustellen.',
			'critical_activation' => false
		),


		array(
			'name' => 'WordPress Version',
			'description' => 'Von Affilipus benötigte WordPress-Version',
			'type' => 'version_compare',
			'value' => get_bloginfo( 'version' ),
			'min' => '4.5',
			'hint' => 'Affilipus benötigt mindestens WordPress Version 4.5 um ordnungsgemäß zu funktionieren. Bitte aktualisiere WordPress, bevor du Affilipus aktivierst.',
			'critical_activation' => true
		),

		array(
			'name' => 'Affilipus Content Ordner',
			'description' => 'Affilipus Content Ordner vorhanden und beschreibbar',
			'type' => 'bool',
			'value' => is_writable(IMBAF_CONTENT_FOLDER),
			'hint' => 'Affilipus besitzt keine Schreibrechte auf den Ordner '.IMBAF_CONTENT_FOLDER.' oder der Ordner ist nicht vorhanden. Bitte lege den Ordner über FTP an und vergebe die nötigen Schreibrechte.',
			'critical_activation' => true
		),

		array(
			'name' => 'Affilipus custom_templates Ordner',
			'description' => 'Affilipus custom_templates Ordner vorhanden und beschreibbar',
			'type' => 'bool',
			'value' => is_writable(IMBAF_CUSTOM_TEMPLATES),
			'hint' => 'Affilipus besitzt keine Schreibrechte auf den Ordner '.IMBAF_CUSTOM_TEMPLATES.' oder der Ordner ist nicht vorhanden. Bitte lege den Ordner über FTP an und vergebe die nötigen Schreibrechte.',
			'critical_activation' => true
		),

		array(
			'name' => 'Affilipus templates_c Ordner',
			'description' => 'Affilipus templates_c Ordner vorhanden und beschreibbar',
			'type' => 'bool',
			'value' => is_writable(IMBAF_TEMPLATE_COMPILE_PATH),
			'hint' => 'Affilipus besitzt keine Schreibrechte auf den Ordner '.IMBAF_TEMPLATE_COMPILE_PATH.' oder der Ordner ist nicht vorhanden. Bitte lege den Ordner über FTP an und vergebe die nötigen Schreibrechte.',
			'critical_activation' => true
		),

		array(
			'name' => 'Affilipus templates_cache Ordner',
			'description' => 'Affilipus templates_cache Ordner vorhanden und beschreibbar',
			'type' => 'bool',
			'value' => is_writable(IMBAF_TEMPLATE_CACHE_PATH),
			'hint' => 'Affilipus besitzt keine Schreibrechte auf den Ordner '.IMBAF_TEMPLATE_CACHE_PATH.' oder der Ordner ist nicht vorhanden. Bitte lege den Ordner über FTP an und vergebe die nötigen Schreibrechte.',
			'critical_activation' => true
		),

		array(
			'name' => 'allow_url_fopen',
			'description' => 'PHP kann externe URLs öffnen',
			'type' => 'bool',
			'value' => ini_get( 'allow_url_fopen' ),
			'hint' => '
				Dieser Wert sollte in der php.ini oder .htaccess auf 1 gesetzt werden, damit die Umbenennung der Bilder beim Produktimport funktioniert. 
				Wie genau ihr diesen Wert ändern könnt, erfahrt ihr bei euerem Hoster.
			',
			'hint2' => '
				<h4>Alternative 1: php.ini Einstellung:</h4>
				<pre>allow_url_fopen=on</pre>
				<h4>Alternative 2: .htaccess Einstellung:</h4>
				<pre>php_value allow_url_fopen 1</pre>
			',
			'critical_activation' => true
		),

		array(
			'name' => 'CURL vorhanden',
			'description' => 'Überprüft ob CURL auf dem Server verfügbar ist',
			'type' => 'bool',
			'value' => function_exists( 'curl_version' ),
			'hint' => 'Die für Affilipus benötigte CURL Erweiterung für PHP ist leider nicht installiert. Bitte wende dich an deinen Hosting-Anbieter.',
			'critical_activation' => true
		),
        array(
            'name' => 'SOAP vorhanden',
            'description' => 'Prüft ob SoapClient verfügbar ist',
            'type' => 'bool',
            'value' => class_exists( 'SoapClient' ),
            'hint' => 'Für einige Affiliate Partner wie Affili Net ist ein SoapClient nötig. Bitte wende dich an deinen Hosting-Anbieter.',
            'critical_activation' => false
        ),



	);




	$results = array(


		'critical_activation_errors' => 0,
		'critical_activation_messages' =>array(),
		'errors' => 0,
		'error_messages' =>array(),


	);


	$critical_activation = 0;
	$critical_messages = array();

	foreach($checks as &$check){


		switch($check['type']){

			case 'version_compare': {

				$check['result'] = version_compare( $check['value'], $check['min'], '>' );

			}

			break;

			case 'bool':

				$check['result'] = $check['value'];

			break;

			case 'number':



				if(isset($check['recommended']) && $check['value'] < $check['recommended'] && $check['value'] >= $check['min']){


					$check['result'] = 'improvable';

				} else {

					$check['result'] = $check['value'] >= $check['min'];

				}

				break;

		}

		if($check['result'] == false){

			if($check['critical_activation'] == false){

                $results['errors']++;
                array_push($results['error_messages'],$check['hint']);

            } else {

			    $results['critical_activation_errors']++;
                array_push($results['critical_activation_messages'],$check['hint']);

			}

		}


	}


	$results['checks'] = $checks;

	return $results;



}

