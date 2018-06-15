<?php

require_once 'wcEvent.php';
	
if (isset($_POST) && is_array($_POST)) {
    $action = $_POST["action"];
		//$event_id = $_POST["eventid"];
		//$user_id 	= $_POST["userid"];
	
	if($action == "getAllUsersPicks"){
		$fsevent = new FSEvent();
		$return = $fsevent->getAllUsersAndPicks();

		
		echo json_encode($return);
		//print_r($return['main']);
	}
	
}
	
?>