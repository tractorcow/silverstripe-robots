# Robots.txt generation module for Silverstripe

This module provides simple robots.txt generation for Silverstripe, with various configuration
options available.

When a site is not in live mode (such as on a testing domain) it will respectively
block the entire domain, ensuring that (at least respectful) search engines will
refrain from indexing your test site.

This doesn't support complex rules (such as multiple rules for different user agents),
as it's designed to cater to the 99.9% of cases which won't require manual configuration.

## Credits and Authors

 * Damian Mooyman - <https://github.com/tractorcow/silverstripe-robots>

## Requirements

 * SilverStripe 3 or above
 * PHP 5.3

## Installation Instructions

 * Extract all files into the 'robots' folder under your Silverstripe root, or install using composer

```bash
composer require "tractorcow/silverstripe-robots": "3.0.*@dev"
```

 * Make sure you are correctly setting your environment for it to work properly
 * It's also advisable to either install the googlesitemaps module, or to create a physical `sitemap.xml` in your site root.

## Configuration

Basic robots configuration can be found at [_config/robots.yml](_config/robots.yml)

You can add a page or pattern to be blocked by adding it to the disallowedUrls configuration

```yaml
---
Name: myrobotsconfiguration
After: '#robotsconfiguration'
---
Robots:
  disallowed_urls:
    - 'mysecretpage.html'
    - '_private'
    - 'Documents-and-Settings/Ricky/My-Documents/faxes/sent-faxes'
```

Also by default, any page with 'ShowInSearch' set to false will also be excluded. This
can be useful for hiding auxiliary pages like "thanks for signing up", or error pages.

You can turn this off (if you really absolutely think you need to) using the below

```yaml
---
Name: myrobotsconfiguration
After: '#robotsconfiguration'
---
Robots:
  disallow_unsearchable: false
```

By default the module will check for a sitemap file in `/sitemap.xml`, or will assume
one is there if the googlesitemap module is installed. You can set a custom file location
using the below configuration.

```yaml
---
Name: myrobotsconfiguration
After: '#robotsconfiguration'
---
Robots:
  sitemap: '/sitemap_index.xml'
```

## Need more help?

Message or email me at damian.mooyman@gmail.com or, well, read the code!

## License

Copyright (c) 2013, Damian Mooyman
All rights reserved.

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
 * The name of Damian Mooyman may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
