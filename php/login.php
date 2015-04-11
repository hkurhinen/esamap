<?php
session_start();

$hash = '$2y$10$idPiitSvEEXRW3oL7KJxxu2qTjH0xTj7g/kOb8qoG5vTH4uhI3MpO';

$user = $_POST['username'];
$password = $_POST['password'];


if($user == "admin"){

	if (password_verify($password, $hash)) {
		$_SESSION['loggedin'] = true;
		header("Location: ../index.php");
	}
}

header("Location: ../admin-login.html");
?>