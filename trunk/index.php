<?
/* ===========================

  gelato CMS development version
  http://www.gelatocms.com/

  gelato CMS is a free software licensed under GPL (General public license)

  =========================== */
?>
<?
	// My approach to MVC
	require(dirname(__FILE__)."/config.php");
	include("classes/configuration.class.php");
	include("classes/gelato.class.php");	
	include("classes/templates.class.php");
	include("classes/pagination.php");
	include("classes/user.class.php");
		
	$user = new user();
	$conf = new configuration();
	$tumble = new gelato();
	$template = new plantillas($conf->template);
	
	$param_url = explode("/",$_SERVER['PATH_INFO']);
	
	if (isset($_GET["post"])) {
		$id_post = $_GET["post"];
	} else {
		if (isset($param_url[1]) && $param_url[1]=="post") {
			$id_post = (isset($param_url[2])) ? ((is_numeric($param_url[2])) ? $param_url[2] : NULL) : NULL;
		} else {
			$id_post = NULL;
		}
	}
	
	if (isset($_GET["page"])) {
		$page_num = $_GET["page"];
	} else {
		if (isset($param_url[1]) && $param_url[1]=="page") {
			$page_num = (isset($param_url[2])) ? ((is_numeric($param_url[2])) ? $param_url[2] : NULL) : NULL;
		} else {
			$page_num = NULL;
		}
	}
	
	$input = array("{Title}", "{Description}", "{URL_Tumble}");
	$output = array($conf->title, $conf->description, $conf->urlGelato);
	
	$template->cargarPlantilla($input, $output, "template_header");
	$template->mostrarPlantilla();
	
	if ($user->isAdmin()) {	
		$input = array("{User}", "{URL_Tumble}");
		$output = array($_SESSION["user_login"], $conf->urlGelato);
		
		$template->cargarPlantilla($input, $output, "template_isadmin");
		$template->mostrarPlantilla();
	}
	
	if (!$id_post) {

		$limit=$conf->postLimit;
	
		if(isset($page_num) && is_numeric($page_num) && $page_num>0) { // Is defined the page and is numeric?
			$from = (($page_num-1) * $limit);
		} else {
			$from = 0;
		}
	
		$rs = $tumble->getPosts($limit, $from);

		if ($tumble->contarRegistros()>0) {				
			while($register = mysql_fetch_array($rs)) {			
				$formatedDate = date("M d", strtotime($register["date"]));
				$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
				switch ($tumble->getType($register["id_post"])) {
					case "1":
						$input = array("{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
											
						$template->cargarPlantilla($input, $output, "template_regular_post");
						$template->mostrarPlantilla();
						break;
					case "2":
						$input = array("{Date_Added}", "{Permalink}", "{PhotoURL}", "{PhotoAlt}", "{Caption}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $register["url"], "", $register["description"], $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_photo");
						$template->mostrarPlantilla();							   
						break;
					case "3":
						$input = array("{Date_Added}", "{Permalink}", "{Quote}", "{Source}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $register["description"], $register["title"], $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_quote");
						$template->mostrarPlantilla();
						break;
					case "4":
						$input = array("{Date_Added}", "{Permalink}", "{URL}", "{Name}", "{Description}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $register["url"], $register["title"], $register["description"], $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_url");
						$template->mostrarPlantilla();
						break;
					case "5":
						$input = array("{Date_Added}", "{Permalink}", "{Title}", "{Conversation}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $register["title"], $tumble->formatConversation($register["description"]), $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_conversation");
						$template->mostrarPlantilla();
						break;
					case "6":
						$input = array("{Date_Added}", "{Permalink}", "{Video}", "{Caption}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $tumble->getVideoPlayer($register["url"]), $register["description"], $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_video");
						$template->mostrarPlantilla();
						break;
					case "7":
						$input = array("{Date_Added}", "{Permalink}", "{Mp3}", "{Caption}", "{URL_Tumble}");
						$output = array($formatedDate, $permalink, $tumble->getMp3Player($register["url"]), $register["description"], $conf->urlGelato);
						
						$template->cargarPlantilla($input, $output, "template_mp3");
						$template->mostrarPlantilla();
						break;
				}
			}

			echo pagination($tumble->getPostsNumber(), $limit, isset($page_num) ? $page_num : 1, array($conf->urlGelato."/index.php/page/[...]/","[...]"), 2);


		} else {
			$template->renderizaEtiqueta("No posts in this tumblelog.", "div","error");
		}
	} else {
		$register = $tumble->getPost($id_post);
		
		$formatedDate = date("M d", strtotime($register["date"]));
		$permalink = $conf->urlGelato."/index.php/post/".$register["id_post"]."/";
		switch ($tumble->getType($register["id_post"])) {
			case "1":
				$input = array("{Date_Added}", "{Permalink}", "{Title}", "{Body}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $register["title"], $register["description"], $conf->urlGelato);
									
				$template->cargarPlantilla($input, $output, "template_regular_post");
				$template->mostrarPlantilla();
				break;
			case "2":
				$input = array("{Date_Added}", "{Permalink}", "{PhotoURL}", "{PhotoAlt}", "{Caption}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $register["url"], "", $register["description"], $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_photo");
				$template->mostrarPlantilla();							   
				break;
			case "3":
				$input = array("{Date_Added}", "{Permalink}", "{Quote}", "{Source}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $register["description"], $register["title"], $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_quote");
				$template->mostrarPlantilla();
				break;
			case "4":
				$input = array("{Date_Added}", "{Permalink}", "{URL}", "{Name}", "{Description}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $register["url"], $register["title"], $register["description"], $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_url");
				$template->mostrarPlantilla();
				break;
			case "5":
				$input = array("{Date_Added}", "{Permalink}", "{Title}", "{Conversation}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $register["title"], $tumble->formatConversation($register["description"]), $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_conversation");
				$template->mostrarPlantilla();
				break;
			case "6":
				$input = array("{Date_Added}", "{Permalink}", "{Video}", "{Caption}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $tumble->getVideoPlayer($register["url"]), $register["description"], $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_video");
				$template->mostrarPlantilla();
				break;
			case "7":
				$input = array("{Date_Added}", "{Permalink}", "{Mp3}", "{Caption}", "{URL_Tumble}");
				$output = array($formatedDate, $permalink, $tumble->getMp3Player($register["url"]), $register["description"], $conf->urlGelato);
				
				$template->cargarPlantilla($input, $output, "template_mp3");
				$template->mostrarPlantilla();
				break;
		}
	}
	
	$input = array("{URL_Tumble}");
	$output = array($conf->urlGelato);
	
	$template->cargarPlantilla($input, $output, "template_footer");
	$template->mostrarPlantilla();
?> 