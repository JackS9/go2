-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql.westvirginiaresearch.org
-- Generation Time: Jan 31, 2019 at 12:42 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marsv2`
--
CREATE DATABASE IF NOT EXISTS `marsv2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `marsv2`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_ActsFinds`
--

DROP TABLE IF EXISTS `tbl_MARS_ActsFinds`;
CREATE TABLE `tbl_MARS_ActsFinds` (
  `actfinds_ID` int(11) NOT NULL,
  `report_data_ID` int(11) DEFAULT '0',
  `actfinds_Activities` text,
  `actfinds_Findings` text,
  `actfinds_Training` text,
  `actfinds_Outreach` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Agencies`
--

DROP TABLE IF EXISTS `tbl_MARS_Agencies`;
CREATE TABLE `tbl_MARS_Agencies` (
  `agency_ID` int(11) NOT NULL,
  `agency_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Agencies`
--

TRUNCATE TABLE `tbl_MARS_Agencies`;
--
-- Dumping data for table `tbl_MARS_Agencies`
--

INSERT INTO `tbl_MARS_Agencies` (`agency_ID`, `agency_Name`) VALUES
(1, 'AO Foundation'),
(2, 'DARPA'),
(3, 'DEPSCoR'),
(4, 'DOD'),
(5, 'DOE'),
(6, 'DOE/NETL-RDS'),
(7, 'EPA'),
(8, 'NASA'),
(9, 'NIH'),
(10, 'NSF'),
(11, 'ONR'),
(12, 'Orthopaedic Research and Education Foundation (OREF)'),
(13, 'US DoEd'),
(14, 'USDA'),
(15, 'WVEPSCoR'),
(16, 'WVU'),
(17, 'WVU Faculty Senate'),
(18, 'Division of Science and Research - HEPC'),
(19, 'Defense Intelligence Agency'),
(20, 'Brown-Hott Foundation'),
(21, 'Marshall University School of Medicine'),
(22, 'Department of the Army - USAMRAA'),
(23, 'Musculoskeletal Transplant Foundation'),
(24, 'Osteosynthesis and Trauma Care Foundation'),
(27, 'Aircast Foundation'),
(28, 'WVU Health Sciences'),
(29, 'NASA EPSCoR'),
(30, 'WVNano'),
(31, 'Japan Society for the Promotion of Science'),
(32, 'Alzheimers Association'),
(33, 'American Parkinson Disease Association'),
(34, 'Micheal J. Fox Foundation for Parkinsons Research'),
(35, 'Carlos Ballard Undergraduate Research Award'),
(36, 'TriLink Biotechnologies Research Rewards Program');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Announce`
--

DROP TABLE IF EXISTS `tbl_MARS_Announce`;
CREATE TABLE `tbl_MARS_Announce` (
  `ann_ID` int(11) NOT NULL,
  `rfp_type_ID` int(11) DEFAULT NULL,
  `ann_Public_Name` varchar(255) DEFAULT NULL,
  `ann_Entity_Eligibility` int(11) DEFAULT '0',
  `ann_PI_Eligiblity` int(11) DEFAULT '0',
  `ann_Review_Requirement` int(11) DEFAULT '0',
  `ann_Grant_Instrument` int(11) DEFAULT '0',
  `ann_Grant_Type` int(11) DEFAULT '0',
  `ann_Grant_Agency` int(11) DEFAULT '0',
  `ann_Agency_Other` varchar(255) DEFAULT NULL,
  `ann_Date_Announce` datetime DEFAULT NULL,
  `ann_Date_Open` datetime DEFAULT NULL,
  `ann_Date_Close` datetime DEFAULT NULL,
  `ann_Date_Award` datetime DEFAULT NULL,
  `ann_Date_Expire` datetime DEFAULT NULL,
  `ann_Reviewer_Count` int(11) DEFAULT '0',
  `ann_Reporting_Period` int(11) DEFAULT '0',
  `ann_PDF_DisplayName` varchar(255) DEFAULT NULL,
  `ann_PDF_StoredName` varchar(255) DEFAULT NULL,
  `ann_Narrative` text,
  `ann_datetimestamp` timestamp NULL DEFAULT NULL,
  `ann_External` tinyint(4) DEFAULT '0',
  `ann_submittedby` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_AwardPubs`
--

DROP TABLE IF EXISTS `tbl_MARS_AwardPubs`;
CREATE TABLE `tbl_MARS_AwardPubs` (
  `awardpub_ID` int(11) NOT NULL,
  `pub_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0',
  `awardpub_submittedby` int(11) DEFAULT NULL,
  `awardpub_datetimestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Awards`
--

DROP TABLE IF EXISTS `tbl_MARS_Awards`;
CREATE TABLE `tbl_MARS_Awards` (
  `award_ID` int(11) NOT NULL,
  `proposal_ID` int(11) DEFAULT '0',
  `ann_ID` int(11) DEFAULT '0',
  `award_Date` datetime DEFAULT NULL,
  `award_Number` varchar(50) DEFAULT NULL,
  `award_amount` float(18,2) DEFAULT '0.00',
  `award_submittedby` int(11) DEFAULT NULL,
  `award_StartDate` datetime DEFAULT NULL,
  `award_EndDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Award_Incs`
--

DROP TABLE IF EXISTS `tbl_MARS_Award_Incs`;
CREATE TABLE `tbl_MARS_Award_Incs` (
  `award_inc_ID` int(11) NOT NULL,
  `award_ID` int(11) NOT NULL,
  `award_inc_Date` datetime DEFAULT NULL,
  `award_inc_Number` varchar(50) DEFAULT NULL,
  `award_inc_amount` float(18,2) DEFAULT '0.00',
  `award_inc_submittedby` int(11) DEFAULT NULL,
  `award_inc_StartDate` datetime DEFAULT NULL,
  `award_inc_EndDate` datetime DEFAULT NULL,
  `award_inc_letter_DisplayName` varchar(255) DEFAULT NULL,
  `award_inc_letter_StoredName` varchar(255) DEFAULT NULL,
  `award_inc_report_DueDate` datetime DEFAULT NULL,
  `award_inc_report_ID` int(11) DEFAULT NULL,
  `award_inc_report_Final` tinyint(4) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Award_Reqs`
--

DROP TABLE IF EXISTS `tbl_MARS_Award_Reqs`;
CREATE TABLE `tbl_MARS_Award_Reqs` (
  `req_ID` int(11) NOT NULL,
  `ann_ID` int(11) DEFAULT '0',
  `req_Proposal` tinyint(4) DEFAULT NULL,
  `req_Reporting` tinyint(4) DEFAULT '0',
  `req_Demographics` tinyint(4) DEFAULT '0',
  `req_Publications` tinyint(4) DEFAULT '0',
  `req_Teams` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Bio`
--

DROP TABLE IF EXISTS `tbl_MARS_Bio`;
CREATE TABLE `tbl_MARS_Bio` (
  `bio_ID` int(11) NOT NULL,
  `people_ID` int(11) DEFAULT '0',
  `bio_Narrative` text,
  `bio_Attachment_DisplayName` varchar(255) DEFAULT NULL,
  `bio_Attachment_StoredName` varchar(255) DEFAULT NULL,
  `bio_datetimestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Citizenship`
--

DROP TABLE IF EXISTS `tbl_MARS_Citizenship`;
CREATE TABLE `tbl_MARS_Citizenship` (
  `citizenship_ID` int(11) NOT NULL,
  `citizenship_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Citizenship`
--

TRUNCATE TABLE `tbl_MARS_Citizenship`;
--
-- Dumping data for table `tbl_MARS_Citizenship`
--

INSERT INTO `tbl_MARS_Citizenship` (`citizenship_ID`, `citizenship_Name`) VALUES
(1, 'US Citizen'),
(2, 'Permanent Resident'),
(3, 'Other non-U.S. Citizen'),
(4, 'Not provided');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Collaborations`
--

DROP TABLE IF EXISTS `tbl_MARS_Collaborations`;
CREATE TABLE `tbl_MARS_Collaborations` (
  `collab_ID` int(11) NOT NULL,
  `report_data_ID` int(11) DEFAULT '0',
  `people_ID` int(11) DEFAULT '0',
  `collab_Degree` int(11) DEFAULT '0',
  `collab_Narrative` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Contributions`
--

DROP TABLE IF EXISTS `tbl_MARS_Contributions`;
CREATE TABLE `tbl_MARS_Contributions` (
  `contrib_ID` int(11) NOT NULL,
  `report_Data_ID` int(11) DEFAULT '0',
  `contrib_Discipline` text,
  `contrib_OtherDisc` text,
  `contrib_HR` text,
  `contrib_ResRes` text,
  `contrib_Beyond` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Country`
--

DROP TABLE IF EXISTS `tbl_MARS_Country`;
CREATE TABLE `tbl_MARS_Country` (
  `country_ID` int(11) NOT NULL,
  `country_Name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Country`
--

TRUNCATE TABLE `tbl_MARS_Country`;
--
-- Dumping data for table `tbl_MARS_Country`
--

INSERT INTO `tbl_MARS_Country` (`country_ID`, `country_Name`) VALUES
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'American Samoa'),
(5, 'Andorra'),
(6, 'Angola'),
(7, 'Antigua and Barbuda'),
(8, 'Argentina'),
(9, 'Armenia'),
(10, 'Aruba'),
(11, 'Australia'),
(12, 'Austria'),
(13, 'Azerbaijan'),
(14, 'Bahamas, The'),
(15, 'Bahrain'),
(16, 'Bangladesh'),
(17, 'Barbados'),
(18, 'Belarus'),
(19, 'Belgium'),
(20, 'Belize'),
(21, 'Benin'),
(22, 'Bermuda'),
(23, 'Bhutan'),
(24, 'Bolivia'),
(25, 'Bosnia and Herzegovina'),
(26, 'Botswana'),
(27, 'Brazil'),
(28, 'Brunei Darussalam'),
(29, 'Bulgaria'),
(30, 'Burkina Faso'),
(31, 'Burundi'),
(32, 'Cambodia'),
(33, 'Cameroon'),
(34, 'Canada'),
(35, 'Cape Verde'),
(36, 'Cayman Islands'),
(37, 'Central African Republic'),
(38, 'Chad'),
(39, 'Channel Islands'),
(40, 'Chile'),
(41, 'China'),
(42, 'Colombia'),
(43, 'Comoros'),
(44, 'Congo, Dem. Rep.'),
(45, 'Congo, Rep.'),
(46, 'Costa Rica'),
(47, 'Côte d\'Ivoire'),
(48, 'Croatia'),
(49, 'Cuba'),
(50, 'Cyprus'),
(51, 'Czech Republic'),
(52, 'Denmark'),
(53, 'Djibouti'),
(54, 'Dominica'),
(55, 'Dominican Republic'),
(56, 'Ecuador'),
(57, 'Egypt, Arab Rep.'),
(58, 'El Salvador'),
(59, 'Equatorial Guinea'),
(60, 'Eritrea'),
(61, 'Estonia'),
(62, 'Ethiopia'),
(63, 'Faeroe Islands'),
(64, 'Fiji'),
(65, 'Finland'),
(66, 'France'),
(67, 'French Polynesia'),
(68, 'Gabon'),
(69, 'Gambia, The'),
(70, 'Georgia'),
(71, 'Germany'),
(72, 'Ghana'),
(73, 'Greece'),
(74, 'Greenland'),
(75, 'Grenada'),
(76, 'Guam'),
(77, 'Guatemala'),
(78, 'Guinea'),
(79, 'Guinea-Bissau'),
(80, 'Guyana'),
(81, 'Haiti'),
(82, 'Honduras'),
(83, 'Hong Kong, China'),
(84, 'Hungary'),
(85, 'Iceland'),
(86, 'India'),
(87, 'Indonesia'),
(88, 'Iran, Islamic Rep.'),
(89, 'Iraq'),
(90, 'Ireland'),
(91, 'Isle of Man'),
(92, 'Israel'),
(93, 'Italy'),
(94, 'Jamaica'),
(95, 'Japan'),
(96, 'Jordan'),
(97, 'Kazakhstan'),
(98, 'Kenya'),
(99, 'Kiribati'),
(100, 'Korea, Dem. Rep.'),
(101, 'Korea, Rep.'),
(102, 'Kuwait'),
(103, 'Kyrgyz Republic'),
(104, 'Lao PDR'),
(105, 'Latvia'),
(106, 'Lebanon'),
(107, 'Lesotho'),
(108, 'Liberia'),
(109, 'Libya'),
(110, 'Liechtenstein'),
(111, 'Lithuania'),
(112, 'Luxembourg'),
(113, 'Macao, China'),
(114, 'Macedonia, FYR'),
(115, 'Madagascar'),
(116, 'Malawi'),
(117, 'Malaysia'),
(118, 'Maldives'),
(119, 'Mali'),
(120, 'Malta'),
(121, 'Marshall Islands'),
(122, 'Mauritania'),
(123, 'Mauritius'),
(124, 'Mayotte'),
(125, 'Mexico'),
(126, 'Micronesia, Fed. Sts.'),
(127, 'Moldova'),
(128, 'Monaco'),
(129, 'Mongolia'),
(130, 'Montenegro'),
(131, 'Morocco'),
(132, 'Mozambique'),
(133, 'Myanmar'),
(134, 'Namibia'),
(135, 'Nepal'),
(136, 'Netherlands'),
(137, 'Netherlands Antilles'),
(138, 'New Caledonia'),
(139, 'New Zealand'),
(140, 'Nicaragua'),
(141, 'Niger'),
(142, 'Nigeria'),
(143, 'Northern Mariana Islands'),
(144, 'Norway'),
(145, 'Oman'),
(146, 'Pakistan'),
(147, 'Palau'),
(148, 'Panama'),
(149, 'Papua New Guinea'),
(150, 'Paraguay'),
(151, 'Peru'),
(152, 'Philippines'),
(153, 'Poland'),
(154, 'Portugal'),
(155, 'Puerto Rico'),
(156, 'Qatar'),
(157, 'Romania'),
(158, 'Russian Federation'),
(159, 'Rwanda'),
(160, 'Samoa'),
(161, 'San Marino'),
(162, 'São Tomé and Principe'),
(163, 'Saudi Arabia'),
(164, 'Senegal'),
(165, 'Serbia'),
(166, 'Seychelles'),
(167, 'Sierra Leone'),
(168, 'Singapore'),
(169, 'Slovak Republic'),
(170, 'Slovenia'),
(171, 'Solomon Islands'),
(172, 'Somalia'),
(173, 'South Africa'),
(174, 'Spain'),
(175, 'Sri Lanka'),
(176, 'St. Kitts and Nevis'),
(177, 'St. Lucia'),
(178, 'St. Vincent and the Grenadines'),
(179, 'Sudan'),
(180, 'Suriname'),
(181, 'Swaziland'),
(182, 'Sweden'),
(183, 'Switzerland'),
(184, 'Syrian Arab Republic'),
(185, 'Tajikistan'),
(186, 'Tanzania'),
(187, 'Thailand'),
(188, 'Timor-Leste'),
(189, 'Togo'),
(190, 'Tonga'),
(191, 'Trinidad and Tobago'),
(192, 'Tunisia'),
(193, 'Turkey'),
(194, 'Turkmenistan'),
(195, 'Uganda'),
(196, 'Ukraine'),
(197, 'United Arab Emirates'),
(198, 'United Kingdom'),
(199, 'United States'),
(200, 'Uruguay'),
(201, 'Uzbekistan'),
(202, 'Vanuatu'),
(203, 'Venezuela, RB'),
(204, 'Vietnam'),
(205, 'Virgin Islands (U.S.)'),
(206, 'West Bank and Gaza'),
(207, 'Yemen, Rep.'),
(208, 'Zambia'),
(209, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Data_Rule`
--

DROP TABLE IF EXISTS `tbl_MARS_Data_Rule`;
CREATE TABLE `tbl_MARS_Data_Rule` (
  `rules_ID` int(11) NOT NULL,
  `rules_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Degree`
--

DROP TABLE IF EXISTS `tbl_MARS_Degree`;
CREATE TABLE `tbl_MARS_Degree` (
  `degree_ID` int(11) NOT NULL,
  `degree_Name` varchar(10) DEFAULT NULL,
  `degree_Order` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Degree`
--

TRUNCATE TABLE `tbl_MARS_Degree`;
--
-- Dumping data for table `tbl_MARS_Degree`
--

INSERT INTO `tbl_MARS_Degree` (`degree_ID`, `degree_Name`, `degree_Order`) VALUES
(1, 'PhD', 0),
(2, 'MA', 0),
(3, 'BA', 0),
(4, 'BS', 0),
(5, 'AA', 0),
(6, 'AS', 0),
(7, 'MD', 0),
(8, 'JSD', 0),
(9, 'JD', 0),
(10, 'MS', 0),
(11, 'Non-Degree', 99),
(12, 'Other', 98),
(13, 'EdD', 0),
(14, 'CD', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Demographic_Data`
--

DROP TABLE IF EXISTS `tbl_MARS_Demographic_Data`;
CREATE TABLE `tbl_MARS_Demographic_Data` (
  `demog_id` int(11) NOT NULL,
  `demog_source_ID` int(11) DEFAULT '0',
  `demog_disability` int(11) DEFAULT '0',
  `demog_disability_other` varchar(50) DEFAULT NULL,
  `demog_ethnicity` int(11) DEFAULT '0',
  `demog_race` int(11) DEFAULT '0',
  `demog_citizenship` int(11) DEFAULT '0',
  `demog_gender` int(11) DEFAULT '0',
  `demog_datetimestamp` datetime DEFAULT NULL,
  `demog_submittedby` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Disability`
--

DROP TABLE IF EXISTS `tbl_MARS_Disability`;
CREATE TABLE `tbl_MARS_Disability` (
  `disability_ID` int(11) NOT NULL,
  `disability_Name` varchar(50) DEFAULT NULL,
  `disability_Sort` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Disability`
--

TRUNCATE TABLE `tbl_MARS_Disability`;
--
-- Dumping data for table `tbl_MARS_Disability`
--

INSERT INTO `tbl_MARS_Disability` (`disability_ID`, `disability_Name`, `disability_Sort`) VALUES
(1, 'None', 99),
(2, 'Hearing impairment', NULL),
(3, 'Visual impairment', NULL),
(4, 'Mobility/orthopedic impairment', NULL),
(5, 'Other impairment', 98);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Eligible_PIs`
--

DROP TABLE IF EXISTS `tbl_MARS_Eligible_PIs`;
CREATE TABLE `tbl_MARS_Eligible_PIs` (
  `pi_ID` int(11) NOT NULL,
  `pi_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Eligible_PIs`
--

TRUNCATE TABLE `tbl_MARS_Eligible_PIs`;
--
-- Dumping data for table `tbl_MARS_Eligible_PIs`
--

INSERT INTO `tbl_MARS_Eligible_PIs` (`pi_ID`, `pi_Name`) VALUES
(1, 'All'),
(2, 'Faculty'),
(3, 'Sr. Academic Official');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Entities`
--

DROP TABLE IF EXISTS `tbl_MARS_Entities`;
CREATE TABLE `tbl_MARS_Entities` (
  `entity_ID` int(11) NOT NULL,
  `entity_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Entities`
--

TRUNCATE TABLE `tbl_MARS_Entities`;
--
-- Dumping data for table `tbl_MARS_Entities`
--

INSERT INTO `tbl_MARS_Entities` (`entity_ID`, `entity_Name`) VALUES
(1, 'PUI'),
(2, 'Non-PUI'),
(3, 'PUI and Non-PUI');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Ethnicity`
--

DROP TABLE IF EXISTS `tbl_MARS_Ethnicity`;
CREATE TABLE `tbl_MARS_Ethnicity` (
  `ethnicity_ID` int(11) NOT NULL,
  `ethnicity_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Ethnicity`
--

TRUNCATE TABLE `tbl_MARS_Ethnicity`;
--
-- Dumping data for table `tbl_MARS_Ethnicity`
--

INSERT INTO `tbl_MARS_Ethnicity` (`ethnicity_ID`, `ethnicity_Name`) VALUES
(1, 'Hispanic or Latino'),
(2, 'Not Hispanic or Latino'),
(3, 'Not Provided');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_FIPS_Cities_WV`
--

DROP TABLE IF EXISTS `tbl_MARS_FIPS_Cities_WV`;
CREATE TABLE `tbl_MARS_FIPS_Cities_WV` (
  `city_ID` int(11) NOT NULL,
  `FIPS_City` varchar(255) DEFAULT NULL,
  `City_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_FIPS_Codes`
--

DROP TABLE IF EXISTS `tbl_MARS_FIPS_Codes`;
CREATE TABLE `tbl_MARS_FIPS_Codes` (
  `FIPS_ID` int(11) NOT NULL,
  `FIPS_State` varchar(255) DEFAULT NULL,
  `FIPS_County` varchar(255) DEFAULT NULL,
  `FIPS_Combined` varchar(255) DEFAULT NULL,
  `State_Abbreviation` varchar(255) DEFAULT NULL,
  `County_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Focus_Areas`
--

DROP TABLE IF EXISTS `tbl_MARS_Focus_Areas`;
CREATE TABLE `tbl_MARS_Focus_Areas` (
  `focus_ID` int(11) NOT NULL,
  `focus_area_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Gender`
--

DROP TABLE IF EXISTS `tbl_MARS_Gender`;
CREATE TABLE `tbl_MARS_Gender` (
  `gender_ID` int(11) NOT NULL,
  `gender_Name` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Gender`
--

TRUNCATE TABLE `tbl_MARS_Gender`;
--
-- Dumping data for table `tbl_MARS_Gender`
--

INSERT INTO `tbl_MARS_Gender` (`gender_ID`, `gender_Name`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Grant_Type`
--

DROP TABLE IF EXISTS `tbl_MARS_Grant_Type`;
CREATE TABLE `tbl_MARS_Grant_Type` (
  `type_ID` int(11) NOT NULL,
  `type_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Grant_Type`
--

TRUNCATE TABLE `tbl_MARS_Grant_Type`;
--
-- Dumping data for table `tbl_MARS_Grant_Type`
--

INSERT INTO `tbl_MARS_Grant_Type` (`type_ID`, `type_Name`) VALUES
(1, 'One-Year'),
(2, 'Multi-Year'),
(3, 'Three-Year'),
(4, 'Four-Year'),
(5, 'Five-Year');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Inst`
--

DROP TABLE IF EXISTS `tbl_MARS_Inst`;
CREATE TABLE `tbl_MARS_Inst` (
  `inst_ID` int(11) NOT NULL,
  `inst_Name` varchar(255) DEFAULT NULL,
  `inst_Street` varchar(50) DEFAULT NULL,
  `inst_City` varchar(50) DEFAULT NULL,
  `inst_State` int(11) DEFAULT '0',
  `inst_Zip` int(11) DEFAULT '0',
  `inst_Zip_4` int(11) DEFAULT '0',
  `inst_Country` int(11) UNSIGNED DEFAULT '0',
  `inst_AuthRep` varchar(128) DEFAULT NULL,
  `inst_AuthRep_Contact` varchar(255) DEFAULT NULL,
  `inst_FO` varchar(128) DEFAULT NULL,
  `inst_FO_Contact` varchar(255) DEFAULT NULL,
  `inst_submittedby` int(11) DEFAULT NULL,
  `inst_datetimestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Inst`
--

TRUNCATE TABLE `tbl_MARS_Inst`;
--
-- Dumping data for table `tbl_MARS_Inst`
--

INSERT INTO `tbl_MARS_Inst` (`inst_ID`, `inst_Name`, `inst_Street`, `inst_City`, `inst_State`, `inst_Zip`, `inst_Zip_4`, `inst_Country`, `inst_AuthRep`, `inst_AuthRep_Contact`, `inst_FO`, `inst_FO_Contact`, `inst_submittedby`, `inst_datetimestamp`) VALUES
(1, 'Alderson-Broaddus College', '101 College Hill Drive', 'Philippi', 63, 26416, 0, 199, NULL, NULL, NULL, NULL, 275, NULL),
(2, 'Appalachian Bible College', 'P.O. Box ABC', 'Bradley', 63, 25818, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Arizona State University', NULL, NULL, 4, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Universidad Autonoma de Madrid', NULL, NULL, NULL, 28049, NULL, 174, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'AVYD Devices', NULL, 'Costa Mesa', 12, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Bethany College', 'Main Street', 'Bethany', 63, 26032, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Bluefield State College', '219 Rock Street', 'Bluefield', 63, 24701, 0, 199, '', '', 'Shelia Johnson', 'sjohnson@bluefieldstate.edu', NULL, NULL),
(8, 'Brigham Young University', NULL, NULL, 58, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'Carnegie Mellon University', NULL, NULL, 51, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'Colorado State University', NULL, NULL, 13, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'Concord University', 'PO Box 1000', 'Athens', 63, 24712, 0, 199, 'Victoria Blankenship', 'blankvl@concord.edu', 'Melanie Farmer', 'mfarmer@concord.edu', NULL, NULL),
(12, 'Cornell University', NULL, NULL, 43, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'Czech Academy of Sciences', NULL, NULL, NULL, NULL, NULL, 51, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'Davis & Elkins College', '100 Campus Drive', 'Elkins', 63, 26241, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'DoE/National Energy Technology Laboratory', NULL, NULL, 51, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 'Eastern WV Community and Technical College', '1929 SR55', 'Moorefield', 63, 26836, 0, 199, '', '', 'Penny Reardon', 'reardon@eastern.wvnet.edu', NULL, NULL),
(17, 'Fairmont State Community & Technical College', '1201 Locust Avenue', 'Fairmont', 63, 26554, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'Fairmont State University', '1201 Locust Avenue', 'Fairmont', 63, 26554, 0, 199, 'Debbie Stiles', 'deborah.stiles@fairmontstate.edu', 'Sandy Shriver', 'sandra.shriver@fairmontstate.edu', NULL, NULL),
(19, 'Georgia Institute of Technology', NULL, NULL, 19, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'Glenville State College', '200 High Street', 'Glenville', 63, 26351, 0, 199, 'Dr. Gary Morris', 'Gary.Morris@glenville.edu', 'John Beckvold', 'john.beckvold@glenville.edu', NULL, NULL),
(21, 'Gunma University', '4-2 Aramaki-machi', 'Maebashi City, Gunma', NULL, 371, 8510, 95, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'Harvard University', NULL, NULL, 32, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'Kansas State University', NULL, NULL, 26, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'Lawrence Berkeley National Laboratory', NULL, NULL, 12, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'Los Alamos National Laboratory', NULL, NULL, 42, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'Lumei Optoelectronics', NULL, NULL, NULL, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 'Marshall Community and Technical College', 'One John Marshall Drive', 'Huntington', 63, 25755, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 'Marshall University', '401 11th Street, Suite 1400', 'Huntington', 63, 25701, 0, 199, 'Dr. John Maher', 'maherj@marshall.edu', 'Joe Ciccarello', 'ciccarello@marshall.edu ', NULL, NULL),
(30, 'Metrica, Inc.', NULL, NULL, 13, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 'Monash University', NULL, 'Victoria', NULL, 3800, NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Mountain State University', 'PO Box 9003', 'Beckley', 63, 25802, 0, 199, 'Michele Sarrett', 'msarrett@mountainstate.edu', 'Stephanie Allard', 'sallard@mountainstate.edu', NULL, NULL),
(33, 'National Institute of Standards and Technology', NULL, NULL, 31, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'National Youth Science Foundation', 'PO Box 3387', 'Charleston', 63, 25333, 0, 199, 'Andrew Blackwood', 'andrew.blackwood@nysf.com', 'Darcie Boschee', 'darcie.boschee@nysf.com', NULL, NULL),
(35, 'NIH/NCI', NULL, NULL, 31, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'NIOSH', NULL, NULL, 19, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'North Carolina State University', NULL, NULL, 44, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 'Ohio University', NULL, NULL, 47, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 'Ohio Valley University', '1 Campus View Drive', 'Vienna', 63, 26105, 8000, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Parabon Computation, Inc', '11260 Roger Bacon Dr. Suite 406', 'Reston', 61, 20190, 0, 199, '', '', '', '', NULL, NULL),
(41, 'Penn State', NULL, NULL, 51, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'Potomac State College of WVU', '101 Fort Ave', 'Keyser', 63, 26726, 0, 199, '', '', 'Harlan Shreve', 'hnshreve@mail.wvu.edu', NULL, NULL),
(43, 'Princeton University', NULL, NULL, 41, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'Protea Bioscience Inc.', NULL, NULL, 63, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'Salem International University', '223 W Main Street', 'Salem', 63, 26426, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 'Shepherd University', 'P.O. Box 3210', 'Shepherdstown', 63, 25443, 0, 199, 'Tracy Bateman', 'tbateman@shepherd.edu', 'Jessica Kump', 'jkump@shepherd.edu', NULL, '2013-09-27 14:45:22'),
(47, 'Southern WV Community and Technical College', '2900 Dempsey Branch Road', 'Mount Gay', 63, 25367, 0, 199, '', '', 'Samuel Litteral', 'saml@southern.wvnet.edu', NULL, NULL),
(48, 'Stony Brook University', NULL, NULL, 43, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 'Texas A&M', NULL, NULL, 57, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 'University of Massachusetts Amherst', NULL, 'Amherst', 32, 1003, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 'University of California - Riverside', NULL, NULL, 12, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 'University of Canterbury', 'Private Bag 4800', 'Christchurch', NULL, 8140, NULL, 139, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 'University of Charleston', '2300 MacCorkle Ave SE', 'Charleston', 63, 25304, NULL, 199, 'Cleta Harless', 'cletarharless@ucwv.edu', 'Cleta Harless', 'cletarharless@ucwv.edu', NULL, NULL),
(54, 'University of Florida', NULL, NULL, 18, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 'University of New Mexico', NULL, NULL, 42, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 'University of North Carolina - Chapel Hill', NULL, NULL, 44, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 'University of Pittsburgh', NULL, NULL, 51, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 'University of South Carolina', NULL, NULL, 54, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 'University of Virginia', NULL, NULL, 61, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 'University of Washington', NULL, NULL, 62, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 'Virginia Commonwealth University', NULL, NULL, 61, NULL, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 'West Liberty University', '208 University Dr, CUB 133', 'West Liberty', 63, 26074, 0, 199, 'Roberta Linger', 'roberta.linger@westliberty.edu', 'Laura Musilli', 'laura.musilli@westliberty.edu ', NULL, '2014-07-08 17:39:59'),
(64, 'West Virginia Northern CTC', '1704 Market Street', 'Wheeling', 63, 26003, 0, 199, '', '', 'Lawrence E. Bandi', 'lbandi@northern.wvnet.edu', NULL, NULL),
(65, 'West Virginia School of Osteopathic Medicine', '400 N. Lee Street', 'Lewisburg', 63, 24901, 0, 199, '', '', 'Richard Rollins', 'rrollins@wvsom.edu', NULL, NULL),
(66, 'West Virginia State Community and Technical College', 'P.O. Box 1000', 'Institute', 63, 25112, 1000, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 'West Virginia State University ', 'P.O. Box 1000', 'Institute', 63, 25112, 0, 199, 'Dr. Orlando McMeans', 'mcmeanso@wvstateu.edu', 'Amy', 'grants@wvstateu.edu', NULL, NULL),
(68, 'West Virginia University ', '886 Chestnut Ridge Road  P.O. Box 6845', 'Morgantown', 63, 26506, 0, 199, 'Fred King', 'fred.king@mail.wvu.edu', 'Office of Sponsored Programs', 'WVUOSP@mail.wvu.edu', NULL, NULL),
(69, 'West Virginia University at Parkersburg', '300 Campus Drive', 'Parkersburg', 63, 26104, 0, 199, '', '', 'Jack Simpkins', 'jack.simpkins@mail.wvu.edu', NULL, NULL),
(70, 'West Virginia University Institute of Technology', '', '', 63, 0, 0, 199, 'Vanessa Williams', 'vmwilliams@mail.wvu.edu', 'WVU Office of Sponsored Programs', 'WVUOSP@mail.wvu.edu', NULL, '2014-02-03 16:26:04'),
(71, 'West Virginia Wesleyan College', '59 College Avenue', 'Buckhannon', 63, 26201, 0, 199, '', '', 'Nicki Bentley-Colthart', 'bentley-colthart@wvwc.edu', NULL, NULL),
(72, 'Wheeling Jesuit University', '316 Washington Avenue', 'Wheeling', 63, 26003, 0, 199, '', '', 'Theresa Parsons', 'tparsons@wju.edu', NULL, NULL),
(73, 'WVEPSCoR', '1018 Kanawha Blvd E', 'Charleston', 63, 25301, 0, 199, 'Dr. Jan Taylor', 'jan.taylor@wvresearch.org', 'Annette Carpenter', 'annette.carpenter@wvresearch.org', NULL, NULL),
(74, 'WVU Community and Technical College at Institute of Technology', '405 Fayette Pike', 'Montgomery', 63, 25136, NULL, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 'Rand Corporation', '1200 South Hayes Street', 'Arlington', 61, 22202, 0, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 'WV Legislature', '2300 Kanawha Blvd', 'Charleston', 63, 25301, 0, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 'Rose Shaw Consulting', '123 Anstreet', 'A City', 13, 123456, 0, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 'New River Community and Technical College', '167 Dye Drive', 'Beckley', 63, 25801, 0, 199, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 'Blue Ridge Community and Technical College', '400 West Stephen Street', 'Martinsburg', 63, 25401, 0, 199, '', '', 'Scott Stephenson', 'sstephenson@bluefieldstate.edu', NULL, NULL),
(81, 'Division of Science and Research - HEPC', '1018 Kanawha Blvd E', 'Charleston', 63, 25301, 0, 199, 'Dr. Jan Taylor', 'jan.taylor@wvresearch.org', 'Annette Carpenter', 'annette.carpenter@wvresearch.org', 275, '2008-08-20 16:41:02'),
(82, 'National Radio Astronomy Observatory', 'P.O. Box 295', 'Green Bank', 63, 24944, 0, 199, '', '', 'Michael Holstine, Business Manager', '', 275, '2008-08-28 16:49:23'),
(83, 'Marshall University Research Corporation', '401 11th Street, Suite 1400', 'Huntington', 63, 25701, 0, 199, 'John Maher', 'maherj@marshall.edu', 'Joseph Ciccarello', 'ciccarello@marshall.edu ', 301, '2008-09-03 22:33:47'),
(84, 'University of Kentucky', '410 Administration Drive', 'Lexington', 27, 40506, 0, 199, '', '', '', '', 275, '2008-11-05 16:11:54'),
(85, 'Albany State University', '', 'Albany', 19, 31705, 0, 199, '', '', '', '', 275, '2008-11-24 18:48:21'),
(86, 'Hillsdale College', '33 East College St', 'Hillsdale', 33, 49242, 0, 199, '', '', '', '', 275, '2008-11-24 18:50:27'),
(87, 'Grove City College', '100 Campus Drive', 'Grove City', 51, 16127, 0, 199, '', '', '', '', 275, '2008-11-24 18:54:11'),
(88, 'Universite du Quebec Institut national de la recherche scientifique', '1650 Boulevard, Lionel-Boulet', 'Varennes, QuÃ©bec', 0, 0, 0, 34, '', '', '', '', 275, '2008-11-25 17:46:10'),
(89, 'TDI, Inc.', '12214 Plum Orchard Drive', 'Silver Spring', 31, 20904, 0, 199, '', '', '', '', 275, '2008-11-25 18:42:21'),
(90, 'University of Connecticut', '', 'Storrs', 14, 6269, 0, 199, '', '', '', '', 275, '2008-11-25 22:04:10'),
(91, 'University of Minnesota', '1701 University Ave. S.E.', 'Minneapolis', 34, 55414, 0, 199, '', '', '', '', 275, '2008-12-01 17:56:10'),
(92, 'Northeastern University', '', 'Boston', 32, 2115, 0, 199, '', '', '', '', 275, '2008-12-01 18:00:32'),
(93, 'Florida State University', '', 'Tallahassee', 18, 0, 0, 199, '', '', '', '', 275, '2008-12-01 18:04:30'),
(94, 'University of Alabama', '', 'Tuscaloosa', 1, 35487, 0, 199, '', '', '', '', 275, '2008-12-01 18:07:31'),
(95, 'SUNY - Buffalo', '', 'Buffalo', 43, 14260, 0, 199, '', '', '', '', 275, '2008-12-01 18:12:26'),
(96, 'Pontificia Universidad Catolica de Chile', '', 'Santiago', 0, 0, 0, 40, '', '', '', '', 275, '2008-12-01 20:52:20'),
(97, 'Stanford Synchrotron Research Laboratory', '', 'Menlo Park', 12, 94025, 0, 199, '', '', '', '', 275, '2008-12-01 21:04:48'),
(98, 'Appalachian State University', '', 'Boone', 44, 28608, 0, 199, '', '', '', '', 275, '2008-12-01 21:17:47'),
(99, 'Brown-Hott Foundation', '', '', 0, 0, 0, 0, '', '', '', '', 275, '2008-12-02 17:10:05'),
(100, 'Chongqing University', '', 'Chongqing', 0, 0, 0, 41, '', '', '', '', 275, '2008-12-03 17:01:24'),
(101, 'Pierre and Marie Curie University', '4 place jussieu', 'Paris', 0, 0, 0, 66, '', '', '', '', 275, '2008-12-03 18:09:54'),
(102, 'University of Utah', '201 Presidents Circle', 'Salt Lake City', 58, 84112, 0, 199, '', '', '', '', 275, '2008-12-03 18:21:53'),
(103, 'California Institute of Technology', '1200 East California Blvd', 'Pasadena', 12, 91125, 0, 199, '', '', '', '', 275, '2008-12-03 18:47:50'),
(104, 'Marshall University Joan C. Edwards School of Medicine', '1600 Medical Center Drive', 'Huntington', 63, 25701, 0, 199, '', '', '', '', 275, '2008-12-03 22:18:12'),
(105, 'Stanford University', '', '', 0, 0, 0, 0, '', '', '', '', 84, '2009-01-07 20:18:04'),
(107, 'Iowa State University', 'Curtiss Hall', 'Ames', 25, 5001, 0, 199, '', '', '', '', 275, '2009-01-26 21:59:38'),
(108, 'University of Montana', '32 Campus Drive', 'Missoula', 37, 59812, 0, 199, '', '', '', '', 275, '2009-01-26 23:05:51'),
(109, 'Nankai University', '', 'Tianjin', 0, 200071, 0, 41, '', '', '', '', 275, '2009-01-28 17:16:33'),
(110, 'Invenlux Corp.', '', 'El Monte', 12, 91731, 0, 199, '', '', '', '', 275, '2009-01-28 17:49:33'),
(111, 'Japan Society for the Promotion of Science', 'Sumitomo-Ichibancho Bldg., 6 Ichibancho', 'Chiyoda-ku,Tokyo', 0, 102, 0, 95, '', '', '', '', 275, '2009-01-28 21:26:42'),
(112, 'University of Texas', 'One UTSA Circle', 'San Antonio', 57, 78249, 0, 0, '', '', '', '', 275, '2009-01-28 22:33:20'),
(113, 'John Carroll University', '', '', 0, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:17:41'),
(114, 'Grinnell College', '', '', 0, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:33:51'),
(115, 'Alabama A&M', '', 'Normal', 1, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:38:08'),
(116, 'Campbell University', '', 'Buies Creek', 44, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:39:47'),
(117, 'Western Carolina University', '', 'Cullowhee', 44, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:41:01'),
(118, 'New Jersey Institute of Technology', '', 'Newark', 41, 0, 0, 199, '', '', '', '', 706, '2010-02-13 02:44:23'),
(119, 'Coppin State University', '', '', 0, 0, 0, 0, '', '', '', '', 299, '2010-08-05 18:26:05'),
(120, 'Norfolk State University', '', 'Norfolk', 61, 0, 0, 0, '', '', '', '', 706, '2010-08-10 22:23:42'),
(121, 'Winston-Salem State University', '', 'Winston-Salem', 44, 0, 0, 0, '', '', '', '', 706, '2010-08-10 22:24:31'),
(122, 'American River College', '', 'Sacramento', 12, 0, 0, 0, '', '', '', '', 706, '2010-08-10 22:24:55'),
(123, 'St. Francis University', '', 'Loretto', 51, 0, 0, 199, '', '', '', '', 706, '2010-08-10 22:28:10'),
(124, 'Coppin State University', '', 'Baltimore', 31, 0, 0, 199, '', '', '', '', 706, '2010-08-10 22:29:04'),
(125, 'York College of Pennsylvania', '', 'York', 51, 0, 0, 199, '', '', '', '', 706, '2010-08-10 22:33:36'),
(126, 'Erskine College', '', 'Due West', 54, 0, 0, 199, '', '', '', '', 706, '2010-08-10 22:34:11'),
(129, 'Bridgemont Community and Technical College', '619 Second Avenue', 'Montgomery', 63, 25136, 0, 199, '', '', '', '', 1357, '2012-02-26 18:42:47'),
(130, 'TechConnect', '1740 Union Carbide Drive, Room 4203', 'South Charleston', 63, 25303, 0, 199, 'Anne Barth', '', 'Anne Barth', '', 9, '2012-08-15 17:50:53'),
(131, 'WV State University ', 'PO Box 1000', 'institute', 63, 25112, 0, 199, 'Dr. Orlando F. McMeans', 'mcmeanso@wvstateu.edu', 'Brunetta Gamble-Dillard', 'bdillard@wvstateu.edu', 1472, '2013-10-14 14:11:03'),
(132, 'Unknown', 'Unknown', 'Unknown ', 63, 25301, 0, 199, 'Jan Taylor', 'jan.taylor@wvresearch.org', 'Annette Carpenter', 'annette.carpenter@wvresearch.org', 1345, '2018-02-07 16:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Instrument`
--

DROP TABLE IF EXISTS `tbl_MARS_Instrument`;
CREATE TABLE `tbl_MARS_Instrument` (
  `instrument_ID` int(11) NOT NULL,
  `instrument_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Instrument`
--

TRUNCATE TABLE `tbl_MARS_Instrument`;
--
-- Dumping data for table `tbl_MARS_Instrument`
--

INSERT INTO `tbl_MARS_Instrument` (`instrument_ID`, `instrument_Name`) VALUES
(1, 'Short form'),
(2, 'Long form'),
(3, 'Contract');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Participants`
--

DROP TABLE IF EXISTS `tbl_MARS_Participants`;
CREATE TABLE `tbl_MARS_Participants` (
  `participant_ID` int(11) NOT NULL,
  `people_ID` int(11) DEFAULT '0',
  `report_data_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0',
  `participant_submittedby` int(11) DEFAULT NULL,
  `participant_datetimestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Participant_Data`
--

DROP TABLE IF EXISTS `tbl_MARS_Participant_Data`;
CREATE TABLE `tbl_MARS_Participant_Data` (
  `part_data_ID` int(11) NOT NULL,
  `people_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0',
  `part_data_Role` int(11) DEFAULT '0',
  `part_data_SysRole` int(11) DEFAULT NULL,
  `part_data_160Hours` tinyint(4) DEFAULT '0',
  `part_data_Involvement` text,
  `part_data_GrantLeverage` double DEFAULT '0',
  `part_data_OtherLeverage` double DEFAULT '0',
  `part_data_InstFundMatch` double DEFAULT '0',
  `part_data_SubmittedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_PartnerOrgs`
--

DROP TABLE IF EXISTS `tbl_MARS_PartnerOrgs`;
CREATE TABLE `tbl_MARS_PartnerOrgs` (
  `org_ID` int(11) NOT NULL,
  `report_data_ID` int(11) DEFAULT '0',
  `inst_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_PartnerOrgs_Data`
--

DROP TABLE IF EXISTS `tbl_MARS_PartnerOrgs_Data`;
CREATE TABLE `tbl_MARS_PartnerOrgs_Data` (
  `org_data_ID` int(11) NOT NULL,
  `inst_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0',
  `report_data_ID` int(11) DEFAULT '0',
  `org_data_Financial` tinyint(4) DEFAULT '0',
  `org_data_InKind` tinyint(4) DEFAULT '0',
  `org_data_Facilities` tinyint(4) DEFAULT '0',
  `org_data_CollabRes` tinyint(4) DEFAULT '0',
  `org_data_Personnel` tinyint(4) DEFAULT '0',
  `org_data_Narrative` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_People`
--

DROP TABLE IF EXISTS `tbl_MARS_People`;
CREATE TABLE `tbl_MARS_People` (
  `people_ID` int(11) NOT NULL,
  `people_Title` varchar(50) DEFAULT NULL,
  `people_LastName` varchar(50) DEFAULT NULL,
  `people_FirstName` varchar(50) DEFAULT NULL,
  `people_Preferred_Name` varchar(50) DEFAULT NULL,
  `people_MI` varchar(50) DEFAULT NULL,
  `people_Suffix` varchar(50) DEFAULT NULL,
  `people_Degree` int(11) DEFAULT '0',
  `people_Dept1` int(11) DEFAULT '0',
  `people_Dept2` varchar(50) DEFAULT NULL,
  `people_Add1` varchar(50) DEFAULT NULL,
  `people_Add2` varchar(50) DEFAULT NULL,
  `people_Add3` varchar(50) DEFAULT NULL,
  `people_City` varchar(50) DEFAULT NULL,
  `people_State` int(11) DEFAULT '0',
  `people_Country` int(11) DEFAULT NULL,
  `people_Zip` int(11) DEFAULT '0',
  `people_Zip_4` int(11) DEFAULT '0',
  `people_OfficePhone` varchar(50) DEFAULT NULL,
  `people_OfficePhoneExt` varchar(50) DEFAULT NULL,
  `people_DeptPhone` varchar(50) DEFAULT NULL,
  `people_DeptPhoneExt` varchar(50) DEFAULT NULL,
  `people_Fax` varchar(50) DEFAULT NULL,
  `people_FaxExt` varchar(50) DEFAULT NULL,
  `people_HomePhone` varchar(50) DEFAULT NULL,
  `people_Email` varchar(255) DEFAULT NULL,
  `people_Alt_Email` varchar(255) DEFAULT NULL,
  `people_URL` varchar(254) DEFAULT NULL,
  `people_Graduate` tinyint(1) DEFAULT NULL,
  `people_CareerPlan` int(11) DEFAULT '0',
  `people_submittedby` int(11) DEFAULT NULL,
  `people_datetimestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Proposals`
--

DROP TABLE IF EXISTS `tbl_MARS_Proposals`;
CREATE TABLE `tbl_MARS_Proposals` (
  `people_ID` int(11) DEFAULT '0',
  `proposal_CoPI` int(11) DEFAULT NULL,
  `proposal_ID` int(11) NOT NULL,
  `ann_ID` int(11) DEFAULT '0',
  `proposal_Name` varchar(255) DEFAULT NULL,
  `proposal_Summary` text,
  `proposal_Description` text,
  `proposal_FBBG` tinyint(4) UNSIGNED DEFAULT NULL,
  `proposal_Certified` tinyint(4) UNSIGNED DEFAULT NULL,
  `proposal_SubmitFinal` tinyint(4) UNSIGNED DEFAULT NULL,
  `proposal_datetimestamp` datetime DEFAULT NULL,
  `proposal_Attachment_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_Attachment_StoredName` varchar(255) DEFAULT NULL,
  `proposal_Budget_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_Budget_StoredName` varchar(255) DEFAULT NULL,
  `proposal_Quotes_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_Quotes_StoredName` varchar(255) DEFAULT NULL,
  `proposal_LoS_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_LoS_StoredName` varchar(255) DEFAULT NULL,
  `proposal_OtherDocs_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_OtherDocs_StoredName` varchar(255) DEFAULT NULL,
  `proposal_MentorBio_DisplayName` varchar(255) DEFAULT NULL,
  `proposal_MentorBio_StoredName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Pubs`
--

DROP TABLE IF EXISTS `tbl_MARS_Pubs`;
CREATE TABLE `tbl_MARS_Pubs` (
  `pub_ID` int(11) NOT NULL,
  `pub_Type` int(11) DEFAULT '0',
  `report_data_ID` int(11) DEFAULT '0',
  `pub_Title` varchar(255) DEFAULT NULL,
  `pub_Editors` varchar(255) DEFAULT NULL,
  `pub_Collection_Title` varchar(255) DEFAULT NULL,
  `pub_Bib_Info` text,
  `pub_Year` varchar(50) DEFAULT NULL,
  `pub_Volume` varchar(50) DEFAULT NULL,
  `pub_Pages` varchar(50) DEFAULT NULL,
  `pub_DOI` varchar(50) DEFAULT NULL,
  `pub_Status` int(11) DEFAULT '0',
  `pub_Status_Other` varchar(255) DEFAULT NULL,
  `pub_NSF_Ack` tinyint(4) DEFAULT '0',
  `pub_Grant_Agency` int(11) UNSIGNED DEFAULT NULL,
  `pub_DescriptionURL` text,
  `pub_award_Amount` int(11) UNSIGNED DEFAULT NULL,
  `pub_RelationSharing` text,
  `pub_submittedby` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Pub_Authors`
--

DROP TABLE IF EXISTS `tbl_MARS_Pub_Authors`;
CREATE TABLE `tbl_MARS_Pub_Authors` (
  `author_ID` int(11) NOT NULL,
  `people_ID` int(11) DEFAULT '0',
  `pub_ID` int(11) DEFAULT '0',
  `author_Order` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Pub_Status`
--

DROP TABLE IF EXISTS `tbl_MARS_Pub_Status`;
CREATE TABLE `tbl_MARS_Pub_Status` (
  `status_ID` int(11) NOT NULL,
  `status_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Pub_Status`
--

TRUNCATE TABLE `tbl_MARS_Pub_Status`;
--
-- Dumping data for table `tbl_MARS_Pub_Status`
--

INSERT INTO `tbl_MARS_Pub_Status` (`status_ID`, `status_Name`) VALUES
(1, 'Submitted'),
(2, 'Accepted'),
(3, 'Published'),
(4, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Pub_Type`
--

DROP TABLE IF EXISTS `tbl_MARS_Pub_Type`;
CREATE TABLE `tbl_MARS_Pub_Type` (
  `type_ID` int(11) NOT NULL,
  `type_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Pub_Type`
--

TRUNCATE TABLE `tbl_MARS_Pub_Type`;
--
-- Dumping data for table `tbl_MARS_Pub_Type`
--

INSERT INTO `tbl_MARS_Pub_Type` (`type_ID`, `type_Name`) VALUES
(1, 'Book'),
(3, 'Thesis or Dissertation'),
(4, 'Internet'),
(5, 'Data or Database'),
(6, 'Physical collection'),
(7, 'Audio/video'),
(8, 'Software/netware'),
(9, 'Educational aid'),
(10, 'Instrument or equipment'),
(11, 'Invention, Other'),
(12, 'Patent Application'),
(13, 'Patent'),
(14, 'Invention Disclosure'),
(15, 'Refereed Publication'),
(21, 'Billboard'),
(22, 'Book Chapter'),
(23, 'Startup Company'),
(24, 'Proposal, Interdisciplinary'),
(25, 'Proposal'),
(26, 'Award'),
(27, 'Award, Interdisciplinary'),
(28, 'Award, Continuing');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Race`
--

DROP TABLE IF EXISTS `tbl_MARS_Race`;
CREATE TABLE `tbl_MARS_Race` (
  `race_ID` int(11) NOT NULL,
  `race_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Race`
--

TRUNCATE TABLE `tbl_MARS_Race`;
--
-- Dumping data for table `tbl_MARS_Race`
--

INSERT INTO `tbl_MARS_Race` (`race_ID`, `race_Name`) VALUES
(1, 'American Indian or Alaskan Native'),
(2, 'Asian'),
(3, 'Black or African American'),
(4, 'Native Hawaiian or other Pacific Islander'),
(5, 'White');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Report_Data`
--

DROP TABLE IF EXISTS `tbl_MARS_Report_Data`;
CREATE TABLE `tbl_MARS_Report_Data` (
  `report_data_ID` int(11) NOT NULL,
  `people_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT '0',
  `report_period_ID` int(11) DEFAULT '0',
  `report_data_datetimestamp` datetime DEFAULT NULL,
  `report_data_submittedby` int(11) DEFAULT NULL,
  `report_SubmitFinal` tinyint(4) UNSIGNED DEFAULT NULL,
  `report_attachment_DisplayName` varchar(255) DEFAULT NULL,
  `report_attachment_StoredName` varchar(255) DEFAULT NULL,
  `report_financial_DisplayName` varchar(255) DEFAULT NULL,
  `report_financial_StoredName` varchar(255) DEFAULT NULL,
  `report_approvedBy` int(11) DEFAULT NULL,
  `report_approvedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Report_Periods`
--

DROP TABLE IF EXISTS `tbl_MARS_Report_Periods`;
CREATE TABLE `tbl_MARS_Report_Periods` (
  `report_period_ID` int(11) NOT NULL,
  `report_period` varchar(40) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Report_Periods`
--

TRUNCATE TABLE `tbl_MARS_Report_Periods`;
--
-- Dumping data for table `tbl_MARS_Report_Periods`
--

INSERT INTO `tbl_MARS_Report_Periods` (`report_period_ID`, `report_period`) VALUES
(1, 'Monthly'),
(2, 'Quarterly'),
(3, 'Semi-Annually'),
(4, 'Annually');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Report_Roles`
--

DROP TABLE IF EXISTS `tbl_MARS_Report_Roles`;
CREATE TABLE `tbl_MARS_Report_Roles` (
  `report_role_ID` int(11) NOT NULL,
  `report_role_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Report_Roles`
--

TRUNCATE TABLE `tbl_MARS_Report_Roles`;
--
-- Dumping data for table `tbl_MARS_Report_Roles`
--

INSERT INTO `tbl_MARS_Report_Roles` (`report_role_ID`, `report_role_Name`) VALUES
(1, 'FBBG Member'),
(2, 'FBBG Leader'),
(3, 'Technical Co-PI'),
(4, 'Coordinator'),
(5, 'State Director');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Reviewer`
--

DROP TABLE IF EXISTS `tbl_MARS_Reviewer`;
CREATE TABLE `tbl_MARS_Reviewer` (
  `rev_ID` int(11) NOT NULL,
  `primary_focus_ID` int(11) DEFAULT '0',
  `people_ID` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Review_Docket`
--

DROP TABLE IF EXISTS `tbl_MARS_Review_Docket`;
CREATE TABLE `tbl_MARS_Review_Docket` (
  `docket_ID` int(11) NOT NULL,
  `rev_ID` int(11) DEFAULT '0',
  `proposal_ID` int(11) DEFAULT '0',
  `docket_Evaluation` text,
  `docket_Award_Reccomend` tinyint(4) DEFAULT '0',
  `docket_datetimestamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_RevStren`
--

DROP TABLE IF EXISTS `tbl_MARS_RevStren`;
CREATE TABLE `tbl_MARS_RevStren` (
  `revstren_ID` int(11) NOT NULL,
  `rev_ID` int(11) DEFAULT '0',
  `strengths_ID` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Rev_Reqs`
--

DROP TABLE IF EXISTS `tbl_MARS_Rev_Reqs`;
CREATE TABLE `tbl_MARS_Rev_Reqs` (
  `revreq_ID` int(11) NOT NULL,
  `revreq_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Rev_Reqs`
--

TRUNCATE TABLE `tbl_MARS_Rev_Reqs`;
--
-- Dumping data for table `tbl_MARS_Rev_Reqs`
--

INSERT INTO `tbl_MARS_Rev_Reqs` (`revreq_ID`, `revreq_Name`) VALUES
(1, 'External'),
(2, 'Internal'),
(3, 'Both');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_RFPs`
--

DROP TABLE IF EXISTS `tbl_MARS_RFPs`;
CREATE TABLE `tbl_MARS_RFPs` (
  `rfp_ID` int(11) NOT NULL,
  `rfp_Name` varchar(50) DEFAULT NULL,
  `rfp_Type` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_RFP_Types`
--

DROP TABLE IF EXISTS `tbl_MARS_RFP_Types`;
CREATE TABLE `tbl_MARS_RFP_Types` (
  `rfp_type_ID` int(11) NOT NULL,
  `rfp_type_name` varchar(50) DEFAULT NULL,
  `rfp_type_desc` varchar(50) DEFAULT NULL,
  `rfp_type_eligibile_entities` int(11) DEFAULT '0',
  `rfp_type_applicant_eligibility` int(11) DEFAULT '0',
  `rfp_type_review_requirement` int(11) DEFAULT '0',
  `rfp_type_review_count` int(11) DEFAULT '0',
  `rfp_type_instrumentation` varchar(50) DEFAULT NULL,
  `rfp_type_period` int(11) DEFAULT '0',
  `rfp_type_prop_desc_page_limit` int(11) UNSIGNED DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Roles`
--

DROP TABLE IF EXISTS `tbl_MARS_Roles`;
CREATE TABLE `tbl_MARS_Roles` (
  `role_ID` int(11) NOT NULL,
  `role_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_Roles`
--

TRUNCATE TABLE `tbl_MARS_Roles`;
--
-- Dumping data for table `tbl_MARS_Roles`
--

INSERT INTO `tbl_MARS_Roles` (`role_ID`, `role_Name`) VALUES
(1, 'Primary Investigator'),
(2, 'Co-PI'),
(3, 'Senior Personnel'),
(4, 'Post-doc (fellow/assistant/etc)'),
(5, 'Graduate student (fellow/assistant/etc)'),
(6, 'Undergraduate student'),
(7, 'Research Experience for Undergraduates (REU)'),
(8, 'High school student'),
(9, 'Technical school student'),
(10, 'K-12 Teacher'),
(11, 'Community college faculty/visitor'),
(12, 'Technical school faculty/visitor'),
(13, 'Technician/programmer/other professional staff'),
(14, 'Other'),
(15, 'Collaborator/University'),
(16, 'Collaborator/Industry'),
(19, 'Collaborator/RII FBBG'),
(20, 'Middle school student'),
(21, 'Doctoral student (fellow/assistant/etc)');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Sec`
--

DROP TABLE IF EXISTS `tbl_MARS_Sec`;
CREATE TABLE `tbl_MARS_Sec` (
  `sec_ID` int(11) NOT NULL,
  `sec_source_ID` int(11) DEFAULT '0',
  `sec_uname` varchar(50) DEFAULT NULL,
  `sec_pword` varchar(50) DEFAULT NULL,
  `sec_unique_ID` varchar(50) DEFAULT NULL,
  `sec_q1` varchar(255) DEFAULT NULL,
  `sec_r1` varchar(255) DEFAULT NULL,
  `sec_SysRole` int(11) DEFAULT '1',
  `sec_FMRRole` int(11) DEFAULT '1',
  `sec_reg_date` datetime DEFAULT NULL,
  `sec_granted` tinyint(4) DEFAULT '0',
  `sec_last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_State`
--

DROP TABLE IF EXISTS `tbl_MARS_State`;
CREATE TABLE `tbl_MARS_State` (
  `state_ID` int(11) NOT NULL,
  `state_Name` varchar(255) DEFAULT NULL,
  `state_Abbreviation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_State`
--

TRUNCATE TABLE `tbl_MARS_State`;
--
-- Dumping data for table `tbl_MARS_State`
--

INSERT INTO `tbl_MARS_State` (`state_ID`, `state_Name`, `state_Abbreviation`) VALUES
(1, 'Alabama', 'AL'),
(2, 'Alaska', 'AK'),
(3, 'American Samoa', 'AS'),
(4, 'Arizona', 'AZ'),
(5, 'Arkansas', 'AR'),
(6, 'Armed Forces Africa', 'AE'),
(7, 'Armed Forces Americas', 'AA'),
(8, 'Armed Forces Canada', 'AE'),
(9, 'Armed Forces Europe', 'AE'),
(10, 'Armed Forces Middle East', 'AE'),
(11, 'Armed Forces Pacific', 'AP'),
(12, 'California', 'CA'),
(13, 'Colorado', 'CO'),
(14, 'Connecticut', 'CT'),
(15, 'Delaware', 'DE'),
(16, 'District of Columbia', 'DC'),
(17, 'Federated States of Micronesia', 'FM'),
(18, 'Florida', 'FL'),
(19, 'Georgia', 'GA'),
(20, 'Guam', 'GU'),
(21, 'Hawaii', 'HI'),
(22, 'Idaho', 'ID'),
(23, 'Illinois', 'IL'),
(24, 'Indiana', 'IN'),
(25, 'Iowa', 'IA'),
(26, 'Kansas', 'KS'),
(27, 'Kentucky', 'KY'),
(28, 'Louisiana', 'LA'),
(29, 'Maine', 'ME'),
(30, 'Marshall Islands', 'MH'),
(31, 'Maryland', 'MD'),
(32, 'Massachusetts', 'MA'),
(33, 'Michigan', 'MI'),
(34, 'Minnesota', 'MN'),
(35, 'Mississippi', 'MS'),
(36, 'Missouri', 'MO'),
(37, 'Montana', 'MT'),
(38, 'Nebraska', 'NE'),
(39, 'Nevada', 'NV'),
(40, 'New Hampshire', 'NH'),
(41, 'New Jersey', 'NJ'),
(42, 'New Mexico', 'NM'),
(43, 'New York', 'NY'),
(44, 'North Carolina', 'NC'),
(45, 'North Dakota', 'ND'),
(46, 'Northern Mariana Islands', 'MP'),
(47, 'Ohio', 'OH'),
(48, 'Oklahoma', 'OK'),
(49, 'Oregon', 'OR'),
(50, 'Palau', 'PW'),
(51, 'Pennsylvania', 'PA'),
(52, 'Puerto Rico ', 'PR'),
(53, 'Rhode Island', 'RI'),
(54, 'South Carolina', 'SC'),
(55, 'South Dakota', 'SD'),
(56, 'Tennessee', 'TN'),
(57, 'Texas', 'TX'),
(58, 'Utah', 'UT'),
(59, 'Vermont', 'VT'),
(60, 'Virgin Islands ', 'VI'),
(61, 'Virginia', 'VA'),
(62, 'Washington', 'WA'),
(63, 'West Virginia', 'WV'),
(64, 'Wisconsin', 'WI'),
(65, 'Wyoming', 'WY');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Strengths`
--

DROP TABLE IF EXISTS `tbl_MARS_Strengths`;
CREATE TABLE `tbl_MARS_Strengths` (
  `strengths_ID` int(11) NOT NULL,
  `strengths_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_SysRole`
--

DROP TABLE IF EXISTS `tbl_MARS_SysRole`;
CREATE TABLE `tbl_MARS_SysRole` (
  `sysrole_ID` int(11) NOT NULL,
  `sysrole_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `tbl_MARS_SysRole`
--

TRUNCATE TABLE `tbl_MARS_SysRole`;
--
-- Dumping data for table `tbl_MARS_SysRole`
--

INSERT INTO `tbl_MARS_SysRole` (`sysrole_ID`, `sysrole_Name`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'End User');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Teams`
--

DROP TABLE IF EXISTS `tbl_MARS_Teams`;
CREATE TABLE `tbl_MARS_Teams` (
  `team_ID` int(11) NOT NULL,
  `team_focus_ID` int(11) DEFAULT '0',
  `award_ID` int(11) DEFAULT NULL,
  `team_Name` varchar(50) DEFAULT NULL,
  `team_Sort_Order` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Team_Comp`
--

DROP TABLE IF EXISTS `tbl_MARS_Team_Comp`;
CREATE TABLE `tbl_MARS_Team_Comp` (
  `team_comp_ID` int(11) NOT NULL,
  `team_ID` int(11) DEFAULT '0',
  `people_ID` int(11) DEFAULT '0',
  `report_role_ID` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Zip_Codes_City`
--

DROP TABLE IF EXISTS `tbl_MARS_Zip_Codes_City`;
CREATE TABLE `tbl_MARS_Zip_Codes_City` (
  `ZIP_CODE` varchar(5) DEFAULT NULL,
  `LATITUDE` double DEFAULT '0',
  `LONGITUDE` double DEFAULT '0',
  `ZIP_CLASS` varchar(1) DEFAULT NULL,
  `PONAME` varchar(28) DEFAULT NULL,
  `FIPS_State` varchar(2) DEFAULT NULL,
  `FIPS_County` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_MARS_ActsFinds`
--
ALTER TABLE `tbl_MARS_ActsFinds`
  ADD PRIMARY KEY (`actfinds_ID`),
  ADD KEY `actfinds_ID` (`actfinds_ID`),
  ADD KEY `report_data_ID` (`report_data_ID`);

--
-- Indexes for table `tbl_MARS_Agencies`
--
ALTER TABLE `tbl_MARS_Agencies`
  ADD PRIMARY KEY (`agency_ID`),
  ADD KEY `agency_ID` (`agency_ID`);

--
-- Indexes for table `tbl_MARS_Announce`
--
ALTER TABLE `tbl_MARS_Announce`
  ADD PRIMARY KEY (`ann_ID`),
  ADD KEY `ann_ID` (`ann_ID`);

--
-- Indexes for table `tbl_MARS_AwardPubs`
--
ALTER TABLE `tbl_MARS_AwardPubs`
  ADD KEY `awardpub_ID` (`awardpub_ID`);

--
-- Indexes for table `tbl_MARS_Awards`
--
ALTER TABLE `tbl_MARS_Awards`
  ADD PRIMARY KEY (`award_ID`),
  ADD KEY `award_ID` (`award_ID`),
  ADD KEY `ann_ID` (`ann_ID`),
  ADD KEY `proposal_ID` (`proposal_ID`);

--
-- Indexes for table `tbl_MARS_Award_Incs`
--
ALTER TABLE `tbl_MARS_Award_Incs`
  ADD PRIMARY KEY (`award_inc_ID`),
  ADD KEY `award_ID` (`award_ID`);

--
-- Indexes for table `tbl_MARS_Award_Reqs`
--
ALTER TABLE `tbl_MARS_Award_Reqs`
  ADD KEY `req_ID` (`req_ID`);

--
-- Indexes for table `tbl_MARS_Bio`
--
ALTER TABLE `tbl_MARS_Bio`
  ADD UNIQUE KEY `bio_ID` (`bio_ID`);

--
-- Indexes for table `tbl_MARS_Citizenship`
--
ALTER TABLE `tbl_MARS_Citizenship`
  ADD PRIMARY KEY (`citizenship_ID`),
  ADD KEY `citizenship_ID` (`citizenship_ID`);

--
-- Indexes for table `tbl_MARS_Collaborations`
--
ALTER TABLE `tbl_MARS_Collaborations`
  ADD UNIQUE KEY `collab_ID` (`collab_ID`);

--
-- Indexes for table `tbl_MARS_Contributions`
--
ALTER TABLE `tbl_MARS_Contributions`
  ADD PRIMARY KEY (`contrib_ID`),
  ADD KEY `contrib_ID` (`contrib_ID`),
  ADD KEY `report_Data_ID` (`report_Data_ID`);

--
-- Indexes for table `tbl_MARS_Country`
--
ALTER TABLE `tbl_MARS_Country`
  ADD PRIMARY KEY (`country_ID`);

--
-- Indexes for table `tbl_MARS_Data_Rule`
--
ALTER TABLE `tbl_MARS_Data_Rule`
  ADD PRIMARY KEY (`rules_ID`),
  ADD KEY `rules_ID` (`rules_ID`);

--
-- Indexes for table `tbl_MARS_Degree`
--
ALTER TABLE `tbl_MARS_Degree`
  ADD KEY `degree_ID` (`degree_ID`);

--
-- Indexes for table `tbl_MARS_Demographic_Data`
--
ALTER TABLE `tbl_MARS_Demographic_Data`
  ADD PRIMARY KEY (`demog_id`),
  ADD KEY `demog_id` (`demog_id`),
  ADD KEY `demog_source_ID` (`demog_source_ID`);

--
-- Indexes for table `tbl_MARS_Disability`
--
ALTER TABLE `tbl_MARS_Disability`
  ADD PRIMARY KEY (`disability_ID`),
  ADD KEY `disability_ID` (`disability_ID`);

--
-- Indexes for table `tbl_MARS_Eligible_PIs`
--
ALTER TABLE `tbl_MARS_Eligible_PIs`
  ADD PRIMARY KEY (`pi_ID`),
  ADD KEY `pi_ID` (`pi_ID`);

--
-- Indexes for table `tbl_MARS_Entities`
--
ALTER TABLE `tbl_MARS_Entities`
  ADD PRIMARY KEY (`entity_ID`),
  ADD KEY `entity_ID` (`entity_ID`);

--
-- Indexes for table `tbl_MARS_Ethnicity`
--
ALTER TABLE `tbl_MARS_Ethnicity`
  ADD PRIMARY KEY (`ethnicity_ID`),
  ADD KEY `ethnicity_ID` (`ethnicity_ID`);

--
-- Indexes for table `tbl_MARS_FIPS_Cities_WV`
--
ALTER TABLE `tbl_MARS_FIPS_Cities_WV`
  ADD PRIMARY KEY (`city_ID`),
  ADD KEY `city_ID` (`city_ID`);

--
-- Indexes for table `tbl_MARS_FIPS_Codes`
--
ALTER TABLE `tbl_MARS_FIPS_Codes`
  ADD PRIMARY KEY (`FIPS_ID`),
  ADD KEY `FIPS_ID` (`FIPS_ID`);

--
-- Indexes for table `tbl_MARS_Focus_Areas`
--
ALTER TABLE `tbl_MARS_Focus_Areas`
  ADD PRIMARY KEY (`focus_ID`),
  ADD KEY `focus_ID` (`focus_ID`);

--
-- Indexes for table `tbl_MARS_Gender`
--
ALTER TABLE `tbl_MARS_Gender`
  ADD PRIMARY KEY (`gender_ID`),
  ADD KEY `gender_ID` (`gender_ID`);

--
-- Indexes for table `tbl_MARS_Grant_Type`
--
ALTER TABLE `tbl_MARS_Grant_Type`
  ADD PRIMARY KEY (`type_ID`),
  ADD KEY `type_ID` (`type_ID`);

--
-- Indexes for table `tbl_MARS_Inst`
--
ALTER TABLE `tbl_MARS_Inst`
  ADD PRIMARY KEY (`inst_ID`);

--
-- Indexes for table `tbl_MARS_Instrument`
--
ALTER TABLE `tbl_MARS_Instrument`
  ADD PRIMARY KEY (`instrument_ID`),
  ADD KEY `instrument_ID` (`instrument_ID`);

--
-- Indexes for table `tbl_MARS_Participants`
--
ALTER TABLE `tbl_MARS_Participants`
  ADD PRIMARY KEY (`participant_ID`),
  ADD KEY `award_ID` (`award_ID`),
  ADD KEY `participant_ID` (`participant_ID`),
  ADD KEY `people_ID` (`people_ID`),
  ADD KEY `report_data_ID` (`report_data_ID`);

--
-- Indexes for table `tbl_MARS_Participant_Data`
--
ALTER TABLE `tbl_MARS_Participant_Data`
  ADD PRIMARY KEY (`part_data_ID`),
  ADD KEY `part_data_ID` (`part_data_ID`);

--
-- Indexes for table `tbl_MARS_PartnerOrgs`
--
ALTER TABLE `tbl_MARS_PartnerOrgs`
  ADD KEY `org_ID` (`org_ID`);

--
-- Indexes for table `tbl_MARS_PartnerOrgs_Data`
--
ALTER TABLE `tbl_MARS_PartnerOrgs_Data`
  ADD KEY `org_data_ID` (`org_data_ID`);

--
-- Indexes for table `tbl_MARS_People`
--
ALTER TABLE `tbl_MARS_People`
  ADD PRIMARY KEY (`people_ID`),
  ADD KEY `people_ID` (`people_ID`);

--
-- Indexes for table `tbl_MARS_Proposals`
--
ALTER TABLE `tbl_MARS_Proposals`
  ADD PRIMARY KEY (`proposal_ID`),
  ADD KEY `ann_id` (`ann_ID`),
  ADD KEY `people_ID` (`people_ID`),
  ADD KEY `proposal_ID` (`proposal_ID`);

--
-- Indexes for table `tbl_MARS_Pubs`
--
ALTER TABLE `tbl_MARS_Pubs`
  ADD PRIMARY KEY (`pub_ID`),
  ADD KEY `pubs_ID` (`pub_ID`),
  ADD KEY `report_data_ID` (`report_data_ID`);

--
-- Indexes for table `tbl_MARS_Pub_Authors`
--
ALTER TABLE `tbl_MARS_Pub_Authors`
  ADD PRIMARY KEY (`author_ID`),
  ADD KEY `author_ID` (`author_ID`),
  ADD KEY `people_ID` (`people_ID`),
  ADD KEY `pub_ID` (`pub_ID`);

--
-- Indexes for table `tbl_MARS_Pub_Status`
--
ALTER TABLE `tbl_MARS_Pub_Status`
  ADD KEY `status_ID` (`status_ID`);

--
-- Indexes for table `tbl_MARS_Pub_Type`
--
ALTER TABLE `tbl_MARS_Pub_Type`
  ADD PRIMARY KEY (`type_ID`),
  ADD KEY `type_ID` (`type_ID`);

--
-- Indexes for table `tbl_MARS_Race`
--
ALTER TABLE `tbl_MARS_Race`
  ADD PRIMARY KEY (`race_ID`),
  ADD KEY `race_ID` (`race_ID`);

--
-- Indexes for table `tbl_MARS_Report_Data`
--
ALTER TABLE `tbl_MARS_Report_Data`
  ADD PRIMARY KEY (`report_data_ID`),
  ADD KEY `award_ID` (`award_ID`),
  ADD KEY `people_ID` (`people_ID`),
  ADD KEY `report_period_ID` (`report_period_ID`),
  ADD KEY `report_data_ID` (`report_data_ID`);

--
-- Indexes for table `tbl_MARS_Report_Periods`
--
ALTER TABLE `tbl_MARS_Report_Periods`
  ADD PRIMARY KEY (`report_period_ID`),
  ADD KEY `report_period_ID` (`report_period_ID`);

--
-- Indexes for table `tbl_MARS_Report_Roles`
--
ALTER TABLE `tbl_MARS_Report_Roles`
  ADD PRIMARY KEY (`report_role_ID`),
  ADD KEY `report_role_ID` (`report_role_ID`);

--
-- Indexes for table `tbl_MARS_Reviewer`
--
ALTER TABLE `tbl_MARS_Reviewer`
  ADD PRIMARY KEY (`rev_ID`),
  ADD KEY `people_ID` (`people_ID`),
  ADD KEY `rev_ID` (`rev_ID`),
  ADD KEY `primary_focus_ID` (`primary_focus_ID`);

--
-- Indexes for table `tbl_MARS_Review_Docket`
--
ALTER TABLE `tbl_MARS_Review_Docket`
  ADD PRIMARY KEY (`docket_ID`),
  ADD KEY `docket_ID` (`docket_ID`),
  ADD KEY `proposal_ID` (`proposal_ID`),
  ADD KEY `rev_ID` (`rev_ID`);

--
-- Indexes for table `tbl_MARS_RevStren`
--
ALTER TABLE `tbl_MARS_RevStren`
  ADD PRIMARY KEY (`revstren_ID`),
  ADD KEY `rev_id` (`rev_ID`),
  ADD KEY `revstren_ID` (`revstren_ID`),
  ADD KEY `strengths_ID` (`strengths_ID`);

--
-- Indexes for table `tbl_MARS_Rev_Reqs`
--
ALTER TABLE `tbl_MARS_Rev_Reqs`
  ADD PRIMARY KEY (`revreq_ID`),
  ADD KEY `revreq_ID` (`revreq_ID`);

--
-- Indexes for table `tbl_MARS_RFPs`
--
ALTER TABLE `tbl_MARS_RFPs`
  ADD PRIMARY KEY (`rfp_ID`),
  ADD KEY `rfp_ID` (`rfp_ID`);

--
-- Indexes for table `tbl_MARS_RFP_Types`
--
ALTER TABLE `tbl_MARS_RFP_Types`
  ADD PRIMARY KEY (`rfp_type_ID`),
  ADD KEY `rfp_type_ID` (`rfp_type_ID`);

--
-- Indexes for table `tbl_MARS_Roles`
--
ALTER TABLE `tbl_MARS_Roles`
  ADD PRIMARY KEY (`role_ID`),
  ADD KEY `role_ID` (`role_ID`);

--
-- Indexes for table `tbl_MARS_Sec`
--
ALTER TABLE `tbl_MARS_Sec`
  ADD PRIMARY KEY (`sec_ID`),
  ADD KEY `sec_source_ID` (`sec_source_ID`),
  ADD KEY `sec_unique_ID` (`sec_unique_ID`);

--
-- Indexes for table `tbl_MARS_State`
--
ALTER TABLE `tbl_MARS_State`
  ADD PRIMARY KEY (`state_ID`),
  ADD KEY `state_ID` (`state_ID`);

--
-- Indexes for table `tbl_MARS_Strengths`
--
ALTER TABLE `tbl_MARS_Strengths`
  ADD PRIMARY KEY (`strengths_ID`),
  ADD KEY `strengths_ID` (`strengths_ID`);

--
-- Indexes for table `tbl_MARS_SysRole`
--
ALTER TABLE `tbl_MARS_SysRole`
  ADD KEY `sysrole_ID` (`sysrole_ID`);

--
-- Indexes for table `tbl_MARS_Teams`
--
ALTER TABLE `tbl_MARS_Teams`
  ADD PRIMARY KEY (`team_ID`),
  ADD KEY `team_focus_ID` (`team_focus_ID`),
  ADD KEY `team_ID` (`team_ID`);

--
-- Indexes for table `tbl_MARS_Team_Comp`
--
ALTER TABLE `tbl_MARS_Team_Comp`
  ADD PRIMARY KEY (`team_comp_ID`),
  ADD KEY `participant_ID` (`people_ID`),
  ADD KEY `report_role_ID` (`report_role_ID`),
  ADD KEY `team_comp_ID` (`team_comp_ID`),
  ADD KEY `team_ID` (`team_ID`);

--
-- Indexes for table `tbl_MARS_Zip_Codes_City`
--
ALTER TABLE `tbl_MARS_Zip_Codes_City`
  ADD KEY `ZIP_CODE` (`ZIP_CODE`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_MARS_ActsFinds`
--
ALTER TABLE `tbl_MARS_ActsFinds`
  MODIFY `actfinds_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Agencies`
--
ALTER TABLE `tbl_MARS_Agencies`
  MODIFY `agency_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_MARS_Announce`
--
ALTER TABLE `tbl_MARS_Announce`
  MODIFY `ann_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_AwardPubs`
--
ALTER TABLE `tbl_MARS_AwardPubs`
  MODIFY `awardpub_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Awards`
--
ALTER TABLE `tbl_MARS_Awards`
  MODIFY `award_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Award_Incs`
--
ALTER TABLE `tbl_MARS_Award_Incs`
  MODIFY `award_inc_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Award_Reqs`
--
ALTER TABLE `tbl_MARS_Award_Reqs`
  MODIFY `req_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Bio`
--
ALTER TABLE `tbl_MARS_Bio`
  MODIFY `bio_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Citizenship`
--
ALTER TABLE `tbl_MARS_Citizenship`
  MODIFY `citizenship_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_MARS_Collaborations`
--
ALTER TABLE `tbl_MARS_Collaborations`
  MODIFY `collab_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Contributions`
--
ALTER TABLE `tbl_MARS_Contributions`
  MODIFY `contrib_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Country`
--
ALTER TABLE `tbl_MARS_Country`
  MODIFY `country_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT for table `tbl_MARS_Data_Rule`
--
ALTER TABLE `tbl_MARS_Data_Rule`
  MODIFY `rules_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Degree`
--
ALTER TABLE `tbl_MARS_Degree`
  MODIFY `degree_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_MARS_Demographic_Data`
--
ALTER TABLE `tbl_MARS_Demographic_Data`
  MODIFY `demog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Disability`
--
ALTER TABLE `tbl_MARS_Disability`
  MODIFY `disability_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_MARS_Eligible_PIs`
--
ALTER TABLE `tbl_MARS_Eligible_PIs`
  MODIFY `pi_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_Entities`
--
ALTER TABLE `tbl_MARS_Entities`
  MODIFY `entity_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_Ethnicity`
--
ALTER TABLE `tbl_MARS_Ethnicity`
  MODIFY `ethnicity_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_FIPS_Cities_WV`
--
ALTER TABLE `tbl_MARS_FIPS_Cities_WV`
  MODIFY `city_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_FIPS_Codes`
--
ALTER TABLE `tbl_MARS_FIPS_Codes`
  MODIFY `FIPS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Focus_Areas`
--
ALTER TABLE `tbl_MARS_Focus_Areas`
  MODIFY `focus_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Gender`
--
ALTER TABLE `tbl_MARS_Gender`
  MODIFY `gender_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_MARS_Grant_Type`
--
ALTER TABLE `tbl_MARS_Grant_Type`
  MODIFY `type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_MARS_Inst`
--
ALTER TABLE `tbl_MARS_Inst`
  MODIFY `inst_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `tbl_MARS_Instrument`
--
ALTER TABLE `tbl_MARS_Instrument`
  MODIFY `instrument_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_Participants`
--
ALTER TABLE `tbl_MARS_Participants`
  MODIFY `participant_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Participant_Data`
--
ALTER TABLE `tbl_MARS_Participant_Data`
  MODIFY `part_data_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_PartnerOrgs`
--
ALTER TABLE `tbl_MARS_PartnerOrgs`
  MODIFY `org_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_PartnerOrgs_Data`
--
ALTER TABLE `tbl_MARS_PartnerOrgs_Data`
  MODIFY `org_data_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_People`
--
ALTER TABLE `tbl_MARS_People`
  MODIFY `people_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Proposals`
--
ALTER TABLE `tbl_MARS_Proposals`
  MODIFY `proposal_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Pubs`
--
ALTER TABLE `tbl_MARS_Pubs`
  MODIFY `pub_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Pub_Authors`
--
ALTER TABLE `tbl_MARS_Pub_Authors`
  MODIFY `author_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Pub_Status`
--
ALTER TABLE `tbl_MARS_Pub_Status`
  MODIFY `status_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_MARS_Pub_Type`
--
ALTER TABLE `tbl_MARS_Pub_Type`
  MODIFY `type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_MARS_Race`
--
ALTER TABLE `tbl_MARS_Race`
  MODIFY `race_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Data`
--
ALTER TABLE `tbl_MARS_Report_Data`
  MODIFY `report_data_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Periods`
--
ALTER TABLE `tbl_MARS_Report_Periods`
  MODIFY `report_period_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Roles`
--
ALTER TABLE `tbl_MARS_Report_Roles`
  MODIFY `report_role_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_MARS_Reviewer`
--
ALTER TABLE `tbl_MARS_Reviewer`
  MODIFY `rev_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Review_Docket`
--
ALTER TABLE `tbl_MARS_Review_Docket`
  MODIFY `docket_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_RevStren`
--
ALTER TABLE `tbl_MARS_RevStren`
  MODIFY `revstren_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Rev_Reqs`
--
ALTER TABLE `tbl_MARS_Rev_Reqs`
  MODIFY `revreq_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_RFPs`
--
ALTER TABLE `tbl_MARS_RFPs`
  MODIFY `rfp_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_RFP_Types`
--
ALTER TABLE `tbl_MARS_RFP_Types`
  MODIFY `rfp_type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Roles`
--
ALTER TABLE `tbl_MARS_Roles`
  MODIFY `role_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_MARS_Sec`
--
ALTER TABLE `tbl_MARS_Sec`
  MODIFY `sec_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_State`
--
ALTER TABLE `tbl_MARS_State`
  MODIFY `state_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_MARS_Strengths`
--
ALTER TABLE `tbl_MARS_Strengths`
  MODIFY `strengths_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_SysRole`
--
ALTER TABLE `tbl_MARS_SysRole`
  MODIFY `sysrole_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_MARS_Teams`
--
ALTER TABLE `tbl_MARS_Teams`
  MODIFY `team_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Team_Comp`
--
ALTER TABLE `tbl_MARS_Team_Comp`
  MODIFY `team_comp_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
