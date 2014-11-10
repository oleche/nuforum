<?php
	session_start();
	require_once $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/config/config.php';
	
	$response = array();
	
	try{
		if (isset($_POST['setmenu'])){
			$at_list = $menu->fetch() ;
			if (count($at_list) > 0){
				$response['code'] = 0;
				$response['request'] = array();
				foreach ($at_list as $at_item) {
					$response['request'][] = $at_item->columns;
				}
				$response['http_code'] = 200;
			}else{
				$response['request'] = $_POST;
				$response['message'] = 'Cannot retrieve: '.$_POST['table'];
				$response['code'] = 2;
				$response['http_code'] = 422;
			}
		}else{
			$response['request'] = $_POST;
			$response['code'] = 1;
			$response['http_code'] = 422;
		}
	
		
	}catch(Exception $e){
		$response['request'] = $_POST;
		$response['message'] = ''.$e->message;
		$response['code'] = 2;
		$response['http_code'] = 422;
	}
	
	$encoded = json_encode($response);
	header('Content-type: application/json');
	http_response_code($response['http_code']);
	exit($encoded);
?>