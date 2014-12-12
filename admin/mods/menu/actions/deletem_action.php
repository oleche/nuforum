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
			$id = $_POST['id'];
			if (trim($id) != ""){
				$menu->fetch_id(array('id'=>$id));
				
				if ($menu->err_data == ""){
					$menu->delete();
					if ($menu->err_data == ""){
						$response['message'] = 'Menu Deleted';
						$response['id'] = $id;
						$response['code'] = 0;
						$response['http_code'] = 202;
					}else{
						$response['request'] = $_POST;
						$response['message'] = $menu->err_data;
						$response['code'] = 2;
						$response['http_code'] = 422;
					}
				}else{
					$response['request'] = $_POST;
					$response['message'] = $menu->err_data;
					$response['code'] = 2;
					$response['http_code'] = 422;	
				}
			}else{
				$response['request'] = $_POST;
				$response['message'] = 'Wrong parameter';
				$response['code'] = 2;
				$response['http_code'] = 422;
			}
		}else{
			$response['request'] = $_POST;
			$response['code'] = 1;
			$response['message'] = "Need to log in to save";
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