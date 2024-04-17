<?php
require("../database/database.php");
include "../database/constructors/user.php";
session_start();

if (isset($_POST['userID'])){
    $userId = $_POST['userID'];
    $password = $_POST['password'];

    $statement = $db->prepare("SELECT * FROM user NATURAL JOIN student WHERE studentId = ? AND password = ? AND type = 1");
    $statement->bind_param('ss', $userId, $password);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows != 0){
        $user = $result->fetch_assoc();
        $_SESSION['userID'] = $user['userId'];

        if ($user['type'] == 1){
            $_SESSION['isStudent'] = true;
        }

        $statement->close();

        if ($_SESSION['isStudent']) {
            header("Location: ../student/index.php");
            exit();
        
        } 

        /*
        else {
            $teacherUserId = $_SESSION['userID'];
            header("Location: http://localhost:8001/homepage?teacherUserId=$teacherUserId");
            // header("Location: ../teacher/views/redirect.ejs?teacherUserId=$teacherUserId");
            exit();
        }
        */
    } else {
        $_SESSION['invalidLogin'] = true;
        header("Location: ../login/loginPage.php");
        exit();
    }
}
?>
