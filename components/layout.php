<?php
	class layoutManager{
		var $_user;
		var $_title;
		
		function __construct($user, $title){
			$this->_user = $user->columns;
			$this->_title = $title;
		}
		
		function renderHead($params){
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			?>
			<head>
		        <title><?php echo $this->_title; ?></title>
		        <meta charset="">
		        <!-- Basic resources -->
		        <link href="<?php echo $protocol.$_SERVER['SERVER_NAME'] ?>/css/bootstrap.min.css" rel="stylesheet">
				<link rel="shortcut icon" type="img/png" href="<?php echo $protocol.$_SERVER['SERVER_NAME'] ?>/img/favicon.png">
		        <script src="<?php echo $protocol.$_SERVER['SERVER_NAME'] ?>/js/jquery-1.11.0.min.js" type="text/javascript"></script>
				<script src="<?php echo $protocol.$_SERVER['SERVER_NAME'] ?>/js/bootstrap.min.js" type="text/javascript"></script>
				<!-- Specific resources -->
				<?php 
					foreach ($params as $pkey => $param) {
						if (is_array($param)){
							foreach ($param as $rkey => $resource) {
								switch ($pkey) {
									case 'script':
										$type = (!is_numeric($rkey))?$rkey:'text/javascript';
										echo "<script type='$type' src='".$protocol.$_SERVER['SERVER_NAME']."/".$resource."'></script>";
									break;
									case 'stylesheet':
										$rel = (!is_numeric($rkey))?$rkey:'';
										echo "<link rel='$rel' href='".$protocol.$_SERVER['SERVER_NAME']."/".$resource."'/>";
									break;
									case 'metadata':
										echo "<meta name='$rkey' content='$resource'/>";
									break;
									default:
										echo '';
										break;
								}
							}
						}
					}
				?>
		    </head>
			<?php
		}
		
		function renderFooter(){
			if ($this->_user['su']){
				?>
					<div id="footer" class="container">
					    <nav class="navbar navbar-default navbar-fixed-bottom">
					    	<div class="navbar-header">
					          <a href="../" class="navbar-brand">NuForum<span style="font-size:8px; padding-top:-10px;">Platform</span></a>
					        </div>
					        <div class="navbar-collapse collapse" id="navbar-main">
					          <ul class="nav navbar-nav">
					            <li class="dropdown">
					              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropper">Menu Admin <span class="caret"></span></a>
					              <ul class="dropdown-menu" aria-labelledby="dropped">
					                <li><a href="/admin/mods/menu-admin.php">Show Menus</a></li>
					                <li><a href="./">Show Relations</a></li>
					                <li class="divider"></li>
					                <li><a href="./">Create New Menu</a></li>
					              </ul>
					            </li>
					            <li class="dropdown">
					              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropper">Forums Admin <span class="caret"></span></a>
					              <ul class="dropdown-menu" aria-labelledby="dropped">
					                <li><a href="./">Categories</a></li>
					                <li><a href="./">Types</a></li>
					                <li class="divider"></li>
					                <li><a href="./">Create Category</a></li>
					                <li><a href="./">Create Type</a></li>
					              </ul>
					            </li>
					            <li class="dropdown">
					              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropper">Content Admin <span class="caret"></span></a>
					              <ul class="dropdown-menu" aria-labelledby="dropped">
					                <li><a href="./">Posts</a></li>
					                <li><a href="./">Pages</a></li>
					                <li class="divider"></li>
					                <li><a href="./">Create Post</a></li>
					                <li><a href="./">Create Page</a></li>
					              </ul>
					            </li>
					            <li class="dropdown">
					              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropper">Site Admin <span class="caret"></span></a>
					              <ul class="dropdown-menu" aria-labelledby="dropped">
					                <li><a href="./">Users</a></li>
					                <li><a href="./">Roles</a></li>
					                <li><a href="./">Permissions</a></li>
					                <li><a href="./">Configuration</a></li>
					                <li class="divider"></li>
					                <li><a href="./">Create User</a></li>
					                <li><a href="./">Create Permission Set</a></li>
					              </ul>
					            </li>
					          </ul>
					        </div>
					    </nav>
					</div>
				<?
			}
		}
		
		function renderHeader(){
			?>
			<div class="navbar">
		      <div class="container">
		        <div class="navbar-header">
		          <a href="../" class="navbar-brand">NuForum<span style="font-size:8px; padding-top:-10px;">Platform</span></a>
		        </div>
		        <div class="navbar-collapse collapse" id="navbar-main">
		          <ul class="nav navbar-nav">
		            <li>
		              <a href="./">Home<br><span style="font-size:10px; padding-top:-10px;">Discover new stories</span></a>
		            </li>
		            <li>
		              <a href="./">Menu<br><span style="font-size:10px; padding-top:-10px;">Discover new stories</span></a>
		            </li>
		            <li class="dropdown">
		              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="dropper">Dropper <span class="caret"></span><br><span style="font-size:10px; padding-top:-10px;">Discover new stories</span></a>
		              <ul class="dropdown-menu" aria-labelledby="dropped">
		                <li><a href="./">Drop1</a></li>
		                <li class="divider"></li>
		                <li><a href="./">Drop2</a></li>
		              </ul>
		            </li>
		          </ul>
		
		          <ul class="nav navbar-nav navbar-right">
		            <li><a href="http://builtwithbootstrap.com/" target="_blank">Account</a></li>
		            <li><a href="https://wrapbootstrap.com/?ref=bsw" target="_blank">Logout</a></li>
		          </ul>
		
		        </div>
		      </div>
		    </div>
			
			<?
		}
	}
?>