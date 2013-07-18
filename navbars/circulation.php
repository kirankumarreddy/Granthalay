<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navloc = new Localize(OBIB_LOCALE,"navbars");
  /* Couldn't trace out how $mbrid and $sclid is getting set, so manually setting the sclid value from $_GET */
  $sclid=$_GET["sclid"];
 
?>
<input type="button" onClick="self.location='../shared/logout.php'" value="<?php echo $navloc->getText("Logout"); ?>" class="navbutton"><br />
<br />

<?php if ($nav == "searchform") { ?>
 &raquo; <?php echo $navloc->getText("memberSearch"); ?><br>
<?php } else { ?>
 <a href="../circ/index.php" class="alt1"><?php echo $navloc->getText("memberSearch"); ?></a><br>
<?php } ?>

<?php if ($nav == "search") { ?>
 &nbsp; &raquo; <?php echo $navloc->getText("catalogResults"); ?><br>
<?php } ?>

<?php if ($nav == "schoolSearch") { ?>
 &raquo; <?php echo $navloc->getText("schoolSearch"); ?><br>
<?php } else { ?>
 <a href="../circ/scl_index.php?reset=Y" class="alt1"><?php echo $navloc->getText("schoolSearch"); ?></a><br>
<?php } ?>

<?php if ($nav == "searchResults") { ?>
 &nbsp; &raquo; <?php echo $navloc->getText("catalogResults"); ?><br>
<?php } ?>

<?php if ($nav == "view") { ?>
 &nbsp; &raquo; <?php echo $navloc->getText("memberInfo"); ?><br>
 &nbsp; &nbsp; <a href="../circ/mbr_edit_form.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_del_confirm.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("catalogDelete"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_account.php?mbrid=<?php echo HURL($mbrid);?>&amp;reset=Y" class="alt1"><?php echo $navloc->getText("account"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_history.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("checkoutHistory"); ?></a><br>
<?php } ?>

<?php if ($nav == "edit") { ?>
 &nbsp; <a href="../circ/mbr_view.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("memberInfo"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("editInfo"); ?><br>
 &nbsp; &nbsp; <a href="../circ/mbr_del_confirm.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("catalogDelete"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_account.php?mbrid=<?php echo HURL($mbrid);?>&amp;reset=Y" class="alt1"><?php echo $navloc->getText("account"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_history.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("checkoutHistory"); ?></a><br>
<?php } ?>

<?php if ($nav == "delete") { ?>
 &nbsp; <a href="../circ/mbr_view.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("memberInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_edit_form.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("catalogDelete"); ?><br>
 &nbsp; &nbsp; <a href="../circ/mbr_account.php?mbrid=<?php echo HURL($mbrid);?>&amp;reset=Y" class="alt1"><?php echo $navloc->getText("account"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_history.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("checkoutHistory"); ?></a><br>
<?php } ?>

<?php if ($nav == "hist") { ?>
 &nbsp; <a href="../circ/mbr_view.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("memberInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_edit_form.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_del_confirm.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("catalogDelete"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_account.php?mbrid=<?php echo HURL($mbrid);?>&amp;reset=Y" class="alt1"><?php echo $navloc->getText("account"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("checkoutHistory"); ?><br>
<?php } ?>

<?php if ($nav == "account") { ?>
 &nbsp; <a href="../circ/mbr_view.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("memberInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_edit_form.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/mbr_del_confirm.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("catalogDelete"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("account"); ?><br>
 &nbsp; &nbsp; <a href="../circ/mbr_history.php?mbrid=<?php echo HURL($mbrid);?>" class="alt1"><?php echo $navloc->getText("checkoutHistory"); ?></a><br>
<?php } ?>


<?php if ($nav == "schoolView") { ?>
 &nbsp; &raquo; <?php echo $navloc->getText("schoolInfo"); ?><br>
 &nbsp; &nbsp; <a href="../circ/scl_edit_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/promote_new_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("promote"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/scl_del_confirm.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolDelete"); ?></a><br>
<?php } ?>

<?php if ($nav == "schoolEdit") { ?>
 &nbsp; <a href="../circ/scl_view.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolInfo"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("editInfo"); ?><br>
 &nbsp; &nbsp; <a href="../circ/promote_new_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("promote"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/scl_del_confirm.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolDelete"); ?></a><br>
<?php } ?>

<?php if ($nav == "schoolDelete") { ?>
 &nbsp; <a href="../circ/scl_view.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/scl_edit_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/promote_new_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("promote"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("schoolDelete"); ?><br>
<?php } ?>

<?php if ($nav == "promote") { ?>
 &nbsp; <a href="../circ/scl_view.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolInfo"); ?></a><br>
 &nbsp; &nbsp; <a href="../circ/scl_edit_form.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("editInfo"); ?></a><br>
 &nbsp; &nbsp; &raquo; <?php echo $navloc->getText("promote"); ?><br>
 &nbsp; &nbsp; <a href="../circ/scl_del_confirm.php?sclid=<?php echo HURL($sclid);?>" class="alt1"><?php echo $navloc->getText("schoolDelete"); ?></a><br>
<?php } ?>


<?php if ($nav == "new") { ?>
 &raquo; <?php echo $navloc->getText("newMember"); ?><br>
<?php } else { ?>
 <a href="../circ/mbr_new_form.php?reset=Y" class="alt1"><?php echo $navloc->getText("newMember"); ?></a><br>
<?php } ?>

<?php if ($nav == "newSchool") { ?>
 &raquo; <?php echo $navloc->getText("newSchool"); ?><br>
<?php } else { ?>
 <a href="../circ/scl_new_form.php?reset=Y" class="alt1"><?php echo $navloc->getText("newSchool"); ?></a><br>
<?php } ?>

<?php if ($nav == "checkin") { ?>
 &raquo; <?php echo $navloc->getText("checkIn"); ?><br>
<?php } else { ?>
 <a href="../circ/checkin_form.php?reset=Y" class="alt1"><?php echo $navloc->getText("checkIn"); ?></a><br>
<?php } ?>

<?php if ($nav == "offline") { ?>
 &raquo; <?php echo $navloc->getText("Offline Circulation"); ?><br>
<?php } else { ?>
 <a href="../circ/offline.php" class="alt1"><?php echo $navloc->getText("Offline Circulation"); ?></a><br>
<?php } ?>

<a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=".H(addslashes(U($helpPage))); ?>')"><?php echo $navloc->getText("help"); ?></a>
