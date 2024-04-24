#!/usr/bin/env bash

BACKUP_DIR="/home/backups"
unset -v latest
for file in "$BACKUP_DIR"/*.sql.zip; do
  [[ $file -nt $latest ]] && latest=$file
done

echo "importing file $latest"

unzip -p "$latest" | mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"


