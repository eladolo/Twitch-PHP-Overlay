<?php
	if(!isset($_REQUEST["tkn"])){
		echo "<script>window.location.href='/';</script>";
		exit;
	}

	try{
		$video_url = "/media/" . $_REQUEST["tkn"];

        $file_in = $this->private["decryptIt"]($_REQUEST["tkn"]);
        $file_in = json_decode($file_in);

        if(stripos($file_in->name, ".youtube") !== false){
			$tmp_youtube = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/php/files/dispatch/" . $file_in->name), true);

			$video_url = $this->getYoutubeURL($tmp_youtube["id"])["url"];
			$new_title = $tmp_youtube["title"];
			$info = array(
				"Plot" => $tmp_youtube["description"],
				"Actors" => $tmp_youtube["author"],
				"Director" => $tmp_youtube["author"],
				"Poster" => $tmp_youtube["image"],
			);
		} else {
			if(stripos($file_in->name, "_media_") !== false){
			    $tmp_title_init = explode("_media_", $file_in->name)[0];
			}
			if(stripos($file_in->name, "_pdownload_") !== false){
			    $tmp_title_init = explode("_pdownload_", $file_in->name)[0];
			}

		    $new_title = preg_replace('#_[0-9].*.?#', '', $tmp_title_init);
		    $new_title = str_ireplace("_", " ", $new_title);

		    $tmp_title_init = str_ireplace("_", " ", $tmp_title_init);
		    $tmp_title_init = explode(" ", $tmp_title_init);

		    $_REQUEST["tipo"] = "movie";
		    $_REQUEST["year"] = $tmp_title_init[count($tmp_title_init) - 1];
		    $_REQUEST["title"] = $new_title;
		    $info = $this->getIMDB();
		}
    } catch(Exception $ex) {
        throw new Exception("Token invalido!", 1);
    }

    $tmp_title = $new_title;
    if(isset($info["Plot"])){
	    $tmp_description = $info["Plot"];
	    $tmp_keywords = $info["Actors"];
	    $tmp_autor = $info["Director"];
	}
	$imagen = ($info["Poster"] != "N/A") ? $info["Poster"] : '/img/video.png';
?>
<div class="fullscreen-bg">
	<video id="playerVideo" class="playerVideo responsive fullscreen-bg__video" width="100%" height="100%" controls poster="<?php echo $imagen; ?>">
	 	 <source src="<?php echo $video_url; ?>" type="video/mp4">
	</video>
</div>