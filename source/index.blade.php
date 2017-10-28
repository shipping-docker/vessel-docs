@extends('_layouts.master')

@section('body')
<p class="intro">Simple Docker development environments for Laravel.</p>

<h2>Use It:</h2>
<ul>
<li><a href="/docs/get-started">Getting Started</a></li>
<li><a href="/docs/everyday-usage">Everyday Usage</a></li>
</ul>

<h2>Why Vessel?</h2>
<p>Vessel started as a bash script I put together to make working with Docker easier.</p>

<p>It all started because Docker commands are cumbersome to type. You end up in the CLI pretty often when hacking on Laravel projects - a typical workflow in Laravel involves creating controllers or models, creating and running migrations, running queue workers, adding more packages, and more!</p>

<p>I developed this workflow from my own daily use, and even <a href="https://serversforhackers.com/dockerized-app" title="docker workflow">created a free video series about it</a>. However, I wanted to make something more official that everyone could easily use.</p>

<p>This goal of this project is to be as simple as possible while also giving people a glimpse into how Docker works. I hope you find Docker a really neat way to compartmentalize your projects, and make hacking on projects (on any machine) a breeze.</p>

<h2>What's Included</h2>

<p>The aim of this project is simplicity. It (only) includes:</p>

<ul>
<li>PHP 7.1</li>
<li>MySQL 5.7</li>
<li>Redis (<a href="https://hub.docker.com/_/redis/">latest</a>)</li>
<li>NodeJS (<a href="https://hub.docker.com/_/node/">latest</a>), with NPM, Yarn, &amp; Gulp</li>
</ul>

<p>If you need or want more technologies in your project, check out the docs on <a href="/docs/customizing-vessel">Customizing Vessel</a>.</p>


@endsection
