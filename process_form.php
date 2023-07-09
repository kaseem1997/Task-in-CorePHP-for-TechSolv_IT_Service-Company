<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate form data
    $errors = array();

    if (empty($fullname)) {
        $errors['fullname'] = 'Full Name is required';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Phone Number is required';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($subject)) {
        $errors['subject'] = 'Subject is required';
    }

    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }

    // If there are no errors, save data to database and send email
    if (empty($errors)) {
        // Database Connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "techsolv_it_service";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Save data to database
        $sql = "INSERT INTO contact_form (fullname, phone, email, subject, message) VALUES ('$fullname', '$phone', '$email', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            // Send email notification
            $to = 'test@techsolvitservice.com';
            $subject = 'New Contact Form Submission';
            $email_message = "Full Name: $fullname\nPhone Number: $phone\nEmail: $email\nSubject: $subject\nMessage: $message";
            $headers = "From: $email";

            if (mail($to, $subject, $email_message, $headers)) {
                // Display success message to the user
                echo '<h2>Form submitted successfully. Thank you for your message!</h2>';
            } else {
                // Display error message if email failed to send
                echo '<h2>Failed to send email. Please try again later.</h2>';
            }
        } else {
            // Display error message if database insert failed
            echo '<h2>Failed to save form data to the database.</h2>';
        }

        $conn->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
    }
}
