<?
include "const.php";
include "helper.class.php";

if(isset($_REQUEST["id"])) {
	Helper::cancel($_REQUEST["id"]);
}
?>