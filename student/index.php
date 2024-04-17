<?php
   require("../database/database.php");
   include "../login/requireSession.php";

   //Fetching currently logged in student by their user
   $userId = $_SESSION['userID'];
   //Fetching student name
   $fullNameQuery = $db->prepare("SELECT firstName, lastName FROM student WHERE userId = ?");
   $fullNameQuery->bind_param("i", $userId);
   $fullNameQuery->execute();
   $result = $fullNameQuery->get_result();
   if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $fullName = $row['firstName'] . ' ' . $row['lastName'];
   } else {
      $fullName = "No data found";
   }
   $_SESSION['fullName'] = $fullName;

   $fullNameQuery->close();

   //Fetching hours worked for the week
   $currentWeekNum = 1;

   $userId = $_SESSION['userID'];
    $hoursQuery = $db->prepare("SELECT hoursWorked FROM reports WHERE studentId = 
    (SELECT studentId FROM student WHERE userId = ?) AND weekNum = ?");
    $hoursQuery->bind_param("ii", $userId, $currentWeekNum);
    $hoursQuery->execute();
    $hoursResult = $hoursQuery->get_result();

    if ($hoursResult->num_rows > 0) {
        $hoursRow = $hoursResult->fetch_assoc();
        $hoursWorked = $hoursRow['hoursWorked'];
    } else {
        $hoursWorked = "No data found";
    }

   $hoursQuery->close();

   //Fetching announcements made by the advisors
   $announcementsQuery = $db->prepare("SELECT a.title, a.message, a.datePosted
   FROM announcements AS a
   JOIN teacher AS tea ON a.teacherId = tea.teacherId
   JOIN internship AS i ON tea.teacherId = i.teacherId
   JOIN student AS s ON i.studentId = s.studentId
   WHERE s.userId = ?
   ORDER BY a.datePosted DESC
   ");

   $announcementsQuery->bind_param("i", $userId);
   $announcementsQuery->execute();
   $announcementsResult = $announcementsQuery->get_result();

   //Fetching job resume status
   $jobResumeStatusQuery = $db->prepare("SELECT s.status
      FROM requirements AS r
      JOIN status AS s ON r.jobResume = s.code
      WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $jobResumeStatusQuery->bind_param("i", $userId);
   $jobResumeStatusQuery->execute();
   $jobResumeStatusResult = $jobResumeStatusQuery->get_result();

   if ($jobResumeStatusResult->num_rows > 0) {
      $jobResumeStatusRow = $jobResumeStatusResult->fetch_assoc();
      $jobResumeStatus = $jobResumeStatusRow['status'];
   } else {
      $jobResumeStatus = "No data found";
   }

   $jobResumeStatusQuery->close();

   //Fetching curriculum vitae status
   $curriVitaeStatusQuery = $db->prepare("SELECT s.status
      FROM requirements AS r
      JOIN status AS s ON r.curriVitae = s.code
      WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $curriVitaeStatusQuery->bind_param("i", $userId);
   $curriVitaeStatusQuery->execute();
   $curriVitaeStatusResult = $curriVitaeStatusQuery->get_result();

   if ($curriVitaeStatusResult->num_rows > 0) {
      $curriVitaeStatusRow = $curriVitaeStatusResult->fetch_assoc();
      $curriVitaeStatus = $curriVitaeStatusRow['status'];
   } else {
      $curriVitaeStatus = "No data found";
   }

   $curriVitaeStatusQuery->close();

   //Fetching cover letter status
   $coverLetterStatusQuery = $db->prepare("SELECT s.status
    FROM requirements AS r
    JOIN status AS s ON r.coverLetter = s.code
    WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $coverLetterStatusQuery->bind_param("i", $userId);
   $coverLetterStatusQuery->execute();
   $coverLetterStatusResult = $coverLetterStatusQuery->get_result();

   if ($coverLetterStatusResult->num_rows > 0) {
      $coverLetterStatusRow = $coverLetterStatusResult->fetch_assoc();
      $coverLetterStatus = $coverLetterStatusRow['status'];
   } else {
      $coverLetterStatus = "No data found";
   }

   $coverLetterStatusQuery->close();

   //Fetching MOA status
   $moaStatusQuery = $db->prepare("SELECT s.status
   FROM requirements AS r
   JOIN status AS s ON r.moa = s.code
   WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $moaStatusQuery->bind_param("i", $userId);
   $moaStatusQuery->execute();
   $moaStatusResult = $moaStatusQuery->get_result();

   if ($moaStatusResult->num_rows > 0) {
      $moaStatusRow = $moaStatusResult->fetch_assoc();
      $moaStatus = $moaStatusRow['status'];
   } else {
      $moaStatus = "No data found";
   }

   //Fetching Medical Certificate status
   $medCertStatusQuery = $db->prepare("SELECT s.status
    FROM requirements AS r
    JOIN status AS s ON r.medCert = s.code
    WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $medCertStatusQuery->bind_param("i", $userId);
   $medCertStatusQuery->execute();
   $medCertStatusResult = $medCertStatusQuery->get_result();

   if ($medCertStatusResult->num_rows > 0) {
      $medCertStatusRow = $medCertStatusResult->fetch_assoc();
      $medCertStatus = $medCertStatusRow['status'];
   } else {
      $medCertStatus = "No data found";
   }

   $medCertStatusQuery->close();

   //Fetching Waiver status
   $waiverStatusQuery = $db->prepare("SELECT s.status
    FROM requirements AS r
    JOIN status AS s ON r.waiver = s.code
    WHERE r.studentId = (SELECT studentId FROM student WHERE userId = ?)");

   $waiverStatusQuery->bind_param("i", $userId);
   $waiverStatusQuery->execute();
   $waiverStatusResult = $waiverStatusQuery->get_result();

   if ($waiverStatusResult->num_rows > 0) {
      $waiverStatusRow = $waiverStatusResult->fetch_assoc();
      $waiverStatus = $waiverStatusRow['status'];
   } else {
      $waiverStatus = "No data found";
   }

   $waiverStatusQuery->close();

   $allApprovedOrReviewing = true;

    // Check the status of each requirement
   $requirements = [
        'jobResume' => $jobResumeStatus,
        'curriVitae' => $curriVitaeStatus,
        'coverLetter' => $coverLetterStatus,
        'moa' => $moaStatus,
        'medCert' => $medCertStatus,
        'waiver' => $waiverStatus
    ];

    foreach ($requirements as $status) {
        if ($status !== 'APPROVED' && $status !== 'REVIEWING') {
            $allApprovedOrReviewing = false;
            break;
        }
    }


   $internshipQuery = $db->prepare("SELECT dateStarted FROM internship WHERE studentId = (SELECT studentId FROM student WHERE userId = ?)");
   $internshipQuery->bind_param("i", $userId);
   $internshipQuery->execute();
   $internshipResult = $internshipQuery->get_result();

   $currentWeekNum = 0;
   $reportDue = false; // Default value

   if ($internshipResult->num_rows > 0) {
       $internshipRow = $internshipResult->fetch_assoc();

       // Calculate the current week number
      $currentDate = new DateTime();
      $dateStarted = new DateTime($internshipRow['dateStarted']);
      $interval = $dateStarted->diff($currentDate);
      $totalWeeksSinceStart = floor($interval->days / 7) + 1;

      $dueReports = [];

      for ($week = 1; $week <= $totalWeeksSinceStart; $week++) {
         $reportCheckQuery = $db->prepare("SELECT reportId FROM reports WHERE studentId = (SELECT studentId FROM student WHERE userId = ?) AND weekNum = ?");
         $reportCheckQuery->bind_param("ii", $userId, $week);
         $reportCheckQuery->execute();
         $reportCheckResult = $reportCheckQuery->get_result();

         if ($reportCheckResult->num_rows === 0) {
               // No report submitted for this week
               $dueReports[] = $week;
         }

         $reportCheckQuery->close();
      }
   }

   $internshipQuery->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - OJT Portal</title>

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
      <h3 class="name"><?php echo $fullName ?></h3>
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

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">
      <div class="rectangle">
         <h3 class="title">Announcements</h3>
         <hr class="hrule">
         <?php
         if ($announcementsResult->num_rows > 0) {
            echo "<div class='box'><ul>";
            while($row = $announcementsResult->fetch_assoc()) {
               echo "<li><strong class='requirements'>" . htmlspecialchars($row['title']) . "</strong> <p>(Posted on: " . htmlspecialchars($row['datePosted']) . ")</p>" . " <p id='message'>" . htmlspecialchars($row['message']) . ")</p></li>";
            }
            echo "</ul></div>";
         } else {
            echo "<div class='box'><p class='requirements'>No announcements found.</p></div>";
         }
         $announcementsQuery->close();
         ?>
      </div>

      <div class="box">
      <?php if (!$allApprovedOrReviewing): ?>
         <h3 class="title">Requirements List</h3>
         <hr class="hrule">
         <?php if ($requirements['jobResume'] !== 'APPROVED' && $requirements['jobResume'] !== 'REVIEWING'): ?>
            <p class="requirements">Job Resume: <span class="reqspan"> <?php echo $requirements['jobResume'] ?></span></p>
         <?php endif; ?>
         <?php if ($requirements['curriVitae'] !== 'APPROVED' && $requirements['curriVitae'] !== 'REVIEWING'): ?>
            <p class="requirements">Curriculum Vitae: <span class="reqspan"> <?php echo $requirements['curriVitae'] ?> </span></p>
         <?php endif; ?>
         <?php if ($requirements['coverLetter'] !== 'APPROVED' && $requirements['coverLetter'] !== 'REVIEWING'): ?>
            <p class="requirements">Cover Letter: <span class="reqspan"> <?php echo $requirements['coverLetter'] ?> </span></p>
         <?php endif; ?>
         <?php if ($requirements['moa'] !== 'APPROVED' && $requirements['moa'] !== 'REVIEWING'): ?>
            <p class="requirements">MOA: <span class="reqspan"> <?php echo $requirements['moa'] ?> </span></p>
         <?php endif; ?>
         <?php if ($requirements['medCert'] !== 'APPROVED' && $requirements['medCert'] !== 'REVIEWING'): ?>
            <p class="requirements">Medical Certificate: <span class="reqspan"> <?php echo $requirements['medCert'] ?> </span></p>
         <?php endif; ?>
         <?php if ($requirements['waiver'] !== 'APPROVED' && $requirements['waiver'] !== 'REVIEWING'): ?>
            <p class="requirements">Waiver: <span class="reqspan"> <?php echo $requirements['waiver'] ?> </span></p>
         <?php endif; ?>
      <?php endif; ?>
         <?php if (!empty($dueReports)): ?>
         <br>
         <br>
         <h3 class="title">Report</h3>
         <hr class="hrule">
            <?php foreach ($dueReports as $dueWeek): ?>
               <p class="requirements"><span>Report for Week <?php echo $dueWeek; ?> is due</span></p>
            <?php endforeach; ?>
         <?php endif; ?>

         <br>
         <br>
         <h3 class="title">Hours Logged This Week</h3>
         <hr class="hrule">
         <div class="flex">
         <a href="#"><i class="fa-solid fa-hourglass-end"></i><span><?php echo $hoursWorked; ?></span></a>
         </div>         
      </div>
   </div>
</section>


<footer class="footer">

   &copy; Copyright @ 2023 by <span>The Croods</span> | All rights reserved!

</footer>

<!-- custom js file link  -->
<script src="../public/js/script.js"></script>
</body>
</html>