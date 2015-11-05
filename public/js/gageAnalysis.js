function validation() {

    var assayData = $('#assayData');
    var geneSet = $('#geneSet');
    var geneIdType = $('#geneIdType');
    var reference = $('#reference');
    var sample = $('#sample');


    var compare = $('#compare');
    var sameDir = $('#sameDir');
    var rankTest = $('#rankTest');
    var useFold = $('#useFold');
    var test = $('#test');
    var geneSetlength = $('#geneSet option:selected').length;
    var refSetlength = ($("#ref option:selected").length);
    var samplelength = ($("#sample option:selected").length);



}





//select box allowing to select multiple option without pressing control
$(document).ready(function () {
        $('#graphics').hide();
        $('#coloration').hide();
        $('#dataType-div').toggle();
        $('#bins-div').toggle();
        $('#pathviewSettings-div').toggle();
        $("[name='dopathview']").bootstrapSwitch();
        $("[name='test2d']").bootstrapSwitch();
        $("[name='rankTest']").bootstrapSwitch();
        $("[name='useFold']").bootstrapSwitch();
        $("[name='useFold']").setOnLabel = "setOnLabel";
        $("[name='useFold']").bootstrapSwitch.defaults.setOffLabel = "setOnLabel";
        $('input[name="dopathview"]').on('switchChange.bootstrapSwitch', function (event, state) {
            $('#dataType-div').toggle();
            if($('#bins-div'))
            {
                $('#bins-div').toggle();
            }
            $('#pathviewSettings-div').toggle();
        });

    $('#pathviewSettings').change(function () {
        var pathviewSettingval = $('#pathviewSettings').val();
        if(pathviewSettingval =="customize")
        {
            $( "#graphicsA" ).trigger( "click" );
            $('#graphics').show();
            $('#coloration').show();
            $('#graphics').addClass('selected');
            $('#analysis').removeClass('selected');

        }
        else
        {
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


        $('#species').change(function () {
                $('#geneIdFile').hide();
                var geneidType = $('#geneIdType').val();
                var val = $('#species').val();
                $.each(goSpeciesArray,function(index,value){
                    if(goSpeciesArray[index]['Go_name'].toLowerCase() === val.toLowerCase())
                    {

                      $('#species').val(goSpeciesArray[index]['species_id']+'-'+goSpeciesArray[index]['species_desc']+'-'+goSpeciesArray[index]['Go_name']);
                    }
                });

                var i = 0;
                var goSpeciesMatch= false;

                while(i < goSpeciesArray.length )
                {
                    if(goSpeciesArray[i]['species_id'] == $('#species').val().split("-")[0] )
                    {

                        goSpeciesMatch = true;
                    }

                    i++;
                }

                if(!goSpeciesMatch)
                {
                    if( $('#species').val().split("-")[0] === "custom")
                    {
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
                    }else {

                        $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                        $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                        $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
                        $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');

                    }
                }

                else
                {
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


                }
                else {
                    $("#geneSet option[value='dise.idx']")[0].removeAttribute('disabled');
                    $.each(speciesdiseaseArray, function (speciesIter, specieValue) {
                        if (specieValue['species_id'] === $('#species').val().split('-')[0]) {
                            $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                            return false;
                        }
                    });
                }
                $("#geneIdType option[value=\""+geneidType + "\"]")[0].setAttribute('selected','selected');
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

            } else {
                currentSelect = $(this).index();
            }
        });

        $('#geneSet').change(function () {


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
                    $('#geneIdType').show();
                    $('#geneIdFile').hide();
                    if (!KegggetSetChange) {
                        GogetSetChange = false;
                        KegggetSetChange = true;


                        $('#specieslist').empty();

                       /* $('#species').val('hsa-Homo sapiens-Human');*/

                    }
                   /* $('#geneIdType').empty();

                    $.each(keggGeneIdType, function (value, key) {
                        $('#geneIdType').append($("<option></option>")
                            .attr("value", value).text(key));
                    });*/
                    $.each(speciesArray, function (speciesIter, specieValue) {

                            if(specieValue['species_common_name'] == null)
                            {
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

                    $('#geneIdType').show();
                    $('#geneIdFile').hide();

                    if (!GogetSetChange) {
                        GogetSetChange = true;
                        KegggetSetChange = false;
                        /*$('#species').val('hsa-Homo sapiens-Human');*/
                    }
                  /*  $('#geneIdType').empty();
                    $('#geneIdType').append($("<option></option>")
                        .attr("value", "Entrez").text("Entrez"));*/


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
                    $('#specieslist').empty();
                    $('#specieslist').append($("<option></option>").attr("value", "custom").text("custom"));
                    $('#species').val('custom');

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
        });
    }
);

