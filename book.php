<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            font-family: 'Poppins', sans-serif;
            color: white;
        }
    </style>
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['event_id'])) {
    
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($quantity < 1 || $quantity > 4) {
        die("Invalid quantity.");
    }

    $sql = "INSERT INTO bookings (user_id, event_id, quantity) VALUES ('$user_id', '$event_id', '$quantity')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            // 1. Confetti Animation (Ye Code se chalti hai, kabhi kharab nahi hogi)
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };

            function randomInRange(min, max) {
              return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
              var timeLeft = animationEnd - Date.now();

              if (timeLeft <= 0) {
                return clearInterval(interval);
              }

              var particleCount = 50 * (timeLeft / duration);
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
              confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);

            // 2. Clean Popup (No Broken GIF)
            Swal.fire({
                title: 'Booking Successful! 🎟️',
                html: 'You have secured <b>$quantity ticket(s)</b>.<br>Get ready for the event!',
                icon: 'success',
                background: 'rgba(20, 20, 35, 0.95)',
                color: '#fff',
                confirmButtonColor: '#00d4ff',
                confirmButtonText: 'View My Tickets',
                backdrop: `rgba(0,0,0,0.6)`, // Simple Dark Overlay
                showClass: { popup: 'animate__animated animate__zoomInDown' },
                hideClass: { popup: 'animate__animated animate__zoomOutUp' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'my_bookings.php';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error', title: 'Booking Failed', text: 'Something went wrong.',
                background: '#1a1a2e', color: '#fff'
            }).then(() => { window.location.href = 'index.php'; });
        </script>";
    }
} else {
    header("Location: index.php");
}
?>
</body>
</html>