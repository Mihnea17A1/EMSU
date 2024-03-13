<?php
@include 'config.php'; //conectarea la baza de date
session_start();


//===========interogare 1=========================================

$query_departamente = "SELECT departament_ID, nume FROM departamente";
$result_departamente = mysqli_query($conn, $query_departamente);
$row_int1 = null;
$departamentNume = '';

if (isset($_POST['show_int1'])) {
    $departamentID = mysqli_real_escape_string($conn, $_POST['form_departamente']);

    // Interogare pentru a obține salariul mediu și numele departamentului
    $query_int1 = mysqli_query($conn, "SELECT d.nume AS DepartamentNume, AVG(a.salariu) AS AverageSalary
                                       FROM angajati a
                                       JOIN departamente d ON a.departament_ID = d.departament_ID
                                       WHERE d.departament_ID = '$departamentID'
                                       GROUP BY d.nume");

    if ($query_int1 && mysqli_num_rows($query_int1) > 0) {
        $row_int1 = mysqli_fetch_assoc($query_int1);
        $departamentNume = $row_int1['DepartamentNume']; // Numele departamentului
    }
}

//===========interogare 2=========================================

$query_sali = "SELECT sala_ID, nume FROM sali"; // Modificat pentru a selecta sălile
$result_sali = mysqli_query($conn, $query_sali); // Rezultatele pentru săli

$totalCosts = []; // Inițializăm un array pentru a stoca costurile totale

if (isset($_POST['show_int2'])) {
    $salaID = mysqli_real_escape_string($conn, $_POST['form_sali']); // Obținem ID-ul sălii selectate

    // Interogarea pentru a calcula costul total al dotărilor pentru sala selectată
    $query_int2 = "SELECT s.nume AS RoomName, IFNULL(SUM(d.pret * ds.cantitate), 0) AS TotalCost
                   FROM sali s
                   LEFT JOIN dotari_sali ds ON s.sala_ID = ds.sala_ID
                   LEFT JOIN dotari d ON ds.dotare_ID = d.dotare_ID
                   WHERE s.sala_ID = '$salaID'
                   GROUP BY s.nume";

    $result_int2 = mysqli_query($conn, $query_int2);

    if (mysqli_num_rows($result_int2) > 0) {
        while ($row = mysqli_fetch_assoc($result_int2)) {
            $totalCosts[$row['RoomName']] = $row['TotalCost']; // Adăugăm costul total în array
        }
    } else {
        $totalCosts = []; // Setăm costurile totale ca un array gol dacă nu există dotări
    }
}

// //===========interogare 3=========================================

// // Numarul de sali din cladirea X a carei suprafata totala e mai mare de Y.

// Inițializăm un array pentru a stoca clădirile și numărul sălilor

$query_cladiri = "SELECT cladire_ID, nume FROM cladiri"; // Interogare pentru a selecta clădirile
$result_cladiri = mysqli_query($conn, $query_cladiri); // Rezultatele pentru clădiri

$saliInfo = []; // Inițializăm un array pentru a stoca informațiile sălilor

if (isset($_POST['show_sali'])) {
    $cladireID = mysqli_real_escape_string($conn, $_POST['form_cladiri']); // Obținem ID-ul clădirii selectate
    $suprafataMinima = mysqli_real_escape_string($conn, $_POST['form_suprafata']); // Obținem valoarea suprafeței minime

    // Modificăm interogarea pentru a selecta numele și suprafața sălilor din clădirea selectată cu suprafața totală mai mare decât valoarea specificată
    $query_sali = "SELECT s.nume AS SalaNume, s.capacitate AS SuprafataSala
    FROM sali s
    JOIN cladiri c ON s.cladire_ID = c.cladire_ID
    WHERE c.cladire_ID = '$cladireID' AND s.capacitate > '$suprafataMinima'";

    $result_sali = mysqli_query($conn, $query_sali);

    while ($row = mysqli_fetch_assoc($result_sali)) {
        $saliInfo[] = [
            'nume' => $row['SalaNume'],
            'suprafata' => $row['SuprafataSala']
        ]; // Adăugăm numele și suprafața sălii în array
    }
}


// // //===========interogare 4=========================================


// Inițializăm variabilele pentru a stoca informațiile angajatului
$employeeDept = '';
$employeeSalary = '';
$employeeInfo = '';

// Obținem lista angajaților pentru dropdown
$query_employees = "SELECT angajat_ID, nume, prenume FROM angajati ORDER BY nume, prenume";
$result_employees = mysqli_query($conn, $query_employees);

if (isset($_POST['show_employee_info'])) {
    $employeeID = mysqli_real_escape_string($conn, $_POST['employee_id']);

    // Interogarea pentru a obține departamentul și salariul angajatului selectat
    $query_employee_info = "SELECT d.nume AS Departament, a.salariu AS Salariu
                            FROM angajati a
                            JOIN departamente d ON a.departament_ID = d.departament_ID
                            WHERE a.angajat_ID = '$employeeID'";

    $result_employee_info = mysqli_query($conn, $query_employee_info);

    if ($result_employee_info && mysqli_num_rows($result_employee_info) > 0) {
        $employeeInfo = mysqli_fetch_assoc($result_employee_info);
    } else {
        $employeeInfo = "Informații indisponibile pentru angajatul selectat.";
    }
}


// // //===========interogare 5=========================================

// // // Building Construction Dates and Total Room Capacities
// // Afiseaza capacitatea totala a fiecarei sali a caldirii selectate impreuna cu anul construit.

$query_building = "SELECT cladire_ID, nume FROM cladiri"; // Selectăm doar ID-ul și numele pentru a popula dropdown-ul
$result_building = mysqli_query($conn, $query_building);

$buildingInfo = [];

if (isset($_POST['show_int5'])) {
    $selectedBuildingID = mysqli_real_escape_string($conn, $_POST['form_building']); // Vom folosi ID-ul pentru a evita ambiguitatea

    // Interogarea SQL este actualizată pentru a utiliza ID-ul în locul numelui
    $query = "SELECT c.nume AS BuildingName, c.data_constructie AS ConstructionDate, SUM(s.capacitate) AS TotalCapacity
              FROM cladiri c
              JOIN sali s ON c.cladire_ID = s.cladire_ID
              WHERE c.cladire_ID = ?
              GROUP BY c.cladire_ID"; // Grupăm după ID-ul clădirii pentru precizie

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $selectedBuildingID); // "i" deoarece ID-ul este un număr întreg
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $buildingInfo = $row;
        } else {
            $buildingInfo = ["error" => "Nu există informații pentru clădirea selectată."];
        }

        mysqli_stmt_close($stmt);
    } else {
        $buildingInfo = ["error" => "Eroare la pregătirea interogării: " . mysqli_error($conn)];
    }
}

