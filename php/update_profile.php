<?php

@include 'config.php'; //conectarea la baza de date

session_start();

$angajat_id = $_SESSION['angajat_ID']; //obtinerea ID-ului userului conectat

if (isset($_POST['delete_account'])) {
   // Validate the input (check if it's a positive integer)
   if (!filter_var($angajat_id, FILTER_VALIDATE_INT) || $angajat_id <= 0) {
      // Invalid input, handle the error or return an error message
      echo "Invalid angajat_ID.";
   } else {
      // Create a prepared statement
      $stmt = mysqli_prepare($conn, "DELETE FROM angajati WHERE angajat_ID = ?");

      if ($stmt) {
         // Bind the parameter
         mysqli_stmt_bind_param($stmt, "i", $angajat_id);

         // Execute the statement
         if (mysqli_stmt_execute($stmt)) {
            // Deletion successful
            echo "Account deleted successfully!";
            header('Location: ../php/login.php');
            exit();
         } else {
            // Handle the error, such as returning an error message
            echo "Error deleting record: " . mysqli_error($conn);
         }

         // Close the statement
         mysqli_stmt_close($stmt);
      } else {
         // Handle the error, such as returning an error message
         echo "Error creating prepared statement: " . mysqli_error($conn);
      }
   }
}

if (isset($_POST['update'])) {

   //obtinerea datelor din formularul de inregistrare
   $update_departament = mysqli_real_escape_string($conn, $_POST['update_departament']);
   $update_nume = mysqli_real_escape_string($conn, $_POST['update_nume']);
   $update_prenume = mysqli_real_escape_string($conn, $_POST['update_prenume']);
   $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
   $update_telefon = mysqli_real_escape_string($conn, $_POST['update_telefon']);
   $update_strada = mysqli_real_escape_string($conn, $_POST['update_strada']);
   $update_numar = mysqli_real_escape_string($conn, $_POST['update_numar']);
   $update_oras = mysqli_real_escape_string($conn, $_POST['update_oras']);
   $update_judet = mysqli_real_escape_string($conn, $_POST['update_judet']);
   $update_sex = mysqli_real_escape_string($conn, $_POST['update_sex']);
   $update_data_nasterii = date('Y-m-d');
   $update_salariu = mysqli_real_escape_string($conn, $_POST['update_salariu']);

   //actualizarea datelor din baza de date din tabela USER
   mysqli_query($conn, "UPDATE `angajati` SET departament_id = '$update_departament', nume = '$update_nume', prenume = '$update_prenume',
    email = '$update_email', telefon = '$update_telefon', strada = '$update_strada', numar = '$update_numar', oras = '$update_oras',
    judet = '$update_judet', sex = '$update_sex', data_nasterii = '$update_data_nasterii', salariu = '$update_salariu' WHERE angajat_ID = '$angajat_id'");

// Fetch the current hashed password from the database
$query = "SELECT parola FROM angajati WHERE angajat_ID = '$angajat_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $current_hashed_pass = $row['parola'];

    // Continue with your password update logic
    $old_pass_input = md5($_POST['parola_introdusa']); // Consider using a more secure hashing method
    $new_pass = md5($_POST['update_parola']); // Same as above

    // Verify the old password
    if (!empty($new_pass) && !empty($old_pass_input)) {
        if ($old_pass_input != $current_hashed_pass) {
            $message_not_ok[] = '!Parola veche nu corespunde celei din baza de date sau nu ati incercat schimbarea parolei!';
        } else {
            // Update the password in the USER table
            $update_query = "UPDATE `angajati` SET parola = '$new_pass' WHERE angajat_ID = '$angajat_id'";
            if (mysqli_query($conn, $update_query)) {
                $message_ok[] = 'Profil actualizat cu succes!';
            } else {
                // Handle error in update
                $message_not_ok[] = 'Error updating password.';
            }
        }
    }
} else {
    // Handle error in fetch
    $message_not_ok[] = 'Error fetching current password.';
}


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yEx1q6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

   <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">
   <link rel="stylesheet" href="../css/style_register.css" />

</head>

<body class="form-v10">

   <?php include "./navbar.php" ?>

   <div class="page-content">
      <div class="form-v10-content">

         <?php
         //obtinerea datelor din tabela USER pentru precompletarea campurilor din formular
         $select = mysqli_query($conn, "SELECT * FROM `angajati` WHERE angajat_ID = '$angajat_id'");
         if ($select) {
            if (mysqli_num_rows($select) > 0) {
               $fetch = mysqli_fetch_assoc($select);
            }
         } else {
            // Handle the database query error here, such as displaying an error message or logging the error.
            echo "Database query error: " . mysqli_error($conn);
         }
         ?>

         <form class="form-detail" action="" method="post">
            <?php
            if (isset($message_ok)) {
               foreach ($message_ok as $message_ok) {
                  echo '<div class="message_ok">' . $message_ok . '</div>';
               }
            }
            if (isset($message_not_ok)) {
               foreach ($message_not_ok as $message_not_ok) {
                  echo '<div class="message_not_ok">' . $message_not_ok . '</div>';
               }
            }
            ?>
            <!-- formularul de actualizare al datelor profilului utilizatorului -->
            <div class="form-left">
               <h2>Actualizare - Informatii Generale </h2>

               <?php
               $sex = $fetch['sex'];
               if ($sex === 'M') {
                  $title = 'Domnul';
               } elseif ($sex === 'F') {
                  $title = 'Doamna';
               } else {
                  $title = 'PULA'; // Handle other cases if needed
               }
               ?>

               <div class="form-row">
                  <select name="update_sex">
                     <option class="option" value="<?php echo $sex ?>"><?php echo $title ?></option>
                     <option class="option" value="F">Doamna</option>
                     <option class="option" value="M">Domnul</option>
                  </select>
                  <span class="select-btn">
                     <i class="zmdi zmdi-chevron-down"></i>
                  </span>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="update_prenume" class="input-text" placeholder="Prenume" value="<?php echo $fetch['prenume']; ?>">
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="update_nume" class="input-text" placeholder="Nume" value="<?php echo $fetch['nume']; ?>">
                  </div>
               </div>

               <?php
               $departament = $fetch['departament_ID'];
               $departament_number = $fetch['departament_ID'];

               switch ($departament) {
                  case 1:
                     $departament = 'IEE';
                     break;
                  case 2:
                     $departament = 'ENER';
                     break;
                  case 3:
                     $departament = 'ACS';
                     break;
                  case 4:
                     $departament = 'ETTI';
                     break;
                  case 5:
                     $departament = 'IMM';
                     break;
                  case 6:
                     $departament = 'IIR';
                     break;
                  case 7:
                     $departament = 'ISB';
                     break;
                  case 8:
                     $departament = 'TR';
                     break;
                  case 9:
                     $departament = 'IA';
                     break;
                  case 10:
                     $departament = 'SIM';
                     break;
                  case 11:
                     $departament = 'ICB';
                     break;
                  case 12:
                     $departament = 'ILS';
                     break;
                  case 13:
                     $departament = 'SA';
                     break;
                  case 14:
                     $departament = 'IMED';
                     break;
                  case 15:
                     $departament = 'AIM';
                     break;
                  case 16:
                     $departament = 'DFCDSS';
                     break;
               }

               ?>

               <div class="form-row">
                  <select name="update_departament">
                     <option value="<?php echo $departament_number ?>"><?php echo $departament ?></option>
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


               <div class="form-row"> Parola veche:
                  <input type="hidden" name="current_pass" value="<?php echo $fetch['parola'] ?>">
                  <input type="password" name="parola_introdusa" class="parola" id="parola_introdusa" placeholder="Introduceti parola veche:">
               </div>
               <div class="form-row">
                  <input type="password" name="update_parola" class="parola" id="update_parola" placeholder="Introduceti parola noua:">
               </div>
               <div class="form-group">
                  <img src="../images/update.png" alt="update pic" width="60%" style="display: block; margin: 0 auto;">
               </div>
            </div>

            <div class="form-right">
               <h2>Actualizare - Detalii de Contact</h2>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="update_judet" class="zip" id="update_judet" placeholder="Judet" value="<?php echo $fetch['judet']; ?>">
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="update_oras" id="update_oras" placeholder="Oras" value="<?php echo $fetch['oras']; ?>">
                  </div>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="update_numar" class="zip" id="update_numar" placeholder="Numar" value="<?php echo $fetch['numar']; ?>">
                  </div>
                  <div class="form-row form-row-2">
                     <input type="text" name="update_strada" class="street" id="update_strada" placeholder="Strada" value="<?php echo $fetch['strada']; ?>">
                  </div>
               </div>

               <div class="form-group">
                  <div class="form-row form-row-1">
                     <input type="text" name="update_telefon" id="update_telefon" placeholder="Numar de telefon" required pattern="0\d{9}" value="<?php echo $fetch['telefon']; ?>">
                  </div>
               </div>

               <div class="form-row">
                  <input type="text" name="update_email" id="update_email" class="input-text" required pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" placeholder="Email" value="<?php echo $fetch['email']; ?>">
               </div>

               <div class="form-row form-row-1">
                  <label for="data_nasterii" style="color: white; padding-left: 17px;">Data de naștere</label>
                  <input type="date" id="data_nasterii" name="update_data_nasterii" value="<?php echo $fetch['data_nasterii']; ?>">
               </div>

               <div class="form-row form-row-1">
                  <input type="text" name="update_salariu" id="salriu" placeholder="Salariu" value="<?php echo $fetch['salariu']; ?>">
               </div>

               <div class="form-row-last">
                  <input type="submit" id="back" name="back" class="register" value="Inapoi">
                  <input type="submit" id="update" name="update" class="register" value="Actualizare" style="margin-right: auto;">
               </div>

               <form id="delete-form" method="post" action="update_profile.php">
                  <div class="form-row-last">
                     <input type="submit" onclick="return confirmDelete()" name="delete_account" class="register" value="Sterge contul" style="background-color: #FF9999;">
                  </div>
               </form>

            </div>
         </form>
      </div>
   </div>

   </div>

   <script>
    function confirmDelete() {
        var confirmAction = confirm('Esti sigur ca vrei sa stergi complet contul? Actiunea nu este reversibila!');
        return confirmAction;
    }
</script>

</body>
<script src="../javascript/navbar.js"></script>

</html>