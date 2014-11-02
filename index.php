<?php
	session_start();
	if (!isset($_SESSION['uid']) || $_SESSION['uid'] == ""){
		header("location:login.php");
	}
	require_once 'config/config.php';
	require_once 'components/layout.php';
	require_once 'components/entries.php';
	
	$uid = $_SESSION['uid'];
	$user->fetch_id(array('id' => $uid));
	$layout = new layoutManager($user);
	$entries = new entriesManager($user, null);
	
?>
<!DOCTYPE html>

<html>
    <head>
        <title></title>
        <meta charset="">
        <link rel="stylesheet" media="screen" href="css/login.css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/entries.css" rel="stylesheet">
		<link rel="shortcut icon" type="img/png" href="img/favicon.png">
        <script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/functions/login.js"></script>
    </head>
    <body>
    	<?php $layout->renderHeader(); ?>
    	<?php $entries->render_all(); ?>
    	<?php $layout->renderFooter(); ?>
	</body>
</html>