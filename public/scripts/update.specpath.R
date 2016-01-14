#!/usr/local/bin/Rscript
#!/usr/bin/sh

args <- commandArgs(TRUE)
pvwdir=args[1]
#pvwdir="/var/www/PathwayWeb/public/"

source(paste0(pvwdir, "/scripts/download.kegg.list.R"))
kdir=paste0(pvwdir, "/kList")
dio=download.kegg.list("organism", kegg.dir=kdir)
if(dio==1){
    orgs=read.delim(paste0(kdir, "/organism.txt"), sep="\t", head=F)
    orgs=as.character(orgs[,2])
    system.time({
        paths.all=NULL
        for(i in 1:3){#length(orgs)){
            print(i)
            di=download.path.list(orgs[i], kegg.dir=kdir)
            if(di==1){
                paths=read.delim(paste0(kdir, "/", orgs[i], ".paths.txt"), sep="\t", head=F)
                paths=gsub(paste0("path:", orgs[i]), paste0(orgs[i], ","), as.character(paths[,1]))
                paths.all=c(paths.all, paths)
            }
        }
        write.table(paths.all, file=paste0(kdir, "/", "spec.paths.csv"), sep=",", quote=F, row.names=F, col.names=F)
    })
} else print("download organism list failed!")
