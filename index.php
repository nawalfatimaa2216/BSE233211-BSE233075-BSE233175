<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventSys - Premium Events</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            background-attachment: fixed;
            color: #fff;
            min-height: 100vh;
        }

        /* --- NAVBAR & GENERAL STYLES --- */
        .navbar { background: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 15px 0; }
        .nav-link { color: rgba(255, 255, 255, 0.8) !important; font-weight: 500; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .nav-link:hover { color: #00d4ff !important; text-shadow: 0 0 10px rgba(0, 212, 255, 0.5); }
        .nav-icon { font-size: 1.2rem; transition: transform 0.3s ease; }
        .nav-link:hover .nav-icon { animation: dance 0.5s ease infinite alternate; color: #00d4ff; }
        @keyframes dance { 0% { transform: rotate(0deg) scale(1); } 100% { transform: rotate(15deg) scale(1.2); } }
        .dropdown-item:hover { background-color: #00d4ff !important; color: #000 !important; font-weight: bold; }

        /* --- HERO & SEARCH --- */
        .hero-section {
            background: linear-gradient(to bottom, rgba(15, 12, 41, 0.3), #0f0c29), url('https://source.unsplash.com/1600x900/?concert,neon');
            background-size: cover; background-position: center;
            padding: 150px 0 100px 0; text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .search-box {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);
            padding: 8px; border-radius: 50px; border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2); max-width: 600px; margin: 0 auto;
        }
        .search-input { background: transparent; border: none; color: white; box-shadow: none !important; }
        .search-input::placeholder { color: rgba(255,255,255,0.6); }
        .btn-search-glow {
            background: #00d4ff; border: none; color: #000; font-weight: bold; transition: all 0.4s ease;
            display: flex; align-items: center; gap: 5px;
        }
        .btn-search-glow:hover { background: #fff; color: #00d4ff; box-shadow: 0 0 20px #00d4ff; transform: scale(1.05); }
        .btn-search-glow:hover .search-icon-anim { animation: searchPulse 0.8s infinite; }
        @keyframes searchPulse { 0% { transform: scale(1); } 50% { transform: scale(1.3); } 100% { transform: scale(1); } }

        /* --- CARD STYLES (UPDATED) --- */
        .event-card {
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; transition: all 0.4s ease; overflow: hidden; position: relative;
        }
        .event-card:hover {
            transform: translateY(-10px); background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: rgba(0, 212, 255, 0.3);
        }
        .card-img-top { height: 220px; object-fit: cover; transition: transform 0.5s; }
        .event-card:hover .card-img-top { transform: scale(1.05); }
        .card-title { color: #fff; margin-right: 10px; line-height: 1.4; } 
        
        /* FONT COLOR FIX: Description Text ko Light kiya hai */
        .description-text { color: rgba(255, 255, 255, 0.7) !important; }

        .date-badge {
            position: absolute; top: 15px; left: 15px;
            background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(5px);
            border-radius: 12px; text-align: center; border: 1px solid rgba(255,255,255,0.2);
            width: 60px; height: 60px; display: flex; flex-direction: column; justify-content: center; align-items: center; line-height: 1.1;
        }
        .date-month { font-size: 0.75rem; text-transform: uppercase; color: #00d4ff; font-weight: bold; }
        .date-day { font-size: 1.4rem; color: #fff; font-weight: bold; }
        
        .price-badge { 
            background: linear-gradient(45deg, #00d4ff, #005bea); 
            color: white; white-space: nowrap; transition: transform 0.2s ease;
        }
        .price-updated { transform: scale(1.1); }

        /* QUANTITY SELECTOR */
        .qty-wrapper {
            display: flex; align-items: center; background: rgba(255, 255, 255, 0.1);
            border-radius: 30px; padding: 3px; border: 1px solid rgba(255, 255, 255, 0.2); margin-right: 10px;
        }
        .qty-btn {
            width: 30px; height: 30px; background: transparent; border: none; color: white;
            font-size: 1.2rem; display: flex; align-items: center; justify-content: center;
            cursor: pointer; border-radius: 50%; transition: 0.3s;
        }
        .qty-btn:hover { background: #00d4ff; color: black; }
        .qty-display {
            width: 30px; text-align: center; background: transparent; border: none;
            color: #00d4ff; font-weight: bold; font-size: 1.1rem; pointer-events: none;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <i class="bi bi-lightning-charge-fill" style="color: #00d4ff;"></i> EventSys
            </a>
            <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php"><i class="bi bi-compass nav-icon"></i> Discover</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="my_bookings.php"><i class="bi bi-ticket-perforated nav-icon"></i> My Tickets</a></li>
                        <li class="nav-item"><a class="nav-link text-info" href="create_event.php"><i class="bi bi-plus-circle-dotted nav-icon"></i> Organize</a></li>
                        <li class="nav-item ms-3">
                            <div class="dropdown">
                                <a class="btn btn-outline-light btn-sm dropdown-toggle rounded-pill px-3" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> <?php echo $_SESSION['user_name']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary p-0 overflow-hidden">
                                    <li><a class="dropdown-item text-white py-2" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right nav-icon"></i> Login</a></li>
                        <li class="nav-item ms-2"><a class="btn btn-light rounded-pill px-4 fw-bold" href="register.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3 text-white">Find Your <span style="color: #00d4ff;">Vibe</span></h1>
            <p class="lead mb-5 text-white-50">Concerts, Tech, Art & More. Experience it all.</p>
            <form class="search-box d-flex" action="index.php" method="GET">
                <input class="form-control search-input" type="search" name="search" placeholder="Search events..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-search-glow rounded-pill px-4 ms-2" type="submit">
                    <i class="bi bi-search search-icon-anim"></i> Search
                </button>
            </form>
        </div>
    </div>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold text-white border-start border-5 border-info ps-3">Upcoming Events</h2>
            <?php if(isset($_GET['search'])): ?>
                <a href="index.php" class="btn btn-outline-light rounded-pill btn-sm">Clear Search</a>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php
            $search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $sql = $search_term ? "SELECT * FROM events WHERE title LIKE '%$search_term%' OR description LIKE '%$search_term%'" : "SELECT * FROM events ORDER BY event_date ASC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Date Formatting
                    $dateObj = date_create($row['event_date']);
                    $month = date_format($dateObj, "M");
                    $day = date_format($dateObj, "d");

                    // --- NEW: Time Formatting ---
                    // Agar database me time nahi hai to default utha lo, warna format karo
                    $timeValue = isset($row['event_time']) ? $row['event_time'] : '18:00:00';
                    $timeObj = date_create($timeValue);
                    $formattedTime = date_format($timeObj, "h:i A"); // e.g., 07:30 PM
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card event-card h-100">
                        <div style="overflow: hidden; position: relative;">
                            <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="Event">
                            <div class="date-badge">
                                <span class="date-month"><?php echo $month; ?></span>
                                <span class="date-day"><?php echo $day; ?></span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0"><?php echo $row['title']; ?></h5>
                                <span class="badge rounded-pill px-3 py-2 price-badge" data-base-price="<?php echo $row['price']; ?>">
                                    Rs. <?php echo number_format($row['price']); ?>
                                </span>
                            </div>

                            <p class="text-white small mb-1">
                                <i class="bi bi-clock-fill text-info me-1"></i> 
                                <strong><?php echo $formattedTime; ?></strong>
                            </p>

                            <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-info me-1"></i> <?php echo $row['venue']; ?></p>
                            
                            <p class="card-text small description-text"><?php echo substr($row['description'], 0, 85); ?>...</p>
                            
                            <div class="mt-auto pt-3">
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <form action="checkout.php" method="POST" class="d-flex align-items-center">
                                        <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                                        <div class="qty-wrapper">
                                            <button type="button" class="qty-btn" onclick="updateQty(this, -1)">-</button>
                                            <input type="text" name="quantity" value="1" class="qty-display" readonly>
                                            <button type="button" class="qty-btn" onclick="updateQty(this, 1)">+</button>
                                        </div>
                                        <button type="submit" class="btn btn-outline-light w-100 rounded-pill btn-sm fw-bold">Book Ticket</button>
                                    </form>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-outline-secondary w-100 rounded-pill">Login to Book</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                } 
            } else {
                echo "<div class='col-12 text-center py-5'><h4 class='text-muted'>No events found.</h4></div>";
            }
            ?>
        </div>
    </div>
    
    <script>
        function updateQty(btn, change) {
            let wrapper = btn.closest('.qty-wrapper');
            let input = wrapper.querySelector('.qty-display');
            let card = btn.closest('.event-card');
            let priceBadge = card.querySelector('.price-badge');
            
            let currentVal = parseInt(input.value);
            let newVal = currentVal + change;
            let basePrice = parseInt(priceBadge.getAttribute('data-base-price'));

            if (newVal >= 1 && newVal <= 4) {
                input.value = newVal;
                let newTotal = basePrice * newVal;
                priceBadge.textContent = "Rs. " + newTotal.toLocaleString();
                priceBadge.classList.add('price-updated');
                setTimeout(() => priceBadge.classList.remove('price-updated'), 200);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>