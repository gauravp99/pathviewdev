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
        }?>><a href="{{ URL::route('home') }}"><b>Overview </b><span class="sr-only">(current)</span></a></li>
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
        <li <?php if (basename($_SERVER['PHP_SELF']) == "tutorial") {
            echo "class=\"active\"";
        }?>><a onclick="window.open('/tutorial', 'newwindow', 'width=1020, height=750'); return false;">
                <b>Instructions</b></a></li>
    </ul>
</div>




