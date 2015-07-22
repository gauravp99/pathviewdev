function validation(){
    console.log('started validation');
    var assayData = $('#assayData');
    var geneSet = $('#geneSet');
    var geneIdType = $('#geneIdType');
    var reference = $('#reference');
    var sample = $('#sample');
    var setSizeMin = $('#setSizeMin');
    var setSizeMax = $('#setSizeMax');
    var compare = $('#compare');
    var sameDir = $('#sameDir');
    var rankTest = $('#rankTest');
    var useFold = $('#useFold');
    var test = $('#test');
    var geneSetlength = $('#geneSet option:selected').length;
    var refSetlength =($("#ref option:selected").length);
    var samplelength = ($("#sample option:selected").length);
    console.log("check if the min is neumeric");
    if (setSizeMin.val() !== "" && !$.isNumeric(setSizeMin.val())) {

        $("label#setSizeMinError").show(); //Show error
        $("input#setSizeMin").focus(); //Focus on field
        return false;
    }



}


$(document).ready(function () {


        $('#dataType-div').toggle();
        $("[name='dopathview']").bootstrapSwitch();
        $("[name='test2d']").bootstrapSwitch();
        $("[name='rankTest']").bootstrapSwitch();
        $("[name='useFold']").bootstrapSwitch();

        $('input[name="dopathview"]').on('switchChange.bootstrapSwitch', function(event, state) {
            $('#dataType-div').toggle();
        });






        //hide the input type file

        $('#geneIdFile').hide();
        var keggGeneIdType = {"entrez":"entrez",
            "kegg":"kegg"
        }
        var goSpecIdBind = {"Anopheles":"eg",
            "Arabidopsis":"tair",
            "Bovine":"eg",
            "Worm":"eg",
            "Canine":"eg",
            "Fly":"eg",
            "Zebrafish":"eg",
            "E coli strain K12":"eg",
            "E coli strain Sakai":"eg",
            "Chicken":"eg",
            "Human":"eg",
            "Mouse":"eg",
            "Rhesus":"eg",
            "Malaria":"orf",
            "Chimp":"eg",
            "Rat":"eg",
            "Yeast":"orf",
            "Pig":"eg",
            "Xenopus":"eg"
        };

        var goSpecies = {'Anopheles':'Anopheles',
            'Arabidopsis':'Arabidopsis',
            'Bovine':'Bovine',
            'Worm':'Worm',
            'Canine':'Canine',
            'Fly':'Fly',
            'Zebrafish':'Zebrafish',
            'E coli strain K12':'E coli strain K12',
            'E coli strain Sakai':'E coli strain Sakai',
            'Chicken':'Chicken',
            'Human':'Human',
            'Mouse':'Mouse',
            'Rhesus':'Rhesus',
            'Malaria':'Malaria',
            'Chimp':'Chimp',
            'Rat':'Rat',
            'Yeast':'Yeast',
            'Pig':'Pig',
            'Xenopus':'Xenopus'
        };
var GogetSetChange = false;
        var KegggetSetChange = false;
        var speciesChange = false;
        console.log("in javascript function");

        $('#species').change(function () {
                speciesChange = true;
                if ($('#geneIdType > option').length == 1) {
                    $('#geneIdType').empty();
                    $('#geneIdType').append($("<option></option>")
                        .attr("value", goSpecIdBind[$('#species').val()]).text(goSpecIdBind[$('#species').val()]));
                }
            }
        );

        $('#refselect').change(function () {
            console.log("in ref change seleect");
            var ref_selected_text;
            var noOfColumns = $('#NoOfColumns').val();
            for (var j = 1; j <= noOfColumns; j++) {
                ref_selected_text = "";
                $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
            }
            for (var i = 0; i < $(this).val().length; i++) {

                var selected = $(this).val()[i];
                ref_selected_text = ref_selected_text + (selected) + ",";
                $("#sampleselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
            }
            $('#reference').val(ref_selected_text);
        });

        $('#sampleselect').change(function () {
            console.log("in sample change seleect");
            var noOfColumns = $('#NoOfColumns').val();
            var sample_selected_text;
            for (var j = 1; j <= noOfColumns; j++) {
                sample_selected_text = "";
                $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
            }
            for (var i = 0; i < $(this).val().length; i++) {

                var selected = $(this).val()[i];
                sample_selected_text = sample_selected_text + (selected) + ",";
                $("#refselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
            }

            $('#sample').val(sample_selected_text);
        });

        $('#reference').bind("change paste keyup", function () {
            console.log("reference text change detected");
            var refrence_text = $('#reference').val();
            var ref_array = refrence_text.split(",");
            var noOfColumns = $('#NoOfColumns').val();
            for (var j = 1; j <= noOfColumns; j++) {

                $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                $("#refselect option[value=\"" + j + "\"]")[0].removeAttribute('selected');
                $("#sampleselect option[value=\"" + j + "\"]")[0].removeAttribute('selected');
            }
            for (var i = 0; i < ref_array.length; i++) {

                if ($("#sampleselect option[value=\"" + ref_array[i] + "\"]")[0] != null)
                    $("#sampleselect option[value=\"" + ref_array[i] + "\"]")[0].setAttribute('disabled', 'disabled');
                if ($("#refselect option[value=\"" + ref_array[i] + "\"]")[0] != null) {
                    $("#refselect option[value=\"" + ref_array[i] + "\"]")[0].removeAttribute('disabled');
                    $("#refselect option[value=\"" + ref_array[i] + "\"]")[0].setAttribute("selected", "selected");
                }
            }

        });

        $('#sample').change(function () {
            var sample_text = $('#sample').val();
            var sample_array = sample_text.split(",");
            for (var i = 0; i < sample_array.length; i++) {

                $("#sampleselect option[value=\"" + sample_array[i] + "\"]")[0].setAttribute('disabled', 'disabled');
                $("#refselect option[value=\"" + sample_array[i] + "\"]")[0].removeAttribute('disabled');
                $("#refselect option[value=\"" + sample_array[i] + "\"]")[0].setAttribute("selected", "selected");

            }
        });

        //javascript code to make sure clicking on the option last option unselect it also make all the other options active
        var currentSelect = $("#geneSet option:selected").index();

        $("#geneSet option").click(function() {
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

            //console.log($("#geneSet option[value="+$(this).val()[$(this).val().length - 1]+"]")[0]);
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
                    if(!KegggetSetChange) {
                        GogetSetChange = false;
                        KegggetSetChange = true;

                        $('#geneIdType').empty();

                        $.each(keggGeneIdType, function (value, key) {
                            $('#geneIdType').append($("<option></option>")
                                .attr("value", value).text(key));
                        });

                        $('#specieslist').empty();
                        if(!speciesChange)
                        $('#species').val('hsa-Homo sapiens');
                        $.each(speciesArray, function (speciesIter, specieValue) {
                            $('#specieslist').append($("<option></option>")
                                .attr("value", specieValue['species_id'] + "-" + specieValue['species_desc']).text(specieValue['species_id'] + "-" + specieValue['species_desc']));
                        });
                    }


                    $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='custom']")[0].setAttribute('disabled', 'disabled');

                }
                else if ($selected === "BP" || $selected === "CC" || $selected === "MF" || $selected === "BP,CC,MF") {

                    $('#geneIdType').show();
                    $('#geneIdFile').hide();

                    if(!GogetSetChange) {
                        $('#geneIdType').empty();
                        getSetChange = true;
                        KegggetSetChange = false;
                        $('#geneIdType').append($("<option></option>")
                            .attr("value", "eg").text("eg"));
                        if(!speciesChange)
                        $('#species').val('Human');

                        $('#specieslist').empty();

                        $.each(goSpecies, function (value, key) {
                            $('#specieslist').append($("<option></option>")
                                .attr("value", value).text(key));
                        });
                    }

                    $("#geneSet option[value='sig.idx']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='met.idx']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='sigmet.idx']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='dise.idx']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='sigmet.idx,dise.idx']")[0].setAttribute('disabled', 'disabled');
                    $("#geneSet option[value='custom']")[0].setAttribute('disabled', 'disabled');
                }
                else {
                    $('#geneIdType').empty();

                    $('#geneIdType').append($("<option></option>")
                        .attr("value", "custom").text("custom"));
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