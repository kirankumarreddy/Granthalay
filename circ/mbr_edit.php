<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "edit";
  $restrictInDemo = true;
  require_once("../shared/logincheck.php");

  require_once("../classes/Member.php");
  require_once("../classes/MemberQuery.php");
  require_once("../classes/DmQuery.php");
  require_once("../functions/errorFuncs.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Checking for post vars.  Go back to form if none found.
  #****************************************************************************

  if (count($_POST) == 0) {
    header("Location: ../circ/index.php");
    exit();
  }

  #****************************************************************************
  #*  Validate data
  #****************************************************************************
  $mbrid = $_POST["mbrid"];
  $mbrQ = new MemberQuery();
  $mbrQ->connect();
  $prev_mbr=$mbrQ->get($mbrid);
  $schoolid=$prev_mbr->getSchoolId();
  $prev_standard=$prev_mbr->getStandard();
  $prev_grade=$prev_mbr->getGrade();
  
  $mbr = new Member();
  $mbr->setMbrid($_POST["mbrid"]);
  
  $mbr->setBarcodeNmbr($_POST["barcodeNmbr"]);
  $_POST["barcodeNmbr"] = $mbr->getBarcodeNmbr();
  
  $mbr->setLastChangeUserid($_SESSION["userid"]);
  $mbr->setLastName($_POST["lastName"]);
  $_POST["lastName"] = $mbr->getLastName();
  $mbr->setFirstName($_POST["firstName"]);
  $_POST["firstName"] = $mbr->getFirstName();
  $mbr->setAddress($_POST["address"]);
  $_POST["address"] = $mbr->getAddress();
  
  $mbr->setGender($_POST["gender"]);
  $_POST["gender"] = $mbr->getGender();
  
  $mbr->setGrade($_POST["grade"]);
  $_POST["grade"] = $mbr->getGrade();
  
  $mbr->setSchoolId($_POST["school"]);
  $_POST["school"] = $mbr->getSchoolId();
  
  $mbr->setSchoolTeacher($_POST["schoolTeacher"]);
  $_POST["schoolTeacher"] = $mbr->getSchoolTeacher();
  
  $mbr->setParentName($_POST["parentname"]);
  $_POST["parentname"] = $mbr->getParentName();
  
  $mbr->setParentOccupation($_POST["parentoccupation"]);
  $_POST["parentoccupation"] = $mbr->getParentOccupation();
  
  $mbr->setMotherTongue($_POST["mothertongue"]);
  $_POST["mothertongue"] = $mbr->getMotherTongue();
  
  $mbr->setStandard($_POST["standard"]);
  $_POST["standard"] = $mbr->getStandard();
  
  $mbr->setHomePhone($_POST["homePhone"]);
  $_POST["homePhone"] = $mbr->getHomePhone();
  $mbr->setWorkPhone($_POST["workPhone"]);
  $_POST["workPhone"] = $mbr->getWorkPhone();
  $mbr->setEmail($_POST["email"]);
  $_POST["email"] = $mbr->getEmail();
  $mbr->setClassification($_POST["classification"]);
  
  $dmQ = new DmQuery();
  $dmQ->connect();
  $customFields = $dmQ->getAssoc('member_fields_dm');
  $dmQ->close();
  foreach ($customFields as $name => $title) {
    if (isset($_REQUEST['custom_'.$name])) {
      $mbr->setCustom($name, $_REQUEST['custom_'.$name]);
    }
  }
  
  $validData = $mbr->validateData();
  if (!$validData) {
    $pageErrors["lastName"] = $mbr->getLastNameError();
    $pageErrors["firstName"] = $mbr->getFirstNameError();
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/mbr_edit_form.php");
    exit();
  }

  if($schoolid==$mbr->getSchoolId())
  	$schoolChanged=false;
  else 
	$schoolChanged=true;
  
  if($prev_standard==$mbr->getStandard())
  	$standard_changed=false;
  else
  	$standard_changed=true;
  
  if($prev_grade==$mbr->getGrade())
  	$grade_changed=false;
  else
  	$grade_changed=true;
  


  #**************************************************************************
  #*  Check for  maximum Roll number
  #**************************************************************************
	 if($schoolChanged==true ||($standard_changed==true)||($grade_changed==true))
	 {
		  $mbrQ = new MemberQuery();
		  $mbrQ->connect();
		  $standardsList = $mbrQ->getStandards($mbr->getSchoolId());
		  $standards=array();
		  foreach ($standardsList as $standard)
		  {
		  	$standards[$standard['standard_grade']]=$standard['max'];
		  }
		  $std=$mbr->getStandard();
		  $stdGrade=$mbr->getGrade();
		  $standardGrade=$std."".$stdGrade;
		  if(($standards[$standardGrade]==null))
		  {
		  	$prev_roll=$standardsList[0]['max'];
		  	if($prev_roll>0)
		  	{
		  		$roll=$prev_roll+100;
		  		$roll-=($prev_roll%100);
		  	}
		  	else
		  		$roll=0;
		  }
		  else
		  	$roll=$standards[$standardGrade];
		  $rollNumber=($roll+1);
		  $roll=$mbrQ->leading_zeros($rollNumber, 3);
		  $mbr->setRollNo($roll);
		  $schoolcode= $mbrQ->getSchoolCode($mbr->getSchoolId());
		  $mbr->setBarcodeNmbr($schoolcode."".$roll);
		  $_POST["barcodeNmbr"] = $mbr->getBarcodeNmbr();
	 }
  #**************************************************************************
  #*  Check for duplicate barcode number
  #**************************************************************************
  $mbrQ = new MemberQuery();
  $mbrQ->connect();
  $dupBarcode = $mbrQ->DupBarcode($mbr->getBarcodeNmbr(),$mbr->getMbrid());
  if ($dupBarcode) {
    $pageErrors["barcodeNmbr"] = $loc->getText("mbrDupBarcode",array("barcode"=>$mbr->getBarcodeNmbr()));
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/mbr_edit_form.php");
    exit();
  }

  #**************************************************************************
  #*  Update library member
  #**************************************************************************
  $mbrQ->update($mbr);
  $mbrQ->close();

  #**************************************************************************
  #*  Destroy form values and errors
  #**************************************************************************
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $msg = $loc->getText("mbrEditSuccess");
  header("Location: ../circ/mbr_view.php?mbrid=".U($mbr->getMbrid())."&reset=Y&msg=".U($msg));
  exit();
?>
