<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');
require_once('User.class.php');

class Reports extends Common
{
    protected $Editor;
    protected $dataDir = '../godocs/reports/';
    protected $mateInstances = array('mate1_');

    function __construct()
    {
        global $user;
        
        session_start();
        ob_start();
        
        if(isset($_SESSION['logged_in'])) :
            $user = unserialize($_SESSION['user']);
            $user->isLoggedIn = 1;
        else :
            $user = new User('');
            $user->isLoggedIn = 0;
        endif;
        
        $this->logRequest();
        
        // Initiate MATE editor
        $this->initiateEditor();

        if(isset($_POST['json']))  // Handle Asynchronous POST with JSON input
        {
            if(ini_get('magic_quotes_gpc'))
            {
                $_POST['json'] = stripslashes($_POST['json']);
            }
            $this->Editor->data = $this->Editor->jsonDecode($_POST['json'],true);
            $this->Editor->setDefaults();
            $this->Editor->main();
        }
        else if(isset($_GET['mate_export']))  // Handle Export Request
        {
            $this->Editor->data['sessionData'] = $_GET['session_data'];
            $this->Editor->setDefaults();
            ob_end_clean();
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Content-type: application/x-msexcel');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="'.$this->Editor->tableName.'.csv"');
            // Add utf-8 signature for windows/excel
            echo chr(0xEF).chr(0xBB).chr(0xBF);
            echo $this->Editor->exportInfo();
            exit();
        }
        else if(isset($_GET['col']))  // Handle GET Column Values Request
        {
            $this->getColVals($_GET['col']);
        }
        else if(isset($_POST) && count($_POST) > 0)  // Handle File Upload Requests
        {
            $this->Editor->setDefaults();
            $this->Editor->handleFileUpload();
        }
        else  // Handle Normal Display Mode
        {
            $this->setHeaderFiles();
            $this->displayHeaderHtml();
            $this->displayHtml();
            $this->displayFooterHtml();
        }
    }

    protected function initiateEditor()
    {
        global $user;

        $tableName = 'tbl_MARS_Report_Data';
        $primaryCol = 'report_data_ID';
        $errorFun = array(&$this,'logError');
        
        if(isset($_GET['award_ID']))
        {
            $query="SELECT * FROM tbl_MARS_Awards LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Awards.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Awards.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE award_ID = '".$_GET['award_ID']."'";
            $queryParams['award_ID'] = $_GET['award_ID'];
            $stmt = DBC::get()->prepare($query);
            $stmt->execute($queryParams);
            $from_award = $stmt->fetch();
        }
        else
            $from_award = [];

        // Set default permissions
        if($user->isLoggedIn)
        {
            if(isset($_GET['read-only']))
            {
                $permissions = 'VXQSHOIUTF';
            }
            else if($user->isAdmin && isset($_GET['asAdmin']))
            {
                $permissions = 'EAVDXQSHOIUTF';
                // Edit, Add, Copy, View, Delete, eXport, Quick search, advanced Search, Hide, Order, Icons, User set rows to display, Table, Filter
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                $permissions = 'EAVXQSHOIUTF';
            }
            else if($user->isOfficer && isset($_GET['asOfficer']))
            {
                $permissions = 'EAVXQSHOIUTF';
            }
            else // Registered User, Edit-mode
            {
                $permissions = 'EAVDIT';
            }
        }
        else // Guest
        {
            $permissions = 'XQSHOUTF';
        }

        // Set up Table Columns
        $tableColumns['report_data_ID'] = array(
            'display_text' => 'Report ID',
            'perms' => 'EVX',
            'input_info' => 'readonly'
        );
        $tableColumns['announce_name'] = array(
            'display_text' => 'Announcement', 
            'display_mask' => "LEFT(announce.ann_Public_Name,100)",
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly',
            'default' => (isset($from_award['ann_Public_Name']) ? $from_award['ann_Public_Name'] : NULL),
            'filter_type' => 'menu'
        );
        $tableColumns['award_ID'] = array(
            'display_text' => 'Award ID',
            'perms' => 'EVAXQSFHO',
            'filter_type' => 'menu',
            'default' => (isset($_GET['award_ID']) ? $_GET['award_ID'] : NULL),
            'req' => 'true'
        );
        $tableColumns['award_Number'] = array(
            'display_text' => 'Award Number',
            'display_mask' => 'award.award_Number',
            'perms' => 'VTXQSFHO',
            'filter_type' => 'menu'
        );
        $tableColumns['people_ID'] = array(
            'display_text' => 'PI', 
            'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                'display_mask' => "concat(people_pi.people_LastName,', ',people_pi.people_FirstName)",
                'alias' => 'people_pi',
                'type' => 'left'
            ),
            'perms' => 'EVTAXQSFHO',
            'filter_type' => 'menu',
            'default' => $user->peopleId,
            'req' => 'true'
        );
        $tableColumns['inst_Name'] = array(
            'display_text' => 'Institution', 
            'display_mask' => 'inst.inst_Name',
            'perms' => 'VTXQSFHO',
            'filter_type' => 'menu'
        );
        $tableColumns['proposal_Title'] = array(
            'display_text' => 'Proposal Title',
            'display_mask' => 'proposal.proposal_Name',
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly',
            'default' => (isset($from_award['proposal_Name']) ? $from_award['proposal_Name'] : NULL),

        );
        $tableColumns['report_period_ID'] = array(
            'display_text' => 'Report Period', 
            'join' => array('table' => 'tbl_MARS_Report_Periods', 'column' => 'report_period_ID',
                'display_mask' => 'report_period.report_period',
                'alias' => 'report_period',
                'type' => 'left'
            ),
            'perms' => 'VXQSFHO',
            'input_info' => 'readonly',
            'default' => (isset($from_award['ann_Reporting_Period']) ? $from_award['ann_Reporting_Period'] : NULL),
            'filter_type' => 'menu'
        );
        $tableColumns['award_inc_ID'] = array(
            'display_text' => 'Award Increment ID',
            'display_mask' => 'increment.award_inc_ID',
            'input_info' => 'readonly',
            'perms' => 'VXQSHO'
        );
        $tableColumns['report_due_date'] = array(
            'display_text' => 'Report Due',
            'display_mask' => 'date_format(increment.award_inc_report_DueDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(increment.award_inc_report_DueDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(increment.award_inc_report_DueDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'TVXQSFHO'
        );
        $tableColumns['report_Final'] = array(
            'display_text' => 'Last',
            'display_mask' => 'increment.award_inc_report_Final',
            'input_info' => 'readonly',
            'perms' => 'TVXQSHO',
            'table_fun' => array(&$this,'centerColumn'), 
            'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
        );
        if($user->isLoggedIn && !isset($_GET['read-only']))
        {
            $tableColumns['report_attachment_DisplayName'] = array(
                'display_text' => 'Report', 
                'perms' => 'EVAXTQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['report_attachment_StoredName'] = array(
                'display_text' => 'Report File Name', 
                'perms' => 'EVAXH', 
                'input_info' => 'readonly size=100'
            );
            $tableColumns['report_financial_DisplayName'] = array(
                'display_text' => 'Financials', 
                'perms' => 'EVAXQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['report_financial_StoredName'] = array(
                'display_text' => 'Financials File Name', 
                'perms' => 'EVAXH', 
                'input_info' => 'readonly size=100'
            );
        }
        $tableColumns['report_data_submittedby'] = array(
            'display_text' => 'Submitted By',
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                'display_mask' => "concat(people_submit.people_LastName,', ',people_submit.people_FirstName)",
                'alias' => 'people_submit',
                'type' => 'left'
            ),
            'default' => $user->peopleId,
            'req' => true
        );
        $tableColumns['report_SubmitFinal'] = array(
            'display_text' => 'Submit Final',
            'perms' => 'EVAXQSFHO',
            'default' => '0',
            'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
        );
        $tableColumns['report_data_datetimestamp'] = array(
            'display_text' => 'Submitted On', 
            'perms' => 'EATVQSFXHO', 
            'display_mask' => 'date_format(report_data_datetimestamp,"%b %d, %Y")', 
            'order_mask' => 'date_format(report_data_datetimestamp,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(report_data_datetimestamp,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'mysql_add_fun' => "NOW()",
            'mysql_edit_fun' => "NOW()"
        );
        if($user->isLoggedIn && !isset($_GET['read-only']) && $user->isManager && isset($_GET['asManager']))
        {
            $tableColumns['report_approvedBy'] = array(
                'display_text' => 'Approved By',
                'perms' => 'EVAXQSFHO',
                'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                    'display_mask' => "concat(people_approve.people_LastName,', ',people_approve.people_FirstName)",
                    'alias' => 'people_approve',
                    'type' => 'left'
                ),
                'default' => $user->peopleId
            );
            $tableColumns['report_approvedOn'] = array(
                'display_text' => 'Approved On', 
                'perms' => 'EVAXQSFHO', 
                'display_mask' => 'date_format(report_approvedOn,"%b %d, %Y")', 
                'order_mask' => 'date_format(report_approvedOn,"%Y-%m-%d %T")',
                'range_mask' => 'date_format(report_approvedOn,"%Y-%m-%d %T")',
                'calendar' => array('js_format' => 'MM dd, yy'),
                'table_fun' => array(&$this,'centerColumn')
            );
        }
        $tableColumns['award_start_date'] = array(
            'display_text' => 'Award Start Date',
            'display_mask' => 'date_format(award.award_StartDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'VXQSFHO'
        );
        $tableColumns['award_end_date'] = array(
            'display_text' => 'Award End Date',
            'display_mask' => 'date_format(award.award_EndDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'VXQSFHO'
        );
        $tableColumns['award_amount'] = array(
            'display_text' => 'Amount',
            'display_mask' => "concat('$',format(award.award_amount,0))",
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'VXQSHO'
        );

        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        $this->Editor->setConfig('tableInfo','cellpadding="1" style="width:100%" align="center" class="mateTable"');
        $this->Editor->setConfig('orderByColumn','report_data_datetimestamp');
        $this->Editor->setConfig('ascOrDesc','desc');
        $this->Editor->setConfig('tableTitle','Reports');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('addRowTitle','Add Report');
        $this->Editor->setConfig('editRowTitle','Edit Report');
        $this->Editor->setConfig('viewRowTitle','View Report');
        $this->Editor->setConfig('tableScreenFun',array(&$this,'tableScreenFun'));
        $this->Editor->setConfig('viewScreenFun',array(&$this,'viewScreenFun'));
        $this->Editor->setConfig('addScreenFun',array(&$this,'addScreenFun'));
        $this->Editor->setConfig('editScreenFun',array(&$this,'editScreenFun'));
        $this->Editor->setConfig('paginationLinks',true);
        $this->Editor->setConfig('displayNum','8');
        $this->Editor->setConfig('displayNumInc','8');
        $this->Editor->setConfig('instanceName',$this->mateInstances[0]);
        $this->Editor->setConfig('modifyRowSets',array(&$this,'changeBgColor'));
        $this->Editor->setConfig('filterPosition','top');
        
        $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Awards AS award ON tbl_MARS_Report_Data.award_ID = award.award_ID LEFT JOIN tbl_MARS_Announce AS announce ON award.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON tbl_MARS_Report_Data.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID LEFT JOIN tbl_MARS_Proposals AS proposal ON award.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Award_Incs AS increment ON tbl_MARS_Report_Data.award_ID = increment.award_ID AND (increment.award_inc_report_ID = report_data_ID OR increment.award_inc_report_ID IS NULL OR increment.award_inc_report_ID = 0 OR increment.award_inc_report_ID = '')");

        if(isset($_GET['award_ID']))
        {
            $this->Editor->setConfig('afterEditFun',array(&$this,'goBack'));
        }

        if($user->isLoggedIn)
        {
            //if(!isset($_GET['quietly']) && $user->isManager && isset($_GET['asManager']))
            if(!isset($_GET['quietly']))
            {
                $this->Editor->setConfig('afterAddFun',array(&$this,'savedReport'));
                $this->Editor->setConfig('afterEditFun',array(&$this,'savedReport'));
            }

            if($user->isAdmin && isset($_GET['asAdmin']))
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','All Reports for This Award');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Reports');
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                if(isset($_GET['pending-only']))
                {
                    if(isset($_GET['award_ID']))
                    {
                        $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()  AND award_ID = '".$_GET['award_ID']."' AND report_SubmitFinal = 1 AND (report_approvedBy IS NULL OR report_approvedBy = 0 OR report_approvedBy = '')");
                        $this->Editor->setConfig('tableTitle','All Reports for This Award Pending Approval (Award Increment)');
                    }
                    else
                    {
                        $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW() AND report_SubmitFinal = 1 AND (report_approvedBy IS NULL OR report_approvedBy = 0 OR report_approvedBy = '') AND (increment.award_inc_report_ID IS NULL OR increment.award_inc_report_ID = 0 OR increment.award_inc_report_ID = '')");
                        $this->Editor->setConfig('tableTitle','All Reports Pending Approval (Award Increment)');
                    }

                    $userActions['award_increment'] = array(&$this,'awardIncrement');
                    $userActions['save_report_id'] = array(&$this,'saveReportID');
                    $this->Editor->setConfig('userActions',$userActions);
                    $userIcons[] = array( 'format_fun' => array(&$this,'getUserIcons'));
                    $this->Editor->setConfig('userIcons',$userIcons);
                }
                else if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','All Reports for This Award');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Reports');
            }
            else if($user->isOfficer && isset($_GET['asOfficer']))
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."' AND award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','Reports for this Award at Your Institution');
                }
                else
                {
                    $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."' OR report_data_submittedby ='".$user->peopleId."'");
                    $this->Editor->setConfig('tableTitle','Reports from Your Institution');
                }
            }
            else // Registerred User
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"people.people_ID = '".$user->peopleId."' AND award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','Your Reports for this Award');
                }
                else
                {
                    $this->Editor->setConfig('sqlFilters',"people.people_ID = '".$user->peopleId."' OR report_data_submittedby ='".$user->peopleId."'");
                    $this->Editor->setConfig('tableTitle','All Your Reports');
                }
            }
        }
    }
            
    public function displayHtml()
    {
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables here

        $defaultSessionData['orderByColumn'] = 'report_data_ID';

        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        if(isset($_GET['report_data_ID']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['report_data_ID'];
        }
        else if(isset($_GET['award_ID']))
        {
            $history = 'false';
            $action = 'add_row';
            $action_info = '';
        }
        else
        {
            $history = 'true';
            $action = 'update_html';
            $action_info = '';
        }

        $javascript = $this->getMateJavaScript($this->mateInstances[0],$defaultSessionData,$history,$action,$action_info);
        echo $javascript;
    }

    public function addCkEditor()
    {
        //$this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'report_Summary");');
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        if(!$user->isLoggedIn || isset($_GET['read-only']) || isset($_GET['award_ID']))
            return $rowSets;

        if($rowInfo['report_SubmitFinal'] != 1)
        {
            $rowSets['bgcolor'] = 'LightYellow';
        }
        else if($user->isLoggedIn && ($rowInfo['people_ID'] === $user->peopleId || $rowInfo['report_data_submittedby'] === $user->peopleId))
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }
        else if($rowInfo['report_Final'] == 1)
        {
            $rowSets['bgcolor'] = 'Lime';
        }
        else if(time() < strtotime($rowInfo['award_end_date']))
        {
            $rowSets['bgcolor'] = 'PaleGreen';
        }
        
        if(empty($rowInfo['award_inc_ID']) || empty($rowInfo['report_due_date']))
        {
            $rowSets['bgcolor'] = 'MistyRose';
        }
        
        return $rowSets;
    }

    public function savedReport($id,$col,$info)
    {
        if ($info['report_SubmitFinal'] == 1)
        {
            global $user;
            $query="SELECT ann_Public_Name, proposal_Name, people_FirstName, people_LastName, people_Email, inst_Name, inst_FO_Contact FROM tbl_MARS_Awards LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Awards.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Awards.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE award_ID = '".$info['award_ID']."'";
            $result = $this->Editor->doQuery($query);
            $row = $result->fetch();
            
            $to = $row['people_Email'];
            if ($row['inst_FO_Contact'] != "") { $to = $to.",".$row['inst_FO_Contact']; };
    
            $headers = "From: go@wvresearch.org\r\n" .
                       "CC: juliana.serafin@wvresearch.org, annette.carpenter@wvresearch.org\r\n" .
                       "Bcc: jack.smith@wvresearch.org\r\n" .
                       "X-mailer: php";
            $headers = "From: go@wvresearch.org\r\n";
            
            $subject = "[GO!] Grant Report Submitted for Approval from ".$row['people_FirstName']." ".$row['people_LastName']." at ".$row['inst_Name'];
            $body = "The grant award below has received a report for approval.\r\n\r\n" .
                    "   Announcement:  ".$row['ann_Public_Name']."\r\n\r\n" .
                    "   Proposal Title:  ".$row['proposal_Name']."\r\n\r\n" .
                    "   Report File Name: ".$info['report_attachment_DisplayName']."\r\n" .
                    "     (https://wvresearch.org/godocs/reports/".$info['report_attachment_StoredName'].")\r\n\r\n" .
                    "GO! System:  https://wvresearch.org/go2/\r\n"; 
    
            mail($to, $subject, $body, $headers);
        }

        if(isset($_GET['award_ID'])) //TODO: what ID should this really be?
        {
            $this->goBack($id,$col,$info);
        }
    }
    
    public function getUserIcons($info)
    {
        $iconHtml = '';
        $numIcons = 0;
        //$iconHtml .= '<li class="award-increment"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'award_increment\',\''.$info['award_inc_ID'].'\');" title="Award Increment"></a></li>';
        if($info['report_Final'] == 1)
        {
            $iconHtml .= '<li class="award-increment"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'save_report_id\',\''.$info['report_data_ID'].'\'); '.$this->mateInstances[0].'.toAjaxTableEditor(\'award_increment\',\''.$info['award_inc_ID'].'\');" title="Approve Final Report"></a></li>';
            $numIcons++; 
        }
        //else if(empty($info['report_due_date']))
        //    $iconHtml .= '<li class="award-increment"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'save_report_id\',\''.$info['report_data_ID'].'\'); '.$this->mateInstances[0].'.toAjaxTableEditor(\'award_increment\',\''.$info['award_inc_ID'].'\');" title="Edit Increment"></a></li>';
        else if(!empty($info['report_due_date']))
        {
            $iconHtml .= '<li class="award-increment"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'save_report_id\',\''.$info['report_data_ID'].'\'); '.$this->mateInstances[0].'.toAjaxTableEditor(\'award_increment\',\''.$info['award_inc_ID'].'\');" title="Award Increment"></a></li>';
            $numIcons++; 
        }
        return array('icon_html' => $iconHtml, 'num_icons' => $numIcons);
    }

    public function saveReportID($report_data_ID)
    {
        $_SESSION['report_ID'] = $report_data_ID;
    }
    
    public function awardIncrement($award_inc_ID)
    {
        global $user;
        
        $query = "SELECT * FROM tbl_MARS_Award_Incs WHERE award_inc_ID = ".$award_inc_ID;
        $result = $this->Editor->doQuery($query);
        
        if($row = $result->fetch())
        {
            $query = "UPDATE tbl_MARS_Report_Data SET report_approvedBy = ".$user->peopleId.", report_approvedOn = NOW() WHERE report_data_ID = ".$_SESSION['report_ID'];        
            error_log($query."\n",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            $result = $this->Editor->doQuery($query);
            
            $query = "UPDATE tbl_MARS_Award_Incs SET award_inc_report_ID = ".$_SESSION['report_ID']." WHERE award_inc_ID = ".$award_inc_ID." AND (award_inc_report_ID IS NULL OR award_inc_report_ID = 0)";
            error_log($query."\n",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
            $result = $this->Editor->doQuery($query);
            
            if($result)
            {
                if($row['award_inc_report_Final'] == 1)
                {
                    $this->Editor->addJavascript('window.location.href = "Increments.php?asManager=T&active-only=T&award_inc_ID='.$row['award_inc_ID'].'"');
                }
                else
                {
                    $query = "INSERT INTO tbl_MARS_Award_Incs (award_ID, award_inc_Date, award_inc_Number, award_inc_submittedby, award_inc_StartDate, award_inc_EndDate, award_inc_report_DueDate) VALUES (".$row['award_ID'].",NOW(),'".$row['award_inc_Number']."',".$user->peopleId.",NOW(),DATE_ADD('".$row['award_inc_EndDate']."',INTERVAL 1 YEAR),DATE_ADD('".$row['award_inc_report_DueDate']."',INTERVAL 1 YEAR))";
                    error_log($query."\n",3,"/home/annech2/westvirginiaresearch.org/go2/error_log");
                    $result = $this->Editor->doQuery($query);
                
                    if($result)
                    {
                        $query = "SELECT LAST_INSERT_ID() AS last_id FROM DUAL";
                        $result = $this->Editor->doQuery($query);
                        
                        if($row = $result->fetch()) 
                        {              
                            $this->Editor->addJavascript('window.location.href = "Increments.php?asManager=T&active-only=T&award_inc_ID='.$row['last_id'].'"');
                        }
                        else
                            $valErrors[] = 'There was an error getting last inserted Award Increment record ID: '.$query;
                    }
                    else
                        $valErrors[] = 'There was an error inserting a new Award Increment record: '.$query;
                }
            }
            else
                $valErrors[] = 'There was an error updating the existing Award Increment record: '.$query;

        }
        else
            $valErrors[] = 'There was an error selecting from the current Award Increment record: '.$query;
            
        if(!empty($valErrors))
            $this->Editor->warnings[] = $valErrors;
            
        return $valErrors;
    }
}
$lte = new Reports();
?>
