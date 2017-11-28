#!/bin/bash

mysqladmin create test
mysql test <<EOF
drop table if exists wikimedia_hits;
create table wikimedia_hits (id integer primary key auto_increment not null, code char(7) not null, pagename varchar(255), hits integer not null, size integer not null, index by_code (code)) engine=InnoDB;
EOF

mysqlimport -v --columns=code,pagename,hits,size --fields-terminated-by=" " test /var/lib/mysql-files/wikimedia_hits.ssv
