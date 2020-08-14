drop table myclientsession cascade constraints;
drop table myclient cascade constraints;

create table myclient (
  clientid varchar2(12) primary key,
  password varchar2(12) not null,
  type varchar2(2),
  typename varchar2(26)
);

create table myclientsession (
  sessionid varchar2(32) primary key,
  clientid varchar2(8) not null,
  sessiondate date,
  foreign key (clientid) references myclient on delete cascade
);

insert into myclient values ('client1', 'client1', '0', 'student');
insert into myclient values ('client2', 'client2', '1', 'administrator');
insert into myclient values ('client3', 'client3', '2', 'student administrator');

commit;

