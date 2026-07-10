# Staff-Profile-Management-System

A secure, centralized, web-based application designed to streamline the management of employee records and attendance. This system replaces inefficient paper-based filing and basic spreadsheets with a structured relational database, ensuring data integrity, robust security, and instant accessibility for administrators. <br> 

Developed as a group project for the Advanced Web Technology (TICT3132) module.  

# Key FeaturesSecure

Authentication: An administrator login portal featuring credential validation to prevent unauthorized system access.  <br>
Centralized Administrative Dashboard: A comprehensive summary view tracking the total number of staff alongside quick-access links to critical modules.  <br>
Full CRUD Operations: Seamless capabilities to Add (Create), View (Read), Edit (Update), and Remove (Delete) comprehensive staff profiles.  <br>
Comprehensive Staff Profiles: Captures detailed records including Employee Name, Designation, Contact Information, Email, and Date of Joining. <br>
Dynamic Attendance Records: Integrated functionality to track, update, and manage daily employee attendance history.<br>
Advanced Search & Filter Directory: A responsive data table enabling administrators to look up staff instantly by ID, Name, or Department. 

# Tech Stack & Tools

Frontend: HTML5, CSS3, JavaScript (for building a clean, responsive user interface).<br>
Backend: PHP (handling server-side application logic and secure API endpoints). <br>
Database: MySQL / SQL (structured relational database design for robust data persistence). <br>
Development Environment: XAMPP local server. <br>
Code Editor: Visual Studio Code. 

# My Contributions
As a core developer on this project, my responsibilities spanned across the full development lifecycle, focusing primarily on data persistence and full-stack integration:Database Architecture,<br><br>
Designed and implemented the relational MySQL database schema, ensuring data integrity and optimizing tables for staff information and attendance history.  
<br>
Backend Logic: Developed server-side PHP scripts to bridge the user interface with the database, handling authentication logic, secure CRUD workflows, and the attendance tracking mechanism. <br>
Frontend Development: Assisted in building responsive, form-based components and dynamic directory tables using HTML, CSS, and JavaScript.

# System Architecture & Interface

1. Database Schema
2. Core Modules <br>
Login Interface: Secure administrator gateway.<br>
Dashboard: High-level metrics showing active personnel summaries. <br>
Staff Directory & Attendance: Filterable tables with real-time search logic.

# Installation & Setup
1. Clone the repository:

Bash
git clone https://github.com/DeshaniBandara/Staff-Profile-Management-System.git

2. Set up the Local Server:
 <br>Download and install XAMPP.  Move the cloned project folder into the server's root directory (e.g., C:/xampp/htdocs/).<br>

3. Import the Database:<br>

Open your browser and navigate to http://localhost/phpmyadmin.<br>

Create a new database (e.g., staff_db).<br>

Import the provided database file (database/staff_db.sql).<br>

4. Configure Database Connection:<br>

Open config.php (or your respective database connection file) and update your database credentials if necessary.<br>

5. Run the Application:<br>

Start Apache and MySQL modules via your server control panel.<br>

Navigate to http://localhost/staff-profile-management in your browser.<br>





