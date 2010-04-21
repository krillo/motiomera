#!/bin/sh
date '+%F %H:%M:%S'
echo "medalj"
/usr/bin/curl http://motiomera.se/cron/medalj.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1
