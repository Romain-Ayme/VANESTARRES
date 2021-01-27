<?php
session_start();
include_once 'mySQL.php';

$dbLink = connect_db();

if ( !isset($_POST['email'], $_POST['password']) ) {
    // Could not get the data that should have been sent.
    exit('Please fill both the username and password fields!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $dbLink->prepare('SELECT ID_USER, PSWD FROM users WHERE EMAIL = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();

// ici ça marche
            $query = 'SELECT * FROM users WHERE EMAIL = \'' . $_POST['email'] . '\'';
            $db_result = execute_query($dbLink, $query);
            $db_row = mysqli_fetch_assoc($db_result);
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['pseudo'] = $db_row['PSEUDO'];
            $_SESSION['email'] =  $db_row['EMAIL'];
            $_SESSION['user_id'] =  $db_row['ID_USER'];
            $_SESSION['password'] =  $db_row['PSWD'];

            header('Location: ../../index.php');
        }
        else {
            // Incorrect password
            echo 'Incorrect password!';
        }
    }
    else {
        // Incorrect username
        echo 'Incorrect username!';
    }

    $stmt->close();
}