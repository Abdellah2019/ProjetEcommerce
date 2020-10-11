<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
    </head>
</html>
<?php 
include 'database.php';

global $db;
if(isset($_GET['pseudo'],$_GET['hash']) AND !empty($_GET['pseudo']) AND !empty($_GET['hash'])){
	$pseudo=htmlspecialchars(urldecode($_GET['pseudo']));
	$hash=htmlspecialchars($_GET['hash']);
	$requser=$db->prepare("SELECT * FROM client WHERE pseudo = ? AND hash = ? AND email =?");
	//echo $requser;
	global $email;
	$requser->execute(array($pseudo,$hash,$email));
	$result=$requser->fetch();
	print_r($result);

	$userexist=$requser->rowCount();
		if($userexist == 1){
			if($result['confirmation'] == 0 AND $result['hash'] == $hash ){
				echo "yeees je suis rentré!";
				//on change la confirmation de l'utisateur en passant sa valeur de 0 à 1
				$updateuser=$db->prepare("UPDATE client SET confirmation =1 WHERE pseudo = ? AND hash= ?");
				$updateuser->execute(array($pseudo,$hash));
				echo 'Votre compte a bien été confirmé!';

		}
			else{
				echo 'votre compte a déjà été confirmé!!';
		}
	}
	else{

		echo "votre email est déjà été enregistré";
	}
}
?>