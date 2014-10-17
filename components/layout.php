<?php
	class layoutManager{
		var $_user;
		
		function __construct($user){
			$this->_user = $user->columns;
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