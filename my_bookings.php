<?php
include 'db.php';
session_start();

// 1. Security Check: User Login Hona Chahiye
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Tickets - EventSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }
        .ticket-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px dashed rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            position: relative;
        }
        .ticket-card:hover { transform: translateY(-5px); border-color: #00d4ff; }
        
        .event-img-small {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }
        .qr-placeholder {
            background: white;
            padding: 5px;
            border-radius: 5px;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark bg-transparent border-bottom border-secondary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-arrow-left"></i> Back to Events</a>
            <span class="navbar-text text-white">My Wallet</span>
        </div>
    </nav>

    <div class="container">
        <h2 class="fw-bold mb-4">My Booked Tickets 🎟️</h2>

        <div class="row g-4">
            <?php
            // --- MAIN LOGIC ---
            // Hum Bookings table se data utha rahe hain aur Events table se naam/photo match kar rahe hain
            $sql = "SELECT bookings.id as booking_id, bookings.quantity, bookings.status, bookings.booking_date, 
                           events.title, events.event_date, events.event_time, events.venue, events.image_url 
                    FROM bookings 
                    JOIN events ON bookings.event_id = events.id 
                    WHERE bookings.user_id = '$user_id' 
                    ORDER BY bookings.id DESC";
            
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Time Formatting
                    $bDate = date("d M Y", strtotime($row['booking_date']));
                    $eDate = date("d M, Y", strtotime($row['event_date']));
                    $eTime = date("h:i A", strtotime($row['event_time']));
            ?>
                
                <div class="col-lg-6">
                    <div class="ticket-card p-3 d-flex align-items-center">
                        <img src="<?php echo $row['image_url']; ?>" class="event-img-small me-3">
                        
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1"><?php echo $row['title']; ?></h5>
                            <p class="text-info small mb-1"><i class="bi bi-calendar"></i> <?php echo $eDate; ?> • <?php echo $eTime; ?></p>
                            <p class="text-white-50 small mb-2"><i class="bi bi-geo-alt"></i> <?php echo $row['venue']; ?></p>
                            
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-dark">Qty: <?php echo $row['quantity']; ?></span>
                                <span class="badge bg-success"><?php echo $row['status']; ?></span>
                                <?php if($row['status'] == 'Confirmed'): ?>
                                    <a href="cancel_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn btn-outline-danger btn-sm py-0" style="font-size: 12px;">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="ms-3 text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=TicketID-<?php echo $row['booking_id']; ?>" class="qr-placeholder" alt="QR">
                            <small class="d-block text-white-50 mt-1" style="font-size: 10px;">#<?php echo $row['booking_id']; ?></small>
                        </div>
                    </div>
                </div>

            <?php 
                } 
            } else {
                echo "
                <div class='col-12 text-center py-5'>
                    <i class='bi bi-ticket-detailed display-1 text-secondary'></i>
                    <h3 class='mt-3 text-white-50'>No tickets found.</h3>
                    <a href='index.php' class='btn btn-info rounded-pill mt-2'>Book Your First Ticket</a>
                </div>";
            }
            ?>
        </div>
    </div>

</body>
</html>