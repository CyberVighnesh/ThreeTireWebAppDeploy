# AWS 3-Tier Student Registration Web Application

## Project Objective

Build, deploy, and test a **Student Registration Web Application using a
3-Tier Architecture on AWS**.

The complete application must be created from scratch and deployed on
AWS.

## Target Architecture

``` text
                         INTERNET
                            |
                            v
                  +-------------------+
                  |     WEB TIER      |
                  |   EC2 + Nginx     |
                  |   HTML/CSS/JS     |
                  +---------+---------+
                            |
                         HTTP/API
                            |
                            v
                  +-------------------+
                  |     APP TIER      |
                  | EC2 + Apache + PHP|
                  |   Business Logic  |
                  +---------+---------+
                            |
                         MySQL 3306
                            |
                            v
                  +-------------------+
                  |   DATABASE TIER   |
                  |    Amazon RDS     |
                  |      MySQL        |
                  +-------------------+
```

------------------------------------------------------------------------

# Part 1 --- Web Tier

### Technology

-   HTML5
-   CSS3
-   JavaScript
-   Nginx
-   Amazon Linux EC2

### Responsibilities

-   Display the student registration interface.
-   Serve static frontend files.
-   Send registration requests to the Application Tier.
-   Must **not** connect directly to MySQL.

### Suggested Structure

``` text
web/
├── index.html
├── css/
│   └── style.css
└── js/
    └── app.js
```

------------------------------------------------------------------------

# Part 2 --- Application Tier

### Technology

-   Amazon Linux
-   Apache
-   PHP
-   MySQL client/library

### Responsibilities

-   Receive requests from the Web Tier.
-   Validate student information.
-   Process registration.
-   Communicate with MySQL/RDS.
-   Perform CRUD operations.

### Required Operations

-   Create student
-   Read students
-   Update student
-   Delete student

### Suggested Structure

``` text
app/
├── config/
│   └── database.php
├── api/
│   ├── register.php
│   ├── students.php
│   ├── update.php
│   └── delete.php
└── includes/
    └── response.php
```

------------------------------------------------------------------------

# Part 3 --- Database Tier

### Technology

-   Amazon RDS
-   MySQL

### Database

``` text
Database: studentdb
Table: students
```

### Suggested Table

``` sql
CREATE DATABASE studentdb;

USE studentdb;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    course VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

------------------------------------------------------------------------

# Part 4 --- AWS Networking

## VPC

``` text
VPC CIDR: 10.0.0.0/16
```

## Subnets

``` text
Web Subnet
10.0.1.0/24

App Subnet
10.0.2.0/24

DB Subnet
10.0.3.0/24
```

The Web and App tiers can initially be kept simple for the lab. The
database should be private and should not be publicly accessible.

------------------------------------------------------------------------

# Part 5 --- Security Groups

## WEB-SG

Allow:

``` text
HTTP  TCP 80   0.0.0.0/0
HTTPS TCP 443  0.0.0.0/0
SSH   TCP 22   Your IP
```

## APP-SG

Allow:

``` text
HTTP TCP 80    Source: WEB-SG
SSH  TCP 22    Source: Your IP
```

Do not open the application server's HTTP port to the entire Internet.

## DB-SG

Allow:

``` text
MySQL TCP 3306    Source: APP-SG
```

### Important Security Rule

Do **not** allow:

``` text
3306 from 0.0.0.0/0
```

The database must not be publicly accessible.

------------------------------------------------------------------------

# Part 6 --- Create Web EC2

Create an EC2 instance:

``` text
Name: Student-Web
OS: Amazon Linux
Subnet: Web Subnet
Security Group: WEB-SG
```

SSH into the server.

Install Nginx:

``` bash
sudo dnf update -y
sudo dnf install nginx -y
```

Start and enable Nginx:

``` bash
sudo systemctl enable --now nginx
```

Test from your browser:

``` text
http://WEB-PUBLIC-IP
```

You should see the Nginx test page.

------------------------------------------------------------------------

# Part 7 --- Deploy Frontend

Copy the frontend files into:

``` text
/var/www/html/
```

Example:

``` bash
sudo cp index.html /var/www/html/
sudo cp style.css /var/www/html/
sudo cp app.js /var/www/html/
```

The Web Tier should now work like this:

``` text
Browser
   |
   v
