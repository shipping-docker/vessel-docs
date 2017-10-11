---
extends: _layouts.master
section: body
title: Installing Docker
---

<p class="intro">Vessel's only requirment is Docker.</p>

<p>Vessel currently only works on Macintosh and Linux. It uses a bash script to run Docker commands.</p>

> Window support may come in the future. It will require [running Hyper-V](https://docs.microsoft.com/en-us/virtualization/hyper-v-on-windows/reference/hyper-v-requirements) which is not supported on Windows 10 **Home** edition.

<table class="table">
    <thead>
        <tr>
            <th>Mac</th>
            <th>Linux</th>
            <th>Windows</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <p>Install Docker on <a href="https://docs.docker.com/docker-for-mac/install/">Mac</a></p>
            </td>
            <td>
                <p>Install Docker on <a href="https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/">Ubuntu</a></p

                <p>Install Docker on <a href="https://docs.docker.com/engine/installation/linux/docker-ce/centos/">CentOS</a></p>
            </td>
            <td>
                <p>Not Currently Supported</p>
            </td>
        </tr>
    </tbody>
</table>

## Linux Users

**First**, the easiest way to install Docker on Linux is the following:

```bash
curl -fsSL get.docker.com | sudo sh
```

**Second**, be sure to add group `docker` to your user so you don't need `sudo` to run docker commands:

```bash
sudo usermod -aG docker your-user
```
