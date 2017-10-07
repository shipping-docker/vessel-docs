---
extends: _layouts.master
section: body
title: Logging
---

<p class="intro">Docker can take the stdout and stderr output of a process and pipes it out for us to see using its logging mechanism.</p>

## Docker Logs

Our containers are running PHP, Nginx, MySQL, and Redis. These are all outputting basic logging information to Docker's logging mechanism.

You can see most log details running some of the following commands:

```bash
# Check log output of a container service
./vessel logs # all container logs
./vessel logs app # nginx | php logs
./vessel logs mysql # mysql logs
./vessel logs redis # redis logs
```

You can `tail` the log files as well to see new output as it is generated:

```bash
## Tail the logs to see output as it's generated
./vessel logs -f # tail all logs
./vessel logs -f app # tail nginx & php logs
./vessel logs -f mysql # tail mysql logs
./vessel logs -f redis # tail redis logs
```

## Laravel Logs

The container should be able to write to your application files. The Laravel log file at `storage/logs/laravel.log` will get written to as usual. You can inspect that file as you usually would.

However, if you'd like, you can also inspect it from within the app container:

```bash
# Tail Laravel Logs
./vessel exec app tail -f /var/www/html/storage/logs/laravel.log
```

Note the file path of the application will always be `/var/www/html` within the container.

## MySQL General Log

By default, MySQL only has it's regular logging enabled (essentially just the error log).

However, sometimes it's useful to enable MySQL's "general" log, which logs every query it receives. This slows down MySQL a bit (regardless of if it's running within a container or not), but it useful to temporarily debug query issues.

To enable this, simply uncomment a few lines within the `docker-compose.yml` file and restart the containers:

Within file `docker-compose.yml`, find the section defining the `mysql` service:

```yaml
  mysql:
    image: mysql:5.7
    ports:
     - "${MYSQL_PORT}:3306"
    environment:
      MYSQL_ROOT_PASSWORD: "${DB_PASSWORD}"
      MYSQL_DATABASE: "${DB_DATABASE}"
      MYSQL_USER: "${DB_USERNAME}"
      MYSQL_PASSWORD: "${DB_PASSWORD}"
    volumes:
     - vesselmysql:/var/lib/mysql
     # - ./docker/mysql/conf.d:/etc/mysql/conf.d
     # - ./docker/mysql/logs:/var/log/mysql
    networks:
     - vessel
```

Uncomment the `volumes` section which are commented out to include the configuration file that enables the general log, and shares a local directory with the mysql logging location.

```yaml
  mysql:
    image: mysql:5.7
    ports:
     - "${MYSQL_PORT}:3306"
    environment:
      MYSQL_ROOT_PASSWORD: "${DB_PASSWORD}"
      MYSQL_DATABASE: "${DB_DATABASE}"
      MYSQL_USER: "${DB_USERNAME}"
      MYSQL_PASSWORD: "${DB_PASSWORD}"
    volumes:
     - vesselmysql:/var/lib/mysql
     - ./docker/mysql/conf.d:/etc/mysql/conf.d
     - ./docker/mysql/logs:/var/log/mysql
    networks:
     - vessel
```


Then restart the containers to see that take affect:

```bash
# Restart the containers
./vessel restart

# Or stop them and start them completely
# to have it destroy and create new container instances
./vessel stop
./vessel start
```



