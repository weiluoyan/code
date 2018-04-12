#!/bin/bash

#连接数据库
mysql = 'mysql -hlocalhost -u root -p'

#发送单个命令
$mysql emwjs -u root -e "show databases;"

#发送多个命令
$mysql emwjs -u root <<EOF
use mysql
show tables;
EOF


