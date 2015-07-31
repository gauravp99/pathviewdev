<div class="stepsdiv" id="gset-div">
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="gageTutorial#gene_set" onclick="window.open('gageTutorial#gene_set', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            <label for="GeneSet">Gene Set:</label>
            <div class="col-sm-12">
                <input name="geneIdFile" type="file"  id="geneIdFile" hidden=""   style="margin-top: 20px;font-size: 14px;">
            </div>
        </div>
        <div class="col-sm-7">
            <select  name="geneSet[]" id="geneSet" class="styled-multiple-select"  multiple="" size="10" style="width:100%;">
                <optgroup label="KEGG">
                    <option value="sigmet.idx">signalling</option>
                    <option value="met.idx">metabolic</option>
                    <option value="sig.idx">sigmet</option>
                    <option value="dise.idx">disease</option>
                    <option value="sigmet.idx,dise.idx">all</option>
                </optgroup>
                <optgroup label="GO">
                    <option value="BP">bp</option>
                    <option value="CC">cc</option>
                    <option value="MF">mf</option>
                    <option value="BP,CC,MF">all</option>
                </optgroup>
                <option value="custom">Custom</option>
            </select>

        </div>
    </div>

</div>
<div class="stepsdiv" id="geneIdType-div">
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="gageTutorial#gene_id_type" onclick="window.open('gageTutorial#gene_id_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            <label for="geneIdType">Gene ID Type:</label>
        </div>
        <div class="col-sm-7" id="geneid">
            <select   class="styled-select" name="geneIdType" id="geneIdType" >
                <option value="entrez" @if (strcmp($geneIdType,'entrez') == 0 ) selected @endif >entrez</option>
                <option value="kegg"   @if (strcmp($geneIdType,'kegg') == 0 ) selected @endif >kegg</option>
            </select>

        </div>
    </div>

</div>
<div class="stepsdiv" id="species-div">
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="gageTutorial#species" onclick="window.open('gageTutorial#species', 'newwindow', 'width=300, height=250').focus();return false;" title="Either the KEGG code, scientific name or the common name of the target species. Species may also be 'ko' for KEGG Orthology pathways."  target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            {!!form::label('species','Species:') !!}
        </div>
        <div class="col-sm-7" id="spciesList">
            <input class="ex8" list="specieslist" name="species" id="species" value={{$species}}  autocomplete="on">
        </div>
    </div>
    <datalist id="specieslist">
        <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
        <?php
        $species = DB::table('Species')->get();
        foreach ($species as $species1) {
            echo "<option>" . $species1->species_id . "-" . $species1->species_desc . "</option>";
        }
        ?>
        <!--[if (lt IE 10)]></select><![endif]-->

    </datalist>
</div>
<div class="stepsdiv" id="ref-div">
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="gageTutorial#contorl_reference" onclick="window.open('gageTutorial#contorl_reference', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            <label for="ref">Control / Reference:</label>
        </div>

        <div class="col-sm-7">
            <input class="ex8"  name="reference"  id="reference"  value={{$reference}}> <h6 class="noteHint" >eg: 1,3,5 or NULL</h6>
            <!-- To get the number of column fields in a file -->
            <input type="text"  name="NoOfColumns" value="<% columns.length %>" hidden="" id="NoOfColumns"   >
            <select name="ref[]" id="refselect"   multiple="" size="5" style="width:100%;" ng-model='refselect' ng-show="columns.length > 0">
                <option ng-repeat="column in columns track by $index"
                        value="<% $index+1 %>">
                    <% column %>
                </option>
            </select>
        </div>
    </div>

</div>
<div class="stepsdiv" id="sample-div">
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="gageTutorial#case_sample" onclick="window.open('gageTutorial#case_sample', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            <label for="sample">Case / Sample:</label>
        </div>

        <div class="col-sm-7">
            <input class="ex8"  name="samples"  id="sample" value={{$sample}}  > <h6 class="noteHint">eg: 2,4,6 or NULL</h6>
            <select name="sample[]" id="sampleselect"  multiple="" size="5" style="width:100%;" ng-model='sampleselect' ng-show="columns.length > 0">
                <option ng-repeat="column in columns track by $index"
                        value="<% $index+1 %>">
                    <% column %>
                </option>

            </select>
        </div>
    </div>
