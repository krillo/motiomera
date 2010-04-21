#!/bin/bash

########################################################################
# General Config

# MotioMera
mm_backupdir="/home/motiomera/project_svn/trunk/db/dbdump/"
mm_filename="motiomera.sql"

########################################################################
# Motiomera Backup
echo "Creating a database dump file $mm_filename of motiomera."

cd $mm_backupdir
rm $mm_filename.gz
mysqldump -h 10.0.1.171 -umotiomera -pt5fugds --databases motiomera > $mm_filename
gzip $mm_filename

