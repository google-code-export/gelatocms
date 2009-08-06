<?php

class index_controller extends appcontroller{
	public $configuration = Array();
	public $options = Array();
	
	public function __construct(){
		parent::__construct();
		
		$Configuration = new configuration();
		$this->configuration = $Configuration->find(1);
		
		$Option = new option();
		$options = $Option->findAll();
		foreach($options as $option){
			$this->options[$option["name"]] = $option["val"];
		}
		
		$this->themes->conf = $this->configuration;
		$this->themes->option = $this->options;
	}
	
	public function index($id_post = null){
		if(!is_null($id_post) and is_numeric($id_post)){
			$id_post = (int) $id_post;
		}else{
			$id_post = false;
		}
		
		$this->html->useTheme($this->configuration["template"]);
		
		$includes = $this->html->charsetTag("utf-8");
		
		$Entry = new entry();
		$entries = $Entry->findAll();
		
		foreach($entries as $k=>$entry){
			$entries[$k]["postType"] = utils::type2Text($entry["type"]);
			$entries[$k]["formatedDate"] = $entry["date"];
			$entries[$k]["Date_Added"] = $entry["created"];
			
			$User = new user();
			$user = $User->find($entry["id_user"]);
			$entries[$k]["User"] = $user["name"];
			
			if($entries[$k]["postType"]=="post"){//1
				$entries[$k]["Title"] = $entry["title"];
				$entries[$k]["Body"] = $entry["description"];
			}else if($entries[$k]["postType"]=="photo"){//2
				$entries[$k]['PhotoURL'] = $this->path."index/getImage/".$entry["id_post"];
				$entries[$k]['PhotoAlt'] = strip_tags($entry["description"]);
				$entries[$k]['Caption'] = $entry["description"];
			}else if($entries[$k]["postType"]=="quote"){//3
			
			}else if($entries[$k]["postType"]=="url"){//4
			
			}else if($entries[$k]["postType"]=="conversation"){//5
			
			}else if($entries[$k]["postType"]=="video"){//6
			
			}else if($entries[$k]["postType"]=="mp3"){//6
			
			}
		}
		
		
		$this->themes->id_post = $id_post;
		$this->themes->Gelato_includes = $includes;
		$this->themes->error = false;
		$this->themes->rows = $entries;
		
		$this->render();
	}
	
	public function getImage($id_post = null){
		if(is_null($id_post) or $id_post<1){
			$this->redirect("index");
		}
		
		$Entry = new entry();
		$entry = $Entry->find($id_post);
		
		if(utils::type2text($entry["type"]) != "photo"){
			$this->redirect("index");
		}
		
		header ("Content-type: image/jpeg");
		header('Cache-Control: max-age=172800, must-revalidate');
		header('Expires: ' . date('r', time()+120));
		
		$img = Absolute_Path."app".DIRSEP."uploads".DIRSEP.$entry["url"];
		
		//Default values
		if(isset($_GET["w"]) and in_array($_GET["w"],array("500","1024"))){
			$w = $_GET["w"];
		}else{
			$w = 500;
		}
		
		$cached_file = Absolute_Path."app".DIRSEP."uploads".DIRSEP."cache".DIRSEP."w".$w.$entry["url"];
		
		$percent = null;
		$constrain = null;
		$h = null;
		
		//First validation
		//if file exists in cache, we use it.
		if(file_exists($cached_file)){
			$x = @getimagesize($cached_file);
			if($x[0]==$w){
				readfile($cached_file);
			}
		}
		
		/*
		JPEG / PNG Image Resizer

		img = path / url of jpeg or png image file

		percent = if this is defined, image is resized by it's
				  value in percent (i.e. 50 to divide by 50 percent)

		w = image width

		h = image height

		constrain = if this is parameter is passed and w and h are set
					to a size value then the size of the resulting image
					is constrained by whichever dimension is smaller

		Requires the PHP GD Extension

		Outputs the resulting image in JPEG Format

		By: Michael John G. Lopez - www.sydel.net
		Filename : imgsize.php
		*/

		$ext=substr($img, -3);
		if ($ext != "jpg" && $ext !="gif" && $ext !="png") {
			die("This is not a valid image file!!");
		}

		// get image size of img
		$x = @getimagesize($img);
		
		// image width
		$sw = $x[0];
		// image height
		$sh = $x[1];

		if ($percent > 0) {
			// calculate resized height and width if percent is defined
			$percent = $percent * 0.01;
			$w = $sw * $percent;
			$h = $sh * $percent;
		} else {
			if (isset ($w) AND !isset ($h)) {
				// autocompute height if only width is set
				$h = (100 / ($sw / $w)) * .01;
				$h = @round ($sh * $h);
			} elseif (isset ($h) AND !isset ($w)) {
				// autocompute width if only height is set
				$w = (100 / ($sh / $h)) * .01;
				$w = @round ($sw * $w);
			} elseif (isset ($h) AND isset ($w) AND isset ($constrain)) {
				// get the smaller resulting image dimension if both height
				// and width are set and $constrain is also set
				$hx = (100 / ($sw / $w)) * .01;
				$hx = @round ($sh * $hx);

				$wx = (100 / ($sh / $h)) * .01;
				$wx = @round ($sw * $wx);

				if ($hx < $h) {
					$h = (100 / ($sw / $w)) * .01;
					$h = @round ($sh * $h);
				} else {
					$w = (100 / ($sh / $h)) * .01;
					$w = @round ($sw * $w);
				}
			}
		}

		$im = @ImageCreateFromJPEG ($img) or // Read JPEG Image
		$im = @ImageCreateFromPNG ($img) or // or PNG Image
		$im = @ImageCreateFromGIF ($img) or // or GIF Image
		$im = false; // If image is not JPEG, PNG, or GIF

		if (!$im) {
			// We get errors from PHP's ImageCreate functions...
			// So let's echo back the contents of the actual image.
			readfile ($img);
		} else {
			// Create the resized image destination
			$thumb = @ImageCreateTrueColor ($w, $h);
			// Copy from image source, resize it, and paste to image destination
			@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
			// Output resized image
			@ImageJPEG ($thumb, $cached_file,100);
		}
		
		readfile($cached_file);
	}
	
	public function beforeRender(){
		if($this->session->check("isAuthenticated") and $this->session->isAuthenticated){
			$this->themes->isAuthenticated = true;
		}else{
			$this->themes->isAuthenticated = false;
		}
	}
}