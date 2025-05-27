<?php

function createDepartment($db, $name) {
    try {
        $insert = $db->prepare('INSERT INTO departments SET name = :name');
        $insert->bindValue(':name', trim(htmlspecialchars($name)), PDO::PARAM_STR);
        $insert->execute();
        return $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function updateDepartment($db, $id, $name) {
    try {
        $update = $db->prepare('UPDATE departments SET name = :name WHERE id = :id');
        $update->bindValue(':name', trim(htmlspecialchars($name)), PDO::PARAM_STR);
        $update->bindValue(':id', $id, PDO::PARAM_INT);
        return $update->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function deleteDepartment($db, $id) {
    try {
        $check = $db->prepare('SELECT COUNT(*) FROM employees WHERE id_department = :id');
        $check->bindValue(':id', $id, PDO::PARAM_INT);
        $check->execute();
        if ($check->fetchColumn() > 0) {
            return false;
        }

        $delete = $db->prepare('DELETE FROM departments WHERE id = :id');
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        return $delete->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function getAllDepartments($db) {
    try {
        $query = $db->prepare('SELECT * FROM departments');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getDepartmentById($db, $id) {
    try {
        $query = $db->prepare('SELECT * FROM departments WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

/*
$getDepartments = getAllDepartments($db);
Comme cette fonction retourne un fetchAll (plusieurs lignes) en clés nommées, vous pouvez accéder aux données de chaque ligne avec un foreach comme ceci :
foreach ($getDepartments as $department) {
    echo $department["name"];
} 
    
$departmentData = getDepartmentById($db, $_GET["id"]);
Comme cette fonction retourne un fetch (une seule ligne) en clés nommées, vous pouvez accéder aux données directement sur la variable comme ceci :    
echo $departmentData["name"];
*/