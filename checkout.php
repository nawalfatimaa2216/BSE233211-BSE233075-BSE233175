<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_POST['event_id'])) {
    header("Location: index.php");
    exit();
}

$event_id = $_POST['event_id'];
$qty = $_POST['quantity'];
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name']; 

// Fetch Event Details
$sql = "SELECT * FROM events WHERE id = '$event_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$total_price = $row['price'] * $qty;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - EventSys</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .checkout-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        
        /* --- FIXED INPUT STYLING (Dark Theme) --- */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.1) !important; /* Glass Effect */
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: #fff !important; /* Text Color White */
        }
        
        /* Jab User Click Kare (Focus) */
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: #00d4ff !important; /* Neon Blue Border */
            box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
            color: white !important;
        }

        /* --- DROPDOWN OPTIONS FIX --- */
        /* Dropdown ke andar jo list khulti hai wo dark honi chahiye */
        option {
            background-color: #0f0c29; /* Dark Background */
            color: white;
            padding: 10px;
        }

        /* Summary Box */
        .summary-box {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 20px;
            border-left: 4px solid #00d4ff;
        }
        .btn-confirm {
            background: linear-gradient(45deg, #00d4ff, #005bea);
            border: none; font-weight: bold; padding: 12px;
            transition: 0.3s;
            color: white;
        }
        .btn-confirm:hover { 
            transform: scale(1.02); 
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.6); 
            color: white;
        }
        
        /* Placeholder Color */
        ::placeholder { color: rgba(255, 255, 255, 0.5) !important; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="checkout-container row">
        
        <h2 class="mb-4 fw-bold text-center">Complete Your Booking <i class="bi bi-bag-check-fill text-info"></i></h2>

        <div class="col-md-7 border-end border-secondary pe-4">
            <h5 class="mb-3 text-info">Contact Details</h5>
            <form action="book.php" method="POST">
                
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <input type="hidden" name="quantity" value="<?php echo $qty; ?>">

                <div class="mb-3">
                    <label class="form-label text-white-50">Full Name</label>
                    <input type="text" class="form-control" value="<?php echo $_SESSION['user_name']; ?>" name="name">
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50">Email Address</label>
                    <input type="email" class="form-control" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" name="email" placeholder="Enter your email">
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50">Phone Number (Required)</label>
                    <input type="tel" name="phone" class="form-control" placeholder="0300-1234567" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50">Payment Method</label>
                    <select class="form-select" name="payment_method">
                        <option value="cash">Cash on Venue</option>
                        <option value="card">Credit/Debit Card (Visa/Master)</option>
                        <option value="easypaisa">EasyPaisa / JazzCash</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-confirm w-100 rounded-pill">
                        Confirm & Pay Rs. <?php echo number_format($total_price); ?>
                    </button>
                    <a href="index.php" class="btn btn-outline-light w-100 rounded-pill mt-2 border-0">Cancel</a>
                </div>
            </form>
        </div>

        <div class="col-md-5 ps-4 d-flex flex-column justify-content-center">
            <div class="summary-box">
                <h5 class="mb-3 text-white">Order Summary</h5>
                <img src="<?php echo $row['image_url']; ?>" class="img-fluid rounded mb-3" style="height: 180px; width: 100%; object-fit: cover;">
                
                <h4 class="fw-bold"><?php echo $row['title']; ?></h4>
                <p class="text-white-50 mb-1"><i class="bi bi-geo-alt"></i> <?php echo $row['venue']; ?></p>
                <p class="text-white-50"><i class="bi bi-calendar-event"></i> <?php echo $row['event_date']; ?></p>
                
                <hr class="bg-secondary">
                
                <div class="d-flex justify-content-between mb-2">
                    <span>Ticket Price</span>
                    <span>Rs. <?php echo number_format($row['price']); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Quantity</span>
                    <span>x <?php echo $qty; ?></span>
                </div>
                <hr class="bg-secondary">
                <div class="d-flex justify-content-between fs-4 fw-bold text-info">
                    <span>Total</span>
                    <span>Rs. <?php echo number_format($total_price); ?></span>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>