<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */

  require_once("../functions/inputFuncs.php");
  require_once('../classes/DmQuery.php');
  require_once('../classes/School.php');
  require_once('../classes/SchoolQuery.php');
  
    $sclid = $_GET["sclid"];
 	$_POST["sclid"]=$sclid;
 	

  $sclQ = new SchoolQuery();
  $sclQ->connect();
  $scl= $sclQ->get($sclid);
  $sclQ->close();

    $standardLevel=array(0=>0,1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9);
	$standardGrade=array(''=>'none','A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E',
							'F'=>'F','G'=>'G','H'=>'H','I'=>'I','J'=>'J','K'=>'K',
							'L'=>'L','M'=>'M','N'=>'N','O'=>'O','P'=>'P','Q'=>'Q',
							'R'=>'R','S'=>'S','T'=>'T','U'=>'U','V'=>'V','W'=>'W',
							'X'=>'X','Y'=>'Y','Z'=>'Z');

  $fields = array(
		    "sclFldsSchoolId" => inputField('text', "schoolId", $sclid, array("readonly"=>"readonly")),
			"sclFldsSchoolName" => inputField('text', "schoolName", $scl->getSchoolName(),array("readonly"=>"readonly")),
			"sclStandard" => inputField('select', 'standardLevel', null, NULL, $standardLevel),
			"sclGrade" => inputField('select', 'standardGrade', null, NULL, $standardGrade)
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
      <input type="submit" value="<?php echo $loc->getText("sclFldsPromote"); ?>" class="button">
      <input type="button" onClick="self.location='<?php echo H(addslashes($cancelLocation));?>'" value="<?php echo $loc->getText("sclFldsCancel"); ?>" class="button">
    </td>
  </tr>

</table>