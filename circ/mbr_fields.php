<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

  require_once("../functions/inputFuncs.php");
  require_once('../classes/DmQuery.php');
  require_once('../classes/SchoolQuery.php');
  require_once('../classes/School.php');
  
  $dmQ = new DmQuery();
  $dmQ->connect();
  $mbrClassifyDm = $dmQ->getAssoc('mbr_classify_dm');
  $customFields = $dmQ->getAssoc('member_fields_dm');
  $dmQ->close();
  $gender=array(OBIB_GENDER_MALE=>"Male",OBIB_GENDER_FEMALE=>"Female");  
  $scQ = new SchoolQuery();
  $scQ->connect();
  $schoolList=$scQ->getSchoolList();
  $scQ->close();
  
  
  $fields = array(
    "mbrFldsClassify" => inputField('select', 'classification', $mbr->getClassification(), NULL, $mbrClassifyDm),
    "mbrFldsLastName" => inputField('text', "lastName", $mbr->getLastName()),
    "mbrFldsFirstName" => inputField('text', "firstName", $mbr->getFirstName()),

    "mbrFldsGender" => inputField('select', "gender",$mbr->getGender() ,Null,$gender),
    "mbrFldsSchool" => inputField('select', "school", $mbr->getSchoolId(),Null,$schoolList),
    "mbrFldsStandard" => inputField('text', "standard", $mbr->getStandard()),
    "mbrFldsSchoolTeacher" => inputField('text', "schoolTeacher", $mbr->getSchoolTeacher()),
    "mbrFldsParentName" => inputField('text', "parentname", $mbr->getParentName()),
    "mbrFldsParentOccupation" => inputField('text', "parentoccupation", $mbr->getParentOccupation()),
    "mbrFldsMotherTongue" => inputField('text', "mothertongue", $mbr->getMotherTongue()),

    "mbrFldsEmail" => inputField('text', "email", $mbr->getEmail()),
    "Mailing Address:" => inputField('textarea', "address", $mbr->getAddress()),
    "mbrFldsHomePhone" => inputField('text', "homePhone", $mbr->getHomePhone()),
    "mbrFldsWorkPhone" => inputField('text', "workPhone", $mbr->getWorkPhone()),
  );
  
  foreach ($customFields as $name => $title) {
    $fields[$title.':'] = inputField('text', 'custom_'.$name, $mbr->getCustom($name));
  }
?>

<table class="primary">
  <tr>
    <th colspan="2" valign="top" nowrap="yes" align="left">
      <?php echo H($headerWording);?> <?php echo $loc->getText("mbrFldsHeader"); ?>
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
      <input type="submit" value="<?php echo $loc->getText("mbrFldsSubmit"); ?>" class="button">
      <input type="button" onClick="self.location='<?php echo H(addslashes($cancelLocation));?>'" value="<?php echo $loc->getText("mbrFldsCancel"); ?>" class="button">
    </td>
  </tr>

</table>
