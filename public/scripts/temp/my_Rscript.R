
                                        #to get the arguments readable in R code
args <- commandArgs(TRUE)

                                        # @arguments are split on comma as they are passed to Rscript with comma separated
library(pathview)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]

logic.idx=c("kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc")
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


#pvwdir = Sys.getenv("pvwdir")
pvwdir = paste0(getwd(), "/public/")

setwd(args2$targedir)


save.image("workenv.RData")



#path.ids = strsplit(args2$pathway,split=";")[[1]]
#args2$glmt = as.numeric(strsplit(args2$glmt,split=";")[[1]])
#args2$clmt = as.numeric(strsplit(args2$clmt,split=";")[[1]])
args2$cpdid=tolower(args2$cpdid)

#setwd(args2$targedir)
zz <- file("errorFile.Rout", open = "wt")
sink(zz,type = "message")
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

##kegg directory
kegg.dir=paste0(pvwdir,paste0("Kegg/", args2$species))
#if (!dir.exists(kegg.dir)) dir.create(kegg.dir)
system(paste("mkdir -p", kegg.dir))

save.image("workenv.RData")

path.ids=args2$pathway
if(args2$kegg) {
    source(paste(pvwdir,"scripts/kg.map.R",sep=""))
    kg.map(args2$species)
    kg.cmap()
    gm.fname=paste0(mmap.dir1, args2$species, ".gene.RData")
    cm.fname=paste0(mmap.dir1, "cpd", ".RData")
    load(gm.fname)
    load(cm.fname)
    pv.dt=c("gene", "cpd")[c(!is.null(gene.d), !is.null(cpd.d))]
}

pv.run=sapply(path.ids, function(pid){
pv.out <- try(pathview(gene.data = gene.d,gene.idtype = args2$geneid,cpd.data = cpd.d,cpd.idtype=args2$cpdid, pathway.id = pid,species = args2$species,out.suffix = args2$suffix,kegg.native = args2$kegg, sign.pos =args2$pos,same.layer = args2$layer,keys.align = args2$align,split.group = args2$split,expand.node = args2$expand,multi.state=args2$multistate, match.data = args2$matchd ,node.sum=args2$nsum,key.pos = args2$kpos,cpd.lab.offset= args2$offset,limit = list(gene = args2$glmt, cpd = args2$clmt), bins = list(gene = args2$gbins, cpd= args2$cbins),low = list(gene = args2$glow, cpd = args2$clow),mid = list(gene = args2$gmid, cpd = args2$cmid), high = list(gene = args2$ghigh, cpd =args2$chigh),discrete = list(gene = args2$gdisc, cpd = args2$cdisc),kegg.dir =kegg.dir))


if(class(pv.out) =="list" & args2$kegg){
pv.labels(pv.out=pv.out, pv.data.type=pv.dt, pid=pid)
}  else  print(paste("error using pawthway id",pid,sep=":"))
})


