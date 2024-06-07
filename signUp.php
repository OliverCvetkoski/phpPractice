<?php
$message = ""; // Initialize an empty message variable

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pwd = $_POST["password"];
    $email = $_POST["email"];

    // Check if any of the input fields are empty
    if (empty($username) || empty($pwd) || empty($email)) {
        $message = "Please fill in all fields.";
    } else {
        try { 
            require_once "dbh.inc.php";
            $query = "INSERT INTO users (username, pwd, email)  VALUES (:username, :pwd, :email);";

            $stmt = $pdo->prepare($query);
            $options = ['cost' => 12];

            $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":pwd", $hashedPwd);
            $stmt->bindParam(":email", $email);

            $stmt->execute();

            header("Location: index.php");
            die();
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign up</title>
    <link rel="stylesheet" href="signUp.css" />
  </head>
  <body>
    <nav><a href="index.php">Home</a><a href="signUp.php">Signup</a></nav>
    <?php if (!empty($message)): ?>
        <p class="emptyInputs"><?php echo $message; ?></p>
    <?php endif; ?>
      <form action="signUp.php" method="post">
        <label for="username">Type your username</label>
        <input
          type="text"
          name="username"
          id="username"
          placeholder="Username"
          required
        />
        <label for="password">Type your password</label>
        <input
          type="password"
          name="password"
          id="password"
          placeholder="Password"
          required
        />
        <label for="email">Type your email</label>
        <input
          type="email"
          name="email"
          id="email"
          placeholder="yep@gmail.com"
          required
        />
        <button>Sign up</button>
      </form>
  </body>
</html>