Nginx
   |
   v
HTML/CSS/JavaScript
```

There should be no MySQL connection in the frontend.

------------------------------------------------------------------------

# Part 8 --- Create Application EC2

Create another EC2:

``` text
Name: Student-App
OS: Amazon Linux
Subnet: App Subnet
Security Group: APP-SG
```

Install Apache and PHP:

``` bash
sudo dnf update -y
sudo dnf install httpd php php-mysqli -y
```

Start Apache:

``` bash
sudo systemctl enable --now httpd
```

Test PHP:

``` bash
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/info.php
```

After testing, remove the file:

``` bash
sudo rm /var/www/html/info.php
```

------------------------------------------------------------------------

# Part 9 --- Create the PHP API

The Application Tier should contain:

``` text
register.php
students.php
update.php
delete.php
```

## register.php

Requirements:

-   Accept POST requests.
-   Validate input.
-   Insert a student into MySQL.
-   Return JSON.
-   Use prepared statements.

## students.php

Requirements:

-   Accept GET requests.
-   Retrieve students.
-   Return JSON.

## update.php

Requirements:

-   Accept PUT requests.
-   Validate ID and fields.
-   Update the student.
-   Return JSON.

## delete.php

Requirements:

-   Accept DELETE requests.
-   Delete the requested student.
-   Return JSON.

------------------------------------------------------------------------

# Part 10 --- Connect Application Tier to RDS

The PHP application must **not** use:

``` text
localhost
```

for the database.

The database is running on Amazon RDS.

Use:

``` text
DB_HOST
DB_NAME
DB_USER
DB_PASSWORD
```

Example:

``` php
$conn = new mysqli(
    "YOUR-RDS-ENDPOINT",
    "admin",
    "YOUR_PASSWORD",
    "studentdb"
);
```

Do not hard-code production credentials in source code.

For the lab, use an appropriate secure configuration method such as
environment variables or a protected configuration file outside the
publicly served frontend.

------------------------------------------------------------------------

# Part 11 --- Create Amazon RDS

Go to:

``` text
AWS Console
→ RDS
→ Create database
```

Select:

``` text
Engine: MySQL
```

Configure:

``` text
DB identifier: student-db
Master username: admin
Password: Your secure password
VPC: Your project VPC
```

Use the DB subnet group and:

``` text
Security Group: DB-SG
```

Keep public access disabled where possible.

------------------------------------------------------------------------

# Part 12 --- Test App EC2 → RDS

From the Application EC2:

``` bash
mysql -h YOUR-RDS-ENDPOINT -u admin -p
```

Enter the RDS password.

If successful:

``` text
mysql>
```

Then:

``` sql
USE studentdb;
```

Verify the table:

``` sql
SHOW TABLES;
```

Then:

``` sql
DESCRIBE students;
```

------------------------------------------------------------------------

# Part 13 --- Create Database Table

Run:

``` sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    course VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Verify:

``` sql
SHOW TABLES;
```

------------------------------------------------------------------------

# Part 14 --- Configure Nginx Reverse Proxy

The browser should access only the Web Tier.

The Web Tier forwards API requests to the Application Tier.

Flow:

``` text
Browser
   |
   v
Web EC2 / Nginx
   |
   | /api/
   v
App EC2 / Apache + PHP
   |
   v
RDS MySQL
```

Create an Nginx configuration:

``` bash
sudo nano /etc/nginx/conf.d/student.conf
```

Example:

``` nginx
server {
    listen 80;
    server_name _;

    root /var/www/html;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }

    location /api/ {
        proxy_pass http://APP-SERVER-PRIVATE-IP/;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}
```

Replace:

``` text
APP-SERVER-PRIVATE-IP
```

with the private IP address of the Application EC2.

Test the Nginx configuration:

``` bash
sudo nginx -t
```

If successful:

``` bash
sudo systemctl restart nginx
```

------------------------------------------------------------------------

# Part 15 --- Frontend API Communication

The frontend JavaScript should communicate with the Application Tier
through Nginx.

Example:

``` javascript
fetch("/api/register.php", {
    method: "POST",
    body: formData
});
```

