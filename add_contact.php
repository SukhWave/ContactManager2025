<?php
    session_start();

    // get data from the form
    $first_name = filter_input(INPUT_POST, 'first_name');
    // alternative
    //$first_name = $_POST['first_name'];
    $last_name = filter_input(INPUT_POST, 'last_name');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status= filter_input(INPUT_POST, 'status'); // assigns the value of the selected radio button
    $dob = filter_input(INPUT_POST, 'dob');

    require_once('database.php');

    //Add the contact to the database
    $query = 'INSERT INTO contacts
        (firstName, lastName, emailAddress, phone, status, dob)
        VALUES
        (:firstName, :lastName, :emailAddress, :phone, :status, :dob)';

    $statement = $db->prepare($query);
    $statement->bindvalue(':firstName', $first_name);
    $statement->bindvalue(':lastName', $last_name);
    $statement->bindvalue(':emailAddress', $email_address); 
    $statement->bindvalue(':phone', $phone_number);
    $statement->bindvalue(':status', $status); 
    $statement->bindvalue(':dob', $dob);
    
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["FullName"] = $first_name . " " . $last_name;

    //redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); // releases add_contact.php from memory


?>