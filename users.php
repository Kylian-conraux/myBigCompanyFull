<?php
include 'includes/db.php';
include 'includes/user.php';
include 'includes/flash.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    setFlash("Connectez vous pour poursuivre.");
    exit();
}

if (isset($_POST["formAddEdit"])) {
    $inputs = [];
    if (!empty($_POST["username"]) && !empty($_POST["mail"])) {
        $inputs = [$_POST["username"], $_POST["mail"]];
        if (!empty($_POST["id"])  && empty($_POST["password"])) {
            if (testTrimInput($inputs)) {
                if (updateUser($_POST["id"], $_POST["username"], $_POST["mail"])) {
                    setFlash("Le user a été modifié avec succès");
                } else {
                    setFlash("Erreur lors de la modification du user", "error");
                }
            } else {
                setFlash("Erreur les champs ne peuvent pas être vide", "error");
            }
        } else {
            $inputs[] = $_POST["password"];
            if (testTrimInput($inputs)) {
                $hashPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
                if (createUser($_POST["username"], $hashPassword, $_POST["mail"])) {
                    setFlash("Le user a été ajouté avec succès");
                } else {
                    setFlash("Erreur lors de l'ajout du user", "error");
                }
            } else {
                setFlash("Erreur les champs ne peuvent pas être vide", "error");
            }
        }
        header("Location: users.php");
        exit();
    }
}

if (isset($_POST["formAddEditPassword"])) {
    if (!empty($_POST["password"]) && !empty($_POST["id"])) {
        $hashPassword = password_hash($_POST["password"], PASSWORD_BCRYPT);
        if (updatePasswordUser($_POST["id"], $hashPassword)) {
            setFlash("Le mot de pas du user a été modifié avec succès");
        } else {
            setFlash("Erreur lors de la modification du mot de passe user", "error");
        }
    }
}


if (!empty($_GET["id"]) && !empty($_GET["action"]) && $_GET["action"] == "effacer" && $_GET["id"] > 0) {
    if (deleteUser($_GET["id"])) {
        setFlash("Le user a été supprimé avec succès");
    } else {
        setFlash("Erreur lors de la suppression du user", "error");
    }
    header("Location: users.php");
    exit();
}

if (!empty($_GET["action"]) && $_GET["action"] == "editer" && $_GET["id"] > 0) {
    $titre = "Editer";
    $userData = readUser($_GET["id"]);
} else {
    $titre = "Enregistrer";
    $userData = array(
        "id" => "",
        "username" => "",
        "mail" => "",
    );
}

$getUsers = listUsers();
$action = isset($_POST['action']) ? $_POST['action'] : "";

function getPasswordEdit($userData)
{
    echo '<div class="card mt-5">
                    <div class="card-header">
                        <h2 class="mb-0">Mot de passe</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="users.php" class="user-validation" novalidate>
                            <input type="hidden" name="id" value="' . $userData['id'] . '">
                            <div class="mb-3">
                                <label for="password" class="form-label">Editer le mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="" required minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Le mot de passe du user doit contenir entre 2 et 50 caractères
                                </div>
                            </div>
                            <button type="submit" name="formAddEditPassword" class="btn btn-primary">Editer</button>
                        </form>
                    </div>
                </div>';
}

function getPasswordInsc()
{
    echo '<div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="" required minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Le mot de passe du user doit contenir entre 2 et 50 caractères
                                </div>
                            </div>';
}

function testTrimInput($inputs)
{
    foreach ($inputs as $input) {
        if (trim($input) === "") {
            return false;
        }
    }
    return true;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body>
    <?php
    include './nav.php';
    /**
     *if (!isset($_SESSION['user'])) {
     *    echo "CONNEXION OBLIGATOIRE";
     *    exit();
     * }
     */
    ?>



    <div class="container">
        <?php displayFlash(); ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Liste des users</h2>
                        <!--<div class="form-group" style="width: 200px;">
                            <select class="form-select" id="departmentFilter">
                                <option value="">Tous les services</option>-->
                        <?php
                        /**
                         *foreach ($getDepartments as $department) {
                         *    echo '<option value="' . $department["id"] . '">' . $department["name"] . '</option>';
                         *}
                         */
                        ?>
                        <!-- </select> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php
                        foreach ($getUsers as $user) {
                            echo '<div class="list-group-item d-flex justify-content-between align-items-center user-item" 
                                          data-user-id="' . $user['id'] . '">
                                          <span>' . $user['username'] . '</span><span> ' . $user['mail'] . '</span> 
                                          <div class="d-flex gap-2">
                                            <a href="users.php?action=editer&id=' . $user['id'] . '" class="btn btn-sm btn-primary"><i class="fas fa-edit me-1"></i>Éditer</a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                              data-id="' . $user['id'] . '" 
                                              data-name="' . $user['username'] . '">
                                              <i class="fas fa-trash-alt me-1"></i>Supprimer</button>
                                          </div>
                                        </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0"><?php echo $titre ?> un user</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="users.php" class="user-validation" novalidate>
                            <input type="hidden" name="id" value="<?php echo $userData["id"] ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nom du user</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo $userData["username"] ?>" required minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Le nom du user doit contenir entre 2 et 50 caractères
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mail" class="form-label">Mail du user</label>
                                <input type="mail" class="form-control" id="mail" name="mail"
                                    value="<?php echo $userData["mail"] ?>" required minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Le mail du user doit contenir entre 2 et 50 caractères
                                </div>
                            </div>
                            <?php if (!isset($_GET['action'])) {
                                getPasswordInsc();
                            }
                            ?>
                            <button type="submit" name="formAddEdit" class="btn btn-primary"><?php echo $titre ?></button>
                        </form>
                    </div>
                </div>
                <?php if (isset($_GET['action']) && $_GET['action'] === "editer") {
                    getPasswordEdit($userData);
                }
                ?>

            </div>
        </div>

        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir supprimer l'utilisateur <span id="deleteName" class="fw-bold"></span> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="#" id="confirmDelete" class="btn btn-danger">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/tools.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                formValidator(".user-validation");
                initFlash();
                initDeleteModal("deleteUserModal");

            });
        </script>
</body>

</html>