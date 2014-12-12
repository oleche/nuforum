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
	$layout = new layoutManager($user, "Menu Administration");
	
	$config = parse_ini_file("menu/config.ini");
	
	$headparams = array(
    	"stylesheet" => array(
    		$config['base_url']."/menu/css/menu.css",
    		"/css/dataTables.bootstrap.css"
		), 
		"script" => array(
    		$config['base_url']."/menu/js/functions/menu.js", 
    		"/js/jquery.dataTables.min.js", 
    		"/js/dataTables.bootstrap.js"
		)
	);
?>
<!DOCTYPE html>

<html>
	<?php $layout->renderHead($headparams) ?>
    <body><div id="wrap">
    	
    	<?php $layout->renderHeader(); ?>
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12">
    				<legend><div class="col-lg-9">Menu Administration</div><div class="col-lg-3">
    					<a href="menu/new.php" class="btn btn-info btn-sm">New Menu</a>
    					<a href="menu/relations.php" class="btn btn-info btn-sm">Relations</a>
    				</div></legend>
    			</div>
    		</div>
    		<div class="col-lg-12" style="padding-top:20px;">
    			<table class="table" id="menu_table">
    				<thead>
    					<tr><th>ID</th><th>Name</th><th>Type</th><th>Link to</th><th>Actions</th></tr>
    				</thead>
    				<tbody>
    				
    				</tbody>
    			</table>
    		</div>
    	</div>
    	<?php $layout->renderFooter(); ?>
    	<!-- Closure of #wrap -->
    	</div>
    	<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="container">
		    <div class="row">
		      <div class="col-sm-6 col-sm-offset-3 text-center">
		        <h1 class="title">Modal with blur effect</h1>
		        <h2 class="sub_title">Put here whatever you want here</h2>
		        <h4 class="message">For instance, a login form or an article content</h4>
		        <h4><kbd>esc</kbd> or click anyway to close</h4>
		        <hr>
		        <div class="button-space"></div>
		      </div>
		    </div>
		  </div>
		</div>
		
	</body>
</html>