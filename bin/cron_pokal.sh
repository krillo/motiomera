#!/bin/sh
date '+%F %H:%M:%S'
echo "Pokal"
/usr/bin/curl http://motiomera.se/cron/pokal.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1
