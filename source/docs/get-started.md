---
extends: _layouts.master
section: body
title: Getting Started
---

<p class="intro">Vessel is just a small set of files that sets up a local Docker-based dev environment per project. There is nothing to install globally, except Docker itself!</p>

> If you don't have PHP 7+ installed on your host machine, see the docs on [I don't have PHP 7 yet (catch-22)](/docs/common-issues#catch22) for a solution.

<a name="install" id="install"></a>
## Install

To install Vessel into any given Laravel project, simply require it via Composer:

``` bash
composer require shipping-docker/vessel
```

If using Laravel <=5.4, register Vessel's service provider (Laravel >=5.5 does this automatically):

```php
// config/app.php

'providers' => [
    // ....
    Vessel\VesselServiceProvider::class,
    // ...
];
```

Finally, publish the `vessel` command and Docker files:

``` bash
php artisan vendor:publish --provider="Vessel\VesselServiceProvider"
```

> Note: You must install Docker to use this project. See [Installing Docker](/docs/installing-docker) for details and supported systems.

<a name="initialize" id="initialize"></a>
## Initialize

Getting started is easy - just initialize vessel and start using it.

```bash
# Run this once to initialize project
# Must run with "bash" until initialized
bash vessel init

# Start vessel
./vessel start
```

Because Vessel uses Redis for caching, the `init` command will install the `predis/predis` composer package if it is not already present.

Head to `http://localhost` in your browser and see your Laravel site!

> **Note 1:** Starting Vessel for the first time will download the base Docker images from [https://hub.docker.com](https://hub.docker.com) and build our application container.
> 
> This will only need to be run the first time.

> **Note 2:** If you receive an error including **EADDRINUSE**, you likely already have something listening on port 80 or 3306. This may be a Vagrant virtual machine, or Laravel Valet, but could be anything! See [Multiple Environments](/docs/everyday-usage#multiple-environments) for a solution.
> 
> Note that the EADDRINUSE error is often last in the error output reported from Docker.

<a name="get-started" id="get-started"></a>

## See it in action

Here's a quick video on installing and getting started with Vessel, with just a tad more explanation.

<div class='embed-container'>
    <iframe src="https://player.vimeo.com/video/238099207" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>

<a name="start-stop" id="start-stop"></a>
## Starting and Stopping Vessel

There's only a few commands to know to start or stop your containers. Database and Redis data is saved when you stop and restart Vessel.

### Starting Vessel

This will start your containers and listen on port 80 for web requests.

```bash
# Start the environment
./vessel start

## This is equivalent to
./vessel up -d
```

### Stopping Vessel

Stopping Vessel will stop the containers and destroy them. They get recreated when you start Vessel back up. Your data (database/cache) is saved between restarts.

```bash
# Stop the environment
./vessel stop

## This is equivalent to
./vessel down
```


