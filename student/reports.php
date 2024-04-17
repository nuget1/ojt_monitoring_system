<?php
require "../database/database.php";
include "../login/requireSession.php";

//remove if login is modified
$idQuery = "SELECT studentId FROM student WHERE userId = '{$_SESSION['userID']}'";
$result = mysqli_query($db, $idQuery);
if ($row = mysqli_fetch_assoc($result)) {
   $_SESSION['studentId'] = $row['studentId'];
}
mysqli_free_result($result);


$maxWeekQuery = "SELECT MAX(weekNum) AS maxWeek FROM reports WHERE studentId = '{$_SESSION['studentId']}'";
$result = mysqli_query($db, $maxWeekQuery);
$maxWeek = 1;
if ($row = mysqli_fetch_assoc($result)) {
   $maxWeek = $row['maxWeek'];
}
$_SESSION['weekNum'] = $maxWeek + 1;

mysqli_free_result($result);

$currentWeekStart = date('Y-m-d H:i:s', strtotime('last monday', strtotime('tomorrow')));

$checkReportQuery = "SELECT COUNT(*) AS reportCount FROM reports WHERE studentId = '{$_SESSION['studentId']}' AND weekNum = '$maxWeek' AND submittedAt >= '$currentWeekStart'";
$checkResult = mysqli_query($db, $checkReportQuery);

if ($checkResult === false) {
   echo "Error: " . mysqli_error($db);
} else {
   $checkRow = mysqli_fetch_assoc($checkResult);

   if ($checkRow !== null) {
      $reportCount = $checkRow['reportCount'];
   } else {
      $reportCount = 0;
   }

   $_SESSION['reportSubmitted'] = $reportCount > 0;
   mysqli_free_result($checkResult);
}


