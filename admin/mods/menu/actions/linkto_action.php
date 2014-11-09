<?php
	session_start();
	require_once $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/config/config.php';
	
	$response = array();
	
	try{
		if (isset($_POST['setlist'])){
			$table = explode(":", $_POST['setlist']);
			$type = (isset($table[1]))?explode("=", $table[1]):null;
			$active_table = null;
			$at_type = null;
			$at_query = "";
			
			switch ($table[0]) {
				case 'category':
					$active_table = $category;
					break;
				case 'entry':
					$active_table = $entry;
					break;
				default:
					$active_table = null;
					break;
			}
			if (count($type) == 2){
				switch ($type[0]) {
					case 'entry_type':
						$at_type = $entry_type;
						break;
					default:
						$at_type = null;
						break;
				}
				$at_query = sprintf("name = '%s'", $type[1]);
			}
			$at_type->fetch_id( array(), null, true, $at_query );
			$at_list = $active_table->fetch_obj_in( array( $at_type ) ) ;
			if (($at_type->err_data == "") && (count($at_list) > 0)){
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