
                                         #to get the arguments readable in R code
args <- commandArgs(TRUE)

                                        # @arguments are split on comma as they are passed to Rscript with comma separated
#library(pathview)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]

logic.idx=c("kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc", "autosel")#"autoPathwaySelection")

num.idx=c("offset", "glmt", "gbins", "clmt", "cbins", "pathidx")
#num.idx=c("offset", "gbins", "cbins", "pathidx")
cn.idx=c("generef", "genesamp", "cpdref", "cpdsamp")


#args2=as.list(args1)
args2=strsplit(args1, ",")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
args2[cn.idx]=lapply(args2[cn.idx], function(x){
                         if(length(x)==0) return(NULL)
                         if(x[1]=="NULL") return(NULL)
                         else return(as.numeric(x))
                     })
#commented parameters
args2$split=F
args2$expand=F

#pvwdir = Sys.getenv("pvwdir")
pvwdir = paste0(getwd(), "/public/")
pvwdir=gsub("public/public/", "public/", pvwdir)

setwd(args2$targedir)


save.image("workenv.RData")

#path.ids = strsplit(args2$pathway,split=";")[[1]]
#args2$glmt = as.numeric(strsplit(args2$glmt,split=";")[[1]])
#args2$clmt = as.numeric(strsplit(args2$clmt,split=";")[[1]])
args2$cpdid=tolower(args2$cpdid)

#setwd(args2$targedir)
zz <- file("errorFile.Rout", open = "wt")
sink(zz,type = "message")
ncol.gene=ncol.cpd=0
if(!is.null(args2$geneextension) && length(args2$geneextension) > 0){
    if(args2$geneextension == "txt"){
        a=read.delim(args2$filename, sep="\t", row.names=NULL)
    } else if(args2$geneextension == "csv"){
        a=read.delim(args2$filename, sep=",", row.names=NULL)
    } else stop(paste(args2$geneextension, ": unsupported gene data file type!"), sep="")

    if(ncol(a)>1){
        gene.d=as.matrix(a[,-1])
        if(!is.null(args2$generef[1])){
            ngsamp=length(args2$genesamp)
            ngref=length(args2$generef)
            if(args2$genecompare=="paired" & ngsamp==ngref) gene.d=gene.d[,args2$genesamp]- gene.d[,args2$generef]
            else if (ngref==1) gene.d=gene.d[,args2$genesamp]- gene.d[,args2$generef]
            else gene.d=gene.d[,args2$genesamp]- rowMeans(gene.d[,args2$generef])
        }
        gene.d=cbind(gene.d)
        rownames(gene.d)=make.unique(as.character(a[,1]))
        ncol.gene=ncol(gene.d)
    } else if(ncol(a)==1) {
        a=as.matrix(a)
        gene.d=a[,1]
        if(is.null(names(gene.d))) gene.d=as.character(gene.d)
        ncol.gene=1
    } else stop("Empty gene data file!")
} else gene.d=NULL

if(!is.null(args2$cpdextension) && length(args2$cpdextension) > 0){
    if(args2$cpdextension == "txt"){
        a1=read.delim(args2$cfilename, sep="\t", row.names=NULL)
    } else if(args2$cpdextension == "csv"){
        a1=read.delim(args2$cfilename, sep=",", row.names=NULL)
    } else stop(paste(args2$cpdextension, ": unsupported compound data file type!"), sep="")

    if(ncol(a1)>1){
        cpd.d=as.matrix(a1[,-1])
        if(!is.null(args2$cpdref[1])){
            ncsamp=length(args2$cpdsamp)
            ncref=length(args2$cpdref)
            if(args2$cpdcompare=="paired" & ncsamp==ncref) cpd.d=cpd.d[,args2$cpdsamp]- cpd.d[,args2$cpdref]
            else if (ncref==1) cpd.d=cpd.d[,args2$cpdsamp]- cpd.d[,args2$cpdref]
            else cpd.d=cpd.d[,args2$cpdsamp]- rowMeans(cpd.d[,args2$cpdref])
        }
        cpd.d=cbind(cpd.d)
        rownames(cpd.d)=make.unique(as.character(a1[,1]))
        ncol.cpd=ncol(cpd.d)
    } else if(ncol(a1)==1) {
        a1=as.matrix(a1)
        cpd.d=a1[,1]
        if(is.null(names(cpd.d))) cpd.d=as.character(cpd.d)
        ncol.cpd=1
    } else stop("Empty compound data file!")
} else cpd.d=NULL

