#!/bin/bash
echo $pvwdir"data/species.txt"
$date = date
mv $pvwdir"Kegg" $pvwdir"Kegg"$date
mkdir $pvwdir"Kegg"
while read line           
do           
mkdir $pvwdir"Kegg/"$line
echo $line
while read line1
do 
    echo $line1" "$line
    Rscript kegg.r $line1 $line 
done <$pvwdir"data/pathway.txt"

done <$pvwdir"data/species.txt"
 
