<!-- Modal images -->
<div id="imagesModal" class="modal modal-fixed-footer full_modal">
	<div class="modal-content">
	  	<h4 class="titulo" style="text-align:center;"><?php echo $lang["views"]["modal_images_label"]; ?></h4>
  		<?php
			include ("views/widget.preloader.php");
		?>
	  	<div id="imagesContent" class="row">
	  		<div class="col s12">
				<div class="file-field input-field">
					<div class="btn">
						<span><?php echo $lang["views"]["file_name"]; ?></span>
						<input type="file" id="img_logo" name="files" data-type="custom">
					</div>
					<div class="file-path-wrapper">
						<input class="file-path validate" type="text">
					</div>
				</div>
	  		</div>
			<template v-for="(img, index) in images">
				<div v-if="app.user.level >= 1 && app.user.level < 50" class="col s4 img"  v-bind:data-search="img.name">
					<img loading="lazy" class="logos left" v-bind:src="img.route + img.name" v-bind:alt="img.name" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route"><br>
					<span class="btn red btnBorrarImagen left" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 50 && app.user.level < 80 " class="col s4 img"  v-bind:data-search="img.name">
					<img loading="lazy" class="logos left" v-bind:src="img.route + img.name" v-bind:alt="img.name" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route">
					<span class="btn red btnBorrarImagen left" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 80 " class="col s4 img"  v-bind:data-search="img.name">
					<img loading="lazy" class="logos left" v-bind:src="img.route + img.name" v-bind:alt="img.name" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route">
					<span class="btn red btnBorrarImagen left" v-bind:data-type="img.type" v-bind:data-name="img.name" v-bind:data-route="img.route"><i class='material-icons'>delete</i></span><br>
				</div>
			</template>
		</div>
	</div>
	<div class="modal-footer" style="height:60px;">
		<div class="row">
			<div class="col s1">
	      		<label><i class="material-icons">search</i></label>
			</div>
			<div class="col s5">
	      		<input class=" buscar_registros" type="text" data-target="#imagesContent" data-tag="div.img">
	      	</div>
	      	<div class="col s6">
	      		<?php
					if(isset($_SESSION["login"]) && $_SESSION["login"]["level"] >= 50){
				?>
	      			<a class="waves-effect waves-lime btn lime refreshFiles"><i class="material-icons">refresh</i></a>
	      		<?php
					}
				?>
		  		<a class="modal-close waves-effect waves-red btn red"><i class="material-icons">close</i></a>
	    	</div>
	    </div>
	</div>
</div>