<?php
	include_once("views/master.ceil.php");
?>
<!DOCTYPE html>
<html lang="es">
	<head id="head">
		<?php
			include_once("views/master.head.php");
		?>
	</head>
	<body id="body">
		<header id="header">
			<?php
				include_once("views/navbar.php");
			?>
		</header>
		<main id="main">
			<?php
				$container_no_css = array(); //array('home');
			?>
			<div class="<?php echo in_array($request, $container_no_css) || CONTAINER_LAYOUT === '' ? '' : 'container'; ?>">
				<?php
					include_once("views/layout." . SITE_LAYOUT . ".php");
				?>
			</div>
		</main>
		<footer class="z-depth-5">
			<?php
				include_once("views/footer.php");
			?>
		</footer>
		<?php
			include_once("views/master.floor.php");
		?>
	</body>
</html>