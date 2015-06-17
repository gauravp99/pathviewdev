<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 4/6/15
 * Time: 12:17 PM
 */
?>
<div class=" col-md-2 sidebar" >
    <ul class="nav nav-sidebar">
        <li <?php if (basename($_SERVER['PHP_SELF']) == "home"||basename($_SERVER['PHP_SELF']) == "guest-home"||basename($_SERVER['PHP_SELF']) == "guest") {
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

<style>
    @media (max-width: 636px)
    {
        .nav{
            font-size: 13px;
        }
        .active{

        }
    }
    @media (max-width: 400px)
    {
        .nav{
            visibility: hidden;
            position:absolute;
        }

    }
    @media (max-width: 768px)
    {
        .conetent-header > p
        {
            width: 100%;
        }

    }
    @media (min-width: 20px) {

        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0;
        }
    }
</style>




