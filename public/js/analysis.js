    /**
     * Created by ybhavnasi on 6/9/15.
     */


    $(document).ready(function () {



        document.getElementById('submit-button').removeAttribute("data-toggle");
        document.getElementById('submit-button').removeAttribute("data-target");
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
                species = $('#species').val();
                $.ajax({
                    url: "/ajax/specGeneIdMatch",
                    method: 'POST',
                    dataType: "json",
                    data: {'species': species},
                    cache: false,
                    success: function (data) {
                        if (data.length > 0) {

                            console.log("success from geneidspecies match ajaz call");
                            $('#geneidlist').empty();
                            console.log("success empty the geneid list" + data);
                            $.each(data, function () {
                                $('#geneidlist').append("<option value=" + this['geneid'] + ">" + this['geneid'] + "</option>");
                                console.log(this['geneid']);
                            });


                        }
                    },
                    error: function (data) {
                        console.log("error");
                    }
                });
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
            console.log("in button add function");

            if ($('#pathwayList').val().split('\n').length == 1) {
                $('#pathwayList').val($('#pathwayList').val() + "\t\r\n");
            }
            $('#select-from option:selected').each(function () {
                var val = $(this).text();
                $('#select-to').append("<option value='" + $(this).val() + "'>" + $(this).text() + "</option>");
                $('#pathwayList').val($('#pathwayList').val() + val + "\t\r\n");
            });
        });

        //javascript code for the add(+) button adding pathway from select box to the text box
        $('#btn-add1').click(function () {


            if ($('#pathwayList').val().split('\n').length == 1) {
                $('#pathwayList').val($('#pathwayList').val() + "\t\r\n");
            }
            var val = $("#pathway1").val();

            console.log(pathway_array[0]);
            console.log($.inArray(val,pathway_array));
            if ($.inArray(val,pathway_array)> -1) {
                $('#pathwayList').val($('#pathwayList').val() + val + ",\r\n");
            }
            else {
                alert("Not a valid Pathway");
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
            if (gene[i]['gene_id'].toLowerCase() === id.toLowerCase()) {
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
            if (cpd[i]['compound_id'].toLowerCase() === id.toLowerCase()) {
                return true;
            }
        }
        return false;
    }

    /*checking the pathway is in the list or not */
    function in_pathway_array(pathway, id) {
        for (var i = 0; i < pathway.length; i++) {

            if (pathway[i] === id) {
                return true;
            }
        }
        return false;
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