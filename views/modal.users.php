<!-- Modal users -->
<div id="usersModal" class="modal modal-fixed-footer full_modal">
	<div class="modal-content">
		<?php
			include ("views/widget.preloader.php");
		?>
	  	<div class="col s12">
      		<ul id="tabUsers" class="tabs">
		        <li class="tab col s3"><a href="#users" class="active"><i class="material-icons">supervisor_account</i></a></li>
		        <li class="tab col s3"><a href="#form_user"><i class='material-icons'>add</i></a></li>
      		</ul>
    	</div>
    	<div id="users">
			<table id="usersContent" class="striped highlight responsive-table">
				<thead>
					<tr>
						<td>User</td>
						<td>Level</td>
						<td>Status</td>
						<td>Created</td>
						<td>Updated</td>
						<td>--</td>
					</tr>
				</thead>
				<tbody>
					<template v-for="(user, index) in users" >
						<tr v-if="user.level >= 60" class="" v-bind:data-search="user.user + ' ' + user.name">
							<td><b class=""><img v-bind:src="user.img" class="circle minilogo">{{ user.name }}</b></td>
							<td><b class="red-text">{{ user.level }}</b></td>
							<td>
								<b v-if="user.status == '1'" class="green-text">activo</b>
								<b v-if="user.status == '0'" class="red-text">inactivo</b>
							</td>
							<td>
								{{ formatDate(user.created) }}
							</td>
							<td>
								{{ formatDate(user.updated) }}
							</td>
							<td>
								<span class="btn orange btnEditaruser" v-bind:data-user="JSON.stringify(user)"><i class='material-icons'>mode_edit</i></span>
							</td>
						</tr>
						<tr v-if="user.level < 60" class="" v-bind:data-search="user.user + ' ' + user.name">
							<td><b class=""><img v-bind:src="user.img" class="circle minilogo">{{ user.user }} - {{ user.name }}</b></td>
							<td><b class="blue-text">{{ user.level }}</b></td>
							<td>
								<b v-if="user.status == '1'" class="green-text">activo</b>
								<b v-if="user.status == '0'" class="red-text">inactivo</b>
							</td>
							<td>
								{{ formatDate(user.created) }}
							</td>
							<td>
								{{ formatDate(user.updated) }}
							</td>
							<td>
								<span class="btn orange btnEditaruser" v-bind:data-user="JSON.stringify(user)"><i class='material-icons'>mode_edit</i></span>
								<span class="btn red btnBorraruser" v-bind:data-id="user.id"><i class='material-icons'>delete</i></span>
							</td>
						</tr>
					</template>
			</table>
		</div>
		<div id="form_user">
			<form>
			  	<div class="row">
		        	<div class="input-field col s12">
		        		<input value="" id="user_id" value="-1" type="hidden" class="validate">
		          		<input value="" id="user_name" type="text" class="validate" disabled="disabled">
		          		<label for="user_name"><?php echo $lang["views"]["modal_users_name"] ?></label>
		        	</div>
		        	<div class="input-field col s12">
		          		<input value="" id="user_user" type="text" class="validate" disabled="disabled">
		          		<label for="user_user"><?php echo $lang["views"]["modal_users_user"] ?></label>
		        	</div>
		        	<div class="input-field col s12">
		          		<input value="" id="user_email" type="email" class="validate" disabled="disabled">
		          		<label for="user_email"><?php echo $lang["views"]["modal_users_email"] ?></label>
		        	</div>
		        	<div class="input-field col s12">
		                <input value="" id="user_img" type="text" class="validate" data-type="user" disabled="disabled">
		                <label for="user_img"><?php echo $lang["views"]["modal_users_img"] ?></label>
		            </div>
		        	<div class="range-field col l10 m10 s12">
		          		<label for="user_level"><?php echo $lang["views"]["modal_users_level"] ?></label>
		          		<input type="range" id="user_level" min="1" max="100" />
		        	</div>
		        	<div class="input-field col l2 m2 s12">
		          		<div class="switch">
		                    <label>
		                        <input type="checkbox" id="user_status">
		                        <span class="lever"></span>
		                        On
		                    </label>
		                </div>
		            </div>
		        	<div class="input-field col s12">
		        		<span class="btn green btnCrearUser full_width"><i class='material-icons'>send</i></span>
		        	</div>
		      	</div>
		    </form>
	    </div>
	</div>
	<div class="modal-footer" style="height:60px;">
		<div class="row">
			<div class="col s1">
	      		<label><i class="material-icons">search</i></label>
			</div>
			<div class="col s5">
	      		<input class=" buscar_registros" type="text" data-target="#usersContent tbody">
	      	</div>
	      	<div class="col s6">
		  		<a class="modal-close waves-effect waves-red btn red"><i class="material-icons">close</i></a>
	    	</div>
	    </div>
	</div>
</div>