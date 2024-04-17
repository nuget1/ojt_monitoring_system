<?php
   include "../login/requireSession.php";

   if (isset($_SESSION['passwordChanged']) && $_SESSION['passwordChanged']) {
      echo '<script>alert("Password changed successfully!");</script>';
      unset($_SESSION['passwordChanged']); 
   }

   if (isset($_SESSION['incorrectPassword']) && $_SESSION['incorrectPassword']) {
      echo '<script>alert("Incorrect current password!");</script>';
      unset($_SESSION['incorrectPassword']);
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile - OJT Portal</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../public/css/style.css">

</head>
<body>

<header class="header">
   
   <section class="flex">

      <a href="index.php" class="logo">OJT Portal</a>


      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>

   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img src="../public/images/pic-1.jpg" class="image" alt="">
      <h3 class="name"><?php echo $_SESSION['fullName'] ?></h3>
      <p class="role">Student</p>
      <a href="profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="index.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="internship-details.php"><i class="fa-solid fa-briefcase"></i><span>Internship Details</span></a>
      <a href="reports.php"><i class="fa-regular fa-clipboard"></i><span>Reports</span></a>
      <a href="logout.php"><i class="fa-solid fa-door-open"></i><span>Logout</span></a>
   </nav>

</div>

<section class="user-profile">

   <h1 class="heading">your profile</h1>

   <div class="info">

      <div class="user">
         <img src="../public/images/pic-1.jpg" alt="">
         <h3><?php echo $_SESSION['fullName'] ?></h3>
         <p>Student</p>
      </div>
   
      <?php
          require("../database/database.php");

          $userId = $_SESSION['userID'];
          $statement = $db->prepare("SELECT * FROM user NATURAL JOIN student WHERE userId = ?");
          $statement->bind_param('s', $userId);
          $statement->execute();
          $result = $statement->get_result();
          
          $data = $result->fetch_assoc();
          $firstName = $data['firstName'];
          $lastName = $data['lastName'];
          $email = $data['email'];
          $password = $data['password'];
          $course = $data['course'];
          $classCode = $data['classCode'];

          $statement->close();
          $_SESSION['currpass'] = $password;
      ?>

      <form class="profile-form" action="change-password.php" method="post">
      <label class="profile-field">
         <span>First Name:</span><input name="firstName" type="text" value=<?php echo "\"$firstName\""; ?> disabled> 
      </label>
      <label class="profile-field">
         <span>Last Name:</span><input name="lastName" type="text" value=<?php echo "\"$lastName\""; ?> disabled> 
      </label>
      <label class="profile-field">
         <span>Email:</span><input name="email" type="text" value=<?php echo "\"$email\""; ?> disabled> 
      </label>
      <label class="profile-field">
         <span>Course:</span><input name="course" type="text" value=<?php echo "\"$course\""; ?> disabled> 
      </label>
      <label class="profile-field">
         <span>Class Code:</span><input name="classCode" type="text" value=<?php echo "\"$classCode\""; ?> disabled> 
      </label>
      <label class="profile-field">
      <span>Current Password:</span>
         <input class="current-password" name="currentPassword" type="password" value="" placeholder="Enter Current Password" required maxlength="20">
         <i class="fa fa-eye-slash current-password-toggle" onclick="toggleCurrentPasswordVisibility()"></i>
      </label>
      <label class="profile-field">
      <span>New Password:</span>
         <input class="password-input" name="password" type="password" placeholder="Your password" required maxlength="20">
         <i class="fa fa-eye-slash password-toggle" onclick="togglePasswordVisibility()"></i>
      </label>
      <label class="profile-field">
         <span>Confirm Password:</span>
         <input class="confirm-password-input" name="confirmPassword" type="password" required maxlength="20" placeholder="Confirm password">
         <i class="fa fa-eye-slash confirm-password-toggle" onclick="toggleConfirmPasswordVisibility()"></i>
      </label>
         <input class="change-password-btn" type="submit" value="Change Password" name="submit">
      </form>
   </div>

</section>

<footer class="footer">

   &copy; copyright @ 2023 by <span>Team Croods</span> | all rights reserved!

</footer>

<!-- custom js file link  -->
<script src="../public/js/script.js"></script>
<script>
   /*
var password = document.querySelector(".password-input");
  , confirm_password = document.querySelector(".confirm-password-input");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
*/
var currentPassword = document.querySelector(".current-password")
var currentPasswordToggle = document.querySelector(".current-password-toggle");
var password = document.querySelector(".password-input");
var confirmPassword = document.querySelector(".confirm-password-input");
var passwordToggle = document.querySelector(".password-toggle");
var confirmPasswordToggle = document.querySelector(".confirm-password-toggle");

function toggleCurrentPasswordVisibility() {
  var currentPassword = document.querySelector(".current-password");
  var toggle = document.querySelector(".current-password-toggle");
  
  if (currentPassword.type === "password") {
    currentPassword.type = "text";
    toggle.classList.remove('fa-eye-slash');
    toggle.classList.add('fa-eye');
  } else {
    currentPassword.type = "password";
    toggle.classList.add('fa-eye-slash');
    toggle.classList.remove('fa-eye');
  }
}

function togglePasswordVisibility() {
  var password = document.querySelector(".password-input");
  var toggle = document.querySelector(".password-toggle");
  if (password.type === "password") {
    password.type = "text";
    toggle.classList.remove('fa-eye-slash');
    toggle.classList.add('fa-eye');
  } else {
    password.type = "password";
    toggle.classList.add('fa-eye-slash');
    toggle.classList.remove('fa-eye');
  }
}

function toggleConfirmPasswordVisibility() {
  var confirmPassword = document.querySelector(".confirm-password-input");
  var toggle = document.querySelector(".confirm-password-toggle");
  if (confirmPassword.type === "password") {
    confirmPassword.type = "text";
    toggle.classList.remove('fa-eye-slash');
    toggle.classList.add('fa-eye');
  } else {
    confirmPassword.type = "password";
    toggle.classList.add('fa-eye-slash');
    toggle.classList.remove('fa-eye');
  }
}

password.onchange = validatePassword;
confirmPassword.onkeyup = validatePassword;

function validatePassword(){
   if(password.value != confirmPassword.value) {
    confirmPassword.setCustomValidity("Passwords Don't Match");
  } else {
   confirmPassword.setCustomValidity('');
  }
}


</script>

   
</body>
</html>