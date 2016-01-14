#!/usr/local/bin/Rscript
#!/usr/bin/sh

args <- commandArgs(TRUE)
pvwdir=args[1]

source(paste0(pvwdir, "/scripts/download.specpath.R"))

specs=readLines(paste0(pvwdir, "/data/species.txt"))
specs=gsub(" ", "", specs)
kdirs = paste0(pvwdir,"/Kegg/", specs)
kdirs1 = paste0(kdirs,1)

for(i in 1:length(specs)) {
system(paste("mkdir -p", kdirs1[i]))
ds.status=download.specpath(species=specs[i], kegg.dir=kdirs1[i])
fc=sum(!ds.status=="succeed")
if(fc<5) system(paste("cp -rf", paste0(kdirs1[i], "/*"), kdirs[i]))
system(paste("rm -rf", kdirs1[i]))
}
