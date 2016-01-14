#!/usr/local/bin/Rscript
#!/usr/bin/sh

args <- commandArgs(TRUE)
library(pathview)
publicPathlines =Sys.getenv("pvwdir") 
download.kegg(pathway.id = args[1] , species = args[2] , kegg.dir = paste(paste(publicPathlines,"Kegg/",sep=""),args[2],sep=""))
#download.kegg(pathway.id="00010" , species ="hsa" , kegg.dir = "/home/ybhavasi/Desktop/Kegg")
