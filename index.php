<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Student Panel</h3>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="add_student.php">Add Student</a></li>
                <li><a href="view_students.php">View Students</a></li>
                <li><a href="contact.php">Contact Form</a></li>
                <li><a href="admin_queries.php">Admin Queries</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Admin Dashboard</h1>
            <div class="stats-grid">
                <div class="card blue">Total Students <br> 2</div>
                <div class="card green">Total Queries <br> 2</div>
                <div class="card orange">Read Queries <br> 2</div>
                <div class="card red">Unread Queries <br> 0</div>
            </div>
        </main>
    </div>
</body>
</html>