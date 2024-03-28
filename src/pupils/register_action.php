<?php
// ---------------------- Register Pupil PHP -------------------- //
// Start the session
session_start();

// * verify the token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
  $_SESSION['success'] = "Invalid CSRF Token";
  header("Location: register_pupil.php");
  die('Invalid CSRF token');
  exit();
}

//  process the form data after verifying the token

if (isset($_POST['register_btn'])) {

  // db variables to pass on connection
  $servername = "localhost";
  $username = "root";
  $password = "";

  // Create connection
  $conn = new mysqli($servername, $username, $password);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // * prevent SQL injection while getting the data from the form
  $user_code = $conn->real_escape_string($_POST['user_code']);
  $first_name = $conn->real_escape_string($_POST['first_name']);
  $last_name = $conn->real_escape_string($_POST['last_name']);
  $phone_no = $conn->real_escape_string($_POST['phone_no']);

  // ! get the data from the form, but this doesn't prevent SQL injection 
  // $user_code = $conn->$_POST['user_code'];
  // $first_name = $conn->$_POST['first_name'];
  // $last_name = $conn->$_POST['last_name'];
  // $phone_no = $conn->$_POST['phone_no'];


  // sql command to insert the rigistered pp
  $sql_data = "INSERT IGNORE INTO
       kindercare.registeredpupils (	user_code,	fname,	lname,	phone_no	)
        VALUES ('$user_code', '$first_name', '$last_name', '$phone_no');";

  //  use the kindercare db
  $conn->select_db('kindercare');

  // insert data query running the querry above
  if ($conn->query($sql_data) === TRUE) {
    // echo "New record created successfully";

    // create text file and add contents to it 
    // require_once 'add_pupil.php';

    // echo "Success, wrote ($pupil_string) to file ($filename)";
    $_SESSION['success'] = "Pupil Successfully registered";

    // redirect to the register screen
    header("Location: register_pupil.php");
  } else {
    $_SESSION['success'] = "Error: " . $sql_data . "<br>" . $conn->error;
  }


  $conn->close();
  exit();
}
