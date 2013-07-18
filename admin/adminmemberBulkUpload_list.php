<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
 /******************************************************************************
 * Import data access component for library MEMBERS
 *
 *                  CHANGE HISTORY
 *     #C6 - Its a feature for bulkupload of members into the library in admin section 
 *     @author Kiran Kumar Reddy and Saiteja Bogade
 *
 ******************************************************************************
 */
 
  require_once("../shared/common.php");
  require_once("../classes/Form.php");
  require_once("../classes/CircQuery.php");
  require_once("../classes/Localize.php");
  require_once("../classes/MemberImportQuery.php");
  

  require_once("../classes/Date.php");  $tab = 'admin';
  $nav = "Member Bulkupload";

  $focus_form_name = "memberbulk_upload";
  $focus_form_field = "date";

  require_once("../shared/logincheck.php"); 
 
  $loc = new Localize(OBIB_LOCALE,$tab);
 
  function run_batch($lines, $date)	{
  	 $rowcount = 0;
	 $b = array();
     while(count($lines)) {
      //$columns = explode(",",trim(array_shift($lines)));
      $columns = str_getcsv(array_shift($lines), ",", "\"");
	  if ($columns[0]=='First Name') {
			continue;
	  }
	  $rowcount++;
	  if (strlen(trim($columns[0]))==0) {
		  	//add title to an array
			$b[$rowcount]="Record " . $rowcount . " first name not entered.";
			continue;
	  } else {
	  	 $columns[0] = str_replace("'","\'",$columns[0]);
	  }
	  if (strlen(trim($columns[1]))==0) {
			//$b[$rowcount]="Record " . $rowcount . " last name not entered.";
	  } else {
	  	 $columns[1] = str_replace("'","\'",$columns[1]);
	  }

	  if (strlen(trim($columns[2]))==0) {
	  	$b[$rowcount]="Record " . $rowcount . " Gender is not entered.";
	  	continue;
	  }
	   
	  if (strlen(trim($columns[3]))==0) {
			$b[$rowcount]="Record " . $rowcount . " school Id  not entered.";
			continue;
	  }else {
	  	 $columns[3] = str_replace("'","\'",$columns[3]);
	  }
	  if (strlen(trim($columns[4]))==0) {
			$b[$rowcount]="Record " . $rowcount . " standard not entered.";
			continue;
	  }
	  if (strlen(trim($columns[5]))==0) {
	  	//			$b[$rowcount]="Record " . $rowcount . " grade not entered.";
	  }
	   
	  if (strlen(trim($columns[6]))==0) {
//			$b[$rowcount]="Record " . $rowcount . " roll no not entered.";
	  }
	   if (strlen(trim($columns[7]))==0) {
		//	$b[$rowcount]="Record " . $rowcount . " parent name  not entered.";
	  } else {
	  	 $columns[6] = str_replace("'","\'",$columns[7]);
	  }
	  
       if (strlen(trim($columns[8]))==0) {
		//	$b[$rowcount]="Record " . $rowcount . " Occupation of parent not entered.";
	  } else {
	  	 $columns[7] = str_replace("'","\'",$columns[8]);
	  }
	  
	  if (strlen(trim($columns[9]))==0) {
	  //	$b[$rowcount]="Record " . $rowcount . " Mother Tongue  not entered.";
	  } else {
	  	$columns[8] = str_replace("'","\'",$columns[9]);
	  }
	   
	  	   
	  $import = new MemberImportQuery();
//	  $mbrid = $import->alreadyInDB($columns[0], $columns[1], $columns[7], $columns[9]);
//	  if ($mbrid==0) {
 		  $lastinsertid = $import->insertMember($columns);
		  if ($lastinsertid==0) {
		  	//add title to an array
			$b[$rowcount]="Record " . $rowcount . " not inserted !!!!";
			continue;
		  }
		  
//	  } else{
//	  	 $b[$rowcount]="Record " . $rowcount . " member already exists in database.";
//			continue;
//	  }		  
    }
	return $b;
  }
 
    
  function layout_links() {
    global $loc;
    echo '<a href="../layouts/default/memberbulkuploadtitles.xlsx?name=upload_format">'.$loc->getText('adminmemberBulkUpload_list_format_of_file_to_be_uploaded').'</a>';
  }

  $form = array(
    'title'=>$loc->getText("adminmemberBulkUpload_list_Bulk_upload_of_new_members"),
    'name'=>'memberbulk_upload',
    'action'=>'../admin/adminmemberBulkUpload_list.php',
    'enctype'=>'multipart/form-data',
    'submit'=>$loc->getText('Upload'),
    'fields'=>array(
      array('name'=>'date', 'title'=>$loc->getText('Date:'), 'type'=>'date', 'default'=>'today'),
      array('name'=>'Excel_file', 'title'=>$loc->getText('adminBulkUpload_list_file'), 'type'=>'file', 'required'=>1),
    ),
  );
  list($values, $errs) = Form::getCgi_el($form['fields']);
  if(!$values['_posted'] or $errs){
    include_once("../shared/header.php");
    if (isset($_REQUEST['msg'])) {
      echo '<font class="error">'.H($_REQUEST['msg']).'</font>';
    }
    $form['values'] = $values;
    $form['errors'] = $errs;
    Form::display($form);
    layout_links();
    include_once("../shared/footer.php");
    exit();
  }
  
  $lines = file($values['Excel_file']['tmp_name']);
  if($lines === false)
    $errors = array("Couldn't read file: ".$values['Excel_file']['tmp_name']);
  else
    $errors = run_batch($lines, $values['date']);
  if($errors) {
    include_once("../shared/header.php");
    echo '<font class="error">'.$loc->getText("Actions which did not produce an error have completed. Think carefully before uploading the same file again, or some circulations may be recorded twice.").'</font>';
    echo '<div class="errorbox">';
    echo '<span class="errorhdr">'.$loc->getText('Errors').'</span>';
    echo '<ul>';
    foreach ($errors as $e) {
      echo '<li>'.H($e).'</li>';
    }
    echo '</ul></div>';
    Form::display($form);
    layout_links();
    include_once("../shared/footer.php");
    exit();
  } else
    header("Location: ../admin/adminmemberBulkUpload_list.php?msg=".U($loc->getText("adminmemberBulkUpload_succesful")));
?>