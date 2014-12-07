<?php
	session_start();
	if (!isset($_SESSION['uid']) || $_SESSION['uid'] == ""){
		header("location:/login.php");
	}
	
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
	require_once $server_root.'/components/layout.php';
	
	$uid = $_SESSION['uid'];
	$user->fetch_id(array('id' => $uid));
	$layout = new layoutManager($user, "New Menu");
	
	$config = parse_ini_file("config.ini");
	
	/**
	 * NEW MENU OPERATION*/
	 
	$types = $menu_type->fetch();
	$type_string = "";
	$first = false;
	foreach ($types as $type) {
		if (!$first){
			$type_string .= '<option data-istable="'.$type->columns['table_related'].'" data-table="'.$type->columns['table'].'" selected="selected" value="'.$type->columns['id'].'">'.$type->columns['name'].'</option>';
			$first = $type;
		}else
			$type_string .= '<option data-istable="'.$type->columns['table_related'].'" data-table="'.$type->columns['table'].'" value="'.$type->columns['id'].'">'.$type->columns['name'].'</option>';
	}
	
?>
<!DOCTYPE html>

<html>
    <?php $layout->renderHead(array(
    	"stylesheet" => array(
    		"stylesheet" => $config['base_url']."/menu/css/menu.css",
		),
    	"script" => array(
				"js/jquery.validate.min.js",
    		$config['base_url']."/menu/js/functions/new-menu.js",
		)
	)) ?>
    <body>
    	<?php $layout->renderHeader(); ?>
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12">
    				<legend><div class="col-lg-10">New Menu</div><div class="col-lg-2"></div></legend>
    			</div>
    		</div>
    		<div class="col-lg-12">
    			<div class="col-sm-5">
		            <h4>Menu Information:</h4>
		            <div class="panel panel-default">
									<form method="post" action="#" id="nm_form" onsubmit="return false">
		                <div class="panel-body form-horizontal payment-form">
		                    <div class="form-group">
		                        <label for="name" class="col-sm-3 control-label">Name</label>
		                        <div class="col-sm-9">
		                            <input type="text" class="form-control" id="name" name="name" required>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label for="description" class="col-sm-3 control-label">Description</label>
		                        <div class="col-sm-9">
		                            <input type="text" class="form-control" id="description" name="description">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label for="type" class="col-sm-3 control-label">Type</label>
		                        <div class="col-sm-9">
		                            <select class="form-control" id="type" name="type" required>
		                                <?php
											echo $type_string;
		                                ?>
		                            </select>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label for="description" class="col-sm-3 control-label">Link to</label>
		                        <div class="col-sm-9">
		                        	<div class="row" id="linktogrp" style="display: none;">
		                        		<div class="col-sm-4">
		                        			<button class="btn btn-sm btn-primary" id="linkto">Select</button>
		                        		</div>
		                        		<div class="col-sm-8 selected-text">Select from list</div>
		                        	</div>

		                            <input type="text" class="form-control" id="link" name="link" required>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-sm-12 text-right">
		                            <button id="save" type="button" class="btn btn-default preview-add-button">
		                                <span class="glyphicon glyphicon-save"></span> Save
		                            </button>
		                        </div>
		                    </div>
		                </div>
									</form>
		            </div>
		        </div>
		        <div class="col-sm-7">
		        	<div class="panel panel-default filled" style="display: none;">
		        		<div class="row filled_content"></div>
	        		</div>
	        	</div>
    		</div>
    	</div>
    	<?php $layout->renderFooter(); ?>
	</body>
</html>
