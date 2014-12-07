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
	
	if (isset($_POST['user']) && isset($_POST['pass'])){
		if ($user->fetch_id(array("id"=>$_POST['user']), null, true, sprintf("password = '%s'", md5($_POST['pass'])))){
			$response['code'] = 0;
			$response['user'] = $user->columns['name'].' '.$user->columns['last_name'];
			$response['email'] = $user->columns['email'];
			$response['http_code'] = 200;
			$_SESSION['uid'] = $user->columns['id'];
		}else{
			$response['request'] = $_POST;
			$response['code'] = 1;
			$response['http_code'] = 403;
		}
	}else{
		$response['request'] = $_POST;
		$response['code'] = 1;
		$response['http_code'] = 403;
	}
	
	$encoded = json_encode($response);
	header('Content-type: application/json');
	http_response_code($response['http_code']);
	exit($encoded);
?>