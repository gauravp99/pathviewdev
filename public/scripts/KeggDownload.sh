#!/bin/bash
while read line           
do           
mkdir "/home/ybhavnasi/Desktop/Kegg/"$line
while read line1
do 
    Rscript kegg.r $line1 $line 
done <pathway.txt

done <species.txt
 
