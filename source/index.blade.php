@extends('_layouts.master')

@section('body')
<p class="intro">Vessel is a tiny Docker environment that aims to be easy to use with your Laravel applications.</p>

<h2>What's Included</h2>

<p>The aim of this project is simplicity. It includes:</p>

<ul>
<li>PHP 7.1</li>
<li>MySQL 5.7</li>
<li>Redis (<a href="https://hub.docker.com/_/redis/">latest</a>)</li>
<li>NodeJS (<a href="https://hub.docker.com/_/node/">latest</a>), with NPM, Yarn, &amp; Gulp</li>
</ul>

<h2>Use It:</h2>
<ul>
<li><a href="/docs/installation">Installation</a></li>
<li><a href="/docs/everyday-usage">Everyday Usage</a></li>
</ul>

@endsection
