=========================================================
Blackened Futsal Tournament Management System - User Guide
=========================================================

This system helps you manage futsal tournaments, including teams, matches, player stats, and generating reports in PDF format.

---------------------------------------------------------
Folder Structure & File Descriptions
---------------------------------------------------------

2_DI220151_BLACKENED FUTSAL TOURNAMENT MANAGEMENT SYSTEM/
│
├── connection.php
│     - Database connection settings. All PHP files include this to connect to MySQL.
│
├── index.php
│     - Main landing/login page for the system.
│
├── dashboard.php
│     - Main dashboard after login. Shows tournament overview and navigation.
│
├── report.php
│     - Displays the tournament report: teams, standings, match results, player stats, etc.
│     - All report sections are shown as tables for easy reading and PDF export.
│
├── export_pdf.php
│     - Exports the report (from report.php) as a PDF using Dompdf.
│     - Removes sidebar and export buttons for clean PDF output.
│
├── sidebar.php
│     - Contains the sidebar navigation menu (included in most pages except PDF export).
│
├── team.php
│     - Manage teams: add, edit, delete, and view teams in the tournament.
│
├── player.php
│     - Manage players: add, edit, delete, and view players for each team.
│
├── match_schedule.php
│     - Manage match schedules: create, edit, and view matches.
│
├── standings.php
│     - View and manage tournament standings.
│
├── brackets.php
│     - View and manage knockout brackets.
│
├── assets/
│     - Contains CSS, images, and JavaScript files for styling and interactivity.
│
├── vendor/
│     - Composer dependencies (including Dompdf for PDF export).
│
└── readme.txt
      - This user guide.

---------------------------------------------------------
How to Use the System
---------------------------------------------------------

1. **Setup**
   - Place the 2_DI220151_BLACKENED FUTSAL TOURNAMENT MANAGEMENT SYSTEM folder in your XAMPP `htdocs` directory.
   - Import the provided SQL file (if any) into your MySQL database.
   - Update `connection.php` with your database credentials.

2. **Login**
   - Open your browser and go to `http://localhost/2_DI220151_BLACKENED FUTSAL TOURNAMENT MANAGEMENT SYSTEM/`.
   - Log in with your credentials.

3. **Dashboard**
   - After login, you will see the dashboard with navigation links.

4. **Managing Teams and Players**
   - Use the sidebar to navigate to Teams or Players.
   - Add, edit, or delete teams and players as needed.

5. **Scheduling Matches**
   - Go to Match Schedule to create or edit matches.

6. **Viewing Standings and Brackets**
   - Use Standings and Brackets pages to view tournament progress.

7. **Generating Reports**
   - Go to the Report page to view a summary of the tournament.
   - Click "Export to PDF" to download a PDF version of the report (without sidebar).

---------------------------------------------------------
Notes
---------------------------------------------------------
- Only users with valid credentials can access the system.
- All data is stored in the connected MySQL database.
- For PDF export, Dompdf is used (included in the vendor folder).
- If you encounter errors, check your database connection and PHP version.

---------------------------------------------------------
Support
---------------------------------------------------------
For issues or questions, contact the system administrator.