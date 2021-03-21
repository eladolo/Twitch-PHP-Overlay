<?php
	$_REQUEST["param"] = array("uid" => $_SESSION["login"]["id"]);
	$overlays = $this->getOverlays()["overlays"];

	$emotes = $this->getEmotes()["emotes"];

	echo "<script>";
	echo "window.overlays = " . json_encode($overlays) . ";";
	echo "window.emotes = " . json_encode($emotes) . ";";
	echo "</script>";
?>
<div class="overlay_custom">
	<div class="content_img">
	    <h6 class="alert_body"></h6><br>
	    <div class="content_img_template">
	        <div class="static hide">
	            <img src="" class="glow_img" />
	            <div class="shadow hide"></div>
	        </div>
	        <div class="spin hide">
	            <img src="" class="spin_img" />
	            <div class="shadow hide"></div>
	        </div>
	        <div class="spin_wheel hide">
	            <img src="" class="spin_wheel_img" />
	            <div class="shadow hide"></div>
	        </div>
	        <div class="magic_type hide">
	            <img src="" class="magic_img" />
	            <div class="shadow hide"></div>
	        </div>
	    </div>
	    <div class="alert_video"></div>
	</div>
</div>