// // //===========interogare 6=========================================

$salaDotariInfo = [];

if (isset($_POST['show_dotari_sala'])) {
    $salaID = mysqli_real_escape_string($conn, $_POST['sala_id']);
    $pretMinim = mysqli_real_escape_string($conn, $_POST['pret_minim']);
    $stareDotare = mysqli_real_escape_string($conn, $_POST['stare_dotare']);

    // Interogarea pentru a obține dotările din sala selectată cu un preț mai mare decât valoarea introdusă
    $query_dotari_sala = "SELECT d.nume AS DotareNume, d.pret AS Pret, ds.stare AS Stare
                          FROM dotari d
                          JOIN dotari_sali ds ON d.dotare_ID = ds.dotare_ID
                          JOIN sali s ON ds.sala_ID = s.sala_ID
                          WHERE s.sala_ID = '$salaID' AND d.pret > '$pretMinim' AND ds.stare = '$stareDotare'";

    $result_dotari_sala = mysqli_query($conn, $query_dotari_sala);

    while ($row = mysqli_fetch_assoc($result_dotari_sala)) {
        $salaDotariInfo[] = $row;
    }
}

// // //===========interogare 7=========================================

$selectedDepartment = '';
$employeesAboveMedian = [];
$medianSalary = 0;

if (isset($_POST['show_department'])) {
    $selectedDepartment = mysqli_real_escape_string($conn, $_POST['department_id']);

    // Calculăm salariul median pentru departamentul selectat
    $medianQuery = "SELECT AVG(salariu) as MedianSalary
                    FROM (SELECT a.salariu
                          FROM angajati a
                          WHERE a.departament_ID = '$selectedDepartment'
                          ORDER BY a.salariu) AS Salaries
                    WHERE (SELECT COUNT(*) FROM angajati WHERE departament_ID = '$selectedDepartment') % 2 = 0
                    OR Salaries.salariu > (SELECT AVG(salariu) FROM angajati WHERE departament_ID = '$selectedDepartment')";

    $medianResult = mysqli_query($conn, $medianQuery);
    if ($medianResult && mysqli_num_rows($medianResult) > 0) {
        $medianRow = mysqli_fetch_assoc($medianResult);
        $medianSalary = $medianRow['MedianSalary'];

        // Obținem angajații cu salariul peste mediana departamentului
        $query = "SELECT nume, prenume, salariu
                  FROM angajati
                  WHERE departament_ID = '$selectedDepartment' AND salariu > '$medianSalary'";

        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $employeesAboveMedian[] = $row;
        }
    }
}

