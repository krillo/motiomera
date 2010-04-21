#!/bin/sh
date '+%F %H:%M:%S'
echo "Skickar fredags-email foretagstavling slutar snart - http://motiomera.se/cron/foretagstavlings_slut1.php"
/usr/bin/curl http://motiomera.se/cron/foretagstavlings_slut1.php >>/usr/local/motiomera/log/email.log 2>&1

