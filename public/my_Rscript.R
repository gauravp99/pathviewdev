
                                        #to get the arguments readable in R code
args <- commandArgs(TRUE)

                                        # @arguments are split on comma as they are passed to Rscript with comma separated

arg.v = strsplit(args[1],split=",|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]

logic.idx=c("kegg", "layer", "split", "expand", "multistate", "matchd", "gdisc", "cdisc")
#num.idx=c("offset", "glmt", "gbins", "clmt", "cbins", "pathidx")
num.idx=c("offset", "gbins", "cbins", "pathidx")


args2=as.list(args1)
args2[logic.idx]=as.list(as.logical(args1[logic.idx]))
args2[num.idx]=as.list(as.numeric(args1[num.idx]))

save.image("workenv.RData")
path.ids = strsplit(args2$pathway,split=";")[[1]]
args2$glmt = as.numeric(strsplit(args2$glmt,split=";")[[1]])
args2$clmt = as.numeric(strsplit(args2$clmt,split=";")[[1]])
<<<<<<< HEAD
args2$cpdid=tolower(args2$cpdid)

setwd(args2$targedir)
zz <- file("errorFile.Rout", open = "wt")
sink(zz,type = "message")
=======

setwd(args2$targedir)
#zz <- file("errorFile1.Rout", open = "wt")
#sink(zz,type = "message")
>>>>>>> b2951e13a89321e5be233308c1b73900b1eee9c0
if(!is.null(args2$geneextension)){
    if(args2$geneextension == "txt"){
        a=read.delim(args2$filename, sep="\t")
    } else if(args2$geneextension == "csv"){
        a=read.delim(args2$filename, sep=",")
    } else stop(paste(args2$geneextension, ": unsupported gene data file type!"), sep="")

    if(ncol(a)>1){
        gene.d=as.matrix(a[,-1])
        rownames(gene.d)=make.unique(as.character(a[,1]))
    } else if(ncol(a)==1) gene.d=make.unique(as.character(a[,1]))
      else stop("Empty gene data file!")
} else gene.d=NULL

if(!is.null(args2$cpdextension)){
    if(args2$cpdextension == "txt"){
        a1=read.delim(args2$cfilename, sep="\t")
    } else if(args2$cpdextension == "csv"){
        a1=read.delim(args2$cfilename, sep=",")
    } else stop(paste(args2$cpdextension, ": unsupported compound data file type!"), sep="")

    if(ncol(a1)>1){
        cpd.d=as.matrix(a1[,-1])
        rownames(cpd.d)=make.unique(as.character(a1[,1]))
    } else if(ncol(a1)==1) cpd.d=make.unique(as.character(a1[,1]))
      else stop("Empty compound data file!")
} else cpd.d=NULL

kegg.dir=paste("/home/ybhavnasi/Desktop/Kegg/", args2$species, sep="")
if (!dir.exists(kegg.dir)) dir.create(kegg.dir)
#path.ids=args1[grep("^pathway", names(args1))]
save.image("workenv.Rdata")

library(pathview)
pv.run=sapply(path.ids, function(pid){
                 pv.out <- try(pathview(gene.data = gene.d,gene.idtype = args2$geneid,cpd.data = cpd.d,cpd.idtype=args2$cpdid, pathway.id = pid,species = args2$species,out.suffix = args2$suffix,kegg.native = args2$kegg, sign.pos =args2$pos,same.layer = args2$layer,keys.align = args2$align,split.group = args2$split,expand.node = args2$expand,multi.state=args2$multistate, match.data = args2$matchd ,node.sum=args2$nsum,key.pos = args2$kpos,cpd.lab.offset= args2$offset,limit = list(gene = args2$glmt, cpd = args2$clmt), bins = list(gene = args2$gbins, cpd= args2$cbins),low = list(gene = args2$glow, cpd = args2$clow),mid = list(gene = args2$gmid, cpd = args2$cmid), high = list(gene = args2$ghigh, cpd =args2$chigh),discrete = list(gene = args2$gdisc, cpd = args2$cdisc),kegg.dir =kegg.dir))


                 if(class(pv.out) != "try-error"){
                     if(!is.null(gene.d)) write.table(pv.out$plot.data.gene,file=paste(paste(paste("genedata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE)
                     if(!is.null(cpd.d)) write.table(pv.out$plot.data.cpd,file=paste(paste(paste("cpddata.",args2$species,sep=""),pid,sep=""),".txt",sep=""),quote = FALSE)
                 }  else  print(paste("error using pawthway id",pid,sep=":"))
             })
	

