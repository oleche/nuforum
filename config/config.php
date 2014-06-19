<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';
	
	$server = "miquinieladb.db.5672761.hostedresource.com";
	$db_user = "miquinieladb";
	$db_pass = "g!GGles09";
	$db_database = "miquinieladb";
	
	$fbappid = "506910116103918";
	$fbsecret = "95552ffeb010aa2d11907db28be54ba9";
	
	$col_usuario = array('id', 'nombre', 'img_url', 'fecha_creacion', 'qg', 'qp', 'pa', 'rango');
	$key_usuario = array('id');
	
	$col_quiniela = array('id', 'name', 'teams', 'fases', 'groups', 'date', 'public', 'finished', 'template', 'approved', 'tipo', 'wpt', 'usuario_id');
	$key_quiniela = array('id');
	$foreign_quiniela = array('usuario_id' => array('usuario','id'));
	
	//$col_tecnologia = array('id', 'nombre', 'peso', 'habilidad_id');
	//$key_tecnologia = array('id');
	//$foreign_tecnologia = array('habilidad_id' => array('habilidad','id'));
	
	$connection = new DataBase($server, $db_user, $db_pass, $db_database);
	
	$usuario = new DBManager($connection, $server, $db_user, $db_pass, $db_database, 'user', $col_usuario, $key_usuario);
	$quinielabase = new DBManager($connection, $server, $db_user, $db_pass, $db_database, 'quiniela', $col_quiniela, $key_quiniela, $foreign_quiniela);
	//$tecnologia = new DBManager($connection, 'localhost', $db_user, $db_pass, $db_database, 'tecnologia', $col_tecnologia, $key_tecnologia, $foreign_tecnologia);
?>