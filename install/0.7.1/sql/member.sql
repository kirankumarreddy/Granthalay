drop table if exists %prfx%member;
create table %prfx%member (
  mbrid integer auto_increment primary key
  ,barcode_nmbr varchar(20) 
  ,create_dt datetime not null
  ,last_change_dt datetime not null
  ,last_change_userid integer not null
  ,last_name varchar(50) not null
  ,first_name varchar(50) not null
  ,address text null
  ,schoolid integer not null
  ,standard varchar(10) null
  ,roll_no int(3) zerofill null
  ,parent_name varchar(50) null
  ,parent_occupation varchar(50) null
  ,mother_tongue varchar(20) null
  ,home_phone varchar(15) null
  ,work_phone varchar(15) null
  ,email varchar(128) null
  ,classification smallint not null
  ,gender varchar(10) not null
  ,school_teacher varchar(50) null
  )
  ENGINE=MyISAM
;
