#!/bin/sh

date '+%F %H:%M:%S'
echo "Skickar email foretagstavling SLUT! - http://motiomera.se/cron/foretagstavlings_slut3_email.php"
/usr/bin/curl http://motiomera.se/cron/foretagstavlings_slut3_email.php >>/usr/local/motiomera/log/email.log 2>&1

