<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="UTF-8">
	<title>Affilipus Debug Helper</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>



<?php


require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

require_once('constants.php');
require_once('check_compatibility.php');


$afp_key = get_option('imbaf_license_key');
if(isset($_GET['key'])){

	$key = $_GET['key'];

} else {

	$key = false;

}




if( !current_user_can( 'manage_options' )) {

	if($key != $afp_key){

		die('<p>Diese Seite ist nur für eingeloggte Admins sichtbar.</p>');

	}



}

$data = check_imbaf_compatibility();

?>

<div class="container">
	<h1>Affilipus Debug Helper</h1>
	<p>Dieses Skript überprüft, ob du alle Voraussetzungen für die Nutzung von Affilipus erfüllst.</p>
<table class="table" style="max-width:800px;">

	<thead>
		<tr>

			<th>Test</th>
			<th style="width: 150px;">Ergebnis</th>

		</tr>
	</thead>


	<tbody>
	<?php foreach($data['checks'] as $check){ ?>


		<tr>

			<td>


				<strong><?php echo $check['name']; ?></strong>
				<p><?php echo $check['description'] ?></p>


				<?php if($check['result'] == false || $check['result'] === 'improvable'){ ?>

				<p><?php echo $check['hint']; ?></p>

					<?php

					if(isset($check['hint2'])){

						echo $check['hint2'];

					}

					?>

				<?php } ?>

			</td>
			<td>

				<?php


				if($check['result'] === 'improvable'){
					echo "<strong style='color: orange;'>akzeptabel</strong>";

					if(isset($check['value_unit'])){


						echo "<div>{$check['value']} {$check['value_unit']}</div>";

					}

				}

				else  if($check['result'] == true){

						echo "<strong style='color: green;'>bestanden</strong>";

					}
					else{

						echo "<strong style='color: red;'>nicht bestanden</strong>";
					}

				?>

			</td>

		</tr>

	<?php } ?>
	</tbody>



</table>

</div>
</body>
</html>
