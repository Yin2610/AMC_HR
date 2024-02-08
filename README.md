# Quick Start

## Installation
1. Install XAMPP.
2. Start Apache and MySQL services in XAMPP. 
3. Download this repository in your local machine.
4. Place this whole project folder under this directory xampp/htdocs/.

## Database
1. After starting MySQL in XAMPP, go to https://localhost/phpmyadmin/index.php in your browser.
2. In the phpMyAdmin, import the amc_hr.sql found under SQL folder downloaded.
(Take note that the database name is amc_hr, user is root and password is blank to connect to phpMyAdmin.)
2. Check if there are 8 tables inserted with data. If it is, the database is succcessfully imported.

## Login to Web App
1. After starting Apache in XAMPP, go to this link http://localhost/AMC_HR/index.php in your browser to see the login page.
2. For login, please note that passwords for Michael (administrator) is michael, Sofia (purchasing director/department head) is sofia, Hailey (purchasing assistant/employee) is H@1ley123.

## Functions accessible for each role after login
### For administrator,
1. View Profile
2. Request Leave
3. Manage Employees -> Retrieve Employees -> Create New Employee
                                          -> Update Existing Employee
                                          -> Delete Existing Employee
                                          -> Change Existing Employee's Password
4. Logout

### For department head,
1. View Profile
2. Request Leave
3. View Own Leave Requests (for viewing leave requests that the department head made)
4. Manage Employees -> Retrieve Employees -> Update Existing Employee
                                          -> Delete Existing Employee
                                          -> Change Existing Employee's Password
5. Manage Payroll -> Retrieve Payroll (Payroll of employees under the specific department of the logged-in department head) -> Create New Payroll
                                                                                                                            -> Update Existing Payroll
                                                                                                                            -> Delete Existing Payroll
6. Manage Leave Requests -> Retrieve Leave Requests (for viewing leave requests made by employees under the department) -> Update Existing Leave Request Status (Approve/Reject)
7. Logout

### For employee,
1. View Profile
2. Request Leave
3. Logout

Please refer to the test plan in the SWAP group report if you need any assistance in testing this web app. :)
