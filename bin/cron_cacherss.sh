#!/bin/sh
date '+%F %H:%M:%S'
echo "Cache rss"
/usr/bin/curl -m 120 http://motiomera.se/cron/cacherss.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1
