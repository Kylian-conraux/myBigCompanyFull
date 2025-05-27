<?php
include 'includes/db.php';
include 'includes/user.php';
include 'includes/flash.php';

if (isset($_SESSION['user'])) {
    logoutUser();
}

if (isset($_POST["loginUser"])) {
    if (!empty($_POST["exampleInputEmail1"]) && !empty($_POST["exampleInputPassword1"])) {
        $user = authenticateUser(trim($_POST["exampleInputEmail1"]), trim($_POST["exampleInputPassword1"]));
        if ($user !== false) {
            loginUser($user);
            header("Location: users.php");
            exit();
        } else {
            setFlash("Erreur lors du login, user non retrouvé", "error");
            header("Location: login.php");
            exit();
        }
    } else {
        setFlash("Erreur lors du login, data manquant", "error");
        header("Location: login.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body>
    <?php

    include './nav.php';

    ?>


    <div class="container">
        <div>
            <?php displayFlash(); ?>
        </div>
        <div class="row align-item-center justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Connexion</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="login.php" class="login-validation" novalidate>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                <div class="invalid-feedback">
                                    Le mail du user doit contenir entre 2 et 50 caractères
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="invalid-feedback">
                                Le mot de passe du user doit contenir entre 2 et 50 caractères
                            </div>
                            <button type="submit" name="loginUser" class="btn btn-primary m-1">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/tools.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            formValidator(".login-validation");
            initFlash();
            //initDeleteModal("deleteUserModal");

        });
    </script>
</body>

</html>