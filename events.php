<?php
require_once 'config.php';

// Handle event registration
$registration_message = '';
$registration_message_type = '';

if (isset($_POST['register_event']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_location = mysqli_real_escape_string($conn, $_POST['event_location']);
    
    // Check if already registered
    $check_query = "SELECT id FROM event_registrations WHERE user_id = $user_id AND event_name = '$event_name'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) == 0) {
        // Removed 'category' from the INSERT query
        $insert_query = "INSERT INTO event_registrations (user_id, event_name, event_date, event_location) 
                         VALUES ($user_id, '$event_name', '$event_date', '$event_location')";
        if (mysqli_query($conn, $insert_query)) {
            $registration_message = "✅ Successfully registered for $event_name!";
            $registration_message_type = 'success';
        } else {
            $registration_message = "❌ Registration failed: " . mysqli_error($conn);
            $registration_message_type = 'error';
        }
    } else {
        $registration_message = "ℹ️ You are already registered for this event!";
        $registration_message_type = 'info';
    }
    
    // Store in session to show after redirect
    $_SESSION['registration_message'] = $registration_message;
    $_SESSION['registration_message_type'] = $registration_message_type;
    
    // Redirect to prevent form resubmission
    header('Location: events.php');
    exit();
}

// Check for session messages
if (isset($_SESSION['registration_message'])) {
    $registration_message = $_SESSION['registration_message'];
    $registration_message_type = $_SESSION['registration_message_type'];
    
    // Clear session messages
    unset($_SESSION['registration_message']);
    unset($_SESSION['registration_message_type']);
}

// Get user's registered events
$registered_events = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $reg_query = "SELECT event_name FROM event_registrations WHERE user_id = $user_id";
    $reg_result = mysqli_query($conn, $reg_query);
    while ($row = mysqli_fetch_assoc($reg_result)) {
        $registered_events[] = $row['event_name'];
    }
}

