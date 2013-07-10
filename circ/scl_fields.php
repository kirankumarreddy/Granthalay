<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

  require_once("../functions/inputFuncs.php");
  require_once('../classes/DmQuery.php');
    
  $fields = array(
    "sclFldsSchoolName" => inputField('text', "schoolName", $scl->getSchoolName()),
    "sclFldsAddress" => inputField('text', "schoolAddress",$scl->getSchoolAddress()),
    "sclFldsContactPerson" => inputField('text', "contactPerson", $scl->getcontactPerson()),
    "sclFldsContactNumber" => inputField('text', "contactNumber", $scl->getContactNumber()),
    "sclFldsEmail" => inputField('text', "email", $scl->getEmail()),
  );
  
?>

<table class="primary">
  <tr>
    <th colspan="2" valign="top" nowrap="yes" align="left">
      <?php echo H($headerWording);?> <?php echo $loc->getText("sclFldsHeader"); ?>
    </td>
  </tr>
<?php
  foreach ($fields as $title => $html) {
?>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo $loc->getText($title); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo $html; ?>
    </td>
  </tr>
<?php
  }
?>
  <tr>
    <td align="center" colspan="2" class="primary">
      <input type="submit" value="<?php echo $loc->getText("sclFldsSubmit"); ?>" class="button">
      <input type="button" onClick="self.location='<?php echo H(addslashes($cancelLocation));?>'" value="<?php echo $loc->getText("sclFldsCancel"); ?>" class="button">
    </td>
  </tr>

</table>
