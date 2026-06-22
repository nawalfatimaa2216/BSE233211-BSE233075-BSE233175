<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Access Denied");
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Database query
$sql = "SELECT events.*, bookings.id as ticket_no, bookings.quantity, bookings.booking_date, users.name as user_name 
        FROM bookings 
        JOIN events ON bookings.event_id = events.id 
        JOIN users ON bookings.user_id = users.id
        WHERE bookings.id = '$booking_id' AND bookings.user_id = '$user_id'";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Ticket not found");
}

$row = $result->fetch_assoc();

// --- CALCULATION LOGIC ---
// Yahan hum Total Price nikal rahe hain (Price x Quantity)
$total_price = $row['price'] * $row['quantity'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>E-Ticket #<?php echo $row['ticket_no']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; font-family: sans-serif; }
        .ticket-container {
            max-width: 750px; margin: 60px auto; background: white; border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); display: flex; overflow: hidden;
        }
        .ticket-left { padding: 40px; flex: 2; border-right: 3px dashed #ccc; position: relative; }
        .ticket-right { flex: 1; background: #212529; color: white; padding: 20px; text-align: center; display: flex; flex-direction: column; justify-content: center; }
        .cut-circle { position: absolute; right: -12px; top: 50%; transform: translateY(-50%); width: 24px; height: 24px; background: #f0f2f5; border-radius: 50%; }
        .cut-circle-left { position: absolute; left: -12px; top: 50%; transform: translateY(-50%); width: 24px; height: 24px; background: #f0f2f5; border-radius: 50%; }
    </style>
</head>
<body>
<div class="container">
    <div class="ticket-container">
        
        <div class="ticket-left">
            <span class="badge bg-primary px-3 py-2 mb-3">CONFIRMED TICKET</span>
            <h2 class="fw-bold mb-4"><?php echo $row['title']; ?></h2>
            
            <div class="row g-4">
                <div class="col-6">
                    <p class="text-muted mb-1 small fw-bold">ATTENDEE</p>
                    <p class="fs-5 fw-bold"><?php echo $row['user_name']; ?></p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-1 small fw-bold">DATE</p>
                    <p class="fs-5 fw-bold"><?php echo $row['event_date']; ?></p>
                </div>
                <div class="col-6">
                    <p class="text-muted mb-1 small fw-bold">VENUE</p>
                    <p class="fw-bold"><?php echo $row['venue']; ?></p>
                </div>
                
                <div class="col-6">
                    <p class="text-muted mb-1 small fw-bold">TOTAL PRICE (<?php echo $row['quantity']; ?> Tix)</p>
                    <p class="fw-bold text-success fs-5">Rs. <?php echo number_format($total_price); ?></p>
                </div>
            </div>
            
            <div class="cut-circle"></div>
        </div>
        
        <div class="ticket-right">
            <div class="cut-circle-left"></div>
            
            <h4 class="text-uppercase mb-2">ADMIT <?php echo $row['quantity']; ?></h4>
            <p class="small text-white-50 mb-3">Scan at Entry</p>
            
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=Ticket-<?php echo $row['ticket_no']; ?>-Qty-<?php echo $row['quantity']; ?>" class="bg-white p-2 rounded">
            
            <p class="mt-3 small text-white-50">EventSys Authorized</p>
        </div>
    </div>

    <div class="text-center mt-4 mb-5">
        <button onclick="window.print()" class="btn btn-dark btn-lg shadow">🖨️ Print Ticket</button>
    </div>
</div>
</body>
</html>