save.image("workenv.RData")

##kegg directory
species0=species=args2$species
if(ncol.gene!=ncol.cpd) args2$matchd=F

#select pathway here
auto.sel=args2$autosel
nmax0=20
nmax=6
if(auto.sel){
require(gage)
mset="sigmet.idx"
    ncut=2
    qcut=0.2
gpath.ids=cpath.ids=NULL
gsets.dir0=paste(pvwdir,'genesets/',sep="")

gclass=class(gene.d)
cclass=class(cpd.d)
gid.type=tolower(args2$geneid)
cid.type=tolower(args2$cpdid)

if(gclass=="matrix" | gclass=="numeric"){

###gene set data
#    map.data=F
#gs.type=args2$mset.category
#gid.type=tolower(args2$mid.type)
#    gid.type=tolower(args2$geneid)
    map.data=F
data(bods, package="gage")
bods[,"id.type"]=gsub("eg", "entrez", bods[,"id.type"])
#gsets.dir="/var/www/PathwayWeb/public/genesets/"
#gsets.dir=paste(pvwdir,'genesets/',sep="")
    if(!gid.type %in% c("entrez", "kegg")) {
        gid.type0=gid.type
        gid.type="entrez"
        map.data=T
        idx=which(bods[,"kegg code"] == species)
        ##        if(length(idx)!=1) stop("bad species value")
    }
    gsets.dir=paste(gsets.dir0, "kegg/", sep="")
    gsfn=paste(gsets.dir, species, ".", gid.type, ".kset.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        sub.idx=unique(unlist(kset.data[mset]))
        gsets=kset.data$kg.sets[sub.idx]
    } else {
        kset.data=kegg.gsets(species=species, id.type =gid.type)
        save(kset.data, file=gsfn)
        sub.idx=unique(unlist(kset.data[mset]))
        gsets=kset.data$kg.sets[sub.idx]
    }

    if(map.data){
    require(pathview)
#        source(paste(pvwdir,"scripts/annot.map.R",sep=""))
    pkg.name = bods[idx, "package"]
    gid.in=gid.type0
    gid.out=gid.type
    if(gid.in=="entrez" | gid.in=="eg") gid.in="ENTREZID"
    if(gid.out=="entrez" | gid.out=="eg") gid.out="ENTREZID"
    exprs=cbind(gene.d)
    mol.ids=rownames(exprs)
    gene.idmap=geneannot.map(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out), pkg.name=pkg.name, na.rm=F)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.csv(gene.idmap, file = "gene.idmap.csv", row.names=F, quote=F)
    gene.d=exprs=pathview::mol.sum(exprs, gene.idmap)
} else  gene.d=exprs=cbind(gene.d)

    print(0)
    print(gsfn)
    print(length(gsets))
    ### gage 1-d #implement weights later
gage.res=gage(exprs=exprs, gsets=gsets, ref = NULL, samp = NULL,
    set.size = c(10, Inf),  same.dir = TRUE) #same.dir = FALSE)
    pgs=gage.res$greater[,"p.val"]
    pms=cbind(pgs*2, (1-pgs)*2)
    pgs.gene=apply(pms, 1, function(x) min(x))
    qgs.gene=p.adjust(pgs.gene, method = "BH")

    colnames(pms)=c("p.up", "p.dn")
    gage.out=cbind(gage.res$greater[, c(2,5)], pms/2, p.val=pgs.gene, q.val=qgs.gene)
    gage.out=gage.out[order(pgs.gene),]
    write.csv(gage.out, file = "gage.res.gene.csv", quote = FALSE)

print(1)

### significant.genesets
    sig.i=gage.out[,"q.val"]<qcut & !is.na(gage.out[,"q.val"])
    nsig=sum(sig.i, na.rm=T)
if(nsig>0) {
    gage.out.sig=data.frame(gage.out)[sig.i,]
    ord1=order(gage.out.sig[,"stat.mean"], decreasing=T)
    gage.out.sig=gage.out.sig[ord1,]
    write.csv(gage.out.sig, file = "gage.res.sig.gene.csv", quote = FALSE)

    gpath.ids=rownames(gage.out.sig)
    pdf("gage.res.gene.gs.heatmap.pdf")
    gage:::gs.heatmap(gage.res$stats[gpath.ids, -1], limit = 5, main = "GAGE test statistics")
    dev.off()

} else {
    print("No gene set selected in 1d-test, select the top 3 instead!")
    gpath.ids=unique(c(rownames(gage.res$greater)[1:3],rownames(gage.res$less)[1:3]))
    }
print(2)

} else if(gclass=="character"){
    mol.sel=gene.d
    
###gene set data
#    map.data=F
#gs.type=mset.category
#gid.type=tolower(args2$mid.type)
#    gid.type=tolower(args2$geneid)
    map.data=F
data(bods, package="gage")
bods[,"id.type"]=gsub("eg", "entrez", bods[,"id.type"])
#gsets.dir="/var/www/PathwayWeb/public/genesets/"
    if(!gid.type %in% c("entrez", "kegg")) {
        gid.type0=gid.type
        gid.type="entrez"
        map.data=T
        idx=which(bods[,"kegg code"] == species)
        ##        if(length(idx)!=1) stop("bad species value")
    }
    gsets.dir=paste(gsets.dir0, "kegg/", sep="")
    gsfn=paste(gsets.dir, species, ".", gid.type, ".kset.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        sub.idx=unique(unlist(kset.data[mset]))
        gsets=kset.data$kg.sets[sub.idx]
    } else {
        kset.data=kegg.gsets(species=species, id.type =gid.type)
        save(kset.data, file=gsfn)
        sub.idx=unique(unlist(kset.data[mset]))
        gsets=kset.data$kg.sets[sub.idx]
    }

    if(map.data){
    require(pathview)
                                        #    source("/var/www/Pathway/public/scripts/annot.map.R")
    pkg.name = bods[idx, "package"]
    gid.in=gid.type0
    gid.out=gid.type
    mol.ids=mol.sel
    if(gid.in=="entrez" | gid.in=="eg") gid.in="ENTREZID"
    if(gid.out=="entrez" | gid.out=="eg") gid.out="ENTREZID"
    gene.idmap=geneannot.map(in.ids=mol.sel, in.type=toupper(gid.in), out.type=toupper(gid.out), pkg.name=pkg.name, na.rm=F)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.csv(gene.idmap, file = "gene.idmap.csv", row.names=F, quote=F)
    gene.d=mol.sel=gene.idmap[,2]
    #if(!is.null(mol.bg)) mol.bg=gene.idmap[-c(1:nsel),2]
} else gene.d=mol.sel

    gsets.all=unique(unlist(gsets))
mol.sel.0=mol.sel
mol.sel=mol.sel[mol.sel %in% gsets.all]
mol.bg= gsets.all
nsel=length(mol.sel)
nbg=length(mol.bg)

cnts.sel=sapply(gsets, function(gs){
                    ii=gs %in% mol.sel
                    return(c(length(ii), sum(ii)))
                })


    cnts.bg=sapply(gsets, function(gs){
                       ii=gs %in% mol.bg
                       return(c(length(ii), sum(ii)))
                   })
#    p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], cnts.bg[1,]-cnts.bg[2,],cnts.sel[1,], lower.tail=F)
    pgs.gene=p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], nbg-cnts.bg[2,],nsel, lower.tail=F)
    q.val=p.adjust(p.val,  method ="BH")
    stats=cbind(t(cnts.sel[1:2,]),nsel, cnts.bg[2,], nbg, p.val, q.val)
    colnames(stats)=c("set.size", "hits","selected", "hits.bg", "background", "p.val", "q.val")
    stats=stats[order(p.val),]
    gage.out=cbind(stats[,2]/stats[,4], stats[,c(2,6:7)])
    sel.idx=stats[,"hits"]>=ncut & stats[,"q.val"]<=qcut

