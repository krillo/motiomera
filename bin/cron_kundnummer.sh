#!/bin/sh
#date '+%F %H:%M:%S'
#echo "hamta kundnummer status -> 30"
#/usr/bin/curl http://krillomera.se/cron/nya_kundnummer.php >>/var/www/krillomera/order/log/cron_motiomera.log 2>&1
#/usr/bin/curl http://trunkomera.se/cron/nya_kundnummer.php >>/var/www/trunkomera/log/cron_motiomera.log 2>&1
/usr/bin/curl http://motiomera.se/cron/nya_kundnummer.php >>/usr/local/motiomera/log/cron_motiomera.log 2>&1

