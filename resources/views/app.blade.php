<!DOCTYPE html>
<?php
use DebugBar\StandardDebugBar;

$debugbar = new StandardDebugBar();
$debugbarRenderer = $debugbar->getJavascriptRenderer();

$debugbar["messages"]->addMessage("hello world!");

if(basename(Request::url())== "gage-home" || basename(Request::url())== "gage-guest-home")
        {
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="AUTHOR" content="Weijun Luo,Yeshvant Kumar Bhavnasi Venkat Satya">
    <link href="{{ asset('/css/bootstrap1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <link rel="stylesheet" href="{{ asset('/css/GageStyle.css') }}" type="text/css" media="screen"/>
    <link href="{{ asset('/css/bootstrap1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <title>GageWeb</title>
</head>
<body ng-app="GageApp" >
<nav class="navbar navbar-default navbar-change">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#pathview">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand navbar-brand-img " href="gageIndex/"><img src="/images/plogo.png"  class="navbrand-image"></a>
            <a class="navbar-brand textlogo" href="gageIndex/"><img src="/images/gage-logo-text.png" height="60px" style="margin-top: -22px">
                <div class="navbrand-text" style="margin-top: -13px"> Generally Applicable Gene-set/Pathway Analysis</div>
            </a>
        </div>

        <div class="collapse navbar-collapse" style="height:60px" id="pathview" >
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/" >Home</a></li>

                <li class="dropdown" <?php if (basename(Request::url()) == "gageTutorial") {
                    echo "class=\"active\"";
                }?>>
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                        Help <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/gageTutorial#input">Attributes Help</a></li>
                        <li><a href="/gageTutorial">Custom Analysis</a></li>
                        <li><a href="/gageTutorial#example1">Example Analysis</a></li>
                        <li><a href="/gageTutorial#refrence">References</a></li>
                        <li><a href="/gageTutorial#contact">Contact</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">
                        GAGE <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a target="_blank" href="http://www.bioconductor.org/packages/release/bioc/html/gage.html">
                                Bioconductor
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="http://www.bioconductor.org/packages/release/bioc/vignettes/Gage/inst/doc/gage.pdf">
                                Tutorial
                            </a>
                        </li>
                        <li>
                            <a target="_blank" href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full">
                                Paper
                            </a>
                        </li>
                    </ul>
                </li>
                <li <?php if (basename(Request::url()) == "gageAbout") {
                    echo "class=\"active\"";
                }?>><a href="/gageAbout">About</a></li>

                @if (Auth::guest())
                    <li <?php if (basename(Request::url()) == "gage") {
                        echo "class=\"active\"";
                    }?>><a href="/gage-guest-home">Guest</a></li>
                @else
                    <li <?php if (basename(Request::url()) == "gage") {
                        echo "class=\"active\"";
                    }?>><a href="/gage-home">GAGE</a></li>
                @endif
                @if (Auth::guest())
                    <?php if(basename(Request::url()) == "login")
                    {
                    ?>
                    <li class="dropdown active" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            Login <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php
                                    if (basename(Request::url()) == "guest") {
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
                    else if(basename(Request::url()) == "register")
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Register <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "guest") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/guest') }}">Guest</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    }
                    else if((basename(Request::url()) == "guest")
                    ||(basename(Request::url()) == "guest-home")
                    ||(basename(Request::url()) == "analysis")
                    ||(basename(Request::url()) == "example1")
                    ||(basename(Request::url()) == "example2")
                    ||(basename(Request::url()) == "example3")
                    ||(basename(Request::url()) == "viewer")
                    ||(basename(Request::url()) == "results")
                    ||(basename(Request::url()) == "postAnalysis")
                    ||(basename(Request::url()) == "post_exampleAnalysis1")
                    ||(basename(Request::url()) == "post_exampleAnalysis2")
                    ||(basename(Request::url()) == "post_exampleAnalysis3")

                    )
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{asset('/images/user.png')}}" alt="Login as Guest" height="20px">
                            Guest <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "guest") {
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
                            <li <?php if (basename(Request::url()) == "login") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/login') }}">
                                    Login
                                </a>
                            </li>
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php if ((basename(Request::url()) == "guest")) {
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
                            <li <?php if (basename(Request::url()) == "prev_anal") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/prev_anal/'.Auth::user()->id) }}">Status & History</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "user") {
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
        var docHeight = $(window).height();
        var footerHeight = $('#footer').height();

        var footerTop = $('#footer').position().top + footerHeight;
        if (footerTop < docHeight) {

            $('#footer').css('margin-top',-30+ (docHeight - footerTop) + 'px');
        }
    });