print(1)

### significant.genesets
if(nrow(stats)>0)  write.csv(stats, file = "discrete.res.gene.csv", quote=F)

nsig=sum(sel.idx)
if(nsig>0) {
    write.csv(stats[sel.idx,], file = "discrete.sig.gene.csv", quote=F)
    gpath.ids=rownames(stats)[sel.idx]
} else {
    print("No gene set selected in 1d-test, view the top 3 instead!")
    gpath.ids=rownames(stats)[1:3]
    }
}


#################compound data
if(cclass=="matrix" | cclass=="numeric"){

    map.data=F
        data(rn.list, package="pathview")
    cpd.type=names(rn.list)=tolower(names(rn.list))
    cpd.type=c(cpd.type,"compound name", "kegg")
#    cpd.type2=c("SMPDB ID", cpd.type[c(3,5,8:11)])
    cpd.type2=cpd.type[c(3,5,8:11)]
    cpd.type2=tolower(cpd.type2)
    kg.idx=grep("kegg", cpd.type)
    gsets.dir=paste(gsets.dir0, "cpd/", sep="")
#    species="ko"
    
    if(!cid.type %in% cpd.type) stop("Incorrect type!")
    if(!cid.type %in% cpd.type[kg.idx]) {
        cid.type0=cid.type
        cid.type="kegg"#?
        map.data=T
    }

    gsfn=paste(gsets.dir,  "kegg.cpd.set.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        csets=kegg.cpd.set
        if(exists("gsets") & species!="ko") {
            gpaths=names(gsets)
            cpaths=gsub("ko", species, names(csets))
            names(csets)=cpaths
            cpi=cpaths %in% gpaths
            csets=csets[cpi]
        }
    } else stop("Can't find KEGG compound set data!")

if(map.data){
    require(pathview)
    gid.in=cid.type0
    gid.out=cid.type
    exprs=cbind(cpd.d)
    mol.ids=rownames(exprs)
    if(gid.in=="compound name"){
        source(paste(pvwdir,"scripts/kg.map.R",sep=""))
        kg.name2id()
        cm.fname=paste0(mmap.dir1, "cpd.name2id", ".RData")
        load(cm.fname)
        gene.idmap=cbind(in.inds=mol.ids, out.ids=cname2id[mol.ids])
    } else{
    gene.idmap=cpdidmap(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out))#?kegg 2 name update
    }
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.csv(gene.idmap, file = "compound.idmap.csv", row.names=F, quote=F)
    cpd.d=exprs=pathview::mol.sum(exprs, gene.idmap)
} else cpd.d=exprs=cbind(cpd.d)

    
gage.res.cpd=gage(exprs=exprs, gsets=csets, ref = NULL, samp = NULL,
    set.size = c(10, Inf),  same.dir = TRUE) #same.dir = FALSE)
    pgs=gage.res.cpd$greater[,"p.val"]
    pms=cbind(pgs*2, (1-pgs)*2)
    pgs.cpd=apply(pms, 1, function(x) min(x))
    qgs.cpd=p.adjust(pgs.cpd, method = "BH")

    colnames(pms)=c("p.up", "p.dn")
    gage.out.cpd=cbind(gage.res.cpd$greater[, c(2,5)], pms/2, p.val=pgs.cpd, q.val=qgs.cpd)
    gage.out.cpd=gage.out.cpd[order(pgs.cpd),]
    write.csv(gage.out.cpd, file = "gage.res.cpd.csv", quote = FALSE)

