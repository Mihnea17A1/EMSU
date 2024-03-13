<?php

@include 'config.php'; //conectarea la baza de date

session_start();

if (!isset($_SESSION['home_page_name'])) { //nu se poate accesa pagina de home daca nu ai trecut de cea de login cu succes
   header('location:login_form.php');
}

// Query to find top 3 sali based on total price of dotari
$query_sali = mysqli_query($conn, "
    SELECT 
        s.nume AS nume_sala, 
        SUM(d.pret * ds.cantitate) AS total_pret
    FROM 
        sali s
    JOIN 
        dotari_sali ds ON s.sala_ID = ds.sala_ID
    JOIN 
        dotari d ON ds.dotare_ID = d.dotare_ID
    GROUP BY 
        s.sala_ID
    ORDER BY 
        total_pret DESC
    LIMIT 3
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home page</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="../css/sali_page.css">

</head>

<body>

   <?php include "navbar.php" ?>

   <div class="table-wrapper">

      <div style="border: 1px solid black;">
         <h3 style="font-size: xx-large; text-align: center; line-height: normal;">
            Bun venit, <span class="greeting"><?php echo $_SESSION['home_page_name'] ?></span> !
         </h3>

         <br>
         <hr size="4" width="100%" color="white"><br>

         <p style="color: black; font-size:xx-large; text-align:center"><b>Top 3 sali renovate</b></p>

      </div>
      <table class="fl-table">
         <thead>
            <tr style="font-size: large">
               <th >Nume sala</th>
               <th>Pretul Total al Dotarilor</th>
            </tr>
         </thead>
         <tbody style="font-size: large">
            <?php
            if (mysqli_num_rows($query_sali) > 0) {
               while ($row_sali = mysqli_fetch_assoc($query_sali)) {
                  echo "
            <tr style='font-size: large'>
                <td style='font-size: large'>" . $row_sali['nume_sala'] . "</td>
                <td style='font-size: large'>" . $row_sali['total_pret'] . "</td>
            </tr>
            ";
               }
            }
            ?>
         </tbody>
      </table>
   </div>
</body>

</html>