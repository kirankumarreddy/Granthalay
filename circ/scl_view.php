<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $nav = "schoolView";
  $helpPage = "schoolView";
  
  require_once("../functions/inputFuncs.php");
  require_once("../functions/formatFuncs.php");
  require_once("../shared/logincheck.php");
  require_once("../classes/School.php");
  require_once("../classes/SchoolQuery.php");
  require_once("../classes/DmQuery.php");
  require_once("../shared/get_form_vars.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Checking for get vars.  Go back to form if none found.
  #****************************************************************************
  if (count($_GET) == 0) {
    header("Location: ../circ/index.php");
    exit();
  }

  #****************************************************************************
  #*  Retrieving get var
  #****************************************************************************
  $sclid = $_GET["sclid"];
  if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">".H($_GET["msg"])."</font><br><br>";
  } else {
    $msg = "";
  }

  #****************************************************************************
  #*  Search database for school
  #****************************************************************************  
  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $scl= $sclQ->get($sclid);
  $sclQ->close();
  
  #**************************************************************************
  #*  Show school information
  #**************************************************************************
  require_once("../shared/header.php");
?>

<?php echo $msg ?>

<table class="primary">
  <tr><td class="noborder" valign="top">
  <br>
<table class="primary">
  <tr>
    <th align="left" colspan="2" nowrap="yes">
      <?php echo $loc->getText("sclInformation"); ?>
    </th>
  </tr>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo $loc->getText("sclFldsSchoolName"); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($scl->getSchoolName());?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      <?php echo $loc->getText("sclFldsSchoolCode"); ?>
    </td>
    <td valign="top" class="primary">
      <?php
        echo H($scl->getSchoolCode());
      ?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      <?php echo $loc->getText("sclFldsAddress"); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($scl->getSchoolAddress());?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      <?php echo $loc->getText("sclFldsContactPerson"); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($scl->getcontactPerson());?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      <?php echo H($loc->getText("sclFldsContactNumber")); ?>
    </td>
    <td valign="top" class="primary">
      <?php
          echo H($scl->getContactNumber()); ?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      <?php echo $loc->getText("sclFldsEmail"); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($scl->getEmail());?>
    </td>
  </tr>
</table>
<?php require_once("../shared/footer.php"); ?>