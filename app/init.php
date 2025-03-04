<?php
# under indonesian
# nama folder untuk website anda. 
# under english
# folder name for your website.
$namebase = "";
define("BASE_URL", "localhost/$namebase/");
define("URL_BASE", "localhost/$namebase/public/");
# App Folder : (ALL FILES) Configs, Controllers, Core, Helpers, and Models
# Folder Configs
require_once("configs/configs.php");
# Folder Controllers
require_once("controllers/Example.php");
# Folder Core
require_once("core/Database.php");
# Folder Helpers
require_once("helpers/helpers.php");
# Folder Models
require_once("models/Example_model.php");