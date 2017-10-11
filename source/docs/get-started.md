---
extends: _layouts.master
section: body
title: Getting Started
---

<p class="intro">Vessel is just a small set of files that sets up a local Docker-based dev environment per project. There is nothing to install globally, except Docker itself!</p>

<a name="install" id="install"></a>
## Install

This is all there is to installing it in any given Laravel project:

```bash
# Install Vessel into your project
composer require shipping-docker/vessel:~1.0

# Publish the `vessel` command and Docker files
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


