#!/usr/local/bin/Rscript
#!/usr/bin/sh

args <- commandArgs(TRUE)
pvwdir=args[1]
#pvwdir="/var/www/PathwayWeb/public/"

source(paste0(pvwdir, "/scripts/download.kegg.list.R"))
kdir=paste0(pvwdir, "/kList")
dip=download.path.list("ko", kegg.dir=kdir)
if(dip==1){
    paths=read.delim(paste0(kdir, "/", "ko", ".paths.txt"), sep="\t", head=F)
    paths[,1]=gsub(paste0("path:", "ko"), "", as.character(paths[,1]))
    write.table(paths, file=paste0(kdir, "/", "ref.paths.txt"), sep="\t", quote=F, row.names=F, col.names=F)
} else print("download organism list failed!")

dio=download.kegg.list("organism", kegg.dir=kdir)
if(dio==1){
    orgs=read.delim(paste0(kdir, "/organism.txt"), sep="\t", head=F)
    m <- as.matrix(orgs)
    colnames(m) <- c("T.number", "organism", "species", "phylogeny")
    ln1=grep("Escherichia coli K-12 MG1655", m[,3])
    #ln1=min(grep("Prokaryotes", m[,4]))
    ksp=m[1:(ln1-1),3]
    ksp.l=strsplit(ksp, " +[(]|[)]$", perl=T)
    kl=sapply(ksp.l, length)
    ksp2=cbind(m[,3], "N")
    ksp2[(1:(ln1-1))[kl==2],]=t(sapply(ksp.l[kl==2], "["))
    ji=grep("Japanese rice", ksp2[,1])
    ksp2[ji,]=rep(c("Oryza sativa japonica", "Japanese rice"),each=2)
    dise.idx=grep("Vertebrates", m[,4])
    korg.new=cbind(m[,2],ksp2, "N")
    colnames(korg.new)=c("kegg code", "scientific name", "common name", "with disease")
    korg.new[dise.idx, 4]="Y"
    ko.line=c("ko", "KEGG Orthology", "N", "N")
    korg.new=rbind(korg.new, ko.line)
    write.table(korg.new[,c(1:2,4,3)], file=paste0(kdir, "/", "kegg.species.txt"), sep="\t", quote=F, row.names=F, col.names=F)
    
    orgs=as.character(orgs[,2])
    system.time({
        paths.all=rm.orgs=NULL
        for(i in 1:length(orgs)){
            print(i)
            di=download.path.list(orgs[i], kegg.dir=kdir)
            if(di==1){
                paths=read.delim(paste0(kdir, "/", orgs[i], ".paths.txt"), sep="\t", head=F)
                paths=gsub(paste0("path:", orgs[i]), paste0(orgs[i], ","), as.character(paths[,1]))
                paths.all=c(paths.all, paths)
            } else rm.orgs=c(rm.orgs, orgs[i])
        }
        write.table(paths.all, file=paste0(kdir, "/", "spec.paths.csv"), sep=",", quote=F, row.names=F, col.names=F)
        if(length(rm.orgs)>0) write.table(rm.orgs, file=paste0(kdir, "/", "remove.species.txt"), sep="\t", quote=F, row.names=F, col.names=F)
    })
} else print("download organism list failed!")
