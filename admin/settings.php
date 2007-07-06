<?php
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?php
require_once('../config.php');
include("../classes/functions.php");
include("../classes/user.class.php");
include("../classes/gelato.class.php");
require_once("../classes/configuration.class.php");

$user = new user();
$tumble = new gelato();
$conf = new configuration();

if ($user->isAdmin()) {
	
	if(isset($_POST["btnsubmit"]))	{		
		unset($_POST["btnsubmit"]);
		$_POST["url_installation"] = (endsWith($_POST["url_installation"], "/")) ? substr($_POST["url_installation"], 0, strlen($_POST["url_installation"])-1) : $_POST["url_installation"] ;
		$tumble->saveSettings($_POST);
	} else {
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>gelato :: settings</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="<?php echo $conf->urlGelato;?>/images/favicon.ico" />
		<script language="javascript" type="text/javascript" src="<?php echo $conf->urlGelato;?>/admin/scripts/mootools.js"></script>
		<script type="text/javascript">
		<!--
			window.onload = function() {
				contenedor = new Fx.Style('divMessages', 'opacity', {duration: 5000, onComplete:
					function() {
						document.getElementById('divMessages').style.display="none";
					}
				});
				contenedor.custom(1,0);
			}
		-->
		</script>	
		<style type="text/css" media="screen">	
			@import "<?php echo $conf->urlGelato;?>/admin/css/style.css";
		</style>
	</head>
	
	<body>
		<div id="div-process" style="display:none;">Processing request...</div>
		<div id="cont">
			<div id="head">
				<h1><a href="<?php echo $conf->urlGelato;?>/" title="gelato :: home">gelato cms</a></h1>
				<ul id="nav">
					<li><a href="<?php echo $conf->urlGelato;?>/" title="Take me to the tumblelog">Back to the Tumblelog</a></li>
			  	</ul>
			</div>
			<div id="main">				
				
				<div class="box">
					<ul class="menu manage">
					<h3>Tumblelog configuration</h3>
					<li><a href="index.php">Post</a></li>
					<li><a href="admin.php">Users</a></li>
					<li class="selected"><a>Settings</a></li>
					</ul>
					<p>&nbsp;</p>
<?php
					if (isset($_GET["modified"])) {
						if ($_GET["modified"]=="true") {
							echo "<div class=\"exito\" id=\"divMessages\">The configuration has been modified successfully.</div>";
						}
					}					
					if (isset($_GET["error"])) {
						if ($_GET["error"]==1) {
							echo "<div class=\"error\" id=\"divMessages\"><strong>Error on the database server: </strong>".$_GET["des"]."</div>";
						}
					}
?>
					<div class="tabla">

						<form action="settings.php" method="post" id="settings_form" autocomplete="off" class="newpost">							
							<fieldset>								
								<ul>							
									<li><label for="title">Title:</label>
										<input type="text" name="title" id="title" value="<?php echo $conf->title;?>" class="txt"/></li>
									<li><label for="description">Description:</label>
										<input type="text" name="description" id="description" value="<?php echo $conf->description;?>" class="txt"/></li>
									<li><label for="url_installation">Installation URL</label>
										<input type="text" name="url_installation" id="url_installation" value="<?php echo $conf->urlGelato;?>" class="txt"/></li>
									<li><label for="posts_limit">Post limit:</label>
										<input type="text" name="posts_limit" id="posts_limit" value="<?php echo $conf->postLimit;?>" class="txt"/></li>
									<li><label for="lang">Language:</label>
										<select id="lang" name="lang">
											<option value="en" selected="selected">english</option>
										</select>
									</li>
									<li><label for="template">Template:</label>
										<select id="template" name="template">
<?php
										$themes = getThemes();
										foreach ($themes as $theme) {									
											echo "<option value=\"".$theme."\" selected=\"true\">".$theme."</option>\n";
											
										}
?>
										</select>
									</li>									
									<li><label for="rich_text">Rich text editor:</label>
										<select name="rich_text" id="rich_text">
											<option value="1" <?php if($conf->richText) echo "selected"; ?>>Active</option>
											<option value="0" <?php if(!$conf->richText) echo "selected"; ?>>Deactive</option>
										</select>
									</li>
								</ul>
							</fieldset>
							<p>								
								
								<input type="hidden" name="url_friendly" id="url_friendly" value="0" />								
								<input type="submit" name="btnsubmit" id="btnsubmit" value="Modify" class="submit"/>
							</p>
						</form>	
								
					</div>

					<div class="footer-box">&nbsp;</div>
				</div>
			</div>
			<div id="foot">
				<a href="http://www.gelatocms.com/" title="gelato CMS">gelato CMS</a> :: PHP/MySQL Tumblelog Content Management System.
			</div>
		</div>
	</body>
	</html>
<?php
	}
} else {
	header("Location: ".$conf->urlGelato."/login.php");
}
?>