// // //===========interogare 8=========================================

$cladiriSimilareInfo = [];

if (isset($_POST['show_cladiri_similare'])) {
    $selectedCladireID = mysqli_real_escape_string($conn, $_POST['cladire_id']);

    $query = "SELECT c1.nume AS NumeCladire, c1.data_constructie AS AnConstructie, c1.suprafata_totala AS SuprafataTotala
              FROM cladiri c1
              JOIN (SELECT data_constructie, AVG(suprafata_totala) AS MediaSuprafata
                    FROM cladiri
                    GROUP BY data_constructie) c2 ON c1.data_constructie = c2.data_constructie
              WHERE c1.data_constructie = (SELECT data_constructie FROM cladiri WHERE cladire_ID = ?)
              AND c1.suprafata_totala > c2.MediaSuprafata";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $selectedCladireID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $cladiriSimilareInfo[] = $row;
        }

        mysqli_stmt_close($stmt);
    }
}

// // //===========interogare 9=========================================

$cladiriFiltrate = [];
$query_stari = "SELECT DISTINCT stare FROM dotari_sali";
$result_stari = mysqli_query($conn, $query_stari);
$stari = [];

// Extrage stările unice ale dotărilor și le adaugă în array-ul $stari
while ($row = mysqli_fetch_assoc($result_stari)) {
    $stari[] = $row['stare'];
}

if (isset($_POST['show_cladiri'])) {
    $dataIntrodusa = mysqli_real_escape_string($conn, $_POST['data_achizitionare']);
    $stareSelectata = mysqli_real_escape_string($conn, $_POST['stare_dotare']);

    $query = "SELECT c.nume, c.adresa
              FROM cladiri c
              WHERE EXISTS (
                  SELECT 1
                  FROM sali s
                  JOIN dotari_sali ds ON s.sala_ID = ds.sala_ID
                  JOIN dotari d ON ds.dotare_ID = d.dotare_ID
                  WHERE s.cladire_ID = c.cladire_ID
                  AND d.data_achizitionare < '$dataIntrodusa'
                  AND ds.stare = '$stareSelectata'
              )
              GROUP BY c.cladire_ID";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $cladiriFiltrate[] = $row;
    }
}

// // //===========interogare 10=========================================

$dotariCostisitoare = [];

