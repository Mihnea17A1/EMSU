<?php

@include 'config.php'; //conectarea la baza de date

if (isset($_POST['register'])) {

   //obtinerea datelor din formularul de inregistrare
   $departament = mysqli_real_escape_string($conn, $_POST['departament']);
   $nume = mysqli_real_escape_string($conn, $_POST['nume']);
   $prenume = mysqli_real_escape_string($conn, $_POST['prenume']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $parola = md5($_POST['parola']);
   $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
   $strada = mysqli_real_escape_string($conn, $_POST['strada']);
   $numar = mysqli_real_escape_string($conn, $_POST['numar']);
   $oras = mysqli_real_escape_string($conn, $_POST['oras']);
   $judet = mysqli_real_escape_string($conn, $_POST['judet']);
   $sex = mysqli_real_escape_string($conn, $_POST['sex']);
   $data_nasterii = date('Y-m-d');
   $salariu = mysqli_real_escape_string($conn, $_POST['salariu']);

   $select = " SELECT * FROM angajati WHERE email = '$email' && parola = '$parola' ";

   $result = mysqli_query($conn, $select);

   //se verifica daca nu mai exista acest utilizator in baza de date
   if (mysqli_num_rows($result) > 0) {
      $error[] = 'Din pacate, utilizatorul deja exista!';
   } else {
      //daca nu mai exista atunci se insereaza datele in baza de date in tabela USER
      $insert = "INSERT INTO angajati(departament_ID, nume, prenume, email, parola, telefon, strada, numar, oras, judet, sex, data_nasterii, salariu) VALUES
      ('$departament','$nume','$prenume','$email','$parola', '$telefon', '$strada', '$numar', '$oras', '$judet', '$sex', '$data_nasterii', '$salariu')";
      mysqli_query($conn, $insert);
      header('location:login.php');
   }
};
?>

<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8">
   <title>Form-v10 by Colorlib</title>

   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">

   <link rel="stylesheet" href="../css/style_register.css" />
   <meta name="robots" content="noindex, follow">
</head>

<body class="form-v10">
   <div class="page-content">
      <div class="form-v10-content">
         <form class="form-detail" action="#" method="post" id="myform">
            <div class="form-left">
               <h2>Informatii Generale</h2>

               <div class="form-row">
                  <select name="sex">
                     <option class="option" value="title">Titlu</option>
                     <option class="option" value="F">Doamna</option>
                     <option class="option" value="M">Domnul</option>
                  </select>
                  <span class="select-btn">
                     <i class="zmdi zmdi-chevron-down"></i>
                  </span>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="prenume" id="prenume" class="input-text" placeholder="Prenume" required>
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="nume" id="nume" class="input-text" placeholder="Nume" required>
                  </div>
               </div>

               <div class="form-row">
                  <select name="departament">
                     <option value="departament">Departament</option>
                     <option value="1">IEE</option>
                     <option value="2">ENER</option>
                     <option value="3">ACS</option>
                     <option value="4">ETTI</option>
                     <option value="5">IMM</option>
                     <option value="6">IIR</option>
                     <option value="7">ISB</option>
                     <option value="8">TR</option>
                     <option value="9">IA</option>
                     <option value="10">SIM</option>
                     <option value="11">ICB</option>
                     <option value="12">ILS</option>
                     <option value="13">SA</option>
                     <option value="14">IMED</option>
                     <option value="15">AIM</option>
                     <option value="16">DFCDSS</option>
                  </select>
                  <span class="select-btn">
                     <i class="zmdi zmdi-chevron-down"></i>
                  </span>
               </div>

               <div class="form-row">
                  <input type="password" name="parola" class="parola" id="parola" placeholder="Noua parola" required>
               </div>
               <div class="form-group">
                  <img src="../images/registration.gif" alt="Registration GIF" width="80%" style="display: block; margin: 0 auto;">
               </div>
            </div>

            <div class="form-right">
               <h2>Detalii de Contact</h2>
  
               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="judet" class="zip" id="judet" placeholder="Judet" required maxlength="2">
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="oras" id="oras" placeholder="Oras" required>
                  </div>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="numar" class="zip" id="numar" placeholder="Numar" required>
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="strada" class="street" id="strada" placeholder="Strada" required>
                  </div>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="telefon" id="telefon" placeholder="Numar de telefon" required pattern="0\d{9}">
                  </div>
               </div>

               <div class="form-row">
                  <input type="text" name="email" id="email" class="input-text" required pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" placeholder="Email">
               </div>

               <div class="form-row form-row-1">
                  <label for="data_nasterii" style="color: white; padding-left: 17px;">Data de naștere:</label>
                  <input type="date" id="data_nasterii" name="data_nasterii">
               </div>

               <div class="form-row form-row-1">
                  <input type="text" name="salariu" id="salriu" placeholder="Salariu">
               </div>

               <div></div>
               <h2><p>Ai deja un cont? <a href="login.php" style="color: white">Conecteaza-te acum</a></p></h2>

               <div class="form-row-last">
                  <input type="submit" name="register" class="register" value="Inregistrare">
               </div>
            </div>
         </form>
      </div>
   </div>

</body>

</html>