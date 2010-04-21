#!/bin/sh
# Deploy motiomera.se
homedir="/home/motiomera"
svnrootdir="$homedir/project_svn"
livedir="/usr/local"
owner="motiomera"

echo "Choose environment to deploy"
echo "1 - for motimera.se"
echo "2 - for testomera.se"
echo "3 - for trunkomera.se"
echo "4 - for branch or revision on trunkomera.se"
echo "5 - for krillomera"
read DEPLOY_TO

if [ "$DEPLOY_TO" = "1" ]; then
  #only deploy a tag - check if tag dir exists
  depenv="motiomera"
  svnrootdir="$homedir/aller-motiomera"  
  echo "Enter which release-tag to deploy"
  read TAG
  svnrootdir=$svnrootdir/tags/$TAG
elif [ "$DEPLOY_TO" = "2" ]; then
  #only deploy a branch - check if the branch dir exists
  depenv="testomera"
  echo "Enter the path to the branch or the revision to deploy to testomera"
  echo "e.g branches/ludvig/  or  tags/release-2.99"
  read TAG
  svnrootdir=$svnrootdir/$TAG
elif [ "$DEPLOY_TO" = "3" ]; then
  depenv="trunkomera"
  homedir="/home/allersvn"
  svnrootdir="$homedir/aller-motiomera"
  svnrootdir=$svnrootdir/trunk
  livedir="/var/www/"
elif [ "$DEPLOY_TO" = "4" ]; then
  depenv="trunkomera"
  homedir="/home/allersvn"
  svnrootdir="$homedir/aller-motiomera"
  livedir="/var/www"  
  echo "Enter the path to the branch or the revision to deploy to trunkomera"
  echo "e.g branches/order  or  tags/release-2.99"
  read TAG
  svnrootdir=$svnrootdir/$TAG
elif [ "$DEPLOY_TO" = "5" ]; then
  depenv="krillomera"
  homedir="/home/krillo"
  livedir="/var/www"
  owner="krillo"
  svnrootdir="$homedir/aller-motiomera"
  echo "Enter the path to trunk, branch or the revision to deploy to krillomera"
  echo "e.g trunk, branches/order or tags/release-2.111"
  read TAG
  svnrootdir=$svnrootdir/$TAG
else
  cat /home/motiomera/project_svn/trunk/bin/bart
  echo "## Exiting deploy"
  exit 0
fi

if [ -d "$svnrootdir" ]; then
  cd $svnrootdir
else
  cat /home/motiomera/project_svn/trunk/bin/bart 
  echo "## $svnrootdir  does not exist"
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

echo "## export all bin files to working bin dir - $workbindir/"
su motiomera -c "svn export --force $svnrootdir/bin/  $workbindir"


echo "## Deploy $depenv - `date` ###################"
echo "create clean temp dir - $tmpdeploydir "
if [ -d "$tmpdeploydir" ];
then
 rm -rf $tmpdeploydir
fi
mkdir $tmpdeploydir
chown -R motiomera:motiomera $tmpdeploydir/

echo "## export all svn files to tmp dir - $tmpdeploydir/"
su motiomera -c "svn export --force $svnrootdir/public_html/  $tmpdeploydir"

echo "## copy serverspecific settings.php and remove settings-template.php  - $tmpdeploydir/"
#cp -fp $workdir/site_properties/dbsettings.php $tmpdeploydir/php/dbsettings.php
cp -fp $workdir/site_properties/settings.php $tmpdeploydir/php/settings.php
rm $tmpdeploydir/php/settings-template.php


echo "## add svn revision to css file  - $tmpdeploydir/ "
SVNREVISION=$(svn info|grep Revision | cut -c 11-15)
echo "$SVNREVISION"
cd $tmpdeploydir/templates/
sed -e "s/motiomera.css/motiomera.css?ver=$SVNREVISION/g" header.tpl >tmpfile
mv tmpfile header.tpl
sed -e "s/print.css/print.css?ver=$SVNREVISION/g" header.tpl >tmpfile
mv tmpfile header.tpl


echo "## set $owner:$owner as owner of all source files  - $tmpdeploydir/"
chown -R $owner:$owner $tmpdeploydir/

echo "## set write permissions on compilation catalogs and others  - $tmpdeploydir/"
chmod  -R  777  $tmpdeploydir/templates_c/
chmod  -R  777  $tmpdeploydir/templates_c/
chmod  -R  777  $tmpdeploydir/templates_c/
chmod  -R  777  $tmpdeploydir/files/
chmod  -R  777  $tmpdeploydir/admin/templates_c/
chmod  -R  777  $tmpdeploydir/php/libs/smarty/cache 
chmod  -R  777  $tmpdeploydir/popup/templates_c/


echo "## change script owner to $owner and copy bin and cron files to $workdir/bin "
su $owner -c "svn export --force $svnrootdir/bin/ $workdir/bin/"
chmod 770 $workdir/bin/*

echo "## tar deploy dir  "
cd $tmpdeploydir/
tar czf $tmpdeploydir/deploy.$depenv.tgz *

echo "## extract tar to $livedir/htdocs  "
cd $livedir/htdocs/
tar xzf $tmpdeploydir/deploy.$depenv.tgz

echo "## append svn revision to $livedir/htdocs/hustlerrev.php"
hustlerrev="$livedir/htdocs/hustlerrev.php"
cd $svnrootdir

echo "<br/><br/>" >> $hustlerrev
date >> $hustlerrev
svn info >>$hustlerrev

echo "## Touch all files in htdocs (to sort of empty xcache) - $livedir/htdocs/"
find $livedir/htdocs/ -name "*" | xargs touch

echo "## remove all smarty compiled templates at $livedir/htdocs/  "
rm -rf  $livedir/htdocs/templates_c/*
rm -rf  $livedir/popup/templates_c/*
rm -rf  $livedir/htdocs/admin/templates_c/*

echo "## Deploy done! - `date` ####################"
