define({ "api": [
  {
    "type": "get",
    "url": "/attendance/students/:class_id/:section_id/:date/:session_id",
    "title": "Get daily attendance",
    "name": "GetDailyAttendance",
    "description": "<p>Get class-section wise daily attendance. Only staffs can access this API.</p>",
    "group": "Attendance",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "class_id",
            "description": "<p>Class ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "section_id",
            "description": "<p>Section ID</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date",
            "description": "<p>date in format YYYY-MM-DD. If not provided current date will be used.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "session_id",
            "description": "<p>Session ID. If not provided current session will be used.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Attendance.php",
    "groupTitle": "Attendance",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/attendance/students/:class_id/:section_id/:date/:session_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/attendance/:student_id/:month/:year",
    "title": "Get monthly attendance",
    "name": "GetStudentAttendance",
    "description": "<p>Get attendance of a student for a month. If no month is provided, current month will be used. If no year is provided, current year will be used.</p>",
    "group": "Attendance",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "student_id",
            "description": "<p>Student ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "month",
            "description": "<p>month number</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "year",
            "description": "<p>four digit year</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Attendance.php",
    "groupTitle": "Attendance",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/attendance/:student_id/:month/:year"
      }
    ]
  },
  {
    "type": "post",
    "url": "/attendance/store",
    "title": "Save attendance",
    "name": "StoreAttendance",
    "description": "<p>Store attendance records. Only staff has access to this API.</p>",
    "group": "Attendance",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Object",
            "optional": false,
            "field": "payload",
            "description": "<p>JSON encoded data</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date",
            "description": "<p>date in YYYY-MM-DD format. If not provided current date will be used.</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Attendance.php",
    "groupTitle": "Attendance",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/attendance/store"
      }
    ]
  },
  {
    "type": "post",
    "url": "/auth/login",
    "title": "Login",
    "name": "login",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>Login id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>password</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "device_id",
            "description": "<p>device id</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "device_type",
            "description": "<p>device type (android, ios)</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Auth.php",
    "groupTitle": "Auth",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/auth/login"
      }
    ]
  },
  {
    "type": "post",
    "url": "/auth/logout",
    "title": "Logout",
    "name": "logout",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "uid",
            "description": "<p>uid value returned during login</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Auth.php",
    "groupTitle": "Auth",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/auth/logout"
      }
    ]
  },
  {
    "type": "post",
    "url": "/auth/newtoken",
    "title": "Get new jwt token",
    "name": "newtoken",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "uid",
            "description": "<p>uid value returned during login</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "refresh_token",
            "description": "<p>refresh token returned during login</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Auth.php",
    "groupTitle": "Auth",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/auth/newtoken"
      }
    ]
  },
  {
    "type": "post",
    "url": "/events",
    "title": "Event list",
    "name": "EventsList",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>Number of events</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "offset",
            "description": "<p>Number of events to skip</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword",
            "description": "<p>Search term</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "start_date",
            "description": "<p>Start date</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "end_date",
            "description": "<p>End date</p>"
          }
        ]
      }
    },
    "group": "Events",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Events.php",
    "groupTitle": "Events",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/events"
      }
    ]
  },
  {
    "type": "get",
    "url": "/events/:id",
    "title": "Event detail",
    "name": "GetEvent",
    "description": "<p>Staff can view any event, parent and student can view public events only</p>",
    "group": "Events",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Event ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Events.php",
    "groupTitle": "Events",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/events/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/exams/routine/:student_id/:exam_id",
    "title": "Student exam routine",
    "name": "ExamRoutine",
    "description": "<p>Exam routine for a student</p>",
    "group": "Exams",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "student_id",
            "description": "<p>Student ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "exam_id",
            "description": "<p>Exam ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Exams.php",
    "groupTitle": "Exams",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/exams/routine/:student_id/:exam_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/exams/classroutine/:class_id/:section_id/:exam_id",
    "title": "Class wise exam routine",
    "name": "ExamRoutineForClass",
    "description": "<p>Class wise exam routine</p>",
    "group": "Exams",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "class_id",
            "description": "<p>Class ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "section_id",
            "description": "<p>Section ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "exam_id",
            "description": "<p>Exam ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Exams.php",
    "groupTitle": "Exams",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/exams/classroutine/:class_id/:section_id/:exam_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/exams/student_marks/:student_id/:exam_id",
    "title": "Marks obtained",
    "name": "ExamStudentMarks",
    "description": "<p>Marks obtained by a student in an exam</p>",
    "group": "Exams",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "student_id",
            "description": "<p>Student ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "exam_id",
            "description": "<p>Exam ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Exams.php",
    "groupTitle": "Exams",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/exams/student_marks/:student_id/:exam_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/exams/:class_id/:section_id",
    "title": "Exams List",
    "name": "ExamsRoutines",
    "description": "<p>Exams list for a class</p>",
    "group": "Exams",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "class_id",
            "description": "<p>Class ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "section_id",
            "description": "<p>Section ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Exams.php",
    "groupTitle": "Exams",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/exams/:class_id/:section_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/instructor/class/:instructor_id",
    "title": "Class List",
    "name": "InstructorClassList",
    "description": "<p>Class list for an instructor</p>",
    "group": "Instructor",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instructor_id",
            "description": "<p>Instructor ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Instructor.php",
    "groupTitle": "Instructor",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/instructor/class/:instructor_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/instructor/class_sections/:instructor_id/:class_id",
    "title": "Class section List",
    "name": "InstructorClassSectionList",
    "description": "<p>Class section list for an instructor</p>",
    "group": "Instructor",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "instructor_id",
            "description": "<p>Instructor ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "class_id",
            "description": "<p>Class ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Instructor.php",
    "groupTitle": "Instructor",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/instructor/class_sections/:instructor_id/:class_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/notifications/:id",
    "title": "Notification detail",
    "name": "GetNotification",
    "group": "Notifications",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Notification ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Notifications.php",
    "groupTitle": "Notifications",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/notifications/:id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/notifications",
    "title": "Notification list",
    "name": "NotificationsList",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>Number of notifications</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "offset",
            "description": "<p>Number of notifications to skip</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword",
            "description": "<p>Search term</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "start_date",
            "description": "<p>Start date</p>"
          },
          {
            "group": "Parameter",
            "type": "Date",
            "optional": false,
            "field": "end_date",
            "description": "<p>End date</p>"
          }
        ]
      }
    },
    "group": "Notifications",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Notifications.php",
    "groupTitle": "Notifications",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/notifications"
      }
    ]
  },
  {
    "type": "get",
    "url": "/routes",
    "title": "List all routes",
    "name": "AllRoutes",
    "description": "<p>List all routes</p>",
    "group": "Routes",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Routes.php",
    "groupTitle": "Routes",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/routes"
      }
    ]
  },
  {
    "type": "get",
    "url": "/routes/student/:student_id",
    "title": "Student route",
    "name": "StudentRoute",
    "description": "<p>Get route for a student</p>",
    "group": "Routes",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "student_id",
            "description": "<p>Student ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Routes.php",
    "groupTitle": "Routes",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/routes/student/:student_id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/school",
    "title": "School detail",
    "name": "GetSchool",
    "group": "School",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/School.php",
    "groupTitle": "School",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/school"
      }
    ]
  },
  {
    "type": "get",
    "url": "/students/:id",
    "title": "Student detail",
    "name": "GetStudent",
    "description": "<p>Staff can access any student detail, parent can access own child detail, student can access only self detail</p>",
    "group": "Students",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Student ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Students.php",
    "groupTitle": "Students",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/students/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/students/classtimetable/:id",
    "title": "Class timetable",
    "name": "StudentClassTimetable",
    "description": "<p>Class timetable for a student</p>",
    "group": "Students",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Student ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Students.php",
    "groupTitle": "Students",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/students/classtimetable/:id"
      }
    ]
  },
  {
    "type": "get",
    "url": "/students/fees/:student_id",
    "title": "Fees report",
    "name": "StudentFees",
    "description": "<p>Get fees details of a student. Staff can access any student data, student can access own data, parent can access own child data.</p>",
    "group": "Students",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "student_id",
            "description": "<p>Student ID</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Students.php",
    "groupTitle": "Students",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/students/fees/:student_id"
      }
    ]
  },
  {
    "type": "post",
    "url": "/students",
    "title": "Student list",
    "name": "StudentsList",
    "description": "<p>Only staff can access this api</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>Number of students</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "offset",
            "description": "<p>Number of students to skip</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "keyword",
            "description": "<p>Search term</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "class_id",
            "description": "<p>Class ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "section_id",
            "description": "<p>Section ID</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "session_id",
            "description": "<p>Session ID</p>"
          }
        ]
      }
    },
    "group": "Students",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/Students.php",
    "groupTitle": "Students",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/students"
      }
    ]
  },
  {
    "type": "get",
    "url": "/user/notices_events/:limit",
    "title": "Notices & events",
    "name": "UserNoticesEvents",
    "description": "<p>Combined notices and events</p>",
    "group": "User",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>number of notices and events</p>"
          }
        ]
      }
    },
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>JWT token</p>"
          }
        ]
      }
    },
    "version": "2.0.0",
    "filename": "application/controllers/api/v2/User.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://172.18.11.14:7070/projects/Neema_School_Api/api/v2/user/notices_events/:limit"
      }
    ]
  }
] });