The browser does not directly connect to the Application EC2.

The request path is:

``` text
Browser
   |
   | /api/register.php
   v
Nginx
   |
   v
Application EC2
   |
   v
PHP
   |
   v
RDS
```

------------------------------------------------------------------------

# Part 16 --- End-to-End Test

## Test 1 --- Web Tier

Open:

``` text
http://WEB-PUBLIC-IP
```

Expected:

``` text
Student Registration Form
```

------------------------------------------------------------------------

## Test 2 --- Registration

Submit:

``` text
Name: Rahul
Email: rahul@example.com
Phone: 9876543210
Course: AWS
```

Expected:

``` text
Registration successful
```

------------------------------------------------------------------------

## Test 3 --- Verify Application Server

On App EC2:

``` bash
sudo tail -f /var/log/httpd/access_log
```

Submit another registration.

Verify that the request reaches Apache/PHP.

------------------------------------------------------------------------

## Test 4 --- Verify Database

Connect to RDS:

``` bash
mysql -h YOUR-RDS-ENDPOINT -u admin -p
```

Then:

``` sql
USE studentdb;

SELECT * FROM students;
```

The newly registered student should appear.

------------------------------------------------------------------------

# Part 17 --- CRUD Testing

Students must demonstrate all four operations.

### CREATE

Register a new student.

``` text
Browser → Web → App → RDS
```

### READ

Display the student list.

### UPDATE

Change the student's course or phone number.

### DELETE

Delete the student.

Verify each operation in MySQL.

------------------------------------------------------------------------

# Part 18 --- Troubleshooting

Students should know how to check:

## Nginx

``` bash
sudo systemctl status nginx
sudo nginx -t
```

## Apache

``` bash
sudo systemctl status httpd
```

## Nginx Logs

``` bash
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

## Apache Logs

``` bash
sudo tail -f /var/log/httpd/access_log
sudo tail -f /var/log/httpd/error_log
```

## RDS Connectivity

``` bash
mysql -h RDS-ENDPOINT -u admin -p
```

## Check Listening Ports

``` bash
sudo ss -tulpn
```

------------------------------------------------------------------------

# Part 19 --- Final Architecture

The completed project should look like:

``` text
                         INTERNET
                            |
                            | HTTP/HTTPS
                            v
                  +-------------------+
                  |     WEB TIER      |
                  |                   |
                  | Amazon Linux EC2  |
                  | Nginx             |
                  | HTML/CSS/JS       |
                  +---------+---------+
                            |
                            | /api/
                            v
                  +-------------------+
                  |     APP TIER      |
                  |                   |
                  | Amazon Linux EC2  |
                  | Apache            |
                  | PHP               |
                  | REST-style APIs   |
                  +---------+---------+
                            |
                            | TCP 3306
                            v
                  +-------------------+
                  |   DATABASE TIER   |
                  |                   |
                  | Amazon RDS MySQL  |
                  | studentdb         |
                  | students          |
                  +-------------------+
```

------------------------------------------------------------------------

# Final Student Checklist

-   [ ] Create VPC
-   [ ] Create subnets
-   [ ] Configure route tables
-   [ ] Configure Internet Gateway
-   [ ] Create WEB-SG
-   [ ] Create APP-SG
-   [ ] Create DB-SG
-   [ ] Create Web EC2
-   [ ] Install Nginx
-   [ ] Create frontend from scratch
-   [ ] Create App EC2
-   [ ] Install Apache + PHP
-   [ ] Create PHP API
-   [ ] Create RDS MySQL
-   [ ] Create database
-   [ ] Create students table
-   [ ] Connect PHP to RDS
-   [ ] Configure Nginx reverse proxy
-   [ ] Test Web → App
-   [ ] Test App → DB
-   [ ] Test registration
-   [ ] Test CRUD
-   [ ] Verify data in RDS
-   [ ] Test Security Groups
-   [ ] Document the architecture

------------------------------------------------------------------------

# Codex Prompt --- Create the Web Application

Use this prompt in VS Code/Codex:

``` text
You are helping me build a beginner-friendly AWS 3-tier Student Registration Web Application.

Create the complete application from scratch.

Architecture:

Browser
  ↓
