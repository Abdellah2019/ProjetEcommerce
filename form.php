<form method="post" action="">
            <h1>Abdellah</h1>
            Pseudo:<input type="text" name="pseudo" id="pseudo"/><br>
            Age:<input type="text" name="age" id="age"/><br>
            Email:<input type="email" name="email" id="email"/><br>
            password:<input type="password" name="password" id="password"/><br>
            Adress:<input type="text" name="adresse" id="adresse"/><br>
            Ville:<input type="text" name="ville" id="ville"/><br>
            <input type="submit" name="formsend" id="formsend"/>

            <?php
            	include 'database.php';
            //on recupere la variable $db definit dans notre fichier database.php
            	global $db;
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
            	if(!empty($pseudo) AND !empty($email) AND !empty($password) AND preg_match($masque,$email)AND !empty($adresse) AND !empty($ville)){

                        //on definit une fonction qui permet de hacher nos parametres en lui donnant des options 
            		$options =[
            			'cost' =>12,
            		];
            		$hashpassword=password_hash($_POST['password'],PASSWORD_BCRYPT,$options);
                        //on prepare notre requete à executer on utilise cette  methode pour contourner l'injection sql
            		$q=$db->prepare("INSERT INTO client(pseudo,email,password,adresse,ville) VALUES (:pseudo,:email,:password,:adresse,:ville)");

                        //on execute la deuxieme partie de notre code  grace à la methode execute
            	      $q->execute([
            		    'pseudo' =>$_POST['pseudo'],
            		    'email'  =>$_POST['email'],
            		    'password'=>$hashpassword,
                            'adresse'=>$_POST['adresse'],
                            'ville' =>$_POST['ville']
            	      ]);


            }
            else{
                  echo 'email or password invalid';
            }
      }

            ?>
</form>