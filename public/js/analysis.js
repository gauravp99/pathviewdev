/**
 * Created by ybhavnasi on 6/9/15.
 */


$(document).ready(function () {
    $('#selecttextfield').append(",\r\n");
    $('#reset').click(function () {
        $('#select-to')
            .find('option')
            .remove()
            .end()
            .append('<option value="00010-Glycolysis / Gluconeogenesis">00010-Glycolysis / Gluconeogenesis</option>')
            .val('whatever')
        ;
    });

    $('#btn-add').click(function () {

        $('#select-from option:selected').each(function () {

            var val = $(this).val();

            $('#select-to').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
            $('#selecttextfield').val($('#selecttextfield').val() + val + ",\r\n");


        });
    });
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
    $('#btn-remove').click(function () {
        $('#select-to option:selected').each(function () {

            $(this).remove();
        });
    });

});

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





/*checking the geneid is in the list or not */

function in_gene_array(gene, id) {
    for (var i = 0; i < gene.length; i++) {
        //console.log(gene[i]['geneid']);
        if (gene[i]['geneid'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}
function in_species_array(gene, id) {
    for (var i = 0; i < gene.length; i++) {
        //console.log(gene[i]['geneid']);
        if (gene[i]['species_id'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}
function in_cmpd_array(gene, id) {
    for (var i = 0; i < gene.length; i++) {
        //console.log(gene[i]['geneid']);
        if (gene[i]['cmpdid'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}
function in_pathway_array(gene, id) {
    for (var i = 0; i < gene.length; i++) {
        //console.log(gene[i]['geneid']);
        if (gene[i]['pathway_id'].toLowerCase() === id.toLowerCase()) {
            return true;
        }
    }
    return false;
}
function fileCheck() {

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


    var errors = document.getElementById("errors");
    errors.innerHTML = "";


    var j;
    var regex = new RegExp("(.*?)\.(txt|csv)$");
    var error = false;

    document.getElementById("nacolor").value = document.getElementById("nacolor").value.replace(/\W/g, "");
    if (!document.getElementById('gcheck').checked && !document.getElementById('cpdcheck').checked) {

        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene data or compound data any one of them should be checked "));
        errors.appendChild(li);
        document.getElementById('gfile-div').style.backgroundColor = "#DA6666";
        document.getElementById('cfile-div').style.backgroundColor = "#DA6666";
        error = true;


    }
    else {
        document.getElementById('gfile-div').style.backgroundColor = "#F4F4F4";
        document.getElementById('cfile-div').style.backgroundColor = "#F4F4F4";

    }


    if (document.getElementById('selecttextfield').value.length < 5) {

        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("At least one Pathway should be selected"));
        errors.appendChild(li);
        error = true;


    }
    else if (!in_pathway_array(pathway_array, document.getElementById('selecttextfield').value.substring(0, 5))) {
        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Pathway enetered is not valid pathway"));
        errors.appendChild(li);
        error = true;

    }
    else {
        var myElement = document.getElementById("pat-select");
        myElement.style.backgroundColor = "#F4F4F4";

    }


    if (geneid.value == "") {

        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene id Cannot be empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (!in_gene_array(gene_array, geneid.value)) {

        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#DA6666";

        var li = document.createElement("li");

        li.appendChild(document.createTextNode("Gene id is not valid"));
        errors.appendChild(li);
        error = true;


    }
    else {
        var myElement = document.getElementById("geneid-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }


    if (document.getElementById('cpdcheck').checked && cpdid.value == "") {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound ID Cannot be empty"));
        errors.appendChild(li);
        error = true;


    }
    if ((!in_cmpd_array(cmpd_array, cpdid.value) && cpdid.value != "") || cpdid.value == "") {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Entered Compound ID does not exist"));
        errors.appendChild(li);
        error = true;
    }
    else {
        var myElement = document.getElementById("cpdid-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }

    if (suffix.value == "") {

        var myElement = document.getElementById("suffix-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Suffix Cannot be left empty"));
        errors.appendChild(li);
        error = true;
    }
    else {

        suffix.value = suffix.value.replace(/\W/g, "-");
    }

    if (species.value == "") {
        var myElement = document.getElementById("species-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Species Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    else if (species.value.length >= 3) {
        if (!in_species_array(species_array, species.value.substring(0, 3))) {
            var myElement = document.getElementById("species-div");
            myElement.style.backgroundColor = "#DA6666";
            var li = document.createElement("li");
            li.appendChild(document.createTextNode("Species is not valid"));
            errors.appendChild(li);
            error = true;
        }
        else {
            var myElement = document.getElementById("species-div");
            myElement.style.backgroundColor = "#F4F4F4";

        }
    }
    else {
        var myElement = document.getElementById("species-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Species is not valid usually having minimum 3 characters"));
        errors.appendChild(li);
        error = true;

    }
    if (offset.value == "") {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Offset Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
    if (glmt.value == "") {

        glmt.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Gene Limit Cannot be left empty"));
        errors.appendChild(li);
        error = true;


    }
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
    if (clmt.value == "") {

        clmt.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound limit  Cannot be left empty"));
        errors.appendChild(li);
        error = true;

    }
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
            li.appendChild(document.createTextNode("Compound limit value should be neumeric"));
            errors.appendChild(li);
            error = true;
        }
        else {
            clmt.style.backgroundColor = "#FFF";
        }
    }

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
        li.appendChild(document.createTextNode("Compound Bins values must be neumeric"));
        errors.appendChild(li);
        error = true;
    }
    else {
        cbins.style.backgroundColor = "#FFF";
    }
    if (!(/^-?\d*.?\d+$/.test(offset.value))) {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#DA6666";
        var li = document.createElement("li");
        li.appendChild(document.createTextNode("Compound level offset  Value must be numeric"));
        errors.appendChild(li);
        error = true;

    }
    else {
        var myElement = document.getElementById("offset-div");
        myElement.style.backgroundColor = "#F4F4F4";

    }


    if (error) {
        error = true;
        errors.removeAttribute("hidden");
        return false;

    }
    else {
        document.getElementById('submit-button').setAttribute("data-toggle", "modal");
        document.getElementById('submit-button').setAttribute("data-target", "#myModal");


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
function colorCheck() {
    document.getElementById("glow").value = "#00FF00";
    /*  $glow = "#00FF00";
     $gmid = "#D3D3D3";
     $ghigh = "#FF0000";
     $clmt = 1;
     $cbins = 10;
     $clow = "#0000FF";
     $cmid = "#D3D3D3";
     $chigh = "#FFFF00";*/
}
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

function reset() {

}
