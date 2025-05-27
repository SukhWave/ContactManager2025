<?php
    session_start();

    $contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);

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
    $queryContacts = 'SELECT * FROM contacts';
    $statement1 = $db->prepare($queryContacts);
    $statement1->execute();
    $contacts = $statement1->fetchAll();

    $statement1->closeCursor();

    foreach($contacts as $contact)
    {
      if ($email_address == $contact["emailAddress"] && $contact_id != $contact["contactID"])
      {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";

        $url = "error.php";
        header("Location: " . $url);
        die(); 
      }
    }

    if($first_name == null || $last_name == null ||
      $email_address == null || $phone_number == null || 
      $dob == null)
      {
        $_SESSION["add_error"] = "Invalid contact data, check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die(); 

      }
    else

      {
        require_once('database.php');

        //Update the contact to the database
        $query = 'UPDATE contacts
            SET firstName = :firstName,
            lastname = :lastName,
            emailAddress = :emailAddress,
            phone = :phone,
            status = :status,
            dob = :dob
            WHERE contactID = :contactID';

        $statement = $db->prepare($query);
        $statement->bindvalue(':contactID', $contact_id);
        $statement->bindvalue(':firstName', $first_name);
        $statement->bindvalue(':lastName', $last_name);
        $statement->bindvalue(':emailAddress', $email_address); 
        $statement->bindvalue(':phone', $phone_number);
        $statement->bindvalue(':status', $status); 
        $statement->bindvalue(':dob', $dob);
        
        $statement->execute();
        $statement->closeCursor();

        $_SESSION["FullName"] = $first_name . " " . $last_name;
      }
    //redirect to confirmation page
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die(); // releases add_contact.php from memory


?>