<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="datasets.css">
<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.11.4/jquery-ui.min.css">
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/api.js"></script>
<style>
	body {
		Font-family:Arial;
	}
	table {
		border-collapse:collapse;
	}
	th, td {
		border:1px solid black;
		padding:2px;
		font-size:x-small;
	}
	caption {
		text-align:left;
	}
</style>
</head>
<body>
<table>
	<caption>
		Unidades registadas no servidor de produção SI-MA
	</caption>
	<thead>
<?php

$localhosts = array(
    '127.0.0.1',
    'localhost',
	'::1'
);

if(in_array($_SERVER['REMOTE_ADDR'], $localhosts)) {
ini_set('display_errors', '1');
error_reporting(E_ALL | E_STRICT);
}

$date = date('Y-m-d');
if ( check_get('debug') ) { $debug = true; } else { $debug = false; }

$ini_array = parse_ini_file('../../cron/si-ma.ini');
$url_data = $ini_array['orgunits_main'];
$post_string_b64 = base64_encode($ini_array['user_64_main']);
$auth = 'Authorization: Basic '.$post_string_b64;
$ch = curl_init($url_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth));
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$html = curl_exec($ch);
if(!curl_errno($ch)) {
	$info = curl_getinfo($ch);
	$http_code =  $info['http_code'];
	$total_time = $info['total_time'];
	if ($debug) {
		echo $http_code;
		echo '<br />';
		echo $total_time;
		echo '<br />';
	}
	$pattern = '/\{(.+)\}/';
	preg_match($pattern, $html, $matches);
	$json = json_decode($matches[0], true);
	$arr = $json['organisationUnits'];	
	$keys = [];
	foreach($arr[0] as $key => $val) { $keys[] = $key; }

	echo '<tr><th></th><th>' . implode('</th><th>', $keys) . '</th></tr>';
	echo '</thead><tbody>';

	$iorg = 1;
	foreach($arr as $e) {
		echo '<tr>';
		echo '<td>'.$iorg.'</td>';
		for ($i = 0; $i < count($keys); $i++) {
			if ( array_key_exists($keys[$i],$e) ) {
				if ($keys[$i]=='href') {
					echo '<td><a target="_blank" href="'.$e[$keys[$i]].'">Link</a></td>';
				} else {
					echo '<td>'.$e[$keys[$i]].'</td>';
				}
			} else {
				echo '<td style="background-color:lightgrey;"></td>';
			}
		}
		echo '</tr>';
		$iorg++;
	}

} else {
	echo '<tr><td colspan="5" style="font-weight:bold;font-size:large;">O servidor não responde, tente mais tarde.</td></tr>';
}
curl_close($ch);
//----------------------------------------------------------------------------------------
function check_get ($var) {
	if(!isset($_GET[$var])) { return false; }
	if($_GET[$var] === '') { return false; }
	if($_GET[$var] === false) { return false; }
	if($_GET[$var] === null) { return false; }
	if(empty($_GET[$var])) { return false; }
	return true;
}
//----------------------------------------------------------------------------------------
?>
</tbody>
</table>
</body>
</html>


