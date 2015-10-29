###argument processing
args <- commandArgs(TRUE)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]
publicPathlines = readLines("data/publicPath.txt")
logic.idx=c("do.pathview")
num.idx=c(  "setSizeMin", "setSizeMax", "cutoff")
args2=strsplit(args1, ",")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
setwd(args2$destDir)

if( args2$setSizeMax == "INF" )
    {
        args2$setSizeMax = Inf
        args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
    }else {
        args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
    }
if( args2$sampleList == "inputbox" )
{
top100GeneID = scan(file ="sampleList.txt",what=" ",sep=c(",",";",":"),strip.white=TRUE)
top100GeneID = strsplit(top100GeneID[[1]],";")[[1]]
}else
{
top100GeneID = scan(file =args2$sampleListFile,what=" ",sep=c(",",";",":"),strip.white=TRUE)
top100GeneID = strsplit(top100GeneID[[1]],";")[[1]]
}
if( args2$backgroundList == "inputbox" )
{
top200GeneID = scan(file ="backgroundList.txt",what=" ",sep=c(",",";",":"),strip.white=TRUE)
top200GeneID = strsplit(top200GeneID[[1]],";")[[1]]	
}else
{
top200GeneID = scan(file =args2$baclgroundListFile,what=" ",sep=c(",",";",":"),strip.white=TRUE)
top200GeneID = strsplit(top200GeneID[[1]],";")[[1]]
}


save.image("workenv.RData")
# #reading data from file into a dataframe 
# setwd("/var/www/Pathway/public/data")
# data = read.table("gse16873.csv", header = TRUE, row.names = 1,sep=",")
# #retrieve top100 and 200 geneid in data imported from file
# # top100GeneID = attr(sort(rowSums(data[c(1,3,5,7,9,11)]) - rowSums(data[c(1,3,5,7,9,11)+1]),decreasing=TRUE)[1:100],"names")
# # top200GeneID = attr(sort(rowSums(data[c(1,3,5,7,9,11)]) - rowSums(data[c(1,3,5,7,9,11)+1]),decreasing=TRUE)[1:200],"names")

# require(gage)
# library(gage)
# load("/var/www/Pathway/public/genesets/kegg/hsa.kegg.kset.RData")
# sub.idx=unique(unlist(kset.data["sigmet.idx"]))
# gsets=kset.data$kg.sets[sub.idx]
###gene set data
species0=species=args2$species
gs.type=args2$geneSetCategory
gid.type=tolower(args2$geneIdType)
map.data=F
data(bods, package="gage")
#gsets.dir="/var/www/PathwayWeb/public/genesets/"
gsets.dir = publicPathlines;
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


rows = length(gsets)
#create a matrix with number of rows as number of rows in gests 
m <- matrix(c(1:rows),nrow=rows)

if(args2$resultBasedOn == "ratio")
{
	rank = function(x) {
	r = length(intersect(top100GeneID,gsets[[x]]))/length(intersect(top200GeneID,gsets[[x]]))
	ifelse(is.nan(r),0,as.numeric(r))

}
}else{
rank = function(x) {
	r = length(intersect(top100GeneID,gsets[[x]]))
	ifelse(is.nan(r),0,as.numeric(r))
}	
}

#find the rank for all the pathway id in gsets
ranks = apply(m,1,rank)

pathways = attr(gsets,"names")
#list the pathway id's
pathways_list = pathways[order(apply(m,1,rank),decreasing = TRUE)]
ranks1 = ranks[order(apply(m,1,rank),decreasing = TRUE)]
pathways_list_Ranks = paste(pathways_list,ranks1,sep=",")
write.table(pathways_list_Ranks, file = "pathways_list.txt", sep = "\n", row.names=F, quote=F)
save.image("workenv.RData")
