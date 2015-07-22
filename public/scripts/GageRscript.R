
###argument processing
args <- commandArgs(TRUE)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]

logic.idx=c("rankTest", "useFold", "test.2d", "do.pathview")
num.idx=c(  "setSizeMin", "setSizeMax", "cutoff")

args2=strsplit(args1, ",")
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

setwd(args2$destDir)
save.image("workenv.RData")
require(gage)
library(gage)

###gene set data
species=args2$species
gs.type=args2$geneSetCategory 
gid.type=args2$geneIdType
gsets.dir="/var/www/Pathway/public/genesets/"
if(gs.type=="kegg"){
  gsets.dir=paste(gsets.dir, "kegg/", sep="")
  gsfn=paste(gsets.dir, species, ".", gid.type, ".kset.RData", sep="")
  fnames=list.files(gsets.dir, full.names=F)
  if(basename(gsfn) %in% fnames)
   {load(gsfn)
   sub.idx=unique(unlist(kset.data[args2$geneSet]))
       gsets=kset.data$kg.sets[sub.idx]
   }
  else {
    kset.data=kegg.gsets(species=species, id.type =gid.type)
    save(kset.data, file=gsfn)
    sub.idx=unique(unlist(kset.data[args2$geneSet]))
    gsets=kset.data$kg.sets[sub.idx]
  }
} else if(gs.type=="go"){
  gsets.dir=paste(gsets.dir, "go/", sep="")
  gsfn=paste(gsets.dir, species, ".goset.RData", sep="")
  fnames=list.files(gsets.dir, full.names=F)
  if(basename(gsfn) %in% fnames){
  load(gsfn)
  sub.idx=unique(unlist(goset.data$go.subs[args2$geneSet]))
      gsets=goset.data$go.sets[sub.idx]
  }
  else {
    goset.data=go.gsets(species=species)
    save(goset.data, file=gsfn)
    sub.idx=unique(unlist(goset.data$go.subs[args2$geneSet]))
    gsets=goset.data$go.sets[sub.idx]
  }
} else {
  gsets=readList(args2$gsfn)
#  save(gsets, file=paste(args2$user.dir, basename(gsfn))
}

###molecular data
    if(args2$geneextension == "txt"){
        a=read.delim(args2$filename, sep="\t", row.names=1)
    } else if(args2$geneextension == "csv"){
        a=read.delim(args2$filename, sep=",", row.names=1)
    } else stop(paste(args2$geneextension, ": unsupported gene data file type!"), sep="")

    exprs=as.matrix(a)
    if(!is.numeric(a[,1])) stop("Input data has to be numeric!")


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
    if(gs.type!="user") outnames =sapply(strsplit(sig.gs.all, " "), "[", 1)
    else outnames=sig.gs.all
    outnames = gsub(" |:|/", "_", outnames)
    for (i in (1:nsig.all)[1:3]) {
      geneData(genes = gsets[[sig.gs.all[i]]], exprs = exprs, ref = args2$reference,
               samp = args2$sample, outname = outnames[i], txt = T, heatmap = T, 
               Colv = F, Rowv = F, dendrogram = "none", limit = 3, scatterplot = T)
    }

print(4)


### pathview
    if(args2$do.pathview & gs.type=="kegg"){
      kegg.dir="/var/www/Pathway/public/Kegg"#specify your own
      require(pathview)
      if(args2$compare=="paired") exprs.d=exprs[,args2$sample]-exprs[,args2$reference]
      else exprs.d=exprs[,args2$reference]-rowMeans(exprs[,args2$reference])
      if(args2$data.type=="gene")
        pv.out.list <- sapply(outnames[1:3], function(pid) pathview(gene.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir))
      else pv.out.list <- sapply(outnames[1:3], function(pid) pathview(cpd.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir))
    }
  } else print("No gene set selected by GAGE, you may relax the cutoff q-value!")

print(5)
  save.image("workenv.RData")
