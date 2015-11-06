
###argument processing
args <- commandArgs(TRUE)

arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]
#publicPathlines = readLines(paste(getwd(),"/data/publicPath.txt",sep=""))
publicPathlines = paste(getwd(),"/public",sep="")
logic.idx=c("rankTest", "useFold", "test.2d", "do.pathview","kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc")
num.idx=c(  "setSizeMin", "setSizeMax", "cutoff","offset", "gbins", "cbins")

args2=strsplit(args1, ",")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
args2$glmt = as.numeric(strsplit(args2$glmt,split=";")[[1]])
args2$clmt = as.numeric(strsplit(args2$clmt,split=";")[[1]])
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

setwd(args2$destDir)
save.image("workenv.RData")

###molecular data

if(args2$geneextension == "txt"){
    a=read.delim(args2$filename, sep="\t", row.names=NULL)
} else if(args2$geneextension == "csv"){
    a=read.delim(args2$filename, sep=",", row.names=NULL)
} else stop(paste(args2$geneextension, ": unsupported gene data file type!"), sep="")

if(ncol(a)>1){
    exprs=as.matrix(a[,-1])
    mol.ids=as.character(a[,1])
    rownames(exprs)=make.unique(mol.ids)
    if(!is.numeric(exprs[,1])) stop("Data matrix has to be numeric!")
} else if(ncol(a)==1) {
    a=as.matrix(a)
    exprs=a[,1]
    if(is.null(names(exprs))) mol.ids=exprs=as.character(exprs)
} else stop("Empty gene data file!")

require(gage)
library(gage)

###gene set data
species0=species=args2$species
gs.type=args2$geneSetCategory
gid.type=tolower(args2$geneIdType)
map.data=F
data(bods, package="gage")

#gsets.dir="/var/www/PathwayWeb/public/genesets/"

gsets.dir=paste(publicPathlines,"/genesets/",sep="")
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
        sub.idx=unique(unlist(kset.data[args2$geneSet]))
        gsets=kset.data$kg.sets[sub.idx]
    } else {
        kset.data=kegg.gsets(species=species, id.type =gid.type)
        save(kset.data, file=gsfn)
        sub.idx=unique(unlist(kset.data[args2$geneSet]))
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
        sub.idx=unique(unlist(goset.data$go.subs[args2$geneSet]))
        gsets=goset.data$go.sets[sub.idx]
    }else {
        goset.data=go.gsets(species=species)
        save(goset.data, file=gsfn)
        sub.idx=unique(unlist(goset.data$go.subs[args2$geneSet]))
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

    source(paste(publicPathlines,"/scripts/annot.map.R",sep=""))
    pkg.name = bods[idx, "package"]
    gid.in=gid.type0
    gid.out=gid.type
    if(gid.in=="entrez" | gid.in=="eg") gid.in="ENTREZID"
    if(gid.out=="entrez" | gid.out=="eg") gid.out="ENTREZID"
    gene.idmap=annot.map(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out), annot.db=pkg.name, na.rm=F)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
    exprs0=exprs
    exprs=pathview::mol.sum(exprs, gene.idmap)
}

print(0)
### gage 1-d #implement weights later
gage.res=gage(exprs=exprs, gsets=gsets, ref = args2$reference, samp = args2$sample,
    set.size = args2$set.size, same.dir = TRUE, compare = args2$compare,
    rank.test = args2$rankTest, use.fold = args2$useFold, saaTest = eval(as.name(args2$test)))
write.table(rbind(gage.res$greater, gage.res$less),
            file = "gage.res.txt", sep = "\t")

print(1)

### significant.genesets
gage.res.sig<-sigGeneSet(gage.res, outname="gage.res", cutoff=args2$cutoff)
sig.gs=unique(c(rownames(gage.res.sig$greater), rownames(gage.res.sig$less)))
nsig=length(sig.gs)
if(nsig>0) {
    write.table(rbind(gage.res.sig$greater, gage.res.sig$less),
                file = "gage.res.sig.txt", sep = "\t")
} else print("No gene set selected in 1d-test!")

print(2)

