#!/bin/bash

now=$(date +"%Y%m%d-%H%M%S")
file="../data/backups/mysql/bach_$now.sql.gz"

docker exec bach3_db_1 mysqldump -upeter --password="apekop01" --add-drop-table bach | gzip -f > $file
