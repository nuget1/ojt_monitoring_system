<?php
   include "../login/requireSession.php";
   require "../database/database.php";

   //Fetching currently logged in student by their user
   $userId = $_SESSION['userID'];

   //Fetch company name
   $companyNameQuery = $db->prepare("SELECT c.companyName
      FROM internship AS i
      JOIN company AS c ON i.companyId = c.companyID
      JOIN student AS s ON i.studentId = s.studentId
      WHERE s.userId = ?
   ");

   $companyNameQuery->bind_param("i", $userId);
   $companyNameQuery->execute();
   $companyNameResult = $companyNameQuery->get_result();

   if ($companyNameResult->num_rows > 0) {
      $companyNameRow = $companyNameResult->fetch_assoc();
      $companyName = $companyNameRow['companyName'];
   } else {
      $companyName = "No data found";
   }

   $companyNameQuery->close();

   //Fetch company address
   $companyAddressQuery = $db->prepare("SELECT c.companyAddress
      FROM internship AS i
      JOIN company AS c ON i.companyId = c.companyID
      JOIN student AS s ON i.studentId = s.studentId
      WHERE s.userId = ?
   ");

   $companyAddressQuery->bind_param("i", $userId);
   $companyAddressQuery->execute();
   $companyAddressResult = $companyAddressQuery->get_result();

   if ($companyAddressResult->num_rows > 0) {
      $companyAddressRow = $companyAddressResult->fetch_assoc();
      $companyAddress = $companyAddressRow['companyAddress'];
   } else {
      $companyAddress = "No data found";
   }

   $companyAddressQuery->close();

   //Fetch company website
   $companyWebsiteQuery = $db->prepare("SELECT c.website
      FROM internship AS i
      JOIN company AS c ON i.companyId = c.companyID
      JOIN student AS s ON i.studentId = s.studentId
      WHERE s.userId = ?
   ");

   $companyWebsiteQuery->bind_param("i", $userId);
   $companyWebsiteQuery->execute();
   $companyWebsiteResult = $companyWebsiteQuery->get_result();

   if ($companyWebsiteResult->num_rows > 0) {
      $companyWebsiteRow = $companyWebsiteResult->fetch_assoc();
      $companyWebsite = $companyWebsiteRow['website'];
   } else {
      $companyWebsite = "No data found";
   }

   $companyWebsiteQuery->close();

   //Fetch company contact
   $companyContactQuery = $db->prepare("SELECT c.contact
      FROM internship AS i
      JOIN company AS c ON i.companyId = c.companyID
      JOIN student AS s ON i.studentId = s.studentId
      WHERE s.userId = ?
   ");

   $companyContactQuery->bind_param("i", $userId);
   $companyContactQuery->execute();
   $companyContactResult = $companyContactQuery->get_result();

   if ($companyContactResult->num_rows > 0) {
      $companyContactRow = $companyContactResult->fetch_assoc();
      $companyContact = $companyContactRow['contact'];
   } else {
      $companyContact = "No data found";
   }

   $companyContactQuery->close();

   //Fetch advisor name
   $advisorNameQuery = $db->prepare("SELECT adv.firstName, adv.lastName
   FROM internship AS i
   JOIN advisor AS adv ON i.advisorId = adv.advisorId
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ");

   $advisorNameQuery->bind_param("i", $userId);
   $advisorNameQuery->execute();
   $advisorNameResult = $advisorNameQuery->get_result();

   if ($advisorNameResult->num_rows > 0) {
      $advisorNameRow = $advisorNameResult->fetch_assoc();
      $advisorName = $advisorNameRow['firstName'] . ' ' . $advisorNameRow['lastName'];
   } else {
      $advisorName = "No advisor found";
   }

   $advisorNameQuery->close();

   //Fetch advisor email
   $advisorEmailQuery = $db->prepare("SELECT adv.email
   FROM internship AS i
   JOIN advisor AS adv ON i.advisorId = adv.advisorId
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ");

   $advisorEmailQuery->bind_param("i", $userId);
   $advisorEmailQuery->execute();
   $advisorEmailResult = $advisorEmailQuery->get_result();

   if ($advisorEmailResult->num_rows > 0) {
      $advisorEmailRow = $advisorEmailResult->fetch_assoc();
      $advisorEmail = $advisorEmailRow['email'];
   } else {
      $advisorEmail = "No email found";
   }

   $advisorEmailQuery->close();

   //Fetch advisor contact
   $advisorContactQuery = $db->prepare("SELECT adv.contact
   FROM internship AS i
   JOIN advisor AS adv ON i.advisorId = adv.advisorId
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ");

   $advisorContactQuery->bind_param("i", $userId);
   $advisorContactQuery->execute();
   $advisorContactResult = $advisorContactQuery->get_result();

   if ($advisorContactResult->num_rows > 0) {
      $advisorContactRow = $advisorContactResult->fetch_assoc();
      $advisorContact = $advisorContactRow['contact'];
   } else {
      $advisorContact = "No contact found";
   }

   $advisorContactQuery->close();

   // Fetch internship start date
   $dateStartedQuery = $db->prepare("SELECT i.dateStarted
   FROM internship AS i
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ");

   $dateStartedQuery->bind_param("i", $userId);
   $dateStartedQuery->execute();
   $dateStartedResult = $dateStartedQuery->get_result();

   if ($dateStartedResult->num_rows > 0) {
   $dateStartedRow = $dateStartedResult->fetch_assoc();
   $dateStarted = $dateStartedRow['dateStarted'];
   } else {
   $dateStarted = "No start date found";
   }

   $dateStartedQuery->close();

   // Fetch internship end date
   $dateEndedQuery = $db->prepare("SELECT i.dateEnded
   FROM internship AS i
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ");

   $dateEndedQuery->bind_param("i", $userId);
   $dateEndedQuery->execute();
   $dateEndedResult = $dateEndedQuery->get_result();

   if ($dateEndedResult->num_rows > 0) {
   $dateEndedRow = $dateEndedResult->fetch_assoc();
   $dateEnded = $dateEndedRow['dateEnded'];
   } else {
   $dateEnded = "No end date found";
   }

   $dateEndedQuery->close();

   if ($dateStarted != "No start date found") {
      $dateStarted = new DateTime($dateStarted);
   }
   if ($dateEnded != "No end date found") {
      $dateEnded = new DateTime($dateEnded);
   }

   if ($dateStarted != "No start date found" and $dateEnded != "No end date found") {
      $interval = $dateStarted->diff($dateEnded);

      $months = $interval->m + 12 * $interval->y;
      $weeks = floor($interval->d / 7);
   } else {
      $interval = "?";
      $months = "?";
      $weeks = "?";
   }

   // Fetch teacher's name
   $teacherNameQuery = $db->prepare("
    SELECT t.firstName, t.lastName
    FROM internship AS i
    JOIN student AS s ON i.studentId = s.studentId
    JOIN teacher AS t ON i.teacherId = t.teacherId
    WHERE s.userId = ?
   ");

   
   $teacherNameQuery->bind_param("i", $userId);
   $teacherNameQuery->execute();
   $teacherNameResult = $teacherNameQuery->get_result();

   if ($teacherNameResult->num_rows > 0) {
      $teacherNameRow = $teacherNameResult->fetch_assoc();
      $teacherName = $teacherNameRow['firstName'] . ' ' . $teacherNameRow['lastName'];
   } else {
      $teacherName = "No teacher found";
   }

   $teacherNameQuery->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Internship Details - OJT Portal</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../public/css/style.css">
   <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin="">
   </script>
</head>
<body>

<header class="header">
   
   <section class="flex">

      <a href="index.php" class="logo">OJT Portal</a>


      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div> 
         <div id="search-btn" class="fas fa-search"></div>
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

<section class="home-grid">
   <h1 class="heading">Internship Details</h1>
   <div class="box-container">
      <div class="rectangle">
         <h3 class="title">Company Details</h3>
         <hr class="hrule">
         <p class="requirements">Name: <span><?php echo $companyName ?></span></p>
         <p class="requirements">Address: <span><?php echo $companyAddress ?></span></p>
         <p class="requirements">Website: <span><?php echo $companyWebsite ?></span></p>
         <p class="requirements">Contact: <span><?php echo $companyContact ?></span></p>
         <br>
         <div id="map">
            
         </div>
      </div>

      <div class="rectangle">
         <h3 class="title">Advisor Details</h3>
         <hr class="hrule">
         <p class="requirements">Name: <span><?php echo $advisorName ?></span></p>
         <p class="requirements">Email: <span><?php echo $advisorEmail ?></span></p>
         <p class="requirements">Contact: <span><?php echo $advisorContact ?></span></p>
         <br>
         <br>
         <h3 class="title">Teacher Details</h3>
         <hr class="hrule">
         <p class="requirements">Name: <span><?php echo $teacherName ?></span></p>
         <br>
         <br>
         <h3 class="title">Internship</h3>
         <hr class="hrule">
         <p class="requirements">Start Date: <span>
            <?php 
            if ($dateStarted != "No start date found") {
            echo $dateStarted->format('m-d-Y');
            } else {
               echo $dateStarted;
            }
            ?></span></p>
         <p class="requirements">Expected End Date: <span>
            <?php 
            if ($dateEnded != "No end date found") {
            echo $dateEnded->format('m-d-Y'); 
            } else {
               echo $dateEnded;
            } ?></span></p>
         <p class="requirements">Duration: <span><?php echo $months . " months and " . $weeks . " weeks"; ?></span></p>
      </div>

</section>


<footer class="footer">

   &copy; copyright @ 2023 by <span>Team Croods</span> | all rights reserved!

</footer>

<script>
   var address = "<?php echo $companyAddress; ?>";
   var map = L.map('map').setView([12.8797, 121.7740], 6); // Default view of the Philippines

   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
   }).addTo(map);

   function attemptGeocoding(address, attemptCount = 0) {
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
      .then(response => response.json())
      .then(data => {
         console.log(data);
         if (data.length > 0) {
            map.setView([data[0].lat, data[0].lon], 16);
         } else if (attemptCount < 5) {
            console.log('Address not found, retrying with shorter address...');
            setTimeout(() => {
               var shorterAddress = address.substring(address.indexOf(' ') + 1);
               attemptGeocoding(shorterAddress, attemptCount + 1);
            }, 1000);
         } else {
            console.log('Address not found after several attempts.');
            map.setView([12.8797, 121.7740], 6);
         }
      })
      .catch(error => console.error('Error in fetching the coordinates:', error));
   }

   attemptGeocoding(address);
</script>


<script src="../public/js/script.js"></script>


   
</body>
</html>