if (isset($_POST['show_dotari'])) {
    $salaID = mysqli_real_escape_string($conn, $_POST['sala_id']);

    $query = "SELECT d.nume, d.pret
              FROM dotari d
              JOIN dotari_sali ds ON d.dotare_ID = ds.dotare_ID
              WHERE ds.sala_ID = '$salaID'
              AND d.pret > (SELECT AVG(dotari.pret)
                            FROM dotari
                            JOIN dotari_sali ON dotari.dotare_ID = dotari_sali.dotare_ID
                            WHERE dotari_sali.sala_ID = '$salaID')";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $dotariCostisitoare[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistic</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/sali_page.css">

</head>

<body>

    <?php include "navbar.php" ?>

    <div class="movie-container">
        <h3 style="text-align: center;"> Pagina de statistici </h3>
        <hr size="4" width="100%" color="white"><br>

        <!-- ==================afisare interogare 1============================== -->
        <form action="" method="post" enctype="multipart/form-data" style="text-align:center">

            <p style="color: white">Alege departamentul pentru a vedea salariul mediu al personalului:
                <select name="form_departamente">
                    <?php
                    while ($row = mysqli_fetch_assoc($result_departamente)) {
                    ?>
                        <option value="<?php echo $row['departament_ID']; ?>">
                            <?php echo $row['nume']; ?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
                <input type="submit" value="Arată" name="show_int1" class="button-30">
            </p>

            <?php if ($row_int1) : ?>
                <p style="color: white">Salariul mediu al departamentului <b><?php echo htmlspecialchars($departamentNume); ?></b> este de: <b><?php echo htmlspecialchars($row_int1['AverageSalary']); ?> lei</b></p>
            <?php else : ?>
                <p style="color: white">Din păcate, departamentul <b><?php echo htmlspecialchars($departamentNume); ?></b> nu are angajați în acest moment.</p>
            <?php endif; ?>

            <br>
            <hr size="4" width="100%" color="white"><br>
        </form>

        <!-- ==================afisare interogare 2============================== -->

        <form action="" method="post" enctype="multipart/form-data" style="text-align:center">

            <p style="color: white">Alege sala pentru care vrei să aflii totalul dotărilor:
                <select name="form_sali">
                    <?php while ($row = mysqli_fetch_assoc($result_sali)) : ?>
                        <option value="<?php echo $row['sala_ID']; ?>">
                            <?php echo $row['nume']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="submit" value="Arată" name="show_int2" class="button-30">
            </p>

            <?php if (!empty($totalCosts)) : ?>
                <?php foreach ($totalCosts as $roomName => $cost) : ?>
                    <p style="color: white">Suma totală a dotărilor din <b><?php echo htmlspecialchars($roomName); ?></b> este de: <b><?php echo htmlspecialchars($cost); ?> lei</b></p>
                <?php endforeach; ?>

            <?php else : ?>
                <p style="color: white"> Momentan nu dispune de nicio dotare.</b></p>
            <?php endif; ?>
            <br>
            <hr size="4" width="100%" color="white"><br>

        </form>

        <!-- ==================afisare interogare 3============================== -->

        <form action="" method="post" style="text-align:center">
            <label for="form_cladiri" style="color: white">Numele si suprafata salilor ce fac parte din caldirea</label>
            <select name="form_cladiri" id="form_cladiri">
                <?php
                // Asigurați-vă că acest cod PHP este integrat corect cu conexiunea la baza de date pentru a obține clădirile
                while ($row = mysqli_fetch_assoc($result_cladiri)) {
                    echo "<option value='" . $row['cladire_ID'] . "'>" . $row['nume'] . "</option>";
                }
                ?>
            </select>

            <label for="form_suprafata" style="color: white">ce au o suprafață totală minimă:</label>
            <input type="number" id="form_suprafata" name="form_suprafata" min="1">

            <input type="submit" class="button-30" name="show_sali" value="Arată">
        </form>

        <div style="text-align: center;">
            <?php
            if (!empty($saliInfo)) {
                echo "<h2>Săli din Clădirea Selectată:</h2>";
                echo "<ul>";
                foreach ($saliInfo as $sala) {
                    echo "<li>" . htmlspecialchars($sala['nume']) . " - Suprafață: " . htmlspecialchars($sala['suprafata']) . " mp</li>";
                }
                echo "</ul>";
            }
            ?>
            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 4============================== -->

            <form action="" method="post" style="text-align:center">
                <label for="employee_id" style="color: white">Departamentul din care face parte si salariul angajatului:</label>
                <select name="employee_id" id="employee_id">
                    <?php while ($row = mysqli_fetch_assoc($result_employees)) : ?>
                        <option value="<?php echo $row['angajat_ID']; ?>">
                            <?php echo htmlspecialchars($row['nume'] . " " . $row['prenume']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="submit" class="button-30" name="show_employee_info" value="Arată">
            </form>

            <div style="color: white;">
                <?php if (!empty($employeeInfo) && is_array($employeeInfo)) : ?>
                    <p>Departament: <b><?php echo htmlspecialchars($employeeInfo['Departament']); ?></b></p>
                    <p>Salariu: <b><?php echo htmlspecialchars($employeeInfo['Salariu']); ?> lei</b></p>
                <?php else : ?>
                    <p><?php echo $employeeInfo; ?></p>
                <?php endif; ?>
            </div>
            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 5============================== -->

            <form action="" method="post">
                <label for="form_building" style="color: white">Data constructiei si capacitatea totala a salilor in materie de locuri pentru:</label>
                <select name="form_building" id="form_building">
                    <?php while ($row = mysqli_fetch_assoc($result_building)) : ?>
                        <option value="<?php echo $row['cladire_ID']; ?>">
                            <?php echo htmlspecialchars($row['nume']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="submit" class="button-30" name="show_int5" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <?php if (!empty($buildingInfo) && !isset($buildingInfo['error'])) : ?>
                    <p>Numele clădirii: <b><?php echo htmlspecialchars($buildingInfo['BuildingName']); ?></b></p>
                    <p>Data construcției: <b><?php echo htmlspecialchars($buildingInfo['ConstructionDate']); ?></b></p>
                    <p>Capacitatea totală a sălilor: <b><?php echo htmlspecialchars($buildingInfo['TotalCapacity']); ?></b> locuri</p>
                <?php elseif (isset($buildingInfo['error'])) : ?>
                    <p><?php echo htmlspecialchars($buildingInfo['error']); ?></p>
                <?php endif; ?>
            </div>

            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 6============================== -->

            <form action="" method="post">
                <label for="sala_id" style="color: white">Verificati dotarile din sala</label>
                <select name="sala_id" id="sala_id">
                    <?php
                    $result_sali = mysqli_query($conn, "SELECT sala_ID, nume FROM sali");
                    while ($row = mysqli_fetch_assoc($result_sali)) : ?>
                        <option value="<?php echo $row['sala_ID']; ?>">
                            <?php echo htmlspecialchars($row['nume']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="pret_minim" style="color: white">ce au pret minim</label>
                <input type="number" id="pret_minim" name="pret_minim" min="0" required>

                <label for="stare_dotare" style="color: white">si starea</label>
                <input type="text" id="stare_dotare" name="stare_dotare" required>

                <input type="submit" class="button-30" name="show_dotari_sala" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <?php if (!empty($salaDotariInfo)) : ?>
                    <h2>Dotările din sala selectată:</h2>
                    <ul>
                        <?php foreach ($salaDotariInfo as $dotare) : ?>
                            <li><?php echo htmlspecialchars($dotare['DotareNume']) . " - Preț: " . htmlspecialchars($dotare['Pret']) . " lei, Stare: " . htmlspecialchars($dotare['Stare']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Nu există dotări care să îndeplinească criteriile specificate.</p>
                <?php endif; ?>
            </div>

            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 7============================== -->

            <!-- Formular pentru selectarea departamentului -->
            <form action="" method="post">
                <label for="department_id" style="color: white">Afiseaza numele si salariul tuturor angajatilor care au salariul mai mare decat salariul median din departamentul:</label>
                <select name="department_id" id="department_id">
                    <?php
                    $query = "SELECT departament_ID, nume FROM departamente ORDER BY nume";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['departament_ID'] . "'>" . htmlspecialchars($row['nume']) . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="show_department" class="button-30" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <?php if (!empty($selectedDepartment)) : ?>
                    <h2>Angajați cu salariu peste mediana departamentului selectat:</h2>
                    <p>Salariul median în departament: <b><?php echo htmlspecialchars(number_format($medianSalary, 2)); ?> lei</b></p>
                    <?php if (!empty($employeesAboveMedian)) : ?>
                        <ul>
                            <?php foreach ($employeesAboveMedian as $employee) : ?>
                                <li><?php echo htmlspecialchars($employee['nume'] . " " . $employee['prenume']) . " - Salariu: " . htmlspecialchars($employee['salariu']); ?> lei</li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Nu există angajați cu salariu peste mediana departamentului.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 8============================== -->

            <form action="" method="post">
                <label for="cladire_id" style="color: white">Obtine numele, anul de constructie si suprafata totala a cladirilor care au fost constuite in acelasi an
                    cu cladirea</label>
                <select name="cladire_id" id="cladire_id">
                    <?php
                    $query_cladiri = "SELECT cladire_ID, nume FROM cladiri ORDER BY nume";
                    $result_cladiri = mysqli_query($conn, $query_cladiri);
                    while ($row = mysqli_fetch_assoc($result_cladiri)) {
                        echo "<option value='" . $row['cladire_ID'] . "'>" . htmlspecialchars($row['nume']) . "</option>";
                    }
                    ?>
                </select> <span style="color: white;">si au o suprafata totala mai mare decat media suprafetelor totale ale cladirilor construite in acelasi an.</span>
                <input type="submit" name="show_cladiri_similare" class="button-30" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <h2>Clădiri similare selectate:</h2>
                <?php if (!empty($cladiriSimilareInfo)) : ?>
                    <ul>
                        <?php foreach ($cladiriSimilareInfo as $cladire) : ?>
                            <li>
                                <?php
                                echo htmlspecialchars($cladire['NumeCladire']) .
                                    " - An Construcție: " . htmlspecialchars($cladire['AnConstructie']) .
                                    ", Suprafață Totală: " . htmlspecialchars($cladire['SuprafataTotala']) . " mp";
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Nu există clădiri care să îndeplinească aceste criterii.</p>
                <?php endif; ?>
            </div>

            <br>
            <hr size="4" width="100%" color="white">

            <!-- ==================afisare interogare 9============================== -->

            <!-- Formular pentru filtrarea clădirilor -->
            <form action="" method="post">
                <label for="data_achizitionare" style="color: white">Obțineți numele și adresa clădirilor care au cel puțin o sală echipată cu dotări achiziționate înainte de data de</label>
                <input type="date" id="data_achizitionare" name="data_achizitionare" required>

                <label for="stare_dotare" style="color: white">Starea Dotării:</label>
                <select name="stare_dotare" id="stare_dotare">
                    <?php foreach ($stari as $stare) : ?>
                        <option value="<?php echo htmlspecialchars($stare); ?>"><?php echo htmlspecialchars($stare); ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" name="show_cladiri" class="button-30" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <h2>Clădiri Filtrate:</h2>
                <?php if (!empty($cladiriFiltrate)) : ?>
                    <ul>
                        <?php foreach ($cladiriFiltrate as $cladire) : ?>
                            <li><?php echo htmlspecialchars($cladire['nume']) . " - Adresa: " . htmlspecialchars($cladire['adresa']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Nu există clădiri care să îndeplinească criteriile specificate.</p>
                <?php endif; ?>
            </div>


            <br>
            <hr size="4" width="100%" color="white">


            <!-- ==================afisare interogare 10============================== -->

            <form action="" method="post">
                <label for="sala_id" style="color: white">Obțineți numele dotarilor care au pretul mai mare decat media preturilor din sala:</label>
                <select name="sala_id" id="sala_id">
                    <?php
                    $query_sali = "SELECT sala_ID, nume FROM sali ORDER BY nume";
                    $result_sali = mysqli_query($conn, $query_sali);
                    while ($row = mysqli_fetch_assoc($result_sali)) {
                        echo "<option value='" . $row['sala_ID'] . "'>" . htmlspecialchars($row['nume']) . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="show_dotari" class="button-30" value="Arată">
            </form>

            <div style="text-align: center; color: white;">
                <h2>Dotări Costisitoare în Sala Selectată:</h2>
                <?php if (!empty($dotariCostisitoare)) : ?>
                    <ul>
                        <?php foreach ($dotariCostisitoare as $dotare) : ?>
                            <li><?php echo htmlspecialchars($dotare['nume']) . " - Preț: " . htmlspecialchars($dotare['pret']) . " lei"; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Nu există dotări cu prețul peste media sălii selectate.</p>
                <?php endif; ?>
            </div>

            <br>
            <hr size="4" width="100%" color="white">
</body>

</html>