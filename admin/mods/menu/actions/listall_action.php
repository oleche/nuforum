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
		if (isset($_SESSION['uid']) && $_SESSION['uid'] != ""){
			$menus = $menu->fetch();
			$response['data'] = array();
			$response["draw"] = 1;
  			$response["recordsTotal"] = count($menus);
  			$response["recordsFiltered"] = count($menus);		
			foreach ($menus as $menu) {
				$response['data'][] = $menu->columns;
			}
			$response['code'] = 0;
			$response['http_code'] = 200;
			
		}else{
			$response['request'] = $_POST;
			$response['code'] = 1;
			$response['message'] = "Need to log in";
			$response['http_code'] = 403;
		}
	
		
	}catch(Exception $e){
		$response['request'] = $_POST;
		$response['message'] = ''.$e->message;
		$response['code'] = 2;
		$response['http_code'] = 403;
	}
	
	$encoded = json_encode($response);
	header('Content-type: application/json');
	http_response_code($response['http_code']);
	exit($encoded);
?>