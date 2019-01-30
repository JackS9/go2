<?php
	$user = unserialize($_SESSION['user']);
	$role = $_REQUEST['role'];
	$value = $_REQUEST['value'];
	echo "Set ".$role." to ".$value;
	if ($value == 'false') :
		$bValue = false;
	else :
		$bValue = true;
	endif;
	if ($role == 'Admin') :
		$user->isAdmin = $bValue;
	elseif ($role == 'Manager') :
		$user->isManager = $bValue;
	elseif ($role == 'Officer') :
		$user->isOfficer = $bValue;
	endif;
	$_SESSION['user'] = serialize($user);
	echo "\n\r";
	var_dump($_SESSION['user']);
?>
