
download.path.list <-
  function (species = "map", kegg.dir = ".")
  {
system(paste("mkdir -p", kegg.dir))
      spath.fname=paste0(species, ".paths.txt")
    spath.fmt="http://rest.kegg.jp/list/pathway/%s"
    warn.fmt.spath="Download of %s pathway list failed!\nThis species may not exist!"
    msg=sprintf("Downloading pathway list for %s..", species)
    message("Info: ", msg)
    
    spath.url=sprintf(spath.fmt,  species)
    spath.target=sprintf("%s/%s", kegg.dir, spath.fname)
    spath.status=try(download.file(spath.url, spath.target, quiet=T), silent=T)

    if(class(spath.status)=="try-error"){
      warn.msg=sprintf(warn.fmt.spath, species)
      message("Warning: ", warn.msg)
      unlink(spath.target)
      return(invisible(0))
    } else return(invisible(1))
}

download.kegg.list <-
  function (lname = "pathway", kegg.dir = ".")
  {
system(paste("mkdir -p", kegg.dir))
      list.fname=paste0(lname, ".txt")
    list.fmt="http://rest.kegg.jp/list/%s"
    warn.fmt.list="Download of %s  list failed!\nThis list may not exist!"
    msg=sprintf("Downloading list for %s..", lname)
    message("Info: ", msg)
    
    list.url=sprintf(list.fmt,  lname)
    list.target=sprintf("%s/%s", kegg.dir, list.fname)
    list.status=try(download.file(list.url, list.target, quiet=T), silent=T)

    if(class(list.status)=="try-error"){
      warn.msg=sprintf(warn.fmt.list, lname)
      message("Warning: ", warn.msg)
      unlink(list.target)
      return(invisible(0))
    } else return(invisible(1))
}
