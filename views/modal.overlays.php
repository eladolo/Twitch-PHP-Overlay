<!-- Modal overlays -->
<div id="overlaysModal" class="modal modal-fixed-footer full_modal">
	<div class="modal-content">
		<?php
			include ("views/widget.preloader.php");
		?>
		<div class="col s12">
			<ul id="tab_modal_overlay" class="tabs">
				<li class="tab col s3"><a href="#overlays" class="active"><i class="material-icons">collections_bookmark</i></a></li>
				<li class="tab col s3"><a href="#form_overlays"><i class='material-icons'>add</i></a></li>
			</ul>
		</div>
		<div id="overlays">
			<table id="overlaysContent" class="striped highlight responsive-table">
				<thead>
					<tr>
						<td>Overlay</td>
						<td>Status</td>
						<td>Created</td>
						<td>Updated</td>
						<td>--</td>
					</tr>
				</thead>
				<tbody>
					<template v-for="(overlay, index) in overlays" >
						<tr class="" v-bind:data-search="getVal(overlay.config, 'body') + ' ' + overlay.oid">
							<td>
								<b v-bind:style="{'font-family': getVal(overlay.config, 'font')}">{{ getVal(overlay.config, 'body') }}</b><br>
								<img v-if="getVal(overlay.config, 'img_url') !== ''" v-bind:src="getVal(overlay.config, 'img_url')" class="minilogo" alt="">
							</td>
							<td>
								<b v-if="overlay.status == '1'" class="green-text">activo</b>
								<b v-if="overlay.status == '0'" class="red-text">inactivo</b>
							</td>
							<td>
								{{ formatDate(overlay.created) }}
							</td>
							<td>
								{{ formatDate(overlay.updated) }}
							</td>
							<td>
								<span class="btn orange btnEditarOverlay" v-bind:data-overlay="JSON.stringify(overlay)" ><i class='material-icons'>mode_edit</i></span>
								<span class="btn red btnBorrarOverlay" v-bind:data-id="overlay.oid"><i class='material-icons'>delete</i></span>
							</td>
						</tr>
					</template>
				</tbody>
			</table>
		</div>
		<div id="form_overlays">
			<form>
				<div class="row">
					<ul id="tab_form_overlay" class="tabs">
						<li class="tab">
							<a href="#init_ov" class="active"><i class="material-icons">switch_camera</i></a>
						</li>
						<li class="tab obs_settings hide">
							<a href="#obs_ov"><i class="material-icons">camera_rear</i></a>
						</li>
						<li class="tab">
							<a href="#content_ov"><i class="material-icons">dashboard</i></a>
						</li>
						<li class="tab">
							<a href="#look_ov"><i class="material-icons">opacity</i></a>
						</li>
						<li class="tab">
							<a href="#fx_ov"><i class="material-icons">settings_input_antenna</i></a>
						</li>
					</ul>
					<br>
					<div id="init_ov">
						<div class="row">
							<input id="oid" type="hidden" value="-1">
							<div id="rewardsContent" class="col l2 m2 s12 input-field">
								<template>
									<select id="event_trigger" class="validate">
										<option value="">---</option>
										<option v-for="(reward, index) in rewards" v-bind:value="reward.id">{{ reward.title }}</option>
									</select>
								</template>
								<label for="event_trigger"><?php echo $lang["views"]["modal_overlays_event_trigger"]; ?></label>
							</div>
							<div class="col l2 m2 s12 input-field">
								<div class="switch">
									<label>
										<input type="checkbox" name="alerts_status" id="alerts_status" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_alert_status"]; ?>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div id="obs_ov" class="obs_settings hide">
						<fieldset>
							<legend>OBS</legend>
							<div class="col l3 m3 s12 input-field">
								<div class="switch">
									<label>
										<input type="checkbox" name="obs_change" id="obs_change" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_obs_change_scene"]; ?>
									</label>
								</div>
							</div>
							<div class="col l3 m3 s12 input-field">
								<div class="switch">
									<label>
										<input type="checkbox" name="obs_stay" id="obs_stay" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_obs_stay"]; ?>
									</label>
								</div>
							</div>
							<div id="scenesContent" class="col l2 m2 s12 input-field">
								<template>
									<select id="obs_scene" class="validate">
										<option value="">---</option>
										<option v-for="(scene, index) in scenes" v-bind:value="scene.name">{{ scene.name }}</option>
									</select>
								</template>
								<label for="obs_scene"><?php echo $lang["views"]["modal_overlays_obs_scene"]; ?></label>
							</div>
						</fieldset>
					</div>
					<div id="content_ov">
						<div class="row">
							<div class="input-field col l8 m8 s12">
								<textarea id="body_alert" class="materialize-textarea validate" placeholder='<?php echo $lang["views"]["modal_overlays_body_alert_placeholder"]; ?>'></textarea>
								<label for="body_alert"><?php echo $lang["views"]["modal_overlays_body_alert"]; ?></label>
							</div>
							<div class="input-field col l2 m2 s12">
								<div class="switch">
									<label>
										<input type="checkbox" name="speech_alert" id="speech_alert" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_speech_alert"]; ?>
									</label>
								</div>
							</div>
							<div class="input-field col l2 m2 s12">
								<div class="switch">
									<label>
										<input type="checkbox" name="tochat" id="tochat" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_tochat"]; ?>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="input-field col l6 m6 s12">
								<input placeholder='<?php echo $lang["views"]["modal_overlays_img_url_placeholder"]; ?>' name="img_url" id="img_url" type="text" class="validate auto_img_complete">
								<label for="img_url"><?php echo $lang["views"]["modal_overlays_img_url"]; ?></label>
							</div>
							<div class="input-field col l3 m3 s12">
								<select id="type_img" class="validate">
									<option value="">---</option>
									<option value="static"><?php echo $lang["views"]["modal_overlays_type_img_static"]; ?></option>
									<option value="spin"><?php echo $lang["views"]["modal_overlays_type_img_spin"]; ?></option>
									<option value="spin_wheel"><?php echo $lang["views"]["modal_overlays_type_img_spin_wheel"]; ?></option>
									<option value="magic_type"><?php echo $lang["views"]["modal_overlays_type_img_magic_type"]; ?></option>
								</select>
								<label><?php echo $lang["views"]["modal_overlays_type_img"]; ?></label>
							</div>
							<div class="input-field col l3 m3 s12">
								<select id="shape_img" class="validate">
									<option value="">---</option>
									<option value="square"><?php echo $lang["views"]["modal_overlays_shape_img_square"]; ?></option>
									<option value="round"><?php echo $lang["views"]["modal_overlays_shape_img_round"]; ?></option>
									<option value="rectangleh"><?php echo $lang["views"]["modal_overlays_shape_img_rectangleh"]; ?></option>
									<option value="rectanglev"><?php echo $lang["views"]["modal_overlays_shape_img_rectanglev"]; ?></option>
									<option value="circle"><?php echo $lang["views"]["modal_overlays_shape_img_circle"]; ?></option>
									<option value="triangle"><?php echo $lang["views"]["modal_overlays_shape_img_triangle"]; ?></option>
									<option value="trapezoid"><?php echo $lang["views"]["modal_overlays_shape_img_trapezoid"]; ?></option>
									<option value="pentagon"><?php echo $lang["views"]["modal_overlays_shape_img_pentagon"]; ?></option>
									<option value="diamond"><?php echo $lang["views"]["modal_overlays_shape_img_diamond"]; ?></option>
									<option value="heart"><?php echo $lang["views"]["modal_overlays_shape_img_heart"]; ?></option>
									<option value="equis"><?php echo $lang["views"]["modal_overlays_shape_img_equis"]; ?></option>
									<option value="star"><?php echo $lang["views"]["modal_overlays_shape_img_star"]; ?></option>
									<option value="crazy_star"><?php echo $lang["views"]["modal_overlays_shape_img_crazy_star"]; ?></option>
								</select>
								<label><?php echo $lang["views"]["modal_overlays_shape_img"]; ?></label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input placeholder='<?php echo $lang["views"]["modal_overlays_video_url_placeholder"]; ?>' name="video_url" id="video_url" type="text" class="auto_media_complete">
								<label for="video_url"><?php echo $lang["views"]["modal_overlays_video_url"]; ?></label>
							</div>
							<div class="input-field col l6 m6 s12">
								<input type="text" placeholder='<?php echo $lang["views"]["modal_overlays_audio_url_placeholder"]; ?>' name="audio_url" id="audio_url" class="validate checkAUDIO auto_media_complete" data-position="top" />
								<label for="audio_url"><?php echo $lang["views"]["modal_overlays_audio_url"]; ?></label>
							</div>
							<div class="input-field col l6 m6 s12">
								<div class="range-field">
									<label for="audio_volumen" class="label_config_volumen"><?php echo $lang["views"]["modal_overlays_audio_volumen"]; ?></label>
									<input name="audio_volumen" id="audio_volumen" type="range" class="validate" min="0" max="1" step="0.1" value="0">
								</div>
								<div class="input-field col l1 m1 s12">
								</div>
							</div>
						</div>
					</div>
					<div id="look_ov">
						<div class="row">
							<div class="col l6 m6 s12">
								<label for="font_alert" class="label_config_font"><?php echo $lang["views"]["modal_overlays_font_alert"]; ?></label>
								<select id="font_alert" name="font_alert" class="browser-default font_color scroller">
									<option value="" disabled selected>---</option>
									<?php
										$google_fonts = str_replace("+", " ", $google_fonts);
										$tmp_fonts = explode("|", $google_fonts);

										foreach ($tmp_fonts as $font) {
											echo '<option value="' . $font . '" style="font-family:' . $font . ' !important; ">' . $font . ' :     Lorem ipsum dolor sit amet, consectetur adipiscing elit.</option>';
										}
									?>
								</select>
							</div>
							<div class="col l6 m6 s12">
								<label for="font_color" class="label_config_font_color"><?php echo $lang["views"]["modal_overlays_font_color"]; ?></label>
								<input id="font_color" name="font_color" type="text" class="" placeholder="#000000" value="#999900">
							</div>
							<div class="input-field col l4 m4 s12">
								<div class="switch">
									<label>
										<input type="checkbox" name="glow" id="glow" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_glow"]; ?>
									</label>
								</div>
							</div>
							<div class="col l4 m4 s12">
								<label for="glow_light" class="label_config_glow_light"><?php echo $lang["views"]["modal_overlays_glow_light"]; ?></label>
								<input id="glow_light" name="glow_light" type="text" class="" placeholder="#000000" value="#999900">
							</div>
							<div class="col l4 m4 s12">
								<label for="glow_hard" class="label_config_glow_dark"><?php echo $lang["views"]["modal_overlays_glow_hard"]; ?></label>
								<input id="glow_hard" name="glow_hard" type="text" class="" placeholder="#000000" value="#ffff00">
							</div>
						</div>
					</div>
					<div id="fx_ov">
						<div class="row">
							<div class="input-field col l3 m3 s12">
								<div class="switch">
									<label>
										<input type="checkbox" name="confetti" id="confetti" />
										<span class="lever"></span>
										<?php echo $lang["views"]["modal_overlays_confetti"]; ?>
									</label>
								</div>
							</div>
							<div class="input-field col l3 m3 s12">
								<label for="conffeti_time" class="label_config_conffeti_time"><?php echo $lang["views"]["modal_overlays_conffeti_time"]; ?></label>
								<input placeholder="5" name="conffeti_time" id="conffeti_time" type="number" class="validate">
							</div>
							<div class="input-field col l3 m3 s12">
								<label for="conffeti_min" class="label_config_conffeti_min"><?php echo $lang["views"]["modal_overlays_conffeti_min"]; ?></label>
								<input placeholder="1" name="conffeti_min" id="conffeti_min" type="number" class="validate">
							</div>
							<div class="input-field col l3 m3 s12">
								<label for="conffeti_max" class="label_config_conffeti_max"><?php echo $lang["views"]["modal_overlays_conffeti_max"]; ?></label>
								<input placeholder="25" name="conffeti_max" id="conffeti_max" type="number" class="validate">
							</div>
						</div>
						<div class="row">
							<div class="input-field col l4 m4 s12">
								<select id="alert_position" class="validate mandatory">
									<option value="">---</option>
									<option value="center"><?php echo $lang["views"]["modal_overlays_alert_position_center"]; ?></option>
									<option value="tleft"><?php echo $lang["views"]["modal_overlays_alert_position_tleft"]; ?></option>
									<option value="tright"><?php echo $lang["views"]["modal_overlays_alert_position_tright"]; ?></option>
									<option value="bleft"><?php echo $lang["views"]["modal_overlays_alert_position_bleft"]; ?></option>
									<option value="bright"><?php echo $lang["views"]["modal_overlays_alert_position_bright"]; ?></option>
								</select>
								<label><?php echo $lang["views"]["modal_overlays_alert_position"]; ?></label>
							</div>
							<div class="input-field col l4 m4 s12">
								<select id="alert_fadein" class="validate mandatory">
									<option value="">---</option>
									<option value="fadein">Fadein</option>
									<option value="slidedown">Slidedown</option>
									<option value="puffIn">Puffin</option>
									<option value="vanishIn">vanishIn</option>
									<option value="foolishIn">FoolishIn</option>
									<option value="swashIn">SwashIn</option>
									<option value="swap">Swap</option>
									<option value="twisterInDown">TwisterInDown</option>
									<option value="twisterInUp">TwisterInUp</option>
									<option value="openDownLeftReturn">OpenDownLeftReturn</option>
									<option value="openUpRightReturn">OpenUpRightReturn</option>
									<option value="tinLeftIn">TinLeftIn</option>
									<option value="tinRightIn">TinRightIn</option>
									<option value="tinUpIn">TinUpIn</option>
									<option value="tinDownIn">TinDownIn</option>
									<option value="boingInUp">BoingInUp</option>
									<option value="spaceInUp">SpaceInUp</option>
									<option value="spaceInRight">SpaceInDownpaceInRight</option>
									<option value="spaceInDown">SpaceInDown</option>
									<option value="spaceInLeft">SpaceInLeft</option>
								</select>
								<label><?php echo $lang["views"]["modal_overlays_alert_fadein"]; ?></label>
							</div>
							<div class="input-field col l4 m4 s12">
								<select id="alert_fadeout" class="validate mandatory">
									<option value="">---</option>
									<option value="fadeout">Fadeout</option>
									<option value="slideup">Slideup</option>
									<option value="puffOut">PuffOut</option>
									<option value="vanishOut">VanishOut</option>
									<option value="holeOut">HoleOut</option>
									<option value="swashOut">SwashOut</option>
									<option value="magic">Magic</option>
									<option value="openDownLeft">OpenDownLeft</option>
									<option value="openDownRight">OpenDownRight</option>
									<option value="openUpLeft">OpenUpLeft</option>
									<option value="openUpRight">OpenUpRight</option>
									<option value="bombLeftOut">BombLeftOut</option>
									<option value="bombRightOut">BombRightOut</option>
									<option value="tinLeftOut">TinLeftOut</option>
									<option value="tinRightOut">TinRightOut</option>
									<option value="tinUpOut">TinUpOut</option>
									<option value="tinDownOut">TinDownOut</option>
									<option value="boingOutDown">BoingOutDown</option>
									<option value="spaceOutUp">SpaceOutUp</option>
									<option value="spaceOutRight">SpaceOutRight</option>
									<option value="spaceOutDown">SpaceOutDown</option>
									<option value="spaceOutLeft">SpaceOutLeft</option>
									<option value="rotateDown">RotateDown</option>
									<option value="rotateUp">RotateUp</option>
									<option value="rotateLeft">RotateLeft</option>
									<option value="rotateRight">RotateRight</option>
								</select>
								<label><?php echo $lang["views"]["modal_overlays_alert_fadeout"]; ?></label>
							</div>
							<div class="input-field col l2 m2 s12">
								<input placeholder="15" name="timer_time" id="timer_time" type="number" class="validate mandatory">
								<label for="timer_time"><?php echo $lang["views"]["modal_overlays_timer_time"]; ?></label>
							</div>
						</div>
					</div>
					<div class="input-field col s12">
						<span class="btn-large orange btnTestOverlay"><i class='material-icons'>visibility</i></span>
						<span class="btn-large green btnCreateOverlay"><i class='material-icons'>save</i></span>
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
				<input class=" buscar_registros" type="text" data-target="#overlaysContent tbody">
			</div>
			<div class="col s6">
				<a class="modal-close waves-effect waves-red btn red"><i class="material-icons">close</i></a>
			</div>
		</div>
	</div>
</div>