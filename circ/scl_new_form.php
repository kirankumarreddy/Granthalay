<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  session_cache_limiter(null);

  $tab = "circulation";
  $restrictToMbrAuth = TRUE;
  $nav = "newSchool";
  $cancelLocation = "../circ/index.php";  
  require_once("../functions/inputFuncs.php");
  require_once("../shared/logincheck.php");
  require_once("../shared/get_form_vars.php");
  require_once("../shared/header.php");
  require_once("../classes/School.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);
  $headerWording = $loc->getText("sclNewForm");
  $scl = new School();
?>
<form name="newsclform" method="POST" action="../circ/scl_new.php">
<?php include("../circ/scl_fields.php"); ?>
<?php include("../shared/footer.php"); ?>
