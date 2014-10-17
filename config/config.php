<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';
	
	$server = "nuforumdevel.db.5672761.hostedresource.com";
	$db_user = "nuforumdevel";
	$db_pass = "g!GGles09";
	$db_database = "nuforumdevel";
	
	$fbappid = "111111111111111";
	$fbsecret = "11111111111111111111111111111111";
	
	$col_usuario = array('id', 'name', 'last_name', 'email', 'created_at', 'updated_at', 'enabled', 'password');
	$key_usuario = array('id');
	
	$col_quiniela = array('id', 'name', 'teams', 'fases', 'groups', 'date', 'public', 'finished', 'template', 'approved', 'tipo', 'wpt', 'usuario_id');
	$key_quiniela = array('id');
	$foreign_quiniela = array('usuario_id' => array('usuario','id'));
	
	//$col_tecnologia = array('id', 'nombre', 'peso', 'habilidad_id');
	//$key_tecnologia = array('id');
	//$foreign_tecnologia = array('habilidad_id' => array('habilidad','id'));
	
	$connection = new DataBase($server, $db_user, $db_pass, $db_database);
	
	$user = new DBManager($connection, $server, $db_user, $db_pass, $db_database, 'usuario', $col_usuario, $key_usuario);
	$quinielabase = new DBManager($connection, $server, $db_user, $db_pass, $db_database, 'quiniela', $col_quiniela, $key_quiniela, $foreign_quiniela);
	//$tecnologia = new DBManager($connection, 'localhost', $db_user, $db_pass, $db_database, 'tecnologia', $col_tecnologia, $key_tecnologia, $foreign_tecnologia);
?>