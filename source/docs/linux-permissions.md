---
extends: _layouts.master
section: body
title: Linux &amp; Permissions
---

<p class="intro">Unlike on Mac or Windows, Docker on Linux has no layer of virtualization. As a result of this (and how containers work in general), operations run within the Docker container on shared files are affected by the owner and permissions of those files. We'll see how here.</p>

> One way to understand Docker is to think of it as a tool to run *processes* in a contained space on any machine, where as virtual servers are a way to run *entire servers* in a contained space on any machine.

<a name="linux" id="linux"></a>
## Docker and Linux Permissions

Say you have a container running PHP as user `www-data`. That user `www-data` may have a UID (user id) of 33.

**If you're sharing files** between the host file system and the container (as we do with Vessel), one potentially surprising element is that when PHP writes to a shared file (such as `laravel.log`), it will still write to that file as user ID 33. It does this even if UID 33 does not exist on the host Linux system, or even if the UID belongs to a different user.

If our code is owned by another user, for example `fideloper` with a UID of 1000, then PHP run within the container likely won't have permission to write to the code files!

In terms of file permissions, it's just as if Docker is not used at all.

> Remember this is specific to running Docker on Linux. The layer of virtualization used on MacOS hides this behavior; Mac users don't have to worry about it!

To get around this issue, Vessel changes the UID used to run processes within the PHP and Node containers. Here's how.

<a name="php" id="php"></a>
## PHP-FPM

If PHP is run as user `www-data` with a UID of 33, and your editing your code as user `fideloper` with UID 1000, then PHP in the container likely won't be able to write to your code files.

To combat this, the following things are done:

Vessel detects the UID of your current user (e.g. user `fideloper` on your Linux machine) and passes it into the ENTRYPOINT script that runs when the app (PHP) container is started.

The app container then runs PHP-FPM as that user ID, so that PHP and the user owning your code files match. This allows PHP to write to your files as needed.

<a name="node" id="node"></a>
## Node

The Node container also creates files in within `node_modules`, and may edit/create the `packages.json` file, the yarn lock file, and your compiled static assets.

To prevent these generated files from being created as user `root` (the default user used within any container), we run the NodeJS container as user `node` (as [documented as a best practice](https://github.com/nodejs/docker-node/blob/c37d5e87fa6d46c0e387f73161b056bbf90b83aa/docs/BestPractices.md#non-root-user) by the containers creators).

However, Vessel changes user node's UID to match your current user (e.g. user `fideloper`). Since the UID of node matches our current user's UID, the files get created on your host machines file system as the correct user name!

The UID is detected at build-time (when an image is created from the `Dockerfile`) and so are hard-coded after that. If you change users on your workstation and thus have a new UID, you can [rebuild the Node container](/docs/docker-usage#rebuild-images) to get these values updated.

