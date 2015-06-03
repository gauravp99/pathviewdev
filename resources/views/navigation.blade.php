<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 4/6/15
 * Time: 12:17 PM
 */
?>
<div class="col-sm-5 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li <?php if (basename($_SERVER['PHP_SELF']) == "index.php") {
            echo "class=\"active\"";
        }?>>
            @if (Auth::guest()) <a href="/guest-home">
                @else <a href="{{ URL::route('home') }}"> @endif <b>Overview </b><span class="sr-only">(current)</span></a>
        </li>
        <li <?php if (basename($_SERVER['PHP_SELF']) == "analysis") {
            echo "class=\"active\"";
        }?>><a href="/analysis"><b>New Analysis</b></a></li>
        <li <?php if (basename($_SERVER['PHP_SELF']) == "example1") {
            echo "class=\"active\"";
        }?>><a href="/example1"><b>Example 1</b></a></li>
        <li <?php if (basename($_SERVER['PHP_SELF']) == "example2") {
            echo "class=\"active\"";
        }?>><a href="/example2"><b>Example 2</b></a></li>
        <li <?php if (basename($_SERVER['PHP_SELF']) == "example3") {
            echo "class=\"active\"";
        }?>><a href="/example3"><b>Example 3</b></a></li>
        <li><a href="#" onclick="openWindow()">
                <b>Instructions</b></a></li>
        <script>
            function openWindow() {
                window.open('/tutorial', 'newwindow', "scrollbars=1,width=2000, height=window.innerHeight");

                var w = window.innerWidth;
                var h = window.innerHeight;

            }
        </script>
    </ul>
</div>




