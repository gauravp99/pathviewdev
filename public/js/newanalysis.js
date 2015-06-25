/**
 * Created by ybhavnasi on 6/9/15.
 */


$(document).ready(function () {

    //start check for the species when page loaded hide and show the pathway id list associated with it
    $(document).ready(function () {
        species = $('#species').val();
        $.ajax({
            url: "/ajax/specPathMatch",
            method: 'POST',
            dataType: "json",
            data: {'species': species},
            cache: false,
            success: function (data) {
                if (data.length > 0) {
                    $('#select-from option').attr('disabled', 'disabled');
                    $('#pathwaylist option').attr('disabled', 'disabled');
                    pathway_array = [];
                    jQuery.each(data, function () {
                        var option1 = this['pathway_id'];
                        pathway_array.push(option1);
                        $("#select-from option[value=\"" + option1 + "\"]").removeAttr('disabled');
                        $("#pathwaylist option[value=\"" + option1 + "\"]").removeAttr('disabled');
                    });
                }
            },
            error: function (data) {
                console.log("error");
            }
        });
    });
    //start check for the species when page loaded hide and show the pathway id list associated with it

    //start on each change in the species check for species and pathway list update
    $('#species').change(function () {
        /* Start Check for most used species if it is most used update the gene ID listed */
        most_flag = false;
        species = $('#species').val();

        var most_spec = ['aga', 'ath', 'bta', 'cel', 'cfa', 'dme', 'dre', 'eco', 'ecs', 'gga', 'hsa', 'mmu', 'mcc', 'pfa', 'ptr', 'rno', 'sce', 'Pig', 'ssc', 'xla'];

        for (var i = 0; i < most_spec.length; i++) {
            if (species.split("-")[0] == most_spec[i]) {
                most_flag = true;
                break;
            }
        }

        if (!most_flag) {
            $('#geneidlist option').attr('disabled', 'disabled');
            $("#geneidlist option[value='ENTREZ']").removeAttr('disabled');
            $("#geneidlist option[value='KEGG']").removeAttr('disabled');
        }
        else {
            $('#geneidlist option').removeAttr('disabled');
        }
        /* End Check for most used species if it is most used update the gene ID listed */

        //start check for the species when species id hide and show the pathway id list associated with it
        $.ajax({
            url: "/ajax/specPathMatch",
            method: 'POST',
            dataType: "json",
            data: {'species': species},
            cache: false,
            success: function (data) {
                if (data.length > 0) {
                    $('#select-from option').attr('disabled', 'disabled');
                    $('#pathwaylist option').attr('disabled', 'disabled');
                    pathway_array = [];
                    jQuery.each(data, function () {
                        var option1 = this['pathway_id'];

                        pathway_array.push(option1);
                        $("#select-from option[value=\"" + option1 + "\"]").removeAttr('disabled');
                        $("#pathwaylist option[value=\"" + option1 + "\"]").removeAttr('disabled');
                    });


                }
            },
            error: function (data) {
                console.log("error");
            }


        });
        //start check for the species when species id hide and show the pathway id list associated with it
    });
    //end on each change in the species check for species and pathway list update


    $('#selecttextfield').append(",\r\n");

    $('#reset').click(function () {
        $("#errors").empty();
        $("#errors").hide();
        $(".stepsdiv").css('background-color', '#F4F4F4');
        $("#glmt").css('background-color', '#FFFFFF');
        $("#clmt").css('background-color', '#FFFFFF');
        $("#gbins").css('background-color', '#FFFFFF');
        $("#cbins").css('background-color', '#FFFFFF');
    });

    //javascript code for the forward button adding pathway from select box to the text box
    $('#btn-add').click(function () {

        $('#select-from option:selected').each(function () {
            var val = $(this).text();
            $('#select-to').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $('#selecttextfield').val($('#selecttextfield').val() + val + ",\r\n");
        });
    });

    //javascript code for the add(+) button adding pathway from select box to the text box
    $('#btn-add1').click(function () {

        var val = $("#pathway1").val();

        if (!in_pathway_array(pathway_array, val.substring(0, 5))) {
            alert("Not a Valid pathway");
        }
        else {
            $("<option />", {'value': val, text: val}).appendTo("#select-to");
            $('#selecttextfield').val($('#selecttextfield').val() + val + ",\r\n");
        }

    });


});


