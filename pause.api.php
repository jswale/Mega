<?
include "const.php";
include "helper.class.php";

if(isset($_REQUEST["id"])) {
	Helper::pause($_REQUEST["id"]);
}
?>