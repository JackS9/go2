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

class Awards extends Common
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

        $tableName = 'tbl_MARS_Awards';
        $primaryCol = 'award_ID';
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
                $permissions = 'EAVDIT';
            }
        }
        else // Guest
        {
            $permissions = 'XQSHOUTF';
        }

        $tableColumns['award_ID'] = array(
            'display_text' => 'Award ID', 
            'input_info' => 'readonly',
            'perms' => 'VTEAVX'
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
        $tableColumns['proposal_Name'] = array(
            'display_text' => 'Title',
            'display_mask' => 'proposal.proposal_Name',
            'perms' => 'VTXQSFHO',
            'input_info' => 'readonly size=100'
        );
        $tableColumns['award_Number'] = array(
            'display_text' => 'Award Number',
            'display_mask' => 'award_Number',
            'perms' => 'EVTXQSFHO'
        );
        $tableColumns['award_StartDate'] = array(
            'display_text' => 'Start Date',
            'display_mask' => 'date_format(award_StartDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award_StartDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award_StartDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTXQSFHO'
        );
        $tableColumns['award_EndDate'] = array(
            'display_text' => 'End Date',
            'display_mask' => 'date_format(award_EndDate,"%b %d, %Y")', 
            'order_mask' => 'date_format(award_EndDate,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(award_EndDate,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, yy'),
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTXQSFHO'
        );
        $tableColumns['award_amount'] = array(
            'display_text' => 'Amount',
            'display_mask' => "concat('$',format(award_amount,0))",
            'table_fun' => array(&$this,'centerColumn'), 
            'perms' => 'EVTXQSHO'
        );
        if($user->isLoggedIn && !isset($_GET['read-only']) && $user->isManager && isset($_GET['asManager']))
        {
            $tableColumns['award_Date'] = array(
                'display_text' => 'Approval Date', 
                'display_mask' => 'date_format(award_Date,"%b %d, %Y")', 
                'order_mask' => 'date_format(award_Date,"%Y-%m-%d %T")', 
                'range_mask' => 'date_format(award_Date,"%Y-%m-%d %T")', 
                'calendar' => array('js_format' => 'MM dd, yy'),
                'table_fun' => array(&$this,'centerColumn'), 
                'mysql_add_fun' => "NOW()",
                'perms' => 'EVAXQSFHO'
            );
            $tableColumns['award_submittedby'] = array(
                'display_text' => 'Submitted By',
                'perms' => 'EVAXQSFHO',
                'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                    'display_mask' => "concat(tbl_MARS_People.people_LastName,', ',tbl_MARS_People.people_FirstName)",
                    'type' => 'left'),
                'default' => $user->peopleId,
                'req' => true
            );
        }

        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        $this->Editor->setConfig('tableInfo','cellpadding="1" style="width:100%" align="center" class="mateTable"');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('orderByColumn','award_EndDate');
        $this->Editor->setConfig('ascOrDesc','desc');
        $this->Editor->setConfig('addRowTitle','Add Award');
        $this->Editor->setConfig('editRowTitle','Edit Award');
        $this->Editor->setConfig('viewRowTitle','View Award');
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

        $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Awards.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Awards.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID");

        if(isset($_GET['award_ID']))
        {
            $this->Editor->setConfig('afterEditFun',array(&$this,'goBack'));
        }

        if($user->isLoggedIn)
        {        
            if(!isset($_GET['quietly']) && $user->isManager && isset($_GET['asManager']))
            {
                $this->Editor->setConfig('afterAddFun',array(&$this,'savedAward'));                
                $this->Editor->setConfig('afterEditFun',array(&$this,'savedAward'));
                //TODO: How to tell if just a change and not new?
            }

            if($user->isAdmin && isset($_GET['asAdmin']))
            {
                if(isset($_GET['active-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award_EndDate > NOW()");
                    $this->Editor->setConfig('tableTitle','Active Awards');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Awards');
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                if(isset($_GET['active-only']))
                {
                    $this->Editor->setConfig('sqlFilters',"award_EndDate > NOW()");
                    $this->Editor->setConfig('tableTitle','Active Awards');
                }
                else
                    $this->Editor->setConfig('tableTitle','All Awards');
            }
            else if($user->isOfficer && isset($_GET['asOfficer']))
            {
                $this->Editor->setConfig('sqlFilters',"inst.inst_Name = '".$user->organization."'");
                $this->Editor->setConfig('tableTitle','Awards for Your Institution');
            }
            else // Registerred User
            {
                $this->Editor->setConfig('sqlFilters',"proposal.people_ID = '".$user->peopleId."' OR proposal.proposal_CoPI = '".$user->peopleId."'");
                $this->Editor->setConfig('tableTitle','All Your Awards');
            }
        }
        else // Is Guest
        {
            $this->Editor->setConfig('sqlFilters',"award_EndDate > NOW()");
            $this->Editor->setConfig('tableTitle','Active Awards');
        }
    }
    
    public function displayHtml()
    {
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables here

        $defaultSessionData['orderByColumn'] = 'award_EndDate';
        $defaultSessionData['ascOrDesc'] = 'desc';

        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        if(isset($_GET['award_ID']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['award_ID'];
        }
        else if(isset($_GET['proposal_ID']))
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
//        $this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'proposal.proposal_Summary");');
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        if(!isset($_GET['active-only']))
        {
            if(time() < strtotime($rowInfo['award_EndDate']))
            {
                $rowSets['bgcolor'] = 'PaleGreen';
            }
        }

        if($user->isLoggedIn && ($rowInfo['people_ID'] === $user->peopleId || (isset($rowInfo['proposal.proposal_CoPI']) && $rowInfo['proposal.proposal_CoPI'] === $user->peopleId)))
        {
            $rowSets['bgcolor'] = 'LightCyan';
        }

        return $rowSets;
    }

    public function savedAward($id,$col,$info)
    {
        global $user;

        $query="SELECT ann_Public_Name, proposal_Name, people_FirstName, people_LastName, inst_Name, people_Email, inst_FO_Contact FROM tbl_MARS_Awards LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Awards.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Awards.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE award_ID = ".$info['award_ID'];
        $result = $this->Editor->doQuery($query);
        $row = $result->fetch();
        
        $to = $row['people_Email'];
        if ($row['inst_FO_Contact'] != "") { $to = $to.",".$row['inst_FO_Contact']; };

        $headers = "From: go@wvresearch.org\r\n" .
                   "CC: jan.taylor@wvresearch.org, annette.echols@wvresearch.org,\r\n" .
                   "Bcc: jack.smith@wvresearch.org\r\n" .
                   "X-mailer: php";
        $subject = "[GO!] Grant Awarded to ".$row[people_FirstName]." ".$row['people_LastName']." at ".$row['inst_Name'];
        $body = "The following grant proposal has been awarded.\r\n\r\n" .
                "   Announcement:  ".$row['ann_Public_Name']."\r\n" .
                "   Proposal Title:  ".$row['proposal_Name']."\r\n" .
                "   PI:  ".$row[people_FirstName]." ".$row['people_LastName']."\r\n" .
                "   Institution:  ".$row['inst_Name']."\r\n" .
                "   Amount:  $".$info['award_amount'].":\r\n" .
                "   Start Date:  ".$info['award_StartDate'].":\r\n" .
                "   End Date:  ".$info['award_EndDate'].":\r\n\r\n" .
                "A formal notification with details will follow.  Congratulations!\r\n\r\n" .
                "GO! System:  http://wvresearch.org/go2/\r\n"; 

        mail($to, $subject, $body, $headers);

        // Add associated Increment and bring up Editor
        
        $query = "SELECT * FROM tbl_MARS_Awards WHERE award_ID = ".$info['award_ID'];
        $result = $this->Editor->doQuery($query);
        
        if($row = $result->fetch())
        {        
            $query = "INSERT INTO tbl_MARS_Award_Incs (award_ID, award_inc_Date, award_inc_Number, award_inc_submittedby, award_inc_StartDate, award_inc_EndDate, award_inc_report_DueDate) VALUES (".$row['award_ID'].",NOW(),'".$row['award_Number']."',".$user->peopleId.",NOW(),DATE_ADD('".$row['award_StartDate']."',INTERVAL 1 YEAR),DATE_ADD('".$row['award_StartDate']."',INTERVAL 1 YEAR))";
            error_log($query);
            $result = $this->Editor->doQuery($query);
        
            if($result)
            {
                $query = "SELECT LAST_INSERT_ID() AS last_id FROM DUAL";
                $result = $this->Editor->doQuery($query);
                if($row = $result->fetch())               
                    $this->Editor->addJavascript('window.location.href = "Increments.php?asManager=T&active-only=T&award_inc_ID='.$row['last_id'].'"');
                else
                    $valErrors[] = 'There was an error getting last inserted Award Increment record ID: '.$query;
            }
            else
                $valErrors[] = 'There was an error inserting a new Award Increment record: '.$query;
        }
        else
            $valErrors[] = 'There was an error getting new Award record: '.$query;        
        return $valErrors;
    }

    public function changedAward($id,$col,$info)
    {
        global $user;

        $query="SELECT ann_Public_Name, proposal_Name, people_FirstName, people_LastName, inst_Name, people_Email, inst_FO_Contact FROM tbl_MARS_Awards LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Awards.proposal_ID = proposal.proposal_ID LEFT JOIN tbl_MARS_Announce AS announce ON tbl_MARS_Awards.ann_ID = announce.ann_ID LEFT JOIN tbl_MARS_People AS people ON proposal.people_ID = people.people_ID LEFT JOIN tbl_MARS_Inst AS inst ON people.people_Dept1 = inst.inst_ID WHERE award_ID = ".$info['award_ID'];
        $result = $this->Editor->doQuery($query);
        $row = $result->fetch();
        
        $to = $row['people_Email'];
        if ($row['inst_FO_Contact'] != "") { $to = $to.",".$row['inst_FO_Contact']; };

        $headers = "From: go@wvresearch.org\r\n" .
                   "CC: jan.taylor@wvresearch.org, annette.echols@wvresearch.org,\r\n" .
                   "Bcc: jack.smith@wvresearch.org\r\n" .
                   "X-mailer: php";
        $subject = "[GO!] Grant Award Modification for ".$row[people_FirstName]." ".$row['people_LastName']." at ".$row['inst_Name'];
        $body = "The following grant award has been modified.\r\n\r\n" .
                "   Announcement:  ".$row['ann_Public_Name']."\r\n" .
                "   Proposal Title:  ".$row['proposal_Name']."\r\n" .
                "   PI:  ".$row[people_FirstName]." ".$row['people_LastName']."\r\n" .
                "   Institution:  ".$row['inst_Name']."\r\n" .
                "   Amount:  $".$info['award_amount'].":\r\n" .
                "   Start Date:  ".$info['award_StartDate'].":\r\n" .
                "   End Date:  ".$info['award_EndDate'].":\r\n\r\n" .
                "A formal notification with details will follow.\r\n\r\n" .
                "GO! System:  http://wvresearch.org/go2/\r\n"; 

        mail($to, $subject, $body, $headers);

        if(isset($_GET['award_ID']))
        {
            $this->goBack($id,$col,$info);
        }
    }
}
$lte = new Awards();
?>