print(1)

### significant.genesets
    sig.i=gage.out.cpd[,"q.val"]<qcut & !is.na(gage.out.cpd[,"q.val"])
    nsig=sum(sig.i, na.rm=T)
if(nsig>0) {
    gage.out.cpd.sig=data.frame(gage.out.cpd)[sig.i,]
    ord1=order(gage.out.cpd.sig[,"stat.mean"], decreasing=T)
    gage.out.cpd.sig=gage.out.cpd.sig[ord1,]
    write.csv(gage.out.cpd.sig, file = "gage.res.sig.cpd.csv", quote = FALSE)

    cpath.ids=rownames(gage.out.cpd.sig)
    pdf("gage.res.cpd.gs.heatmap.pdf")
    gage:::gs.heatmap(gage.res.cpd$stats[cpath.ids, -1], limit = 5, main = "GAGE test statistics")
    dev.off()

} else {
    print("No compound set selected in 1d-test, select the top 3 instead!")
    cpath.ids=unique(c(rownames(gage.res.cpd$greater)[1:3],rownames(gage.res.cpd$less)[1:3]))
    }
print(2)

} else if(cclass=="character"){
    mol.sel=cpd.d
    
    map.data=F
    data(rn.list, package="pathview")
    cpd.type=names(rn.list)=tolower(names(rn.list))
    cpd.type=c(cpd.type,"compound name", "kegg")
    cpd.type2=cpd.type[c(3,5,8:11)]
    cpd.type2=tolower(cpd.type2)
    kg.idx=grep("kegg", cpd.type)
    gsets.dir=paste(gsets.dir0, "cpd/", sep="")
#    species="ko"
    

    if(!cid.type %in% cpd.type) stop("Incorrect type!")
    if(!cid.type %in% cpd.type[kg.idx]) {
        cid.type0=cid.type
        cid.type="kegg"#?
        map.data=T
    }

    gsfn=paste(gsets.dir,  "kegg.cpd.set.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        csets=kegg.cpd.set
        if(exists("gsets") & species!="ko") {
            gpaths=names(gsets)
            cpaths=gsub("ko", species, names(csets))
            names(csets)=cpaths
            cpi=cpaths %in% gpaths
            csets=csets[cpi]
        }
    } else stop("Can't find KEGG compound set data!")


if(map.data){
    require(pathview)
    gid.in=cid.type0
    gid.out=cid.type
    mol.ids=mol.sel
    gene.idmap=cpdidmap(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out))#?kegg 2 name update
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.csv(gene.idmap, file = "compound.idmap.csv", row.names=F, quote=F)
    cpd.d=mol.sel=gene.idmap[,2]
} else cpd.d=mol.sel

