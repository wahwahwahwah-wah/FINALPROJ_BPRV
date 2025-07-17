<?php
require_once 'includes/header.php';
require_once 'config/db.php';

if (!isset($_GET['id'])) {
    echo "No pet specified.";
    exit;
}
$pet_id = $_GET['id'];

$sql = "
    SELECT p.*, s.SpeciesName, 
           COALESCE(db.BreedName, cb.BreedName) AS BreedName
    FROM pets p
    JOIN species s ON p.SpeciesID = s.SpeciesID
    LEFT JOIN dog_breeds db ON p.BreedID = db.BreedID AND p.SpeciesID = 0
    LEFT JOIN cat_breeds cb ON p.BreedID = cb.BreedID AND p.SpeciesID = 1
    WHERE p.PetID = ?
";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $pet_id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    echo "Pet not found.";
    exit;
}
$is_favorited = isset($_SESSION['favorites']) && in_array($pet['PetID'], $_SESSION['favorites']);
?>

<div class="row">
    <div class="col-md-6 mb-4">
        <img src="<?php echo htmlspecialchars($pet['ImageURL']); ?>" class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($pet['PetName']); ?>">
    </div>
    <div class="col-md-6">
        <h1 class="display-5 fw-bold" style="color: #5a189a;"><?php echo htmlspecialchars($pet['PetName']); ?></h1>
        <p class="lead text-muted"><?php echo htmlspecialchars($pet['BreedName']); ?></p>

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Profile Details</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Age:</strong> 
                        <span><?php echo htmlspecialchars($pet['Age']); ?> years old</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Gender:</strong> 
                        <span><?php echo htmlspecialchars($pet['gender']); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Status:</strong> 
                        <span><?php echo ($pet['is_spayed_neutered'] == 1) ? 'Spayed/Neutered' : 'Not Spayed/Neutered'; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Personality:</strong> 
                        <span><?php echo htmlspecialchars($pet['personality_tags']); ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-4 text-center">
             <a href="manage_favorites.php?id=<?php echo $pet['PetID']; ?>" class="btn <?php echo $is_favorited ? 'btn-danger' : 'btn-outline-danger'; ?> w-100 mb-2 fs-5">
                <?php echo $is_favorited ? '♥ In My Carrier' : '♡ Add to My Carrier'; ?>
            </a>
            <p class="text-muted small">Contact us at (08) 359 4364 or animalcareandadoption@email.com to schedule a meeting!</p>
        </div>
    </div>
</div>

<?php
$stmt->close();
$db->close();
require_once 'includes/footer.php';
?>