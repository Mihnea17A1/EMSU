<?php
    @include 'config.php'; //conectarea la baza de date
    session_start();
    //interogare pentru obtinerea datelor din tabela FILM
    $query = "SELECT * FROM sali";
    $result = mysqli_query($conn,$query);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sali</title>

   <link rel="stylesheet" type="text/css" href="../css/montserrat-font.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <link rel="stylesheet" href="../css/sali_page.css">
 
</head>
<body>

<?php include "navbar.php"?>

<div class="table-wrapper">
    <table class="fl-table">
    <thead>
        <tr>
            <th>Nume</th>
            <th>Tip</th>
            <th>Capacitate</th>
            <th>Dotari</th>
            <th>Departament</th>
            <th>Cladiri</th>
        </tr>
    </thead>
        <tbody>
        <?php
            while ($row = mysqli_fetch_assoc($result))
            {   
            echo "
            <tr>
                <td>".$row['nume']."</td>
                <td>".$row['tip']."</td>
                <td>".$row['capacitate']."</td>
                <td><a href='dotare.php?dotareid=".$row['sala_ID']."'> 
                        <p><i class='fa fa-eye' aria-hidden='true'></i> Dotari</p>
                    </a>
                </td>
                <td><a href='departament.php?departamentid=".$row['sala_ID']."'> 
                        <p><i class='fa fa-eye' aria-hidden='true'></i> Departament</p>
                    </a>
                </td>
                <td><a href='cladire.php?cladireid=".$row['sala_ID']."'>
                        <p> <i class='fa fa-eye' aria-hidden='true'></i> Cladire</p>
                    </a>
                </td>
            </tr>
            ";
            } 
            ?> 
            </tbody>  
    </table>
</div>

</body>
</html>