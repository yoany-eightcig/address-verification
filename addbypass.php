<?php 

$response = ['status' => false];

if ( count($_GET) && !empty($_GET['email']) && !empty($_GET['address_line1'])) {
	$list = [$_GET['email'], $_GET['address_line1']];

	$file = fopen('bypass.csv','a');  // 'a' for append to file - created if doesn't exit

  	$result = fputcsv($file, $list);
  	if ($result) { 
  		$response['status'] = true;
  	}
	fclose($file); 
}

$json = json_encode($response);

echo $json;

?>