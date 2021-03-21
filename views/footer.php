<div class="LFFontColor">
	<div class="container">
		<div class="row">
			<div class="col s12">
				<div class="logo circle left">
		        	<img loading="lazy"  class="logo-footer circle" src="<?php echo LFfavicon;?>">
		      	</div>
				<p class="black-text left"><?php echo MINIDESC;?></p><br><br><br>
				<ul>
					<?php
						$home_href = $request == "home" ? "#navbar" : "/";
						$trail_href = $request == "home" ? "" : "/";
					?>
					<li>
						<a href="<?php echo $home_href; ?>" class='truncate' ><?php echo HOMELABEL; ?></a>
					</li>
					<?php
						$files = scandir('views', 1);
						foreach($files as $file) {
							if(stripos($file, "navbar.") === false) continue;
							if(stripos($file, ".not.") !== false) continue;
							if(stripos($file, ".custom.") !== false) continue;
							$tmp_nm = explode(".", $file)[1];
							if(!in_array($tmp_nm, $open_pages) || $tmp_nm == "home" || $tmp_nm == "error") continue;
							$url = $tmp_nm == "home" ? "/" : "/" . $tmp_nm;
							$tmp_nm =  preg_replace("([A-Z])", " $0", $tmp_nm);
							echo "
								<li><a href=\"" . $url . "\" class=' sidenav-close'>" . strtoupper($tmp_nm) . "</a></li>
							";
						}
						$files = file_exists("php/configs/" . site_req . "/navbar.order") ? json_decode(file_get_contents("php/configs/" . site_req . "/navbar.order")) : $files;
						foreach($files as $file) {
							if(stripos($file, "." . site_req . ".") === false) continue;
							if(stripos($file, "navbar.") === false) continue;
							if(stripos($file, ".not.") !== false) continue;
							$tmp_nm = explode(".", $file)[1];
							$url = $tmp_nm == "home" ? "/" : "/" . $tmp_nm;
							$tmp_nm =  preg_replace("([A-Z])", " $0", $tmp_nm);
							echo "
								<li><a class='truncate sidenav-close' href=\"" . $url . "\">" . strtoupper($tmp_nm) . "</a></li>
							";
						}
					?>
					<?php
						if(!isset($_SESSION["login"])){
					?>
						<li>
							<a href="<?php echo $trail_href; ?>#about" class='truncate' ><?php echo ABOUTLABEL; ?></a>
						</li>
					<?php
						}
					?>
				</ul><br>
				<a class="btn blue darken-2" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); ?>" target="_blank" style="width:120px;">Share <img class="minilogo" src="/img/facebook.png"></a><br>
				<a href="https://twitter.com/intent/tweet?text=wow!😀&url=<?php echo urlencode('https://' . $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);?>" target="_blank" class="btn blue lighten-2 hoverable LFFontColor" data-show-count="false" data-lang="es" style="width:120px;">Tweet  <img class="minilogo" src="/img/twitter.png"></a><br><br>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			<div class="s12 right">
				<a class="modal-trigger cursor" href="#modalCredits">
					<?php
						echo "© " . date("Y") . " " . $_SERVER["SERVER_NAME"];
					?>
				</a>
			</div>
		</div>
	</div>
</div>