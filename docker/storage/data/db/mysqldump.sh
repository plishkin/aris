#!/usr/bin/env bash

BACKUP_DIR="/home/backups"
BACKUP_FILE_NAME="$(date +"%Y-%m-%d %H:%M:%S.sql")"

mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" | zip > "$BACKUP_DIR"/"$BACKUP_FILE_NAME".zip
#mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" > "$BACKUP_DIR"/"$BACKUP_FILE_NAME"

