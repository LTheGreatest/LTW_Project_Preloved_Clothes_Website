<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../classes/session.class.php');
    require_once(__DIR__ . '/../database/database_connection.db.php');
    require_once(__DIR__ . '/../classes/user.class.php');
    
    $session = new Session();

    if ($_SESSION['csrf'] !== $_POST['csrf']) {
        $session->addMessage('error', 'Suspicious activity found');
        header('Location: /../pages/login.php');
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'], $_POST['password'])) {
        //only continues if  everything is set
        $email = filter_var(strtolower($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];
        if (!$email) {
            $session->addMessage('error', 'Invalid email format');
            header('Location: /../pages/login.php');
            exit();
        }
    }
    else {
        $session->addMessage('error', 'Invalid request');
        header('Location: /../pages/login.php');
        exit();
    }
        
    $db = getDatabaseConnection();
    $user = User::getUserByEmail($db, $email);
  
    if($user && password_verify($password, $user->getPassword())){
        //if everything is correct, initializes the session array

        $session->setId($user->getID());
        $session->setUsername($user->getUsername());
        $session->addMessage('Success', 'Login successful');

        header('Location: /../pages/main_page.php' );
        exit();
    } else{
        $session->addMessage('error', 'Wrong email or password!');
        header('Location: /../pages/login.php');
        exit();
    }
?>