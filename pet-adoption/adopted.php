<?php
require_once 'includes/header.php';
require_once 'config/db.php';

$sql = "SELECT p.PetID, p.PetName, p.ImageURL, s.SpeciesName, p.days_in_shelter, p.description
        FROM pets p 
        JOIN species s ON p.SpeciesID = s.SpeciesID 
        WHERE p.PetAvail = '0'
        ORDER BY p.PetName ASC"; 
$result = $db->query($sql);
?>

<!-- This now uses the new CSS class instead of an inline style -->
<div class="hero-section-success">
    <h1 class="display-4 fw-bold">Our Success Stories</h1>
    <p class="lead">Every pet here has found their forever home. Thank you for making it possible!</p>
</div>

<div class="row mt-5">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($pet = $result->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card pet-card h-100">
                    <img src="<?php echo htmlspecialchars($pet['ImageURL']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($pet['PetName']); ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fs-4 fw-bold text-center" style="color: #5a189a;"><?php echo htmlspecialchars($pet['PetName']); ?></h5>
                        
                        <p class="text-center">
                           <span class="badge rounded-pill text-bg-secondary">
                                Waited <?php echo htmlspecialchars($pet['days_in_shelter']); ?> days for a home
                           </span>
                        </p>
                        
                        <p class="card-text text-muted small mt-2">
                           <?php echo htmlspecialchars($pet['description']); ?>
                        </p>
                        
                        <div class="mt-auto text-center">
                            <p class="card-text text-success fw-bold mb-0">
                                ♥ Happily Adopted!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col">
            <div class="alert alert-info text-center">
                <h4>No Adoption Stories Yet</h4>
                <p>Check back later to see which of our pets have found their loving families!</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$db->close();
require_once 'includes/footer.php';
?>