### gage 2-d
if(args2$test.2d & gs.type!="go"){
    gage.res.2d=gage(exprs=exprs, gsets=gsets, ref = args2$reference, samp = args2$sample,
        set.size = args2$set.size, same.dir = FALSE, compare = args2$compare,
        rank.test = args2$rankTest, use.fold = args2$useFold, saaTest = eval(as.name(args2$test)))
    write.table(gage.res.2d$greater, file = "gage.res.2d.txt",
                sep = "\t")
    gage.res.2d.sig<-sigGeneSet(gage.res.2d, outname="gage.res", cutoff=args2$cutoff)
    sig.gs.2d=rownames(gage.res.2d.sig$greater)
    nsig.2d=length(sig.gs.2d)
    if(nsig.2d>0) {
        write.table(gage.res.2d.sig$greater, file = "gage.res.2d.sig.txt",
                    sep = "\t")
    } else print("No gene set selected in 2d-test!")
} else sig.gs.2d=NULL
sig.gs.all=unique(c(sig.gs,sig.gs.2d))
nsig.all=length(sig.gs.all)

print(3)


### output
if(nsig.all>0){

### geneData
    if(gs.type!="user") {outnames =sapply(strsplit(sig.gs.all, " "), "[", 1)
                     }else {outnames=sig.gs.all}
    outnames = gsub(" |:|/", "_", outnames)
    source(paste(publicPathlines,"/geneData.R",sep=""))
    environment(geneData2)=environment(geneData)
    for (i in (1:nsig.all)[1:3]) {
        geneData2(genes = gsets[[sig.gs.all[i]]], exprs = exprs, ref = args2$reference,
                 samp = args2$sample, outname = outnames[i], txt = T, heatmap = T,
                 Colv = F, Rowv = F, dendrogram = "none", limit = 3, scatterplot = T)
    }

    print(4)


### pathview
    if(args2$do.pathview & gs.type=="kegg"){
        kegg.dir=paste(publicPathlines,"/Kegg",sep="") #specify your own
        require(pathview)
        if(!is.null(args2$reference) & !is.null(args2$sample)) {
        if(args2$compare=="paired") exprs.d=exprs[,args2$sample]-exprs[,args2$reference]
        else exprs.d=exprs[,args2$reference]-rowMeans(exprs[,args2$reference])
        } else exprs.d=exprs
        if(args2$data.type=="gene")
            pv.out.list <- sapply(outnames[1:3], function(pid) pathview(gene.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir, gene.idtype=gid.type,kegg.native = args2$kegg, sign.pos =args2$pos,same.layer = args2$layer,keys.align = args2$align,split.group = args2$split,expand.node = args2$expand,multi.state=args2$multistate, match.data = args2$matchd ,node.sum=args2$nsum,key.pos = args2$kpos,cpd.lab.offset= args2$offset,limit = list(gene = args2$glmt, cpd = args2$clmt), bins = list(gene = args2$gbins, cpd= args2$cbins),low = list(gene = args2$glow, cpd = args2$clow),mid = list(gene = args2$gmid, cpd = args2$cmid), high = list(gene = args2$ghigh, cpd =args2$chigh),discrete = list(gene = args2$gdisc, cpd = args2$cdisc)))
        else pv.out.list <- sapply(outnames[1:3], function(pid) pathview(cpd.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir, gene.idtype=gid.type,kegg.native = args2$kegg, sign.pos =args2$pos,same.layer = args2$layer,keys.align = args2$align,split.group = args2$split,expand.node = args2$expand,multi.state=args2$multistate, match.data = args2$matchd ,node.sum=args2$nsum,key.pos = args2$kpos,cpd.lab.offset= args2$offset,limit = list(gene = args2$glmt, cpd = args2$clmt), bins = list(gene = args2$gbins, cpd= args2$cbins),low = list(gene = args2$glow, cpd = args2$clow),mid = list(gene = args2$gmid, cpd = args2$cmid), high = list(gene = args2$ghigh, cpd =args2$chigh),discrete = list(gene = args2$gdisc, cpd = args2$cdisc)))
    }
} else print("No gene set selected by GAGE, you may relax the cutoff q-value!")

print(5)
save.image("workenv.RData")