</div>
</fieldset >
<fieldset class="step analysis-step">
    <div class="stepsdiv" id="cutoff-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#q_value_cutoff" onclick="window.open('gageTutorial#q_value_cutoff', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="cutoff">q-value Cutoff:</label>
            </div>
            <div class="col-sm-7">

                <input class="ex8"   name="cutoff"  id="cutoff" value={{$cutoff}}  placeholder="0.1">
            </div>
        </div>
    </div>
    <div class="stepsdiv" id="setSize-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#set_size" onclick="window.open('gageTutorial#set_size', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="setSize">Set Size:</label>
            </div>
            <div class="col-sm-7">
                <div class="col-sm-6">
                    Minimum: <input class="ex8" style="width:60px;"  name="setSizeMin"  id="setSizeMin" value={{$setSizeMin}}  placeholder="5">


                </div>
                <div class="col-sm-6">
                    Maximum:     <input class="ex8" style="width:60px;"  name="setSizeMax"  class="MaxGreaterThanMinCheck" data-min="setSizeMin"  id="setSizeMax" value={{$setSizeMax}} placeholder="100">
                </div>
            </div>
        </div>
    </div>
    <div class="stepsdiv" id="compare-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#compare" onclick="window.open('gageTutorial#compare', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="compare">Compare:</label>
            </div>
            <div class="col-sm-7">
                <select  name="compare"  class="styled-select" id="compare" class="compare" >
                    <option value="paired" @if (strcmp($compare,'paired') == 0 ) selected @endif >paired</option>
                    <option value="unpaired" @if (strcmp($compare,'unpaired') == 0 ) selected @endif >unpaired</option>
                    <option value="1ongroup" @if (strcmp($compare,'1ongroup') == 0 ) selected @endif>1ongroup</option>
                    <option value="as.group" @if (strcmp($compare,'as.group') == 0 ) selected @endif >as.group</option>
                </select>
            </div>
        </div>
    </div>
    <div class="stepsdiv" id="sameDir-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#two_direction_test" onclick="window.open('gageTutorial#two_direction_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="sameDir">Two-direction Test:</label>
            </div>
            <div class="col-sm-7">

                <input type="checkbox" id="sameDir" style="width: 44px;" value="true" name="test2d" @if ($test2d) checked @endif  >
            </div>
        </div>

    </div>
    <div class="stepsdiv" id="rankTest-div">

        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#rank_test" onclick="window.open('gageTutorial#rank_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="rankTest">Rank Test:</label>
            </div>
            <div class="col-sm-7">
                <input type="checkbox" id="rankTest" style="width: 44px;" value="true" name="rankTest" @if ($rankTest) checked @endif  >

            </div>
        </div>
    </div>
    <div class="stepsdiv" id="useFold-div">

        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#per_gene_score" onclick="window.open('gageTutorial#per_gene_score', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="useFold">Per Gene Score:</label>
            </div>
            <div class="col-sm-7">
                <input type="checkbox"  id="useFold" value="true"  data-off-text="t-test" data-on-text="fold" name="useFold" @if ($useFold) checked @endif>

            </div>
        </div>
    </div>
    <div class="stepsdiv" id="test-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#gene_set_test" onclick="window.open('gageTutorial#gene_set_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="test">Gene Set Test:</label>
            </div>
            <div class="col-sm-7">
                <select name="test" id="test" class="styled-select" class="compare">
                    <option value="gs.tTest" @if (strcmp($test,'gs.tTest') == 0 ) selected @endif >t-test</option>
                    <option value="gs.zTest" @if (strcmp($test,'gs.zTest') == 0 ) selected @endif >z-test</option>
                    <option value="gs.KSTest" @if (strcmp($test,'gs.KSTest') == 0 ) selected @endif  >Kolmogorov–Smirnov test</option>
                </select>
            </div>
        </div>
    </div>

    <div class="stepsdiv" id="UsePathview-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#use_pathview" onclick="window.open('gageTutorial#use_pathview', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="usePathview">Use Pathview:</label>
            </div>
            <div class="col-sm-7">
                <input type="checkbox" id="usePathview" value="true" style="width: 44px;" name="dopathview" @if ($dopathview) checked @endif >
            </div>
        </div>

    </div>
    <div class="stepsdiv" id="dataType-div" >
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="gageTutorial#data_type" onclick="window.open('gageTutorial#data_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <label for="usePathview">Data Type:</label>
            </div>
            <div class="col-sm-7" >
                <select name="dataType" class="styled-select" id="dataType" class="compare"  >
                    <option value="gene">gene</option>
                    <option value="compound">compound</option>
                </select>
            </div>
        </div>

    </div>

