<?
include "const.php";
include "helper.class.php";

if(isset($_REQUEST["link"])) {
	Helper::start($_REQUEST["link"]);
}
?>