// Submit button clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $weekNumber = $_POST['weekNum'];
   $hoursWorked = $_POST['hoursWorked'];

   if ($hoursWorked < 0) {
      echo "<script>alert('Error: Hours Worked cannot be negative.');</script>";
      $_SESSION['negativeHrs'] = true;
      exit;
   }

   $uploadFolder = '../public/uploads';
   $allowedFileTypes = ['pdf', 'doc', 'docx'];

   $uploadedFileName = $_FILES['uploadFiles']['name'];
   $uploadedFileType = pathinfo($uploadedFileName, PATHINFO_EXTENSION);

   if (!in_array($uploadedFileType, $allowedFileTypes)) {
      echo "<script>alert('Error: Only PDF, DOC, and DOCX files are allowed.');</script>";
   } else {
      $reportFile = $uploadFolder . '/' . uniqid() . '.' . $uploadedFileType;

      if (move_uploaded_file($_FILES['uploadFiles']['tmp_name'], $reportFile)) {
         $insertQuery = "INSERT INTO reports (studentId, weekNum, hoursWorked, reportFile, status) 
                            VALUES ('{$_SESSION['studentId']}', '$weekNumber', '$hoursWorked', '$reportFile', 1)";

         if (mysqli_query($db, $insertQuery)) {
            echo "<script>alert('Report submitted successfully.');</script>";
         } else {
            echo "<script>alert('Error: " . $insertQuery . " " . mysqli_error($db) . "');</script>";
         }
      } else {
         echo "<script>alert('Error: Failed to move the uploaded file.');</script>";
      }

      unset($_SESSION['negativeHrs']);
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reports - OJT Portal</title>

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
         <a href="internship-details.php"><i class="fa-solid fa-briefcase"></i><span>Intership Details</span></a>
         <a href="reports.php"><i class="fa-regular fa-clipboard"></i><span>Reports</span></a>
         <a href="logout.php"><i class="fa-solid fa-door-open"></i><span>Logout</span></a>
      </nav>

   </div>

   <section class="reports">
      <h1 class="heading">Reports</h1>
      <div class="box-container">
         <div class="rectangle">
            <script>
               window.addEventListener('DOMContentLoaded', (event) => {
                  var formSubmitted = <?php echo json_encode($_SESSION['reportSubmitted']); ?>;
                  if (formSubmitted) {
                     disableForm();
                  }
               });

               function disableForm() {
                  var form = document.querySelector('form');
                  var elements = form.elements;

                  for (var i = 1; i < elements.length; i++) {
                     elements[i].disabled = true;
                  }

                  var messageContainer = document.getElementById('message-container');
                  messageContainer.innerHTML = '<h3>Please enter an appropriate week based on your reports.</h3>';
               }

               function enableForm() {
                  var form = document.querySelector('form');
                  var elements = form.elements;

                  for (var i = 0; i < elements.length; i++) {
                     elements[i].disabled = false;
                  }
                  var messageContainer = document.getElementById('message-container');
                  messageContainer.innerHTML = '';
               }

            </script>

            <form action="" method="post" enctype="multipart/form-data" onsubmit="setMaxWeek(); return checkFormSubmission();">

            <!--
               <label for="weekNumber">Week Number: <?php echo $_SESSION['weekNum']; ?> </label> 
            -->
               <label for="weekNumber">Week Number: 
                  <input type="number" id="weekNumber" name="weekNum" min="1" required onchange="checkWeekNumber()">
               </label>

               <label for="hoursWorked">Hours Worked:</label>
               <input type="number" id="hoursWorked" name="hoursWorked" min="0" required>

               <label for="uploadFiles">Upload Files:</label>
               <input type="file" id="uploadFiles" name="uploadFiles" required>

               <button type="submit">Submit Report</button>
            </form>
            <div id="message-container"></div>
         </div>
      </div>
      <script>
               const weekDataRows = document.getElementsByClassName("weekData");
               document.querySelector("input#weekNumber").setAttribute("max", weekDataRows.length + 1);

               function checkWeekNumber() {
                  console.log("CheckWeekNumber called");
                  const weekDataRows = document.getElementsByClassName("weekData");
                  document.querySelector("input#weekNumber").setAttribute("max", weekDataRows.length + 1);
                  const weekInput = document.querySelector("input#weekNumber").value;
                  console.log("Week Input: " + weekInput);
                  console.log("Num of current weeks: " + weekDataRows.length);
               
                  if (weekDataRows.length > 0 && weekInput > weekDataRows.length + 1) {
                     disableForm();
                  } else {
                     for (const week of weekDataRows) {
                        if (week.textContent == weekInput) {
                           disableForm();
                           console.log("Disabled form.");
                        } else {
                           enableForm();
                           console.log("Enabled form.");
                        }
                     }
                  }
               }
               </script>

      <div class="box-container">
         <div class="rectangle">
            <table>
               <caption>Weekly Reports History</caption>
               <tr>
                  <th>Week</th>
                  <th>Hours Worked</th>
                  <th>Submitted</th>
                  <th>File</th>
                  <th>Teacher Comment</th>
                  <th>Demerit</th>
                  <th>Status</th>
               </tr>

               <?php
               $fetchReportsQuery = "SELECT r.weekNum, r.hoursWorked, r.submittedAt, r.reportFile, r.comment, r.demerit, s.status FROM reports r JOIN status s ON r.status = s.code WHERE r.studentId = '{$_SESSION['studentId']}'";

               $fetchReportsResult = mysqli_query($db, $fetchReportsQuery);

               if (!$fetchReportsResult) {
                  die('Error in query: ' . mysqli_error($db));
               }

               if (mysqli_num_rows($fetchReportsResult) > 0) {
                  while ($reportRow = mysqli_fetch_assoc($fetchReportsResult)) {
                     echo "<tr>";
                     echo "<td class='weekData' id='{$reportRow['weekNum']}' >{$reportRow['weekNum']}</td>";
                     echo "<td>{$reportRow['hoursWorked']}</td>";
                     echo "<td>{$reportRow['submittedAt']}</td>";
                     echo "<td><a href='{$reportRow['reportFile']}' target='_blank'>Show File</a></td>";
                     echo "<td>{$reportRow['comment']}</td>";
                     $demerit = $reportRow['demerit'] !== null ? $reportRow['hoursWorked'] - $reportRow['demerit'] : "N/A";
                     echo "<td>{$demerit}</td>";
                     echo "<td>{$reportRow['status']}</td>";
                     echo "</tr>";
                  }
               } else {
                  echo "<tr><td colspan='7'>No reports found.</td></tr>";
               }

               mysqli_free_result($fetchReportsResult);
               ?>
            </table>
         </div>
      </div>

   </section>


   <?php
   $totalReportsQuery = "SELECT COUNT(*) as total FROM reports WHERE studentId = '{$_SESSION['studentId']}'";
   $totalReportsResult = mysqli_query($db, $totalReportsQuery);
   $totalReports = mysqli_fetch_assoc($totalReportsResult)['total'];

   mysqli_free_result($totalReportsResult);
   ?>
   </div>
   </section>

   <footer class="footer">
      &copy; copyright @ 2023 by <span>Team Croods</span> | all rights reserved!
   </footer>

   <!-- custom js file link  -->
   <script src="../public/js/script.js"></script>
</body>

</html>