
annot.map <- function(in.ids, in.type, out.type, annot.db, na.rm=TRUE) {
    requireNamespace(annot.db)
    db.obj <- eval(parse(text=paste0(annot.db, "::", annot.db)))
    ##id.types <- keytypes(db.obj)
    id.types <- columns(db.obj) #columns(eval(as.name(annot.db)))
    
    msg <-  paste0("must from: ", paste(id.types, collapse=", "), "!")
    if (! in.type %in% id.types) stop("'in.type' ", msg)
    if (! all(out.type %in% id.types)) stop("'out.type' ", msg)

    in.ids <- unique(as.character(in.ids))
    res <- suppressWarnings(select(db.obj,
                                   keys = in.ids,
                                   keytype = in.type,
                                   columns=c(in.type, out.type)))

    res <- res[, c(in.type, out.type)]
    
    na.idx <- is.na(res[,2])
    if (sum(na.idx)>0) {
        n.na <- length(unique(res[na.idx, 1]))
        if (n.na>0) {
            print(paste("Note:", n.na, "of", length(in.ids), "input IDs unmapped."))
        }
        if (na.rm) res <- res[!na.idx, ]
    }
    return(res)
}

