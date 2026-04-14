<?php
$dbhost     = "localhost";
$dbname     = "test";
$dbuser     = "root";
$dbpass     = "";

$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();
$user_check = $_SESSION['login_user'];

$result = $conn->prepare("SELECT * FROM users WHERE email = :user_check OR username = :user_check");
$result->execute(array(":usercheck" => $user_check));

$row = $result->fetch(PDO::FETCH_ASSOC);

$login_session = $row['username'];
$ln_session = $row['last_name'];
$fn_session = $row['first_name'];
$user_id = $row['id'];
// $user_passwords = $row['password'];

if (!isset($login_session)) {
    $conn = null;
    header('Location: ../index.php');
}
