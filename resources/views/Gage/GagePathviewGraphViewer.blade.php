@extends('GageApp')
@section('content')
    <h1 class="page-header" style="margin-left: 40%;">Result </h1>

    <div class="col-sm-12">
        <div class="col-sm-8">

            <p class="alert alert-info">Note:  Nodes in native KEGG view graphs are hyperlinked.</p>
            <?php
            $anal_id = $_GET['id'];
            if (Auth::user()) {
                $user_id = Auth::user()->id;

                $val1 = DB::select(DB::raw("select analysis_id from analyses where id = $user_id  and analysis_id ='$anal_id'"));
            } else {
                $val1 = DB::select(DB::raw("select analysis_id from analyses where id = 0  and analysis_id ='$anal_id'"));
            }
            /**if(sizeof($val1) == 0 && !isset($_GET['email']))
            {
                echo "<h1 class='alert alert-danger'> Alert Yo don't have access to this analysis </h1>";

            }
            else{**/
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
            $id = substr($_GET['image'],0,strpos($_GET['image'], '.', 1));

            $a = cURL("http://www.genome.jp/kegg-bin/show_pathway?map=" . $id);

            $c = extract_unit($a, "<map name=\"mapdata\">", "</map>");
            $d = str_replace("href=\"", "href=\"http://www.genome.jp", $c);
            if(Auth::user())
            {
                $dir = "all/".Auth::user()->email."/".$_GET['analyses']."/".$_GET['image'];
            }
            else if(isset($_GET['email']))
            {
                $dir = "all/".$_GET['email']."/".$_GET['id']."/".$_GET['image'];
            }
            else{
                $dir = "all/demo/".$_GET['id']."/".$_GET['image'];
            }
            echo "<img src=" . $dir . " name=\"pathwayimage\" usemap=\"#mapdata\" border=\"0\" />";

            echo "<map name=\"mapdata\">";
            echo $d;
            echo "</map>";

            ?>
            <br/>
            <br/>


        </div>
    </div>

   <?php
   /** } */
   ?>




@stop


