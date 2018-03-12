<?php
if (isset($modules_loading) && $modules_loading == true) {
	$i = isset($modules_list) ? count($modules_list) : 0;
	$modules_list[$i]['payment_name'] = basename(__FILE__, '.php');
}
?>