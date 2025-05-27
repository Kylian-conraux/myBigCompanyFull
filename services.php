<?php
include 'includes/db.php';
include 'includes/service.php';
include 'includes/flash.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    setFlash("Connectez vous pour poursuivre.");
    exit();
}

if (isset($_POST["formAddEdit"])) {
    if (!empty($_POST["name"])) {
        if (!empty($_POST["id_department"]) && $_POST["id_department"] !== "none") {
            if (updateDepartment($db, $_POST["id_department"], $_POST["name"])) {
                setFlash("Le service a été modifié avec succès");
            } else {
                setFlash("Erreur lors de la modification du service", "error");
            }
        } else {
            if (createDepartment($db, $_POST["name"])) {
                setFlash("Le service a été ajouté avec succès");
            } else {
                setFlash("Erreur lors de l'ajout du service", "error");
            }
        }
        header("Location: services.php");
        exit();
    }
}

if (!empty($_GET["id"]) && !empty($_GET["action"]) && $_GET["action"] == "effacer" && $_GET["id"] > 0) {
    if (deleteDepartment($db, $_GET["id"])) {
        setFlash("Le service a été supprimé avec succès");
    } else {
        setFlash("Erreur lors de la suppression du service", "error");
    }
    header("Location: services.php");
    exit();
}

if (!empty($_GET["action"]) && $_GET["action"] == "editer" && $_GET["id"] > 0) {
    $titre = "Editer";
    $departmentData = getDepartmentById($db, $_GET["id"]);
} else {
    $titre = "Enregistrer";
    $departmentData = array(
        "id" => "none",
        "name" => ""
    );
}

$getDepartments = getAllDepartments($db);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
</head>

<body>
    <?php

    include './nav.php';

    ?>

    <div class="container">
        <?php displayFlash(); ?>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Liste des services</h2>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php
                            foreach ($getDepartments as $department) {
                                echo '<div class="list-group-item d-flex justify-content-between align-items-center"><span>' . $department['name'] . '</span>
                                <div class="d-flex gap-2"><a href="services.php?action=editer&id=' . $department['id'] . '" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i>Éditer</a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteServiceModal" data-id="' . $department['id'] . '" data-name="' . $department['name'] . '">
                                <i class="fas fa-trash-alt me-1"></i>Supprimer</button></div>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0"><?php echo $titre ?> un service</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="services.php" class="service-validation" novalidate>
                            <input type="hidden" name="id_department" value="<?php echo $departmentData["id"] ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du service</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo $departmentData["name"] ?>" required minlength="2" maxlength="50">
                                <div class="invalid-feedback">
                                    Le nom du service doit contenir entre 2 et 50 caractères
                                </div>
                            </div>
                            <button type="submit" name="formAddEdit" class="btn btn-primary"><?php echo $titre ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer le service <span id="deleteName" class="fw-bold"></span> ?
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
            formValidator(".service-validation");
            initFlash();
            initDeleteModal("deleteServiceModal");
        });
    </script>
</body>

</html>