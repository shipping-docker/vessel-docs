# Vessel

Documentation for [Vessel](https://github.com/shipping-docker/vessel).

## Install

To install deps for this using Node 10, run:

```bash
./develop npm install --unsafe-perm=true
```

[More info here](https://github.com/gulpjs/gulp/issues/2162#issuecomment-384506747).

> This requires the `vessel` project and images `vessel/app` and `vessel/node` to exist on your system.

## Build

Currently it's not in a great state, but I don't have time to improve it to a more modern flow/css framework.

Build static assets:

```bash
./develop gulp [watch]
```

Build jigsaw:

```bash
docker run --rm -it -v $(pwd):/opt -w /opt vessel/app root bash
> ./vendor/bin/jigsaw build
```
