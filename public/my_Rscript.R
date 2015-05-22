
#to get the arguments readable in R code
args <- commandArgs(TRUE)

#initialize arguments values @if the arguments is not specified from form will take @default value here
geneextension <- NULL
cpdextension <- NULL
cpd.d <- NULL
gene.d <- NULL
geneid <- "entrez"
cpdid <- "kegg"
kegg <- T
expand <- F
split <- F
nodesum <- "sum"
gdisc <- F
cdisc <- F
glmt <- 1
clmt <- 1
gbins <- 10
cbins <- 10
glow <- "green"
gmid <- "gray"
ghigh <- "red"
clow <- "blue"
cmid <- "gray"
chigh <- "yellow"
species <- "hsa"
pathway <- "00110"
suffix <- "pathview"
nsum <- "sum"
ncolor <- "transparent"
offset <- 1.0
align <- 'y'
multistate <- NULL

# @arguments are split on comma as they are passed to Rscript with comma separated
v = strsplit(args[1],split=",",fixed=TRUE)

v.split=strsplit(v[[1]], ":", fixed=T)

vs1=sapply(v.split, "[", 2)

names(vs1)=sapply(v.split, "[", 1)

if(!is.na(vs1["geneid"][[1]])){
geneid <- vs1["geneid"][[1]]
}

if(is.na(vs1["geneid"][[1]])){
	geneid <- "ENTREZ"
}

if(!is.na(vs1["cpdid"][[1]])){
cpdid <- tolower(gsub("-"," ",vs1["cpdid"][[1]]))
}

if(is.na(vs1["cpdid"][[1]])){
	cpdid <- "KEGG"
}

if(!is.na(vs1["species"][[1]])){
	v2= strsplit(vs1["species"][[1]],split="-",fixed=TRUE)
 	species <- substr(v2[[1]][1],1,3)
}

if(is.na(vs1["species"][[1]])){
	species <- "hsa"
}

if(!is.na(vs1["suffix"][[1]])){
suffix <- vs1["suffix"][[1]]
}

if(is.na(vs1["suffix"][[1]])){
	suffix <- "pathway"
}

if(!is.na(vs1["pathway"][[1]])){
	v2= strsplit(vs1["pathway"][[1]],split="-",fixed=TRUE)
	pathway <- v2[[1]][1]
}

if(is.na(vs1["pathway"][[1]])){
    pathway <- "00010"
}

if(!is.na(vs1["kegg"][[1]])){
kegg <- eval(vs1["kegg"][[1]]=="T")
}

if(!is.na(vs1["layer"][[1]])){
layer <- eval(vs1["layer"][[1]]=="T")
}

if(!is.na(vs1["split"][[1]])){
split <- eval(vs1["split"][[1]]=="T")
}

if(!is.na(vs1["expand"][[1]])){
expand <- eval(vs1["expand"][[1]]=="T")
}

if(!is.na(vs1["multistate"][[1]])){
multistate <- eval(vs1["multistate"][[1]]=="T")
}

if(!is.na(vs1["matchd"][[1]])){
matchd <- eval(vs1["matchd"][[1]]=="T")
}

if(!is.na(vs1["gdisc"][[1]])){
gdisc <- eval(vs1["gdisc"][[1]]=="T")
}

if(!is.na(vs1["cdisc"][[1]])){
cdisc <- eval(vs1["cdisc"][[1]]=="T")
}

if(!is.na(vs1["kpos"][[1]])){
	kpos <- vs1["kpos"][[1]]
}

if(is.na(vs1["kpos"][[1]])){
	kpos <- "topright"
}

if(!is.na(vs1["pos"][[1]])){
	pos <- vs1["pos"][[1]]
}

if(is.na(vs1["pos"][[1]])){
	pos <- "bottomleft"
}
if(!is.na(vs1["offset"][[1]])){
	offset <- as.numeric(vs1["offset"][[1]]);
}

if(!is.na(vs1["align"][[1]])){
	align <- vs1["align"][[1]]
}

if(!is.na(vs1["nodesum"][[1]])){
	nodesum <- vs1["nodesum"][[1]]
}

if(is.na(vs1["nodesum"][[1]])){
	nodesum <- "sum"
}

if(!is.na(vs1["genecheck"][[1]])){
	genecheck <- vs1["genecheck"][[1]]
}

if(!is.na(vs1["glmt"][[1]])){
	glmt <- as.numeric(vs1["glmt"][[1]])
}

if(is.na(vs1["glmt"][[1]])){
   glmt <- 1
}

if(!is.na(vs1["clmt"][[1]])){
	clmt <- as.numeric(vs1["clmt"][[1]])
}

if(is.na(vs1["clmt"][[1]])){
   clmt <- 1
}

if(!is.na(vs1["gbins"][[1]])){
	gbins <- as.numeric(vs1["gbins"][[1]])
}

