const express = require('express');
const session = require('express-session');
const bodyParser = require('body-parser');
const router = express.Router();
const mysql = require('mysql');

var db = mysql.createConnection({
    host: 'localhost', user: 'root', password: '', database: 'ojt_monitoring'
});

router.use(express.static('${__dirname}../public'));
router.use(bodyParser.urlencoded({ extended: true }));
router.use(bodyParser.json());
router.use(session({
    secret: 'frogrammers',
    resave: false,
    saveUninitialized: true
}));

const noCacheMiddleware = (req, res, next) => {
    res.header('Cache-Control', 'private, no-cache, no-store, must-revalidate');
    res.header('Expires', '-1');
    res.header('Pragma', 'no-cache');
    next();
};

router.use(noCacheMiddleware);

const requireLogin = (req, res, next) => {
    if (!req.session.userId) {
        return res.send('<script>window.location.href="/login/loginPage.php";</script>');
    }
    next();
};
router.post('/login', (req, res) => {
    console.log("connect to /login");
    const userId = req.body.userID;
    const password = req.body.password;
    req.session.pass = password;

    const statement = "SELECT firstName, lastName, userId FROM user natural join teacher WHERE teacherId = ? AND password = ?";

    db.query(statement, [userId, password], (error, result) => {
        if (error) {
            console.error('Error executing query:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }

        if (result.length > 0) {
            req.session.userID = result[0].userId;
            res.redirect("/homepage");
        } else {
            res.redirect('/login-fail');
        }
    });
});

router.get('/homepage', (req, res) => {
    console.log("connect to /homepage");

    if (req.session.userID) {
        const userId = req.session.userID;
        console.log(userId);
        const getTeacherNameQuery = "SELECT firstName, lastName, teacherId FROM user JOIN teacher ON user.userId = teacher.userId WHERE user.userId = ?";

        const getStudentsQuery = "SELECT student.studentId, CONCAT(student.firstName, ' ', student.lastName) AS fullName, user.email FROM student JOIN user ON student.userId = user.userId JOIN internship ON student.studentId = internship.studentId WHERE internship.teacherId = ?";

        // Fetch announcements query
        const fetchAnnouncementsQuery = "SELECT * FROM announcements WHERE teacherId = ?";

        db.query(getTeacherNameQuery, [userId], (error, teacherResult) => {
            if (error) {
                console.error('Error executing query to get teacher name:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            }
            console.log(teacherResult);

            if (teacherResult.length > 0) {
                const teacherRow = teacherResult[0];
                const fullName = teacherRow.firstName + ' ' + teacherRow.lastName;
                req.session.fullName = fullName;

                console.log(fullName);
                const teacherId = teacherRow.teacherId;
                console.log(teacherId);

                db.query(getStudentsQuery, [teacherId], (studentError, studentResult) => {
                    if (studentError) {
                        console.error('Error executing query to get students:', studentError);
                        res.status(500).json({ error: 'Internal Server Error' });
                        return;
                    }
                    console.log(studentResult);

                    // Fetching announcements
                    db.query(fetchAnnouncementsQuery, [teacherId], (announcementError, announcements) => {
                        if (announcementError) {
                            console.error('Error fetching announcements:', announcementError);
                            res.status(500).json({ error: 'Internal Server Error' });
                            return;
                        }

                        announcements.forEach(announcement => {
                            if (announcement.datePosted) {
                                announcement.datePosted = new Date(announcement.datePosted).toDateString() + ' ' + new Date(announcement.datePosted).toLocaleTimeString();
                            }
                        });
                        console.log(announcements);

                        // Render homepage with all necessary data
                        res.render('homepage', {
                            fullName: fullName,
                            teacherUserId: teacherId,
                            students: studentResult,
                            announcements: announcements
                        });
                    });
                });
            }
        });
    } else {
        res.redirect("logout");
    }
});

router.post('/edit-announcement', (req, res) => {
    console.log(req.body);
    const { announcementId, title, message } = req.body;
    const updateQuery = "UPDATE announcements SET title = ?, message = ? WHERE announcementId = ?";
    db.query(updateQuery, [title, message, announcementId], (error, result) => {
        if (error) {
            console.error('Error updating announcement:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }
        res.json({ success: true });
    });
});

router.post('/delete-announcement', (req, res) => {
    
    console.log(req.body);
    const { announcementId } = req.body;
    const deleteQuery = "DELETE FROM announcements WHERE announcementId = ?";
    db.query(deleteQuery, [announcementId], (error, result) => {
        if (error) {
            console.error('Error deleting announcement:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }
        res.json({ success: true });
    });
});

router.get('/student-requirements', (req, res) => {
    const studentId = req.query.studentId;

    const query = `
        SELECT jobResume, curriVitae, coverLetter, moa, medCert, waiver
        FROM requirements
        WHERE studentId = ?`;

    db.query(query, [studentId], (error, results) => {
        if (error) {
            console.error('Error fetching requirements:', error);
            res.status(500).json({ error: 'Internal Server Error' });
        } else {
            res.json(results[0]);
        }
    });
});

router.post('/update-requirement-status', (req, res) => {
    const { studentId, requirement, status } = req.body;
    console.log({ studentId, requirement, status });

    if (!studentId || !requirement || status === undefined) {
        return res.status(400).json({ success: false, message: 'Invalid request parameters.' });
    }

    const query = `UPDATE requirements SET ${mysql.escapeId(requirement)} = ? WHERE studentId = ?`;

    db.query(query, [status, studentId], (error, result) => {
        if (error) {
            console.error('Error updating requirement:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }
        res.json({ success: true });
    });
});


router.get('/student-reports', (req, res) => {
    const studentId = req.query.studentId;

    const query = `
        SELECT *
        FROM reports
        WHERE studentId = ? AND status = 1`;

    db.query(query, [studentId], (error, results) => {
        if (error) {
            console.error('Error fetching reports:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }
        res.json(results);
    });
});

router.post('/approve-report', (req, res) => {
    const reportId = req.body.reportId;
    const comment= req.body.comment;
    const demerit = req.body.demerit;

    if (!reportId) {
        return res.status(400).json({ error: 'Report ID is required' });
    }

    const updateQuery = "UPDATE reports SET status = 2, comment = ?, demerit = ? WHERE reportId = ?";

    db.query(updateQuery, [comment, demerit, reportId], (error, result) => {
        if (error) {
            console.error('Error updating report:', error);
            return res.status(500).json({ error: 'Internal Server Error' });
        }

        res.json({ success: true });
    });
});

router.get('/fetch-all-reports', (req, res) => {
    const studentId = req.query.studentId;

    if (!studentId) {
        return res.status(400).json({ error: 'Student ID is required' });
    }

    const query = "SELECT * FROM reports WHERE studentId = ?";

    db.query(query, [studentId], (error, results) => {
        if (error) {
            console.error('Error fetching reports:', error);
            return res.status(500).json({ error: 'Internal Server Error' });
        }
        res.json(results);
    });
});

router.get('/fetch-hours-worked', (req, res) => {
    const studentId = req.query.studentId;

    if (!studentId) {
        return res.status(400).json({ error: 'Student ID is required' });
    }

    const query = "select sum(hoursWorked) as totalHours, sum(demerit) as totalDemerit from reports natural join student where studentId = ?";

    db.query(query, [studentId], (error, results) => {
        if (error) {
            console.error('Error fetching hours worked:', error);
            return res.status(500).json({ error: 'Internal Server Error' });
        } else {
            const resultRow = results[0];
            const totalHours = resultRow.totalHours - resultRow.totalDemerit;
            res.json(totalHours);
        }
    });

});


router.get('/logout', (req, res) => {
    console.log("connect to /logout");
    req.session.destroy();
    res.redirect("http://teamcroods-ojt.com/ojt-web-project/login/loginPage.php");
});

router.get('/login-fail', (req, res) => {
    console.log("connect to /logout");
    req.session.destroy();
    res.redirect("http://teamcroods-ojt.com/ojt-web-project/login/loginPage.php?status=fail");
});

router.post('/change-password', (req, res) => {
    console.log("connect to /change-password");
    console.log(req.body.currentPassword);
    console.log(req.body.password);
    console.log(req.body.confirmPassword);

    let response; // Variable to store the response message

    if (req.body.password === req.body.currentPassword) {
        console.log("1");
        response = 'New password must be different from the current password';
    } else if (req.session.pass != req.body.currentPassword) {
        console.log(req.session.pass);
        console.log("2");
        response = 'Incorrect current password';
    } else {
        const statement = "UPDATE user SET password = ? WHERE userId = ?";
    
        db.query(statement, [req.body.password, req.session.userID], (error, result) => {
            if (error) {
                console.error('Error executing query:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            } else {
                console.log("Changed password");
                response = 'Password changed successfully!';
                res.status(200).json({ message: response });
            }
        });
        return; // Return to prevent further execution after the database query
    }

    // Respond with the appropriate message
    res.json({ message: response });
});



router.get('/profile', (req, res) => {
    console.log("connect to /profile");
    if (req.session.userID) {
        const statement = "SELECT * FROM user NATURAL JOIN teacher WHERE userId = ?";
        db.query(statement, [req.session.userID], (error, result) => {
            if (error) {
                console.error('Error executing query:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            } else if (result.length > 0) {
                const row = result[0];
                res.render("profile", {
                    firstName: row.firstName,
                    lastName: row.lastName,
                    password: row.password,
                    email: row.email,
                    teacherId: row.teacherId
                });
            }
        });
    } else {
        res.redirect('logout');
    }
});

router.get('/company-details', (req, res) => {
    console.log("connect to /company-details");
    if (req.session.userID) {
        const userQuery = "SELECT * FROM teacher WHERE userId = ?";
        const companyDetailsQuery = "SELECT * FROM company";
        db.query(userQuery, [req.session.userID], (error, teacherResult) => {
            if (error) {
                console.error('Error executing query:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            } else {
                console.log("User Result:", teacherResult);
                console.log("User ID from session:", req.session.userID);
                console.log("First Name:", teacherResult[0].firstName);
                console.log("Last Name:", teacherResult[0].lastName);
                db.query(companyDetailsQuery, (error, companies) => {
                    if (error) {
                        console.error('Error executing query:', error);
                        res.status(500).json({ error: 'Internal Server Error'   });
                        return;
                    } else {
                        res.render("company-details", {
                            firstName: teacherResult[0].firstName,
                            lastName: teacherResult[0].lastName,
                            companies: companies
                        });
                    }
                });
            }
        });
    } else {
        res.redirect('logout');
    }
});


router.post('/submit-announcement', (req, res) => {
    const { teacherId, title, message } = req.body;

    const insertAnnouncementQuery = 'INSERT INTO announcements (teacherId, title, message) VALUES (?, ?, ?)';

    db.query(insertAnnouncementQuery, [teacherId, title, message], (error, result) => {
        if (error) {
            console.error('Error executing query to insert announcement:', error);
            res.status(500).json({ error: 'Internal Server Error' });
            return;
        }

        console.log('Announcement inserted successfully.');
        res.json({ success: true });
    });
});

router.get('/manage-students', (req, res) => {
    console.log("connect to /manage-students");
    if (req.session.userID) {
        const userQuery = "SELECT * FROM teacher WHERE userId = ?";
        db.query(userQuery, [req.session.userID], (error, teacherResult) => {
            if (error) {
                console.error('Error executing query:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            } else {
                res.render("manage-students", {
                    fullName: req.session.fullName
                });
            }
        });
    } else {
        res.redirect('logout');
    }
});

router.get('/fetch-all-students', (req, res) => {
    try {
       const fetchAllStudentsQuery = `
          SELECT
             student.studentId,
             CONCAT(student.lastName, ' ', student.firstName) AS fullName,
             user.email,
             student.course,
             student.classCode,
             company.companyName AS company
          FROM student
          JOIN user ON student.userId = user.userId
          LEFT JOIN internship ON student.studentId = internship.studentId
          LEFT JOIN company ON internship.companyId = company.companyId  ORDER BY fullName ASC
       `;
 
       // Execute the query
       db.query(fetchAllStudentsQuery, (error, results) => {
          if (error) {
             console.error('Error fetching all students:', error);
             res.status(500).json({ error: 'Internal Server Error' });
          } else {
             res.json(results);
          }
       });
    } catch (error) {
       console.error('Error in /fetch-all-students route:', error);
       res.status(500).json({ error: 'Internal Server Error' });
    }
 });

 router.get('/fetch-my-students', (req, res) => {
    try {
        const fetchTeacherIdQuery = 'SELECT teacherId FROM teacher WHERE userId = ?';

        db.query(fetchTeacherIdQuery, [req.session.userID], (teacherIdError, teacherIdResults) => {
            if (teacherIdError) {
                console.error('Error fetching teacher ID:', teacherIdError);
                return res.status(500).json({ error: 'Internal Server Error' });
            }

            if (teacherIdResults.length === 0) {
                return res.status(404).json({ error: 'Teacher not found' });
            }

            const teacherId = teacherIdResults[0].teacherId;

            // Fetch students associated with the teacher
            const fetchMyStudentsQuery = `
                SELECT
                    student.studentId,
                    CONCAT(student.lastName, ' ', student.firstName) AS fullName,
                    user.email,
                    student.course,
                    student.classCode,
                    company.companyName AS company
                FROM
                    student
                    JOIN user ON student.userId = user.userId
                    LEFT JOIN internship ON student.studentId = internship.studentId
                    LEFT JOIN company ON internship.companyId = company.companyId
                WHERE
                    internship.teacherId = ?  ORDER BY fullName ASC
            `;

            // Execute the query
            db.query(fetchMyStudentsQuery, [teacherId], (error, results) => {
                if (error) {
                    console.error('Error fetching my students:', error);
                    res.status(500).json({ error: 'Internal Server Error' });
                } else {
                    res.json(results);
                }
            });
        });
    } catch (error) {
        console.error('Error in /fetch-my-students route:', error);
        res.status(500).json({ error: 'Internal Server Error' });
    }
});

router.get('/is-student-assigned', (req, res) => {
    try {
        const studentId = req.query.studentId; // Get the student ID from the query parameter

        // First, fetch the teacherId based on the logged-in user's ID
        const fetchTeacherIdQuery = 'SELECT teacherId FROM teacher WHERE userId = ?';

        db.query(fetchTeacherIdQuery, [req.session.userID], (teacherIdError, teacherIdResults) => {
            if (teacherIdError) {
                console.error('Error fetching teacher ID:', teacherIdError);
                return res.status(500).json({ error: 'Internal Server Error' });
            }

            if (teacherIdResults.length === 0) {
                return res.status(404).json({ error: 'Teacher not found' });
            }

            const teacherId = teacherIdResults[0].teacherId;

            // Now check if the student is assigned to this teacher
            const checkStudentAssignmentQuery = `
                SELECT COUNT(*) AS isAssigned
                FROM internship
                WHERE teacherId = ?
                  AND studentId = ?;
            `;

            db.query(checkStudentAssignmentQuery, [teacherId, studentId], (error, results) => {
                if (error) {
                    console.error('Error checking student assignment:', error);
                    return res.status(500).json({ error: 'Internal Server Error' });
                } 

                const isAssigned = results[0].isAssigned > 0;
                console.log(isAssigned);
                res.json({ isAssigned });
            });
        });
    } catch (error) {
        console.error('Error in /is-student-assigned route:', error);
        res.status(500).json({ error: 'Internal Server Error' });
    }
});


router.get('/search-students', async (req, res) => {
    try {
       const searchQuery = req.query.query;
	    console.log(searchQuery);
 
	    /*
       const sql = `
       SELECT student.studentId, CONCAT(student.firstName, ' ', student.lastName) AS fullName, user.email, student.course, student.classCode, company.companyName
       FROM student
       JOIN user ON student.userId = user.userId
       JOIN internship ON student.studentId = internship.studentId
       JOIN company ON internship.companyId = company.companyId
       WHERE
          student.studentId LIKE '%${searchQuery}%'
          OR user.email LIKE '%${searchQuery}%'
          OR CONCAT(student.firstName, ' ', student.lastName) LIKE '%${searchQuery}%'
          OR student.course LIKE '%${searchQuery}%'
          OR student.classCode LIKE '%${searchQuery}%'
          OR company.companyName LIKE '%${searchQuery}%'
       `;
 
       const results = await db.query(sql);
	     */

       const sql = `SELECT student.studentId, CONCAT(student.lastName, ' ', student.firstName) AS fullName, user.email, student.firstName, student.lastName, student.course, student.classCode, company.companyName as company FROM student JOIN user ON student.userId = user.userId JOIN internship ON student.studentId = internship.studentId JOIN company ON internship.companyId = company.companyId WHERE student.studentId LIKE '%${searchQuery}%' OR user.email LIKE '%${searchQuery}%' OR CONCAT(student.firstName, ' ', student.lastName) LIKE '%${searchQuery}%' OR student.course LIKE '%${searchQuery}%'  OR student.classCode LIKE '%${searchQuery}%' OR company.companyName LIKE '%${searchQuery}%' ORDER BY fullName ASC`;

	console.log(db.query(sql, [searchQuery,searchQuery,searchQuery,searchQuery,searchQuery,searchQuery], (error, result) => {
            if (error) {
                console.error('Error executing query:', error);
                res.status(500).json({ error: 'Internal Server Error' });
                return;
            } else {
      		const plainResults = JSON.parse(JSON.stringify(result));
		    console.log(plainResults);
       		res.json(plainResults);
            }
	    }));

 
	    /*
      const plainResults = JSON.parse(JSON.stringify(results));
 
       res.json(plainResults);
       */
    } catch (error) {
       console.error('Error in /search-students route:', error);
       res.status(500).json({ error: 'Internal Server Error' });
    }
 });  

module.exports = router;
