<?php 
// session_start();
// for csrf attack
if($_SERVER['REQUEST_METHOD']=='POST'){
	if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
		echo "Invalid CSRF token!!";
		die();
	}else{
		unset($_SESSION['csrf_token']);
	}
}

if(empty($_SESSION['csrf_token'])){
	if(function_exists('random_bytes')){
		$_SESSION['csrf_token']=bin2hex(random_bytes(32));
	}elseif(function_exists('mycrypt_create_iv')){
		$_SESSION['csrf_token']=bin2hex(mcrypt_create_iv(32,MCRYPT_DEV_URANDOM));
	}else{
		$_SESSION['csrf_token']=bin2hex(openssl_random_pseudo_bytes(32));
	}
}



// Escape html for output for xss attack

function escape($html){
	return htmlspecialchars($html,ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>
