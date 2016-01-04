#source("/var/www/Pathway/public/kg.map.R")
if(!exists("pvwdir")) pvwdir = substr(getwd(),1,nchar(getwd())-30)
print(pvwdir)
mmap.dir1=paste(pvwdir,"/mmap/",sep="")
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

kg.name2id=function(mmap.dir=mmap.dir1){
    species="cpd"
    fn.list=list.files(mmap.dir, full.names=F)
    furl=paste0("http://rest.kegg.jp/list/", species)
    mmap.dir=gsub("//", "/", paste0(mmap.dir, "/"))
    fname=paste0(mmap.dir, species, ".txt")
    oname=paste0(mmap.dir, species, ".name2id.RData")
    if(!basename(fname) %in% fn.list){
        dstatus=download.file(furl, fname, quiet=T)
    }
    if(!basename(oname) %in% fn.list){
        gtab=read.delim(fname, sep="\t", head=F, row.names=1, stringsAsFactors=F)
        cn.list=strsplit(gtab[,1], "; ")
        lens=sapply(cn.list, length)
        cid2name=unlist(cn.list)
        cids=gsub(paste0(species, ":"),"",rownames(gtab))
        cids2=rep(cids, lens)
        cname2id=cids2
        names(cname2id)=cid2name
        save(cname2id, file=oname)
    }
}

old.code=function(){
    if(!is.null(gene.d) & !is.null(pv.out$plot.data.gene)) {
        gids=pv.out$plot.data.gene$all.mapped
        gids=strsplit(gids, ",")
        lens=sapply(gids, length)
        idx2=cumsum(lens)
        ln=length(idx2)
        idx1=c(0,idx2[-ln])+1
        gids.v=unlist(gids)
        gsymb.v=eg2symbs[gids.v]
        gsymbs=sapply(1:ln, function(i) paste(gsymb.v[idx1[i]:idx2[i]], collapse=","))
        gsymbs[idx1>idx2]=""
        ncg=ncol(pv.out$plot.data.gene)
        pvg=cbind(pv.out$plot.data.gene[,1:3], all.mapped.symb=gsymbs, pv.out$plot.data.gene[,4:ncg])
        write.table(pvg,file=paste(paste(paste("genedata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE, sep="\t")
    }
    if(!is.null(cpd.d) & !is.null(pv.out$plot.data.cpd)) {
        cids=pv.out$plot.data.cpd$all.mapped
        cnames=cids
        eidx=cnames>""
        cnames[eidx]=cid2name[cnames[eidx]]
        ncc=ncol(pv.out$plot.data.cpd)
        pvc=cbind(pv.out$plot.data.cpd[,1:3], all.mapped.name=cnames, pv.out$plot.data.cpd[,4:ncc])
        write.table(pvc,file=paste(paste(paste("cpddata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE, sep="\t")
    }
}


pv.labels=function(pv.out, pv.data.type=c("gene", "cpd"),  pid){
    if("gene" %in% pv.data.type & !is.null(pv.out$plot.data.gene)) {
        gids=pv.out$plot.data.gene$all.mapped
        gids=strsplit(gids, ",")
        lens=sapply(gids, length)
        idx2=cumsum(lens)
        ln=length(idx2)
        idx1=c(0,idx2[-ln])+1
        gids.v=unlist(gids)
        gsymb.v=eg2symbs[gids.v]
        gsymbs=sapply(1:ln, function(i) paste(gsymb.v[idx1[i]:idx2[i]], collapse=","))
        gsymbs[idx1>idx2]=""
        ncg=ncol(pv.out$plot.data.gene)
        pvg=cbind(pv.out$plot.data.gene[,1:3], all.mapped.symb=gsymbs, pv.out$plot.data.gene[,4:ncg])
        write.table(pvg,file=paste(paste(paste("genedata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE, sep="\t")
    }
    if("cpd" %in% pv.data.type & !is.null(pv.out$plot.data.cpd)) {
        cids=pv.out$plot.data.cpd$all.mapped
        cnames=cids
        eidx=cnames>""
        cnames[eidx]=cid2name[cnames[eidx]]
        ncc=ncol(pv.out$plot.data.cpd)
        pvc=cbind(pv.out$plot.data.cpd[,1:3], all.mapped.name=cnames, pv.out$plot.data.cpd[,4:ncc])
        write.table(pvc,file=paste(paste(paste("cpddata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE, sep="\t")
    }
}
