<?php
http_response_code(403);
echo "Access Denied. For security reasons, this setup script has been deactivated.";
exit;
require_once 'connection.php';

// Create Tables
$tables = [
    "lms_categories" => "CREATE TABLE IF NOT EXISTS lms_categories (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        name VARCHAR(255) NOT NULL, 
        slug VARCHAR(255) NOT NULL
    )",
    "lms_courses" => "CREATE TABLE IF NOT EXISTS lms_courses (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        title VARCHAR(255) NOT NULL, 
        slug VARCHAR(255) NOT NULL, 
        short_desc TEXT, 
        long_desc TEXT, 
        thumbnail VARCHAR(255), 
        price DECIMAL(10,2), 
        discount_price DECIMAL(10,2), 
        duration VARCHAR(100), 
        level VARCHAR(50), 
        instructor VARCHAR(255), 
        category_id INT, 
        status TINYINT DEFAULT 1, 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    "lms_lessons" => "CREATE TABLE IF NOT EXISTS lms_lessons (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        course_id INT NOT NULL, 
        title VARCHAR(255) NOT NULL, 
        description TEXT, 
        content_type ENUM('video', 'pdf', 'text') DEFAULT 'video', 
        content_url TEXT, 
        is_preview TINYINT DEFAULT 0, 
        sort_order INT DEFAULT 0
    )",
    "lms_enrollments" => "CREATE TABLE IF NOT EXISTS lms_enrollments (
        id INT AUTO_INCREMENT PRIMARY KEY, 
        user_id INT NOT NULL, 
        course_id INT NOT NULL, 
        amount DECIMAL(10,2), 
        payment_id VARCHAR(255), 
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending', 
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )"
];

foreach ($tables as $name => $sql) {
    if (mysqli_query($con, $sql)) {
        echo "Table $name created or already exists.\n";
    } else {
        echo "Error creating table $name: " . mysqli_error($con) . "\n";
    }
}

// Insert dummy data if empty
$count = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM lms_courses"))['total'];
if ($count == 0) {
    mysqli_query($con, "INSERT INTO lms_categories (name, slug) VALUES ('Web Development', 'web-dev'), ('Graphic Design', 'graphic-design')");
    mysqli_query($con, "INSERT INTO lms_courses (title, slug, short_desc, price, duration, level, instructor, category_id) VALUES 
    ('Modern Web Development 2026', 'modern-web-dev', 'Master HTML, CSS, JS and more.', 4999.00, '40', 'Beginner', 'John Doe', 1),
    ('Mastering UI/UX Design', 'ui-ux-design', 'Design beautiful interfaces.', 2999.00, '25', 'Intermediate', 'Jane Smith', 2)");
    echo "Dummy data inserted.\n";
}
