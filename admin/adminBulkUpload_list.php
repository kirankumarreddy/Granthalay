<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
 /******************************************************************************
 * Import data access component for library bibliographies
 *
 *                  CHANGE HISTORY
 *     #C3 - Its a feature for bulkupload of titles and copies of an items in admin section 
 *     @author Kiran Kumar Reddy and Saiteja Bogade
 *
 ******************************************************************************
 */
 
  require_once("../shared/common.php");
  require_once("../classes/Form.php");
  require_once("../classes/CircQuery.php");
  require_once("../classes/Localize.php");
  require_once("../classes/ImportQuery.php");
  

  require_once("../classes/Date.php");  $tab = 'admin';
  $nav = "Bulkupload";

  $focus_form_name = "bulk_upload";
  $focus_form_field = "date";

  require_once("../shared/logincheck.php"); 
 
  $loc = new Localize(OBIB_LOCALE,$tab);
 
  function run_batch($lines, $date)	{
  	 $rowcount = 0;
	 $b = array();
     while(count($lines)) {
      $columns = explode(",",trim(array_shift($lines)));
	  if ($columns[0]=='Barcode number') {
			continue;
	  }
	  $rowcount++;
	  if (strlen(trim($columns[0]))==0) {
		  	//add title to an array
			$b[$rowcount]="Record " . $rowcount . " Barcode not entered.";
			continue;
	  }
	  if (strlen(trim($columns[1]))==0) {
			$b[$rowcount]="Record " . $rowcount . " Title not entered.";
			continue;
	  }
	  if (strlen(trim($columns[3]))==0) {
	  		$columns[3] = "Active";
 	  }
	  if (strlen(trim($columns[4]))==0) {
			$b[$rowcount]="Record " . $rowcount . " Reading Level not entered.";
			continue;
	  }
	  if (strlen(trim($columns[5]))==0) {
			$b[$rowcount]="Record " . $rowcount . " Basket not entered.";
			continue;
	  }
	  
	  $import = new ImportQuery();
	  $bibid = $import->alreadyInDB(str_replace("'","\'",$columns[1]));
	  if ($bibid==0) {
 		  $lastinsertid = $import->insertBiblio($columns);
		  if ($lastinsertid==0) {
		  	//add title to an array
			$b[$rowcount]="Record " . $rowcount . " Unknown error.";
			continue;
		  }
		  
		  $import->insertBiblioCopy($columns,$lastinsertid);
	  } else{
	  	  $import->insertBiblioCopy($columns,$bibid);
	  }		  
    }
	return $b;
  }
 
    
  function layout_links() {
    global $loc;
    echo '<a href="../layouts/default/bulkuploadtitles.xlsx?name=upload_format">'.$loc->getText('adminBulkUpload_list_format_of_file_to_be_uploaded').'</a>';
  }

  $form = array(
    'title'=>$loc->getText("adminBulkUpload_list_Bulk_upload_of_new_bibliographies"),
    'name'=>'bulk_upload',
    'action'=>'../admin/adminBulkUpload_list.php',
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
    header("Location: ../admin/adminBulkUpload_list.php?msg=".U($loc->getText("adminBulkUpload_succesful")));
?>