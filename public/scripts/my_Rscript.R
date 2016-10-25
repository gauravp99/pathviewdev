
                                         #to get the arguments readable in R code
args <- commandArgs(TRUE)

                                        # @arguments are split on comma as they are passed to Rscript with comma separated
#library(pathview)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]

logic.idx=c("kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc", "autoPathwaySelection")#"autosel")
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
#zz <- file("errorFile.Rout", open = "wt")
#sink(zz,type = "message")
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
    } else if(ncol(a)==1) {
        a=as.matrix(a)
        gene.d=a[,1]
        if(is.null(names(gene.d))) gene.d=as.character(gene.d)
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
    } else if(ncol(a1)==1) {
        a1=as.matrix(a1)
        cpd.d=a1[,1]
        if(is.null(names(cpd.d))) cpd.d=as.character(cpd.d)
    } else stop("Empty compound data file!")
} else cpd.d=NULL

save.image("workenv.RData")

##kegg directory
    species0=species=args2$species

#select pathway here
auto.sel=args2$autoPathwaySelection
nmax=4
if(auto.sel){
require(gage)
mset="sigmet.idx"
    ncut=2
    qcut=0.1
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
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
    gene.d=exprs=pathview::mol.sum(exprs, gene.idmap)
} else  gene.d=exprs=cbind(gene.d)

    print(0)
    print(gsfn)
    print(length(gsets))
    ### gage 1-d #implement weights later
gage.res=gage(exprs=exprs, gsets=gsets, ref = NULL, samp = NULL,
    set.size = c(10, Inf),  same.dir = TRUE) #same.dir = FALSE)
write.table(rbind(gage.res$greater, gage.res$less),
            file = "gage.res.gene.txt", sep = "\t", quote = FALSE)

print(1)

### significant.genesets
gage.res.sig<-sigGeneSet(gage.res, outname="gage.res.gene", cutoff=qcut)
sig.gs=unique(c(rownames(gage.res.sig$greater), rownames(gage.res.sig$less)))#rownames(gage.res.sig$greater)
nsig=length(sig.gs)
if(nsig>0) {
    write.table(rbind(gage.res.sig$greater,gage.res.sig$less), 
                file = "gage.res.sig.gene.txt", sep = "\t", quote = FALSE)
        gpath.ids=sig.gs
} else {
    print("No gene set selected in 1d-test, view the top 3 instead!")
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
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
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
    p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], nbg-cnts.bg[2,],nsel, lower.tail=F)
    q.val=p.adjust(p.val,  method ="BH")
    stats=cbind(t(cnts.sel[1:2,]),nsel, cnts.bg[2,], nbg, p.val, q.val)
    colnames(stats)=c("set.size", "hits","selected", "hits.bg", "background", "p.val", "q.val")
    stats=stats[order(p.val),]
    sel.idx=stats[,"hits"]>=ncut & stats[,"q.val"]<=qcut

print(1)

### significant.genesets
if(nrow(stats)>0)  write.table(stats, file = "discrete.res.gene.txt", sep = "\t", quote=F)

nsig=sum(sel.idx)
if(nsig>0) {
    write.table(stats[sel.idx,], file = "discrete.sig.gene.txt", sep = "\t", quote=F)
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
        gsets=kegg.cpd.set
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
    write.table(gene.idmap, file = "compound.idmap.txt", sep = "\t", row.names=F, quote=F)
    cpd.d=exprs=pathview::mol.sum(exprs, gene.idmap)
} else cpd.d=exprs=cbind(cpd.d)

    
gage.res.cpd=gage(exprs=exprs, gsets=gsets, ref = NULL, samp = NULL,
    set.size = c(10, Inf),  same.dir = TRUE) #same.dir = FALSE)
write.table(rbind(gage.res.cpd$greater, gage.res.cpd$less),
            file = "gage.res.cpd.txt", sep = "\t", quote = FALSE)

print(1)

