<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "newconfirm";
  $restrictInDemo = true;
  require_once("../shared/logincheck.php");

  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../classes/DmQuery.php");
  require_once("../functions/errorFuncs.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Checking for post vars.  Go back to form if none found.
  #****************************************************************************
  if (count($_POST) == 0) {
    header("Location: ../circ/scl_new_form.php");
    exit();
  }

  #****************************************************************************
  #*  Validate data
  #****************************************************************************
  $scl = new School();
  $scl->setSchoolCode($_POST["schoolCode"]);
  $_POST["schoolCode"] = $scl->getSchoolCode();
  $scl->setLastChangeUserid($_SESSION["userid"]);
  $scl->setSchoolName($_POST["schoolName"]);
  $_POST["schoolName"] = $scl->getSchoolName();
  $scl->setSchoolAddress($_POST["schoolAddress"]);
  $_POST["schoolAddress"] = $scl->getSchoolAddress();
  $scl->setContactNumber($_POST["contactNumber"]);
  $_POST["contactNumber"] = $scl->getContactNumber();
  
  $scl->setcontactPerson($_POST["contactPerson"]);
  $_POST["contactPerson"] = $scl->getContactPerson();

  $scl->setEmail($_POST["email"]);
  $_POST["email"] = $scl->getEmail();
  
  $validData = $scl->validateData();
  if (!$validData) {
    $pageErrors["schoolName"] = $scl->getSchoolNameError();
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/scl_new_form.php");
    exit();
  }
  
  #**************************************************************************
  #*  Insert new library school
  #**************************************************************************
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $sclid = $sclQ->insert($scl);
  $sclQ->close();

  #**************************************************************************
  #*  Destroy form values and errors
  #**************************************************************************
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $msg = $loc->getText("sclNewSuccess");
  header("Location: ../circ/scl_view.php?sclid=".U($sclid)."&reset=Y&msg=".U($msg));
  exit();
?>
