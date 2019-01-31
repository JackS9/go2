-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql.westvirginiaresearch.org
-- Generation Time: Jan 30, 2019 at 03:18 PM
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
-- Stand-in structure for view `Award_Report`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `Award_Report`;
CREATE TABLE `Award_Report` (
`RFP Type` varchar(50)
,`Announcement` varchar(255)
,`Institution` varchar(255)
,`Recipient` varchar(254)
,`Title` varchar(255)
,`Date` datetime
,`Amount` float(18,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `Distribution_Report`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `Distribution_Report`;
CREATE TABLE `Distribution_Report` (
`IRP #` int(11)
,`Endowment` varchar(255)
,`College` varchar(255)
,`Department` varchar(255)
,`Donor(s)` text
,`Amount` float(18,2)
,`Pledged` tinyint(4)
,`Distribution to Date` double
,`Submitted By` varchar(101)
,`At` varchar(255)
,`Approved By` varchar(101)
,`On` varchar(8)
);

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Eligible_PIs`
--

DROP TABLE IF EXISTS `tbl_MARS_Eligible_PIs`;
CREATE TABLE `tbl_MARS_Eligible_PIs` (
  `pi_ID` int(11) NOT NULL,
  `pi_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Entities`
--

DROP TABLE IF EXISTS `tbl_MARS_Entities`;
CREATE TABLE `tbl_MARS_Entities` (
  `entity_ID` int(11) NOT NULL,
  `entity_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Ethnicity`
--

DROP TABLE IF EXISTS `tbl_MARS_Ethnicity`;
CREATE TABLE `tbl_MARS_Ethnicity` (
  `ethnicity_ID` int(11) NOT NULL,
  `ethnicity_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Grant_Type`
--

DROP TABLE IF EXISTS `tbl_MARS_Grant_Type`;
CREATE TABLE `tbl_MARS_Grant_Type` (
  `type_ID` int(11) NOT NULL,
  `type_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Instrument`
--

DROP TABLE IF EXISTS `tbl_MARS_Instrument`;
CREATE TABLE `tbl_MARS_Instrument` (
  `instrument_ID` int(11) NOT NULL,
  `instrument_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Pub_Type`
--

DROP TABLE IF EXISTS `tbl_MARS_Pub_Type`;
CREATE TABLE `tbl_MARS_Pub_Type` (
  `type_ID` int(11) NOT NULL,
  `type_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Race`
--

DROP TABLE IF EXISTS `tbl_MARS_Race`;
CREATE TABLE `tbl_MARS_Race` (
  `race_ID` int(11) NOT NULL,
  `race_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_MARS_Report_Roles`
--

DROP TABLE IF EXISTS `tbl_MARS_Report_Roles`;
CREATE TABLE `tbl_MARS_Report_Roles` (
  `report_role_ID` int(11) NOT NULL,
  `report_role_Name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Structure for view `Award_Report`
--
DROP TABLE IF EXISTS `Award_Report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`westvirginiarese`@`64.90.32.0/255.255.224.0` SQL SECURITY DEFINER VIEW `Award_Report`  AS  select `RFP`.`rfp_type_name` AS `RFP Type`,`Announce`.`ann_Public_Name` AS `Announcement`,`Inst`.`inst_Name` AS `Institution`,concat(`People`.`people_Title`,' ',`People`.`people_FirstName`,' ',`People`.`people_MI`,' ',`People`.`people_LastName`,' ',`People`.`people_Suffix`) AS `Recipient`,`Proposal`.`proposal_Name` AS `Title`,`Award`.`award_Date` AS `Date`,`Award`.`award_amount` AS `Amount` from (((((`tbl_MARS_Proposals` `Proposal` join `tbl_MARS_Announce` `Announce` on((`Announce`.`ann_ID` = `Proposal`.`ann_ID`))) join `tbl_MARS_Awards` `Award` on((`Award`.`proposal_ID` = `Proposal`.`proposal_ID`))) join `tbl_MARS_People` `People` on((`People`.`people_ID` = `Proposal`.`people_ID`))) join `tbl_MARS_RFP_Types` `RFP` on((`RFP`.`rfp_type_ID` = `Announce`.`rfp_type_ID`))) left join `tbl_MARS_Inst` `Inst` on((`Inst`.`inst_ID` = `People`.`people_Dept1`))) where 1 order by `RFP`.`rfp_type_ID`,`Announce`.`ann_ID`,`Inst`.`inst_ID`,`People`.`people_ID` ;

-- --------------------------------------------------------

--
-- Structure for view `Distribution_Report`
--
DROP TABLE IF EXISTS `Distribution_Report`;

CREATE ALGORITHM=UNDEFINED DEFINER=`westvirginiarese`@`64.90.32.0/255.255.224.0` SQL SECURITY DEFINER VIEW `Distribution_Report`  AS  select `IRP`.`irp_ID` AS `IRP #`,`MatchRequests`.`matchreq_Endowment` AS `Endowment`,`MatchRequests`.`matchreq_EndowmentCollege` AS `College`,`MatchRequests`.`matchreq_EndowmentDepartment` AS `Department`,`MatchRequests`.`matchreq_Donor` AS `Donor(s)`,`MatchRequests`.`matchreq_Amount` AS `Amount`,`MatchRequests`.`matchreq_Pledge` AS `Pledged`,`MatchRequests`.`matchreq_DistToDate` AS `Distribution to Date`,concat(`SubmitPeople`.`people_FirstName`,' ',`SubmitPeople`.`people_LastName`) AS `Submitted By`,`Institution`.`inst_Name` AS `At`,concat(`ApprovalPeople`.`people_FirstName`,' ',`ApprovalPeople`.`people_LastName`) AS `Approved By`,date_format(`Approved`.`approved_Date`,'%m/%d/%y') AS `On` from (((((`fund_tbl_MatchRequests` `MatchRequests` join `fund_tbl_Approved` `Approved` on((`Approved`.`matchreq_ID` = `MatchRequests`.`matchreq_ID`))) join `fund_tbl_IRP` `IRP` on((`MatchRequests`.`irp_ID` = `IRP`.`irp_ID`))) join `tbl_MARS_People` `ApprovalPeople` on((`ApprovalPeople`.`people_ID` = `Approved`.`approved_submittedby`))) join `tbl_MARS_People` `SubmitPeople` on((`SubmitPeople`.`people_ID` = `MatchRequests`.`matchreq_submittedby`))) join `tbl_MARS_Inst` `Institution` on((`SubmitPeople`.`people_Dept1` = `Institution`.`inst_ID`))) order by `IRP`.`irp_ID`,`MatchRequests`.`matchreq_Endowment`,`MatchRequests`.`matchreq_EndowmentCollege`,`MatchRequests`.`matchreq_EndowmentDepartment` ;

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
  MODIFY `agency_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `citizenship_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `country_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Data_Rule`
--
ALTER TABLE `tbl_MARS_Data_Rule`
  MODIFY `rules_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Degree`
--
ALTER TABLE `tbl_MARS_Degree`
  MODIFY `degree_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Demographic_Data`
--
ALTER TABLE `tbl_MARS_Demographic_Data`
  MODIFY `demog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Disability`
--
ALTER TABLE `tbl_MARS_Disability`
  MODIFY `disability_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Eligible_PIs`
--
ALTER TABLE `tbl_MARS_Eligible_PIs`
  MODIFY `pi_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Entities`
--
ALTER TABLE `tbl_MARS_Entities`
  MODIFY `entity_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Ethnicity`
--
ALTER TABLE `tbl_MARS_Ethnicity`
  MODIFY `ethnicity_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `gender_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Grant_Type`
--
ALTER TABLE `tbl_MARS_Grant_Type`
  MODIFY `type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Inst`
--
ALTER TABLE `tbl_MARS_Inst`
  MODIFY `inst_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Instrument`
--
ALTER TABLE `tbl_MARS_Instrument`
  MODIFY `instrument_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `status_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Pub_Type`
--
ALTER TABLE `tbl_MARS_Pub_Type`
  MODIFY `type_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Race`
--
ALTER TABLE `tbl_MARS_Race`
  MODIFY `race_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Data`
--
ALTER TABLE `tbl_MARS_Report_Data`
  MODIFY `report_data_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Periods`
--
ALTER TABLE `tbl_MARS_Report_Periods`
  MODIFY `report_period_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Report_Roles`
--
ALTER TABLE `tbl_MARS_Report_Roles`
  MODIFY `report_role_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `revreq_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `role_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Sec`
--
ALTER TABLE `tbl_MARS_Sec`
  MODIFY `sec_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_State`
--
ALTER TABLE `tbl_MARS_State`
  MODIFY `state_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_Strengths`
--
ALTER TABLE `tbl_MARS_Strengths`
  MODIFY `strengths_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_MARS_SysRole`
--
ALTER TABLE `tbl_MARS_SysRole`
  MODIFY `sysrole_ID` int(11) NOT NULL AUTO_INCREMENT;

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
