<?php
include "const.php";
include "helper.class.php";
include "mega.class.php";

if(count($argv) != 3) {
	die;
}

Helper::download($argv[1], $argv[2]);
?>