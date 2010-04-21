#!/bin/sh
date '+%F %H:%M:%S'
echo "Blogg.se cache"
/usr/bin/curl http://motiomera.se/cron/get_user_rss.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1
