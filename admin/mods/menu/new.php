<?php
	session_start();
	if (!isset($_SESSION['uid']) || $_SESSION['uid'] == ""){
		header("location:/login.php");
	}
	
	require_once $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/config/config.php';
	require_once $_SERVER['SUBDOMAIN_DOCUMENT_ROOT'].'/components/layout.php';
	
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
		                <div class="panel-body form-horizontal payment-form">
		                    <div class="form-group">
		                        <label for="name" class="col-sm-3 control-label">Name</label>
		                        <div class="col-sm-9">
		                            <input type="text" class="form-control" id="name" name="name">
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
		                            <select class="form-control" id="type" name="type">
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
		                        		<div class="col-sm-8">Select from list</div>
		                        	</div>
		                        	
		                            <input type="text" class="form-control" id="link" name="link">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label for="description" class="col-sm-3 control-label">Parent</label>
		                        <div class="col-sm-9">
		                        	<div class="col-sm-12 text-right">
		                        		<button class="btn btn-sm btn-danger">None</button>
		                        		<button class="btn btn-sm btn-primary">Select</button>
		                        	</div>
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <div class="col-sm-12 text-right">
		                            <button type="button" class="btn btn-default preview-add-button">
		                                <span class="glyphicon glyphicon-save"></span> Save
		                            </button>
		                        </div>
		                    </div>
		                </div>
		            </div>            
		        </div>
		        <div class="col-sm-7">
		        	<div class="panel panel-default filled" style="display: none;">
	        		</div>
	        	</div>
    		</div>
    	</div>
    	<?php $layout->renderFooter(); ?>
	</body>
</html>