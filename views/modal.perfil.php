<!-- Modal perfil -->
<?php if(isset($_SESSION["login"])){ ?>
    <div id="perfilModal" class="modal modal-fixed-footer" style="height:460px;">
        <div class="modal-content">
            <?php
                include ("views/widget.preloader.php");
            ?>
            <h4 class="right"><?php echo "Updated: " . date('d-m-Y H:i:s', $_SESSION["login"]["updated"]) ; ?></h4>
            <form>
                <div class="col s6 input-field">
                    <img src="<?php echo isset($_SESSION["login"]) && !empty($_SESSION["login"]["img"]) ? $_SESSION["login"]["img"] : '/img/logo.png'; ?>" class="logo left hoverable" />
                </div>
                <div class="col s6 input-field">
                   <div class="switch">
                        <label>
                            <input type="checkbox" <?php echo ($_SESSION["login"]["overlay"] ? 'checked=checked' : '');?> name="overlayProfile" id="overlayProfile" />
                            <span class="lever"></span>
                            <?php echo $lang["views"]["modal_perfil_overlay_logo"] ?>
                        </label>
                        <br>
                    </div>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["id"]; ?>" id="perfil_id" value="-1" type="hidden" class="validate">
                    <input value="<?php echo $_SESSION["login"]["name"]; ?>" id="perfil_name" type="text" class="validate" disabled="disabled">
                    <label for="perfil_name"></label>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["email"]; ?>" id="perfil_email" type="email" class="validate" disabled="disabled">
                    <label for="perfil_email"><?php echo $lang["views"]["modal_perfil_email"] ?></label>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["user"]; ?>" id="perfil_user" type="text" class="validate" disabled="disabled">
                    <label for="perfil_user"><?php echo $lang["views"]["modal_perfil_user"] ?></label>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["img"]; ?>" id="perfil_img" type="text" class="validate" data-type="user" disabled="disabled">
                    <label for="perfil_img"><?php echo $lang["views"]["modal_perfil_img"] ?></label>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["obs_host"]; ?>" id="obs_host" type="text" class="validate">
                    <label for="obs_host"><?php echo $lang["views"]["modal_perfil_obs_host"] ?></label>
                </div>
                <div class="col s12 input-field">
                    <input value="<?php echo $_SESSION["login"]["obs_password"]; ?>" id="obs_password" type="password" class="validate">
                    <label for="obs_password"><?php echo $lang["views"]["modal_perfil_obs_password"] ?></label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a class="modal-close waves-effect waves-red btn red"><i class="material-icons">close</i></a>
        </div>
    </div>
<?php } ?>