</fieldset>
</div>
<div  class="steps" style="margin-left:-2%">
    <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return validation()"  />
    <input type="Reset" id="reset" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset" />
</div>

</div>

</div>


<script>


$('#submit-button').click(function(){
        //$('#progress').show();
    $('#completed').hide();
});
    $('#geneIdFile').hide();
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
    });
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
    //saved species to be used in javascript
    var speciesArray = <?php echo JSON_encode($species);?> ;
    $('#gage_anal_form').validate({

        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();

            if (errors > 0) {
                $('#progress').hide();
                $("#error-message").show().text("** You missed " + errors + " field(s) or errors check and fill it to proceed.");
                $("");
            } else {
                $("#error-message").hide();
            }
        },
        rules: {
            assayData: {
                required:true,
                extension: "txt|csv"
            },
            'geneSet[]': "required",
            reference: {
                refSampleFieldValidate:true,
                refSampleSameColumnCheck:true,
                required: {
                    depends: function(element){
                        return $("#sample").val()!=""
                    }
                }
            },
            samples:{
                refSampleFieldValidate:true,
                refSampleSameColumnCheck:true,
                required: {
                    depends: function(element){
                        return $("#reference").val()!=""
                    }
                }
            },
            setSizeMin:{
                required: true,
                digits: true
            },
            setSizeMax:{
                setSize: true,
                MaxGreaterThanMinCheck:true,
                setSizeMaxDigitCheck:true
            },
            cutoff:{
                required: true,
                decimal:true
            },
            geneIdFile:{
                required:true,
                extension: "txt|csv"
            },
            species: {
                required:true,
                speciesValid:true
            },
            compare: {
                refSapleColumnLengthCheck:true
            }
        },
        messages: {
            assayData: {
                required: "* Select the Input file csv/txt file",
                extension: "* only txt and csv extensions are allowed"
            },
            geneIdFile: {
                required: "* Select the Gene ID file csv/txt file",
                extension: "* only txt and csv extensions are allowed"
            },
            'geneSet[]': "* At least one GeneSet Field option should be selected",
            reference: {
                required: "* Reference columns should be specified",
                refSampleFieldValidate: "* Input field should be Column numbers of input file separated by comma or NULL",
                refSampleSameColumnCheck: "* column numbers should not intersect with each other"
            },
            samples: {
                required: "* Sample columns should be specified",
                refSampleFieldValidate: "* Input field should be Column numbers of input file separated by comma or NULL",
                refSampleSameColumnCheck: "* column numbers should not intersect with each other"
            },
            setSizeMin: {
                required: "* Set Size Min field is required",
                digits: "* Set Size Min field must be numeric"
            },
            setSizeMax: {
                setSize: "* Set Size Max field must be numeric or INF",
                MaxGreaterThanMinCheck: "* SetSizeMax value should be greater than Min",
                setSizeMaxDigitCheck: "* Max size should only contains digits or inf to indicate infinity "
            },
            cutoff: {
                required: "* Cutoff field value is required",
                decimal: "* Cutoff field must be decimal value"
            },
            species: {
                required: "* Species field value is required",
                speciesValid: "* Entered speceis is not a valid species"
            },
            compare:{
                refSapleColumnLengthCheck: "* Since Reference and Sample columns lengths are not equal select value to be unpaired "
            }
        }/*,
        submitHandler: function(form) {
            var fd = new FormData(document.querySelector("form"));
            $.ajax({
                url:$('#gage_anal_form').attr('action'),
                type:$('#gage_anal_form').attr('method'),
                data: fd,
                success: function(data){
                    $("#resultLink").attr("href", "/gageResult?analysis_id="+data);

                    hideProgress();
                    showDoneResultMessage();
                },

                contentType: false,
                processData: false

            });
        }*/

    });
