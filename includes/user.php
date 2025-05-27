<?php


function createUser($username, $password, $email)
{
    global $db;
    $stmt = $db->prepare("INSERT INTO users (username, password, mail) VALUES (:username, :password, :mail)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':mail', $email);
    return $stmt->execute();
}
function readUser($id)
{
    global $db;
    $stmt = $db->prepare("SELECT id, username, mail FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($id, $username, $email)
{
    global $db;
    $stmt = $db->prepare("UPDATE users SET username = :username, mail = :mail WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':mail', $email);
    return $stmt->execute();
}


function updatePasswordUser($id, $password)
{
    global $db;
    $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':password', $password);
    return $stmt->execute();
}

function deleteUser($id)
{
    global $db;
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
function listUsers()
{
    global $db;
    $stmt = $db->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function authenticateUser($mail, $password)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE mail = :mail");
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $user = readUser($user['id']);
            return $user;
        }
    }

    return false;
}
function isLoggedIn()
{
    return isset($_SESSION['user']);
}
function loginUser($user)
{
    $_SESSION['user'] = $user;
}
function logoutUser()
{
    unset($_SESSION['user']);
}
function getCurrentUser()
{
    return isLoggedIn() ? $_SESSION['user'] : null;
}
