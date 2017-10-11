---
extends: _layouts.master
section: body
title: How Docker is Used
---

<p class="intro">You have all the power of Docker at your disposal. This means you can run any Docker command against Vessel.</p>

<a name="images" id="images"></a>
## Images

The first time you start up an application with Vessel, it will download the following official Docker images:

* MySQL (<a href="https://hub.docker.com/_/mysql/">5.7</a>)
* Redis (<a href="https://hub.docker.com/_/redis/">latest</a>)

Vessel will also **build** the following Docker images locally on your machine:

* PHP 7.1 (using <a href="https://hub.docker.com/_/ubuntu/">Ubuntu 16.04</a> as a base)
    - [Dockerfile used](https://github.com/shipping-docker/vessel/blob/master/docker-files/docker/app/Dockerfile) to build it
* NodeJS (<a href="https://hub.docker.com/_/node/">latest</a>), with NPM, Yarn, and Gulp
    - [Dockerfile used](https://github.com/shipping-docker/vessel/blob/master/docker-files/docker/node/Dockerfile) to build it

Images are used to run containers. You can think of an image as a PHP class, and a container as an instance of a class.

### Find Images

You can view the images you have downloaded or built on your local machine:

```bash
# List images built on your computer
docker image ls
```

You'll see something like this:

```
REPOSITORY                   TAG                 IMAGE ID            CREATED             SIZE
vessel/app                   latest              ee589c11428a        6 weeks ago         385MB
vessel/node                  latest              e0c607290998        6 weeks ago         696MB
ubuntu                       16.04               ccc7a11d65b1        8 weeks ago         120MB
mysql                        5.7                 c73c7527c03a        2 months ago        412MB
redis                        alpine              9d8fa9aa0e5b        2 months ago        27.5MB
node                         8.6                 90223b3d894e        5 months ago        665MB
```

This shows the base images used (Node, Redis, MySQL, Ubuntu), and then our two custom images we use for the PHP application controller, and the Node controller, to which we added Git and Yarn.

<a name="rebuild-images" id="rebuild-images"></a>
### Rebuilding Vessel Images

If you want to completely rebuild Vessel images (to perhaps pull in the latest version of NodeJS or update the base Ubuntu image), you can delete these images and then have Vessel re-build them. Let's, for example, have Vessel rebuild the Node container:

```bash
# Remove the vessel/node image and its base image
# in this order
docker image rm vessel/node
docker image rm node

# Have vessel rebuild any images it needs to
# which will be "node" in this case
./vessel build
```

We can do the same with our PHP application image:

```bash
# Remove the vessel/app image and its base image
# in this order
docker image rm vessel/app
docker image rm ubuntu

# Have vessel rebuild any images it needs to
# which will be "vessel/app" in this case
./vessel build
```

<a name="docker-compose" id="docker-compose"></a>
## Docker Compose

Docker Compose is used to tie together the Docker images created/downloaded. When we start Vessel, Docker Compose tells it to create new containers, set up the networking, persist MySQL/Redis data through volumes, and setup file sharing between your code and the PHP container.

Vessel will pass-through any command it does not understand to `docker-compose`. If no command is passed, it will run `docker-compose ps`.

```bash
# Running this:
./vessel

# Is equivalent to
./vessel ps

# Which, under the hood, is running:
docker-compose ps
```

You can run any `docker-compose` command with `vessel`. Vessel will fill in defaults and environment variables needed/expected by the `docker-compose.yml` file for you, so it's easier and more convenient than using the `docker-compose` command itself.

For example, we can restart our containers (e.g. run `docker-compose restart`) by letting `vessel` pass the command through to `docker-compose`:

```bash
./vessel restart
```

You can see Docker Compose's help menu, including available commands:

```bash
./vessel help
```

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

<!--
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
-->

<a name="volumes" id="volumes"></a>
### Volumes

Both Redis and MySQL images automatically create a Docker volume. This is a volume *mounted to your host machine*, which lets the containers save data in a place that *persists between stopping and restarting the containers*.

This means when your containers are destroyed (which they are during normal Docker operation), you won't lose your important data, such as your databases or redis cache data.

You can see what volumes are created after starting Vessel:

```bash
# List created volumes
docker volume ls
```

You'll see something like this:

```bash
DRIVER              VOLUME NAME
local               vesselexample_vesselmysql
local               vesselexample_vesselredis
```

This shows two "local" volumes. Their name is built by Docker using a normalized directory name of the project, and the name "vesselmysql" or "vesselredis" appended.

If you want to clean old volumes, or delete your current ones, you can! Just know this permanently deletes your data, including your databases:

```bash
docker volume rm vesselexample_vesselmysql
docker volume rm vesselexample_vesselredis
```



