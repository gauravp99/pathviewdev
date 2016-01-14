
###argument processing
args <- commandArgs(TRUE)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]
#pvwdir = paste0(getwd(), "/public/")
pvwdir = paste0(getwd(), "/")

args2=strsplit(args1, ",")
logic.idx=c("rankTest", "useFold", "test.2d", "dopathview", "normalized", "count.data", "do.log")
num.idx=c("setSizeMin", "setSizeMax", "cutoff")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
if( args2$setSizeMax == "INF" )
    {
        args2$setSizeMax = Inf
        args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
    }else {
        args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
    }

if(identical(args2$reference, "NULL"))
{
    args2$reference = NULL
}else{
    args2$reference = as.numeric(args2$reference)
}
if(identical(args2$sample, "NULL"))
{
    args2$sample = NULL
}else{
    print("in sample else");
    args2$sample = as.numeric(args2$sample)
}

#extra parameters for pathview
if(!is.null(args2$kegg)){
logic.idx=c("kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc")
num.idx=c("offset", "glmt", "gbins", "clmt", "cbins", "pathidx")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
#commented parameters
args2$split=F
args2$expand=F
}

setwd(args2$destDir)
save.image("workenv.RData")

###molecular data

if(args2$fn.extension == "txt"){
    a=read.delim(args2$filename, sep="\t", row.names=NULL)
} else if(args2$fn.extension == "csv"){
    a=read.delim(args2$filename, sep=",", row.names=NULL)
} else stop(paste(args2$fn.extension, ": unsupported gene data file type!"), sep="")

d0=0
if(ncol(a)>1){
    exprs=as.matrix(a[,-1])
    mol.ids=as.character(a[,1])
    rownames(exprs)=make.unique(mol.ids)
    if(!is.numeric(exprs[,1])) stop("Data matrix has to be numeric!")
    if(!args2$normalized & ncol(exprs)>1 & !is.null(args2$reference)){
        if(args2$count.data){
            sel.rn=rowSums(exprs) != 0
            exprs=exprs[sel.rn,]
            libsizes=colSums(exprs)
            size.factor=libsizes/exp(mean(log(libsizes)))
            exprs=t(t(exprs)/size.factor)
            exprs=log2(exprs+1)
        } else{
            size.factor=apply(exprs,2,median)
            exprs=t(t(exprs)/size.factor)
            i0=exprs>0
            if(!any(i0)) d0=min(exprs[i0])/2
            exprs=log2(exprs+d0)
        }
    } else if(args2$do.log & quantile(exprs, 0.05)>0){
        i0=exprs>0
        if(!any(i0)) d0=min(exprs[i0])/2
        exprs=log2(exprs+d0)
    }
} else if(ncol(a)==1) {
    a=as.matrix(a)
    exprs=a[,1]
    if(is.null(names(exprs))) mol.ids=exprs=as.character(exprs)
    else if(args2$do.log & quantile(exprs, 0.05)>0){
        i0=exprs>0
        if(!any(i0)) d0=min(exprs[i0])/2
        exprs=log2(exprs+d0)
    }
} else stop("Empty gene data file!")

require(gage)

