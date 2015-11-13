@extends('app')
@section('content')
    <h1 class="page-header" style="margin-left: 40%;">Result </h1>

    <div class="col-sm-12">
        <div class="col-sm-8">

            <p class="alert alert-info">Note:  Nodes in native KEGG view graphs are hyperlinked.</p>
    <?php
            $anal_id = $_GET['analyses'];
            if (Auth::user()) {
                $user_id = Auth::user()->id;

                $val1 = DB::select(DB::raw("select analysis_id from analysis where id = $user_id  and analysis_id ='$anal_id'"));
            } else {
                $val1 = DB::select(DB::raw("select analysis_id from analysis where id = 0  and analysis_id ='$anal_id'"));
            }
            if(sizeof($val1) == 0 && !isset($_GET['email']))
            {
                echo "<h1 class='alert alert-danger'> Alert You don't have access to this analysis </h1>";

            }
            else{
    function extract_unit($oneByThreeing, $start, $end)
    {
        $pivotFlag = stripos($oneByThreeing, $start);
        $oneByThree = substr($oneByThreeing, $pivotFlag);
        $twoByThree = substr($oneByThree, strlen($start));
        $second_pos = stripos($twoByThree, $end);
        $threeByThree = substr($twoByThree, 0, $second_pos);
        $unit = trim($threeByThree);
        return $unit;
    }
    function cURL($url, $header = NULL, $cookie = NULL, $p = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_NOBODY, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if ($p) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
        }
        $result = curl_exec($ch);


        if ($result) {
            return $result;
        } else {
            return curl_error($ch);
        }
        curl_close($ch);
    }



            $species = preg_replace('/[0-9]+/', '', $_GET['id']);
            if(Auth::user())
            {
                $dir = "all/".Auth::user()->email."/".$_GET['analyses']."/".$_GET['image'];
                $directory = public_path()."/all/".Auth::user()->email."/".$_GET['analyses'];
            }
            else if(isset($_GET['email']))
            {
                $dir = "all/".$_GET['email']."/".$_GET['analyses']."/".$_GET['image'];
                $directory = public_path()."/all/".$_GET['email']."/".$_GET['analyses'];
            }
            else{
                $dir = "all/demo/".$_GET['analyses']."/".$_GET['image'];
                $directory = public_path()."/all/demo/".$_GET['analyses'];
            }


            echo "<img src=" . $dir . " name=\"pathwayimage\" usemap=\"#mapdata\" border=\"0\" />";


            echo "<map name=\"mapdata\">";
            //parse compound map data
	$cpdfilename = "cpddata.".$_GET['id'].".txt";
	$genefilename = "genedata.".$_GET['id'].".txt";
		






            //parse geneid data
	if(File::exists($directory."/".$genefilename))
{
            $contents = file_get_contents($directory.'/'.$genefilename);
            $contentsArray = explode("\n",$contents);
            array_shift($contentsArray);
            foreach($contentsArray as $line)
            {
                $splitLineArray = explode("\t",$line);
                if( sizeof($splitLineArray) > 1 )
                {
                    $width = intval($splitLineArray[8]);
                    $height = intval($splitLineArray[9]);
                    $midx = intval($splitLineArray[6]);
                    $midy = intval($splitLineArray[7]);

                    $x1 = $midx - intval($width/2);
                    $y1 = $midy - intval($height/2) -1;

                    $x2 = $midx + intval($width/2);
                    $y2 = $midy + intval($height/2);

                    if(strpos($splitLineArray[3],",") > 0)
                    {
                        $geneids = explode(",",$splitLineArray[3]);
                        $geneid_names = explode(",",$splitLineArray[4]);
                        $gidString ="";
                        $gidtitle ="";
                        $i = 0;
                        foreach($geneids as $gid)
                        {
                            if(sizeof($geneid_names) >= $i)
                            {
                                $gidtitle .= $gid." (".$geneid_names[$i]."), ";
                            }
                            $gidString .= $species.":".$gid."+";
                            $i = $i + 1;
                        }
                        $gidtitle = substr($gidtitle,0,sizeof($gidtitle)-3);
                        $gidString = substr($gidString,0,strlen($gidString)-1);
                        echo "<area shape=\"rect\" coords=\"".$x1.",".$y1.",".$x2.",".$y2."\"href=\"http://www.genome.jp/dbget-bin/www_bget?".$gidString."\" title =\"".$gidtitle."\">";
                        echo "\n";

                    }
                    else if((strcmp($splitLineArray[3],"")!=0)){
                        echo "<area shape=\"rect\" coords=\"".$x1.",".$y1.",".$x2.",".$y2."\"href=\"http://www.genome.jp/dbget-bin/www_bget?".$species.":".$splitLineArray[3]."\" title =\"".$splitLineArray[3]." (".$splitLineArray[4].") \">";
                        echo "\n";
                    }
                }
            }
}
            #else{
            #echo "<area shape=\"circle\" coords=\"".$splitLineArray[4].",".$splitLineArray[5].",4\" href=\"http://www.genome.jp/dbget-bin/www_bget?".$splitLineArray[1]."\" title=\"".$splitLineArray[1]."\" onmouseover=\"popupTimer(&quot;".$splitLineArray[1]."&quot;, &quot;".$splitLineArray[1]."&quot;, &quot;#ffffff&quot;)\" onmouseout=\"hideMapTn()\">";
            if (File::exists($directory."/".$cpdfilename))
            {
                $Compound_contents = file_get_contents($directory.'/'.$cpdfilename);
                $compound_contents_array = explode("\n",$Compound_contents);
                array_shift($compound_contents_array);
                foreach($compound_contents_array as $cmpd_line)
                {

                    $cmpd_line_array = explode("\t",$cmpd_line);
                    if(sizeof($cmpd_line_array) > 7 && strcmp($cmpd_line_array[4],"")!=0 )
                        echo "<area shape=\"circle\" coords=\"".$cmpd_line_array[6].",".$cmpd_line_array[7].",8\" href=\"http://www.genome.jp/dbget-bin/www_bget?".$cmpd_line_array[1]."\" title=\"".$cmpd_line_array[1]." (".$cmpd_line_array[4].")\" onmouseover=\"popupTimer(&quot;".$cmpd_line_array[1]."&quot;, &quot;".$cmpd_line_array[1]." (".$cmpd_line_array[4].")&quot;, &quot;#ffffff&quot;)\" onmouseout=\"hideMapTn()\">";

                    echo "\n";

                }

            }
            echo "</map>";
            ?>




            <br/>
            <br/>


        </div>
    </div>

    <?php }?>




@stop



