
###argument processing
args <- commandArgs(TRUE)
arg.v = strsplit(args[1],split=";|:")[[1]]
idx=seq(1, length(arg.v), by=2)
args1=arg.v[idx+1]
names(args1)=arg.v[idx]
#pvwdir = Sys.getenv("pvwdir")
pvwdir = paste0(getwd(), "/public/")
pvwdir=gsub("public/public/", "public/", pvwdir)
logic.idx=c("test.enrich", "do.pathview")
num.idx=c("bins", "ncut", "qcut")

args2=strsplit(args1, ",")
args2[logic.idx]=lapply(args2[logic.idx],as.logical)
args2[num.idx]=lapply(args2[num.idx],as.numeric)

species0=species=args2$species
gs.type=args2$mset.category
gid.type=tolower(args2$mid.type)

###molecular data
setwd(args2$destDir)
if( args2$sampleList == "file" )
{
    mol.sel = scan(file =args2$sampleListFile, what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
    mol.sel = mol.sel[mol.sel>""]
} else {
    mol.sel = scan(file ="sampleList.txt", what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
    mol.sel = mol.sel[mol.sel>""]
}

if(args2$test.enrich ){
    if( args2$backgroundList == "file" )
        {
            mol.bg = scan(file =args2$backgroundListFile, what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
            mol.bg = mol.bg[mol.bg>""]
        } else if( args2$backgroundList == "inputbox" ) {
            mol.bg = scan(file ="backgroundList.txt", what=" ",sep=c(",", ";", ":", "\t", "\n"),strip.white=TRUE)
            mol.bg = mol.bg[mol.bg>""]
        } else if(gid.type!="custom"){
            require(pathview)
            mol.bg=pathview::sim.mol.data(id.type=gid.type, species=species, nmol=10^6, discrete=T)
        } else {
            print("No enrichment test! without Background custom ID list!")
            args2$test.enrich=FALSE
        }
    if(any(!mol.sel %in% mol.bg)) stop("Selected IDs not from background!")
} else mol.bg=NULL

save.image("workenv.RData")

###gene set data
require(gage)

map.data=F
data(bods, package="gage")
bods[,"id.type"]=gsub("eg", "entrez", bods[,"id.type"])
#gsets.dir="/var/www/Pathway/public/genesets/"
gsets.dir=paste(pvwdir,"genesets/",sep="")
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
save.image("workenv.RData")
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
    gene.idmap=geneannot.map(in.ids=c(mol.sel, mol.bg), in.type=toupper(gid.in), out.type=toupper(gid.out), pkg.name=pkg.name, na.rm=F)
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "gene.idmap.txt", sep = "\t", row.names=F, quote=F)
    mol.sel=gene.idmap[1:nsel,2]
    if(!is.null(mol.bg)) mol.bg=gene.idmap[-c(1:nsel),2]
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
    gene.idmap=cpdidmap(in.ids=mol.ids, in.type=toupper(gid.in), out.type=toupper(gid.out))#?kegg 2 name update
    didx=duplicated(gene.idmap[,1])
    gene.idmap=gene.idmap[!didx,]
    write.table(gene.idmap, file = "compound.idmap.txt", sep = "\t", row.names=F, quote=F)
    mol.sel=gene.idmap[1:nsel,2]
    if(!is.null(mol.bg)) mol.bg=gene.idmap[-c(1:nsel),2]
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

print(0)
rows = length(gsets)
                                        #create a matrix with number of rows as number of rows in gests 
m <- matrix(c(1:rows),nrow=rows)
gsets.all=unique(unlist(gsets))
mol.sel.0=mol.sel
mol.bg.0=mol.bg
mol.sel=mol.sel[mol.sel %in% gsets.all]
mol.bg=mol.bg[mol.bg %in% gsets.all]
nsel=length(mol.sel)
nbg=length(mol.bg)

cnts.sel=sapply(gsets, function(gs){
                    ii=gs %in% mol.sel
                    return(c(length(ii), sum(ii)))
                })

if(args2$test.enrich){
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
    sel.idx=stats[,"hits"]>=args2$ncut & stats[,"q.val"]<=args2$qcut
} else{
    ratios=cnts.sel[2,]/cnts.sel[1,]
    stats=cbind(t(cnts.sel[1:2,]),nsel, ratios)
    colnames(stats)=c("set.size", "hits","selected", "ratio")
    stats=stats[order(stats[,4],decreasing=T),]
    sel.idx=stats[,"hits"]>=args2$ncut
}

print(1)

### significant.genesets
if(nrow(stats)>0)  write.table(stats, file = "discrete.res.txt", sep = "\t", quote=F)

nsig=sum(sel.idx)
if(nsig>0) {
    write.table(stats[sel.idx,], file = "discrete.sig.txt", sep = "\t", quote=F)

### pathview
    if(args2$do.pathview & gs.type=="kegg"){
        if(nsig>3) ii=1:3 else ii=1:nsig
        path.ids=sapply(strsplit(rownames(stats)[sel.idx], " "), "[", 1)
        path.ids=gsub(paste0(species, '|map'), "", path.ids)
        path.ids=path.ids[ii]
        
        source(paste(pvwdir,"scripts/kg.map.R",sep=""))
        kg.map(args2$species)
        kg.cmap()
        gm.fname=paste0(mmap.dir1, args2$species, ".gene.RData")
        cm.fname=paste0(mmap.dir1, "cpd", ".RData")
        load(gm.fname)
        load(cm.fname)
        pv.dt="gene"
        if(args2$data.type=="compound") pv.dt="cpd"

        kegg.dir=paste0(pvwdir,"Kegg/", species)
        system(paste("mkdir -p", kegg.dir))
        require(pathview)

        pv.run=sapply(path.ids, function(pid){
                          if(args2$data.type=="gene"){
                              pv.out <- try(pathview(gene.data = mol.sel, pathway.id = pid, species = species, kegg.dir=kegg.dir, gene.idtype=gid.type, limit=args2$bins, bins=args2$bins, discrete =T))
                          } else if(args2$data.type=="compound"){
                              pv.out <- try( pathview(cpd.data = mol.sel, pathway.id = pid, species = species, kegg.dir=kegg.dir, cpd.idtype=gid.type, limit=1, bins=1, discrete =T))
                          }
                          if(class(pv.out) =="list"){
                              pv.labels(pv.out=pv.out, pv.data.type=pv.dt, pid=pid)
                          }  else  print(paste("error using pawthway id",pid,sep=":"))
                      })
    }
} else print("No gene set selected, you may relax the cutoff q-value!")

print(2)

save.image("workenv.RData")
