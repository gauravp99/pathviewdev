            //saving the arguments and re-render on back button
            var pathviewSettingsTab = false;
            var pathviewSettingsCustomizable = false;
            //this function saves the content on the current page and put into a input hidden text
            function save() {
                //resetting the input element
                $("#input").val();

                console.log("In save function saving attributes");
                var savedString = "";
                //for each attribute

                //1. Assay Data
                var assayData = $("#assayData").val();
                savedString += "assayData:" + assayData + ";";

                //2. Log transformed
                var logTransformed = $("#logTransformed").is(":checked");
                savedString += "logTransformed:" + logTransformed + ";";

                //3. Count Data
                var countData = $("#countData").is(":checked");
                savedString += "countData:" + countData + ";";

                //4. Normalized DAta
                var normalizedData = $("#normalizedData").is(":checked");
                savedString += "normalizedData:" + normalizedData + ";";

                //5. Data Type
                var dataType = $("#dataType").val();
                savedString += "dataType:" + dataType + ";";

                //6. Species
                var species = $("#species").val();
                savedString += "species:" + species + ";";

                //7. GeneSet
                var geneSet = $("#geneSet").val();
                savedString += "geneSet:" + geneSet + ";";

                //7.1 getting the geneset options which are dynamically genearted set of options comma separated values
                var geneSetOptions = "geneSetOptions:";
                $("#geneSet option").each(function () {
                    geneSetOptions += $(this).val() + ",";
                });
                savedString += geneSetOptions + ";";

                // 8. Gene ID Type
                var geneIdType = $("#geneIdType").val();
                savedString += "geneIdType:" + geneIdType + ";";

                //getting the options which are dynamically generated
                var geneIdTypeOptions = "geneIdTypeOptions:";
                $("#geneIdType option").each(function () {
                    geneIdTypeOptions += $(this).val() + ",";
                });
                savedString += geneIdTypeOptions + ";";

                // 9.q-value Cutoff
                var cutoff = $("#cutoff").val();
                savedString += "cutoff:" + cutoff + ";";

                // 10. Size min,max
                var setSizeMin = $("#setSizeMin").val();
                savedString += "setSizeMin:" + setSizeMin + ";";

                var setSizeMax = $("#setSizeMax").val();
                savedString += "setSizeMax:" + setSizeMax + ";";

                // 11. Compare
                var compare = $("#compare").val();
                savedString += "compare:" + compare + ";";

                // 12. sameDir
                var sameDir = $("#sameDir").is(":checked");
                savedString += "sameDir:" + sameDir + ";";

                // 13. rankTest
                var rankTest = $("#rankTest").is(":checked");
                savedString += "rankTest:" + rankTest + ";";

                // 14. useFold
                var useFold = $("#useFold").is(":checked");
                savedString += "useFold:" + useFold + ";";

                // 15. Gene Set Test
                var test = $("#test").val();
                savedString += "test:" + test + ";";

                //16. usePathview
                var usePathview = $("#usePathview").is(":checked");
                savedString += "usePathview:" + usePathview + ";";

                //17. RefSelect


                var refselectOptions = "refselectOptions:";
                $("#refselect option").each(function () {
                    if ($.trim($(this).text()).length > 0)
                        refselectOptions += $.trim($(this).text()) + ",";
                });
                savedString += refselectOptions + ";";


                //18. sampleselect
                var sampleselectOptions = "sampleselectOptions:";
                $("#sampleselect option").each(function () {
                    if ($.trim($(this).text()).length > 0)
                        sampleselectOptions += $.trim($(this).text()) + ",";
                });
                savedString += sampleselectOptions + ";";

                //19. reference
                var reference = $("#reference").val();

                savedString += "reference:" + reference + ";";
                //20. sample
                var sample = $("#sample").val();

                savedString += "sample:" + sample + ";";


                if (usePathview) {

                    var pathviewSettings = $("#pathviewSettings").val();
                    savedString += "pathviewSettings:" + pathviewSettings + ";"
                    //if(pathviewSettings === "customize") {


                        //Combining pathview arguments
                        //21. kegg
                        var kegg = $("#kegg").is(":checked");

                        savedString += "kegg:" + kegg + ";";
                        //22.layer
                        var layer = $("#layer").is(":checked");

                        savedString += "layer:" + layer + ";";
                        //23. gdisc
                        var gdisc = $("#gdisc").is(":checked");

                        savedString += "gdisc:" + gdisc + ";";
                        //24.cdisc
                        var cdisc = $("#cdisc").is(":checked");

                        savedString += "cdisc:" + cdisc + ";";
                        //25. split
                        var split = $("#split").is(":checked");

                        savedString += "split:" + split + ";";
                        //26.expand
                        var expand = $("#expand").is(":checked");

                        savedString += "expand:" + expand + ";";
                        //27. multistate
                        var multistate = $("#multistate").is(":checked");

                        savedString += "multistate:" + multistate + ";";
                        //28.matchd
                        var matchd = $("#matchd").is(":checked");

                        savedString += "matchd:" + matchd + ";";
                        //29. Offset
                        var offset = $("#offset").val();

                        savedString += "offset:" + offset + ";";
                        //30. Key Alignment
                        var align = $("#align").val();

                        savedString += "align:" + align + ";";
                        //31. pos
                        var pos = $("#pos").val();

                        savedString += "pos:" + pos + ";";
                        //32. kpos
                        var kpos = $("#kpos").val();

                        savedString += "kpos:" + kpos + ";";
                        //33. nodesun
                        var nodesun = $("#nodesun").val();

                        savedString += "nodesum:" + nodesun + ";";
                        //34. nacolor
                        var nacolor = $("#nacolor").val();

                        savedString += "nacolor:" + nacolor + ";";
                        //35. glmt
                        var glmt = $("#glmt").val();

                        savedString += "glmt:" + glmt + ";";
                        //36. clmt
                        var clmt = $("#clmt").val();

                        savedString += "clmt:" + clmt + ";";
                        //37. gbins
                        var gbins = $("#gbins").val();

                        savedString += "gbins:" + gbins + ";";
                        //38. cbins
                        var cbins = $("#cbins").val();

                        savedString += "cbins:" + cbins + ";";
                        //39. glow
                        var glow = $("#glow").val();

                        savedString += "glow:" + glow + ";";
                        //40. clow
                        var clow = $("#clow").val();

                        savedString += "clow:" + clow + ";";
                        //41. gmid
                        var gmid = $("#gmid").val();

                        savedString += "gmid:" + gmid + ";";
                        //42. cmid
                        var cmid = $("#cmid").val();

                        savedString += "cmid:" + cmid + ";";
                        //43. ghigh
                        var ghigh = $("#ghigh").val();

                        savedString += "ghigh:" + ghigh + ";";
                        //44. chigh
                        var chigh = $("#chigh").val();
                        savedString += "chigh:" + chigh + ";";
                    }
               // }

                //save the string into input element
                console.log("savedString: "+savedString);
                $("#input").val(savedString);

            }

            //reDisplay taking the input from input box function rendering the page
            function reDisplay() {
                console.log("redisplay function displaying the reselected options");
                //1. assay data is populated by default
                //2. Need to display the edit button and delete button

                var inputString = $("#input").val();
                if ($.trim(inputString)) {
                    console.log("showing the edit div");
                    $("#edit").show();
                }
                //split the array into parts for input elements
                var arr = inputString.split(';');


                //1. Assay Data
                if (arr !== "undefined" && $.trim(inputString)) {

                    //setting the value for file is not possible security error
                    //$("#assayData").val(arr[0].split(":")[1]);

                    //2. Log transformed
                    var logTransformed = arr[1].split(":")[1];
                    if (logTransformed == "true")
                        $("#logTransformed").prop("checked", true);
                    else {
                        $("#logTransformed").prop("checked", false);
                    }

                    //3. Count Data
                    var countData = arr[2].split(":")[1];
                    if (countData == "true")
                        $("#countData").prop("checked", true);
                    else {
                        $("#countData").prop("checked", false);
                    }


                    //4. Normalized DAta
                    var normalizedData = arr[3].split(":")[1];
                    if (normalizedData == "true")
                        $("#normalizedData").prop("checked", true);
                    else {
                        $("#normalizedData").prop("checked", false);
                    }


                    //5. Data Type
                    var dataType = arr[4].split(":")[1];
                    $("#dataType").val(dataType);

                    //6. Species
                    var species = arr[5].split(":")[1];
                    $("#species").val(species);

                    //7. GeneSet


                    //7.1 getting the geneset options which are dynamically genearted set of options comma separated values

                    var geneSetOptions = arr[7].split(":")[1].split(",");
                    $("#geneSet").empty();
                    console.log("geneSetOptions :" + geneSetOptions.length);

                    val = dataType;

                    //because the geneset and geneIdfile is dynamically changed we have saved all the option in the select box for geneset and
                    //geneid re-render process makes the options to be removed and pasted back
                    if (val === "compound") {
                        //greying out the species
                        $("#species").prop("readonly", true);
                        $("#species").css("opacity","0.5");
                        $('#geneIdFile').hide();
                        //populating the compound related fields

                        //updating gene set label to Compound set
                        var label = $("label[for='GeneSet']");


                            //setting the value of setSize Min to 5
                        // $('#setSizeMin').val(5);
                        // $('#setSizeMin').val(5);
                        //labels change the inner text

                        $(label[0]).html("Compound Set");
                        $('#geneSet').empty();
                        //gene set populating
                        var molecularSet = ['Metabolic',
                            'Physiological', 'Signaling',
                            'Drug Metabolism', 'Drug Action',
                            'Disease', 'All'];

                        $('#geneSet').append($("<option></option>").attr("value", "Kegg").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("KEGG"));

                        $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'SMPDB').attr('id', 'SMPDB'));

                        $.each(molecularSet, function (index, value) {
                            $('#SMPDB').append($("<option></option>").attr("value", value).text(value));
                        });

                        $('#geneSet').append($("<option></option>").attr("value", "custom").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));

                        var label = $("label[for='geneIdType']");
                        $(label[0]).html("Compound ID Type");


                        $("#geneIdType").empty();
                        var keggGeneSetArray = ["Agricola citation", "Beilstein Registry Number"
                            , "CAS Registry Number", "COMe accession"
                            , "ChEMBL COMPOUND", "DrugBank accession"
                            , "Gmelin Registry Number", "HMDB accession"
                            , "KEGG COMPOUND accession", "KEGG DRUG accession"
                            , "KEGG GLYCAN accession", "LIPID MAPS instance accession"
                            , "MetaCyc accession", "MolBase accession"
                            , "PDB accession", "PDBeChem accession"
                            , "Patent accession", "PubMed citation"
                            , "Reaxys Registry Number", "UM-BBD compID"
                            , "Wikipedia accession", "Compound name"];


                        $.each(keggGeneSetArray, function (index, value) {
                            $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                        });

                        $('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));



                    }

                    if (val === "other") {
                        $('#geneIdFile').show();
                        $("#species").prop("readonly", true);
                        $("#species").css("opacity","0.5");
                        //populating the gene related fields
                        //updating gene set label to Gene set with

                        //$('#setSizeMin').val(10);
                        var label = $("label[for='GeneSet']");
                        $(label[0]).html("Molecular Set");
                        $('#geneSet').empty();

                        var idTypeLabel = $("label[for='geneIdType']");
                        $(idTypeLabel[0]).html("Molecular ID Type");
                        $('#geneSet').append($("<option></option>").attr("value", "custom").attr("selected", "").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));
                        $('#geneIdType').empty();
                        $('#geneIdType').val("KEGG COMPOUND accession");
                        $('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));


                    }


                    if (val === "gene") {
                        $("#species").prop("readonly", false);
                        //$("#geneSet option[value='sigmet.idx']")[0].setAttribute('selected', '');
                        // $('#setSizeMin').val(10);
                        //populating the gene related fields
                        //updating gene set label to Gene set with

                        var label = $("label[for='GeneSet']");
                        $(label[0]).html("Gene Set");
                        $('#geneSet').empty();
                        var idTypeLabel = $("label[for='geneIdType']");
                        $(idTypeLabel[0]).html("Gene ID Type");

                        //gene set popluating values
                        var keggSet = [{'name': 'sigmet.idx', 'value': 'Signaling & Metabolic'},
                            {'name': 'sig.idx', 'value': 'Signaling'}, {'name': 'met.idx', 'value': 'Metabolic'},
                            {'name': 'dise.idx', 'value': 'Disease'}, {'name': 'sigmet.idx,dise.idx', 'value': 'All'}];

                        var goSet = [{'name': 'BP', 'value': 'Biological Process'},
                            {'name': 'CC', 'value': 'Cellular Component'}, {'name': 'MF', 'value': 'Molecular Function'},
                            {'name': 'BP,CC,MF', 'value': 'All'}];


                        $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'KEGG').attr('id', 'Kegg'));
                        $.each(keggSet, function (index, iter) {
                            $('#Kegg').append($("<option></option>").attr("value", iter.name).text(iter.value));
                        });

                        $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'GO').attr('id', 'Go'));
                        $.each(goSet, function (index, iter) {
                            $('#Go').append($("<option></option>").attr("value", iter.name).text(iter.value));
                        });

                        $('#geneSet').append($("<option></option>").attr("value", "custom").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));

                        //geneidtype
                        var geneIdTypeArray = ['ACCNUM', 'ENSEMBL', 'ENSEMBLPROT', 'ENSEMBLTRANS', 'ENTREZ', 'ENZYME', 'GENENAME', 'PROSITE', 'REFSEQ', 'SYMBOL', 'UNIGENE', 'UNIPROT']

                        $('#geneIdType').empty();
                        console.log("rendering the geneid array");
                        $.each(geneIdTypeArray, function (index, value) {
                            $('#geneIdType').append($("<option></option>").attr("value", value).text(value));

                        });

                    }


                    var geneSet = arr[6].split(":")[1];

                    console.log("geneSetValue:" + geneSet);
                    //$("#geneSet").val(geneSet);
                    var geneSetVal = geneSet.split(",");
                    if (geneSetVal !== null)
                        $.each(geneSetVal, function (index, value) {
                            $("#geneSet option[value='" + value + "']")[0].setAttribute('selected', 'selected');

                        });

                    // 8. Gene ID Type
                    var geneIdType = arr[8].split(":")[1];

                    $("#geneIdType").val(geneIdType);


                    //getting the options which are dynamically generated
                    var geneIdTypeOptions = arr[9].split(":")[1].split(",");
                   $.each(geneIdTypeOptions, function (index, value) {
                        console.log("value:"+value);
                       // $("#geneIdType").append("<option></option>").attr("value", value).text(value);
                    });
                    // 9.q-value Cutoff
                    var cutoff = arr[10].split(":")[1];
                    $("#cutoff").val(cutoff);

                    // 10. Size min,max
                    var setSizeMin = arr[11].split(":")[1];
                    $("#setSizeMin").val(setSizeMin);


                    var setSizeMax = arr[12].split(":")[1];
                    $("#setSizeMax").val(setSizeMax);


                    // 11. Compare
                    var compare = arr[13].split(":")[1];
                    $("#compare").val(compare);


                    // 12. sameDir
                    var sameDir = arr[14].split(":")[1];
                    if (sameDir === "true")
                        $("#sameDir").prop("checked", true);
                    else
                        $("#sameDir").prop("checked", false);

                    // 13. rankTest
                    var rankTest = arr[15].split(":")[1];

                    if (rankTest === "true")
                        $("#rankTest").prop("checked", true);
                    else
                        $("#rankTest").prop("checked", false);

                    // 14. useFold
                    var useFold = arr[16].split(":")[1];

                    if (useFold === "true")
                        $("#useFold").prop("checked", true);
                    else
                        $("#useFold").prop("checked", false);

                    // 15. Gene Set Test
                    var test = arr[17].split(":")[1];
                    $("#test").val(test);

                    //16. usePathview
                    var usePathview = arr[18].split(":")[1];
                    if (usePathview === "true")
                        $("#usePathview").prop("checked", true);
                    else
                        $("#usePathview").prop("checked", false);


                    //17. RefSelect
                    var refselectOptions = arr[19].split(":")[1];
                    console.log(refselectOptions + "");
                    var refselectOptions = refselectOptions.split(",");
                    $("#refselect").empty();
                    $.each(refselectOptions, function (index, value) {
                        if ($.trim(value).length > 0)
                            $("#refselect").append("<option></option>").attr("value", index + 1).text(value);
                    });


                    //18. sampleselect

                    var sampleselectOptions = arr[20].split(":")[1];

                    var sampleselectOptions = sampleselectOptions.split(",");
                    $("#sampleselect").empty();
                    $.each(sampleselectOptions, function (index, value) {
                        if ($.trim(value).length > 0)
                            $("#sampleselect").append("<option></option>").attr("value", index + 1).text(value);
                    });


                    //19. reference
                    var reference = arr[21].split(":")[1];
                    $("#reference").val(reference);

                    //20. sample
                    var sample = arr[22].split(":")[1];
                    $("#sample").val(sample);

                    if (usePathview === "true") {
                        console.log("Use Pathview:"+usePathview);
                      //  console.log(""+$("#pathviewSettings-div"));
                        pathviewSettingsTab = true;
                        //45. Pathview Customizable or default;
                        var pathviewSettings = arr[23].split(":")[1];
                        console.log("")
                        if(pathviewSettings == "customize"){
                            pathviewSettingsCustomizable = true;
                        }
                            $("#pathviewSettings-div").show();
                            $('#graphics').show();
                            $('#coloration').show();
                        console.log("Done rendeing the tabs:");
                            //Combining pathview arguments
                            //21. kegg
                            var kegg = arr[24].split(":")[1];
                            if (kegg === "true")
                                $("#kegg").prop("checked", true);
                            else
                                $("#kegg").prop("checked", false);

                            //22.layer
                            var layer = arr[25].split(":")[1];
                            if (layer === "true")
                                $("#layer").prop("checked", true);
                            else
                                $("#layer").prop("checked", false);


                            //23. gdisc
                            var gdisc = arr[26].split(":")[1];

                            if (gdisc === "true")
                                $("#gdisc").prop("checked", true);
                            else
                                $("#gdisc").prop("checked", false);

                            //24.cdisc
                            var cdisc = arr[27].split(":")[1];

                            if (cdisc === "true")
                                $("#cdisc").prop("checked", true);
                            else
                                $("#cdisc").prop("checked", false);


                            //25. split
                            var split = arr[28].split(":")[1];

                            if (split === "true")
                                $("#split").prop("checked", true);
                            else
                                $("#split").prop("checked", false);

                            //26.expand
                            var expand = arr[29].split(":")[1];

                            if (expand === "true")
                                $("#expand").prop("checked", true);
                            else
                                $("#expand").prop("checked", false);

                            //27. multistate
                            var multistate = arr[30].split(":")[1];

                            if (multistate === "true")
                                $("#multistate").prop("checked", true);
                            else
                                $("#multistate").prop("checked", false);


                            //28.matchd
                            var matchd = arr[31].split(":")[1];
                            if (matchd === "true")
                                $("#matchd").prop("checked", true);
                            else
                                $("#matchd").prop("checked", false);


                            //29. Offset
                            var offset = arr[32].split(":")[1];
                            $("#offset").val(offset);


                            //30. Key Alignment
                            var align = arr[33].split(":")[1];
                            $("#align").val(align);

                            //31. pos
                            var pos = arr[34].split(":")[1];
                            $("#pos").val(pos);

                            //32. kpos
                            var kpos = arr[35].split(":")[1];
                            $("#kpos").val(kpos);

                            //33. nodesun
                            var nodesun = arr[36].split(":")[1];
                            $("#nodesun").val(nodesun);

                            //34. nacolor
                            var nacolor = arr[37].split(":")[1];
                            $("#nacolor").val(nacolor);

                            //35. glmt
                            var glmt = arr[38].split(":")[1];
                            $("#glmt").val(glmt);

                            //36. clmt
                            var clmt = arr[39].split(":")[1];
                            $("#clmt").val(clmt);

                            //37. gbins
                            var gbins = arr[40].split(":")[1];
                            $("#gbins").val(gbins);


                            //38. cbins
                            var cbins = arr[41].split(":")[1];
                            $("#cbins").val(cbins);

                            //39. glow
                            var glow = arr[42].split(":")[1];
                            $("#glow").val(glow);


                            //40. clow
                            var clow = arr[43].split(":")[1];
                            $("#clow").val(clow);


                            //41. gmid
                            var gmid = arr[44].split(":")[1];
                            $("#gmid").val(gmid);


                            //42. cmid
                            var cmid = arr[45].split(":")[1];
                            $("#cmid").val(cmid);

                            //43. ghigh
                            var ghigh = arr[46].split(":")[1];
                            $("#ghigh").val(ghigh);

                            //44. chigh
                            var chigh = arr[47].split(":")[1];
                            $("#chigh").val(chigh);





                    }

                }
            }

            //select box allowing to select multiple option without pressing control
            $(document).ready(function () {

               //clear button for the file upload
                $("#clearFile").on("click",function() {
                    $("#assayData").val(null);/*
                    $('#menu').hide();
                    $('#clearFile').hide();*/
                    $('#edit').hide();
                });


                    //code change for reset button fix
                    $("#reset").click(function(){

                       console.log("hello in click event of reset ");
                        //removing the additional two tabls
                        $('#graphics').hide();
                        $('#coloration').hide();
                        //going to the first tab
                        $("#inputA").trigger("click");

                        //resetting the label for geneSet and geneIdType
                        var label = $("label[for='GeneSet']");
                        $(label[0]).html("Gene Set");

                        var label = $("label[for='geneIdType']");
                        $(label[0]).html("Gene ID Type");

                        $('#geneSet').empty();
                        $('#geneIdType').empty();
                        //gene set popluating values
                        var keggSet = [{'name': 'sigmet.idx', 'value': 'Signaling & Metabolic'},
                            {'name': 'sig.idx', 'value': 'Signaling'}, {'name': 'met.idx', 'value': 'Metabolic'},
                            {'name': 'dise.idx', 'value': 'Disease'}, {'name': 'sigmet.idx,dise.idx', 'value': 'All'}];

                        var goSet = [{'name': 'BP', 'value': 'Biological Process'},
                            {'name': 'CC', 'value': 'Cellular Component'}, {'name': 'MF', 'value': 'Molecular Function'},
                            {'name': 'BP,CC,MF', 'value': 'All'}];


                        $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'KEGG').attr('id', 'Kegg'));
                        $.each(keggSet, function (index, iter) {
                            $('#Kegg').append($("<option></option>").attr("value", iter.name).text(iter.value));
                        });

                        $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'GO').attr('id', 'Go'));
                        $.each(goSet, function (index, iter) {
                            $('#Go').append($("<option></option>").attr("value", iter.name).text(iter.value));
                        });

                        $('#geneSet').append($("<option></option>").attr("value", "custom").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));

                        //geneidtype
                        var geneIdTypeArray = ['ACCNUM', 'ENSEMBL', 'ENSEMBLPROT', 'ENSEMBLTRANS', 'ENTREZ', 'ENZYME', 'GENENAME', 'PROSITE', 'REFSEQ', 'SYMBOL', 'UNIGENE', 'UNIPROT']

                        $('#geneIdType').empty();

                        $.each(geneIdTypeArray, function (index, value) {
                            $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                        });


                        //setting the default value for geneIdType and geneSet

                        $('#geneIdType option[value=ENTREZ]').attr('selected','selected');
                        $('#geneSet option[value="sigmet.idx"]').attr('selected','selected');
                        //$('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));

                        //resetting the species readonly property to false
                        $("#species").prop("readonly",false);
                        //greying out the species div and input element
                        $("#species").css("opacity","1");

                        //enabling use pathview if disabled
                        $("[name='dopathview']").bootstrapSwitch('disabled',false);

                    });


                    //the popup window refselect and sample select option values on change function
                    $('#refselect').change(function () {
                        console.log("refselect change function");
                        var noOfColumns = $('#refselect option').size();

                        //console.log("noofcolumns: is working" + noOfColumns);
                        for (var j = 1; j < noOfColumns; j++) {
                            $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                        }
                        //console.log($(this).val());
                        for (var i = 0; i < $(this).val().length; i++) {
                            var selected = $(this).val()[i];
                            $("#sampleselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                        }

                    });

                    $('#sampleselect').change(function () {
                        console.log("sampleselect change function");
                        var noOfColumns = $('#sampleselect option').size();
                        //console.log("noofcolumns: is working" + noOfColumns);
                        for (var j = 1; j < noOfColumns; j++) {
                            sample_selected_text = "";
                            $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                        }
                        for (var i = 0; i < $(this).val().length; i++) {

                            var selected = $(this).val()[i];
                            //console.log(selected);
                            $("#refselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                        }


                    });

                    //assy data file chnage function
                    $("#assayData").change(function () {
                        var fileName = $("#assayData").val();

                        if (fileName) {
                            $('#reference').val("");
                            $('#sample').val("");
                            $("#refselect option").prop("disabled", false);
                            $("#sampleselect option").prop("disabled", false);
                        }
                    });

                    //onclick function
                    $("#submit-button").click(function () {
                        //calling a function
                        save();
                    });

                    //redisplay function formatting the string and redisplay the input elements
                    reDisplay();
                    $("#graphics").hide();
                    console.log(pathviewSettingsTab+": in document ready function");

                    //get the input element value
                    var value = $("#input").val();
                    if ($.trim(value).length == 0)
                        $("#geneSet").val($("#geneSet option:first").val());
                    //make sure the control button will work with select and unselect

                    $('#coloration').hide();
                    $('#bins-div').toggle();
                    $('#pathviewSettings-div').toggle();
                    $("[name='dopathview']").bootstrapSwitch();
                    $("[name='logTransformed']").bootstrapSwitch();
                    $("[name='normalizedData']").bootstrapSwitch();
                    $("[name='countData']").bootstrapSwitch();
                    $("[name='logTransformed']").bootstrapSwitch();
                    $("[name='test2d']").bootstrapSwitch();
                    $("[name='rankTest']").bootstrapSwitch();
                    $("[name='useFold']").bootstrapSwitch();
                    $("[name='useFold']").setOnLabel = "setOnLabel";
                    $("[name='useFold']").bootstrapSwitch.defaults.setOffLabel = "setOnLabel";

                    $('input[name="dopathview"]').on('switchChange.bootstrapSwitch', function (event, state) {
                        // $('#dataType-div').toggle();
                        if ($('#bins-div')) {
                            $('#bins-div').toggle();
                        }
                        if($(this).is(":checked")){
                        $('#pathviewSettings-div').show();
                        }
                        else{
                            $('#pathviewSettings-div').hide();
                            $('#graphics').hide();
                            $('#coloration').hide();
                            $('#pathviewSettings').val("default");
                        }
                    });



                    $('#pathviewSettings').change(function () {
                        var pathviewSettingval = $('#pathviewSettings').val();
                        if (pathviewSettingval == "customize") {
                            $("#graphicsA").trigger("click");
                            $('#graphics').show();
                            $('#coloration').show();
                            $('#graphics').addClass('selected');
                            $('#analysis').removeClass('selected');

                        }
                        else {
                            $('#graphics').hide();
                            $('#coloration').hide();
                        }
                    });

                    //making go species and id type binding

                    //hide the input type file

                    var keggGeneIdType = {
                        "ENTREZ": "ENTREZ",
                        "ACCNUM": "ACCNUM",
                        "ENSEMBL": "ENSEMBL",
                        "ENSEMBLPROT": "ENSEMBLPROT",
                        "ENSEMBLTRANS": "ENSEMBLTRANS",
                        "ENZYME": "ENZYME",
                        "GENENAME": "GENENAME",
                        "KEGG": "KEGG",
                        "PROSITE": "PROSITE",
                        "REFSEQ": "REFSEQ",
                        "SYMBOL": "SYMBOL",
                        "UNIGENE": "UNIGENE",
                        "UNIPROT": "UNIPROT"
                    };

                    var goSpecIdBind = {
                        "Anopheles": "Entrez",
                        "Arabidopsis": "TAIR",
                        "Bovine": "Entrez",
                        "Worm": "Entrez",
                        "Canine": "Entrez",
                        "Fly": "Entrez",
                        "Zebrafish": "Entrez",
                        "E coli strain K12": "Entrez",
                        "E coli strain Sakai": "Entrez",
                        "Chicken": "Entrez",
                        "Human": "Entrez",
                        "Mouse": "Entrez",
                        "Rhesus": "Entrez",
                        "Malaria": "ORF",
                        "Chimp": "Entrez",
                        "Rat": "Entrez",
                        "Yeast": "ORF",
                        "Pig": "Entrez",
                        "Xenopus": "Entrez"
                    };

                    $.each(goSpeciesArray, function (index, value) {
                        if ((goSpeciesArray[index]['id_type']).toLowerCase() === 'eg')
                            goSpecIdBind[goSpeciesArray[index]['species_id']] = "Entrez";
                        else
                            goSpecIdBind[goSpeciesArray[index]['species_id']] = (goSpeciesArray[index]['id_type']).toUpperCase();
                    });

                    var goSpecies = {
                        'Anopheles': 'Anopheles',
                        'Arabidopsis': 'Arabidopsis',
                        'Bovine': 'Bovine',
                        'Worm': 'Worm',
                        'Canine': 'Canine',
                        'Fly': 'Fly',
                        'Zebrafish': 'Zebrafish',
                        'E coli strain K12': 'E coli strain K12',
                        'E coli strain Sakai': 'E coli strain Sakai',
                        'Chicken': 'Chicken',
                        'Human': 'Human',
                        'Mouse': 'Mouse',
                        'Rhesus': 'Rhesus',
                        'Malaria': 'Malaria',
                        'Chimp': 'Chimp',
                        'Rat': 'Rat',
                        'Yeast': 'Yeast',
                        'Pig': 'Pig',
                        'Xenopus': 'Xenopus'
                    };

                    var GogetSetChange = false;
                    var KegggetSetChange = false;

                    //data type change function
                    $('#dataType').change(function () {
                        var val = $('#dataType').val();
                        if (val === "compound") {

                            //setting species input non editable and greyed out
                            $("#species").prop("readonly",true);
                            //greying out the species div and input element
                            $("#species").css("opacity","0.5");



                            $('#geneIdFile').hide();
                            //populating the compound related fields

                            //updating gene set label to Compound set
                            var label = $("label[for='GeneSet']");
                            //setting the value of setSize Min to 5
                            $('#setSizeMin').val(5);
                            //labels change the inner text

                            $(label[0]).html("Compound Set");
                            $('#geneSet').empty();
                            //gene set populating
                            var molecularSet = ['Metabolic',
                                'Physiological', 'Signaling',
                                'Drug Metabolism', 'Drug Action',
                                'Disease', 'All'];

                            $('#geneSet').append($("<option></option>").attr("selected", "").attr("value", "Kegg").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("KEGG"));

                            $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'SMPDB').attr('id', 'SMPDB'));

                            $.each(molecularSet, function (index, value) {
                                if(value === "All")
                                {
                                    $('#SMPDB').append($("<option></option>").attr("value", "Metabolic,Physiological,Signaling,Drug Metabolism,Drug Action,Disease").text(value));
                                }else{
                                    $('#SMPDB').append($("<option></option>").attr("value", value).text(value));
                                }

                            });

                            $('#geneSet').append($("<option></option>").attr("value", "custom").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));

                            //default option set for geneset to kegg and disable all
                            $('#geneSet option').attr('disabled',true);
                            $('#geneSet option[value="Kegg"]')[0].removeAttribute('disabled');


                            var label = $("label[for='geneIdType']");
                            $(label[0]).html("Compound ID Type");


                            $("#geneIdType").empty();
                            var keggGeneSetArray = ["Agricola citation", "Beilstein Registry Number"
                                , "CAS Registry Number", "COMe accession"
                                , "ChEMBL COMPOUND", "DrugBank accession"
                                , "Gmelin Registry Number", "HMDB accession"
                                , "KEGG COMPOUND accession", "KEGG DRUG accession"
                                , "KEGG GLYCAN accession", "LIPID MAPS instance accession"
                                , "MetaCyc accession", "MolBase accession"
                                , "PDB accession", "PDBeChem accession"
                                , "Patent accession", "PubMed citation"
                                , "Reaxys Registry Number", "UM-BBD compID"
                                , "Wikipedia accession", "Compound name"];

                            $.each(keggGeneSetArray, function (index, value) {
                                $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                            });
                            $('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));

                            $("#geneIdType").val("KEGG COMPOUND accession");
                            //$("#geneIdType option[value='KEGG COMPOUND accession']")[0].setAttribute('selected', '');

                        }

                        if (val === "other")
                        {
                            //disable pathview optio
                            $("#usePathview").bootstrapSwitch('state', false);
                            $("[name='dopathview']").bootstrapSwitch('disabled',true);

                            $('#geneIdFile').show();
                            //setting species input non editable and greyed out
                            $("#species").prop("readonly",true);
                            //greying out the species div and input element
                            $("#species").css("opacity","0.5");

                            //populating the gene related fields
                            //updating gene set label to Gene set with
                            $('#setSizeMin').val(10);
                            var label = $("label[for='GeneSet']");
                            $(label[0]).html("Molecular Set");
                            $('#geneSet').empty();

                            var idTypeLabel = $("label[for='geneIdType']");
                            $(idTypeLabel[0]).html("Molecular ID Type");
                            $('#geneSet').append($("<option></option>").attr("value", "custom").attr("selected", "").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));
                            $('#geneIdType').empty();
                            $('#geneIdType').val("KEGG COMPOUND accession");
                            $('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));


                        }


                        if (val === "gene") {
                            $('#geneIdFile').hide();
                            //setting species input non editable and greyed out
                            $("#species").prop("readonly",false);
                            //greying out the species div and input element
                            $("#species").css("opacity","1");

                            //$("#geneSet option[value='sigmet.idx']")[0].setAttribute('selected', '');
                            $('#setSizeMin').val(10);
                            //populating the gene related fields
                            //updating gene set label to Gene set with

                            var label = $("label[for='GeneSet']");
                            $(label[0]).html("Gene Set");
                            $('#geneSet').empty();
                            var idTypeLabel = $("label[for='geneIdType']");
                            $(idTypeLabel[0]).html("Gene ID Type");

                            //gene set popluating values
                            var keggSet = [{'name': 'sigmet.idx', 'value': 'Signaling & Metabolic'},
                                {'name': 'sig.idx', 'value': 'Signaling'}, {'name': 'met.idx', 'value': 'Metabolic'},
                                {'name': 'dise.idx', 'value': 'Disease'}, {'name': 'sigmet.idx,dise.idx', 'value': 'All'}];

                            var goSet = [{'name': 'BP', 'value': 'Biological Process'},
                                {'name': 'CC', 'value': 'Cellular Component'}, {
                                    'name': 'MF',
                                    'value': 'Molecular Function'
                                },
                                {'name': 'BP,CC,MF', 'value': 'All'}];


                            $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'KEGG').attr('id', 'Kegg'));
                            $.each(keggSet, function (index, iter) {
                                $('#Kegg').append($("<option></option>").attr("value", iter.name).text(iter.value));
                            });

                            $('#geneSet').append($("<optgroup></optgroup>").attr('label', 'GO').attr('id', 'Go'));
                            $.each(goSet, function (index, iter) {
                                $('#Go').append($("<option></option>").attr("value", iter.name).text(iter.value));
                            });

                            $('#geneSet').append($("<option></option>").attr("value", "custom").attr("style", "background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;").text("Custom"));

                            //geneidtype
                            var geneIdTypeArray = ['ACCNUM', 'ENSEMBL', 'ENSEMBLPROT', 'ENSEMBLTRANS', 'ENTREZ', 'ENZYME', 'GENENAME', 'PROSITE', 'REFSEQ', 'SYMBOL', 'UNIGENE', 'UNIPROT']

                            $('#geneIdType').empty();

                            $.each(geneIdTypeArray, function (index, value) {
                                $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                            });


                            $('#geneIdType').append($("<option></option>").attr("value", 'custom').text('custom'));
                            $("#geneIdType").val("ENTREZ");
                            $('#geneSet').val("sigmet.idx");
                        }

                    });

                    $('#species').change(function () {
                            $('#geneIdFile').hide();
                            var geneidType = $('#geneIdType').val();
                            var val = $('#species').val();
                            $.each(goSpeciesArray, function (index, value) {
                                if (goSpeciesArray[index]['Go_name'].toLowerCase() === val.toLowerCase()) {

                                    $('#species').val(goSpeciesArray[index]['species_id'] + '-' + goSpeciesArray[index]['species_desc'] + '-' + goSpeciesArray[index]['Go_name']);
                                }
                            });

                            var i = 0;
                            var goSpeciesMatch = false;

                            while (i < goSpeciesArray.length) {
                                if (goSpeciesArray[i]['species_id'] == $('#species').val().split("-")[0]) {

                                    goSpeciesMatch = true;
                                    break;
                                }

                                i++;
                            }

                            if (!goSpeciesMatch) {
                                if ($('#species').val().split("-")[0] === "custom") {
                                    $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sig.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='met.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx,dise.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet").val('custom');
                                    $('#geneIdType').empty();
                                    $('#geneIdType').append($("<option></option>")
                                        .attr("value", "custom").text("custom"));
                                    $('#geneIdFile').show();
                                } else {
                                    $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');

                                    //to make sure that these options are not enabled even none of the option selected
                                    $("#geneSet option[value='BP']")[0].setAttribute('DoNotEnable', 'DoNotEnable');
                                    $("#geneSet option[value='CC']")[0].setAttribute('DoNotEnable', 'DoNotEnable');
                                    $("#geneSet option[value='MF']")[0].setAttribute('DoNotEnable', 'DoNotEnable');
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('DoNotEnable', 'DoNotEnable');

                                }
                            }
                            else {
                                $("#geneSet option[value='BP']")[0].removeAttribute('DoNotEnable');
                                $("#geneSet option[value='CC']")[0].removeAttribute('DoNotEnable');
                                $("#geneSet option[value='MF']")[0].removeAttribute('DoNotEnable');
                                $("#geneSet option[value='BP,CC,MF']")[0].removeAttribute('DoNotEnable');
                                $("#geneSet option[value='BP']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='CC']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='BP,CC,MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sig.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='met.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sigmet.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='dise.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sigmet.idx,dise.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='custom']")[0].removeAttribute('disabled');
                            }

                            var flag = false;
                            $.each(GageSpeciesGeneIDMAtch, function (index, value) {
                                if (GageSpeciesGeneIDMAtch[index]['species_id'] === $('#species').val().substr(0, 3)) {
                                    if (!flag) {
                                        $('#geneIdType').empty();
                                        flag = true;
                                    }
                                    $('#geneIdType').append($("<option></option>")
                                        .attr("value", GageSpeciesGeneIDMAtch[index]['geneid']).text(GageSpeciesGeneIDMAtch[index]['geneid']));
                                }
                            });


                            if ($('#geneIdType > option').length == 1) {
                                if (goSpecIdBind[$('#species').val()] != null) {
                                    $('#geneIdType').empty();
                                    $('#geneIdType').append($("<option></option>")
                                        .attr("value", goSpecIdBind[$('#species').val()]).text(goSpecIdBind[$('#species').val()]));
                                }
                                else if (goSpecIdBind[$('#species').substr(0, 3).val()] != null) {
                                    $('#geneIdType').empty();
                                    $('#geneIdType').append($("<option></option>")
                                        .attr("value", goSpecIdBind[$('#species').val()]).text(goSpecIdBind[$('#species').val()]));
                                }


                            } else {
                                $("#geneSet option[value='dise.idx']")[0].removeAttribute('disabled');
                                $.each(speciesdiseaseArray, function (speciesIter, specieValue) {
                                    if (specieValue['species_id'] === $('#species').val().split('-')[0]) {
                                        $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                                        return false;
                                    }
                                });
                            }

                            if (!flag) {
                                $('#geneIdType').empty();
                                flag = true;
                                $('#geneIdType').append($("<option></option>").attr("value", "KEGG").text("KEGG"));
                                $('#geneIdType').append($("<option></option>").attr("value", "ENTREZ").text("ENTREZ"));
                            } else {
                                $("#geneIdType").val(geneidType);
                                //$("#geneIdType option[value=\""+geneidType + "\"]")[0].setAttribute('selected','selected');
                            }

                        }
                    );

                    $('#refselect').change(function () {

                        var ref_selected_text = "";
                        var noOfColumns = $('#NoOfColumns').val();
                        for (var j = 1; j <= noOfColumns; j++) {
                            ref_selected_text = "";
                            $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                        }
                        for (var i = 0; i < $(this).val().length; i++) {

                            var selected = $(this).val()[i];
                            console.log(selected);
                            ref_selected_text = ref_selected_text + (selected) + ",";
                            $("#sampleselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                        }
                        $('#reference').val(ref_selected_text);
                    });

                    $('#sampleselect').change(function () {

                        var noOfColumns = $('#NoOfColumns').val();
                        var sample_selected_text = "";
                        for (var j = 1; j <= noOfColumns; j++) {
                            sample_selected_text = "";
                            $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                        }
                        for (var i = 0; i < $(this).val().length; i++) {

                            var selected = $(this).val()[i];
                            console.log(selected);
                            sample_selected_text = sample_selected_text + selected + ",";
                            $("#refselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                        }

                        $('#sample').val(sample_selected_text);
                    });

                    $('#reference').bind("change paste keyup", function () {

                        var refrence_text = $('#reference').val().trim();
                        var ref_array = refrence_text.split(",");
                        var noOfColumns = $('#NoOfColumns').val();
                        for (var j = 1; j < noOfColumns; j++) {
                            $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                            $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('selected');
                        }
                        for (var i = 0; i < ref_array.length; i++) {

                            if ($("#sampleselect option[value=\"" + ref_array[i] + "\"]")[0] != null) {
                                $("#sampleselect option[value=\"" + ref_array[i] + "\"]")[0].setAttribute('disabled', 'disabled');
                                $("#sampleselect option[value=\"" + ref_array[i] + "\"]")[0].removeAttribute("selected");
                            }
                            if ($("#refselect option[value=\"" + ref_array[i] + "\"]")[0] != null) {
                                $("#refselect option[value=\"" + ref_array[i] + "\"]")[0].removeAttribute('disabled');
                                $("#refselect option[value=\"" + ref_array[i] + "\"]")[0].setAttribute("selected", "selected");
                            }
                        }

                    });

                    $('#sample').bind("change paste keyup click", function () {
                        var sample_text = $('#sample').val().trim();
                        var sample_array = sample_text.split(",");
                        var noOfColumns = $('#NoOfColumns').val();
                        for (var j = 1; j < noOfColumns; j++) {
                            $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                            $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('selected');

                        }
                        for (var i = 0; i < sample_array.length; i++) {

                            if ($("#refselect option[value=\"" + sample_array[i] + "\"]")[0] != null) {
                                $("#refselect option[value=\"" + sample_array[i] + "\"]")[0].setAttribute('disabled', 'disabled');
                                $("#sampleselect option[value=\"" + sample_array[i] + "\"]")[0].removeAttribute("selected");
                            }
                            if ($("#sampleselect option[value=\"" + sample_array[i] + "\"]")[0] != null) {
                                $("#sampleselect option[value=\"" + sample_array[i] + "\"]")[0].removeAttribute('disabled');
                                $("#sampleselect option[value=\"" + sample_array[i] + "\"]")[0].setAttribute("selected", "selected");
                            }

                        }
                    });

                    //javascript code to make sure clicking on the option last option unselect it also make all the other options active
                    var currentSelect = $("#geneSet option:selected").index();

                    $("#geneSet option").click(function () {
                        if ($(this).index() == currentSelect) {
                            $(this).prop("selected", false);
                            currentSelect = -1;

                            if (!$("#geneSet option[value='BP']")[0].getAttribute('DoNotEnable')) {
                                $("#geneSet option[value='BP']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='CC']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='BP,CC,MF']")[0].removeAttribute('disabled');
                            }

                            $("#geneSet option[value='sig.idx']")[0].removeAttribute('disabled');
                            $("#geneSet option[value='met.idx']")[0].removeAttribute('disabled');
                            $("#geneSet option[value='sigmet.idx']")[0].removeAttribute('disabled');
                            $("#geneSet option[value='dise.idx']")[0].removeAttribute('disabled');
                            $("#geneSet option[value='sigmet.idx,dise.idx']")[0].removeAttribute('disabled');
                            $("#geneSet option[value='custom']")[0].removeAttribute('disabled');

                        } else {
                            currentSelect = $(this).index();
                        }
                    });

                    $('#geneSet').change(function () {


                        if($(this).val() == null)
                        {
                            console.log("variable is empty");
                            $("#geneSet option").attr("disabled", false);
                        }
                        else if($(this).val()[0] != null){
                            //log the value
                            var checkValue = $(this).val()[0];
                            console.log($(this).val()[0]);
                            var keggValues = ['sig.idx','met.idx','sigmet.idx','dise.idx','sigmet.idx,dise.idx','Kegg'];
                            console.log($.inArray(checkValue, keggValues ));
                            if($.inArray(checkValue, keggValues ) < 0)
                            {

                                //if($("#usePathviw").is(":checked"))
                                $("#usePathview").bootstrapSwitch('state', false);
                                $("[name='dopathview']").bootstrapSwitch('disabled',true);
                                $("#usePathview").attr('checked',false);
                                $('#graphics').hide();
                                $('#coloration').hide();
                                $('#pathviewSettings-div').hide();
                            }else{
                                $("[name='dopathview']").bootstrapSwitch('disabled',false);

                            }


                        }


                        
			            var dataType = $('#dataType').val();
                        var keggGeneSetArray = ["Agricola citation", "Beilstein Registry Number"
                            , "CAS Registry Number", "COMe accession"
                            , "ChEMBL COMPOUND", "DrugBank accession"
                            , "Gmelin Registry Number", "HMDB accession"
                            , "KEGG COMPOUND accession", "KEGG DRUG accession"
                            , "KEGG GLYCAN accession", "LIPID MAPS instance accession"
                            , "MetaCyc accession", "MolBase accession"
                            , "PDB accession", "PDBeChem accession"
                            , "Patent accession", "PubMed citation"
                            , "Reaxys Registry Number", "UM-BBD compID"
                            , "Wikipedia accession", "Compound Name"];

                        //var SMPDBGeneSetArray = ["SMPDB ID", "Metabolite ID", "Metabolite Name"];
                        var SMPDBGeneSetArray = ["SMPDB Metabolite ID"];

                        if (dataType == "compound") {

                            var selectedGeneSet = $('#geneSet').val();

                            if ($(this).val() == null) {
                                $('#geneIdFile').hide();
                                $.each($("#geneSet option"), function (index, value) {
                                    value.removeAttribute('disabled');
                                });
                            }
                            else if ($.trim(selectedGeneSet) !== "Kegg" && $.trim(selectedGeneSet) !== "custom") {
                                $('#geneIdFile').hide();
                                $('#geneSet option[value = "Kegg" ]')[0].setAttribute('disabled', 'disabled');
                                $('#geneSet option[value = "custom" ]')[0].setAttribute('disabled', 'disabled');
                                $('#geneIdType').empty();
                                $.each(keggGeneSetArray, function (index, value) {
                                    $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                                });

                                $.each(SMPDBGeneSetArray, function (index, value) {
                                    $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                                });

                                $('#geneIdType').val("KEGG COMPOUND accession");

                            }
                            else if($.trim(selectedGeneSet) === "custom"){
                                //disabling all option and enabling custom option
                                $("#geneSet option").attr("disabled", true);
                                $('#geneSet option[value = "custom"]')[0].removeAttribute('disabled');

                                //removing options in geneid and adding a custom option
                                $('#geneIdType').empty();

                                $('#geneIdType').append($("<option></option>")
                                    .attr("value", "custom").text("custom"));

                                $('#specieslist').append($("<option></option>").attr("value", "custom").text("custom"));
                                $('#geneIdFile').show();



                            }else {
                                $('#geneIdFile').hide();
                                var molecularSet = ['Metabolic',
                                    'Physiological', 'Signaling',
                                    'Drug Metabolism', 'Drug Action',
                                    'Disease', 'All'];
                                $.each(molecularSet, function (index, value) {

                                    var eleme = $('#geneSet option[value = \'' + $.trim(value) + '\' ]')[0];
                                    eleme.setAttribute('disabled', 'disabled');
                                });
                                $('#geneIdType').empty();
                                $.each(keggGeneSetArray, function (index, value) {
                                    $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                                });
                                $('#geneIdType').val("KEGG COMPOUND accession");
                            }

                        }
                        if (dataType == "gene" || dataType == "other") {
                            if ($(this).val() == null) {
                                $("#geneSet option[value='BP']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='CC']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='BP,CC,MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sig.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='met.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sigmet.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='dise.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='BP,CC,MF']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='sigmet.idx,dise.idx']")[0].removeAttribute('disabled');
                                $("#geneSet option[value='custom']")[0].removeAttribute('disabled');
                            }
                            else {
                                var $selected = $(this).val()[$(this).val().length - 1];
                                if ($selected === "sig.idx" || $selected === "met.idx" || $selected === "sigmet.idx" || $selected === "dise.idx" || $selected === "sigmet.idx,dise.idx") {
                                    var geneIdTypeArray = ['ACCNUM', 'ENSEMBL', 'ENSEMBLPROT', 'ENSEMBLTRANS', 'ENTREZ', 'ENZYME', 'GENENAME', 'PROSITE', 'REFSEQ', 'SYMBOL', 'UNIGENE', 'UNIPROT']

                                    $('#geneIdType').empty();
                                    console.log("rendering the geneid array");
                                    $.each(geneIdTypeArray, function (index, value) {
                                        $('#geneIdType').append($("<option></option>").attr("value", value).text(value));

                                    });
                                    $('#geneIdType').val("ENTREZ");

                                    $('#geneIdType').show();
                                    $('#geneIdFile').hide();
                                    if (!KegggetSetChange) {
                                        GogetSetChange = false;
                                        KegggetSetChange = true;
                                        $('#specieslist').empty();

                                    }

                                    $.each(speciesArray, function (speciesIter, specieValue) {

                                        if (specieValue['species_common_name'] == null) {
                                            $('#specieslist').append($("<option></option>")
                                                .attr("value", specieValue['species_id'] + "-" + specieValue['species_desc']).text(specieValue['species_id'] + "-" + specieValue['species_desc']));
                                        }
                                        else
                                            $('#specieslist').append($("<option></option>")
                                                .attr("value", specieValue['species_id'] + "-" + specieValue['species_desc'] + "-" + specieValue['species_common_name']).text(specieValue['species_id'] + "-" + specieValue['species_desc'] + "-" + specieValue['species_common_name']));
                                    });


                                    $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='custom']")[0].setAttribute('disabled', 'disabled');

                                }
                                else if ($selected === "BP" || $selected === "CC" || $selected === "MF" || $selected === "BP,CC,MF") {
                                    var geneIdTypeArray = ['ACCNUM', 'ENSEMBL', 'ENSEMBLPROT', 'ENSEMBLTRANS', 'ENTREZ', 'ENZYME', 'GENENAME', 'PROSITE', 'REFSEQ', 'SYMBOL', 'UNIGENE', 'UNIPROT']

                                    $('#geneIdType').empty();
                                    console.log("rendering the geneid array");
                                    $.each(geneIdTypeArray, function (index, value) {
                                        $('#geneIdType').append($("<option></option>").attr("value", value).text(value));

                                    });
                                    $('#geneIdType').val("ENTREZ");
                                    $('#geneIdType').show();
                                    $('#geneIdFile').hide();

                                    if (!GogetSetChange) {
                                        GogetSetChange = true;
                                        KegggetSetChange = false;
                                    }

                                    $('#specieslist').empty();

                                    $.each(goSpecies, function (value, key) {
                                        $('#specieslist').append($("<option></option>")
                                            .attr("value", value).text(key));
                                    });
                                    $.each(goSpeciesArray, function (index, value) {
                                        $('#specieslist').append($("<option></option>")
                                            .attr("value", goSpeciesArray[index]['species_id'] + '-' + goSpeciesArray[index]['species_desc'] + '-' + goSpeciesArray[index]['Go_name']).text(goSpeciesArray[index]['species_id'] + '-' + goSpeciesArray[index]['species_desc'] + '-' + goSpeciesArray[index]['Go_name']));
                                    });


                                    $("#geneSet option[value='sig.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='met.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx,dise.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='custom']")[0].setAttribute('disabled', 'disabled');
                                }
                                else {
                                    $('#geneIdFileResult').hide();
                                    $('#geneIdType').empty();

                                    $('#geneIdType').append($("<option></option>")
                                        .attr("value", "custom").text("custom"));

                                    $('#specieslist').append($("<option></option>").attr("value", "custom").text("custom"));
                                    $('#geneIdFile').show();
                                    $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled')
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sig.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='met.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
                                    $("#geneSet option[value='sigmet.idx,dise.idx']")[0].setAttribute('disabled', 'disabled');

                                }
                            }
                        }

                    });

                    if(pathviewSettingsTab){
                        $("#pathviewSettings-div").show();
                        if(pathviewSettingsCustomizable)
                        {
                            $('#graphics').show();
                            $('#coloration').show();
                        }


                    }


                    //check the data type and disable geneset options
                    var dataType = $('#dataType').val();
                    if(dataType === "gene"){
                        var kegg = ['sig.idx','met.idx','sigmet.idx','dise.idx','sigmet.idx,dise.idx'];
                        //check the value of geneset
                        var geneSet = $('#geneSet').val();
                        if( geneSet !== null)
                        {
                            var res =geneSet;

                            if(res.length > 0)
                            {
                                if($.inArray(res[0], kegg ) != -1)
                                {
                                    $('#geneSet option').attr("disabled",true);
                                    $.each(kegg,function(index,value){
                                        $('#geneSet option[value=\''+value+'\']')[0].removeAttribute("disabled");
                                    });

                                }else if(res[0] === "custom"){
                                    $('#geneSet option').attr("disabled",true);
                                    $('#geneSet option[value="custom"]')[0].attr("disabled",false);
                                }else{

                                    $.each(kegg,function(index,value){
                                        $('#geneSet option[value=\''+value+'\']')[0].setAttribute("disabled","disabled");
                                    });
                                    $('#geneSet option[value="custom"]')[0].setAttribute("disabled","disabled");
                                }
                            }
                        }
                    }
                    else if(dataType === "compound"){
                        var kegg = ['Kegg'];
                        //check the value of geneset
                        var geneSet = $('#geneSet').val();
                        if( geneSet !== null)
                        {
                            console.log(geneSet);
                            var res =geneSet;

                            if(res.length > 0)
                            {
                                if($.inArray(res[0], kegg ) != -1 )
                                {
                                    $('#geneSet option').attr("disabled",true);
                                    $.each(kegg,function(index,value){
                                        $('#geneSet option[value=\''+value+'\']')[0].removeAttribute("disabled");
                                    });

                                }else if(res[0] === "custom"){
                                    $('#geneSet option').attr("disabled",true);
                                    $('#geneSet option[value="custom"]')[0].removeAttribute("disabled");
                                }else{

                                    $.each(kegg,function(index,value){
                                        $('#geneSet option[value=\''+value+'\']')[0].setAttribute("disabled","disabled");
                                    });
                                    $('#geneSet option[value="custom"]')[0].setAttribute("disabled","disabled");
                                }
                            }
                        }
                    }


                }
            );
