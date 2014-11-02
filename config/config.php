<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';
	
	$server = "nuforumdevel.db.5672761.hostedresource.com";
	$db_user = "nuforumdevel";
	$db_pass = "g!GGles09";
	$db_database = "nuforumdevel";
	
	$fbappid = "111111111111111";
	$fbsecret = "11111111111111111111111111111111";
	
	$col_usuario = array('id', 'name', 'last_name', 'email', 'created_at', 'updated_at', 'enabled', 'password', 'su');
	$key_usuario = array('id');
	
	$col_category_type = array('id', 'name', 'description', 'created_at', 'num_value', 'enabled');
	$key_category_type = array('id');
	$col_post_type = array('id', 'name', 'description', 'num_value', 'enabled');
	$key_post_type = array('id');
	$col_menu_type = array('id', 'name', 'base_url', 'sec_level', 'table_related', 'table');
	$key_menu_type = array('id');
	
	$col_post = array('id', 'created_at', 'updated_at', 'post_type_id', 'entry_id', 'usuario_id', 'content', 'flagged', 'enabled', 'locked');
	$key_post = array('id');
	$foreign_post = array('usuario_id' => array('usuario','id'),'post_type_id' => array('post_type','id'), 'entry_id' => array('entry','id'));
	
	$col_category = array('id', 'category_type_id', 'name', 'description', 'created_at', 'parent', 'link_url', 'enabled', 'menu_id');
	$key_category = array('id');
	$foreign_category = array('category_type_id' => array('category_type','id'),'parent' => array('category','id'));
	
	$col_entry = array('id', 'created_at', 'updated_at', 'enabled', 'locked', 'user_id', 'category_id', 'title', 'description', 'img_path');
	$key_entry = array('id');
	$foreign_entry = array('user_id' => array('usuario','id'),'category_id' => array('category','id'));
	
	$col_menu = array('id', 'name', 'description', 'parent', 'menu_type_id', 'created_at', 'link_to', 'sec_level', 'enabled', 'visible', 'done');
	$key_menu = array('id');
	$foreign_menu = array('parent' => array('menu','id'),'menu_type_id' => array('menu_type','id'));
	
	$connection = new DataBase($server, $db_user, $db_pass, $db_database);
	
	$user = new DBManager($connection, 'usuario', $col_usuario, $key_usuario);
	$category_type = new DBManager($connection, 'category_type', $col_category_type, $key_category_type);
	$post_type = new DBManager($connection, 'post_type', $col_post_type, $key_post_type);
	$post = new DBManager($connection, 'post', $col_post, $key_post, $foreign_post);
	$category = new DBManager($connection, 'category', $col_category, $key_category, $foreign_category);
	$entry = new DBManager($connection, 'entry', $col_entry, $key_entry, $foreign_entry);
	$menu = new DBManager($connection, 'entry', $col_menu, $key_menu, $foreign_menu);
	$menu_type = new DBManager($connection, 'menu_type', $col_menu_type, $key_menu_type);
?>