if(is.na(vs1["gbins"][[1]])){
    gbins <- 10
}

if(!is.na(vs1["cbins"][[1]])){
	cbins <- as.numeric(vs1["cbins"][[1]])
}

if(is.na(vs1["cbins"][[1]])){
    cbins <- 10
}

if(!is.na(vs1["glow"][[1]])){
	glow <- vs1["glow"][[1]]
}
if(!is.na(vs1["gmid"][[1]])){
	gmid <- vs1["gmid"][[1]]
}

if(is.na(vs1["gmid"][[1]])){
    gmid <- "gray"
}

if(!is.na(vs1["ghigh"][[1]])){
	ghigh <- vs1["ghigh"][[1]]
}

if(is.na(vs1["ghigh"][[1]])){
    ghigh <- "red"
}

if(!is.na(vs1["cpdcheck"][[1]])){
	cpdcheck <- vs1["cpdcheck"][[1]]
}

if(!is.na(vs1["clow"][[1]])){
	clow <- vs1["clow"][[1]]
}

if(is.na(vs1["clow"][[1]])){
 clow <- "blue"
}

if(!is.na(vs1["cmid"][[1]])){
	cmid <- vs1["cmid"][[1]]
}

if(is.na(vs1["cmid"][[1]])){
   cmid <- "gray"
}

if(!is.na(vs1["chigh"][[1]])){
	chigh <- vs1["chigh"][[1]]
}

if(is.na(vs1["chigh"][[1]])){
	chigh <- "yellow"
}

if(!is.na(vs1["geneextension"][[1]])){
	geneextension <- vs1["geneextension"][[1]]
}

if(!is.na(vs1["targedir"][[1]])){
	targedir <- vs1["targedir"][[1]]
}

if(!is.na(vs1["filename"][[1]])){
	filename <- vs1["filename"][[1]]
}

if(!is.na(vs1["cfilename"][[1]])){
	cfilename <- vs1["cfilename"][[1]]
}

if(!is.na(vs1["nsum"][[1]])){
	nsum <- vs1["nsum"][[1]]
}

if(!is.na(vs1["ncolor"][[1]])){
	ncolor <- vs1["ncolor"][[1]]
}

if(!is.na(vs1["pathidx"][[1]])){
	pathidx <- as.numeric(vs1["pathidx"][[1]])

if(pathidx>0)
	{

	}
}

cpdextension <- vs1["cpdextension"][[1]]

setwd(targedir)

print(filename)

v <- strsplit(filename,".",fixed=TRUE)

format <- v[[1]][length(v[[1]])]

format <- strsplit(filename,".",fixed=TRUE)[[1]][]

print (filename)

print (format)

if(geneextension == "txt"){
    print("inside if loop")
	#gene.d=read.delim(filename, sep="\t",header = TRUE, row.names = 1)
	a=read.delim(filename, sep="\t")
    b=as.matrix(a[,-1])
    rownames(b)=make.unique(as.character(a[,1]))
    gene.d = b
	if(ncol(gene.d)<1)
	gene.d=rownames(gene.d)
	else
	gene.d=as.matrix(gene.d)
}

if(geneextension == "csv"){

	a=read.delim(filename, sep=",")
        b=as.matrix(a[,-1])
        rownames(b)=make.unique(as.character(a[,1]))
        gene.d = b
	if(ncol(gene.d)<1)
	gene.d=rownames(gene.d)
	else
	gene.d=as.matrix(gene.d)
	# gene_no_col <- max(count.fields(filename, sep = ","))
	# if(gene_no_col > 1)
	# gene.d = read.delim(filename, sep=",",header = TRUE,row.names = 1)
	# else
	# gene.d = read.delim(filename, sep=",",header = TRUE)
    # gene.d =as.matrix(gene.d)
}

if(geneextension == "rda"){
gene.d <- load(filename)
}


