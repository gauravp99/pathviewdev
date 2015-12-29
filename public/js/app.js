/**
 * Created by ybhavnasi on 7/7/15.
 */

var app = angular.module('GageApp',[], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});
app.controller('analysisController',function($scope,$timeout) {
    var outScope = $scope;
    $scope.delete = function(){
        $('.tempColumn').remove();
    };

    $scope.showContent = function($fileContent)
    {
        $('#edit').show();
        $scope.fileReset = function(){
            $('#geneMenu').hide();
            $('#GeneClearFile').hide();
            $scope.columns = "";
            $scope.geneRefSelect = "";
            $scope.Genecolumns = "";
            $scope.samSelect = "";
        };
        $scope.reset = function(e){
            console.log("reset function of the file called");
            e = $('#assayData');
            console.log("reset function of the file called");
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();
            $(".form-control").val("");
            $('#clearFile').hide();
            $('#menu').hide();
        };


        if($scope.filename)
            $scope.content = $fileContent;
        console.log($scope.filetype);
        if($scope.filetype === 'text/csv' || $scope.fileExtension === 'csv' )
        {
            $scope.content = $fileContent.split("\n")[0].replace(" ", "").split(",").length;
            $scope.columns = $fileContent.split("\n")[0].replace(" ", "").split(",");
            var columns1 = $fileContent.split("\n")[0].split(",");
            var columns2 = $fileContent.split("\n")[1].split(",");
            console.log(columns1.length+" "+columns2.length);
            if(columns1.length === columns2.length)
            {
                $scope.columns.splice(0, 1);
            }

        }

        else if($scope.filetype === 'text/plain')
        {

            $scope.columns = $fileContent.split("\n")[0].split("\t");
            var columns1 = $fileContent.split("\n")[0].split("\t");
            var columns2 = $fileContent.split("\n")[1].split("\t");

            if(columns1.length === columns2.length)
            {
                $scope.columns.splice(0,1);
            }
            $scope.sample=[];
            $scope.ref=[];

        }
        else
        {
            alert("File uploded is not in specified format");

        }
        var geneIDArray = [];
        $.each($fileContent.split("\n"),function($index,$line){

                geneIDArray.push($line.split("\t")[0]);


        });


        $scope.content = $fileContent.split("\n")[0];

        $scope.reference = "";
        $scope.sample = "";

    };

});


app.controller('ExampleAnalysisController1',function($scope) {
    $scope.columns = ['HN_1','DCIS_1','HN_2','DCIS_2','HN_3','DCIS_3','HN_4','DCIS_4','HN_5','DCIS_5','HN_6','DCIS_6'];
});

app.directive('onReadFile', function($parse){
    return {
        restrict: 'A',
        scope: false,
        link: function(scope, element, attrs) {



            var fn = $parse(attrs.onReadFile);
            element.on('change',function(onChangeEvent){
                var reader = new FileReader();
                reader.onload = function(onLoadEvent) {

                    scope.$apply(function(){

                        fn(scope,{$fileContent:onLoadEvent.target.result});
                    });
                };
                scope.fileName1 = (onChangeEvent.srcElement || onChangeEvent.target).files[0].name;
                scope.fileExtension = scope.fileName1.substring(scope.fileName1.length-3,scope.fileName1.length);
                scope.filetype =( (onChangeEvent.srcElement || onChangeEvent.target).files[0].type);
                reader.readAsText((onChangeEvent.srcElement || onChangeEvent.target).files[0]);
                $('#menu').trigger('click');
            }) ;

        }
    };
});

