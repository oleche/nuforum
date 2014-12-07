<?php
	session_start();
	if (!isset($_SESSION['uid']) || $_SESSION['uid'] == ""){
		header("location:login.php");
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
	
	$menus = $menu->fetch();
?>
<!DOCTYPE html>

<html>
    <?php $layout->renderHead(array(
    	"stylesheet" => array(
    		"stylesheet" => $config['base_url']."/menu/css/menu.css",
    		"stylesheet" => "/css/dataTables.bootstrap.css"
		), 
    	"script" => array(
    		$config['base_url']."/menu/js/functions/menu.js", 
    		"/js/jquery.dataTables.min.js", 
    		"/js/dataTables.bootstrap.js"
		)
	)) ?>
    <body>
    	<?php $layout->renderHeader(); ?>
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-12">
    				<legend><div class="col-lg-10">Menu Administration</div><div class="col-lg-2"><a href="menu/new.php" class="btn btn-info btn-sm">New Menu</a></div></legend>
    			</div>
    		</div>
    		<div class="col-lg-12">
    			<table class="table" id="menu_table">
    				<tr><th>Name</th><th>Parent</th><th>Link to</th><th>Actions</th></tr>
    				<?php
    					if (count($menus) == 0){
    						?><tr><td colspan="4" align="center">Not a single item created</td></tr><?php
    					}else{
    						foreach ($menus as $m) {
								?><tr>
									<td><?php echo $m->columns['name'] ?></td>
									<td><?php echo $m->columns['parent'] ?></td>
									<td><?php echo $m->columns['link_to'] ?></td>
									<td></td>
								</tr><?php
							}
    					}
    				?>
    			</table>
    		</div>
    	</div>
    	<?php $layout->renderFooter(); ?>
	</body>
</html>