<?php
include_once('../../../sbo-common/Common.php');
$selectedtaxcode = $_GET['selectedtaxcode'];
echo Common::getTaxCodeOptions($selectedtaxcode);
?>