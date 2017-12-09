<?
include "const.php";
include "helper.class.php";

if(isset($_REQUEST["id"])) {
	Helper::resume($_REQUEST["id"]);
}
?>