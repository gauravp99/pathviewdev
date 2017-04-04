#!/usr/local/bin/Rscript
#!/usr/bin/sh

#args <- commandArgs(TRUE)
#pvwdir=args[1]
pvwdir="/var/www/PathwayWeb/public/"

url="http://rest.kegg.jp/list/organism"
lines <- readLines(url)
split <- strsplit(lines, "\t")
u <- unlist(split)
m <- matrix(u, ncol = 4, byrow = TRUE)
colnames(m) <- c("T.number", "organism", "species", "phylogeny")

ln1=grep("Escherichia coli K-12 MG1655", m[,3])
ksp=m[1:(ln1-1),3]
ksp.l=strsplit(ksp, " +[(]|[)]$", perl=T)
kl=sapply(ksp.l, length)
ksp2=cbind(m[,3], "")
ksp2[(1:(ln1-1))[kl==2],]=t(sapply(ksp.l[kl==2], "["))
ji=grep("Japanese rice", ksp2[,1])
ksp2[ji,]=rep(c("Oryza sativa japonica", "Japanese rice"),each=2)
korg.new=cbind(m[,2],ksp2)
colnames(korg.new)=c("kegg code", "scientific name", "common name")

data(korg, package ="pathview")
korg.rfile=paste0(pvwdir, "data/korg.rda")
korg.tfile=paste0(pvwdir, "data/korg.txt")
#load(korg.rfile)
na.idx=is.na(korg[,"ncbi.geneid"])
sel=korg.new[,1] %in% korg[na.idx,1] |!korg.new[,1] %in% korg[,1]

#source("/Users/luo/project/bn/Rfunctions/check.entrez.gnodes.R")
check.entrez.gnodes.2=function(species="eco", pathway.id="00230", head.n=5){
    require(KEGGREST)
  species=unique(species)
  is.korg=species %in% korg[,1]
  if(!all(is.korg)){
    warn.msg=paste(species[!is.korg], collapse=", ")
    warn.msg=sprintf("unrecognized species: %s!", warn.msg)
    message(warn.msg)
  }
  if(sum(is.korg)==0){
    warning("No valid species name!")
    return(logical(0))
  }
  pathway.name = paste(species, pathway.id, sep = "")
  kegg.ids=try(keggLink("genes", pathway.name))
  if(length(kegg.ids)<1 | class(kegg.ids)=="try-error"){
    warning("improper pathway name, no gene entries!")
    return(logical(0))
  } else{
    kegg.ids.1=unlist(tapply(kegg.ids, names(kegg.ids), head, n=head.n))
    ncbi.ids=NULL
    while(length(kegg.ids.1)>100){
      ncbi.ids=c(ncbi.ids, keggConv("ncbi-geneid", head(kegg.ids.1,100)))
      kegg.ids.1=kegg.ids.1[-c(1:100)]
    }
    if(length(kegg.ids.1)>0) ncbi.ids=c(ncbi.ids, keggConv("ncbi-geneid", kegg.ids.1))
    if(length(ncbi.ids)>0){
        ncbi.ids=sapply(strsplit(ncbi.ids,":"), "[", 2)
        kegg.ids.2=sapply(strsplit(names(ncbi.ids),":"), "[", 2)
        species.ids=sapply(strsplit(names(ncbi.ids),":"), "[", 1)#substr(names(ncbi.ids),1,3)
        entrez.gnodes=tapply(kegg.ids.2==ncbi.ids, species.ids, all)
        kn.ids=cbind(kegg.id=kegg.ids.2,ncbi.id=ncbi.ids)
        kn.ids=tapply(1:nrow(kn.ids), species.ids, function(n) kn.ids[n[1],])
        kn.ids=sapply(kn.ids, function(x) x)
        return(cbind(entrez.gnodes=as.numeric(entrez.gnodes), t(kn.ids)))
    } else {
        warning("no ncbi-geneid returned for the gene entries!")
        return(logical(0))
    }
  }
}

system.time({
org.new=korg.new[sel,1]
orgs.egn.new<-NULL
while(length(org.new)>100){
print(length(org.new))
orgs.egn.100<- check.entrez.gnodes.2(head(org.new,100))
orgs.egn.new=rbind(orgs.egn.new, orgs.egn.100)
org.new=org.new[-c(1:100)]
}
orgs.egn.100<- check.entrez.gnodes.2(head(org.new,100))
orgs.egn.new=rbind(orgs.egn.new, orgs.egn.100)
}
)
idx=match(rownames(orgs.egn.new),korg.new[,1])
korg2=cbind(korg.new[idx,], orgs.egn.new)
sel1=korg[,1] %in% korg2[,1]
korg2=rbind(korg[!sel1,], korg2)
rownames(korg2)=NULL
ji=grep("Japanese rice", korg2[,2])
korg2[ji,2:3]=c("Oryza sativa japonica", "Japanese rice")

sel=korg.new[,1] %in% korg2[,1]
ord=match(korg2[,1], korg.new[sel,1])
korg2=korg2[ord,]
korg=korg2
save(korg, file=korg.rfile)
write.table(korg, file=korg.tfile, sep="\t", quote=F)
