#!/bin/sh
#status -> 40
#/usr/bin/curl http://krillomera.se/cron/foretagsfiler.php >>/var/www/krillomera/order/log/cron_motiomera.log 2>&1
#/usr/bin/curl http://trunkomera.se/cron/foretagsfiler.php >>/var/www/trunkomera/log/cron_motiomera.log 2>&1
/usr/bin/curl http://motiomera.se/cron/foretagsfiler.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1

