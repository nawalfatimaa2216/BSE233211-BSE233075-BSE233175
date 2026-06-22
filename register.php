<?php
include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
        exit();
    } else {
        $message = "Error: Email likely already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - EventSys</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 30px;
            padding: 10px 20px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-color: #00d4ff;
            box-shadow: none;
        }
        .btn-neon {
            background: white;
            color: #0f0c29;
            border: none;
            border-radius: 30px;
            padding: 10px;
            font-weight: bold;
            width: 100%;
            transition: 0.3s;
        }
        .btn-neon:hover {
            background: #00d4ff;
            color: white;
            box-shadow: 0 0 15px rgba(0, 212, 255, 0.5);
        }
        .link-light { color: #00d4ff; text-decoration: none; }
    </style>
</head>
<body>

    <div class="glass-card">
        <h3 class="text-center text-white mb-4">Create Account</h3>
        
        <?php if($message): ?>
            <div class="alert alert-danger bg-transparent text-danger border-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-neon">Sign Up</button>
        </form>
        <p class="text-center mt-3 text-white-50">Already have an account? <a href="login.php" class="link-light">Login</a></p>
        <p class="text-center"><a href="index.php" class="text-white-50 small">Back to Home</a></p>
    </div>

</body>
</html>