<?php
	$error = $_FILES["file"]["error"];
    if ($error == UPLOAD_ERR_OK) {
   		$name = $_FILES["file"]["name"];
   		move_uploaded_file( $_FILES["file"]["tmp_name"], "ads/" . $_FILES['file']['name']);
    }
?>