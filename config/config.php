<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';
	
	$server = "nuforumdevel.db.5672761.hostedresource.com";
	$db_user = "nuforumdevel";
	$db_pass = "g!GGles09";
	$db_database = "nuforumdevel";
	
	$fbappid = "111111111111111";
	$fbsecret = "11111111111111111111111111111111";
	
	$connection = new DataBase($server, $db_user, $db_pass, $db_database);
	
	$col_usuario = array('id', 'name', 'last_name', 'email', 'created_at', 'updated_at', 'enabled', 'password', 'su');
	$key_usuario = array('id');
	$user = new DBManager($connection, 'usuario', $col_usuario, $key_usuario);
	
	$col_category_type = array('id', 'name', 'description', 'created_at', 'num_value', 'enabled');
	$key_category_type = array('id');
	$category_type = new DBManager($connection, 'category_type', $col_category_type, $key_category_type);
	
	$col_post_type = array('id', 'name', 'description', 'num_value', 'enabled');
	$key_post_type = array('id');
	$post_type = new DBManager($connection, 'post_type', $col_post_type, $key_post_type);
	
	$col_menu_type = array('id', 'name', 'base_url', 'sec_level', 'table_related', 'table');
	$key_menu_type = array('id');
	$menu_type = new DBManager($connection, 'menu_type', $col_menu_type, $key_menu_type);
	
	$col_entry_type = array('id', 'name', 'description', 'created_at', 'enabled', 'url');
	$key_entry_type = array('id');
	$entry_type = new DBManager($connection, 'entry_type', $col_entry_type, $key_entry_type);
	
	$col_category = array('id', 'category_type_id', 'name', 'description', 'created_at', 'parent', 'url', 'enabled', 'menu_id');
	$key_category = array('id');
	$foreign_category = array('category_type_id' => array('category_type','id', $category_type),'parent' => array('category','id', DBManager::SELF));
	$category = new DBManager($connection, 'category', $col_category, $key_category, $foreign_category);
	
	$col_entry = array('id', 'created_at', 'updated_at', 'enabled', 'locked', 'user_id', 'category_id', 'title', 'description', 'img_path', 'entry_type_id');
	$key_entry = array('id');
	$foreign_entry = array('user_id' => array('usuario','id', $user),'category_id' => array('category','id', $category),'entry_type_id' => array('entry_type','id', $entry_type));
	$entry = new DBManager($connection, 'entry', $col_entry, $key_entry, $foreign_entry);
	
	$col_post = array('id', 'created_at', 'updated_at', 'post_type_id', 'entry_id', 'usuario_id', 'content', 'flagged', 'enabled', 'locked');
	$key_post = array('id');
	$foreign_post = array('usuario_id' => array('usuario','id', $user),'post_type_id' => array('post_type','id', $post_type), 'entry_id' => array('entry','id', $entries));
	$post = new DBManager($connection, 'post', $col_post, $key_post, $foreign_post);
	
	$col_menu = array('id', 'name', 'description', 'parent', 'menu_type_id', 'created_at', 'link_to', 'sec_level', 'enabled', 'visible', 'done');
	$key_menu = array('id');
	$foreign_menu = array('parent' => array('menu','id', DBManager::SELF),'menu_type_id' => array('menu_type','id', $menu_type));
	$menu = new DBManager($connection, 'menu', $col_menu, $key_menu, $foreign_menu);
	
	
	
?>