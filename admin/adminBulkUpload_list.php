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
      //$columns = explode(",",trim(array_shift($lines)));
      $columns = str_getcsv(array_shift($lines), ",", "\"");
  /*
   * Column 0 is for Serial Number
   * */
	  if ($columns[0]=='S No') {
			continue;
	  }
	  $rowcount++;
	  if (strlen(trim($columns[0]))==0) {
		  	//add title to an array
			$b[$rowcount]="Line Number " . $rowcount . " Barcode/Serial No not entered.";
			continue;
	  }
	  /*
	   * Column 1 is for Title 
	   */
	  if (strlen(trim($columns[1]))==0) {
			$b[$rowcount]="Barcode " . $columns[0] . " Title not entered.";
			continue;
	  } else {
	  	 $columns[1] = str_replace("'","\'",$columns[1]);
	  }
	  /*
	   * Column 2 is for Author
	  */
	  	 
	  $columns[2] = str_replace("'","\'",$columns[2]);
	  /*
	   * Column 3 is for Circulation Status
	  */
	  	 
	  if (strlen(trim($columns[3]))==0) {
	  		$columns[3] = "in";
 	  }
	  if (strcmp($columns[3],'Missing')==0) {
	  		$columns[3] = "lst";
 	  }
	  if (strcmp($columns[3],'Active')==0) {
	  		$columns[3] = "in";
 	  }
	  if (strcmp($columns[3],'Inactive')==0) {
	  		$columns[3] = "ina";
 	  }
 	  if (strcmp($columns[3],'Damaged')==0) {
 	  	$columns[3] = "mnd";
 	  }
 	  
 	  /*
 	   * Column 4 is for Reading Level
 	  */
 	  
	  if ((strlen(trim($columns[4]))==0)||(strcmp($columns[4],'X')==0)) {
	  	$columns[4]=9;
	  }
	  
	  /*
	   * Column 5 is for Basket Number
	  */
	  	 
	  if ((strlen(trim($columns[5]))==0)|| (strcmp($columns[5],'XY')==0)) 
	  {
	  	 $columns[5]="Unassigned";
	  }
	  
	  /*
	   * Column 6 is for Comments
	  */
	  	 
	  if (strlen(trim($columns[6]))==0) {
			$columns[6] = ' ';
	  }
	   
	  $import = new ImportQuery();
	  $bibid = $import->alreadyInDB($columns[1]);
	  if ($bibid==0) {
 		  $lastinsertid = $import->insertBiblio($columns);
		  if ($lastinsertid==0) {
		  	//add title to an array
			$b[$rowcount]="Record " . $rowcount . " Unknown error.";
			continue;
		  }
		  $result=$import->insertBiblioCopy($columns,$lastinsertid);
		  if($result!=null)
		  {
		  	$b[$rowcount]=$result;
		  }
	  } else{
	  	  $result=$import->insertBiblioCopy($columns,$bibid);
	  	  if($result!=null)
	  	  {
	  	  	$b[$rowcount]=$result;
	  	  }
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