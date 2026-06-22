<?php
include 'db.php';
session_start();

// 1. Check Login (Admin/User)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- INSERT EVENT LOGIC (Self Processing) ---
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $venue = $conn->real_escape_string($_POST['venue']);
    $price = $_POST['price'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $desc = $conn->real_escape_string($_POST['description']);
    
    // Default Image (Safe Link) - Taake image break na ho
    $image = "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80";

    $sql = "INSERT INTO events (title, venue, price, event_date, event_time, description, image_url) 
            VALUES ('$title', '$venue', '$price', '$date', '$time', '$desc', '$image')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "<div class='alert alert-success'>Event Published Successfully! <a href='index.php'>View Live</a></div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Organizer Dashboard - EventSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .container { max-width: 900px; padding-top: 50px; padding-bottom: 50px; }
        
        /* Stats Cards Styling */
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: #00d4ff; }
        .stat-icon { font-size: 2rem; margin-bottom: 10px; display: block; }
        
        /* Form Styling */
        .form-container {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-color: #00d4ff;
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }
        
        /* Dropdown Options Black Background Fix */
        option { background-color: #000; color: #fff; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent border-bottom border-secondary mb-4">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left"></i> Back to Home</a>
            <span class="navbar-text text-white">Organizer Panel</span>
        </div>
    </nav>

    <div class="container">
        
        <h2 class="fw-bold mb-4">Dashboard Overview 📊</h2>
        
        <?php
        // FIX: Join laga kar Price * Quantity calculate kiya hai (Kyunke total_price column nahi tha)
        $earnings_query = $conn->query("
            SELECT SUM(events.price * bookings.quantity) as earnings 
            FROM bookings 
            JOIN events ON bookings.event_id = events.id 
            WHERE bookings.status = 'Confirmed'
        ");
        $earnings = $earnings_query->fetch_assoc()['earnings'] ?? 0;

        $bookings_query = $conn->query("SELECT SUM(quantity) as total_tickets FROM bookings");
        $tickets_sold = $bookings_query->fetch_assoc()['total_tickets'] ?? 0;

        $events_query = $conn->query("SELECT COUNT(*) as total_events FROM events");
        $active_events = $events_query->fetch_assoc()['total_events'] ?? 0;
        ?>

        <div class="row mb-5 g-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-cash-stack stat-icon text-success"></i>
                    <h3 class="fw-bold">Rs. <?php echo number_format($earnings); ?></h3>
                    <p class="text-white-50 m-0">Total Revenue</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-ticket-perforated-fill stat-icon text-info"></i>
                    <h3 class="fw-bold"><?php echo $tickets_sold; ?></h3>
                    <p class="text-white-50 m-0">Tickets Sold</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <i class="bi bi-calendar-event-fill stat-icon text-warning"></i>
                    <h3 class="fw-bold"><?php echo $active_events; ?></h3>
                    <p class="text-white-50 m-0">Active Events</p>
                </div>
            </div>
        </div>

        <h3 class="fw-bold mb-3 border-top border-secondary pt-4">🚀 Publish New Event</h3>
        
        <?php echo $msg; ?>

        <div class="form-container">
            <form action="" method="POST"> 
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Event Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. Grand Music Fest">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Venue</label>
                        <select name="venue" class="form-select">
                            <option>Expo Center</option>
                            <option>PC Hotel</option>
                            <option>F-9 Park</option>
                            <option>Arts Council</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" name="time" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ticket Price (Rs.)</label>
                        <input type="number" name="price" class="form-control" required placeholder="1500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Tell people about the event..."></textarea>
                </div>

                <button type="submit" class="btn btn-info w-100 fw-bold py-2">Publish Event Now</button>
            </form>
        </div>

    </div>

</body>
</html>