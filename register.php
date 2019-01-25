<?php
    include("start.php");

    //contiendront les données du form
    $username = "";
    $email = "";
    $password = "";

    //contient les éventuels messages d'erreur
    $errors = [];

    //est-ce que le formulaire est soumis ?
    if (!empty($_POST)){
        //récupère les données dans nos variables
        $username   = strip_tags($_POST['username']);
        $email      = strip_tags($_POST['email']);
        $password   = $_POST['password'];

        //valider les données
        //longueur du pseudo
        //le champ est requis...
        if (empty($username)){
            $errors['username'][] = "Please provide a username!";
        }
        //longueur minimale
        elseif (strlen($username) < 2){
            $errors['username'][] = "Your username is too short! 2 chars minimum!";
        }
        //longueur max
        elseif (strlen($username) > 3000){
            $errors['username'][] = "Your username is too long! 30 chars max!";
        }
        //existe déjà en bdd ?
        $sql = "SELECT COUNT(*) 
                FROM users 
                WHERE username = :username";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([':username' => $username]);

        $result = (int) $stmt->fetchColumn();
        if ($result > 0){
            $errors['username'][] = 'Username is already taken! Damnit!';
        }

        //email
        //le champ est requis...
        if (empty($email)){
            $errors['email'][] = "Please provide an email!";
        }
        //email valide ?
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'][] = "Your email is not valid!";
        }
        //existe déjà en bdd ?
        $sql = "SELECT COUNT(*) 
                FROM users 
                WHERE email = :email";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([':email' => $email]);

        $result = (int) $stmt->fetchColumn();
        if ($result > 0){
            $errors['email'][] = 'You are already registered here!';
        }

        //valider le password
        if (empty($password)){
            $errors['password'][] = "Please choose a password!";
        }
        elseif(strlen($password) < 8){
            $errors['password'][] = "Minimum 8 characters please!";
        }

        //si tout est valide...
        if(empty($errors)){
            //hash le mot de passe
            $hash = password_hash($password, PASSWORD_ARGON2I);

            //on insère en bdd
            $sql = "INSERT INTO users 
            (username, email, password, creation_date)
            VALUES 
            (:username, :email, :password, NOW())";

            //envoie la requête MySQL sans l'exécuter
            $stmt = $dbh->prepare($sql);

            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $hash,
            ]);

            //crée un message à afficher sur la prochaine page
            $_SESSION['flash'] = "Welcome at Kino, $username !";

            //redirige vers la home
            header("Location: index.php");

            //solution au bug !! @copyright Fanny
            die();
        }
    }

    showTop('Register | Kino');
?>
<main>
    <h1>Create your account!</h1>
    <form method="post" novalidate>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username"
                   maxlength="30000" placeholder="lidya44"
                   value="<?= $username ?>">
            <div class="error">
                <?php
                if(!empty($errors['username'])) {
                    foreach ($errors['username'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                   maxlength="254" placeholder="yo@gmail.com" value="<?= $email ?>">
            <div class="error">
                <?php
                if(!empty($errors['email'])) {
                    foreach($errors['email'] as $message){
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" maxlength="100000">
            <p class="help">8 caracters minimum please !</p>
            <div class="error">
                <?php
                if(!empty($errors['password'])) {
                    foreach ($errors['password'] as $message) {
                        echo "<p>$message</p>";
                    }
                }
                ?>
            </div>
        </div>

        <button>Go!</button>
    </form>
</main>

<?php showBottom() ?>