if(!is.na(cpdextension)){
if(cpdextension == "txt"){
	#cpd.d=read.delim(cfilename, sep="\t",header = TRUE, row.names = 1)
	a1=read.delim(cfilename, sep="\t")
    b1=as.matrix(a1[,-1])
    rownames(b1)=make.unique(as.character(a1[,1]))
    cpd.d = b1
	if(ncol(cpd.d)<1)
	cpd.d=rownames(cpd.d)
	else
	cpd.d=as.matrix(cpd.d)

	# print ("hello inside if loop")
	# no_col <- max(count.fields(cfilename, sep = "\t"))
	# if(no_col > 1)
	# x <- read.delim(cfilename, sep="\t",header = TRUE,row.names = 1)
	# else
	# x <- read.delim(cfilename, sep="\t",header = TRUE)
	# cpd.d=as.matrix(x)
}

if(cpdextension == "csv"){
	#cpd.d=read.delim(cfilename, sep=",",header = TRUE, row.names = 1)
	#a1=read.delim(cfilename, sep=",")
     #       b1=as.matrix(a[,-1])
      #      rownames(b1)=make.unique(as.character(a1[,1]))
       #     gene.d = b1
       a1=read.delim(cfilename, sep=",")
           b1=as.matrix(a1[,-1])
           rownames(b1)=make.unique(as.character(a1[,1]))
           cpd.d = b1
       	if(ncol(cpd.d)<1)
       	cpd.d=rownames(cpd.d)
       	else
       	cpd.d=as.matrix(cpd.d)

# no_col <- max(count.fields(cfilename, sep = ","))
# if(no_col > 1)
# x <- read.delim(cfilename, sep=",",header = TRUE,row.names = 1)
# else
# x <- read.delim(cfilename, sep=",",header = TRUE)
# cpd.d=as.matrix(x)
}

if(cpdextension == "rda"){
cpd.d <- load(cfilename)
}
}

save.image("workenv.Rdata")

library(pathview)



pv.out <- pathview(gene.data = gene.d,gene.idtype = geneid,cpd.data = cpd.d,cpd.idtype=cpdid,pathway.id = pathway,species = species,out.suffix = suffix,kegg.native = kegg,sign.pos =pos,same.layer = layer,keys.align = align,split.group = split,expand.node = expand,multi.state=multistate,match.data = matchd ,node.sum=nsum,key.pos = kpos,cpd.lab.offset= offset,limit = list(gene = glmt, cpd = clmt), bins = list(gene = gbins, cpd= cbins),low = list(gene = glow, cpd = clow),mid = list(gene = gmid, cpd = cmid), high = list(gene = ghigh, cpd =chigh),discrete = list(gene = gdisc, cpd = cdisc))

save.image("workenv.Rdata")


write.table(pv.out$plot.data.gene,file=paste(paste(paste("genedata.",species,sep=""),pathway,sep=""),".txt",sep=""),quote = FALSE)
write.table(pv.out$plot.data.cpd,file=paste(paste(paste("cpddata.",species,sep=""),pathway,sep=""),".txt",sep=""),quote = FALSE)

    if(pathidx>0){
		idx <- pathidx
	print("more than one path")
	print(idx)
	print(typeof(idx))
	while(idx > 0)
    {
    	print("inside while loop")

      idx <- idx-1
      print(paste("pathway",idx, sep = ""))
      if(!is.na(vs1[paste("pathway",idx, sep = "")][[1]])){

          print("inside if loop for pathway")
	     pathway1 <- vs1[paste("pathway",idx, sep = "")][[1]]
	     print(pathway1	)
	    try(pv.out <- pathview(gene.data = gene.d,gene.idtype = geneid,cpd.data = cpd.d,cpd.idtype=cpdid,pathway.id = pathway1,species = species,out.suffix = suffix,kegg.native = kegg,sign.pos =pos,same.layer = layer,keys.align = align,split.group = split,expand.node = expand,multi.state=multistate,match.data = matchd ,node.sum=nodesum,key.pos = kpos,limit = list(gene = glmt, cpd = clmt), bins = list(gene = gbins, cpd= cbins),na.col=ncolor,low = list(gene = glow, cpd = clow),mid = list(gene = gmid, cpd = cmid), high = list(gene = ghigh, cpd =chigh),discrete = list(gene = gdisc, cpd = cdisc)), silent=T)
        if(class(pv.out) != "try-error")
        {
         # pv.out <- pathview(gene.data = gene.d,gene.idtype = geneid,cpd.data = cpd.d,cpd.idtype=cpdid,pathway.id = pathway1,species = species,out.suffix = suffix,kegg.native = kegg,sign.pos =pos,same.layer = layer,keys.align = align,split.group = split,expand.node = expand,multi.state=multistate,match.data = matchd ,node.sum=nodesum,key.pos = kpos,limit = list(gene = glmt, cpd = clmt), bins = list(gene = gbins, cpd= cbins),low = list(gene = glow, cpd = clow),mid = list(gene = gmid, cpd = cmid), high = list(gene = ghigh, cpd =chigh),discrete = list(gene = gdisc, cpd = cdisc))
         try(err <- write.table(pv.out$plot.data.gene,file=paste(paste(paste("genedata.",species,sep=""),pathway1,sep=""),".txt",sep=""),quote = FALSE),silent=T)
         try(err <- write.table(pv.out$plot.data.cpd,file=paste(paste(paste("cpddata.",species,sep=""),pathway1,sep=""),".txt",sep=""),quote = FALSE),silent=T)
         }
         else
         print(paste("error using pawthway id",pathway1,sep=":"))

        print(err)
         if(class(err) == "try-error")
         {
         print(paste("error using pawthway id",pathway1,sep=":"))
         }
      }
    }

	}


