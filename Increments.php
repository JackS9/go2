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
require_once('User.class.php');
require_once('DBC.php');
require_once('Common.php');
require_once('php/lang/LangVars-en.php');
require_once('php/AjaxTableEditor.php');

class Increments extends Common
{
    protected $Editor;
    protected $dataDir = '../godocs/letters/';
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
                
        $this->initiateEditor();
        
        if(isset($_POST['json']))
        {
            if(ini_get('magic_quotes_gpc'))
            {
                $_POST['json'] = stripslashes($_POST['json']);
            }
            $this->Editor->data = $this->Editor->jsonDecode($_POST['json'],true);
            $this->Editor->setDefaults();
            $this->Editor->main();
        }
        else if(isset($_GET['mate_export']))
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
        else if(isset($_GET['col']))
        {
            $this->getColVals($_GET['col']);
        }
        else if(isset($_POST) && count($_POST) > 0)
        {
            $this->Editor->setDefaults();
            $this->Editor->handleFileUpload();
        }
        else
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

        $tableName = 'tbl_MARS_Award_Incs';
        $primaryCol = 'award_inc_ID';
        $errorFun = array(&$this,'logError');

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
                $permissions = 'VIT';
            }
        }
        else // Guest
        {
            $permissions = 'XQSHOUTF';
        }

        $tableColumns['award_inc_ID'] = array(
            'display_text' => 'Increment ID', 
            'input_info' => 'readonly',
            'perms' => 'EVX'
        );
        $tableColumns['ann_ID'] = array(
            'display_text' => 'Announcement', 
            'display_mask' => "LEFT(announce.ann_Public_Name,40)",
            //'join' => array('table' => 'tbl_MARS_Announce', 'column' => 'ann_ID',
            //    'display_mask' => "LEFT(tbl_MARS_Announce.ann_Public_Name,40)",
            //    'type' => 'left'
            //),
            'input_info' => 'readonly size=100',
            'perms' => 'VTXQSFHO',
            'filter_type' => 'menu'
        );
        $tableColumns['people_ID'] = array(
            'display_text' => 'PI', 
            'display_mask' => "concat(people.people_LastName,', ',people.people_FirstName)",
            //'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
            //    'display_mask' => "concat(tbl_MARS_People.people_LastName,', ',tbl_MARS_People.people_FirstName)",
            //    'type' => 'left'
            //),
            'perms' => 'VTXQSFHO',
            'filter_type' => 'menu',
            'input_info' => 'readonly'
        );
        $tableColumns['people_Dept1'] = array(
            'display_text' => 'Institution', 
            'display_mask' => 'inst.inst_Name',
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly size=100',
            'filter_type' => 'menu'
        );
        $tableColumns['inst_fo_email'] = array(
            'display_text' => 'Institutional FO E-mail', 
            'display_mask' => 'inst.inst_FO_Contact',
            'perms' => 'V',
        );
        $tableColumns['proposal_Name'] = array(
            'display_text' => 'Title',
            'display_mask' => 'proposal.proposal_Name',
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly size=100'
        );
        $tableColumns['award_ID'] = array(
            'display_text' => 'Award ID',
            'display_mask' => 'award.award_ID',
            'perms' => 'VAXQSFHO',
            'default' => (isset($_GET['award_ID']) ? $_GET['award_ID'] : NULL),
            'req' => 'true'
        );
        $tableColumns['award_number'] = array(
            'display_text' => 'Award Number',
            'display_mask' => 'award.award_Number',
            'input_info' => 'readonly',
            'perms' => 'VTXQSFHO'
        );
        $tableColumns['award_StartDate'] = array(
            'display_text' => 'Award Start',
            'display_mask' => 'date_format(award.award_StartDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_StartDate,"%Y-%m-%d %T")',
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'VXQSFHO'
        );
        $tableColumns['award_EndDate'] = array(
            'display_text' => 'Award End',
            'display_mask' => 'date_format(award.award_EndDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award.award_EndDate,"%Y-%m-%d %T")',
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'VXQSFHO'
        );
        $tableColumns['award_amount'] = array(
            'display_text' => 'Award Amount',
            'display_mask' => "concat('$',format(award.award_amount,0))",
            'table_fun' => array(&$this,'centerColumn'), 
            'input_info' => 'readonly',
            'perms' => 'VXQSHO'
        );
        $tableColumns['award_inc_Number'] = array(
            'display_text' => 'Increment Number',
            'perms' => 'EVTAXQSFHO'
        );
        $tableColumns['award_inc_StartDate'] = array(
            'display_text' => 'Start Date',
            'display_mask' => 'date_format(award_inc_StartDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award_inc_StartDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award_inc_StartDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'mysql_add_fun' => "NOW()",
            'perms' => 'EVTAXQSFHO'
        );
        $tableColumns['award_inc_EndDate'] = array(
            'display_text' => 'End Date',
            'display_mask' => 'date_format(award_inc_EndDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award_inc_EndDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award_inc_EndDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTAXQSFHO'
        );
        $tableColumns['award_inc_amount'] = array(
            'display_text' => 'Amount',
            'display_mask' => "concat('$',format(award_inc_amount,0))",
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTAXQSHO'
        );
        $tableColumns['award_inc_report_Final'] = array(
            'display_text' => 'Last',
            'perms' => 'EVTAXQSFHO',
            'default' => '0',
            'table_fun' => array(&$this,'centerColumn'), 
            'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
        );
        if($user->isLoggedIn && !isset($_GET['read-only']) && $user->isManager && isset($_GET['asManager']))
        {
            $tableColumns['award_inc_Date'] = array(
                'display_text' => 'Increment Approval Date', 
                'display_mask' => 'date_format(award_inc_Date,"%b %d, %Y")', 
                'order_mask' => 'date_format(award_inc_Date,"%Y-%m-%d %T")', 
                'range_mask' => 'date_format(award_inc_Date,"%Y-%m-%d %T")', 
                'calendar' => array('js_format' => 'MM dd, yy'),
                'table_fun' => array(&$this,'centerColumn'), 
                'mysql_add_fun' => "NOW()",
                'perms' => 'EVAXQSFHO'
            );
            $tableColumns['award_inc_submittedby'] = array(
                'display_text' => 'Submitted By',
                'perms' => 'EVAXQSFHO',
                'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                    'display_mask' => "concat(tbl_MARS_People.people_LastName,', ',tbl_MARS_People.people_FirstName)",
                    'type' => 'left'),
                'default' => $user->peopleId
            );
            $tableColumns['award_inc_letter_DisplayName'] = array(
                'display_text' => 'Letter', 
                'perms' => 'EVAXTQSHO', 
                'file_upload' => array(
                    'upload_fun' => array(&$this,'handleDocumentUpload'), 
                    'delete_fun' => array(&$this,'deleteDocumentFile')
                ), 
                'table_fun' => array(&$this,'formatDocument'), 
                'view_fun' => array(&$this,'formatDocument')
            );
            $tableColumns['award_inc_letter_StoredName'] = array(
                'display_text' => 'Letter File Name', 
                'perms' => 'EVAXH', 
                'input_info' => 'readonly size=100'
            );
        }
        $tableColumns['award_inc_report_DueDate'] = array(
            'display_text' => 'Report Due',
            'display_mask' => 'date_format(award_inc_report_DueDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award_inc_report_DueDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award_inc_report_DueDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTAXQSFHO'
        );
        // TODO: Check Logic here.  award_inc_report_ID is used a lot.
        if(!isset($_GET['pending-only']))
        {
            $tableColumns['award_inc_report_ID'] = array(
                'display_text' => 'Report',
                'join' => array('table' => 'tbl_MARS_Report_Data', 'column' => 'report_data_ID',
                    'display_mask' => 'report.report_attachment_StoredName',
                    'alias' => 'report',
                    'type' => 'left'),
                'perms' => 'VTXQSFHO',
                'table_fun' => array(&$this,'centerColumn'), 
                'default' => (isset($_SESSION['report_ID']) ? $_SESSION['report_ID'] : NULL),
                'table_fun' => array(&$this,'formatReport'), 
                'view_fun' => array(&$this,'formatReport')
            );
        }
        
        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        $this->Editor->setConfig('tableInfo','cellpadding="1" style="width:100%" align="center" class="mateTable"');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('orderByColumn','award_inc_EndDate');
        $this->Editor->setConfig('ascOrDesc','desc');
        $this->Editor->setConfig('addRowTitle','Add Award Increment');
        $this->Editor->setConfig('editRowTitle','Edit Award Increment');
        $this->Editor->setConfig('viewRowTitle','View Award Increment');
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
        
        $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Awards AS award ON tbl_MARS_Award_Incs.award_ID = award.award_ID LEFT JOIN tbl_MARS_Announce AS announce ON award.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_Proposals AS proposal ON award.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID");

        if(isset($_GET['award_ID']))
        {
            $this->Editor->setConfig('afterEditFun',array(&$this,'goBack'));
        }

        if($user->isLoggedIn)
        {        
            if(!isset($_GET['quietly']) && $user->isManager && isset($_GET['asManager']))
            {
                $this->Editor->setConfig('afterAddFun',array(&$this,'savedIncrement'));
                $this->Editor->setConfig('afterEditFun',array(&$this,'savedIncrement'));
            }

            if(isset($_GET['active-only']) && !isset($_GET['read-only']))
            {
                $userActions['submit_report'] = array(&$this,'submitReport');                
                $this->Editor->setConfig('userActions',$userActions);
                $userIcons[] = array('format_fun' => array(&$this,'getUserIcons'));
                $this->Editor->setConfig('userIcons',$userIcons);
            }

            if($user->isAdmin && isset($_GET['asAdmin']))
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','All Increments for This Award');
                }
                else if(isset($_GET['active-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()");
                    $this->Editor->setConfig('tableTitle','Active Award Increments');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Award Increments');
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                if(isset($_GET['pending-only']))
                {
                    if(isset($_GET['award_ID']))
                    {
                        $this->Editor->setConfig('sqlFilters',"award_inc_report_DueDate < NOW() AND (award_inc_report_ID = 0 OR award_inc_report_ID = '' OR award_inc_report_ID IS NULL) AND award_ID = '".$_GET['award_ID']."'");
                        $this->Editor->setConfig('tableTitle','All Increments for This Award Pending a Report');
                    }
                    else if(isset($_GET['active-only']))
                    {
                        $this->Editor->setConfig('sqlFilters',"award_inc_report_DueDate < NOW() AND (award_inc_report_ID = 0 OR award_inc_report_ID = '' OR award_inc_report_ID IS NULL) AND award.award_EndDate > NOW()");
                        $this->Editor->setConfig('tableTitle','All Active Award Increments with Overdue Report');
                    }
                    else
                    {
                        //$this->Editor->setConfig('sqlFilters',"award_inc_report_DueDate < NOW() AND (award_inc_report_ID = 0 OR award_inc_report_ID = '' OR award_inc_report_ID IS NULL)");
                        $this->Editor->setConfig('sqlFilters',"award_inc_report_ID = 0 OR award_inc_report_ID = '' OR award_inc_report_ID IS NULL");
                        $this->Editor->setConfig('tableTitle','All Award Increments Pending a Report');
                    }
                    
                    $userActions['submit_report'] = array(&$this,'submitReport');                
                    $this->Editor->setConfig('userActions',$userActions);
                    $userIcons[] = array('format_fun' => array(&$this,'getUserIcons'));
                    $this->Editor->setConfig('userIcons',$userIcons);
                }
                else if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','All Increments for This Award');
                }
                else if(isset($_GET['active-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()");
                    $this->Editor->setConfig('tableTitle','Active Award Increments');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Award Increments');
            }
            else if($user->isOfficer && isset($_GET['asOfficer']))
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."' AND award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','Increments for this Award at Your Institution');
                }
                else
                {
                    $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."'");
                    $this->Editor->setConfig('tableTitle','Award Increments for Your Institution');
                }
            }
            else // Registerred User
            {
                if(isset($_GET['award_ID']))
                {
                    $this->Editor->setConfig('sqlFilters',"(proposal.people_ID = '".$user->peopleId."' OR proposal.proposal_CoPI = '".$user->peopleId."') AND award.award_ID = '".$_GET['award_ID']."'");
                    $this->Editor->setConfig('tableTitle','Increments for this Award');
                }
                else
                {
                    $this->Editor->setConfig('sqlFilters',"proposal.people_ID = '".$user->peopleId."' OR proposal.proposal_CoPI = '".$user->peopleId."'");
                    $this->Editor->setConfig('tableTitle','All Your Award Incremenets');
                }
            }
        }
        else // Is Guest
        {
            $this->Editor->setConfig('sqlFilters',"award.award_EndDate > NOW()");
            $this->Editor->setConfig('tableTitle','Active Award Increments');
        }
    }
    
    public function displayHtml()
    {
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables here

        $defaultSessionData['orderByColumn'] = 'award_inc_EndDate';
        $defaultSessionData['ascOrDesc'] = 'desc';

        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        if(isset($_GET['award_inc_ID']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['award_inc_ID'];
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
        //$this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'proposal.proposal_Summary");');
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        if(isset($_GET['active-only']))
        {
            if(time() > strtotime($rowInfo['award_inc_report_DueDate']) && empty($rowInfo['award_inc_report_ID']))
            {
                $rowSets['bgcolor'] = 'LightYellow';
            }
        }
        else 
        {
            if(time() > strtotime($rowInfo['award_inc_report_DueDate']) && empty($rowInfo['award_inc_report_ID']))
            {
                $rowSets['bgcolor'] = 'LightYellow';
            }
            else if(time() < strtotime($rowInfo['award_EndDate']))
            {
                $rowSets['bgcolor'] = 'PaleGreen';
            }
            
            if(strtotime($rowInfo['award_inc_report_DueDate']) < strtotime("-1 year") && empty($rowInfo['award_inc_report_ID']))
            {
                $rowSets['bgcolor'] = 'MistyRose';
            }
        }

        if($user->isLoggedIn && isset($_GET['asUser']) && ($rowInfo['people_ID'] === $user->peopleId || (isset($rowInfo['proposal.proposal_CoPI']) && $rowInfo['proposal.proposal_CoPI'] === $user->peopleId)))
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }

        return $rowSets;
    }

    public function getUserIcons($info)
    {
        global $user;
        
        $iconHtml = '';
        $numIcons = 0;
        $iconHtml .= '<li class="submit-report"><a href="javascript: void(0);" onclick="'.$this->mateInstances[0].'.toAjaxTableEditor(\'submit_report\',\''.$info['award_ID'].'\');" title="Submit Report"></a></li>';
        $numIcons++;
        return array('icon_html' => $iconHtml, 'num_icons' => $numIcons);
    }
    
    public function formatReport($col,$val,$row)
    {
        $html = '';
        if(strlen($val) > 0)
        {
            if($this->Editor->action == 'update_html')
                $html .= '<div style="text-align: center;">'; 
            else
                $html .= '<div style="text-align: left;">'; 
            $html .= '<a target="_blank" href="../godocs/reports/'.$row[$col].'"><img style="border: none;" src="images/OpenDocument.png" alt="'.$val.'" title="Click to view document" width="16" /></a>';
            $html .= '</div>';
        }
        return $html;
    }

    public function submitReport($award_ID)
    {
        $this->Editor->addJavascript('window.location.href = "Reports.php?award_ID='.$award_ID.'"');
    }

    public function savedIncrement($id,$col,$info)
    {
        global $user;

        $query="SELECT ann_Public_Name, proposal_Name, people_FirstName, people_LastName, people_Email, inst_Name, inst_FO_Contact FROM tbl_MARS_Award_Incs LEFT JOIN tbl_MARS_Awards AS award ON tbl_MARS_Award_Incs.award_ID = award.award_ID LEFT JOIN tbl_MARS_Proposals AS proposal ON award.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Announce AS announce ON award.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE award_inc_ID = ".$info['award_inc_ID'];
        $result = $this->Editor->doQuery($query);
        $row = $result->fetch();
        
        $to = $row['people_Email'];
        if ($row['inst_FO_Contact'] != "") { $to = $to.",".$row['inst_FO_Contact']; };

        $headers = "From: go@wvresearch.org\r\n" .
                   "CC: jan.taylor@wvresearch.org, annette.echols@wvresearch.org,\r\n" .
                   "Bcc: jack.smith@wvresearch.org\r\n" .
                   "X-mailer: php";
                   
        if($info['award_inc_report_Final'] == 1)
        {
            $subject = "[GO!] Grant Award Final Report Approved for ".$row[people_FirstName]." ".$row['people_LastName']." at ".$row['inst_Name'];
            $body = "A final report was received and approved for the following grant award.\r\n\r\n" .
                    "   Announcement:  ".$row['ann_Public_Name']."\r\n" .
                    "   Proposal Title:  ".$row['proposal_Name']."\r\n" .
                    "   PI:  ".$row[people_FirstName]." ".$row['people_LastName']."\r\n" .
                    "   Institution:  ".$row['inst_Name']."\r\n\r\n" .
                    "Congratulations!\r\n\r\n" .
                    "GO! System:  http://wvresearch.org/go2/\r\n"; 
        }
        else if($info['award_inc_report_Final'] != 1)
        {
            $subject = "[GO!] Grant Award Annual Increment Approved for ".$row[people_FirstName]." ".$row['people_LastName']." at ".$row['inst_Name'];
            $body = "The following grant has received an annual award increment.\r\n\r\n" .
                    "   Announcement:  ".$row['ann_Public_Name']."\r\n" .
                    "   Proposal Title:  ".$row['proposal_Name']."\r\n" .
                    "   PI:  ".$row[people_FirstName]." ".$row['people_LastName']."\r\n" .
                    "   Institution:  ".$row['inst_Name']."\r\n\r\n" .
//                    "   Increment Amount:  $".$info['award_inc_amount'].":\r\n\r\n" .
                    "A formal notification with details will follow.  Congratulations!\r\n\r\n" .
                    "GO! System:  http://wvresearch.org/go2/\r\n"; 
        }

        mail($to, $subject, $body, $headers);

        if(isset($_GET['award_ID']))
        {
            $this->goBack($id,$col,$info);
        }
    }
}
$lte = new Increments();
?>