print(0)
rows = length(csets)
                                        #create a matrix with number of rows as number of rows in gests 
m <- matrix(c(1:rows),nrow=rows)
csets.all=unique(unlist(csets))
mol.sel.0=mol.sel
mol.sel=mol.sel[mol.sel %in% csets.all]
    mol.bg= csets.all
nsel=length(mol.sel)
nbg=length(mol.bg)

cnts.sel=sapply(csets, function(gs){
                    ii=gs %in% mol.sel
                    return(c(length(ii), sum(ii)))
                })


    cnts.bg=sapply(csets, function(gs){
                       ii=gs %in% mol.bg
                       return(c(length(ii), sum(ii)))
                   })
#    p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], cnts.bg[1,]-cnts.bg[2,],cnts.sel[1,], lower.tail=F)
    pgs.cpd=p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], nbg-cnts.bg[2,],nsel, lower.tail=F)
    q.val=p.adjust(p.val,  method ="BH")
    stats.cpd=cbind(t(cnts.sel[1:2,]),nsel, cnts.bg[2,], nbg, p.val, q.val)
    colnames(stats.cpd)=c("set.size", "hits","selected", "hits.bg", "background", "p.val", "q.val")
    stats.cpd=stats.cpd[order(p.val),]
    gage.out.cpd=cbind(stats.cpd[,2]/stats.cpd[,4], stats.cpd[,c(2,6:7)])
    sel.idx=stats.cpd[,"hits"]>=ncut & stats.cpd[,"q.val"]<=qcut

