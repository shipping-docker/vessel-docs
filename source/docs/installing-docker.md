---
extends: _layouts.master
section: body
title: Installing Docker
---

<p class="intro">Vessel's only requirment is Docker.</p>

<p>Vessel uses a bash script to run Docker commands, it works on Macintosh, Linux and Windows.</p>

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
                <p>Install Docker on <a href="https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/">Ubuntu</a></p>
                <p>Install Docker on <a href="https://docs.docker.com/engine/installation/linux/docker-ce/centos/">CentOS</a></p>
            </td>
            <td>
                <p>Install Docker on <a href="https://docs.docker.com/docker-for-windows/install/">Windows</a></p>
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
