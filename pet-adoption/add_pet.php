<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth/login.php");
    exit;
}

$pet_name = $age = $species_id = $breed_id = $gender = $personality_tags = "";
$is_spayed_neutered = 0;
$error = "";

$species_result = $db->query("SELECT * FROM species");
$dog_breeds_result = $db->query("SELECT * FROM dog_breeds");
$cat_breeds_result = $db->query("SELECT * FROM cat_breeds");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_name = trim($_POST['pet_name']);
    $age = trim($_POST['age']);
    $species_id = trim($_POST['species_id']);
    $breed_id = trim($_POST['breed_id']);
    $gender = trim($_POST['gender']);
    $personality_tags = trim($_POST['personality_tags']);
    $is_spayed_neutered = isset($_POST['is_spayed_neutered']) ? 1 : 0;
    
    $image_url = "";
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $target_dir = "uploads/";
        $image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error = "An image file is required.";
    }

    if (empty($error)) {
        $sql = "INSERT INTO pets (PetName, Age, gender, is_spayed_neutered, personality_tags, SpeciesID, BreedID, ImageURL, PetAvail) VALUES (?, ?, ?, ?, ?, ?, ?, ?, '1')";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sisisiss", $pet_name, $age, $gender, $is_spayed_neutered, $personality_tags, $species_id, $breed_id, $image_url);
            if ($stmt->execute()) {
                header("location: dashboard.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Add a New Pet</h2>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<form action="add_pet.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="pet_name" class="form-label">Pet Name</label>
                <input type="text" name="pet_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" name="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <div>
                    <input type="radio" name="gender" value="Male" id="male" required> <label for="male">Male</label>
                    <input type="radio" name="gender" value="Female" id="female" class="ms-3"> <label for="female">Female</label>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_spayed_neutered" value="1" class="form-check-input" id="spayed">
                <label class="form-check-label" for="spayed">Is Spayed/Neutered?</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="species_id" class="form-label">Species</label>
                <select name="species_id" class="form-select" required>
                    <option value="">-- Select Species --</option>
                    <?php mysqli_data_seek($species_result, 0); while($s = $species_result->fetch_assoc()): ?>
                        <option value="<?php echo $s['SpeciesID']; ?>"><?php echo $s['SpeciesName']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="breed_id" class="form-label">Breed</label>
                <select name="breed_id" class="form-select" required>
                    <option value="">-- Select Breed --</option>
                    <optgroup label="Dogs">
                        <?php mysqli_data_seek($dog_breeds_result, 0); while($b = $dog_breeds_result->fetch_assoc()): ?>
                            <option value="<?php echo $b['BreedID']; ?>"><?php echo $b['BreedName']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                    <optgroup label="Cats">
                         <?php mysqli_data_seek($cat_breeds_result, 0); while($b = $cat_breeds_result->fetch_assoc()): ?>
                            <option value="<?php echo $b['BreedID']; ?>"><?php echo $b['BreedName']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                </select>
            </div>
            <div class="mb-3">
                <label for="personality_tags" class="form-label">Personality Tags (comma-separated)</label>
                <input type="text" name="personality_tags" class="form-control" placeholder="e.g. Playful, Calm, Good with kids">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Pet Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-3">Add Pet</button>
</form>

<?php
$db->close();
require_once 'includes/footer.php';
?>