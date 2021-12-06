<?php

	$db_host='localhost';
	// $db_user='worker';
	$db_user='forward_worker';
	$db_pass='En5!n3r()m';
	$db_name='forward_application';

	try {
		$db_conn= new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	}catch(PDOException $e){
		echo $e->getMessage();
	}

?>