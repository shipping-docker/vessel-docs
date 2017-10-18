---
extends: _layouts.master
section: body
title: Common Issues
---

<p class="intro">There's a few common issues you might hit. Hopefully you'll find a resolution here!</p>

<a name="eaddrinuse" id="eaddrinuse"></a>
## EADDRINUSE

You might receive an error when starting up your containers. The error's message is typically not worded well - something like this:

```
ERROR: for vesselexample_app_1  Cannot start service app: driver failed programming external connectivity on endpoint vesselexample_app_1 (4c891372c): Error starting userland proxy: Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)
ERROR: Encountered errors while bringing up the project.
```

The last part of the error message is the most important part: `Bind for 0.0.0.0:80: unexpected error (Failure EADDRINUSE)`. The error `EADDRINUSE` means **"Error: Address in use"**.

What's happening is that port 80 is already in use by another process. If it's port 80, it's likely another web server. This might be another instance of Vessel, Laravel Valet, a Vagrant virtual machine, MAMP, WAMP, Apache or anything else you may have running that is listening for web connections.

Within Vessel, you may also get an error when starting up the MySQL port which binds to port `3306`.

For either case, you can either turn off the other program also listening on ports 80 and/or 3306, or see the documentation on [Multiple Environments](/docs/everyday-usage#multiple-environments) for a work-around.


<a name="symlinks" id="symlinks"></a>
## Symlinks

If your project files use symlinks to point to other locations outside of the project, Docker will likely not be able to follow those symlinks as the location will not exist within the container.

It's possible for a Symlink to point to a file that *does* exist within your project but at a file path the container doesn't understand.

For example, a symlink like the following:

```
/Users/fideloper/foo-project/vendor/fideloper -> /Users/fideloper/foo-project/packages/fideloper
```

...will not work within a container, as the container sees project files within `/var/www/html/`. The file path `/Users/fideloper/foo-project` will not exist for code run inside of the container.

If your symlink points to other files *within* the project, you can create the symlink inside the container so it sees a path it understands:

```bash
# Log into the container
./vessel exec app bash

# Create the symlink from within the container
> cd /var/www/html
> ln -s /var/www/html/some-dir/real-file.ext /var/www/html/symlinked.ext
```

If your symlink points to files outside of what the container has available to it, then the container simply will not be able to see those files. 

You may want to copy those files over to your project (or use `rsync` to sync changes over to your project files).

Alternatively, you can adjust the `docker-compose.yml` file to share additional directories from your host filesystem:

```yaml
# Portion of file `docker-compose.yml`
services:
  app:
    build:
      context: ./docker/app
      dockerfile: Dockerfile
    image: vessel/app
    ports:
     - "${APP_PORT}:80"
    environment:
      CONTAINER_ENV: "${APP_ENV}"
      XDEBUG_HOST: "${XDEBUG_HOST}"
      WWWUSER: "${WWWUSER}"
    volumes:
     - .:/var/www/html
     - /path/to/additional/directory:/opt
    networks:
     - vessel
```

If you then share additional directories on your host file system to the `/opt` directory of your app container, you can then create symlinks between the two:

```bash
# Log into the container
./vessel exec app bash

# Create symlink from file in /opt directory to the project files
> ln -s /opt/foo-file.ext /var/www/html/foo-file.ext
```

<a name="mysql-password" id="mysql-password"></a>
## MySQL Access Denied

When you start Vessel within a project for the first time, MySQL initializes itself. This initialization does the following:

1. Sets the `root` user's password to the password defined in the .`env` file's `DB_PASSWORD`
2. Creates a new database defined by `DB_DATABASE`
3. Creates a new user and password defined by `DB_USERNAME` and `DB_PASSWORD`
    - This means the root user and the created username both have the same password

If the `DB_PASSWORD` field is empty, then MySQL may not initialize correctly. It will not create a user with an empty password as you might expect.

**When you perform an action such as a database migration, you might receive an Access Denied error.**

If you run into this, you can:

1. Spin down your containers
2. Destroy the Volume created for the MySQL container
3. Set a password within the `.env` file
4. Start the containers back up - the MySQL container will re-initialize itself

```bash
# Spin down the containers
./vessel stop

# Destroy the volume created
## List volumes to find it
docker volume ls
## Destroy the appropriate one
docker volume rm vesselexample_vesselmysql

# Set a password within the .env file
DB_PASSWORD=secret

# Start the containers back up
./vessel start
```

<a name="catch22" id="catch22"></a>

## I don't have PHP 7 yet (catch-22)

Starting a new Laravel project, or just pulling in certain packages, requires PHP7+. If you don't have PHP installed, or don't have PHP 7+, you'll run into an issue where you can't create a new Laravel project, or may not be able to add Vessel into your current project.

In this case, we have a catch-22; You won't yet have Vessel (which has PHP 7+) but need PHP 7+ to get Laravel and/or Vessel.

In this case, you can use a [pre-built Docker container](https://hub.docker.com/r/shippingdocker/php-composer/) setup for just this use case. It will allow you to run PHP and `composer` commands using PHP 7.

Here's how:

```bash
# Head to whatever directory you with to create new project in
cd ~/Path/To/Projects

# Create a new laravel project (or just `cd` into your existing project if you have one)
docker run --rm -it \
    -v $(pwd):/opt \
    -w /opt shippingdocker/php-composer:latest \
    composer create-project laravel/laravel vessel-php-composer

# Get Vessel:
docker run --rm -it \
    -v $(pwd):/opt \
    -w /opt shippingdocker/php-composer:latest \
    composer require shipping-docker/vessel

# Publish Vessel assets
docker run --rm -it \
    -v $(pwd):/opt \
    -w /opt shippingdocker/php-composer:latest \
    php artisan vendor:publish --provider="Vessel\VesselServiceProvider"

# Then initialize and run Vessel as normal:
bash vessel init
./vessel start
./vessel composer require foo/bar
```

You can delete the `php-composer` image when you're finished with it:

```bash
docker image rm shippingdocker/php-composer:latest
```



