#!/usr/local/bin/Rscript
#!/usr/bin/sh

args <- commandArgs(TRUE)
library(pathview)
download.kegg(pathway.id = args[1] , species = args[2] , kegg.dir = paste(substr(getwd(),1,nchar(getwd())-20),paste("/Kegg/", args[2], sep=""),sep=""))
#download.kegg(pathway.id="00010" , species ="hsa" , kegg.dir = "/home/ybhavasi/Desktop/Kegg")