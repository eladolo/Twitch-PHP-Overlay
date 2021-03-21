<!-- Dropdown Structure -->
<ul id="dropdown_menu" class="dropdown_menu dropdown-content">
	<?php
		if(isset($_SESSION["login"])){
			$files = scandir('views');
			foreach($files as $file) {
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				if(stripos($file, ".custom.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				$tmp_nm2 =  $tmp_nm == "dashboard" ? '<i class="material-icons">show_chart</i>' : strtoupper($tmp_nm);
				if(in_array($tmp_nm, $open_pages)) continue;
				echo "
					<li class='withdrop'><a class='truncate' href=\"/" . $tmp_nm . "\">" .$tmp_nm2 . "</a></li>
					<li class=\"divider\"></li>
				";
			}
			foreach($files as $file) {
				if(stripos($file, "." . site_req . ".") === false) continue;
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				$tmp_nm2 =  $tmp_nm == "dashboard" ? '<i class="material-icons">show_chart</i>' : strtoupper($tmp_nm);
				echo "
					<li class='withdrop'><a class='truncate' href=\"/" . $tmp_nm . "\">" . $tmp_nm2 . "</a></li>
					<li class=\"divider\"></li>
				";
			}
	?>
			<li class='withdrop'><a class="cursor btnLogout tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_logout"] ?>" ><i class="material-icons red-text">vpn_key</i></a></li>
			<li class="divider"></li>
	<?php
			$files = scandir('views');
			foreach($files as $file) {
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				if(stripos($file, ".custom.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				if(!in_array($tmp_nm, $open_pages) || $tmp_nm == "error" || $tmp_nm == "login") continue;
				$tmp_nm2 =  $tmp_nm == "dashboard" ? '<i class="material-icons">show_chart</i>' : strtoupper($tmp_nm);
				echo "
					<li class='withdrop'><a class='truncate' href=\"/" . $tmp_nm . "\">" . $tmp_nm2 . "</a></li>
					<li class=\"divider\"></li>
				";
			}
			$files = scandir('views');
			foreach($files as $file) {
				if(stripos($file, "." . site_req . ".") === false) continue;
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				$tmp_nm2 =  $tmp_nm == "dashboard" ? '<i class="material-icons">show_chart</i>' : strtoupper($tmp_nm);
				echo "
					<li class='withdrop'><a class='truncate' href=\"/" . $tmp_nm . "\">" . $tmp_nm2 . "</a></li>
					<li class=\"divider\"></li>
				";
			}
		} else {
			$files = scandir('views');
			foreach($files as $file) {
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				if(stripos($file, ".custom.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				if(!in_array($tmp_nm, $open_pages) || $tmp_nm == "error") continue;
				$url = $tmp_nm == "home" ? "/" : "/" . $tmp_nm;
				$url = $url == "/" && $request == "home" ? "#navbar" : $url;
				echo "
					<li class='withdrop'><a class='truncate' href=\"" . $url . "\">" . strtoupper($tmp_nm) . "</a></li>
				";
			}
			foreach($files as $file) {
				if(stripos($file, "." . site_req . ".") === false) continue;
				if(stripos($file, "view.") === false) continue;
				if(stripos($file, ".not.") !== false) continue;
				$tmp_nm = explode(".", $file)[1];
				echo "
					<li class='withdrop'><a class='truncate' href=\"/" . $tmp_nm . "\">" . strtoupper($tmp_nm) . "</a></li>
					<li class=\"divider\"></li>
				";
			}
		}
	?>
</ul>
<ul id="slide-out" class="sidenav">
	<li>
		<div class="user-view">
	      	<div class="background">
	        	<img loading="lazy" src="<?php echo LFParallax;?>">
	      	</div>
	      	<?php
	      		$user_img = LFLogo;
	      		$user_href = '/';
	      		$user_css = '';
	      		if(isset($_SESSION["login"]) && !empty($_SESSION["login"]["img"])){
	      			$user_img = $_SESSION["login"]["img"];
	      			$user_href = '#perfilModal';
	      			$user_css = 'modal-trigger';
	      		}
	      	?>
	      	<a class="<?php echo $user_css; ?> cursor" href="<?php echo $user_href; ?>"><img alt="" class="circle" src="<?php echo $user_img; ?>"></a><br>
	      	<?php
	      		$target_url = $overlay_url === "" ? "_self" : "_blank";
	      	?>
	      	<a href="/<?php echo $overlay_url; ?>" target="<?php echo $target_url; ?>" class="btnOverlay"><span class="white-text name"><?php echo SITE;?></span></a><br>
	    </div>
	</li>
	<?php
		if(isset($_SESSION["login"])){
	?>
		<?php
			if($_SESSION["login"]["level"] >= 60){
		?>
			<li><a class="tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_site_settings"]; ?>" href="/settings"><i class="material-icons">settings</i></a></li>
			<li><a class="modal-trigger tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_users"]; ?>" href="#usersModal"><i class="material-icons">supervisor_account</i></a></li>
		<?php
			}
		?>
		<li><a class="darken-1 modal-trigger tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_overlays"]; ?>" href="#overlaysModal"><i class="material-icons">collections_bookmark</i></a></li>
	<?php
		}
	?>
	<?php
		$files = scandir('views', 1);
		asort ($files);
		foreach($files as $file) {
			if(stripos($file, "navbar.") === false) continue;
			if(stripos($file, ".not.") !== false) continue;
			if(stripos($file, ".custom.") !== false) continue;
			$tmp_nm = explode(".", $file)[1];
			if(!in_array($tmp_nm, $open_pages) || $tmp_nm == "error") continue;
			$url = $tmp_nm == "home" ? "/" : $tmp_nm;
			$tmp_nm = $tmp_nm == "home" ? HOMELABEL : $tmp_nm;
			$tmp_nm = $tmp_nm == "login" ? $lang["views"]["navbar_login"] : $tmp_nm;
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
				<li class=\"divider\"></li>
				<li><a class='truncate sidenav-close' href=\"" . $url . "\">" . strtoupper($tmp_nm) . "</a></li>
			";
		}
	?>
	<?php
		if(!isset($_SESSION["login"])){
			if($request == "home"){
	?>
			<li>
				<a href="#about"  class='truncate sidenav-close' ><?php echo ABOUTLABEL; ?></a>
			</li>
	<?php
			} else {
	?>
			<li>
				<a href="/#about" class='truncate sidenav-close' ><?php echo ABOUTLABEL; ?></a>
			</li>
	<?php
			}
		}
	?>
	<li>
		<a class="dropdown-trigger" href="#!" data-target="dropdown_menu"><i class="material-icons left">all_out</i></a>
	</li>
</ul>
<!-- Navbar -->
<?php if(SIDENAV === "") { ?>
<nav id="navbar">
	<div class="nav-wrapper">
		<a style="width:64px; height: 64px;" class="<?php echo $user_css; ?> cursor brand-logo" href="<?php echo $user_href; ?>"><img loading="lazy" alt="" class="circle logo" src="<?php echo $user_img; ?>"></a>
		<a href="#!" data-target="slide-out" class="sidenav-trigger right"><i class="material-icons">menu</i></a>
		<ul id="nav-mobile" class="right hide-on-med-and-down">
			<li>
				<a href="/<?php echo $overlay_url; ?>" target="<?php echo $target_url; ?>" class="btnOverlay"><span class="white-text name truncate"><?php echo SITE;?></span></a>
			</li>
			<?php
				if(isset($_SESSION["login"])){
			?>
				<?php
					if($_SESSION["login"]["level"] >= 60){
				?>
					<li><a class="tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_site_settings"]; ?>" href="/settings"><i class="material-icons">settings</i></a></li>
					<li><a class="modal-trigger tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_users"]; ?>" href="#usersModal"><i class="material-icons">supervisor_account</i></a></li>
				<?php
					}
				?>
				<li><a class="darken-1 modal-trigger tooltip" data-position="<?php echo SIDENAVPOS == 'left' ? 'right': 'left';?>" data-tooltip="<?php echo $lang["views"]["navbar_overlays"]; ?>" href="#overlaysModal"><i class="material-icons">collections_bookmark</i></a></li>
			<?php } ?>
			<?php
				$files = scandir('views', 1);
				asort ($files);
				foreach($files as $file) {
					if(stripos($file, "navbar.") === false) continue;
					if(stripos($file, ".not.") !== false) continue;
					if(stripos($file, ".custom.") !== false) continue;
					$tmp_nm = explode(".", $file)[1];
					if(!in_array($tmp_nm, $open_pages) || $tmp_nm == "error") continue;
					$url = $tmp_nm == "home" ? "" : $tmp_nm;
					$tmp_nm = $tmp_nm == "home" ? HOMELABEL : $tmp_nm;
					$tmp_nm =  preg_replace("([A-Z])", " $0", $tmp_nm);
					echo "
						<li><a href=\"/" . $url . "\">" . strtoupper($tmp_nm) . "</a></li>
					";
				}
				$files = file_exists("php/configs/" . site_req . "/navbar.order") ? json_decode(file_get_contents("php/configs/" . site_req . "/navbar.order")) : $files;
				foreach($files as $file) {
					if(stripos($file, "." . site_req . ".") === false) continue;
					if(stripos($file, "navbar.") === false) continue;
					if(stripos($file, ".not.") !== false) continue;
					$tmp_nm = explode(".", $file)[1];
					$url = $tmp_nm == "home" ? "" : $tmp_nm;
					$tmp_nm =  preg_replace("([A-Z])", " $0", $tmp_nm);
					echo "
						<li><a class='truncate' href=\"/" . $url . "\">" . strtoupper($tmp_nm) . "</a></li>
					";
				}
			?>
			<?php
				if(!isset($_SESSION["login"])){
					if($request == "home"){
			?>
					<li>
						<a href="#about"  class='truncate sidenav-close' ><?php echo ABOUTLABEL; ?></a>
					</li>
			<?php
					} else {
			?>
					<li>
						<a href="/#about" class='truncate sidenav-close' ><?php echo ABOUTLABEL; ?></a>
					</li>
			<?php
					}
				}
			?>
			<li class="withdrop">
				<a class="dropdown-trigger" href="#!" data-target="dropdown_menu"><i class="material-icons left">all_out</i></a>
			</li>
		</ul>
	</div>
</nav>
<?php } else { ?>
<!-- Sidebar -->
<?php
	$overlay_view = "";
	if(isset($_SESSION["login"]) && $request == "overlay"){
		$overlay_view = $_SESSION["login"]["overlay"] ? "" : "hide";
	}
?>
<nav id="navbar" class="hide"></nav>
<a href="#!" data-target="slide-out" class='logo sidenav-trigger cursor sidenavbtn-<?php echo SIDENAVPOS_Y; ?> sidenavbtn-<?php echo SIDENAVPOS; ?>  <?php echo $overlay_view; ?>'><img loading="lazy" src='<?php echo $request == "overlay" ? $_SESSION["login"]["img"] : LFLogo; ?>' alt="logo" class='brand-logo logo magictime vanishIn tooltip cursor' data-position='<?php echo SIDENAVPOS == "right" ? "left": "right"; ?>' data-tooltip='<?php echo SITE;?>'></a>
<?php } ?>
