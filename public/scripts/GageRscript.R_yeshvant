args <- commandArgs(TRUE)

arg.v = strsplit(args[1],split=";|:")[[1]]

logic.idx=c("rankTest", "useFold")

num.idx=c("setSizeMin","setSizeMax")

idx=seq(1, length(arg.v), by=2)

args1=arg.v[idx+1]

names(args1)=arg.v[idx]

args2=as.list(args1)

args2[logic.idx]=as.list(as.logical(args1[logic.idx]))

args2[num.idx]=as.list(as.numeric(args1[num.idx]))

reference.ids = as.numeric(strsplit(args2$reference,split=",")[[1]])

sample.ids = as.numeric(strsplit(args2$sample,split=",")[[1]])

setwd(args2$destDir)

# reading file
        if(!is.null(args2$geneextension)){
                if(args2$geneextension == "txt"){
                    a=read.delim(args2$filename, sep="\t")
                    }
                    else if(args2$geneextension == "csv"){
                    a=read.delim(args2$filename, sep=",")
                    }
                if(ncol(a)>1){
                  exprs=as.matrix(a[,-1])
                }
        }

require(gage)

set.size = c(args2$setSizeMin,args2$setSizeMax)

library(gage)

gsetarg = strsplit(args2$geneSet,split=",")

idx1 = c()
if(args2$geneSetCategory == "kegg"){
kg.hsa=kegg.gsets("hsa")
} else if(args2$geneSetCategory == "go"){
        library(gageData)
        data(go.sets.hs)
        data(go.subs.hs)
}


    for ( i in 1:length(gsetarg[[1]]))
    {
    
    if(gsetarg[[1]][i] == "signalling"){
    idx1 = c(idx1,kg.hsa$sigmet.idx)
    }else if(gsetarg[[1]][i] == "metabolic"){
    idx1 = c(idx1,kg.hsa$met.idx)
    }else if(gsetarg[[1]][i] == "sigmet"){
    idx1 = c(idx1,kg.hsa$sig.idx)
    }else if(gsetarg[[1]][i] == "disease"){
    idx1 = c(idx1,kg.hsa$dise.idx)
    }else if(gsetarg[[1]][i] == "all"){
    idx1 = c(idx1,kg.hsa$met.idx)
    idx1 = c(idx1,kg.hsa$sigmet.idx)
    i = i+1;
    }


    if(gsetarg[[1]][i] == 'bp'){
    idx1 = c(idx1,go.subs.hs$BP)
    }else if(gsetarg[[1]][i] == 'cc'){
    idx1 = c(idx1,go.subs.hs$CC)
    }else if(gsetarg[[1]][i] == 'mf paper'){
    idx1 = c(idx1,go.subs.hs$MF)
    }else if(gsetarg[[1]][i] == 'bp+cc'){
    idx1 = c(idx1,go.subs.hs$BP)
    idx1 = c(idx1,go.subs.hs$CC)
    }else if(gsetarg[[1]][i] == 'all'){
    idx1 = c(idx1,go.subs.hs$BP)
    idx1 = c(idx1,go.subs.hs$CC)
    idx1 = c(idx1,go.subs.hs$MF)
    } 

    }

if(args2$test=='t-test'){
test = gs.tTest
}else if(args2$test=='z-test'){
test = gs.zTest
}else if(args2$test=='KS-test'){
test = gs.KSTest
}


if(args2$geneSetCategory == "kegg"){
idx1 = unique(idx1)
kegg.gs=kg.hsa$kg.sets[idx1]
gsets = kegg.gs
}else if(args2$geneSetCategory == "go"){
  library(gageData)
  data(go.sets.hs)
  data(go.subs.hs)
  gsets = go.sets.hs[idx1]
}

save.image("workenv.RData")


gage.res=gage(exprs=exprs, gsets=gsets, ref = reference.ids, samp = sample.ids,
  set.size = set.size, same.dir = TRUE, compare = args2$compare,
  rank.test = args2$rankTest, use.fold = args2$useFold, saaTest = gs.tTest  )
write.table(rbind(gage.res$greater, gage.res$less), 
            file = "gage.res.txt", sep = "\t")



  