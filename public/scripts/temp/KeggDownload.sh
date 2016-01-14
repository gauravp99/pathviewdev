#!/bin/bash
pvwdir=$1
echo $pvwdir"data/species.txt"
$date = date
cd $pvwdir"Kegg"
while read line           
do           
mkdir $pvwdir""$line
echo $line
while read line1
do 
    echo $line1" "$line
    Rscript $pvwdir"scripts/kegg.r" $line1 $line 
done <$pvwdir"data/pathway.txt"

done <$pvwdir"data/species.txt"
 
