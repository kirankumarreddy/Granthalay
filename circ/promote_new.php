<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "promote";
  $restrictInDemo = true;
  require_once("../shared/logincheck.php");

  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
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
    header("Location: ../circ/promote_new_form.php");
    exit();
  }
    $sclid = $_POST["schoolId"];
//    if (isset($_GET["sclid"])){
//    $sclid = $_GET["sclid"];
//  } else {
//    require("../shared/get_form_vars.php");
//    $sclid = $postVars["sclid"];
//  }

  $schoolid = $_POST["schoolId"];

  #****************************************************************************
  #*  Validate data
  #****************************************************************************
  
  #**************************************************************************
  #*  Insert new library school
  #**************************************************************************

  $standardLevel=$_POST["standardLevel"];
  $_POST["standardLevel"]=$standardLevel;
  $standardGrade=$_POST["standardGrade"];
  $_POST["standardGrade"]=$standardGrade;
  
  $mbrQ = new MemberQuery();
  $mbrQ->connect();
  $mbrQ->updateStandards($schoolid,$standardLevel,$standardGrade);
  $mbrQ->close();


  #**************************************************************************
  #*  Destroy form values and errors
  #**************************************************************************
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  $msg = $loc->getText("sclPromoteSuccess",array("grade"=>$standardLevel."".$standardGrade));
  header("Location: ../circ/scl_view.php?sclid=".U($sclid)."&reset=Y&msg=".U($msg));
  exit();
?>
