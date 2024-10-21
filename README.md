phpMp2
======

A web-based client for [mpd](http://www.musicpd.org), written in
[PHP](http://www.php.net) as a replacement for phpMp.

![phpMp2 sceenshot](screenshot.png "phpMp2 screenshot")

Requirements:
-------------
- mpd
- apache _(or any HTTP server of your choce that can run PHP)_
- php 4.1 or higher _(though I wouldn't use too new a version, since this is a vey old project)_
- gd support in php for graphical sliders

Installation:
-------------
Download phpMp2 and extract into a directory on your webserver:

    tar xjvf phpMp2-0.12.0-rc1.tar.gz

Alternatively, you can use git to get the latest bleeding-edge version:

    git clone https://github.com/whitelynx/phpMp2

Edit `config.php`; This file contains numerous configuration options described
in the comments in the file.

Usage:
------
Surf to the location of the extracted tarball with your web browser.

Testing:
--------
You can use the provided `docker-compose.yml` to run [Mopidy]() and phpMp2 in [Docker]().

Before running, create the `media` and `local` directories:
```bash
mkdir media local
```

You can place media files in the `media` directory and then index it with the `wernight/mopidy` image:
```bash
docker run --rm \
    --device /dev/snd --user 105:100 \
    -v "$PWD/media:/var/lib/mopidy/media:ro" \
    -v "$PWD/local:/var/lib/mopidy/local" \
    -p 6680:6680 \
    wernight/mopidy mopidy local scan
```

Next, bring up the Docker Compose stack:
```bash
docker compose up
```

Finally, you can browse to <http://localhost:80> to use phpMp2.

Credits:
--------
- phpMp written by Warren Dukes (shank)
- phpMp2 written by David H. Bronke ([whitelynx](https://github.com/whitelynx); formerly nosferat)

Bugs:
-----
See `TODO` for information on known bugs that need to be fixed.
If you find a bug, please file an issue in
[this repository](https://github.com/whitelynx/phpMp2).

License:
--------
Ths code is released under the terms of the
[GNU General Public License v3.0](COPYING).

Changes:
--------
version 2b2r1 -> 0.11.0 (change in versioning to match MPD)
- too many changes to list, since there hasn't been a release in ages... now
  supports streams, playlist uploading, search, sort, browsing by tags, playlist
  IDs, graphical sliders, combined sliders, separate styles and layouts,
  on-the-fly configuration, and much more.

version 2b2 -> 2b2r1
- fixed the crossfade function for newest version of mpd.
- fixed bug in volume slider in frames layouts.

version 2b1 -> 2b2
- too many things to remember

version 0.9.1 -> 2b1
- complete rewrite
