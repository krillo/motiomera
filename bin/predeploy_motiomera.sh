#!/bin/sh
# Deploy motiomera.se
homedir="/home/motiomera"
svnrootdir="$homedir/project_svn"

echo "Choose environment to pre deploy"
echo "1 - for motimera.se"
echo "2 - for testomera.se"
echo "3 - for trunkomera.se"
read DEPLOY_TO

if [ "$DEPLOY_TO" = "1" ]; then
  #only deploy a tag - check if tag dir exists
  depenv="motiomera"
  echo "Enter which tag to pre deploy"
  read TAG
  svnrootdir=$svnrootdir/tags/$TAG
elif [ "$DEPLOY_TO" = "2" ]; then
  #only deploy a branch - check if the branch dir exists
  depenv="testomera"
  echo "Enter which beta-tag to pre deploy"
  read TAG
  svnrootdir=$svnrootdir/tags/$TAG
elif [ "$DEPLOY_TO" = "3" ]; then
  depenv="trunkomera"
  svnrootdir=$svnrootdir/trunk
else
  cat /home/motiomera/project_svn/trunk/bin/bart
  echo "## Exiting pre deploy"
  exit 0
fi

if [ -d "$svnrootdir" ]; then
  cd $svnrootdir
else
  cat /home/motiomera/project_svn/trunk/bin/bart 
  echo "## $svnrootdir  does not exist"
  echo "## Exiting pre deploy"
  exit 0
fi

workdir="$homedir/$depenv"
sqlworkdir="$workdir/db"

echo "## Pre deploy $depenv - `date` ###################"

echo "create sql work dir - $sqlworkdir "
if [ -d "$sqlworkdir" ];
then
 rm -rf $sqlworkdir
fi
mkdir $sqlworkdir
chown -R motiomera:motiomera $sqlworkdir/

echo "## export all sql files to work dir "
su motiomera -c "svn export --force $svnrootdir/db/  $sqlworkdir/"

echo "## customize sql scripts to $depenv i.e swap use motiomera to use testomera"
#find $sqlworkdir | xargs perl -p -i -e .s/use \`motiomera\`/use \`$depenv\`/g.

echo "## Pre deploy done `date` ###################"