Web Tier: Nginx + HTML/CSS/JavaScript
  ↓
Application Tier: Apache + PHP
  ↓
Database Tier: Amazon RDS MySQL

IMPORTANT ARCHITECTURE RULES:

1. The frontend must NOT connect directly to MySQL.
2. PHP is responsible for all database operations.
3. The frontend communicates with PHP through HTTP API endpoints.
4. The application must be suitable for deployment on separate AWS EC2 instances.
5. Use prepared statements.
6. Validate input on the server side.
7. Return JSON responses from API endpoints.
8. Do not expose database credentials.
9. Do not hard-code production passwords.
10. Keep the project simple enough for students to understand.

Create this structure:

student-registration/
├── web/
│   ├── index.html
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
│
├── app/
│   ├── config/
│   │   └── database.php
│   ├── api/
│   │   ├── register.php
│   │   ├── students.php
│   │   ├── update.php
│   │   └── delete.php
│   └── includes/
│       └── response.php
│
└── database/
    └── schema.sql

FRONTEND:

Create a responsive Student Registration page with:

- Full Name
- Email
- Phone
- Course
- Register button
- Reset button
- Student list
- Edit button
- Delete button

Use vanilla JavaScript and fetch().

API endpoints:

POST /api/register.php
GET /api/students.php
PUT /api/update.php
DELETE /api/delete.php

DATABASE:

Database:
studentdb

Table:
students

Columns:

id INT AUTO_INCREMENT PRIMARY KEY
name VARCHAR(100) NOT NULL
email VARCHAR(150) NOT NULL
phone VARCHAR(20)
course VARCHAR(100)
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

BACKEND:

Implement all CRUD operations.

Use PDO or mysqli with prepared statements.

Validate:
- Name
- Email
- Phone
- Course

Return JSON responses such as:

{
  "success": true,
  "message": "Student registered successfully"
}

Handle errors safely.

Do not expose SQL errors, passwords, or sensitive configuration to the browser.

Also create database/schema.sql containing the database and table creation SQL.

Before creating files, explain the architecture briefly.

Then create the files.

After creating the application, explain:

1. What each file does.
2. How the frontend communicates with PHP.
3. How PHP communicates with MySQL.
4. How this application will be split across two EC2 instances and RDS.
5. What needs to be changed when deploying to AWS.
```

------------------------------------------------------------------------

# Codex Prompt --- AWS Deployment

After the application is working, use:

``` text
Now help me deploy this existing Student Registration application to AWS.

DO NOT rebuild the application.

Deploy it using this architecture:

Internet
 ↓
Web EC2
Nginx
 ↓
App EC2
Apache + PHP
 ↓
Amazon RDS MySQL

Use:

VPC: 10.0.0.0/16

Web subnet: 10.0.1.0/24
App subnet: 10.0.2.0/24
DB subnet: 10.0.3.0/24

Security Groups:

WEB-SG:
HTTP 80 from Internet
HTTPS 443 from Internet
SSH 22 from my IP

APP-SG:
HTTP 80 from WEB-SG
SSH 22 from my IP

DB-SG:
MySQL 3306 from APP-SG only

Guide me through deployment in this exact order:

1. VPC
2. Subnets
3. Route tables
4. Internet Gateway
5. Web EC2
6. App EC2
7. RDS MySQL
8. Security Groups
9. Nginx
10. Apache
11. PHP
12. Frontend deployment
13. PHP API deployment
14. RDS database creation
15. PHP database configuration
16. Nginx reverse proxy
17. End-to-end testing
18. CRUD testing
19. Troubleshooting

For every step provide:

- What I need to do in AWS Console
- Linux commands
- What the command does
- Expected output
- How to verify success
- Common errors and how to troubleshoot them

Important:

- Do not expose MySQL to the Internet.
- Do not use 0.0.0.0/0 for port 3306.
- Do not expose the Application EC2 unnecessarily.
- Do not put database passwords in frontend JavaScript.
- Do not assume values such as IP addresses or RDS endpoints; clearly mark values I must replace.
- Do not skip networking configuration.
```

This gives you a **complete reusable `.md` practical document** for both
**your trainer workflow and student assignment**, while keeping the
application creation and AWS deployment as separate Codex stages.
