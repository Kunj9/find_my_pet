    <?php
    require_once(__DIR__ . "/../lib/db_config.php");
    require(__DIR__ . "/../lib/safe_echo.php");
    require(__DIR__ . "/../partials/nav.php");

    if (!isset($_SESSION['username'])) {
        flashMessage("You need to be logged in to create a post.", "info");
        header("Location: login.php");
        exit;
    }

    $hasError = false;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name = se($_POST, "name", " ", false);
        $postedBy = se($_POST, "postedBy", " ", false);
        $breed = se($_POST, "breed", " ", false);
        $species = se($_POST, "species", " ", false);
        $gender = se($_POST, "gender", " ", false);
        $status = se($_POST, "status", " ", false);
        $imageID = '';

        // Handle file upload
        if (isset($_FILES['petImage']) && $_FILES['petImage']['error'] == UPLOAD_ERR_OK) {
            $imgFile = $_FILES['petImage'];
            $fileName = $imgFile['name'];
            $imgExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $imageID = uniqid('', true) . "." . $imgExtension;
            $imageFinalLocation = '../media/uploads/' . $imageID;
            move_uploaded_file($imgFile['tmp_name'], $imageFinalLocation);
        }

        $additionalDetails = se($_POST, "additionalDetails", " ", false);

        // Set found/lost datetime based on status
        $foundDatetime = $lostDatetime = null;
        if ($status === "found") {
            $foundDatetime = se($_POST, "dateTime", " ", false);
        } elseif ($status === "lost") {
            $lostDatetime = se($_POST, "dateTime", " ", false);
        }

        // Insert into the database if no errors
        if (!$hasError) {
            $stmt = $pdo->prepare("
                INSERT INTO pets (name, posted_by, breed, species, gender, status, 
                                found_datetime, lost_datetime, additional_details, image_id)
                VALUES (:name, :postedBy, :breed, :species, :gender, :status, 
                        :foundDatetime, :lostDatetime, :additionalDetails, :imageID)
            ");

            try {
                $stmt->execute([
                    ":name" => $name,
                    ":postedBy" => $postedBy,
                    ":breed" => $breed,
                    ":species" => $species,
                    ":gender" => $gender,
                    ":status" => $status,
                    ":foundDatetime" => $foundDatetime,
                    ":lostDatetime" => $lostDatetime,
                    ":additionalDetails" => $additionalDetails,
                    ":imageID" => $imageID
                ]);
                echo "Your post has been uploaded!";
                $_SESSION['access_method'] = "redirected";
                header("Location: postcreated.php");
                exit;
            } catch (Exception $e) {
                echo "There was a problem registering the pet.";
                echo "<pre>" . var_export($e, true) . "</pre>";
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../project-css/createpost.css">
        
        <script>
            function showFields() {
                const foundFields = document.getElementById('foundFields');
                const lostFields = document.getElementById('lostFields');
                const isFound = document.getElementById('foundRadio').checked;

                foundFields.style.display = isFound ? 'block' : 'none';
                lostFields.style.display = !isFound ? 'block' : 'none';
            }

            function validateForm() {
                return document.getElementById('reportPetForm').reportValidity();
            }
        </script>
    </head>
    <body>
        <div class="container">
           
            <form id="reportPetForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
                <div class="radio-group">
                <h2>Report a Missing or Found Pet</h2>
                    <label>Status of Pet:</label>
                    <input type="radio" name="status" value="found" id="foundRadio" required onclick="showFields()"> Found
                    <input type="radio" name="status" value="lost" id="lostRadio" required onclick="showFields()"> Lost
                </div>

                <div class="form-group">
                    <label>Posted By</label>
                    <input type="text" name="postedBy" value="<?php echo $_SESSION['username']; ?>" readonly required maxlength="30" />
                </div>

                <div class="form-group">
                    <label>Pet Name</label>
                    <input type="text" name="name" required />
                </div>

                <div class="form-group">
                    <label>Breed</label>
                    <input type="text" name="breed" required />
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Unknown">Unknown</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Species</label>
                    <select name="species" required>
                        <option value="Cat">Cat</option>
                        <option value="Dog">Dog</option>
                        <option value="Bird">Bird</option>
                        <option value="Reptiles">Reptiles</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div id="foundFields" class="form-group hidden">
                    <label>Found Date and Time</label>
                    <input type="datetime-local" name="dateTime" />
                </div>

                <div id="lostFields" class="form-group hidden">
                    <label>Lost Date and Time</label>
                    <input type="datetime-local" name="dateTime" />
                </div>

                <div class="form-group">
                    <label>Upload Image</label>
                    <input type="file" name="petImage" accept="image/*" required />
                </div>

                <div class="form-group">
                    <label>Additional Details</label>
                    <textarea name="additionalDetails" placeholder="Enter any additional information..."></textarea>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </body>
    </html>
