#!/bin/sh
#status -> 50
#/usr/bin/curl http://krillomera.se/cron/foretagsfiler_ftp.php >>/var/www/krillomera/order/log/cron_motiomera.log 2>&1
#/usr/bin/curl http://trunkomera.se/cron/foretagsfiler_ftp.php >>/var/www/trunkomera/log/cron_motiomera.log 2>&1
/usr/bin/curl http://motiomera.se/cron/foretagsfiler_ftp.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1
