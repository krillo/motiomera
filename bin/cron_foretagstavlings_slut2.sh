#!/bin/sh

date '+%F %H:%M:%S'
echo "Spar tvalingsdata foretagstavling SLUT! - http://motiomera.se/cron/foretagstavlings_slut2.php"
/usr/bin/curl http://motiomera.se/cron/foretagstavlings_slut2.php >>/usr/local/motiomera/log/email.log 2>&1