</script>
<?php
if ( basename(Request::url())== "gageIndex" && !Auth::user() || basename(Request::url()) == "gageAbout" )
{

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
}?>
</body>
</html>

<?php
        }else {
    ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta name="AUTHOR" content="Weijun Luo,Yeshvant Kumar Bhavnasi Venkat Satya">
    <link rel="icon" href="/images/plogo.png"  sizes="32x32">
    <link href="{{ asset('/css/bootstrap1.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <title>Pathview</title>
</head>
<body>

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
                        if (basename(Request::url()) == "tutorial") {
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

                <li <?php if (basename(Request::url()) == "about") {
                    echo "class=\"active\"";
                    }?>>
                    <a href="/about">About</a>
                </li>

                <li <?php if (basename(Request::url()) == "related") {
                    echo "class=\"active\"";
                    }?>>
                    <a href="/gageIndex" target="_blank">Related</a>
                </li>

                @if (Auth::guest())
                    <?php if(basename(Request::url()) == "login")
                    {
                    ?>
                    <li class="dropdown active" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            Login <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php
                                    if (basename(Request::url()) == "guest") {
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
                    else if(basename(Request::url()) == "register")
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Register <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "guest") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/guest') }}">Guest</a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    }
                    else if((basename(Request::url()) == "guest")
                        ||(basename(Request::url()) == "guest-home")
                        ||(basename(Request::url()) == "analysis")
                        ||(basename(Request::url()) == "example1")
                        ||(basename(Request::url()) == "example2")
                        ||(basename(Request::url()) == "example3")
                        ||(basename(Request::url()) == "viewer")
                        ||(basename(Request::url()) == "results")
                        ||(basename(Request::url()) == "postAnalysis")
                        ||(basename(Request::url()) == "post_exampleAnalysis1")
                        ||(basename(Request::url()) == "post_exampleAnalysis2")
                        ||(basename(Request::url()) == "post_exampleAnalysis3")

                        )
                    {
                    ?>
                    <li class="dropdown active">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="{{asset('/images/user.png')}}" alt="Login as Guest" height="20px">
                            Guest <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                            }?>>
                                <a href="{{ url('/auth/login') }}">Login</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "guest") {
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
                            <li <?php if (basename(Request::url()) == "login") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/login') }}">
                                    Login
                                </a>
                            </li>
                            <li <?php if (basename(Request::url()) == "register") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/auth/register') }}">Register</a>
                            </li>
                            <li <?php if ((basename(Request::url()) == "guest")) {
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
                            <li <?php if (basename(Request::url()) == "prev_anal") {
                                echo "class=\"active\"";
                                }?>>
                                <a href="{{ url('/prev_anal/'.Auth::user()->id) }}">Status & History</a>
                            </li>
                            <li <?php if (basename(Request::url()) == "user") {
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
<input id="csrf" type="text" hidden value="{{ csrf_token() }}">
        @yield('content')

        @include('footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ asset('/js/sliding.form.js') }}"></script>
<script src="{{asset('/js/ChartNew.js')}}"></script>
<script type="text/javascript" src="http://jscolor.com/example/jscolor/jscolor.js"></script>
<script>
    $(document).ready(function () {
    var docHeight = $(window).height();
        var footerHeight = $('#footer').height();

        var footerTop = $('#footer').position().top + footerHeight;
        if (footerTop < docHeight) {

            $('#footer').css('margin-top',-30+ (docHeight - footerTop) + 'px');
        }
    });
</script>

<?php

if ( basename(Request::url())==$_SERVER['SERVER_NAME'] || basename(Request::url()) == "about") {

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
<?php
}
?>
