<?php 
require_once('../../../../../wp-load.php');
$cUID = $_POST['real-id'];
update_user_meta( $cUID, 'REAL-Track-Start', 'True'); 
 
?>	