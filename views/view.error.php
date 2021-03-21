<?php
	$tmp_report = array(
		"error" => $_REQUEST["error"],
		"request" => $request,
		"states" => json_encode($app_state)
	);

	echo "<script>window.report = " . json_encode($tmp_report, true) . ";</script>";
?>
<div id="titulo" class="col s12">
	<h1 class="black-text magictime spaceInUp"><?php echo $_REQUEST["error"]; ?></h1>
	<p class="descripcion black-text"></p>
	<img src="<?php echo LFLogo; ?>" alt="logo" class="brand-logo magictime puffIn" /a>
	<br>
	<span class="btn-large blue btnBack"> << </span>
</div>
