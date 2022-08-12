<?php 
$servername="localhost";
$dbname="ApShopping";
$username="root";
$pass="";
$dns="mysql:host=$servername;dbname=$dbname";
$pdo=new PDO($dns,$username,$pass);
try{
	$conn=$pdo;
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//echo "connected successfull";
 }catch(PDOException $e){
 	echo "connection fail".$e->getMessage();
 }

?>
