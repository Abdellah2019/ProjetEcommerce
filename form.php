<form method="post" action="">
            <h1>Abdellah</h1>
            Pseudo:<input type="text" name="pseudo" id="pseudo"/><br>
            Age:<input type="text" name="age" id="age"/><br>
            Email:<input type="email" name="email" id="email"/><br>
            password:<input type="password" name="password" id="password"/><br>
            Adress:<input type="text" name="adresse" id="adresse"/><br>
            Ville:<input type="text" name="ville" id="ville"/><br>
            <input type="submit" name="formsend" id="formsend"/>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <div id="data"></div>




            <?php
            	include 'database.php';
                 
            //on recupere la variable $db definit dans notre fichier database.php
            	 $db;
            //on definit notre expression regulière
             $masque = 
            "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/";
            if(isset($_POST['formsend'])){
            //on recupère les champs saisi par l'utilisateur et les passe à la fonction htmlspecialchars qui permet de changer les caractère des balises
            //contre la faille xss
            $pseudo=htmlspecialchars($_POST['pseudo']);
            $email=htmlspecialchars($_POST['email']);
            $password=htmlspecialchars($_POST['password']);
            $adresse=htmlspecialchars($_POST['adresse']);
            $ville=htmlspecialchars($_POST['ville']);

            //on verifie les les differentes champs s'il ne sont pas vide grace à lla fonction empty
                  $hash=md5(rand(1000,6000));
                  print_r($hash);
            	if(!empty($pseudo) AND !empty($email) AND !empty($password) AND preg_match($masque,$email)AND !empty($adresse) AND !empty($ville)){

                        //on definit une fonction qui permet de hacher nos parametres en lui donnant des options 
            		$options =[
            			'cost' =>12,
            		];
            		$hashpassword=password_hash($_POST['password'],PASSWORD_BCRYPT,$options);
                        //on prepare notre requete à executer on utilise cette  methode pour contourner l'injection sql
                        //on ajoute un attribut hash qui sera renvoyer à l'utilisateur enfin pour lui permettre de s'inscrire
            		$q=$db->prepare("INSERT INTO client(pseudo,email,password,adresse,ville,hash) VALUES (:pseudo,:email,:password,:adresse,:ville,:hash)");

                        //on execute la deuxieme partie de notre code  grace à la methode execute
            	      $q->execute([
            		    'pseudo' =>$_POST['pseudo'],
            		    'email'  =>$_POST['email'],
            		    'password'=>$hashpassword,
                            'adresse'=>$_POST['adresse'],
                            'ville' =>$_POST['ville'],
                            'hash' =>$hash
            	      ]);

                        //le corp du message à envoyer
                        $msg='
                        <html>
                        <body>
                        <div align="center">
                        <p>
                        Your account has been made, <br /> please verify it by clicking 
                        the activation link that has been send to your email.
                        </p>
                        <a href="http://localhost/confirmation.php?pseudo='.urlencode($pseudo).'&hash='.$hash.'">Confirmez votre compte!</a>
                        ';
                        //l'adresse du destinataire
                        $to=$email;
                        $subject='signup|Verification';
                        //les headers sont des des types d'encodages facultatif
                        $headers="MIME-Version: 1.0\r\n";
                        $headers.='From:"noreply@boson.com"<support@ecommerce.com>'."\n";
                        $headers.='Content-Type:text/html;charset="utf-8"'."\n";
                        $headers.='Content-Transfer-Encoding: 8bit';
                        ini_set("SMTP","ssl://smtp.gmail.com");
                        ini_set("smtp_port","587");
                        //la fonction qui permet l'envoie des mails
                        mail($to,$subject,$msg,$headers);
                 




                  


            }
            else{
                  echo 'email or password invalid';
            }
      }

           // ?>
</form>
