<?php
    include("start.php");

    //contiendront les données du form
    $usernameOrEmail = "";
    $password = "";
    $error = "";

    //est-ce que le formulaire est soumis ?
    if (!empty($_POST)){
        //récupère les données dans nos variables
        $usernameOrEmail   = $_POST['usernameOrEmail'];
        $password   = $_POST['password'];

        //existe en bdd ?
        $sql = "SELECT *
                FROM users 
                WHERE username = :usernameOrEmail 
                OR email = :usernameOrEmail";

        $stmt = $dbh->prepare($sql);
        $stmt->execute([':usernameOrEmail' => $usernameOrEmail]);

        $user = $stmt->fetch();
        //echo json_encode($user);

        //on a trouvé le user ?
        if (!empty($user)){
            //vérifie le mot de passe
            $isPasswordValid = password_verify($password, $user['password']);

            if ($isPasswordValid){
                //stocke les infos du user dans la session
                //ce qui le connecte
                $_SESSION['user'] = $user;

                //petit message...
                $_SESSION['flash'] = "Welcome back " . $_SESSION['user']['username'] . "!";

                //redirige vers la home
                header("Location: index.php");

                //solution au bug !! @copyright Fanny
                die();
            }
            else {
                $error = "Bad credentials!";
            }
        }
        else {
            $error = "Bad credentials!";
        }
    }

    showTop('Register | Kino');
?>
<main>
    <h1>Login!</h1>
    <form method="post" novalidate>
        <div>
            <label for="usernameOrEmail">Username or email</label>
            <input type="text" name="usernameOrEmail" id="usernameOrEmail"
                   maxlength="30000" placeholder="lidya44"
                   value="<?= $usernameOrEmail ?>">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"
                   maxlength="100000">
            <div class="error">
                <?php echo $error; ?>
            </div>
        </div>

        <button>Go!</button>
    </form>
</main>

<?php showBottom() ?>