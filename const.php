<?php
define("PID_EXTENSION", "pid");
define("FILE_EXTENSION", "file");
define("PROGRESS_EXTENSION", "progress");
define("DATA_EXTENSION", "data");
define("WAITING_EXTENSION", "waiting");
define("CANCEL_EXTENSION", "cancel");
define("PAUSE_EXTENSION", "pause");

define("TMP_FOLDER", "/volume1/web/Mega/tmp/");
define("COMPLETE_FOLDER", "/volume1/video/Download/");
define("SCRIPT_FOLDER", getcwd());

@mkdir(TMP_FOLDER);
@mkdir(COMPLETE_FOLDER);
?>