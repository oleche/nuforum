<?php
	session_start();
	require_once $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/config/config.php';
	
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