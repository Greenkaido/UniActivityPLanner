<?php
// admin/microproject_report.php
require_once '../config/database.php';
require_once '../includes/header.php';

// Ensure it's admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Fetch stats for the report
$studentsCount = $pdo->query("SELECT count(*) FROM users WHERE role = 'student'")->fetchColumn();
$activitiesCount = $pdo->query("SELECT count(*) FROM activities")->fetchColumn();
$notificationsCount = $pdo->query("SELECT count(*) FROM notifications")->fetchColumn();
?>

<style>
    @media print {
        .navbar, .btn-print, .footer, .no-print {
            display: none !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
        }
        body {
            background: white !important;
        }
        .report-header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
    }
    .report-section {
        margin-bottom: 40px;
    }
    .report-section h3 {
        border-left: 5px solid #dc3545;
        padding-left: 15px;
        margin-bottom: 20px;
        color: #0f172a;
        font-weight: 700;
    }
    .code-block {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.9rem;
        border: 1px solid #dee2e6;
        margin: 10px 0;
    }
</style>

<div class="text-end mb-4 no-print">
    <button onclick="window.print()" class="btn btn-danger btn-print rounded-pill px-4 shadow-sm">
        <i class="bi bi-printer me-2"></i> Print Report / Save as PDF
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 p-5 mb-5">
    <!-- Title Page -->
    <div class="report-header text-center py-5">
        <h1 class="display-4 fw-bold text-dark">UniActivity Planner</h1>
        <h2 class="text-danger fw-medium">Microproject Report</h2>
        <hr class="w-25 mx-auto my-4 border-dark">
        <p class="lead">Course: <strong>JAVA SCRIPT AND PHP</strong></p>
        <div class="mt-5">
            <p class="mb-1">Submitted by:</p>
            <h4 class="fw-bold text-dark">[Student Name / Roll Number]</h4>
            <p class="text-muted">Academic Year: 2024-25</p>
        </div>
        <div class="mt-5">
            <p class="mb-0">Under the Guidance of:</p>
            <h5 class="fw-bold">[Prof. Name]</h5>
        </div>
    </div>

    <!-- 1. Introduction -->
    <div class="report-section mt-5">
        <h3>1. Introduction</h3>
        <p>The <strong>UniActivity Planner</strong> is a specialized web application designed to streamline the management and tracking of university-level activities. It provides a centralized platform where administrators can publish events and students can track their participation and receive direct notifications.</p>
        <p>Traditional manual tracking often leads to missed deadlines and poor communication. This project creates a role-based interaction system that ensures transparency and immediate feedback through an integrated notification system.</p>
    </div>

    <!-- 2. Objective -->
    <div class="report-section">
        <h3>2. Objective of the Project</h3>
        <ul>
            <li><strong>Efficiency:</strong> Efficiently communicate new activities to specific student groups.</li>
            <li><strong>Feedback Loop:</strong> Allow students to react (emojis) and reply (text) to announcements.</li>
            <li><strong>Tracking:</strong> Enable digital attendance recording (Present/Absent) for accountability.</li>
            <li><strong>History:</strong> Maintain a historical log for sent notifications and student responses.</li>
        </ul>
    </div>

    <!-- 3. Technologies Used -->
    <div class="report-section">
        <h3>3. Tools / Technologies Used</h3>
        <div class="row text-center g-3">
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border h-100">
                    <h5 class="fw-bold mb-1">Frontend</h5>
                    <p class="small text-muted mb-0">HTML5, CSS3, JS, Bootstrap 5</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border h-100">
                    <h5 class="fw-bold mb-1">Backend</h5>
                    <p class="small text-muted mb-0">PHP 8.x (PDO)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border h-100">
                    <h5 class="fw-bold mb-1">Database</h5>
                    <p class="small text-muted mb-0">MySQL (MariaDB)</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-light rounded border h-100">
                    <h5 class="fw-bold mb-1">Server</h5>
                    <p class="small text-muted mb-0">XAMPP Virtual Server</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Implementation -->
    <div class="report-section">
        <h3>4. Implementation Detail</h3>
        <p>The system is built on a relational database structure designed for high performance and scalability.</p>
        
        <h5 class="fw-bold text-dark mt-4">Database Schema: Notifications & Feedback</h5>
        <div class="code-block">
            CREATE TABLE notifications (<br>
            &nbsp;&nbsp;id INT AUTO_INCREMENT PRIMARY KEY,<br>
            &nbsp;&nbsp;user_id INT,<br>
            &nbsp;&nbsp;message TEXT,<br>
            &nbsp;&nbsp;reaction_emoji VARCHAR(50),<br>
            &nbsp;&nbsp;reply_text TEXT,<br>
            &nbsp;&nbsp;created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP<br>
            );
        </div>

        <h5 class="fw-bold text-dark mt-4">Database Schema: Attendance System</h5>
        <div class="code-block">
            CREATE TABLE attendance (<br>
            &nbsp;&nbsp;activity_id INT,<br>
            &nbsp;&nbsp;user_id INT,<br>
            &nbsp;&nbsp;status ENUM('present', 'absent'),<br>
            &nbsp;&nbsp;PRIMARY KEY (activity_id, user_id)<br>
            );
        </div>
    </div>

    <!-- 5. Key Features -->
    <div class="report-section">
        <h3>5. Key Features Integrated</h3>
        <div class="mb-4">
            <h6 class="fw-bold"><i class="bi bi-emoji-smile text-danger me-2"></i>Dual-Feedback Notifications</h6>
            <p>Students can express their status regarding an activity with a single click (using emojis like 👍 or ❤️) or provide detailed text feedback. This ensures the admin knows the mood and engagement level of students instantly.</p>
        </div>
        <div class="mb-4">
            <h6 class="fw-bold"><i class="bi bi-person-check text-danger me-2"></i>Attendance Tracking</h6>
            <p>A dedicated module allows admins to select any activity and mark students as either "Present" or "Absent". Students can view their personal participation record to stay updated on their attendance status.</p>
        </div>
        <div class="mb-4">
            <h6 class="fw-bold"><i class="bi bi-tablet text-danger me-2"></i>Responsive Premium UI</h6>
            <p>Built with Bootstrap 5 and the Outfit font, the design is mobile-first, ensuring students can access activity details and provide feedback even on the move.</p>
        </div>
    </div>

    <!-- 6. Project Stats -->
    <div class="report-section">
        <h3>6. Current Implementation Stats</h3>
        <table class="table table-bordered text-center h-100">
            <thead class="bg-light">
                <tr>
                    <th>Total Students</th>
                    <th>Total Activities</th>
                    <th>Notifications Sent</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fs-4 fw-bold">
                    <td><?php echo $studentsCount; ?></td>
                    <td><?php echo $activitiesCount; ?></td>
                    <td><?php echo $notificationsCount; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 7. Conclusion -->
    <div class="report-section">
        <h3>7. Conclusion</h3>
        <p>The project successfully creates a streamlined digital bridge between the university administration and its students. The implementation of interactive feedback and attendance logging provides a modern solution for educational institutions. The application is ready for deployment in a local network environment and provides a strong foundation for future enhancements like real-time push notifications.</p>
    </div>

    <div class="mt-5 pt-5 text-center text-muted">
        <p>© 2024 UniActivity Planner - Academic Project</p>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
