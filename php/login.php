<?php

@include 'config.php'; //conectarea la baza de date

session_start();

if (isset($_POST['login'])) {

	//obtinerea datelor completate in formularul de login
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$parola = md5($_POST['parola']);

	$select = " SELECT * FROM angajati WHERE email = '$email' && parola = '$parola' ";

	$result = mysqli_query($conn, $select);

	//verificarea ca datele exista in baza de date 
	if (mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_array($result);

		$_SESSION['home_page_name'] = $row['prenume'];
		$_SESSION['angajat_ID'] = $row['angajat_ID'];
		header('location:home_page.php');
	} else {
		$error[] = 'Ati introdus gresit email-ul sau parola!';
	}
};
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<title>Conectare</title>
	<link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">
	<link rel="stylesheet" href="../css/style_login.css">
</head>

<body>
	<div class="center">
		<h1>Conectare</h1>
		<form method="post">
			<div class="txt_field">
				<input type="email" name="email" required>
				<span></span>
				<label>Email</label>
			</div>

			<div class="txt_field">
				<input type="password" name="parola" required>
				<span></span>
				<label>Parola</label>
			</div>

			<input type="submit" name="login" class="button-30" value="Conectare">
			<div class="signup_link">
				Inca nu ai cont? <a href="register.php">Inregistreaza-te!</a>
			</div>
		</form>
	</div>

</body>

</html>