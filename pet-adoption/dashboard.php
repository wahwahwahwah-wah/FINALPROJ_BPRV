<?php

session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth/login.php");
    exit;
}

require_once 'config/db.php';
require_once 'includes/header.php';

$sql = "SELECT p.PetID, p.PetName, p.Age, s.SpeciesName, p.PetAvail 
        FROM pets p 
        JOIN species s ON p.SpeciesID = s.SpeciesID
        ORDER BY p.PetID DESC";
$result = $db->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Pet Management Dashboard</h1>
    <a href="add_pet.php" class="btn btn-success"><i class="bi bi-plus-circle-fill me-2"></i>Add New Pet</a>
</div>

<p>Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Here you can view, edit, and delete pet listings.</p>

<table class="table table-striped table-bordered mt-4">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Species</th>
            <th>Age</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($pet = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $pet['PetID']; ?></td>
                    <td><?php echo htmlspecialchars($pet['PetName']); ?></td>
                    <td><?php echo htmlspecialchars($pet['SpeciesName']); ?></td>
                    <td><?php echo htmlspecialchars($pet['Age']); ?></td>
                    <td>
                        <?php if($pet['PetAvail'] == '1'): ?>
                            <span class="badge bg-success">Available</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Adopted</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="view_pet.php?id=<?php echo $pet['PetID']; ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_pet.php?id=<?php echo $pet['PetID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_pet.php?id=<?php echo $pet['PetID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this pet?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr class="text-center">
                <td colspan="6">No pets found. Add one!</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
$db->close();
require_once 'includes/footer.php';
?>