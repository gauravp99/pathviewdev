#!/bin/bash

#This script pulls the information from the kegg website for 
#the new species and the relevant pathways associated with the 
#species. The actual work will be done by the R script update.specpath.R which 
#gets invoked from this shell script.
#This script also takes into account for the invalid species and removes those 
#species from the database .
#Again this script runs from the public directory and runs via a scheduled 
#job configured in cron.
#Manual Running: sudo ./scripts/update.specPath.sh <public_path>

#Author: Gaurav Pant


pvwdir=$1
if [ ! -f ../.env ]
then
   echo "The environment file doesn't exist.. Exiting !!"
   exit 1
fi

source ../.env
if [ -z $DB_USERNAME ] || [ -z $DB_PASSWORD ] || [ -z $DB_DATABASE ]
then
   echo "Database information not found. Exiting ...!!"
   exit 1
fi
MYSQL="mysql -u$DB_USERNAME -p$DB_PASSWORD $DB_DATABASE"

cd $pvwdir"/kList"

start=`date +%s`
echo "started the job "$start

echo "started running the Rscript"
Rscript $pvwdir"/scripts/update.specpath.R" $pvwdir
echo "end running the Rscript"

echo "removing if any redudent data exist"
uniq spec.paths.csv > spec.paths.uniq.csv

echo "sorting the data"
sort spec.paths.uniq.csv > spec.paths.csv

echo "creating a new table"
printf "create table speciesPathwayMatch_new(
species_id varchar(10), 
pathway_id varchar(10), 
created_at timestamp default current_timestamp, 
updated_at timestamp DEFAULT 0, 
PRIMARY KEY (species_id,pathway_id)
);" | $MYSQL

echo "deleteing if by mistake any data present in new table"

printf "delete from speciesPathwayMatch_new"|$MYSQL

echo "loading data from rscript into new tables"
printf "load data infile '"%s"/kList/spec.paths.csv' into table speciesPathwayMatch_new fields terminated by ',' lines terminated by '\n'"  "$pvwdir"|$MYSQL;

echo "making a backup of the new data"

printf "delete from speciesPathwayMatch_bkp"|$MYSQL

printf "INSERT INTO speciesPathwayMatch_bkp (species_id,pathway_id,created_at,updated_at)
SELECT distinct species_id,pathway_id,created_at,updated_at
FROM speciesPathwayMatch_new"|$MYSQL
 
echo "removing the old table and renaming the existing table to old"
printf "drop table speciesPathwayMatch_old" | $MYSQL
printf "rename table speciesPathwayMatch to speciesPathwayMatch_old" | $MYSQL;


echo "updating the new table to production table"
printf "rename table speciesPathwayMatch_new to speciesPathwayMatch" | $MYSQL;


#update pathway table
echo "drop table pathway_old"|mysql -uroot -proot PathwayWeb
echo "create table pathway_new
(
pathway_id varchar(10),
pathway_desc varchar(100),
created_at timestamp default 0,
updated_at timestamp default 0,
primary key(pathway_id)
)"|mysql -uroot -proot PathwayWeb


echo "loading data from rscript into new tables"
printf "load data infile '$pvwdir/kList/ref.paths.txt' into table pathway_new fields terminated by '\t' lines terminated by '\n'"  "$pvwdir"|$MYSQL;

echo "rename table pathway to pathway_old"|$MYSQL

echo "rename table pathway_new to pathway"|$MYSQL

#species table updation
echo "drop table species_old"|$MYSQL
echo "create table species_new
(
species_id varchar(10),
species_desc varchar(100),
disease_index_exist varchar(10),
species_common_name varchar(100),
created_at timestamp default 0,
updated_at timestamp default 0,
primary key(species_id)
)"|$MYSQL


echo "loading species data from rscript into new tables"
printf "load data infile '$pvwdir/kList/kegg.species.txt' into table species_new fields terminated by '\t' lines terminated by '\n'"  "$pvwdir"|$MYSQL
#delete
echo "Deleting the species which are not associated with a pathway..."
if [ -f "$pvwdir/kList/kegg.species.txt" ]
then
    sed -i~ -e "s/^/'/;s/$/'/" "$pvwdir/kList/remove.species.txt"
    remove_species=`paste -sd',' "$pvwdir/kList/remove.species.txt"`
    echo "delete from species_new where species_id in ($remove_species)" |$MYSQL
fi

echo "rename table species to species_old"|$MYSQL

echo "rename table species_new to species"|$MYSQL

end=`date +%s`
runtime=$((end-start))
echo $runtime" seconds"
