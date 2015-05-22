<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/images/icon.png">
    <title>Pathview</title>

    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/carousel.css') }}" rel="stylesheet">
    <link href="http://mjolnic.com/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="http://mjolnic.com/bootstrap-colorpicker/src/css/docs.css" rel="stylesheet">
    {{--<link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dashboard.css') }}" rel="stylesheet">--}}

    <!-- Fonts
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet'
          type='text/css'>
    <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="{{asset('/js/ChartNew.js')}}"></script>
    <script src="{{asset('/js/bootstrap-colorpicker.js')}}"></script>
    <script src="http://mjolnic.com/bootstrap-colorpicker/src/js/docs.js"></script>
    <script type="text/javascript" src="http://jscolor.com/example/jscolor/jscolor.js"></script>
</head>
<body onLoad="init()">
<nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><img src="/images/logo.png" height="40px"
                                                               style="margin-top: -17px">
                Pathway based data integration and visualization
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Help
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/tutorial#input">Attributes
                                Help</a></li>
                        <li><a href="/tutorial">Custom
                                Analysis</a></li>
                        <li><a href="/tutorial">Multiple
                                Sample Analysis</a></li>
                        <li><a href="/tutorial">Multiple
                                Sample Graphviz analysis</a></li>
                        <li><a href="/tutorial">ID
                                mapping Analysis</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Pathview
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a target="_blank"
                               href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html">Pathview
                                Bioconductor</a></li>
                        <li><a target="_blank"
                               href="http://www.bioconductor.org/packages/release/bioc/vignettes/pathview/inst/doc/pathview.pdf">Pathview
                                Bioconductor Tutorial</a></li>
                        <li><a target="_blank" href="http://pathview.r-forge.r-project.org/">Pathview R-Forge</a></li>
                    </ul>
                </li>
                <li <?php if (basename($_SERVER['PHP_SELF']) == "about") {
                    echo "class=\"active\"";
                }?>><a href="/about">About</a></li>
                <li <?php if (basename($_SERVER['PHP_SELF']) == "related") {
                    echo "class=\"active\"";
                }?>><a href="#">Related</a></li>

                @if (Auth::guest())
                    {{--<li><a href="{{ url('/auth/login') }}">Login</a></li>--}}
                    <li <?php if (basename($_SERVER['PHP_SELF']) == "login") {
                        echo "class=\"active\"";
                    }?>><a href="{{ url('/auth/login') }}">Login</a></li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == "register") {
                        echo "class=\"active\"";
                    }?>><a href="{{ url('/auth/register') }}">Register</a></li>
                    <li <?php if (basename($_SERVER['PHP_SELF']) == "guest") {
                        echo "class=\"active\"";
                    }?>><a href="{{ url('/guest') }}"><img src="{{asset('/images/user.png')}}" alt="Login as Guest"
                                                           height="20px">Guest</a></li>

                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><img src="{{asset('/images/user.png')}}" alt="login user"
                                                      height="20px">{{ " ".Auth::user()->name }} <span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "prev_anal") {
                                echo "class=\"active\"";
                            }?>><a href="{{ url('/prev_anal/'.Auth::user()->id) }}">Previous Analysis</a></li>
                            <li <?php if (basename($_SERVER['PHP_SELF']) == "user") {
                                echo "class=\"active\"";
                            }?>><a href="{{ url('/user/'.Auth::user()->id) }}">Edit Profile</a></li>
                            <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('/js/sliding.form.js') }}"></script>
<script type="text/javascript">
    var i = 1;
    function addText() {


        var el = document.createElement("input");
        el.setAttribute("list", "pathwaylist");
        el.setAttribute("class", "fex8");
        el.setAttribute("name", "pathway" + i);
        el.setAttribute("id", "pathway" + i);
        el.setAttribute("value", "00010");
        el.setAttribute("required", "required");

        var foo = document.getElementById("pat_div");
        foo.appendChild(document.createElement("br"));
        foo.appendChild(el);
        foo.appendChild(document.createElement("br"));
        i++;


    }

    function removeText() {

        if (i > 0) {
            console.log(i);
            var pat = document.getElementById("pat_div");
            for (j = 0; j < 3; j++)
                pat.removeChild(pat.lastChild);
            i--;
        }
    }
</script>
@include('footer')
</body>
</html>