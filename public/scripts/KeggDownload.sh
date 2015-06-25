#!/bin/bash
while read line           
do           
mkdir "../Kegg/"$line
while read line1
do 
    Rscript kegg.r $line1 $line 
done <../data/pathway.txt

done <../data/species.txt
 