function showDoneResultMessage() {
    console.log("in show done result message");
   $('#completed').show();
}
function hideProgress() {
    console.log("in show done progress hide");
    $('#progress').hide();

}


    jQuery.validator.addMethod('refSampleFieldValidate',function(value, element){
        if(value.toLowerCase() === "null" || value.toLowerCase() === "")
        {
            return true;
        }
        else {
            return /^\d(\d?\,?\d+)*/.test(value);
        }
    },"Input field should be Column numbers of input file separated by comma");
    jQuery.validator.addMethod('decimal',function(value, element){
        return /^\d(\d?\.?\d+)*/.test(value);
    },"Cut off value should be a decimal value");
    jQuery.validator.addMethod('setSize',function(value, element){
        if(value.toLowerCase() === 'inf' || $.isNumeric(value) )
        {
            return true;
        }
        else
        {
            return false;
        }
    },"setSize Value should be neumeric or INF");
    jQuery.validator.addMethod('speciesValid',function(value, element){
        var validSpeciesFlag = false;

        if($('#geneIdType > option').length == 1  ) {
            $.each(goSpecIdBind, function (key1, value1) {
                if( key1 === value )
                {
                    validSpeciesFlag = true;
                }
            });
        }
        else{
            $.each(speciesArray, function (speciesIter, specieValue) {

                if (specieValue['species_id']  === value.split('-')[0] || (value.length == 3 && specieValue['species_id'] === value )) {
                    validSpeciesFlag = true;
                }
            });

        }
        return validSpeciesFlag;
    },"Entered Speceis is not a valid speceis");

    //jquery validation method to check if number of columns specified in  reference is always
    //equal to sample columns

    jQuery.validator.addMethod("refSapleColumnLengthCheck",function(value,element)
    {
        $refArray = $('#reference').val().replace(/\,/g," ").trim().split(" ");
        $sampleArray = $('#sample').val().replace(/\,/g," ").trim().split(" ");

        if( $refArray.length != $sampleArray.length && ($('#compare').val() === 'paired') )
        {
            return false
        }else{
            return true;
        }
    },"Since Reference and Sample columns lengths are not equal select the argument 'compare' to be unpaired");
    jQuery.validator.addMethod("refSampleSameColumnCheck", function(value, element) {


        $refArray = $('#reference').val().replace(/\,/g," ").trim().split(" ");
        $sampleArray = $('#sample').val().replace(/\,/g," ").trim().split(" ");



        function containsAny(array1, array2) {
            for (var i = 0; i < array1.length; i++) {
                for (var j = 0; j < array2.length; j++) {
                    if (array1[i] == array2[j]) return true;
                }
            }
            return false;
        }

        if (containsAny($refArray, $sampleArray)  ) {
            if(($refArray[0] =="" && $sampleArray[0] ==""))
            {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }

    },"Column numbers should not intersect with each other");

    jQuery.validator.addMethod('MaxGreaterThanMinCheck', function(value, element) {
        if(value.toLowerCase() === 'inf')
        {
            return true;
        }
        else{
            return parseInt(value) > parseInt($('#setSizeMin').val());
        }

    }, "Max must be greater than min");
    jQuery.validator.addMethod('setSizeMaxDigitCheck', function(value, element) {
        if(value.toLowerCase() === 'inf')
        {
            return true;
        }
        else{
            return $.isNumeric($("#setSizeMax").val());

        }

    }, "Max size should only contains digits or inf");

</script>




