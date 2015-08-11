

file=`du -sm * | awk '$1 > 98' |awk '{print $2}' `
echo $file
