<?php
$servername = "localhost";
$username = "root";
$password = "";

// 1. Connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// 2. Database Reset
$conn->query("CREATE DATABASE IF NOT EXISTS event_db");
$conn->select_db("event_db");

$conn->query("SET FOREIGN_KEY_CHECKS = 0");
$conn->query("TRUNCATE TABLE bookings");
$conn->query("TRUNCATE TABLE events");
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// 3. Define Image Paths (LOCAL FILES - 100% Safe)
// Ye file names wohi hone chahiye jo tumne 'images' folder mein rakhe hain
$img_music = "images/music.jpg";
$img_tech = "images/tech.jpg";
$img_cricket = "images/cricket.jpg";
$img_food = "images/food.jpg";
$img_art = "images/art.jpg";

// Arrays
$prefixes = ["Grand", "Annual", "Mega", "Live", "Exclusive"];
// Category Mapping
$types = [
    "Qawwali Night" => "music", 
    "Tech Expo" => "tech", 
    "Music Fest" => "music", 
    "Cricket Screening" => "cricket", 
    "Art Show" => "art", 
    "Food Festival" => "food",
    "Startup Meetup" => "tech"
];
$venues = ["Expo Center", "F-9 Park", "PC Hotel", "Arts Council", "Centaurus Mall"];

// --- INSERT DATA LOOP ---
echo "<h3>Setting up Local Images...</h3>";

for ($i = 0; $i < 20; $i++) {
    
    $type_name = array_rand($types); // Event Type Name
    $category = $types[$type_name];  // Category (music, tech, etc)

    $title = $prefixes[array_rand($prefixes)] . " " . $type_name . " " . (2026 + rand(0, 2));
    $venue = $venues[array_rand($venues)];
    $price = rand(500, 3000);
    $date = date("Y-m-d", mt_rand(1760000000, 1800000000));
    $hour = rand(17, 23); $minute = rand(0, 59);
    $time = sprintf("%02d:%02d:00", $hour, $minute);
    
    // --- SMART LOCAL IMAGE ASSIGNMENT ---
    if ($category == "music") {
        $image = $img_music;
    } elseif ($category == "tech") {
        $image = $img_tech;
    } elseif ($category == "cricket") {
        $image = $img_cricket;
    } elseif ($category == "food") {
        $image = $img_food;
    } else {
        $image = $img_art;
    }

    $desc = "Join us for an amazing $type_name at $venue. Best event of the year!";

    // Insert Query
    $sql = "INSERT INTO events (title, description, event_date, event_time, venue, price, image_url) 
            VALUES ('$title', '$desc', '$date', '$time', '$venue', '$price', '$image')";
            
    if(!$conn->query($sql)){
        echo "Error: " . $conn->error;
    }
}

echo "<h1 style='color:green; font-family:sans-serif'>SUCCESS! Local Images Connected.</h1>";
echo "<p>Make sure you have put music.jpg, tech.jpg etc inside 'images' folder.</p>";
echo "<a href='index.php' style='font-size:20px; font-weight:bold;'>Go to Website >></a>";
?>