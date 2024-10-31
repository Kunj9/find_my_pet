<?php
require_once(__DIR__ . "/../lib/db_config.php");
require(__DIR__ . "/../lib/safe_echo.php");
require(__DIR__ . "/../partials/nav.php");

$lostFoundStatus = isset($_GET['lost_found']) ? $_GET['lost_found'] : '';
$selectedSpecies = isset($_GET['species']) ? $_GET['species'] : '';
$selectedGender = isset($_GET['gender']) ? $_GET['gender'] : '';

$sql = "SELECT name, posted_by, breed, gender, species, additional_details, image_id FROM pets WHERE 1";

if (!empty($lostFoundStatus)) {
    $sql .= " AND status = :lostFoundStatus";
}

if (!empty($selectedSpecies)) {
    $sql .= " AND species = :selectedSpecies";
}

if (!empty($selectedGender)) {
    $sql .= " AND gender = :selectedGender";
}

$stmt = $pdo->prepare($sql);

if (!empty($lostFoundStatus)) {
    $stmt->bindParam(':lostFoundStatus', $lostFoundStatus);
}

if (!empty($selectedSpecies)) {
    $stmt->bindParam(':selectedSpecies', $selectedSpecies);
}

if (!empty($selectedGender)) {
    $stmt->bindParam(':selectedGender', $selectedGender);
}

$stmt->execute();
$petPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" type="text/css" href="../project-css/homepage.css">
</head>
<body style="background-color:#FFFAED;">
    <div class="center-div">
    <center class="search-bar"> 
        <form action="" method="get">
            <select name="lost_found" style="width: 180px; margin-right:3px; height: 40px; font-size: 20px; text-align:center">
                <option value="">Lost/Found</option>
                <option value="lost" <?php if($lostFoundStatus == 'lost') echo 'selected'; ?>>Lost</option>
                <option value="found" <?php if($lostFoundStatus == 'found') echo 'selected'; ?>>Found</option>
            </select>
            <select name="species" style="width: 180px; margin-right:3px; height: 40px; font-size: 20px; text-align:center">
                <option value="">Select Species</option>
                <option value="Apes" <?php if($selectedSpecies == 'Apes') echo 'selected'; ?>>Apes</option>
                <option value="Arachnids" <?php if($selectedSpecies == 'Arachnids') echo 'selected'; ?>>Arachnids</option>
                <option value="Bird" <?php if($selectedSpecies == 'Bird') echo 'selected'; ?>>Bird</option>
                <option value="cat" <?php if($selectedSpecies == 'cat') echo 'selected'; ?>>Cat</option>
                <option value="dog" <?php if($selectedSpecies == 'dog') echo 'selected'; ?>>Dog</option>
                <option value="Mustelidae" <?php if($selectedSpecies == 'Mustelidae') echo 'selected'; ?>>Mustelidae</option>
                <option value="Rabbits" <?php if($selectedSpecies == 'Rabbits') echo 'selected'; ?>>Rabbits</option>
                <option value="Reptiles" <?php if($selectedSpecies == 'Reptiles') echo 'selected'; ?>>Reptiles</option>
                <option value="Rodents" <?php if($selectedSpecies == 'Rodents') echo 'selected'; ?>>Rodents</option>
                <option value="Other" <?php if($selectedSpecies == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            <select name="gender" style="width: 180px; margin-right:3px; height: 40px; font-size: 20px; text-align:center">
                <option value="">Select Gender</option>
                <option value="Male"<?php if($selectedGender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female"<?php if($selectedGender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="Other"<?php if($selectedGender == 'Other') echo 'selected'; ?>>Other</option>
            </select>
            <input type="submit" value="Search" style="width: 180px; height: 42px; font-size: 20px; text-align:center; background-color: #20b2aa; border:none; color: white;border-radius: 25px;">
        </form>
    </center>

    <div class="pet-posts">
    <?php
        foreach ($petPosts as $pet) {
            echo '<div class="pet-post">';
            echo '<p class="align-center"><strong>Name: </strong>' . $pet["name"] . '</p>';
            if ($pet["image_id"] != NULL) {
                echo '<p class="align-center"><img src="../media/uploads/' . $pet["image_id"] . '" height=250 width=310 ></p>';
            }
            echo '<p class="align-center"><strong>Species: </strong>' . $pet["species"] . '</p>';
            echo '<p class="align-center"><strong>Breed: </strong>' . $pet["breed"] . '</p>';
            echo '<p class="align-center"><strong>Gender: </strong>' . $pet["gender"] . '</p>';
            echo '<p class="align-center"><strong>Posted By: </strong>' . $pet["posted_by"] . '</p>'; 
            echo '<p class="align-center"><strong>Additional Details: </strong>' . $pet["additional_details"] . '</p>'; 
            echo '</div>';
        }  
    ?>
    </div>
    </div>
</body>
</html>
