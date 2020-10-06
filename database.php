<?php
	//on definit les constantes qui nous permettent fde ce conneter à notre base de données
	define('HOST','localhost');
	define('DB_NAME','mydatabase');
	define('USER','root');
	define('PASS','');

	//on essai de ce connecter et on leve l'exception dans le cas échouant 

	try{
		$db=new PDO("mysql:host=" .HOST. ";dbname=" . DB_NAME,USER,PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		echo "Connect >  Ok!<br>";

	}
	catch(PDOException $e){
		echo $e;

	}

?>