#!/bin/bash

########################################################################
# General Config
ftp_site=ftp3.aller.se
username=NYA_M_ADM
passwd=#16bt46

# MotioMera
mm_backupdir="/usr/local/motiomera"
mm_filename="backup-motiomera-$(date '+%F-%H%M').tar.gz"
mm_dbfilename="backup-motiomera-$(date '+%F-%H%M').sql"

########################################################################
# Motiomera Backup
echo "Creating a backup file $mm_filename of $mm_backupdir."

mysqldump -h 10.0.1.171 -umotiomera -pt5fugds --databases motiomera > $mm_dbfilename
# Make a tar gzipped backup file
/bin/tar -czf  "$mm_filename" "$mm_backupdir" $mm_dbfilename
rm -f $mm_dbfilename

# FTP ALL FILES TO FTP SERVER
/usr/bin/ftp -in <<EOF
open $ftp_site
user $username $passwd
bin
cd backup
put $mm_filename 
close 
bye
EOF

rm -f $mm_filename
