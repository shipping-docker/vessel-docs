---
extends: _layouts.master
section: body
title: Customizing Vessel
---

<p class="intro">Under the hood, Vessel is just Docker.</p>

If you're familiar with Docker and Docker Compose, you can basically do anything you want to Vessel.

To give you an idea of what's possible, here are some example customizations.

<a name="pgsql" id="pgsql"></a>
## Using PostgreSQL over MySQL

If you prefer PostgreSQL instead of MySQL, you can change the database container to use the [official PostreSQL image](https://hub.docker.com/_/postgres/).

Within your `.env` file, change the database items as needed:

```
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
```

This will prepare Laravel to connet to a PostgreSQL database, listening on port 5432.

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
