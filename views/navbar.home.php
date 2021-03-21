<?php
	$tmp_title = DESC;
?>
<div>
	<?php if(isset($_SESSION["login"]["id"])){ ?>
		<div class="col l9 m9 s12 video magictime spaceInDown scroller" style="height:800px">
			<iframe
				id="iframeVideo"
			    src="about:blank"
			    width="100%"
			    frameBorder="0"
			    class="clone hide videos cursor hoverable magictime tinDownIn"
			    allowfullscreen="true">
			</iframe>
		</div>
		<div class="col l3 m3 s12 chat magictime spaceInDown">
			<iframe
				id="iframeChat"
				src="about:blank"
			    height="800"
			    width="100%"
			    class="clone hide chats cursor hoverable magictime tinDownIn"
			    frameBorder="0">
			</iframe>
		</div>
		<div class="col s12"></div>
		<div class="col l2 m2 s12 input-field">
			<div class="switch">
				<label>
					<input type="checkbox" name="chat_remember" id="chat_remember" />
					<span class="lever"></span>
					<?php echo $lang["views"]["chat_remember"];?>
				</label>
			</div>
		</div>
		<div class="col l2 m2 s12 input-field">
			<input type="text" value="<?php echo isset($_REQUEST["channel"]) ? $_REQUEST["channel"]: $_SESSION["login"]["user"];?>" placeholder="<?php echo $_SESSION["login"]["user"];?>" name="new_channel" id="new_channel" class="autoCompleteFollows" />
			<label for="new_channel" class="cursor btnNewChannel"><?php echo $lang["views"]["chat_new_channel"];?></label>
		</div>
		<div class="col l2 m2 s12 input-field">
			<select id="chat_grid_x" name="chat_grid_x">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
			<label for="chat_grid_x">
				<?php echo $lang["views"]["chat_grid_x"];?>
			</label>
		</div>
		<div class="col l2 m2 s12 input-field">
			<select id="chat_grid_y" name="chat_grid_y">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>
			<label for="chat_grid_y">
				<?php echo $lang["views"]["chat_grid_y"];?>
			</label>
		</div>
		<div class="col l2 m2 s12 input-field">
			<div class="switch right">
				<label>
					<input type="checkbox" checked="checked" name="chat_video_switch" id="chat_video_switch" />
					<span class="lever"></span>
					<?php echo $lang["views"]["chat_video_switch"];?>
				</label>
			</div>
		</div>
		<div class="col l2 m2 s12 input-field">
			<div class="switch right">
				<label>
					<input type="checkbox" checked="checked" name="chat_switch" id="chat_switch" />
					<span class="lever"></span>
					<?php echo $lang["views"]["chat_switch"];?>
				</label>
			</div>
		</div>
	<?php } else { ?>
		<div id="main_c1" class="white-text block home_sections" style="">
			<div id="view" class="page-view-none" data-target="main_c1">
				<?php
					if(LFParallax !== ""){
				?>
					<div class="parallax-container">
						<?php
							if(LandingVideo !== ""){
								$video_url = LandingVideo;

						        $file_in = $this->private["decryptIt"](str_replace("/media/", "", $video_url));
						        $file_in = json_decode($file_in);

						        if(stripos($file_in->name, ".youtube") !== false){
									if(isset($_SESSION["yt_url"])){
										$video_url = $_SESSION["yt_url"];
									} else {
										$tmp_youtube = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/php/files/dispatch/" . $file_in->name), true);
										$video_url = $this->getYoutubeURL($tmp_youtube["id"])["url"];
										$_SESSION["yt_url"] = $video_url;
									}
								}
						?>
							<video id="landingVideo" class="landingVideo" muted loop poster="<?php echo LFParallax; ?>" style="position: absolute; width:1920px; top: -150px;">
							 	<source src="<?php echo $video_url; ?>" type="video/mp4">
							</video>
						<?php
							} else {
						?>
							<div class="parallax"><img loading="lazy" src="<?php echo LFParallax;?>"></div>
						<?php
							}
						?>
					</div>
				<?php
					}
				?>
				<div id="titulo" class="hide perspectiveUpReturn anchor z-depth-5" style="background-color: <?php echo LandingColor; ?>; min-height: 300px">
					<h3 class="black-text padding truncate"><?php echo MINIDESC; ?></h3>
					<p class="descripcion white-text padding hide">{{ titulo }}</p>
					<br>
				</div>
			</div>
		</div>
		<div id="main_c2" class="white-text block home_sections" style="margin-top: 20px;">
			<div id="about" class="page-view-none" data-target="main_c2">
				<div class="parallax-container ">
					<?php
						if(ABOUTVIDEO !== ""){
							$video_url = ABOUTVIDEO;

					        $file_in = $this->private["decryptIt"](str_replace("/media/", "", $video_url));
					        $file_in = json_decode($file_in);

					        if(stripos($file_in->name, ".youtube") !== false){
								if(isset($_SESSION["yt_about_url"])){
									$video_url = $_SESSION["yt_about_url"];
								} else {
									$tmp_youtube = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/php/files/dispatch/" . $file_in->name), true);
									$video_url = $this->getYoutubeURL($tmp_youtube["id"])["url"];
									$_SESSION["yt_about_url"] = $video_url;
								}
							}
					?>
						<video id="aboutVideo" class="aboutVideo cursor" controls loop poster="<?php echo ABOUTIMG; ?>" style="position: absolute; width:1920px; top: -150px;">
						 	<source src="<?php echo $video_url; ?>" type="video/mp4">
						</video>
					<?php
						} else {
					?>
						<div class="parallax"><img loading="lazy" src="<?php echo ABOUTIMG;?>" class="parallax-pos1"></div>
					<?php
						}
					?>
				</div>
				<div class="magictime perspectiveLeftReturn padding black-text scroller" style="background-color: <?php echo AboutColor; ?>; min-height: 300px">
					<h3 class="black-text padding center anchor"><a href="#navbar" class='truncate' ><?php echo ABOUTLABEL; ?></a></h3>
					<?php
						if(ABOUT !== '' && ABOUT != '<p><br data-mce-bogus="1"></p>') echo $this->private["splitStringBySpace"](ABOUT);
					?>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
