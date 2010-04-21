#!/bin/sh
date '+%F %H:%M:%S'
echo "Trunkera feeditems"
/usr/bin/curl http://motiomera.se/cron/feeditems_trunicate.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1

