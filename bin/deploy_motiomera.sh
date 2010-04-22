#!/bin/sh
# Deploy motiomera.se
homedir="/home/motiomera"
gitrootdir=$PWD
owner="motiomera"
GITTAG=$(git describe)

echo "You are currently in $gitrootdir, is this the git root dir?"
echo "Are your sure you want to deploy $GITTAG?
echo "Choose environment to deploy"
echo "1 - for motimera.se"
echo "2 - for trunkomera.se"
echo "3 - to exit"
read DEPLOY_TO

if [ "$DEPLOY_TO" = "1" ]; then
  depenv="motiomera"
  livedir="/usr/local"  
elif [ "$DEPLOY_TO" = "2" ]; then
  depenv="trunkomera"
  livedir="/var/www"  
else
  cat /home/motiomera/$depenv/bin/bart
  echo "## Exiting deploy"
  exit 0
fi


livedir="$livedir/$depenv"
workdir="$homedir/$depenv"
tmpdeploydir="$workdir/deploy"
workbindir="$workdir/bin"


echo "## Deploy working bin dir: $workbindir - `date` ###################"
echo "create clean temp dir - $workbindir"
if [ -d "$workbindir" ];
then
 rm -rf $workbindir
fi
mkdir $workbindir
chown -R motiomera:motiomera $workbindir/

echo "## copy all bin files to working bin dir - $workbindir/"
su motiomera -c "cp -rf $gitrootdir/bin/. $workbindir"


echo "## Deploy $depenv - `date` ###################"
echo "create clean temp dir - $tmpdeploydir "
if [ -d "$tmpdeploydir" ];
then
 rm -rf $tmpdeploydir
fi
mkdir $tmpdeploydir
chown -R motiomera:motiomera $tmpdeploydir/

echo "## copy all files to tmp dir - $tmpdeploydir/"
su motiomera -c "cp -rf $gitrootdir/public_html/. $tmpdeploydir"


echo "## copy serverspecific settings.php and remove settings-template.php  - $tmpdeploydir/"
cp -fp $workdir/site_properties/settings.php $tmpdeploydir/php/settings.php
rm $tmpdeploydir/php/settings-template.php


echo "## add GITTAG $GITTAG to css file  - $tmpdeploydir/ "
cd $tmpdeploydir/templates/
sed -e "s/motiomera.css/motiomera.css?ver=$GITTAG/g" header.tpl >tmpfile
mv tmpfile header.tpl
sed -e "s/print.css/print.css?ver=$GITTAG/g" header.tpl >tmpfile
mv tmpfile header.tpl


echo "## set $owner:$owner as owner of all source files  - $tmpdeploydir/"
chown -R $owner:$owner $tmpdeploydir/

echo "## set write permissions on compilation catalogs and others  - $tmpdeploydir/"
chmod  -R  777  $tmpdeploydir/templates_c/
chmod  -R  777  $tmpdeploydir/files/
chmod  -R  777  $tmpdeploydir/admin/templates_c/
chmod  -R  777  $tmpdeploydir/php/libs/smarty/cache 
chmod  -R  777  $tmpdeploydir/popup/templates_c/

echo "## change script owner to $owner and copy bin and cron files to $workdir/bin "
su $owner -c "cp -rf $gitrootdir/bin/. $workdir/bin/"
chmod 770 $workdir/bin/*

echo "## tar deploy dir  "
cd $tmpdeploydir/
tar czf $tmpdeploydir/deploy.$depenv.tgz *

echo "## extract tar to $livedir/htdocs  "
cd $livedir/htdocs/
tar xzf $tmpdeploydir/deploy.$depenv.tgz

echo "## append svn revision to $livedir/htdocs/hustlerrev.php"
hustlerrev="$livedir/htdocs/hustlerrev.php"
cd $gitrootdir

echo "<br/><br/>" >> $hustlerrev
date >> $hustlerrev
$GITTAG >>$hustlerrev

echo "## Touch all files in htdocs (to sort of empty xcache) - $livedir/htdocs/"
find $livedir/htdocs/ -name "*" | xargs touch

echo "## remove all smarty compiled templates at $livedir/htdocs/  "
rm -rf  $livedir/htdocs/templates_c/*
rm -rf  $livedir/popup/templates_c/*
rm -rf  $livedir/htdocs/admin/templates_c/*

echo "## Deploy done! - `date` ####################"