###gene set data
species0=species=args2$species
gs.type=args2$mset.category
gid.type=tolower(args2$mid.type)
map.data=F
data(bods, package="gage")
bods[,"id.type"]=gsub("eg", "entrez", bods[,"id.type"])
#gsets.dir="/var/www/PathwayWeb/public/genesets/"
gsets.dir=paste(pvwdir,'genesets/',sep="")
if(args2$data.type=="gene"){
    if(gs.type=="kegg"){
    if(!gid.type %in% c("entrez", "kegg")) {
        gid.type0=gid.type
        gid.type="entrez"
        map.data=T
        idx=which(bods[,"kegg code"] == species)
        ##        if(length(idx)!=1) stop("bad species value")
    }
    gsets.dir=paste(gsets.dir, "kegg/", sep="")
    gsfn=paste(gsets.dir, species, ".", gid.type, ".kset.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        sub.idx=unique(unlist(kset.data[args2$mset]))
        gsets=kset.data$kg.sets[sub.idx]
    } else {
        kset.data=kegg.gsets(species=species, id.type =gid.type)
        save(kset.data, file=gsfn)
        sub.idx=unique(unlist(kset.data[args2$mset]))
        gsets=kset.data$kg.sets[sub.idx]
    }
} else if(gs.type=="go"){
    idx=which(bods == species) %% nrow(bods)
    species=bods[idx, "species"]
    if(gid.type != bods[idx,"id.type"]) {
        gid.type0=gid.type
        gid.type= bods[idx,"id.type"]
        map.data=T
    }
    gsets.dir=paste(gsets.dir, "go/", sep="")
    gsfn=paste(gsets.dir, species, ".goset.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        sub.idx=unique(unlist(goset.data$go.subs[args2$mset]))
        gsets=goset.data$go.sets[sub.idx]
    }else {
        goset.data=go.gsets(species=species)
        save(goset.data, file=gsfn)
        sub.idx=unique(unlist(goset.data$go.subs[args2$mset]))
        gsets=goset.data$go.sets[sub.idx]
    }
} else {
    if(args2$gsetextension == "gmt"){
        gsets=readList(args2$gsfn)
        gsets=lapply(gsets, function(x) x[x>""])
    } else {
        gsets=read.delim(args2$gsfn, sep="\t", row.names=NULL)
        gsets=split(as.character(gsets[,1]), gsets[,2])
    }
    ##  save(gsets, file=paste(args2$user.dir, basename(gsfn))
}


if(map.data){
    require(pathview)
#        source(paste(pvwdir,"scripts/annot.map.R",sep=""))
    pkg.name = bods[idx, "package"]
    gid.in=gid.type0
    gid.out=gid.type
    if(gid.in=="entrez" | gid.in=="eg") gid.in="ENTREZID"
    if(gid.out=="entrez" | gid.out=="eg") gid.out="ENTREZID"
    gene.idmap=geneannot.map(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out), pkg.name=pkg.name, na.rm=F)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
    exprs0=exprs
    exprs=pathview::mol.sum(exprs, gene.idmap)
}
}else if(args2$data.type=="compound"){
    data(rn.list, package="pathview")
    cpd.type=names(rn.list)=tolower(names(rn.list))
    cpd.type=c(cpd.type,"compound name")
    cpd.type2=c("SMPDB ID", cpd.type[c(3,5,8:11)])
    cpd.type2=tolower(cpd.type2)
    kg.idx=grep("kegg", cpd.type)
    gsets.dir=paste(gsets.dir, "cpd/", sep="")
    species="ko"
    
if(gs.type=="kegg"){
    if(!gid.type %in% cpd.type) stop("Incorrect type!")
    if(!gid.type %in% cpd.type[kg.idx]) {
        gid.type0=gid.type
        gid.type="kegg"#?
        map.data=T
    }

    gsfn=paste(gsets.dir,  "kegg.cpd.set.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        gsets=kegg.cpd.set
    } else stop("Can't find KEGG compound set data!")
}else if(gs.type=="smpdb"){
    if(!gid.type %in% c(cpd.type, cpd.type2)) stop("Incorrect type!")
    if(!gid.type %in% cpd.type2){
        gid.type0=gid.type
        gid.type="kegg"#?
        map.data=T
    }

    gsfn=paste(gsets.dir,  "smpdb.set.RData", sep="")
    fnames=list.files(gsets.dir, full.names=F)
    if(basename(gsfn) %in% fnames){
        load(gsfn)
        if(gid.type %in% cpd.type2) gsets=eval(as.name(paste("smpdb.set",strsplit(gid.type," ")[[1]][1], sep=".")))
        else gsets=smpdb.set.kegg
        sub.idx=unique(unlist(smpdb.sub[args2$mset]))
        gsets=gsets[sub.idx]

    } else stop("Can't find SMPDB compound set data!")
} else {
    if(args2$gsetextension == "gmt"){
        gsets=readList(args2$gsfn)
        gsets=lapply(gsets, function(x) x[x>""])
    } else {
        gsets=read.delim(args2$gsfn, sep="\t", row.names=NULL)
        gsets=split(as.character(gsets[,1]), gsets[,2])
    }
    ##  save(gsets, file=paste(args2$user.dir, basename(gsfn))
}


if(map.data){
    require(pathview)
    gid.in=gid.type0
    gid.out=gid.type
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
    exprs0=exprs
    exprs=pathview::mol.sum(exprs, gene.idmap)
}
} else{
    if(args2$gsetextension == "gmt"){
        gsets=readList(args2$gsfn)
        gsets=lapply(gsets, function(x) x[x>""])
    } else {
        gsets=read.delim(args2$gsfn, sep="\t", row.names=NULL)
        gsets=split(as.character(gsets[,1]), gsets[,2])
    }
    ##  save(gsets, file=paste(args2$user.dir, basename(gsfn))
}

