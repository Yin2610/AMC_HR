-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2023 at 11:39 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amc_hr`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `Attendance_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Status` varchar(10) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `Leave_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `Bank_ID` int(11) NOT NULL,
  `Bank_Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`Bank_ID`, `Bank_Name`) VALUES
(1, 'DBS'),
(2, 'OCBC'),
(3, 'UOB');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `Department_ID` int(11) NOT NULL,
  `Department_Name` varchar(30) NOT NULL,
  `Description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`Department_ID`, `Department_Name`, `Description`) VALUES
(1, 'Purchasing Department', 'Department that purchases materials required for AMC'),
(2, 'Sales Department', 'Department that sells products from AMC'),
(3, 'HR Department', 'Department that manages human resource in AMC');

-- --------------------------------------------------------

--
-- Table structure for table `designation`
--

CREATE TABLE `designation` (
  `Designation_ID` int(11) NOT NULL,
  `Designation` varchar(30) NOT NULL,
  `Salary` decimal(15,0) NOT NULL,
  `Department_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `designation`
--

INSERT INTO `designation` (`Designation_ID`, `Designation`, `Salary`, `Department_ID`) VALUES
(1, 'Purchasing Director', 20000, 1),
(2, 'Purchasing Manager', 10000, 1),
(3, 'Purchasing Assistant', 2000, 1),
(4, 'Sales Director', 20000, 2),
(5, 'Sales Representative', 3000, 2),
(6, 'Sales Associate', 2300, 2),
(7, 'HR Director', 20000, 3),
(8, 'Recruiter', 2800, 3);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_ID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Gender` varchar(6) NOT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Phone_Num` varchar(8) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Address` varchar(50) NOT NULL,
  `Onboard_Date` date NOT NULL,
  `Offboard_Date` date NOT NULL,
  `Bank_Account` int(15) NOT NULL,
  `IC_Number` varchar(9) NOT NULL,
  `Profile_Pic` varchar(50) DEFAULT NULL,
  `Resume` varchar(50) DEFAULT NULL,
  `Contract` varchar(50) DEFAULT NULL,
  `Password` varchar(50) NOT NULL,
  `Role_ID` int(11) NOT NULL,
  `Designation_ID` int(11) NOT NULL,
  `Bank_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Employee_ID`, `Name`, `Gender`, `Date_Of_Birth`, `Phone_Num`, `Email`, `Address`, `Onboard_Date`, `Offboard_Date`, `Bank_Account`, `IC_Number`, `Profile_Pic`, `Resume`, `Contract`, `Password`, `Role_ID`, `Designation_ID`, `Bank_ID`) VALUES
(1, 'Michael', 'Male', '1992-05-12', '52981408', 'michael@gmail.com', '371 Alexandra Rd #08-03A Singapore, 159963', '2020-09-11', '0000-00-00', 2147483647, 'S9245251D', 'Employee_Info/Profile_Pics/Michael_profile.jpg', 'Employee_Info/Resume/Michael_resume.pdf', 'Employee_Info/Contract/Michael_contract.pdf', 'michael', 1, 8, 1),
(2, 'Sofia', 'Female', '2000-11-05', '90238547', 'sofia@gmail.com', '828 Tampines Street 81 #01-228 Singapore, 520828', '2020-06-15', '0000-00-00', 2147483647, 'T0030938E', 'Employee_Info/Profile_Pics/Sofia_profile.jpg', 'Employee_Info/Resume/Sofia_resume.pdf', 'Employee_Info/Contract/Sofia_contract.pdf', 'sofia', 2, 4, 2),
(3, 'Alyssa', 'Female', '2002-10-12', '45091209', 'alyssa@gmail.com', '16 Ayer Rajah Crescent #04-06A Singapore, 139965', '2021-04-23', '0000-00-00', 2147483647, 'T0276579E', 'Employee_Info/Profile_Pics/Alyssa_profile.jpg', 'Employee_Info/Resume/Alyssa_resume.pdf', 'Employee_Info/Contract/Alyssa_contract.pdf', 'alyssa', 3, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `leave`
--

CREATE TABLE `leave` (
  `Leave_ID` int(11) NOT NULL,
  `Leave_Category` varchar(30) NOT NULL,
  `Submission_Date` date NOT NULL,
  `From_Date` date NOT NULL,
  `Until_Date` date NOT NULL,
  `Notes` varchar(200) NOT NULL,
  `Supporting_Doc` varchar(50) DEFAULT NULL,
  `Status` varchar(30) NOT NULL,
  `Approval_Date` date DEFAULT NULL,
  `Approved_By` int(11) NOT NULL,
  `Submitted_By` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `Payroll_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Payslip` varchar(50) DEFAULT NULL,
  `Employee_ID` int(11) NOT NULL,
  `Designation_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `Role_ID` int(11) NOT NULL,
  `Role_Name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`Role_ID`, `Role_Name`) VALUES
(1, 'Administrator'),
(2, 'Department Head'),
(3, 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Attendance_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `Leave_ID` (`Leave_ID`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`Bank_ID`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Department_ID`);

--
-- Indexes for table `designation`
--
ALTER TABLE `designation`
  ADD PRIMARY KEY (`Designation_ID`),
  ADD KEY `Department_ID` (`Department_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_ID`),
  ADD KEY `Role_ID` (`Role_ID`),
  ADD KEY `Designation_ID` (`Designation_ID`),
  ADD KEY `Bank_ID` (`Bank_ID`);

--
-- Indexes for table `leave`
--
ALTER TABLE `leave`
  ADD PRIMARY KEY (`Leave_ID`),
  ADD KEY `Approved_By` (`Approved_By`),
  ADD KEY `Submitted_By` (`Submitted_By`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`Payroll_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`),
  ADD KEY `Designation_ID` (`Designation_ID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`Role_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `Attendance_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `Bank_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `Department_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `designation`
--
ALTER TABLE `designation`
  MODIFY `Designation_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leave`
--
ALTER TABLE `leave`
  MODIFY `Leave_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `Payroll_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `Role_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employee` (`Employee_ID`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`Leave_ID`) REFERENCES `leave` (`Leave_ID`);

--
-- Constraints for table `designation`
--
ALTER TABLE `designation`
  ADD CONSTRAINT `designation_ibfk_1` FOREIGN KEY (`Department_ID`) REFERENCES `department` (`Department_ID`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_3` FOREIGN KEY (`Role_ID`) REFERENCES `role` (`Role_ID`),
  ADD CONSTRAINT `employee_ibfk_6` FOREIGN KEY (`Designation_ID`) REFERENCES `designation` (`Designation_ID`),
  ADD CONSTRAINT `employee_ibfk_7` FOREIGN KEY (`Bank_ID`) REFERENCES `bank` (`Bank_ID`);

--
-- Constraints for table `leave`
--
ALTER TABLE `leave`
  ADD CONSTRAINT `leave_ibfk_1` FOREIGN KEY (`Approved_By`) REFERENCES `employee` (`Employee_ID`),
  ADD CONSTRAINT `leave_ibfk_2` FOREIGN KEY (`Submitted_By`) REFERENCES `employee` (`Employee_ID`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employee` (`Employee_ID`),
  ADD CONSTRAINT `payroll_ibfk_2` FOREIGN KEY (`designation_id`) REFERENCES `designation` (`Designation_ID`),
  ADD CONSTRAINT `payroll_ibfk_3` FOREIGN KEY (`Designation_ID`) REFERENCES `designation` (`Designation_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