// Get current month and year for featured events
$current_month = date('F');
$current_year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Earth NGO | Events</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary-green: #2e7d32;
            --secondary-cream: #f5f5dc;
            --accent-brown: #8b5a2b;
            --register-orange: #e67e22;
            --donate-gold: #ffd700;
            --text-dark: #2c3e50;
            --light-gray: #f4f4f4;
            --event-blue: #3498db;
            --event-purple: #9b59b6;
            --event-red: #e74c3c;
        }
        
        body {
            background-color: #ffffff;
            color: var(--text-dark);
        }
        
        /* Navbar Styles - ENLARGED */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background-color: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .green-earth-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(145deg, #2e7d32, #1b5e20, #8b5a2b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 12px rgba(0,60,0,0.4), inset 0 2px 4px rgba(255,255,255,0.5);
            border: 3px solid #f5f5dc;
            position: relative;
            animation: pulse 2s infinite ease-in-out;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 6px 12px rgba(0,60,0,0.4);
            }
            50% {
                box-shadow: 0 8px 20px rgba(46,125,50,0.7), 0 0 0 3px rgba(245,245,220,0.5);
            }
            100% {
                box-shadow: 0 6px 12px rgba(0,60,0,0.4);
            }
        }
        
        .green-earth-logo::before {
            content: "🌍";
            font-size: 42px;
            filter: drop-shadow(2px 4px 4px rgba(0,0,0,0.3));
            transform: scale(1.1);
            transition: transform 0.3s;
        }
        
        .green-earth-logo:hover::before {
            transform: scale(1.2) rotate(10deg);
        }
        
        .green-earth-logo::after {
            content: "";
            position: absolute;
            top: 5px;
            left: 10px;
            width: 15px;
            height: 15px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
            filter: blur(2px);
        }
        
        .ngo-fullform {
            display: flex;
            flex-direction: column;
        }
        
        .ngo-fullform .ngo {
            font-size: 34px;
            font-weight: 800;
            color: var(--primary-green);
            line-height: 1.1;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .ngo-fullform .fullform-text {
            font-size: 15px;
            color: var(--accent-brown);
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--accent-brown), var(--primary-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s;
            font-size: 16px;
            padding: 8px 0;
            position: relative;
            white-space: nowrap;
        }
        
        .nav-links a:hover {
            color: var(--primary-green);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-green);
            transition: width 0.3s;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .donate-nav-btn {
            background-color: var(--donate-gold) !important;
            color: var(--text-dark) !important;
            padding: 12px 25px !important;
            border-radius: 50px;
            font-weight: 700 !important;
            box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            border: 2px solid transparent;
            transition: all 0.3s !important;
            animation: glow 2s infinite;
        }
        
        .donate-nav-btn:hover {
            background-color: #e6c200 !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.7);
            border-color: white;
        }
        
        @keyframes glow {
            0% {
                box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            }
            50% {
                box-shadow: 0 8px 25px rgba(255, 215, 0, 0.8), 0 0 0 3px rgba(255, 215, 0, 0.3);
            }
            100% {
                box-shadow: 0 6px 15px rgba(255, 215, 0, 0.5);
            }
        }
        
        .register-btn {
            background-color: var(--register-orange) !important;
            color: white !important;
            padding: 12px 25px !important;
            border-radius: 50px;
            font-weight: 700 !important;
            box-shadow: 0 6px 15px rgba(230,126,34,0.5);
            border: 2px solid transparent;
            transition: all 0.3s !important;
            letter-spacing: 0.5px;
        }
        
        .register-btn:hover {
            background-color: #d35400 !important;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 25px rgba(230,126,34,0.7);
            border-color: white;
        }
        
        .register-btn::after {
            display: none !important;
        }
        
        /* Registration Message Styles - IMPROVED */
        .registration-message {
            padding: 15px 25px;
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            animation: slideDown 0.5s ease;
            position: relative;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .registration-message.success {
            background-color: #4CAF50;
            color: white;
        }
        
        .registration-message.error {
            background-color: #f44336;
            color: white;
        }
        
        .registration-message.info {
            background-color: #2196F3;
            color: white;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(rgba(139,90,43,0.9), rgba(46,125,50,0.9)), 
            url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        
        .page-header h1 {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .page-header p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Login Prompt Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            max-width: 400px;
            text-align: center;
            position: relative;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-content h3 {
            color: var(--primary-green);
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .modal-content p {
            color: #666;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        
        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .modal-btn {
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .modal-btn-primary {
            background-color: var(--primary-green);
            color: white;
        }
        
        .modal-btn-primary:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
        }
        
        .modal-btn-secondary {
            background-color: #e0e0e0;
            color: var(--text-dark);
        }
        
        .modal-btn-secondary:hover {
            background-color: #d0d0d0;
            transform: translateY(-2px);
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }
        
        .close-modal:hover {
            color: var(--primary-green);
        }
        
        /* Featured Event Banner */
        .featured-event {
            background: linear-gradient(135deg, var(--event-purple), var(--event-blue));
            padding: 40px 5%;
            color: white;
        }
        
        .featured-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }
        
        .featured-badge {
            background-color: var(--donate-gold);
            color: var(--text-dark);
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .featured-title {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .featured-countdown {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        
        .countdown-item {
            text-align: center;
        }
        
        .countdown-number {
            font-size: 32px;
            font-weight: 700;
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 10px;
            min-width: 60px;
        }
        
        .countdown-label {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .featured-btn {
            background-color: var(--donate-gold);
            color: var(--text-dark);
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .featured-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        /* Filter Section */
        .filter-section {
            padding: 40px 5%;
            background-color: white;
            border-bottom: 1px solid #eee;
        }
        
        .filter-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .filter-btn {
            padding: 12px 25px;
            border: 2px solid var(--primary-green);
            background: transparent;
            color: var(--primary-green);
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background-color: var(--primary-green);
            color: white;
        }
        
        .search-bar {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
            gap: 10px;
        }
        
        .search-bar input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 16px;
        }
        
        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-green);
        }
        
        .search-bar button {
            padding: 12px 30px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .search-bar button:hover {
            background-color: #1b5e20;
        }
        
        /* Events Section */
        .events-section {
            padding: 60px 5%;
            background-color: var(--light-gray);
        }
        
        .events-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .month-divider {
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-green);
        }
        
        .month-divider h2 {
            color: var(--primary-green);
            font-size: 28px;
        }
        
        .event-card {
            display: flex;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: all 0.3s;
            opacity: 1;
            transform: scale(1);
            position: relative;
        }
        
        .event-card:hover {
            transform: translateX(10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .event-card.hidden {
            display: none;
        }
        
        .event-card.featured {
            border-left: 5px solid var(--donate-gold);
        }
        
        .event-date {
            background: linear-gradient(145deg, var(--primary-green), #1b5e20);
            color: white;
            padding: 40px 30px;
            min-width: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        
        .event-date .day {
            font-size: 52px;
            font-weight: 800;
            line-height: 1;
        }
        
        .event-date .month {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .event-date .year {
            font-size: 18px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .event-details {
            padding: 30px 40px;
            flex: 1;
        }
        
        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .event-category {
            display: inline-block;
            background-color: var(--secondary-cream);
            color: var(--accent-brown);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .event-category.cleanup { background-color: #e1f5fe; color: #0288d1; }
        .event-category.tree { background-color: #e8f5e9; color: #2e7d32; }
        .event-category.workshop { background-color: #fff3e0; color: #f57c00; }
        .event-category.fundraiser { background-color: #fce4ec; color: #c2185b; }
        .event-category.wildlife { background-color: #f3e5f5; color: #7b1fa2; }
        .event-category.education { background-color: #e3f2fd; color: #1565c0; }
        
        .event-spots {
            background-color: #ff6b6b;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .event-spots.low {
            background-color: #f44336;
        }
        
        .event-spots.medium {
            background-color: #ff9800;
        }
        
        .event-spots.high {
            background-color: #4caf50;
        }
        
        .event-details h3 {
            color: var(--primary-green);
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .event-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            color: var(--accent-brown);
            font-weight: 500;
        }
        
        .event-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .event-description {
            color: #666;
            line-height: 1.8;
            margin-bottom: 25px;
        }
        
        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .event-organizer {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .organizer-avatar {
            width: 40px;
            height: 40px;
            background-color: var(--light-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary-green);
        }
        
        .organizer-info {
            font-size: 14px;
        }
        
        .organizer-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .organizer-role {
            color: #999;
        }
        
        .event-button {
            background-color: var(--primary-green);
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .event-button:hover {
            background-color: #1b5e20;
            transform: scale(1.05);
        }
        
        .event-button.registered {
            background-color: #4CAF50;
            cursor: default;
        }
        
        .event-button.registered:hover {
            background-color: #4CAF50;
            transform: none;
        }
        
        .event-button:disabled {
            background-color: #999;
            cursor: not-allowed;
        }
        
        .event-button:disabled:hover {
            background-color: #999;
            transform: none;
        }
        
        /* Calendar View */
        .calendar-section {
            padding: 60px 5%;
            background-color: white;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .calendar-title {
            color: var(--primary-green);
            font-size: 32px;
        }
        
        .calendar-nav {
            display: flex;
            gap: 10px;
        }
        
        .calendar-nav button {
            padding: 10px 20px;
            border: 2px solid var(--primary-green);
            background: transparent;
            color: var(--primary-green);
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .calendar-nav button:hover {
            background-color: var(--primary-green);
            color: white;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: var(--primary-green);
            padding: 10px;
        }
        
        .calendar-day {
            border: 1px solid #eee;
            min-height: 100px;
            padding: 10px;
            border-radius: 5px;
        }
        
        .calendar-day.today {
            background-color: #e8f5e9;
            border-color: var(--primary-green);
        }
        
        .calendar-day-number {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .calendar-event {
            background-color: var(--primary-green);
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            margin-bottom: 3px;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .calendar-event.cleanup { background-color: #0288d1; }
        .calendar-event.tree { background-color: #2e7d32; }
        .calendar-event.workshop { background-color: #f57c00; }
        .calendar-event.fundraiser { background-color: #c2185b; }
        
        /* No Events Message */
        .no-events {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-events h3 {
            color: var(--primary-green);
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .no-events p {
            color: #666;
            font-size: 18px;
        }
        
        /* Newsletter */
        .newsletter {
            background: linear-gradient(135deg, var(--primary-green), #1b5e20);
            padding: 60px 5%;
            text-align: center;
            color: white;
        }
        
        .newsletter h2 {
            font-size: 36px;
            margin-bottom: 15px;
        }
        
        .newsletter p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .newsletter-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
            gap: 10px;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
        }
        
        .newsletter-form button {
            padding: 15px 35px;
            background-color: var(--donate-gold);
            color: var(--text-dark);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .newsletter-form button:hover {
            background-color: #e6c200;
        }
        
        /* Footer */
        footer {
            background-color: #1e2a2f;
            color: white;
            padding: 60px 5% 30px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 50px;
            max-width: 1300px;
            margin: 0 auto 50px;
        }
        
        .footer-section h3 {
            color: var(--secondary-cream);
            margin-bottom: 25px;
            font-size: 22px;
            position: relative;
            padding-bottom: 12px;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-green);
        }
        
        .footer-section p, .footer-section a {
            color: #b0c4c9;
            text-decoration: none;
            line-height: 2;
        }
        
        .footer-section a:hover {
            color: var(--secondary-cream);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #2a3a40;
            color: #8a9ca0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .event-card {
                flex-direction: column;
            }
            
            .event-date {
                flex-direction: row;
                justify-content: space-around;
                padding: 20px;
            }
            
            .featured-container {
                grid-template-columns: 1fr;
            }
            
            .calendar-grid {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar - ENLARGED with CORRECT ORDER -->
    <nav class="navbar">
        <div class="logo-container">
            <div class="green-earth-logo" title="Green Earth NGO"></div>
            <div class="ngo-fullform">
                <div class="ngo">NGO</div>
                <div class="fullform-text">Non-governmental organization</div>
            </div>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="social.php">Social Services</a>
            <a href="benefits.php">Benefits</a>
            <a href="events.php">Events</a>
            <a href="collaborations.php">Collaborations</a>
            <a href="blog.php">Blog</a>
            <a href="gallery.php">Gallery</a>
            <a href="volunteer.php">Volunteer</a>
            <a href="branches.php">Branches</a>
            <a href="contact.php">Contact Us</a>
            <a href="donate.php" class="donate-nav-btn">💰 Donate</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php" class="register-btn">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="register-btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Registration Message -->
    <?php if(isset($registration_message) && $registration_message): ?>
        <div class="registration-message <?php echo $registration_message_type; ?>">
            <span><?php echo $registration_message; ?></span>
            <?php if($registration_message_type == 'success'): ?>
                <span style="font-size: 20px;">🎉</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Login Prompt Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Login Required</h3>
            <p>You need to be logged in to register for events. Please login or create an account to continue.</p>
            <div class="modal-buttons">
                <a href="login.php" class="modal-btn modal-btn-primary">Login</a>
                <a href="register.php" class="modal-btn modal-btn-secondary">Register</a>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <section class="page-header">
        <div>
            <h1>Events & Activities</h1>
            <p>Join us in making a difference through these environmental initiatives</p>
        </div>
    </section>

    <!-- Featured Event -->
    <section class="featured-event">
        <div class="featured-container">
            <div>
                <span class="featured-badge">🌟 Featured Event</span>
                <h2 class="featured-title">Annual Climate Action Summit 2024</h2>
                <p>Join global leaders, activists, and experts for the biggest environmental conference of the year. Network, learn, and collaborate on climate solutions.</p>
                <div class="featured-countdown">
                    <div class="countdown-item">
                        <div class="countdown-number" id="days">45</div>
                        <div class="countdown-label">Days</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="hours">12</div>
                        <div class="countdown-label">Hours</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="minutes">30</div>
                        <div class="countdown-label">Minutes</div>
                    </div>
                </div>
                <a href="#" class="featured-btn">Learn More & Register</a>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80" alt="Climate Summit" style="width: 100%; border-radius: 15px;">
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="filter-container">
            <div class="filter-tabs">
                <button class="filter-btn active" onclick="filterEvents('all')">All Events</button>
                <button class="filter-btn" onclick="filterEvents('cleanup')">Clean-up Drives</button>
                <button class="filter-btn" onclick="filterEvents('tree')">Tree Planting</button>
                <button class="filter-btn" onclick="filterEvents('workshop')">Workshops</button>
                <button class="filter-btn" onclick="filterEvents('fundraiser')">Fundraisers</button>
                <button class="filter-btn" onclick="filterEvents('wildlife')">Wildlife</button>
                <button class="filter-btn" onclick="filterEvents('education')">Education</button>
            </div>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search events by name or location...">
                <button onclick="searchEvents()">Search</button>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="events-section">
        <div class="events-container" id="eventsContainer">
            
            <!-- JUNE 2024 -->
            <div class="month-divider">
                <h2>June 2024</h2>
            </div>

            <!-- Clean-up Drive Event -->
            <div class="event-card" data-category="cleanup" data-name="Beach Cleanup Drive" data-location="Miami">
                <div class="event-date">
                    <span class="day">15</span>
                    <span class="month">June</span>
                    <span class="year">2024</span>
                </div>
                <div class="event-details">
                    <div class="event-header">
                        <span class="event-category cleanup">🌊 Clean-up Drive</span>
                        <span class="event-spots low">🔥 Only 5 spots left!</span>
                    </div>
                    <h3>Beach Cleanup Drive</h3>
                    <div class="event-meta">
                        <span>📍 Miami Beach, Florida</span>
                        <span>⏰ 8:00 AM - 12:00 PM</span>
                        <span>👥 25 volunteers needed</span>
                    </div>
                    <p class="event-description">Join us for a morning of cleaning up Miami Beach. We'll provide gloves, bags, and refreshments. Together, we can protect our oceans and marine life from plastic pollution. All ages welcome!</p>
                    <div class="event-footer">
                        <div class="event-organizer">
                            <div class="organizer-avatar">MJ</div>
                            <div class="organizer-info">
                                <div class="organizer-name">Maria Johnson</div>
                                <div class="organizer-role">Event Coordinator</div>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="event_name" value="Beach Cleanup Drive">
                                <input type="hidden" name="event_date" value="June 15, 2024">
                                <input type="hidden" name="event_location" value="Miami Beach, Florida">
                                <?php if(in_array('Beach Cleanup Drive', $registered_events)): ?>
                                    <button type="button" class="event-button registered" disabled>
                                        <span>✓</span> Already Registered
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="register_event" class="event-button">
                                        <span>📝</span> Register Now
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <button onclick="checkLoginAndRegister('Beach Cleanup Drive')" class="event-button">
                                <span>📝</span> Register to Join
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tree Planting Event -->
            <div class="event-card featured" data-category="tree" data-name="Tree Planting Festival" data-location="Oregon">
                <div class="event-date">
                    <span class="day">22</span>
                    <span class="month">June</span>
                    <span class="year">2024</span>
                </div>
                <div class="event-details">
                    <div class="event-header">
                        <span class="event-category tree">🌳 Tree Planting</span>
                        <span class="event-spots medium">📅 2 weeks away</span>
                    </div>
                    <h3>Tree Planting Festival 🌟</h3>
                    <div class="event-meta">
                        <span>📍 Greenwood Park, Oregon</span>
                        <span>⏰ 9:00 AM - 3:00 PM</span>
                        <span>👥 100 volunteers needed</span>
                    </div>
                    <p class="event-description">Help us plant 1000 native trees in Greenwood Park. Learn proper planting techniques and contribute to reforestation efforts. Lunch and tools provided. Family-friendly event with activities for kids!</p>
                    <div class="event-footer">
                        <div class="event-organizer">
                            <div class="organizer-avatar">DS</div>
                            <div class="organizer-info">
                                <div class="organizer-name">David Smith</div>
                                <div class="organizer-role">Forestry Expert</div>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="event_name" value="Tree Planting Festival">
                                <input type="hidden" name="event_date" value="June 22, 2024">
                                <input type="hidden" name="event_location" value="Greenwood Park, Oregon">
                                <?php if(in_array('Tree Planting Festival', $registered_events)): ?>
                                    <button type="button" class="event-button registered" disabled>
                                        <span>✓</span> Already Registered
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="register_event" class="event-button">
                                        <span>📝</span> Register Now
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <button onclick="checkLoginAndRegister('Tree Planting Festival')" class="event-button">
                                <span>📝</span> Register to Join
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- JULY 2024 -->
            <div class="month-divider">
                <h2>July 2024</h2>
            </div>

            <!-- Workshop Event -->
            <div class="event-card" data-category="workshop" data-name="Sustainable Living Workshop" data-location="Austin">
                <div class="event-date">
                    <span class="day">05</span>
                    <span class="month">July</span>
                    <span class="year">2024</span>
                </div>
                <div class="event-details">
                    <div class="event-header">
                        <span class="event-category workshop">📚 Workshop</span>
                        <span class="event-spots high">✅ 20 spots available</span>
                    </div>
                    <h3>Sustainable Living Workshop</h3>
                    <div class="event-meta">
                        <span>📍 Community Center, Austin</span>
                        <span>⏰ 10:00 AM - 2:00 PM</span>
                        <span>👥 30 participants max</span>
                    </div>
                    <p class="event-description">Learn practical tips for sustainable living including composting, reducing plastic use, and energy conservation. Interactive sessions with environmental experts. Take home a free compost starter kit!</p>
                    <div class="event-footer">
                        <div class="event-organizer">
                            <div class="organizer-avatar">LG</div>
                            <div class="organizer-info">
                                <div class="organizer-name">Lisa Green</div>
                                <div class="organizer-role">Sustainability Coach</div>
                            </div>
                        </div>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="event_name" value="Sustainable Living Workshop">
                                <input type="hidden" name="event_date" value="July 5, 2024">
                                <input type="hidden" name="event_location" value="Community Center, Austin">
                                <?php if(in_array('Sustainable Living Workshop', $registered_events)): ?>
                                    <button type="button" class="event-button registered" disabled>
                                        <span>✓</span> Already Registered
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="register_event" class="event-button">
                                        <span>📝</span> Register Now
                                    </button>
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <button onclick="checkLoginAndRegister('Sustainable Living Workshop')" class="event-button">
                                <span>📝</span> Register to Join
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Rest of the events remain the same - just copy them from your original file -->
            <!-- ... -->
        </div>
    </section>

    <!-- Calendar View Section -->
    <section class="calendar-section">
        <div class="calendar-header">
            <h2 class="calendar-title">📅 Event Calendar - <?php echo $current_month . ' ' . $current_year; ?></h2>
            <div class="calendar-nav">
                <button onclick="changeMonth(-1)">← Previous</button>
                <button onclick="changeMonth(1)">Next →</button>
            </div>
        </div>
        <div class="calendar-grid" id="calendarGrid">
            <!-- Calendar days will be generated by JavaScript -->
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter">
        <h2>Stay Updated</h2>
        <p>Subscribe to our newsletter to receive updates about upcoming events and initiatives</p>
        <form class="newsletter-form" onsubmit="event.preventDefault(); newsletterSubscribe(this);">
            <input type="email" id="newsletter-email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Green Earth NGO</h3>
                <p>Non-governmental organization dedicated to environmental conservation since 2010.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <p><a href="index.php">Home</a><br>
                <a href="social.php">Social Services</a><br>
                <a href="benefits.php">Benefits</a><br>
                <a href="events.php">Events</a><br>
                <a href="branches.php">Branches</a><br>
                <a href="contact.php">Contact</a><br>
                <a href="collaborations.php">Collaborations</a><br>
                <a href="blog.php">Blog</a><br>
                <a href="gallery.php">Gallery</a><br>
                <a href="volunteer.php">Volunteer</a><br>
                <a href="donate.php">Donate</a></p>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <p>Email: info@greenearthngo.org<br>
                Phone: +1 (555) 123-4567<br>
                Address: 123 Green Avenue, Earth City</p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 Green Earth NGO - Non-governmental organization. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Check login status from PHP session
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        
        function checkLoginAndRegister(eventName) {
            if (!isLoggedIn) {
                document.getElementById('loginModal').style.display = 'flex';
            }
        }
        
        function closeModal() {
            document.getElementById('loginModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        // Filter events by category
        function filterEvents(category) {
            const events = document.querySelectorAll('.event-card');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            filterButtons.forEach(button => {
                button.classList.remove('active');
            });
            
            const activeButton = Array.from(filterButtons).find(button => {
                const buttonText = button.textContent.toLowerCase();
                if (category === 'all' && buttonText.includes('all')) return true;
                if (category === 'cleanup' && (buttonText.includes('clean-up') || buttonText.includes('cleanup'))) return true;
                if (category === 'tree' && buttonText.includes('tree')) return true;
                if (category === 'workshop' && buttonText.includes('workshop')) return true;
                if (category === 'fundraiser' && buttonText.includes('fundraiser')) return true;
                if (category === 'wildlife' && buttonText.includes('wildlife')) return true;
                if (category === 'education' && buttonText.includes('education')) return true;
                return false;
            });
            
            if (activeButton) {
                activeButton.classList.add('active');
            }
            
            events.forEach(event => {
                const eventCategories = event.getAttribute('data-category').split(' ');
                if (category === 'all') {
                    event.classList.remove('hidden');
                } else if (eventCategories.includes(category)) {
                    event.classList.remove('hidden');
                } else {
                    event.classList.add('hidden');
                }
            });
        }
        
        // Search events
        function searchEvents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const events = document.querySelectorAll('.event-card');
            
            events.forEach(event => {
                const eventName = event.getAttribute('data-name').toLowerCase();
                const eventLocation = event.getAttribute('data-location').toLowerCase();
                
                if (eventName.includes(searchTerm) || eventLocation.includes(searchTerm)) {
                    event.classList.remove('hidden');
                } else {
                    event.classList.add('hidden');
                }
            });
        }
        
        // Newsletter subscription
        function newsletterSubscribe(form) {
            const email = document.getElementById('newsletter-email').value;
            alert(`Thank you for subscribing with ${email}! You'll receive our event updates.`);
        }
        
        // Countdown timer for featured event
        function updateCountdown() {
            const eventDate = new Date('June 22, 2024 09:00:00').getTime();
            const now = new Date().getTime();
            const distance = eventDate - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById('days').textContent = days;
            document.getElementById('hours').textContent = hours;
            document.getElementById('minutes').textContent = minutes;
        }
        
        // Calendar navigation
        function changeMonth(direction) {
            alert('Calendar navigation would load ' + (direction > 0 ? 'next' : 'previous') + ' month');
        }
        
        // Auto-hide message after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            filterEvents('all');
            updateCountdown();
            setInterval(updateCountdown, 60000); // Update every minute
            
            const messageElement = document.querySelector('.registration-message');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.transition = 'opacity 0.5s';
                    messageElement.style.opacity = '0';
                    setTimeout(() => {
                        messageElement.style.display = 'none';
                    }, 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>