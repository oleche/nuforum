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
			$name = $_POST['name'];
			$description = $_POST['description'];
			$type = $_POST['type'];
			$url = $_POST['link'];
			if (trim($name) != ""){
				$menu->columns['menu_type_id'] = $type;
				$menu->columns['name'] = $name;
				$menu->columns['description'] = $description;
				$menu->columns['link_to'] = $url;
				$menu->columns['created_at'] = date('Y-m-d H:i:s');
				$menu->columns['sec_level'] = 0;
				$menu->columns['enabled'] = true;
				$menu->columns['visible'] = true;
				$menu->columns['done'] = true;
				$menu->columns['parent'] = null;
				$id = $menu->insert();
				
				$response['message'] = 'Menu Saved';
				$response['id'] = $id;
				//$response['request'][] = $menu->columns;
				$response['code'] = 0;
				$response['http_code'] = 202;
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