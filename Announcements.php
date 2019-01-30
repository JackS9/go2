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

class Announcements extends Common
{
    protected $Editor;
    protected $dataDir = '../godocs/';
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

        $tableName = 'tbl_MARS_Announce';
        $primaryCol = 'ann_ID';
        $errorFun = array(&$this,'logError');

        if($user->isLoggedIn)
        {
            if($user->isAdmin && isset($_GET['asAdmin']))
            {
                $permissions = 'EAVDXQSHOIUTF';
                // Edit, Add, Copy, View, Delete, eXport, Quick search, advanced Search, Hide, Order, Icons, User set rows to display, Table, Filter
            }
            else if($user->isManager && isset($_GET['asManager']))
            {
                $permissions = 'EAVXQSHOIUTF';
            }
            else if(isset($_GET['read-only']))
            {
                $permissions = 'VXQSHOIUTF';
            }
            else
            {
                $permissions = 'AVXQSHOIUTF';
            }
        }
        else
        {
            $permissions = 'VQSHOIUTF';
        }

        $tableColumns['ann_ID'] = array(
            'display_text' => 'ID', 
            'perms' => 'VX'
        );
        $tableColumns['rfp_type_ID'] = array(
            'display_text' => 'RFP Type', 
            'perms' => 'EVTAXQSFHO',
            'join' => array('table' => 'tbl_MARS_RFP_Types', 'column' => 'rfp_type_ID',
                'display_mask' => 'rfp_type_name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Public_Name'] = array(
            'display_text' => 'Announcement Title', 
            'perms' => 'EVTAXQSFHO',
            'textarea' => array('rows' => 3, 'cols' => 100)
        );
        $tableColumns['ann_Entity_Eligibility'] = array(
            'display_text' => 'Entity Eligibility', 
            'perms' => 'EVTAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Entities', 'column' => 'entity_ID',
                'display_mask' => 'entity_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_PI_Eligiblity'] = array(
            'display_text' => 'PI Eligibility', 
            'perms' => 'EVTAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Eligible_PIs', 'column' => 'pi_ID',
                'display_mask' => 'pi_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Review_Requirement'] = array(
            'display_text' => 'Review Requirement', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Rev_Reqs', 'column' => 'revreq_ID',
                'display_mask' => 'revreq_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Grant_Instrument'] = array(
            'display_text' => 'Grant Instrument', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Instrument', 'column' => 'instrument_ID',
                'display_mask' => 'instrument_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Grant_Type'] = array(
            'display_text' => 'Grant Type', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Grant_Type', 'column' => 'type_ID',
                'display_mask' => 'type_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Grant_Agency'] = array(
            'display_text' => 'Grant Agency', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Agencies', 'column' => 'agency_ID',
                'display_mask' => 'agency_Name',
                'type' => 'left'),
            'filter_type' => 'menu',
            'req' => true
        );
        $tableColumns['ann_Agency_Other'] = array(
            'display_text' => 'Agency (Other)', 
            'perms' => 'EVAXQSFHO',
            'filter_type' => 'menu',
            'textarea' => array('rows' => 2, 'cols' => 100)
        );
        $tableColumns['ann_Date_Announce'] = array(
            'display_text' => 'Announce Date', 
            'perms' => 'EVAQSFXHO', 
            'display_mask' => 'date_format(ann_Date_Announce,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_Date_Announce,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_Date_Announce,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'table_fun' => array(&$this,'centerColumn')
        );
        $tableColumns['ann_Date_Open'] = array(
            'display_text' => 'Open Date', 
            'perms' => 'EVAQSFXHO', 
            'display_mask' => 'date_format(ann_Date_Open,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_Date_Open,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_Date_Open,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'table_fun' => array(&$this,'centerColumn')
        );
        $tableColumns['ann_Date_Close'] = array(
            'display_text' => 'Close Date', 
            'perms' => 'EVATQSFXHO', 
            'display_mask' => 'date_format(ann_Date_Close,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_Date_Close,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_Date_Close,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'table_fun' => array(&$this,'centerColumn')
        );
        $tableColumns['ann_Date_Award'] = array(
            'display_text' => 'Award Date', 
            'perms' => 'EVAQSFXHO', 
            'display_mask' => 'date_format(ann_Date_Award,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_Date_Award,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_Date_Award,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'table_fun' => array(&$this,'centerColumn')
        );
        $tableColumns['ann_Date_Expire'] = array(
            'display_text' => 'Expire Date', 
            'perms' => 'EVAQSFXHO', 
            'display_mask' => 'date_format(ann_Date_Expire,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_Date_Expire,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_Date_Expire,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'table_fun' => array(&$this,'centerColumn')
        );
        $tableColumns['ann_PDF_DisplayName'] = array(
            'display_text' => 'PDF', 
            'perms' => 'EVAXTQSHO', 
            'file_upload' => array(
                'upload_fun' => array(&$this,'handleDocumentUpload'), 
                'delete_fun' => array(&$this,'deleteDocumentFile')
            ), 
            'table_fun' => array(&$this,'formatDocument'), 
            'view_fun' => array(&$this,'formatDocument')
        );
        $tableColumns['ann_PDF_StoredName'] = array(
            'display_text' => 'Stored Name', 
            'perms' => 'VXH', 
        );
        $tableColumns['ann_Narrative'] = array(
            'display_text' => 'Narrative', 
            'perms' => 'EVAXQSFHO',
            'textarea' => array('rows' => 15, 'cols' => 100) 
        );
        $tableColumns['ann_External'] = array(
            'display_text' => 'External Review', 
            'perms' => 'EVAXQSFHO',
            'default' => '0',
            'checkbox' => array('checked_value' => '1', 'un_checked_value' => '0')
        );
        $tableColumns['ann_Reviewer_Count'] = array(
            'display_text' => 'Reviewer Count', 
            'perms' => 'EVAXQSFHO'
        );
        $tableColumns['ann_Reporting_Period'] = array(
            'display_text' => 'Reporting Period', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_Report_Periods', 'column' => 'report_period_ID',
                'display_mask' => 'report_period',
                'type' => 'left'),
            'req' => true
        );
        $tableColumns['ann_submittedby'] = array(
            'display_text' => 'Submitted By', 
            'perms' => 'EVAXQSFHO',
            'join' => array('table' => 'tbl_MARS_People', 'column' => 'people_ID',
                'display_mask' => "concat(tbl_MARS_People.people_LastName,', ',tbl_MARS_People.people_FirstName)",
                'type' => 'left'),
            'default' => $user->peopleId,
            'req' => true
        );
        $tableColumns['ann_datetimestamp'] = array(
            'display_text' => 'Submitted On', 
            'perms' => 'VQSFXHO', 
            'display_mask' => 'date_format(ann_datetimestamp,"%b %d, %Y")', 
            'order_mask' => 'date_format(ann_datetimestamp,"%Y-%m-%d %T")',
            'range_mask' => 'date_format(ann_datetimestamp,"%Y-%m-%d %T")',
            'calendar' => array('js_format' => 'MM dd, y'),
            'mysql_add_fun' => "NOW()",
            'mysql_edit_fun' => "NOW()"
        );
        if ($user->isLoggedIn && ($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager'])))
        {
            $tableColumns['submitted_proposals'] = array(
                'display_text' => 'Submitted Proposals',
                'perms' => 'VXQSHO',
                'display_mask' => "GROUP_CONCAT(proposal.proposal_Name SEPARATOR \"&#13;&#10* \")",
                'table_fun' => array(&$this,'formatProposals'),
                'view_fun' => array(&$this,'formatProposals')
            );
        }

        $this->Editor = new AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns);
        $this->Editor->setConfig('tableInfo','cellpadding="1" width="1100" align="center" class="mateTable"');
        $this->Editor->setConfig('tableTitle','Funding Opportunity Announcements');
        $this->Editor->setConfig('iconTitle','Actions');
        $this->Editor->setConfig('orderByColumn','ann_Date_Close');
        $this->Editor->setConfig('ascOrDesc','desc');
        $this->Editor->setConfig('addRowTitle','Add Announcement');
        $this->Editor->setConfig('editRowTitle','Edit Announcement');
        $this->Editor->setConfig('viewRowTitle','View Announcement');
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
        
        if ($user->isLoggedIn)
        {
            if(($user->isAdmin && isset($_GET['asAdmin'])) || ($user->isManager && isset($_GET['asManager'])))
            {
                $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Announce.ann_ID = proposal.ann_ID");
                $this->Editor->setConfig('groupByClause',"GROUP BY tbl_MARS_Announce.ann_ID");
            }
            else
                $this->Editor->setConfig('customJoin',"LEFT JOIN tbl_MARS_Proposals AS proposal ON tbl_MARS_Announce.ann_ID = proposal.ann_ID AND proposal.people_ID = '".$user->peopleId."'");
        }
    }
        
    public function displayHtml()
    {
        $html = $this->getMateHtml($this->mateInstances[0]);
        echo $html;
            
        // Set default session configuration variables here

        $defaultSessionData['orderByColumn'] = 'ann_Date_Close';

        $defaultSessionData = base64_encode($this->Editor->jsonEncode($defaultSessionData));

        if(isset($_GET['ann_ID']))
        {
            $history = 'false';
            $action = 'edit_row';
            $action_info = $_GET['ann_ID'];
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
        $this->Editor->addJavascript('addCkEditor("'.$this->mateInstances[0].'ann_Narrative");');
    }

    public function formatProposals($col,$val,$row)
    {
        global $user;
        $html = '<textarea readonly="readonly" rows="10" cols="120">* '.$val.'</textarea>';
        return $html;
    }

    public function changeBgColor($rowSets,$rowInfo,$rowNum)
    {
        global $user;

        $result = $this->Editor->doQuery("SELECT * from tbl_MARS_Announce WHERE ann_ID = '".$rowInfo['ann_ID']."' AND ann_Date_Close > NOW()");
        if($resultRow = $result->fetch())
        {
            $rowSets['bgcolor'] = 'PaleGreen';
        }
        return $rowSets;
    }
}
$lte = new Announcements();
?>
