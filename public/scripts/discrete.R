
###argument processing
args <- commandArgs(TRUE)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]
publicPathlines = readLines(paste(getwd(),"/data/publicPath.txt",sep=""))
logic.idx=c("test.enrich", "do.pathview")
num.idx=c("setSizeMin", "setSizeMax", "ncut", "pcut")

args2=strsplit(args1, ",")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)
if( args2$setSizeMax == "INF" ){
    args2$setSizeMax = Inf
    args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
}else {
    args2$set.size = c(args2$setSizeMin,args2$setSizeMax)
}


###molecular data
setwd(args2$destDir)
if( args2$sampleList == "inputbox" )
{
    mol.sel = scan(file ="sampleList.txt", what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
    mol.sel = mol.sel[mol.sel>""]
} else
{
    mol.sel = scan(file =args2$sampleListFile, what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
    mol.sel = mol.sel[mol.sel>""]
}
if(args2$test.enrich){
    if( args2$backgroundList == "inputbox" )
        {
            mol.bg = scan(file ="backgroundList.txt", what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
            mol.bg = mol.bg[mol.bg>""]
        } else
            {
                mol.bg = scan(file =args2$baclgroundListFile, what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
                mol.bg = mol.bg[mol.bg>""]
            }
    if(any(!mol.sel %in% mol.bg)) stop("Selected IDs not from background!")
} else mol.bg=NULL

save.image("workenv.RData")

###gene set data
require(gage)

species0=species=args2$species
gs.type=args2$geneSetCategory
gid.type=tolower(args2$geneIdType)
map.data=F
data(bods, package="gage")
#gsets.dir="/var/www/Pathway/public/genesets/"
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

                                        #gene ID only, cpd ID latter
nsel=length(mol.sel)
nbg=length(mol.bg)
if(map.data){
    require(pathview)
                                        #    source("/var/www/Pathway/public/scripts/annot.map.R")
    pkg.name = bods[idx, "package"]
    gid.in=gid.type0
    gid.out=gid.type
    if(gid.in=="entrez" | gid.in=="eg") gid.in="ENTREZID"
    if(gid.out=="entrez" | gid.out=="eg") gid.out="ENTREZID"
    gene.idmap=geneannot.map(in.ids=c(mol.sel, mol.bg), in.type=toupper(gid.in), out.type=toupper(gid.out), annot.db=pkg.name)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
    mol.sel=gene.idmap[1:nsel,2]
    if(!mol.bg) mol.bg=gene.idmap[-c(1:nsel),2]
}

print(0)
rows = length(gsets)
                                        #create a matrix with number of rows as number of rows in gests 
m <- matrix(c(1:rows),nrow=rows)

cnts.sel=sapply(gsets, function(gs){
                    ii=gs %in% mol.sel
                    return(c(length(ii), sum(ii)))
                })

if(args2$test.enrich){
    cnts.bg=sapply(gsets, function(gs){
                       ii=gs %in% mol.bg
                       return(c(length(ii), sum(ii)))
                   })
    p.val=phyper(cnts.sel[2,]-1, cnts.bg[2,], cnts.bg[1,]-cnts.bg[2,],cnts.sel[1,], lower.tail=F)
    q.val=p.adjust(p.val,  method ="BH")
    stats=cbind(t(cnts.sel)[,2:1], t(cnts.bg)[,2:1], p.val, q.val)
    colnames(stats)=c("hits", "selected", "hits.bg", "background", "p.val", "q.val")
    stats=stats[order(p.val),]
    sel.idx=stats[,"hits"]>=args2$ncut & stats[,"p.val"]<=args2$pcut
} else{
    stats=cbind(t(cnts.sel)[,2:1])
    colnames(stats)=c("hits", "selected")
    stats=stats[order(cnts.sel[2,]),]
    sel.idx=stats[,"hits"]>=args2$ncut
}

print(1)

### significant.genesets
if(nrow(stats)>0)  write.table(stats, file = "discrete.res.txt", sep = "\t")

nsig=sum(sel.idx)
if(nsig>0) {
write.table(stats[sel.idx,], file = "discrete.sig.txt", sep = "\t")

### pathview
if(args2$do.pathview & gs.type=="kegg"){
    kegg.dir=paste(publicPathlines,"/Kegg",sep="") #specify your own
    require(pathview)
    path.ids=substr(rownames(stats),1,8)
    if(args2$data.type=="gene"){
        pv.out.list <- sapply(path.ids[1:3], function(pid) pathview(gene.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir, gene.idtype=gid.type))
       } else pv.out.list <- sapply(path.ids[1:3], function(pid) pathview(cpd.data = exprs.d, pathway.id = pid, species = species, kegg.dir=kegg.dir, gene.idtype=gid.type))
}
} else print("No gene set selected, you may relax the cutoff q-value!")

print(2)

save.image("workenv.RData")
