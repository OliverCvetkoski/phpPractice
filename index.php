<?php
include_once('./dbh.inc.php');

// Function to delete a user by ID
function deleteUser($id, $pdo) {
    $sqlDelete = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sqlDelete);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

if (isset($_POST['deleteId'])) {
    $id = $_POST['deleteId'];
    if (deleteUser($id, $pdo)) {
        header("Location: index.php"); 
        exit();
    } else {
        echo "Failed to delete user.";
    }
}

// Fetch users from the database
$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Admin panel</title>

</head>
<body>
    <nav><a href="index.php">Home</a><a href="signUp.php">Signup</a></nav>

<section class="container">
    <h1>Users</h1>
    <div class="table">
    <form method="post" action="index.php">
        <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                    <div class="userContainer">
                        <div class="user">
                            ID: <div class="id"><?php echo htmlspecialchars($user['id']); ?></div>
                            Name: <div class="name"><?php echo htmlspecialchars($user['username']); ?></div>
                            Email: <div class="email"><?php echo htmlspecialchars($user['email']); ?></div>
                        </div>
                        <button type="submit" name="deleteId" value="<?php echo $user['id']; ?>">Delete user</button>
                    </div>
                <br>
            <?php endforeach; ?>
        <?php else: ?>
          <p>No users found</p> 
        <?php endif; ?>
        </form>
    </div>
</section>

</body>
</html>
