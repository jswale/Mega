#!/bin/sh

cd /volume1/web/Mega/

# Starting downloads
for f in $(find tmp -name '*.waiting')
do
  filename=$(basename $f)
  id=${filename%.*}
  url=`cat $f`
  echo "Starting to download $url with process $id"
  nohup php start.cli.php $url $id > tmp/$id.progress 2>&1 & echo $! > tmp/$id.pid
  rm -rf $f
done

# Stoping downloads
for f in $(find tmp -name '*.cancel')
do
  filename=$(basename $f)
  id=${filename%.*}
  pid=`cat tmp/$id.pid`
  echo "Canceling process $id"
  kill -9 $pid
  rm $f
  php cancel.cli.php $id &
done