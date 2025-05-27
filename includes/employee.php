<?php

function createEmployee($db, $firstName, $lastName, $departmentId) {
    try {
        $insert = $db->prepare('INSERT INTO employees SET first_name = :first_name, last_name = :last_name, id_department = :id_department');
        $insert->bindValue(':first_name', trim(htmlspecialchars($firstName)), PDO::PARAM_STR);
        $insert->bindValue(':last_name', trim(htmlspecialchars($lastName)), PDO::PARAM_STR);
        $insert->bindValue(':id_department', $departmentId, PDO::PARAM_INT);
        $insert->execute();
        return $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

function updateEmployee($db, $id, $firstName, $lastName, $departmentId) {
    try {
        $update = $db->prepare('UPDATE employees SET first_name = :first_name, last_name = :last_name, id_department = :id_department WHERE id = :id_employee');
        $update->bindValue(':first_name', trim(htmlspecialchars($firstName)), PDO::PARAM_STR);
        $update->bindValue(':last_name', trim(htmlspecialchars($lastName)), PDO::PARAM_STR);
        $update->bindValue(':id_department', $departmentId, PDO::PARAM_INT);
        $update->bindValue(':id_employee', $id, PDO::PARAM_INT);
        return $update->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function deleteEmployee($db, $id) {
    try {
        $delete = $db->prepare('DELETE FROM employees WHERE id = :id');
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        return $delete->execute();
    } catch (PDOException $e) {
        return false;
    }
}

function getAllEmployees($db) {
    try {
        $query = $db->prepare('SELECT employees.id AS employee_id, employees.first_name, employees.last_name, 
                                employees.id_department, departments.name 
                         FROM employees 
                         INNER JOIN departments ON employees.id_department = departments.id');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

function getEmployeeById($db, $id) {
    try {
        $query = $db->prepare('SELECT * FROM employees WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}