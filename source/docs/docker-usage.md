---
extends: _layouts.master
section: body
title: How Docker is Used
---

<p class="intro">You have all the power of Docker at your disposal. This means you can run any Docker command against Vessel.</p>

<a name="images" id="images"></a>
## Images

The first time you start up an application with Vessel, it will build a Docker image with PHP, PHP-FPM, Composer, and a few other items. This is based off of the latest official [Ubuntu 16.04 image](https://hub.docker.com/_/ubuntu/).

Vessel will also download the official [MySQL 5.7 image](https://hub.docker.com/_/mysql/) and [NodeJS](https://hub.docker.com/_/node/) image. Vessel adds yarn and git to the Node image to help it download NPM packages as well.

Images are used to run containers. You can think of an image as a PHP class, and a container as an instance of a class.

**The following images are used:**

* PHP 7.1 (using <a href="https://hub.docker.com/_/ubuntu/">Ubuntu 16.04</a>)
* MySQL (<a href="https://hub.docker.com/_/mysql/">5.7</a>)
* Redis (<a href="https://hub.docker.com/_/redis/">latest</a>)
* NodeJS (<a href="https://hub.docker.com/_/node/">latest</a>), with NPM, Yarn, and Gulp

<a name="docker-compose" id="docker-compose"></a>
## Docker Compose

Docker Compose is used to tie together the Docker images created/downloaded. When we start Vessel, Docker Compose tells it to create new containers, set up the networking, persist the MySQL data between restarts, and setup file sharing between your code and the PHP container.

When we run a command such as `artisan`, under the hood, we're calling the following:

```bash
docker-compose exec app \
    sh -c "cd /var/www/html && php artisan list"
```

Using `docker-compose exec` takes our already-running containers and runs a command inside of them. This is faster than `docker-compose run`, which spins up new containers to run a command.

This command uses the container (service) we've named `app` and runs the `sh -c "..."` command inside the container. We use `sh -c` so we can get into the /`var/www/html` directory and run an `artisan` command from there.

You can run a similar command in a way that spins up a new container, and is a little more straightforward (if involving more command line flags):

```bash
docker-compose run --rm \
    -w /var/www/html \
    app \
    php artisan list
```

This runs a command inside of a new container, removing that container when the command finishes (thanks to the `--rm` flag). We also set a working directory (`-w /var/www/html`) inside the container. We once again run an instance of the `app` service (our PHP container), and then define the command to run.

> The above commands are all run inside of the Docker containers. The Docker containers have the code files inside of `/var/www/html` as defined in the `docker-compose.yml` file and expected by the Nginx/PHP-FPM configuration used.

<a name="network" id="network"></a>
### Network

The Docker Compose setup creates a network when the containers are spun up. Each container is added to the network automatically, allowing the containers to communicate to eachother.

The container's hostname is the service name, so the mysql container can be reached using hostname `mysql`, and the redis container can be reached using hostname `redis`.

After running `vessel start`, you can see the network created:

```bash
# List created networks
docker network ls

# Inspect the vessel network created. 
# It will have a unique name ending in _vessel
docker network inspect <network-name-from-above>
```

Using `docker-compose` (which Vessel uses under the hood) puts any container into the defined network automatically. We can do this explicitly if we wanted using the `docker` command:

```bash
docker run --rm \
    --network=example_vessel \
    -w /var/www/html \
    -v .:/var/www/html \
    vessel/app \
    php artisan list
```

This creates a new container from the image `vessel/app`, adds it into network `example_vessel`, sets the working directly, mounts the current directly into the containers `/var/www/html` directory (which is the same as the working directory), and runs `php artisan list` inide of it.

One example use of this is running `mysqldump` against a mysql container.

```bash
docker run --rm \
    --network=example_vessel \
    mysql:5.7 \
    mysqldump -h mysql -u root -psecret some_database > some_database.sql
```

The above runs a new instance of the `mysql` container and runs `mysqldump` inside of it (it does not spin up mysql server). Because it's in the same vessel network as our MySQL instance, we can use the hostname "mysql" (`-h mysql`) to connect to our running MySQL instance.

We never really need to run a command like this, however. The `docker-compose` command makes this easier:

```bash
docker-compose run --rm \
    mysql \
    mysqldump -h mysql -u root -psecret some_database > some_database.sql
```

<a name="volumes" id="volumes"></a>
### Volumes

Both Redis and MySQL images automatically create a Docker Volume. This is a volume mounted to your host machine, which lets the containers run from the image save data in a place that persists between stopping and restarting the containers.

This lets us know that our database and redis data won't disappear on us if we stop or even destroy our containers.

You can see what volumes are created after starting Vessel:

```bash
docker volume ls
```

They'll be named something like:

```bash
example_redisdata
example_mysqldata
```

The `example` portion is created based on the directory name of your project.

If you have old volumes hanging around that you want to delete, you can! Just know this permanently deletes your data, including your databases:

```bash
docker volume rm example_redisdata
docker volume rm example_mysqldata
```