//start check if the page is reviseted handled using a cookie
var dirty_bit = document.getElementById('page_is_dirty');

if (document.getElementById('page_is_dirty').value != '1') {

    document.getElementById('page_is_dirty').value = '1';
    for (var j = 0; j < parseInt(readCookie("pathidx")); j++) {
        eraseCookie("pathway" + j);
        eraseCookie("pathidx");
    }
}
else {
    if (readCookie("pathidx") === null) {

    }
    else {

        i = parseInt(readCookie("pathidx"));
        var x = document.getElementById("select-to");
        document.getElementById("select-to").innerHTML = "";
        for (var j = 0; j < i; j++) {

            var option = document.createElement("option");
            option.text = readCookie("pathway" + j);
            eraseCookie("pathway" + j);
            document.getElementById("select-to").add(option);
        }
    }
}
//end check if the page is reviseted handled using a cookie

/*checking the geneid is in the list or not */
function in_gene_array(gene, id) {
    for (var i = 0; i < gene.length; i++) {
        if (gene[i]['geneid'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}

/*checking the species is in the list or not */
function in_species_array(species, id) {
    for (var i = 0; i < species.length; i++) {
        if (species[i]['species_id'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}

/*checking the species is in the list or not */
function in_cmpd_array(cpd, id) {
    for (var i = 0; i < cpd.length; i++) {
        if (cpd[i]['cmpdid'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}

/*checking the pathway is in the list or not */
function in_pathway_array(pathway, id) {
    for (var i = 0; i < pathway.length; i++) {
        //console.log(gene[i]['geneid']);
        if (pathway[i].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}

function fileCheck() {
    $("#errors").show();
    document.getElementById('submit-button').setAttribute("data-toggle", "");
    document.getElementById('submit-button').setAttribute("data-target", "");
    var errors = document.getElementById("errors");
    errors.innerHTML = "";
    var selectBox = document.getElementById("select-to");
    var geneid = document.getElementById("geneid");
    var cpdid = document.getElementById("cpdid");
    var offset = document.getElementById("offset");
    var suffix = document.getElementById("suffix");
    var species = document.getElementById("species");
    var glmt = document.getElementById("glmt");
    var gbins = document.getElementById("gbins");
    var clmt = document.getElementById("clmt");
    var cbins = document.getElementById("cbins");
    var glow = document.getElementById("glow");
    var gmid = document.getElementById("gmid");
    var ghigh = document.getElementById("ghigh");
    var gfilediv = document.getElementById("gfile-div");
    var pathway_text = document.getElementById("selecttextfield");
    var offset = document.getElementById("offset");

    var most_spec = ['aga', 'ath', 'bta', 'cel', 'cfa', 'dme', 'dre', 'eco', 'ecs', 'gga', 'hsa', 'mmu', 'mcc', 'pfa', 'ptr', 'rno', 'sce', 'Pig', 'ssc', 'xla'];
    var most_flag = false;


    /**
     * Trim the text field for spaces in all input fields not required for suffix already handled
     */
    document.getElementById("selecttextfield").value = document.getElementById("selecttextfield").value.trim();
    geneid.value = geneid.value.trim();
    cpdid.value = cpdid.value.trim();
    species.value = species.value.trim();
    document.getElementById("offset").value = document.getElementById("offset").value.trim();
    clmt.value = clmt.value.trim();
    cbins.value = cbins.value.trim();
    glmt.value = glmt.value.trim();
    gbins.value = gbins.value.trim();


    var j;
    var f = document.getElementById("gfile");
    var f1 = document.getElementById("cpdfile");
    var regex = new RegExp("(.*?)\.(txt|csv)$");
    var error = false;


    document.getElementById("nacolor").value = document.getElementById("nacolor").value.replace(/\W/g, "");
    if (f.value.length < 4 && f1.value.length < 4) {

        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Upload the Gene Data or Compound Data file."));
        errors.appendChild(li);
        document.getElementById('gfile-div').style.backgroundColor = "#DA6666";
        error = true;

    }
    else if (f.value.length > 4 && !regex.test(document.getElementById('gfile').value)) {
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Uploaded Gene Data file extension is not supported. Supports only txt/csv."));
        errors.appendChild(li);
        gfilediv.style.backgroundColor = "#DA6666";
        error = true;


    }
    else {
        document.getElementById('gfile-div').style.backgroundColor = "#F4F4F4";

    }

    if (f1.value.length > 4 && !regex.test(document.getElementById('cpdfile').value)) {
        var myElement = document.getElementById("cfile-div");
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Uploaded Compound Data file extension is not supported. Supports only txt/csv."));
        errors.appendChild(li);
        myElement.style.backgroundColor = "#DA6666";
        error = true;

    }

    /*------------------------Pathway ID check ------------------------------*/
    if (document.getElementById('selecttextfield').value.length < 5) {

        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("At least one Pathway should be selected."));
        errors.appendChild(li);
        error = true;


    }
    else if (!in_pathway_array(pathway_array, document.getElementById('selecttextfield').value.substring(0, 5))) {
        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Entered Pathway ID is not a valid pathway."));
        errors.appendChild(li);
        error = true;

    }
    else {
        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#F4F4F4";

    }
    /*------------------------Pathway ID check ------------------------------*/

    /*------------------------Gene ID check ------------------------------*/
    if (geneid.value == "") {

        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene ID Type Cannot be empty."));
        errors.appendChild(li);
        error = true;


    }
    else if (!in_gene_array(gene_array, geneid.value)) {

        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#DA6666";

        var li = document.createElement("li");

        li.appendChild(document.createTextNode("Gene ID Type is not valid."));
        errors.appendChild(li);
        error = true;


    }
    else {
        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }
    /*------------------------Gene ID check ------------------------------*/


    /*------------------------Compound ID check ------------------------------*/
    if (cpdid.value == "") {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound ID Type Cannot be empty"));
        errors.appendChild(li);
        error = true;

    }
    else if (!in_cmpd_array(cmpd_array, cpdid.value)) {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Entered Compound ID Type does not exist"));
        errors.appendChild(li);
        error = true;
    }
    else {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }
    /*------------------------Gene ID check ------------------------------*/

    /*------------------------Suffix check ------------------------------*/
    if (suffix.value == "") {

        var myElement = document.getElementById("suffix-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Output Suffix Cannot be left empty"));
        errors.appendChild(li);
        error = true;
    }
    else {

        suffix.value = suffix.value.replace(/\W/g, "-");
        if (suffix.value.length > 100) {
            suffix.value = suffix.value.substring(0, 100);

        }
    }
    /*------------------------Gene ID check ------------------------------*/

    /*------------------------Species check ------------------------------*/
    if (species.value == "") {
        var myElement = document.getElementById("species-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Species Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (species.value.length >= 3 || species.value.substring(0, 2) == 'ko') {
        if (species.value.substring(0, 2) == 'ko') {
            var myElement = document.getElementById("species-div");
            myElement.style.backgroundColor = "#F4F4F4";
        }
        else if ((!in_species_array(species_array, species.value.split("-")[0]))) {
            var myElement = document.getElementById("species-div");
            myElement.style.backgroundColor = "#DA6666";
            var li = document.createElement("li");
            li.appendChild(document.createTextNode("Species: '" + species.value.split("-")[0] + "' is not valid"));
            errors.appendChild(li);
            error = true;
        }
        else {

            for (var i = 0; i < most_spec.length; i++) {
                if (species.value.split("-")[0] == most_spec[i]) {

                    most_flag = true;
                    break;

                }
            }


            if (!most_flag && ( geneid.value.toUpperCase() != 'ENTREZ' && geneid.value.toUpperCase() != 'KEGG' )) {
                alert("For Species: " + species.value + " Gene ID Type should be either 'ENTREZ' or 'KEGG'.");
                var li = document.createElement("li");
                li.appendChild(document.createTextNode("For Species: " + species.value + " Gene ID Type  should be either 'ENTREZ' or 'KEGG'."));
                errors.appendChild(li);
                error = true;

            }

            var myElement = document.getElementById("species-div");
            myElement.style.backgroundColor = "#F4F4F4";

        }
    }
    else {
        var myElement = document.getElementById("species-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Species is not valid"));
        errors.appendChild(li);
        error = true;

    }
    /*------------------------Species check ------------------------------*/


    /*------------------------Compound Label offset check ------------------------------*/
    if (offset.value == "") {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound Label Offset value cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (!(/^-?\d*.?\d+$/.test(offset.value))) {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound Label Offset  Value must be numeric"));
        errors.appendChild(li);
        error = true;

    }
    else {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }
    /*------------------------Compound Label offset check------------------------------*/


    /*------------------------Gene Limit check------------------------------*/
    if (glmt.value == "") {
        glmt.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene Limit value cannot be left empty"));
        errors.appendChild(li);
        error = true;
    }
    else {
        var str_array = glmt.value.split(',');
        if (str_array.length > 1) {
            if ((!(/^-?\d*.?\d+$/.test(str_array[0]))) || (!(/^-?\d*.?\d+$/.test(str_array[1])))) {
                glmt.style.backgroundColor = "#DA6666";
                var li = document.createElement("li");
                li.appendChild(document.createTextNode("Gene Limit Value must be numeric values separated by comma"));
                errors.appendChild(li);
                error = true;

            }
            else {
                glmt.style.backgroundColor = "#FFF";
            }

        }
        else {
            if (!(/^-?\d*.?\d+$/.test(glmt.value))) {
                glmt.style.backgroundColor = "#DA6666";
                var li = document.createElement("li");
                li.appendChild(document.createTextNode("Gene Limit Value must be numeric"));
                errors.appendChild(li);
                error = true;

            }

            else {
                glmt.style.backgroundColor = "#FFF";
            }

        }
    }
    /*------------------------Gene Limit check------------------------------*/

    /*------------------------Gene Bins check------------------------------*/
    if (gbins.value == "") {

        gbins.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene Bins Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (!(/^\d+$/.test(gbins.value))) {
        gbins.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene bins Value must be numeric"));
        errors.appendChild(li);
        error = true;

    }
    else {
        gbins.style.backgroundColor = "#FFF";

    }
    /*------------------------Gene Bins check------------------------------*/

    /*------------------------Compound Limit check------------------------------*/
    if (clmt.value == "") {

        clmt.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound limit  Cannot be left empty"));
        errors.appendChild(li);
        error = true;

    }
    else {
        var str_array = clmt.value.split(',');
        if (str_array.length > 1) {
            if ((!(/^-?\d*.?\d+$/.test(str_array[0]))) || (!(/^-?\d*.?\d+$/.test(str_array[1])))) {
                clmt.style.backgroundColor = "#DA6666";
                var li = document.createElement("li");
                li.appendChild(document.createTextNode("Compound Limit Value must be numeric values separated by comma"));
                errors.appendChild(li);
                error = true;

            }
            else {
                clmt.style.backgroundColor = "#FFF";
            }

        }
        else {
            if (!(/^-?\d*.?\d+$/.test(clmt.value))) {
                clmt.style.backgroundColor = "#DA6666";
                var li = document.createElement("li");
                li.appendChild(document.createTextNode("Compound Limit value should be numeric"));
                errors.appendChild(li);
                error = true;
            }
            else {
                clmt.style.backgroundColor = "#FFF";
            }
        }
    }
    /*------------------------Compound Limit check------------------------------*/

    /*------------------------Compound Bins check------------------------------*/
    if (cbins.value == "") {

        cbins.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound Bins Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (!(/^\d+$/.test(cbins.value))) {
        cbins.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound Bins values must be numeric"));
        errors.appendChild(li);
        error = true;
    }
    else {
        cbins.style.backgroundColor = "#FFF";
    }
    /*------------------------Compound Limit check------------------------------*/


    //If error occurs then error div on analysis page is shown and
    if (error) {
        error = true;
        errors.removeAttribute("hidden");
        return false;

    }
    else {
        document.getElementById('submit-button').setAttribute("data-toggle", "modal");
        document.getElementById('submit-button').setAttribute("data-target", "#myModal");
        $("#errors").hide();

        for (var i = 0; i < selectBox.options.length; i++) {

            selectBox.options[i].selected = true;
        }
        var i = selectBox.options.length;


        if (i > 0) {
            createCookie("pathidx", selectBox.options.length, 1);
            for (var j = 0; j <= i; j++) {
                createCookie("pathway" + j, "" + selectBox.options[j].value);
            }

        }
        else {
            i = parseInt(readCookie("pathidx"));
            if (i === null) {

            }
            else {
                for (var j = 1; j < parseInt(readCookie("pathidx")); j++) {
                    eraseCookie("pathway" + j);
                    eraseCookie("pathidx");
                }
            }

        }


    }


}

//Cookie functions are used to handle creating and reading and erase cookies
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}