### significant.genesets
if(nrow(stats.cpd)>0)  write.csv(stats.cpd, file = "discrete.res.cpd.csv", quote=F)

nsig=sum(sel.idx)
if(nsig>0) {
    write.csv(stats.cpd[sel.idx,], file = "discrete.sig.cpd.csv", quote=F)
    cpath.ids=rownames(stats.cpd)[sel.idx]
} else {
    print("No gene set selected in 1d-test, view the top 3 instead!")
    cpath.ids=rownames(stats.cpd)[1:3]
    }
}

path.ids=c(gpath.ids,cpath.ids)
nsig.c=0
if(!is.null(gene.d) & !is.null(cpd.d)){
    pnames=names(gsets)
    pmat=cbind(pgs.gene[pnames], pgs.cpd[pnames])
    log.pmat=-log(pmat)
    nc <- apply(log.pmat,1, function(x) sum(!is.na(x)))
    sg.glob <- apply(log.pmat, 1, sum, na.rm=T)
    pvals <- pgamma(sg.glob, shape = nc, rate = 1, lower.tail = FALSE)
    qvals=p.adjust(pvals, method = "BH")
    colnames(pmat)=paste0("p.", c("gene","cpd"))
    gmat=cmat=cbind(gage.out[pnames,1:2], gage.out.cpd[,1][pnames], gage.out.cpd[,2][pnames])
    colnames(gmat)=paste0(rep(c("stat.mean", "set.size"),2), rep(c(".gene",".cpd"),each=2))

    combo.out=cbind(gmat, pmat, p.global=pvals, q.global=qvals)
    combo.out=combo.out[order(pvals),]
    combo.out[is.nan(combo.out)]=NA
    write.csv(combo.out, file="combo.res.csv",  quote=F)

    sig.c=combo.out[,"q.global"]<qcut & !is.na(combo.out[,"q.global"])
    nsig.c=sum(sig.c, na.rm=T)
    if(nsig.c>0){
        combo.out.sig=data.frame(combo.out)[sig.c,]
        write.csv(combo.out.sig, file = "combo.res.sig.csv", quote = FALSE)
        path.ids=rownames(combo.out.sig)
    }
} 

path.ids=gsub(".+([0-9]{5}).+", "\\1",path.ids)
globs=grep("^01[1-2]", path.ids)
#rm.glob=length(globs)>0 & (!is.null(gene.d) | !args2$kegg)
#if(rm.glob) path.ids=path.ids[- globs]
if(length(globs)>0) path.ids=path.ids[- globs]
path.ids=unique(path.ids)
if(length(path.ids)>nmax & nsig.c<1){
    lgp=length(gpath.ids)
    lcp=length(cpath.ids)
    mn=min(lgp,lcp,round(nmax/2))
    path.ids=c(gpath.ids[1:mn],cpath.ids[1:mn])
    if(lgp>mn) path.ids=c(path.ids,gpath.ids[(mn+1):lgp])
    if(lcp>mn) path.ids=c(path.ids,cpath.ids[(mn+1):lcp])
    path.ids=gsub(".+([0-9]{5}).+", "\\1",path.ids)
    globs=grep("^01[1-2]", path.ids)
#    rm.glob=length(globs)>0 & (!is.null(gene.d) | !args2$kegg)
#    if(rm.glob) path.ids=path.ids[- globs]
    if(length(globs)>0) path.ids=path.ids[- globs]
    path.ids=unique(path.ids)
}
path.ids=path.ids[1:min(nmax, length(path.ids))]

} else{
    path.ids=args2$pathway
    globs=grep("^01[1-2]", path.ids)
#    rm.glob=length(globs)>0 & (!is.null(gene.d) | !args2$kegg)
#    if(rm.glob) path.ids=path.ids[- globs]
    if(length(globs)>0) path.ids=path.ids[- globs]
    path.ids=unique(path.ids)
    path.ids=path.ids[1:min(nmax0, length(path.ids))]
}

source(paste(pvwdir,"scripts/do.pathview.R",sep=""))
save.image("workenv.RData")