if((args2$do.log & quantile(exprs, 0.05)>0)|
   args2$count.data |
   (! args2$normalized & ncol(exprs)>1 & !is.null(args2$reference)) |
   map.data) write.table(exprs, file = "processed.data.txt", sep = "\t", quote = FALSE)

    print(0)
### gage 1-d #implement weights later
gage.res=gage(exprs=exprs, gsets=gsets, ref = args2$reference, samp = args2$sample, 
    set.size = args2$set.size, same.dir = TRUE, compare = args2$compare,
    rank.test = args2$rankTest, use.fold = args2$useFold, saaTest = eval(as.name(args2$test)))
write.table(rbind(gage.res$greater, gage.res$less), 
            file = "gage.res.txt", sep = "\t", quote = FALSE)

print(1)

### significant.genesets
gage.res.sig<-sigGeneSet(gage.res, outname="gage.res", cutoff=args2$cutoff)
sig.gs=unique(c(rownames(gage.res.sig$greater), rownames(gage.res.sig$less)))
nsig=length(sig.gs)
if(nsig>0) {
    write.table(rbind(gage.res.sig$greater, gage.res.sig$less), 
                file = "gage.res.sig.txt", sep = "\t", quote = FALSE)
} else print("No gene set selected in 1d-test!")

print(2)

### gage 2-d
if(args2$test.2d & gs.type!="go"){
    gage.res.2d=gage(exprs=exprs, gsets=gsets, ref = args2$reference, samp = args2$sample, 
        set.size = args2$set.size, same.dir = FALSE, compare = args2$compare,
        rank.test = args2$rankTest, use.fold = args2$useFold, saaTest = eval(as.name(args2$test)))
    write.table(gage.res.2d$greater, file = "gage.res.2d.txt", 
                sep = "\t", quote = FALSE)
    gage.res.2d.sig<-sigGeneSet(gage.res.2d, outname="gage.res", cutoff=args2$cutoff)
    sig.gs.2d=rownames(gage.res.2d.sig$greater)
    nsig.2d=length(sig.gs.2d)
    if(nsig.2d>0) {
        write.table(gage.res.2d.sig$greater, file = "gage.res.2d.sig.txt", 
                    sep = "\t", quote = FALSE)
    } else print("No gene set selected in 2d-test!")
} else sig.gs.2d=NULL
sig.gs.all=unique(c(sig.gs,sig.gs.2d))
nsig.all=length(sig.gs.all)

print(3)


### output
if(nsig.all>0){

### geneData
nm=4
if(nsig.all>nm) ii=1:nm else ii=1:nsig.all
    if(gs.type!="user") {outnames =sapply(strsplit(sig.gs.all, " "), "[", 1)
                     }else {outnames=sig.gs.all}
    outnames = gsub(" |:|/", "_", outnames)
    source(paste(pvwdir,"scripts/geneData.R",sep=""))
    environment(geneData2)=environment(geneData)
    for (i in (1:nsig.all)[ii]) {
        geneData2(genes = gsets[[sig.gs.all[i]]], exprs = exprs, ref = args2$reference,
                 samp = args2$sample, outname = outnames[i], txt = T, heatmap = T, 
                 Colv = F, Rowv = F, dendrogram = "none", limit = 3, scatterplot = T)
    }

    print(4)


### pathview
    outnames=gsub(paste0(species, '|map'), "", outnames)
path.ids=outnames[ii]
    if(args2$dopathview & gs.type=="kegg"){
        if(!is.null(args2$reference) & !is.null(args2$sample)) {
        if(args2$compare=="paired") exprs.d=exprs[,args2$sample]-exprs[,args2$reference]
        else exprs.d=exprs[,args2$reference]-rowMeans(exprs[,args2$reference])
        } else exprs.d=exprs

    source(paste(pvwdir,"scripts/do.pathview.R",sep=""))
    }
} else print("No gene set selected by GAGE, you may relax the cutoff q-value!")

print(5)
save.image("workenv.RData")
