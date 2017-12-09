<?
include "const.php";
include "helper.class.php";

if(count($argv) != 2) {
	die;
}

Helper::clean($argv[1]);

?>