---
extends: _layouts.master
section: body
title: Customizing Vessel
---

<p class="intro">Under the hood, Vessel is just Docker.</p>

If you're familiar with Docker and Docker Compose, you can basically do anything you want to Vessel.

To give you an idea of what's possible, here are some example customizations.

<a name="network" id="network"></a>
## Connecting Two Installs

Sometimes you may have a Vessel setup for two applications. You can have multiple Vessel's running as described in the [Multiple Environments](https://vessel.shippingdocker.com/docs/everyday-usage/#multiple-environments) section.

However, what if your two Vessels need to speak to eachother? If you have one running on port 8080 (and use `http://localhost:8080` in the browser to view it), you may try to have your other Vessel reach it using `localhost:8080`. [However, this won't work](https://serversforhackers.com/c/dckr-localhost)! Only your host machine can use `localhost:8080`. That's a special port-forwarding setup between the host machine and your Vessel environment.

For the Docker containers to speak to eachother directly, they need to be connected over a Docker network. So, to make this work, we need to manually create a new network:

```bash
# Name the network whatever you want
# here, I named it "overwatch"
docker network create overwatch
```

So we have a new network which is NOT managed by `docker-compose`. Instead, it exists outside of our projects. We can use this network to connect two or more Vessel's being used on our host machine.

To use that network, we need to add our two (or more) projects' `app` containers into this new network:

```yml
# Some things here omitted
# We just need to add our app service
# into the "overwatch" network
services:
  app:
    networks:
      overwatch:
        aliases:
          - someapp
      sdnet:

# And then, in the networks section
# we define the overwatch network as
# an external network
networks:
  overwatch:
    external:
      name: overwatch
```

> Note that I show this example once, but I'm assuming you'll make these changes in BOTH of your Vessel's. Make sure to make a *unique* network alias in both/all of your project's `docker-compose.yml` files.

We did three things here:

1. The `app` service network gets added to `sdnet` as usual
  - **Note**: The syntax there of `sdnet:`, with the colon, is intentional/needed to be valid yaml
2. The `app` service network gets added to our new network `overwatch`, and we give it an alias of `someapp`. The alias `someapp` becomes the network name of that app container within the `overwatch` network.
3. We defined the `overwatch` network as an external network - docker-compose will NOT try to manage that network.

So, if you do this in both of your `docker-compose.yml` files for both of your Vessel's, they can then communicate with eachother.

**If we aliased both Vessel's `app` container to `someapp` and `fooapp`, then `fooapp` can send requests to `http://someapp` and `someapp` can send requests to `http://fooapp` (no ports necessary, Nginx is listening on port 80 in both cases inside of the containers).**

> If you're confused and want to learn more, definitely consider picking up the [Shipping Docker course](https://serversforhackers.com/shipping-docker), which goes into Docker networking in a bit more detail.

<a name="pgsql" id="pgsql"></a>
## Using PostgreSQL over MySQL

If you prefer PostgreSQL instead of MySQL, you can change the database container to use the [official PostreSQL image](https://hub.docker.com/_/postgres/).

Within your `.env` file, change the database items as needed:

```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
```

This will prepare Laravel to connect to a PostgreSQL database, listening on port 5432.

Then we'll edit the `docker-compose.yml` file to swap MySQL out with PostgreSQL.

Luckily, the PostgreSQL configuration is very similar to the MySQL configuration (same for the Percona and MariaDB containers).

Replace the `mysql` section with the following:

```yaml
services:
  pgsql:
    image: postgres:10.0
    ports:
     - "${MYSQL_PORT}:5432"
    environment:
      POSTGRES_USER: "${DB_USERNAME}"
      POSTGRES_PASSWORD: "${DB_PASSWORD}"
      POSTGRES_DB: "${DB_DATABASE}"
    volumes:
     - vesselmysql:/var/lib/postgresql/data
    networks:
     - vessel
```

### Environment Variables

**Note** that we still use the `MYSQL_PORT` environment variable. You can re-use this environment variable or edit the `vessel` file to use another environment variable. If we don't change the `vessel` file, you can start up your containers like so:

```bash
MYSQL_PORT=5432 ./vessel start
```

Otherwise you can add a `PGSQL_PORT` environment variable to the `vessel` script:

```bash
export APP_PORT=${APP_PORT:-80}
export MYSQL_PORT=${MYSQL_PORT:-3306}
export PGSQL_PORT=${PGSQL_PORT:-5432}
```

You can then use `PGSQL_PORT` in place of `MYSQL_PORT` within the `docker-compose.yml` file.

### Volume

Lastly, we re-use the `vesselmysql` named volume. This is fine as the name is arbitrary, however if you want the naming to be consistent, you can define a volume for PostgreSQL instead.

```yaml
services:
  pgsql:
    image: postgres:10.0
    ports:
     - "${MYSQL_PORT}:5432"
    environment:
      POSTGRES_USER: "${DB_USERNAME}"
      POSTGRES_PASSWORD: "${DB_PASSWORD}"
      POSTGRES_DB: "${DB_DATABASE}"
    volumes:
     - vesselpgsql:/var/lib/postgresql/data
    networks:
     - vessel
volumes:
  vesselpgsql:
    driver: "local"
```

I've omitted the Redis volume within the `volumes` section, but that should be there too.

> **Note**: If you created an environment with MySQL before switching to PgSQL, the volume may have data in it already. If there is data in the volume `vesselmysql` or `vesselpgsql`, then a new database will fail to be initialized.

<a name="beanstalkd" id="beanstalkd"></a>
## Add Beanstalkd for Queues

Vessel doesn't have any queue technology out of the box (the database driver is great for that use case!).

If you want to add a queue driver such as Beanstalkd, you can add additional services to the `docker-compose.yml` file.

Here we'll add an additional [beanstalkd](https://hub.docker.com/r/jonbaldie/beanstalkd/) beanstalkd service, using an image from a popular Docker hub repository.

This one is pretty simple as there are no environment variables nor volumes needed.

```yaml
services:
  beanstalkd:
    image: jonbaldie/beanstalkd:latest
    networks:
     - vessel
```

Within your `.env` file you can set Laravel to use Beanstalkd:

```
QUEUE_DRIVER=beanstalkd
```

Finally, within your `config/queues.php` file, you can set the beanstalkd connection settings:

```php
<?php

return [
    // Set to "beanstalkd" in .env file
    'default' => env('QUEUE_DRIVER', 'sync'),

    'connections' => [
        'beanstalkd' => [
            'driver' => 'beanstalkd',
            'host' => 'beanstalkd', // Match hostname to docker service name
            'queue' => 'default',
            'retry_after' => 90,
        ],
    ],
]
```

Here we just changed the `host` to `beanstalkd`, the hostname used to connect to the Beanstalkd service.

> Don't forget to run `composer require pda/pheanstalk:~3.0` so you can connect to Beanstalkd from PHP.

Then you can run a queue worker using Beanstalkd:

```bash
./vessel artisan queue:work
```

And within your code, your jobs will default to being pushed to the Beanstalkd queue:

```php
dispatch( new App\Jobs\FooJob() );
```
