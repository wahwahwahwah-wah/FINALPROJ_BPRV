<?php
require_once 'includes/header.php';
require_once 'config/db.php';

$favorite_ids = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : [];
$pets = [];

if (!empty($favorite_ids)) {
    $placeholders = implode(',', array_fill(0, count($favorite_ids), '?'));

    $sql = "SELECT p.*, s.SpeciesName FROM pets p JOIN species s ON p.SpeciesID = s.SpeciesID WHERE p.PetID IN ($placeholders)";
    
    $stmt = $db->prepare($sql);

    $stmt->bind_param(str_repeat('i', count($favorite_ids)), ...$favorite_ids);
    
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $pets[] = $row;
    }
    $stmt->close();
}
?>

<div class="hero-section">
    <h1 class="display-4 fw-bold">My Adoption Carrier</h1>
    <p class="lead">These are the lovely pets you're interested in!</p>
</div>

<div class="row mt-5">
    <?php if (!empty($pets)): ?>
        <?php foreach ($pets as $pet): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card pet-card h-100">
                    <img src="<?php echo htmlspecialchars($pet['ImageURL']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($pet['PetName']); ?>">
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title fs-4 fw-bold" style="color: #5a189a;"><?php echo htmlspecialchars($pet['PetName']); ?></h5>
                        <p class="card-text text-secondary"><?php echo htmlspecialchars($pet['SpeciesName']); ?></p>
                        <div class="mt-auto">
                           <a href="view_pet.php?id=<?php echo $pet['PetID']; ?>" class="btn btn-custom mb-2">View Profile</a>
                           <a href="manage_favorites.php?id=<?php echo $pet['PetID']; ?>" class="btn btn-outline-danger btn-sm">Remove from Carrier</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col">
            <div class="alert alert-info text-center">
                <h4>Your Carrier is Empty</h4>
                <p>Go back to the <a href="index.php" class="alert-link">homepage</a> and add some pets you'd like to meet!</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$db->close();
require_once 'includes/footer.php';
?>