#!/bin/sh
export not_updated=true;
while $not_updated; do
    date;
    ping -c 1 8.8.8.8
    rc=$?
    if [ $rc -eq 0 ] ; then
        export not_updated=false;
        echo 'ping successful, updating frontend now...';
        cd /var/www/html/coinreader-fe;
        dep deploy production;
        echo 'update done';
    fi
    sleep 5;
done