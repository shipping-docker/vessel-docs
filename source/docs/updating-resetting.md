---
extends: _layouts.master
section: body
title: Updating and Resetting
---

<p class="intro">If you want to update Vessel from an older version, or burn it all down and start over, read on here!</p>

<a name="updating" id="updating"></a>
## Updating Vessel

Updating Vessel involves a steps around getting the latest pubslished assets from the Vessel package, and rebuilding your containers if needed.

**1. Update the Composer Package**

You can simply update all your packages to the latest:

```bash
./vessel composer update
```

If there's a new major version of Vessel, you can remove the old and install the new. For example, to update from Vessel 1.x to 2.0:

```bash
./vessel composer remove shipping-docker/vessel
./vessel composer require shipping-docker/vessel:~2.0
```

**2. Publish Latest Vessel Files**

Vessel's published assets may be updated as well, so you can get the latest by deleting the old and re-publishing the new ones:

```bash
# Delete the "vessel" command
rm vessel
# Delete docker-compose.yml and the docker directory
rm -rf docker*
```

Then you can re-publish the new Vessel files:

```bash
# Note vessel isn't available to use here at this point
php artisan vendor:publish --provider="Vessel\VesselServiceProvider"
bash vessel init
```

> **Note**: If you have customized Vessel files, you'll need to save those customizations and re-implement them into the latest Vessel files.

**3. Re-initialize Vessel**

```bash
bash vessel init
```

**4. Optional: Rebuild Containers**

Updates may make changes to the containers themselves. If you want to rebuild your containers, you can do the following without losing any MySQL or Redis data:

```bash
# Ensure containers are not running
./vessel down

# Delete the Docker images built previously
docker image rm vessel/app
docker image rm vessel/node

# Rebuild the images
./vessel build

# Start Vessel back up
./vessel start
```

Rebuilding the containers may take a few minutes, especially with a slower internet connection.


<a name="resetting" id="resetting"></a>
## Resetting Vessel

If you need or want a completely fresh start from Vessel, here's how!

**1. Ensure all instances of Vessel are stopped**

You may have multiple projets running with Vessel - ensure each are stopped:

```bash
# See what containers are running globally:
docker ps

# Head to each Vessel instance running and stop them
cd ~/Path/To/Project
./vessel down
```

**2. Delete Vessel Images, Update Official Images**

Vessel creates 2 images and uses 2 other images from Docker Hub. Here we'll delete the Vessel images and pull down the latest images for the others:

```bash
# Delete Vessel-built images
docker image rm vessel/node
docker image rm vessel/app

# Pull down latest base images
docker pull ubuntu:16.04
docker pull mysql:5.7
docker pull node:latest
```

**3. Delete Old Data**

Assuming you want to delete your old MySQL and Redis data as well, you can here. *If you don't want to delete that data, you can skip this step.*

To delete your data, we need to find your created Volumes and remove them:

```bash
# Find vessel volumes
docker volume ls

# Remove any you want (ending in *_vesselmysql or *_vesselredis)
docker volume rm foo_vesselmysql
docker volume rm foo_vesselredis

# Or delete all volumes ever created:
## 🔴 THIS IS DANGEROUS
##    You're deleting all redis/mysql data for every project!
docker volume rm (docker volume ls -q)
```

**4. Rebuild Images**

You can then head to any project with Vessel in it and rebuild the images used by Vessel.

```bash
cd ~/Path/To/Project
./vessel build
```

Building can take a few minutes, depending on internet speed.

> If you want to update to refresh the Vessel files, you can follow the steps from the [Updating docs](#updating) above.



