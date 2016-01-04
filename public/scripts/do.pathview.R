
kegg.dir=paste0(pvwdir,"Kegg/", species)
system(paste("mkdir -p", kegg.dir))

if(!is.null(args2$dopathview)){ #call from gage
    if(args2$data.type=="gene"){
        gene.data=exprs.d
        cpd.data=NULL
        gene.idtype=gid.type
        cpd.idtype="kegg"
    }else if (args2$data.type=="compound"){
        gene.data=NULL
        cpd.data=exprs.d
        gene.idtype="entrez"
        cpd.idtype=gid.type
    }
    osuffix="pathview"
} else{ #call from pathview
    gene.data=gene.d
    cpd.data=cpd.d
    gene.idtype=args2$geneid
    cpd.idtype=args2$cpdid
    osuffix=args2$suffix
}


kegg.native=T
if(!is.null(args2$kegg)){
    if(!args2$kegg) kegg.native=F
}

if(kegg.native){
    source(paste(pvwdir,"scripts/kg.map.R",sep=""))
    kg.map(args2$species)
    kg.cmap()
    gm.fname=paste0(mmap.dir1, args2$species, ".gene.RData")
    cm.fname=paste0(mmap.dir1, "cpd", ".RData")
    load(gm.fname)
    load(cm.fname)
    pv.dt=c("gene", "cpd")[c(!is.null(gene.data), !is.null(cpd.data))]
}

require(pathview)

pv.run=sapply(path.ids, function(pid){
                  if(is.null(args2$kegg)){
                      pv.out <- try(pathview(gene.data = gene.data, gene.idtype=gene.idtype, cpd.data = cpd.data, cpd.idtype=cpd.idtype, pathway.id = pid, species = species, kegg.dir=kegg.dir))
                  } else{
                      pv.out <- try(pathview(gene.data = gene.data, gene.idtype=gene.idtype, cpd.data = cpd.data, cpd.idtype=cpd.idtype, pathway.id = pid, species = species, kegg.dir=kegg.dir, kegg.native =kegg.native, out.suffix = osuffix, sign.pos =args2$pos,same.layer = args2$layer,keys.align = args2$align,split.group = args2$split,expand.node = args2$expand,multi.state=args2$multistate, match.data = args2$matchd ,node.sum=args2$nsum,key.pos = args2$kpos,cpd.lab.offset= args2$offset,limit = list(gene = args2$glmt, cpd = args2$clmt), bins = list(gene = args2$gbins, cpd= args2$cbins),low = list(gene = args2$glow, cpd = args2$clow),mid = list(gene = args2$gmid, cpd = args2$cmid), high = list(gene = args2$ghigh, cpd =args2$chigh),discrete = list(gene = args2$gdisc, cpd = args2$cdisc)))
                  }

                  if(class(pv.out) =="list" & kegg.native){
                      pv.labels(pv.out=pv.out, pv.data.type=pv.dt, pid=pid)
                  }  else  print(paste("error using pawthway id",pid,sep=":"))
              })


