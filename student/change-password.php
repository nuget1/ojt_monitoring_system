<?php
    require("../database/database.php");
    require("../login/requireSession.php");

    
    if ($_POST['currentPassword'] != $_SESSION['currpass']) {
      $_SESSION['incorrectPassword'] = true;
   } else {
        $statement = $db->prepare("UPDATE user set password = ? WHERE userId = ?");
        $statement->bind_param('ss', $_POST['password'], $_SESSION['userID']);
        $statement->execute();
        $statement->close();
        $_SESSION['passwordChanged'] = true;
   }
    header("Location: profile.php");
    exit();
?>