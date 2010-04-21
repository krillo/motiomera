#!/bin/sh

# Live:
date '+%F %H:%M:%S'
echo "Paminnelse"
/usr/bin/curl http://motiomera.se/cron/paminnelser.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1

# Development:
#/usr/bin/curl http://branch.motiomera/cron/paminnelser.php