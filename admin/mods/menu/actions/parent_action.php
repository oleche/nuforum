<?php
	session_start();
	if (!empty($_SERVER['SUBDOMAIN_DOCUMENT_ROOT'])) {
	  $server_root = $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'];
	}
	elseif (!empty($_SERVER['REAL_DOCUMENT_ROOT'])) {
	  $server_root = $_SERVER['REAL_DOCUMENT_ROOT'];
	}
	else {
	  $server_root = $_SERVER['DOCUMENT_ROOT'];
	}
	
	require_once $server_root.'/config/config.php';
	
	$response = array();
	
	try{
		if (isset($_POST['setmenu'])){
			$at_list = $menu->fetch("parent is null") ;
			if (count($at_list) > 0){
				$response['code'] = 0;
				$response['request'] = array();
				foreach ($at_list as $at_item) {
					$response['request'][] = $at_item->columns;
				}
				$response['http_code'] = 200;
			}else{
				$response['request'] = $_POST;
				$response['message'] = 'Cannot retrieve menu';
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