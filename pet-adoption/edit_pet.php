<?php
session_start();
require_once 'config/db.php';


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location: dashboard.php");
    exit;
}
$pet_id = $_GET['id'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_name = trim($_POST['pet_name']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $is_spayed_neutered = isset($_POST['is_spayed_neutered']) ? 1 : 0;
    $personality_tags = trim($_POST['personality_tags']);
    $species_id = trim($_POST['species_id']);
    $breed_id = trim($_POST['breed_id']);
    $pet_avail = trim($_POST['pet_avail']);
    $image_url = $_POST['current_image'];

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        if (!empty($image_url) && file_exists($image_url)) {
            unlink($image_url);
        }
        $target_dir = "uploads/";
        $image_name = uniqid() . '-' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file;
        } else {
            $error = "Sorry, there was an error uploading your new file.";
        }
    }

    if (empty($error)) {
        $sql = "UPDATE pets SET PetName = ?, Age = ?, gender = ?, is_spayed_neutered = ?, personality_tags = ?, SpeciesID = ?, BreedID = ?, ImageURL = ?, PetAvail = ? WHERE PetID = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sisisisssi", $pet_name, $age, $gender, $is_spayed_neutered, $personality_tags, $species_id, $breed_id, $image_url, $pet_avail, $pet_id);
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


$stmt = $db->prepare("SELECT * FROM pets WHERE PetID = ?");
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();
$stmt->close();

if (!$pet) {
    echo "Pet not found.";
    exit;
}

$species_result = $db->query("SELECT * FROM species");
$dog_breeds_result = $db->query("SELECT * FROM dog_breeds");
$cat_breeds_result = $db->query("SELECT * FROM cat_breeds");

require_once 'includes/header.php';
?>

<h2>Edit Pet Information</h2>
<?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

<form action="edit_pet.php?id=<?php echo $pet_id; ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label>Pet Name</label>
                <input type="text" name="pet_name" class="form-control" value="<?php echo htmlspecialchars($pet['PetName']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($pet['Age']); ?>" required>
            </div>
            <div class="mb-3">
                <label>Gender</label>
                <div>
                    <input type="radio" name="gender" value="Male" id="male" required <?php if($pet['gender'] == 'Male') echo 'checked'; ?>> <label for="male">Male</label>
                    <input type="radio" name="gender" value="Female" id="female" class="ms-3" <?php if($pet['gender'] == 'Female') echo 'checked'; ?>> <label for="female">Female</label>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_spayed_neutered" value="1" class="form-check-input" id="spayed" <?php if($pet['is_spayed_neutered'] == 1) echo 'checked'; ?>>
                <label class="form-check-label" for="spayed">Is Spayed/Neutered?</label>
            </div>
             <div class="mb-3">
                <label>Current Image</label><br>
                <img src="<?php echo htmlspecialchars($pet['ImageURL']); ?>" width="150" class="rounded">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($pet['ImageURL']); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label>Species</label>
                <select name="species_id" class="form-select" required>
                    <?php mysqli_data_seek($species_result, 0); while($s = $species_result->fetch_assoc()): ?>
                        <option value="<?php echo $s['SpeciesID']; ?>" <?php if($s['SpeciesID'] == $pet['SpeciesID']) echo 'selected'; ?>><?php echo $s['SpeciesName']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Breed</label>
                 <select name="breed_id" class="form-select" required>
                    <optgroup label="Dogs">
                        <?php mysqli_data_seek($dog_breeds_result, 0); while($b = $dog_breeds_result->fetch_assoc()): ?>
                            <option value="<?php echo $b['BreedID']; ?>" <?php if($b['BreedID'] == $pet['BreedID']) echo 'selected'; ?>><?php echo $b['BreedName']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                    <optgroup label="Cats">
                         <?php mysqli_data_seek($cat_breeds_result, 0); while($b = $cat_breeds_result->fetch_assoc()): ?>
                            <option value="<?php echo $b['BreedID']; ?>" <?php if($b['BreedID'] == $pet['BreedID']) echo 'selected'; ?>><?php echo $b['BreedName']; ?></option>
                        <?php endwhile; ?>
                    </optgroup>
                </select>
            </div>
            <div class="mb-3">
                <label>Personality Tags (comma-separated)</label>
                <input type="text" name="personality_tags" class="form-control" value="<?php echo htmlspecialchars($pet['personality_tags']); ?>">
            </div>
             <div class="mb-3">
                <label>Availability</label>
                <select name="pet_avail" class="form-select">
                    <option value="1" <?php if($pet['PetAvail'] == '1') echo 'selected'; ?>>Available</option>
                    <option value="0" <?php if($pet['PetAvail'] == '0') echo 'selected'; ?>>Adopted</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Upload New Image (Optional)</label>
                <input type="file" name="image" class="form-control">
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100 mt-3">Update Pet</button>
</form>

<?php
$db->close();
require_once 'includes/footer.php';
?>