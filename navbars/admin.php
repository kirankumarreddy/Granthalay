<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 **********************************************************************************************************************
 *       CHANGE HISTORY 
 * #C3- This is adding a new feature of bulkupload  to admin section. Here BulkUpload option in the left list of admin list
 *
 * #C6 - its a feature for bulk upload of members into library system in admin section 
 *
 *   Author - Kiran Kumar Reddy and Bogade Saiteja 
 ***********************************************************************************************************************  
 */
 
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");

?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navLoc->getText("logout");?>" class="navbutton"><br />
<br />

<?php if ($nav == "summary") { ?>
 &raquo; <?php echo $navLoc->getText("adminSummary");?><br>
<?php } else { ?>
 <a href="../admin/index.php" class="alt1"><?php echo $navLoc->getText("adminSummary");?></a><br>
<?php } ?>

<?php if ($nav == "staff") { ?>
 &raquo; <?php echo $navLoc->getText("adminStaff");?><br>
<?php } else { ?>
 <a href="../admin/staff_list.php" class="alt1"><?php echo $navLoc->getText("adminStaff");?></a><br>
<?php } ?>

<?php if ($nav == "settings") { ?>
 &raquo; <?php echo $navLoc->getText("adminSettings");?><br>
<?php } else { ?>
 <a href="../admin/settings_edit_form.php?reset=Y" class="alt1"><?php echo $navLoc->getText("adminSettings");?></a><br>
<?php } ?>

<?php if ($nav == "classifications") { ?>
 &raquo; <?php echo $navLoc->getText("Member Types");?><br>
<?php } else { ?>
 <a href="../admin/mbr_classify_list.php" class="alt1"><?php echo $navLoc->getText("Member Types");?></a><br>
<?php } ?>

<?php if ($nav == "member_fields") { ?>
 &raquo; <?php echo $navLoc->getText("Member Fields");?><br>
<?php } else { ?>
 <a href="../admin/member_fields_list.php" class="alt1"><?php echo $navLoc->getText("Member Fields ");?></a><br>
<?php } ?>

<?php if ($nav == "copy_fields") { ?>
 &raquo; <?php echo $navLoc->getText("Copy Fields");?><br>
<?php } else { ?>
 <a href="../admin/copy_fields_list.php" class="alt1"><?php echo $navLoc->getText("Copy Fields ");?></a><br>
<?php } ?>

<?php if ($nav == "materials") { ?>
 &raquo; <?php echo $navLoc->getText("adminMaterialTypes");?><br>
<?php } else { ?>
 <a href="../admin/materials_list.php" class="alt1"><?php echo $navLoc->getText("adminMaterialTypes");?></a><br>
<?php } ?>

<?php if ($nav == "collections") { ?>
 &raquo; <?php echo $navLoc->getText("adminCollections");?><br>
<?php } else { ?>
 <a href="../admin/collections_list.php" class="alt1"><?php echo $navLoc->getText("adminCollections");?></a><br>
<?php } ?>
<!--C3 -begin-->
<?php if ($nav == "BulkUpload") { ?>
 &raquo; <?php echo $navLoc->getText("bulkupload");?><br>
<?php } else { ?>
 <a href="../admin/adminBulkUpload_list.php" class="alt1"><?php echo $navLoc->getText("adminBulkUpload");?></a><br>
<?php } ?>
<!--#C3 -end-->
<!--C5 -begin-->
<?php if ($nav == "memberBulkUpload") { ?>
 &raquo; <?php echo $navLoc->getText("memberbulkupload");?><br>
<?php } else { ?>
 <a href="../admin/adminmemberBulkUpload_list.php" class="alt1"><?php echo $navLoc->getText("adminmemberBulkUpload");?></a><br>
<?php } ?>
<!--#C6 -end-->

<?php if ($nav == "checkout_privs") { ?>
 &raquo; <?php echo $navLoc->getText("Checkout Privs");?><br>
<?php } else { ?>
 <a href="../admin/checkout_privs_list.php" class="alt1"><?php echo $navLoc->getText("Checkout Privs");?></a><br>
<?php } ?>

<?php if ($nav == "themes") { ?>
 &raquo; <?php echo $navLoc->getText("adminThemes");?><br>
<?php } else { ?>
 <a href="../admin/theme_list.php" class="alt1"><?php echo $navLoc->getText("adminThemes");?></a><br>
<?php } ?>

<!--
< ?php if ($nav == "translation") { ?>
 &raquo; < ?php echo $navLoc->getText("adminTranslation");?><br>
< ?php } else { ?>
 <a href="../admin/translation_list.php" class="alt1">< ?php echo $navLoc->getText("adminTranslation");?></a><br>
< ?php } ?>
-->

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=".H(addslashes(U($helpPage))); ?>')"><?php echo $navLoc->getText("help");?></a>

