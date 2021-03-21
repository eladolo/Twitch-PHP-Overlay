<!-- Modal media -->
<div id="mediaModal" class="modal modal-fixed-footer full_modal">
	<div class="modal-content">
	  	<h4 class="titulo" style="text-align:center;"><?php echo $lang["views"]["modal_media_label"]; ?></h4>
  		<?php
			include ("views/widget.preloader.php");
		?>
	  	<div id="mediaContent" class="row">
	  		<ul class="tabs">
	  			<li class="tab active col s3"><a href="#tabArchivo"><i class="material-icons">upload</i></a></li>
	  			<li class="tab col s3"><a href="#tabYoutube"><i class="material-icons">movie</i></a></li>
	  		</ul>
	  		<div class="col s12">
	  			<div id="tabArchivo">
					<div class="file-field input-field">
						<div class="btn">
							<span class="LFColor"><?php echo $lang["views"]["file_name"]; ?></span>
							<input class="file_to_upload" type="file" id="file_to_upload" name="files" data-type="media">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>
	  			</div>
	  			<div id="tabYoutube">
	  				<br>
                	<span class="btn left LFColor btnUploadYoutube">Youtube</span>
                	<input class="left" id="youtube_file" type="text" style="width: 70%;" />
	  			</div>
	  		</div>
			<template v-for="(file, index) in media">
				<div v-if="app.user.level >= 15 && app.user.level < 50 && (file.type != 'mp3' && file.type != 'wav' && file.type != 'mp4' && file.type != 'zip')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/file.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal"><br>
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 15 && app.user.level < 50 && (file.type == 'wav' && file.type == 'mp3')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/mp3.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal"><br>
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 15 && app.user.level < 50 && (file.type == 'mp4')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a>
					<img loading="lazy" class="logos files left" src="/img/mp4.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal"><br>
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 15 && app.user.level < 50 && (file.type == 'zip')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/zip.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal"><br>
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>

				<div v-if="app.user.level >= 50 && app.user.level < 80 && (file.type != 'mp3' && file.type != 'wav' && file.type != 'mp4' && file.type != 'zip')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a>
					<img loading="lazy" class="logos files left" src="/img/file.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 50 && app.user.level < 80 && (file.type == 'mp3' && file.type == 'wav')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a>
					<img loading="lazy" class="logos files left" src="/img/mp3.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 50 && app.user.level < 80 && (file.type == 'mp4')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a>
					<img loading="lazy" class="logos files left" src="/img/mp4.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 50 && app.user.level < 80 && (file.type == 'zip')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a>
					<img loading="lazy" class="logos files left" src="/img/zip.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>

				<div v-if="app.user.level >= 80 && (file.type != 'mp3' && file.type != 'wav' && file.type != 'mp4' && file.type != 'zip')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/file.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 80 && (file.type == 'mp3' || file.type == 'wav')" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/mp3.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 80 && file.type == 'mp4'" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/mp4.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
				</div>
				<div v-if="app.user.level >= 80 && file.type == 'zip'" class="col s4 file"  v-bind:data-search="file.name" v-bind:data-info="file.name">
					<a v-bind:href="'/video/' + file.tkn" target="_blank" class="btn grey"><i class='material-icons'>link</i></a><img loading="lazy" class="logos files left" src="/img/zip.png" v-bind:alt="file.name" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" v-bind:data-tkn="file.tkn" data-target="mediaModal">
					<span class="btn red btnBorrarArchivo left" v-bind:data-type="file.type" v-bind:data-name="file.name" v-bind:data-route="file.route" data-ftype="media"><i class='material-icons'>delete</i></span><br>
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
	      		<input class=" buscar_registros" type="text" data-target="#mediaContent" data-tag="div.file">
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