### significant.genesets
gage.res.cpd.sig<-sigGeneSet(gage.res.cpd, outname="gage.res.cpd", cutoff=qcut)
sig.gs=unique(c(rownames(gage.res.cpd.sig$greater), rownames(gage.res.cpd.sig$less)))#rownames(gage.res.cpd.sig$greater)
nsig=length(sig.gs)
if(nsig>0) {
    write.table(rbind(gage.res.cpd.sig$greater, gage.res.cpd.sig$less),
                file = "gage.res.sig.cpd.txt", sep = "\t", quote = FALSE)
        cpath.ids=sig.gs
} else {
    print("No gene set selected in 1d-test, view the top 3 instead!")
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
        gsets=kegg.cpd.set
    } else stop("Can't find KEGG compound set data!")


if(map.data){
    require(pathview)
    gid.in=cid.type0
    gid.out=cid.type
    mol.ids=mol.sel
    gene.idmap=cpdidmap(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out))#?kegg 2 name update
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "compound.idmap.txt", sep = "\t", row.names=F, quote=F)
    cpd.d=mol.sel=gene.idmap[,2]
} else cpd.d=mol.sel

print(0)
rows = length(gsets)
                                        #create a matrix with number of rows as number of rows in gests 
m <- matrix(c(1:rows),nrow=rows)
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
    p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], nbg-cnts.bg[2,],nsel, lower.tail=F)
    q.val=p.adjust(p.val,  method ="BH")
    stats.cpd=cbind(t(cnts.sel[1:2,]),nsel, cnts.bg[2,], nbg, p.val, q.val)
    colnames(stats.cpd)=c("set.size", "hits","selected", "hits.bg", "background", "p.val", "q.val")
    stats.cpd=stats.cpd[order(p.val),]
    sel.idx=stats.cpd[,"hits"]>=ncut & stats.cpd[,"q.val"]<=qcut

### significant.genesets
if(nrow(stats.cpd)>0)  write.table(stats.cpd, file = "discrete.res.cpd.txt", sep = "\t", quote=F)

nsig=sum(sel.idx)
if(nsig>0) {
    write.table(stats.cpd[sel.idx,], file = "discrete.sig.cpd.txt", sep = "\t", quote=F)
    cpath.ids=rownames(stats.cpd)[sel.idx]
} else {
    print("No gene set selected in 1d-test, view the top 3 instead!")
    cpath.ids=rownames(stats.cpd)[1:3]
    }
}


path.ids=c(gpath.ids,cpath.ids)
path.ids=gsub(".+([0-9]{5}).+", "\\1",path.ids)
globs=grep("^01[1-2]", path.ids)
if(length(globs)>0) path.ids=path.ids[- globs]
path.ids=unique(path.ids)
if(length(path.ids)>nmax){
    lgp=length(gpath.ids)
    lcp=length(cpath.ids)
    mn=min(lgp,lcp,round(nmax/2))
    path.ids=c(gpath.ids[1:mn],cpath.ids[1:mn])
    if(lgp>mn) path.ids=c(path.ids,gpath.ids[(mn+1):lgp])
    if(lcp>mn) path.ids=c(path.ids,cpath.ids[(mn+1):lcp])
    path.ids=gsub(".+([0-9]{5}).+", "\\1",path.ids)
    globs=grep("^01[1-2]", path.ids)
    if(length(globs)>0) path.ids=path.ids[- globs]
    path.ids=unique(path.ids)
}
path.ids=path.ids[1:min(nmax, length(path.ids))]

} else{
    path.ids=args2$pathway
    globs=grep("^01[1-2]", path.ids)
    if(length(globs)>0) path.ids=path.ids[- globs]
    path.ids=unique(path.ids)
    path.ids=path.ids[1:min(nmax, length(path.ids))]
}

source(paste(pvwdir,"scripts/do.pathview.R",sep=""))
save.image("workenv.RData")
