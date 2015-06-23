<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="AUTHOR" content="Weijun Luo,Yeshvant Kumar Bhavnasi Venkat Satya">
    <link rel="icon" href="/images/plogo.png"  sizes="32x32">
    <link href="{{ asset('/css/bootstrap1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <title>Pathview</title>
</head>
<body >
<nav class="navbar navbar-default navbar-change">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#pathview">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand navbar-brand-img " href="{{ url('/') }}"><img src="/images/plogo.png"  class="navbrand-image"></a>
            <a class="navbar-brand textlogo" href="{{ url('/') }}"><img src="/images/logo-text.png" height="40px" style="margin-top: -16px">
                <div class="navbrand-text"> Pathway based data integration and visualization</div>
            </a>
        </div>

        <div class="collapse navbar-collapse" style="height:60px" id="pathview" >
            <ul class="nav navbar-nav navbar-right">
                <li <?php
                        if (basename($_SERVER['PHP_SELF']) == "tutorial") {
                            echo "class=\"active\"";
                        }
                        ?> class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                        Help <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/tutorial#input">Attributes Help</a></li>
                        <li><a href="/tutorial">Custom Analysis</a></li>
                        <li><a href="/tutorial#example1">Example Analysis</a></li>
                        <li><a href="/tutorial#refrence">References</a></li>
                        <li><a href="/tutorial#contact">Contact</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                        Pathview <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a target="_blank" href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html">
                                Bioconductor
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="http://www.bioconductor.org/packages/release/bioc/vignettes/pathview/inst/doc/pathview.pdf">
                                Tutorial
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="http://pathview.r-forge.r-project.org/">
                                R-Forge
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full">
                                Paper
                            </a>
                        </li>
                    </ul>
                </li>

                <li <?php if (basename($_SERVER['PHP_SELF']) == "about") {
                    echo "class=\"active\"";
                    }?>>
                    <a href="/about">About</a>
                </li>

                <li <?php if (basename($_SERVER['PHP_SELF']) == "related") {
                    echo "class=\"active\"";
                    }?>>
                    <a href="#">Related</a>
                </li>

                @if (Auth::guest())
                    <?php if(basename($_SERVER['PHP_SELF']) == "login")
                    {
                    ?>
                    <li class="dropdown active" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            Login <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php
                                    if (basename($_SERVER['PHP_SELF']) == "guest") {
                                        echo "class=\"active\"";
                                    }
                                    ?>>
                                <a href="{{ url('/guest') }}">
                                    Guest</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    }
                    else if(basename($_SERVER['PHP_SELF']) == "register")
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Register <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "guest") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/guest') }}">Guest</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    }
                    else if((basename($_SERVER['PHP_SELF']) == "guest")
                        ||(basename($_SERVER['PHP_SELF']) == "guest-home")
                        ||(basename($_SERVER['PHP_SELF']) == "analysis")
                        ||(basename($_SERVER['PHP_SELF']) == "example1")
                        ||(basename($_SERVER['PHP_SELF']) == "example2")
                        ||(basename($_SERVER['PHP_SELF']) == "example3")
                        ||(basename($_SERVER['PHP_SELF']) == "viewer")
                        ||(basename($_SERVER['PHP_SELF']) == "results")
                        ||(basename($_SERVER['PHP_SELF']) == "postAnalysis")
                        ||(basename($_SERVER['PHP_SELF']) == "post_exampleAnalysis1")
                        ||(basename($_SERVER['PHP_SELF']) == "post_exampleAnalysis2")
                        ||(basename($_SERVER['PHP_SELF']) == "post_exampleAnalysis3")

                        )
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{asset('/images/user.png')}}" alt="Login as Guest" height="20px">
                            Guest <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "guest") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                        </ul>
                    </li>

                    <?php
                    }
                    else {
                    ?>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{asset('/images/user.png')}}" alt="Login as Guest" height="20px">
                            Account
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "login") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/login') }}">
                                    Login
                                </a>
                            </li>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php if ((basename($_SERVER['PHP_SELF']) == "guest")) {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/guest') }}">Guest</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    }
                    ?>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            <img src="{{asset('/images/user.png')}}" alt="login user" height="20px">
                            {{ " ".Auth::user()->name }}
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "prev_anal") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/prev_anal/'.Auth::user()->id) }}">Status & History</a>
                            </li>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "user") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/edit_user/'.Auth::user()->id) }}">Edit Profile</a>
                            </li>
                            <li>
                                <a href="{{ url('/passwordReset') }}">Reset Password</a>
                            </li>
                            <li>
                                <a href="{{ url('/auth/logout') }}">Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>


        @yield('content')

        @include('footer')



<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ asset('/js/sliding.form.js') }}"></script>
<script src="{{asset('/js/ChartNew.js')}}"></script>
<script type="text/javascript" src="http://jscolor.com/example/jscolor/jscolor.js"></script>
<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': {!! json_encode(csrf_token()) !!},
    }
    });
        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();

        var footerTop = $('#footer').position().top + footerHeight;
        if (footerTop < docHeight) {

            $('#footer').css('margin-top',-30+ (docHeight - footerTop) + 'px');
        }
    });
</script>
<?php if ((basename($_SERVER['PHP_SELF']) == "index.php")||basename($_SERVER['PHP_SELF']) == "about") {
?>
<script>
    var ctx = $("#myChart").get(0).getContext("2d");

    var opt2 = {

        canvasBordersWidth: 3,
        canvasBordersColor: "#205081",
        legend: true,
        graphTitleFontSize: 18,
        logarithmic: true,
        responsive: true
    };

    var data = {

        labels: JSON.parse('<?php echo JSON_encode($months);?>'),
        datasets: [
            {
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                data: JSON.parse('<?php echo JSON_encode($usage);?>')
                //title:"No of Graphs"
            },
            {
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                data: JSON.parse('<?php echo JSON_encode($ip);?>')
                // title:"Unique IP's"
            }
        ]
    };

    var myBarChart = new Chart(ctx).Bar(data, opt2);

    var ctx1 = $("#myChart1").get(0).getContext("2d");

    var data1 = {
        labels: JSON.parse('<?php echo JSON_encode($bioc_months);?>'),
        datasets: [
            {
                fillColor: "rgba(220,220,220,0.5)",
                strokeColor: "rgba(220,220,220,1)",
                pointColor: "rgba(220,220,220,1)",
                pointStrokeColor: "#fff",
                data: JSON.parse('<?php echo JSON_encode($bioc_downloads);?>')
            },
            {
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                data: JSON.parse('<?php echo JSON_encode($bioc_ip);?>')
            }
        ]
    };

    var opt1 = {

        canvasBordersWidth: 3,
        canvasBordersColor: "#205081",
        scaleOverride: true,
        scaleSteps: 4,
        scaleStepWidth: 500,
        scaleStartValue: 0,
        scaleLabel: "<%=value%>",
        legend: true,
        graphTitleFontSize: 18,
        responsive: true


    };
    var myBarChart1 = new Chart(ctx1).Bar(data1, opt1);
</script>
<?php
        }
        ?>

</body>
</html>