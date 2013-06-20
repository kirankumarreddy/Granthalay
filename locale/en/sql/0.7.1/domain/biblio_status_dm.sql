/ *     #C4 - This change contains adding new fields Basket number to Biblio and Biblio_copy.
 *                AUTHOR - BOGADE SAITEJA AND KIRAN KUMAR REDDY.*/
insert into %prfx%biblio_status_dm values ('in','checked in','Y');
insert into %prfx%biblio_status_dm values ('out','checked out','N');
insert into %prfx%biblio_status_dm values ('mnd','damaged/mending','N');
insert into %prfx%biblio_status_dm values ('dis','display area','N');
insert into %prfx%biblio_status_dm values ('hld','on hold','N');
insert into %prfx%biblio_status_dm values ('lst','lost','N');
insert into %prfx%biblio_status_dm values ('ln','on loan','N');
insert into %prfx%biblio_status_dm values ('ord','on order','N');
insert into %prfx%biblio_status_dm values ('crt','shelving cart','N');
/* #C4- begin*/
insert into %prfx%biblio_status_dm values ('ina','inactive','N');
/* #C4- end*/