<?php
function validateUsername($username){
    if (strlen($username) < 4 || strlen($username) > 8 || empty($username)) {
        throw new Exception("User name must be between 4 an 8 symbols.");
    }
    $patern = '#^[A-Za-z0-9]+$#';
    if (!preg_match($patern, $username)) {
        throw new Exception("User name must not contain special characters.");
    }
    else{
        return true;
    }
}
function validatePasswords($password, $confirmPass){
    $patern = '#^(?=(.*\d){2,})(?=.*[A-Z]{1,})(?=.*[a-zA-Z]{2,})(?=.*[!@~\#$%^&?]{1,})[0-9a-zA-Z!@~\#?$^%&`]+$#';
    if (!preg_match($patern, $password)) {
        throw new Exception("Password must contains at least 1 special symbol, 1 uppercase letter, 2 numbers and 3 letters.");
    }
    if ($password != $confirmPass) {
        throw new Exception("Passwords do not match.");
    }
}
function validateEmail($email){
    $patern = '#^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$#';
    if (!preg_match($patern, $email)) {
        throw new Exception("Please fill valid email.");
    }
}
function validatePhone($phone){
    $patern = '#^[0-9]{10,10}$#';
    if (strlen($phone) != 10 || !preg_match($patern, $phone)) {
        throw new Exception("Phone must be 10 digits.");
    }
}
function validateAge($age){
    if (intval($age) < 18) {
        throw new Exception("You must be at least 18 years old");
    }
}
function checkUsernameExists($username, $pdo){
    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row['num'] > 0) {
        throw new Exception('That username already exists!');
    }
}
function checkEmailExists($email, $pdo){
    $sql = "SELECT COUNT(email) AS test FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['test'] > 0) {
        throw new Exception('That email already exists!');
    }
}
function verifyUserPassword($id, $password,$passwordHash, $pdo){
    $query = "SELECT * FROM users WHERE id = " .$id;
    $stmt = $pdo->prepare( $query );


    // execute our query
    $stmt->execute();

    // store retrieved row to a variable
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!password_verify($password, $passwordHash)) {
        throw new Exception("Wrong password!");

    }
}