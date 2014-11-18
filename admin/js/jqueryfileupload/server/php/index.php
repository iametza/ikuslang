<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

// Konstante globalak


define("URL_BASE", 'http://'. rtrim($_SERVER['SERVER_NAME'],"/") );
define("URL_BASE_ADMIN", URL_BASE  . '/admin/');



define("AZPITITULUEN_PATH", "azpitituluak/");
define("BIDEOEN_PATH", "bideoak/");
define("AUDIOEN_PATH", "audioak/");

$document_root = "../../../../../";

require($document_root."inc/db.inc.php");
require($document_root."inc/libs/url.lib.php");
require($document_root."inc/libs/dbo.lib.php");
require($document_root."inc/libs/hizkuntzak.lib.php");
require($document_root."inc/funtzioak/globalak.fun.php");
require($document_root."admin/inc/libs/erabiltzailea.lib.php");
require_once($document_root."admin/inc/libs/etiketak.lib.php");
require($document_root."admin/inc/funtzioak/funtzioak.php");

// Si hay problemas con los caracteres puede que sea por esto...
setlocale(LC_ALL, 'es_ES');
header("Content-type: text/html; charset=utf-8");




require('UploadHandler.php');
$upload_handler = new UploadHandler();
