#!/bin/sh
#status -> 70
#/usr/bin/curl http://krillomera.se/cron/postpac.php >>/var/www/krillomera/postpac/log/cron_motiomera.log 2>&1
#/usr/bin/curl http://trunkomera.se/cron/postpac.php >>/var/www/trunkomera/log/cron_motiomera.log 2>&1
/usr/bin/curl http://motiomera.se/cron/postpac.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1