#source("/var/www/Pathway/public/kg.map.R")

mmap.dir1="/var/www/PathwayWeb/public/mmap/"
kg.map=function(species="hsa", mmap.dir=mmap.dir1){
    fn.list=list.files(mmap.dir, full.names=F)
    furl=paste0("http://rest.kegg.jp/list/", species)
    mmap.dir=gsub("//", "/", paste0(mmap.dir, "/"))
    fname=paste0(mmap.dir, species, ".gene.txt")
    oname=paste0(mmap.dir, species, ".gene.RData")
    if(!basename(fname) %in% fn.list){
        dstatus=download.file(furl, fname, quiet=T)
    }
    if(!basename(oname) %in% fn.list){
        gtab=read.delim(fname, sep="\t", head=F, row.names=1, stringsAsFactors=F)
        eg2symbs=sapply(strsplit(gtab[,1], ", |; "), "[",1)
        egs=gsub(paste0(species, ":"),"",rownames(gtab))
        names(eg2symbs)=egs
        save(eg2symbs, file=oname)
    }
}

kg.cmap=function(mmap.dir=mmap.dir1){
    species="cpd"
    fn.list=list.files(mmap.dir, full.names=F)
    furl=paste0("http://rest.kegg.jp/list/", species)
    mmap.dir=gsub("//", "/", paste0(mmap.dir, "/"))
    fname=paste0(mmap.dir, species, ".txt")
    oname=paste0(mmap.dir, species, ".RData")
    if(!basename(fname) %in% fn.list){
        dstatus=download.file(furl, fname, quiet=T)
    }
    if(!basename(oname) %in% fn.list){
        gtab=read.delim(fname, sep="\t", head=F, row.names=1, stringsAsFactors=F)
        cid2name=sapply(strsplit(gtab[,1], "; "), "[",1)
        cids=gsub(paste0(species, ":"),"",rownames(gtab))
        names(cid2name)=cids
        save(cid2name, file=oname)
    }
}
