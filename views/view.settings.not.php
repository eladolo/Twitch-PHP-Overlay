<?php
	$accessLevel = 60;
	$show_advance = false;
	$tmp_title = INSTALL ? "Installing id:" . SITE_ID . " " . site_req : "Define config id:" . SITE_ID . " " . site_req;

	if(isset($_SESSION["login"]) && $_SESSION["login"]["level"] >= 60) $show_advance = true;
	if(INSTALL){
		$show_advance = true;
		$tmp_apikey = $this->genAPIKey();
	}
?>
<div id="titulo" class="col s12 hide twisterInDown center-align">
	<h3 class="black-text">{{ titulo }} <?php echo " ID: " . SITE_ID . " " . site_req ; ?></h3>
	<br>
</div>
<div class="col s12">
	<ul class="tabs">
		<li class="tab col l4 m4 l2"><a class="active" href="#basic_cfg"><i class='material-icons'>web_asset</i></a></li>
		<?php
			if($show_advance){
		?>
			<li class="tab col l4 m4 l2"><a href="#advance_cfg"><i class='material-icons'>web</i></a></li>
			<li class="tab col l4 m4 l2"><a href="#bc_llf_lang"><i class="material-icons">translate</i></a></li>
		<?php
			}
		?>
	</ul>
</div>
<form id="install" name="install" method="POST" action="/api/updateConfig" data-confirm="<?php echo $lang["views"]["settings_form_confirm"];?>" data-sntzr="true">
	<input type="hidden" value="on" name="isform" id="isform" />
	<div id="basic_cfg">
		<fieldset id="bc_llf">
			<legend><?php echo $lang["views"]["settings_look_feel"];?></legend>
			<ul class="tabs">
				<li class="tab active"><a href="#bc_llf_colors"><i class="material-icons">palette</i></a></li>
				<li class="tab"><a href="#bc_llf_label"><i class="material-icons">dvr</i></a></li>
			</ul>
			<fieldset id="bc_llf_colors">
				<fieldset>
					<legend><?php echo $lang["views"]["settings_colours"];?></legend>
					<div class="col l2 m2 s12 input-field">
						<input type="text" value="<?php echo LFColor;?>" name="LFColor" id="LFColor" />
						<label for="LFColor"><?php echo $lang["views"]["settings_lfcolor"];?></label>
					</div>
					<div class="col l2 m2 s12 input-field">
						<input type="text" value="<?php echo LFBKColor;?>" name="LFBKColor" id="LFBKColor" />
						<label for="LFBKColor"><?php echo $lang["views"]["settings_bkcolor"];?></label>
					</div>
					<div class="col l2 m2 s12 input-field">
						<input type="text" value="<?php echo LFFontColor;?>" name="LFFontColor" id="LFFontColor" />
						<label for="LFFontColor"><?php echo $lang["views"]["settings_lffcolor"];?></label>
					</div>
					<div class="col l2 m2 s12 input-field">
						<input type="text" value="<?php echo LandingColor;?>" name="LandingColor" id="LandingColor" />
						<label for="LandingColor"><?php echo $lang["views"]["settings_landingcolor"];?></label>
					</div>
					<div class="col l4 m4 s12 input-field">
						<input type="text" value="<?php echo AboutColor;?>" name="AboutColor" id="AboutColor" />
						<label for="AboutColor"><?php echo $lang["views"]["settings_aboutcolor"];?></label>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo $lang["views"]["settings_images"];?></legend>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo LFfavicon;?>" name="LFfavicon" id="LFfavicon" class="auto_img_complete" />
						<label for="LFfavicon"><?php echo $lang["views"]["settings_favicon"];?></label>
						<img src="<?php echo LFfavicon;?>" class="logo-config cursor materialboxed" />
					</div>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo LFLogo;?>" name="LFLogo" id="LFLogo" class="auto_img_complete" />
						<label for="LFLogo"><?php echo $lang["views"]["settings_logo"];?></label>
						<img src="<?php echo LFLogo;?>" class="logo-config cursor materialboxed" />
					</div>
					<div class="col s12 input-field"><br></div>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo LFParallax;?>" name="LFParallax" id="LFParallax" class="auto_img_complete" />
						<label for="LFParallax"><?php echo $lang["views"]["settings_parallax"];?></label>
						<img src="<?php echo LFParallax;?>" class="logo-config cursor materialboxed" />
					</div>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo ABOUTIMG;?>" name="ABOUTIMG" id="ABOUTIMG" class="auto_img_complete" />
						<label for="ABOUTIMG"><?php echo $lang["views"]["settings_about_img"];?></label>
						<img src="<?php echo ABOUTIMG;?>" class="logo-config cursor materialboxed" />
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo $lang["views"]["settings_videos"];?></legend>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo LandingVideo;?>" name="LandingVideo" id="LandingVideo" class="auto_media_complete" data-type="media" />
						<label for="LandingVideo"><?php echo $lang["views"]["settings_video_home"];?></label>
					</div>
					<div class="col l6 m6 s12 input-field">
						<input type="text" value="<?php echo ABOUTVIDEO;?>" name="ABOUTVIDEO" id="ABOUTVIDEO" class="auto_media_complete" />
						<label for="ABOUTVIDEO"><?php echo $lang["views"]["settings_video_about"];?></label>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo $lang["views"]["settings_layout"];?></legend>
					<div class="col l2 m2 s12 input-field">
						<div class="switch">
							<label>
								<input type="checkbox" <?php echo (CONTAINER_LAYOUT ? 'checked=checked' : '');?> name="CONTAINER_LAYOUT" id="CONTAINER_LAYOUT" />
								<span class="lever"></span>
								<?php echo $lang["views"]["settings_container_layout"];?>
							</label>
						</div>
					</div>
					<div class="col l2 m2 s12 input-field">
						<div class="switch">
							<label>
								<input type="checkbox" <?php echo (ANIME_ON ? 'checked=checked' : '');?> name="ANIME_ON" id="ANIME_ON" />
								<span class="lever"></span>
								<?php echo $lang["views"]["settings_animations"];?>
							</label>
							<br>
						</div>
					</div>
					<div class="col l2 m2 s12 input-field">
						<div class="switch">
							<label>
								<input type="checkbox" <?php echo (SIDENAV ? 'checked=checked' : '');?> name="SIDENAV" id="SIDENAV" />
								<span class="lever"></span>
								<?php echo $lang["views"]["settings_sidenav"];?>
							</label>
							<br>
						</div>
					</div>
					<div class="col l2 m2 s12 input-field">
						<select id="SIDENAVPOS" name="SIDENAVPOS">
					      	<option value="" disabled selected>---</option>
					      	<option value="left" <?php if(SIDENAVPOS == 'left') echo'selected="selected"';?>><?php echo $lang["views"]["settings_sidenav_left"];?></option>
					      	<option value="right" <?php if(SIDENAVPOS == 'right') echo'selected="selected"';?>><?php echo $lang["views"]["settings_sidenav_right"];?></option>
					    </select>
						<label for="SIDENAVPOS"><?php echo $lang["views"]["settings_sidenav_x"];?></label>
					</div>
					<div class="col l2 m2 s12 input-field">
						<select id="SIDENAVPOS_Y" name="SIDENAVPOS_Y">
					      	<option value="" disabled selected>---</option>
					      	<option value="top" <?php if(SIDENAVPOS_Y == 'top') echo'selected="selected"';?>><?php echo $lang["views"]["settings_sidenav_top"];?></option>
					      	<option value="bottom" <?php if(SIDENAVPOS_Y == 'bottom') echo'selected="selected"';?>><?php echo $lang["views"]["settings_sidenav_bottom"];?></option>
					    </select>
						<label for="SIDENAVPOS_Y"><?php echo $lang["views"]["settings_sidenav_y"];?></label>
					</div>
				</fieldset>
			</fieldset>
			<fieldset id="bc_llf_label">
				<legend><?php echo $lang["views"]["settings_labels_content"];?></legend>
				<div class="col s12">
					<label for="LFFont"><?php echo $lang["views"]["settings_lffont"];?></label>
					<select id="LFFont" name="LFFont" class="browser-default scroller">
				      	<option value="" disabled selected>---</option>
				      	<?php
							$google_fonts = str_replace("+", " ", $google_fonts);
							$tmp_fonts = explode("|", $google_fonts);

							foreach ($tmp_fonts as $font) {
								$selected_option = $font == LFFont ? "selected='selected'" : '';
								echo '<option value="' . $font . '" style="font-family:' . $font . ' !important; " ' . $selected_option . '>' . $font . ' :     Lorem ipsum dolor sit amet, consectetur adipiscing elit.</option>';
							}
						?>
				    </select>
				</div>
				<div class="col s12"></div>
				<div class="col l3 m3 s12 input-field">
					<input type="text" value="<?php echo SITE;?>" name="SITE" id="SITE" />
					<label for="SITE"><?php echo $lang["views"]["settings_site"];?></label>
				</div>
				<div class="col l3 m3 s12 input-field">
					<input type="text" value="<?php echo HOMELABEL;?>" name="HOMELABEL" id="HOMELABEL" />
					<label for="HOMELABEL"><?php echo $lang["views"]["settings_homelabel"];?></label>
				</div>
				<div class="col l3 m3 s12 input-field">
					<input type="text" value="<?php echo ABOUTLABEL;?>" name="ABOUTLABEL" id="ABOUTLABEL" />
					<label for="ABOUTLABEL"><?php echo $lang["views"]["settings_aboutlabel"];?></label>
				</div>
				<div class="col l3 m3 s12 input-field">
					<textarea name="MINIDESC" id="MINIDESC" class="materialize-textarea"><?php echo MINIDESC;?></textarea>
					<label for="MINIDESC"><?php echo $lang["views"]["settings_minidesc"];?></label>
				</div>
				<div class="col s12"></div>
				<div class="col l6 m6 s12 input-field">
					<textarea name="DESC" id="DESC" class="materialize-textarea"><?php echo DESC;?></textarea>
					<label for="DESC"><?php echo $lang["views"]["settings_desc"];?></label>
				</div>
				<div class="col l6 m6 s12 input-field">
					<textarea name="ABOUT" id="ABOUT" class="materialize-textarea wygwyg"><?php echo ABOUT;?></textarea>
					<label for="ABOUT"><?php echo $lang["views"]["settings_about"];?></label>
				</div>
			</fieldset>
		</fieldset>
	</div>
	<?php
		if($show_advance){
	?>
		<div id="advance_cfg">
			<fieldset>
				<legend><?php echo $lang["views"]["settings_app_info"];?></legend>
				<div class="col l2 m2 s12 input-field">
					<div class="switch">
						<label>
							<input type="checkbox" <?php echo (DEBUG ? 'checked=checked' : '');?> name="DEBUG" id="DEBUG" />
							<span class="lever"></span>
							<?php echo $lang["views"]["settings_debug"];?>
						</label>
					</div>
				</div>
				<div class="col l2 m2 s12 input-field">
					<div class="switch">
						<label>
							<input type="checkbox" <?php echo (FORCE_SSL ? 'checked=checked' : '');?> name="FORCE_SSL" id="FORCE_SSL" />
							<span class="lever"></span>
							<?php echo $lang["views"]["settings_force_ssl"];?>
						</label>
					</div>
				</div>
				<div class="col l2 m2 s12 input-field">
					<select id="LANG" name="LANG">
						<?php
							foreach(glob('php/lang/*', GLOB_ONLYDIR) as $dir) {
								$dir = str_ireplace("php/lang/", "", $dir);
								$selected = $dir == LANG ? "selected=selected" : "";
								echo "<option value='" . $dir . "' " . $selected . " >" . $dir . "</option>";
							}
						?>
					</select>
					<label for="LANG"><?php echo $lang["views"]["settings_lang"];?></label>
				</div>
				<div class="col s12"></div>
			</fieldset>
			<fieldset>
				<legend><?php echo $lang["views"]["settings_allow_origins"];?></legend>
				<div class="col s12 input-field">
					<input type="text" value="<?php echo ALLOW_ORIGINS;?>" name="ALLOW_ORIGINS" id="ALLOW_ORIGINS" />
				</div>
			</fieldset>
			<fieldset>
				<legend><?php echo $lang["views"]["settings_databases"];?></legend>
				<div class="col s4 input-field">
					<select id="DB_DRIVER" name="DB_DRIVER">
				      	<option value="" disabled selected>---</option>
				      	<option value="mysql" <?php if(DB_DRIVER == 'mysql') echo'selected="selected"';?>>mysql</option>
				      	<option value="firebird" <?php if(DB_DRIVER == 'firebird') echo'selected="selected"';?>>firebird</option>
				      	<option value="pgsql" <?php if(DB_DRIVER == 'pgsql') echo'selected="selected"';?>>pgsql</option>
				      	<option value="sqlite" <?php if(DB_DRIVER == 'sqlite') echo'selected="selected"';?>>sqlite</option>
				      	<option value="odbc" <?php if(DB_DRIVER == 'odbc') echo'selected="selected"';?>>odbc</option>
				      	<option value="sqlsrv" <?php if(DB_DRIVER == 'sqlsrv') echo'selected="selected"';?>>sqlsrv</option>
				      	<option value="oci" <?php if(DB_DRIVER == 'oci') echo'selected="selected"';?>>oci</option>
				    </select>
					<label for="DB_DRIVER"><?php echo $lang["views"]["settings_db_driver"];?></label>
				</div>
				<div class="col s4 input-field">
					<input type="text" value="<?php echo DB_host;?>" name="DB_host" id="DB_host" />
					<label for="DB_host"><?php echo $lang["views"]["settings_db_host"];?></label>
				</div>
				<div class="col s4 input-field">
					<input type="text" value="<?php echo DB_name;?>" name="DB_name" id="DB_name" />
					<label for="DB_name"><?php echo $lang["views"]["settings_db_name"];?></label>
				</div>
				<div class="col s6 input-field">
					<input type="text" value="<?php echo DB_user;?>" name="DB_user" id="DB_user" />
					<label for="DB_user"><?php echo $lang["views"]["settings_db_user"];?></label>
				</div>
				<div class="col s6 input-field">
					<input type="password" value="<?php echo DB_password;?>" name="DB_password" id="DB_password" />
					<label for="DB_password"><?php echo $lang["views"]["settings_db_password"];?></label>
				</div>
				<?php if(!empty(INSTALL) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/php/install")){ ?>
					<div class="col s12 input-field">
						<div class="cursor btn orange white-text btnResetDb" style="width:100%;">set/reset db</div>
					</div>
				<?php } ?>
			</fieldset>
			<fieldset>
				<legend><?php echo $lang["views"]["settings_crypto_keys"];?></legend>
				<div class="col s6 input-field">
					<input type="password" value="<?php echo SECRET_PUBLIC;?>" name="SECRET_PUBLIC" id="SECRET_PUBLIC" />
					<label for="SECRET_PUBLIC"><?php echo $lang["views"]["settings_secret_public"];?></label>
				</div>
				<div class="col s6 input-field">
					<input type="password" value="<?php echo SECRET_SHARE;?>" name="SECRET_SHARE" id="SECRET_SHARE" />
					<label for="SECRET_SHARE"><?php echo $lang["views"]["settings_secret_share"];?></label>
				</div>
			</fieldset>
			<fieldset>
				<legend><?php echo $lang["views"]["settings_apikey"];?></legend>
				<div class="col s12 input-field">
					<input type="password" value="<?php echo !empty(APIKEY) ? APIKEY : $tmp_apikey['apikey'];?>" name="APIKEY" id="APIKEY" />
				</div>
			</fieldset>
		</div>
		<div id="bc_llf_lang">
			<?php
				$lang_buffer = '';
				foreach($lang as $category => $cat_content) {
					$lang_buffer .= '<fieldset>';
					$lang_buffer .= '<legend>' . $category . '</legend>';
		            foreach($cat_content as $varname => $value) {
		            	$lang_buffer .= '<div class="col l3 m4 s12 input-field">';
		                $lang_buffer .= '<input id="langvar_' . $category . '_' . $varname . '" name="langvar_' . $category . '_' . $varname . '" type="text" value="' . $value . '" data-sntzr="true" />';
		                $lang_buffer .= '<label for="langvar_' . $category . '_' . $varname . '">' . $varname . '</label>';
		                $lang_buffer .= '</div>';
		            }
		            $lang_buffer .= '</fieldset>';
		        }

		        echo $lang_buffer;
			?>
			<fieldset>
				<legend><?php echo $lang["views"]["settings_new_lang"];?></legend>
				<div class="col s12 input-field">
					<input type="text" name="target" id="target" data-sntzr="true" placeholder="<?php echo LANG; ?>"/>
				</div>
			</fieldset>
		</div>
	<?php
		}
	?>
	<div class="col s12"><br><br>
		<button type="submit" class="btn green white-text full_width">
			<i class='material-icons'>save</i>
		</button>
	<div class="col s12"><br><br></div>
</form>
