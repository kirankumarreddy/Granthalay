drop table if exists %prfx%school;
create table %prfx%school (
  schoolid integer auto_increment primary key
  ,school_name text not null
  ,school_code varchar(10) not null
  ,create_dt datetime not null
  ,last_change_dt datetime not null
  ,last_change_userid integer not null
  ,school_address text null
  ,contact_person varchar(50) null
  ,contact_number varchar(20) null
  ,email varchar(50) null
  )
  ENGINE=MyISAM
;
