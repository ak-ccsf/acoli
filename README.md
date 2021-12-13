# aqoli - Aggregated Quality of Life Indices

![License: AGPL.](https://img.shields.io/badge/license-AGPL-%233897f0)
[![Discussion on Matrix.](https://img.shields.io/matrix/2021_fall_cs195_general:matrix.org?label=%23aqoli&logo=matrix)](https://matrix.to/#/#2021_fall_cs195_general:matrix.org)

## Navigation

- [aqoli project on sourcehut (to be fully migrated)][project]
- › aqoli website code repository
- [numbeo-scraping code repository][numbeo-scraping repo]
- [Issue tracker][trackers]
- [Mailing lists][lists]
- [Full documentation][docs folder]
- [Discussion on Matrix][matrix]

[project]: https://sr.ht/~akspecs/aqoli
[aqoli website repo]: https://github.com/ak-ccsf/acoli
[numbeo-scraping repo]: https://git.sr.ht/~akspecs/numbeo-scraping
[trackers]: https://sr.ht/~akspecs/aqoli/trackers
[lists]: https://sr.ht/~akspecs/aqoli/lists
[docs folder]: https://git.sr.ht/~akspecs/aqoli/tree/master/docs

## aqoli - a front-end web interface to quality of life living metrics

browse and compare aggregated quality of life indices with the help of
this website

Join the aqoli/numbeo scraping discussion room on Matrix:
[#2021 fall cs195 general:matrix.org][matrix]

[matrix]: https://matrix.to/#/#2021_fall_cs195_general:matrix.org

See also: [numbeo-scraping, a set of scrapy spiders that crawl numbeo.com.][numbeo-scraping repo]

[numbeo-scraping repo]: https://git.sr.ht/~akspecs/numbeo-scraping

## Installing

A local instance can be spun up by running the following:
```
php -S 0.0.0.0:8000
```

The site's code is tested against php version 8+, though php version 7
may still work (untested). Ensure that you have the sqlite3 php
extension installed and enabled.

## Credits & license information

All of aqoli's code uses the
[AGPL 3.0 license](https://choosealicense.com/licenses/agpl-3.0/). In
short, this means that if you make any modifications to the code and
then publish the result (e.g. by hosting the result on a webserver),
you must publicly distribute your changes and declare that they also
use AGPL 3.0.
