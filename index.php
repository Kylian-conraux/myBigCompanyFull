<?php
include 'includes/db.php';
include 'includes/employee.php';
include 'includes/service.php';
include 'includes/flash.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    setFlash("Connectez vous pour poursuivre.");
    exit();
}

if (isset($_POST["formAddEdit"])) {
    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["department"])) {
        if (!empty($_POST["id_employee"])) {
            if (updateEmployee($db, $_POST["id_employee"], $_POST["first_name"], $_POST["last_name"], $_POST["department"])) {
                setFlash("L'employé a été modifié avec succès");
            } else {
                setFlash("Erreur lors de la modification de l'employé", "error");
            }
        } else {
            if (createEmployee($db, $_POST["first_name"], $_POST["last_name"], $_POST["department"])) {
                setFlash("L'employé a été ajouté avec succès");
            } else {
                setFlash("Erreur lors de l'ajout de l'employé", "error");
            }
        }
        header("Location: index.php");
        exit();
    }
}

if (!empty($_GET["id"]) && !empty($_GET["action"]) && $_GET["action"] == "effacer" && $_GET["id"] > 0) {
    if (deleteEmployee($db, $_GET["id"])) {
        setFlash("L'employé a été supprimé avec succès");
    } else {
        setFlash("Erreur lors de la suppression de l'employé", "error");
    }
    header("Location: index.php");
    exit();
}

if (!empty($_GET["action"]) && $_GET["action"] == "editer") {
    $titre = "Editer";
    $employeeData = getEmployeeById($db, $_GET["id"]);
} else {
    $titre = "Enregistrer";
    $employeeData = array(
        "id" => "",
        "first_name" => "",
        "last_name" => "",
        "id_department" => ""
    );
}

$getDepartments = getAllDepartments($db);
$getEmployees = getAllEmployees($db);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés</title>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Liste des employés</h2>
                        <div class="form-group" style="width: 200px;">
                            <select class="form-select" id="departmentFilter">
                                <option value="">Tous les services</option>
                                <?php
                                foreach ($getDepartments as $department) {
                                    echo '<option value="' . $department["id"] . '">' . $department["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php
                            foreach ($getEmployees as $employee) {
                                echo '<div class="list-group-item d-flex justify-content-between align-items-center employee-item" 
                                          data-department-id="' . $employee['id_department'] . '">
                                          <span>' . $employee['first_name'] . ' ' . $employee['last_name'] . ' <span class="badge bg-secondary">' . $employee['name'] . '</span></span>
                                          <div class="d-flex gap-2">
                                            <a href="index.php?action=editer&id=' . $employee['employee_id'] . '" class="btn btn-sm btn-primary"><i class="fas fa-edit me-1"></i>Éditer</a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteEmployeeModal" 
                                              data-id="' . $employee['employee_id'] . '" 
                                              data-name="' . $employee['first_name'] . ' ' . $employee['last_name'] . '">
                                              <i class="fas fa-trash-alt me-1"></i>Supprimer</button>
                                          </div>
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
                        <h2 class="mb-0"><?php echo $titre ?> un employé</h2>
                    </div>
                    <div class="card-body">
                        <form method="post" action="index.php" class="employee-validation" novalidate>
                            <input type="hidden" name="id_employee" value="<?php echo $employeeData["id"] ?>">

                            <div class="mb-3">
                                <label for="department" class="form-label">Service</label>
                                <select class="form-select" id="department" name="department" required>
                                    <option value="">Sélectionnez un service</option>
                                    <?php
                                    foreach ($getDepartments as $department) {
                                        $selected = ($employeeData["id_department"] == $department["id"]) ? ' selected' : '';
                                        echo '<option value="' . $department["id"] . '"' . $selected . '>' . $department["name"] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner un service
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?php echo $employeeData["first_name"] ?>" required minlength="2" maxlength="30">
                                <div class="invalid-feedback">
                                    Le prénom doit contenir entre 2 et 30 caractères
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?php echo $employeeData["last_name"] ?>" required minlength="2" maxlength="30">
                                <div class="invalid-feedback">
                                    Le nom doit contenir entre 2 et 30 caractères
                                </div>
                            </div>

                            <button type="submit" name="formAddEdit" class="btn btn-primary"><?php echo $titre ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer l'employé <span id="deleteName" class="fw-bold"></span> ?
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
            formValidator(".employee-validation");
            initFlash();
            initDeleteModal("deleteEmployeeModal");

            const departmentFilter = document.getElementById('departmentFilter');
            const employeeItems = document.querySelectorAll('.employee-item');
            const listGroup = document.querySelector('.list-group');

            if (departmentFilter) {
                departmentFilter.addEventListener('change', function() {
                    const selectedDepartment = this.value;
                    let hasVisibleEmployees = false;
                    let firstVisibleElement = false;
                    let lastVisibleElement = null;

                    employeeItems.forEach(item => {
                        item.classList.remove('rounded-top', 'rounded-bottom', 'border-top');
                    });

                    employeeItems.forEach(item => {
                        const itemDepartment = item.dataset.departmentId;
                        if (!selectedDepartment || selectedDepartment === itemDepartment) {
                            item.setAttribute('style', 'display: flex !important');
                            if (!firstVisibleElement) {
                                item.classList.add('rounded-top', 'border-top');
                                firstVisibleElement = true;
                            }
                            lastVisibleElement = item;
                            hasVisibleEmployees = true;
                        } else {
                            item.setAttribute('style', 'display: none !important');
                        }
                    });

                    if (lastVisibleElement) {
                        lastVisibleElement.classList.add('rounded-bottom');
                    }

                    const existingMessage = listGroup.querySelector('.empty-message');
                    if (existingMessage) {
                        existingMessage.remove();
                    }

                    if ((!hasVisibleEmployees || employeeItems.length === 0)) {
                        const emptyMessage = document.createElement('div');
                        emptyMessage.className = 'h3 empty-message';
                        emptyMessage.textContent = "Pas d'employé dans ce service";
                        listGroup.appendChild(emptyMessage);
                    }
                });

            }

        });
    </script>
</body>

</html>