# BoilerPlateDownloader

[![Heroku](https://heroku-badge.herokuapp.com/?app=boilerplatedownloader)](https://boilerplatedownloader.herokuapp.com)
[![Codeship Status for Starli0n/BoilerPlateDownloader](https://codeship.com/projects/5c379690-4b5e-0134-e294-3eedfb4d574d/status?branch=master)](https://codeship.com/projects/169900)
[![Coverage Status](https://coveralls.io/repos/github/Starli0n/BoilerPlateDownloader/badge.svg?branch=master)](https://coveralls.io/github/Starli0n/BoilerPlateDownloader?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/57c43069968d640039516a23/badge.svg?style=flat)](https://www.versioneye.com/user/projects/57c43069968d640039516a23)

Download files with a boiler plate server using a REST Api.

* `[GET] /hello/{name}` return `Hello <name>` message
* `[GET] /list` return the list of the downloaded files
* `[PUT] /download` download a file
* `[DELETE] /delete` delete file(s)

## How to install

Start with the next chapiter if it is your first intall and if node.js is not installed.

```
> git clone https://github.com/Starli0n/BoilerPlateDownloader boilerplatedownloader
> cd boilerplatedownloader
> composer update
> npm install
> bower install
> gulp :deploy
> gulp :test
```

For PHP Tools for Visual Studio

`BoilerPlateDownloader.phpproj > Properties > Application > Start action`

* `Specific page` `public/index.html`
* `Specific page` `publish/index.html`
* `Specific page` `boilerplatedownloader/public/index.html`


## First Install of the toolchain

* Install Node.js
````
> npm install -g npm
> npm install -g bower
> npm install -g gulp-cli
````

On Windows `npm` should be found here:
`C:\Users\Starli0n\AppData\Roaming\npm`


## Settings Proxy

**For npm**

````
> npm config set analytics false
> npm config set proxy http://user:pass@proxy.com:port
> npm config set https-proxy http://user:pass@proxy.com:port
````

In your `Home` directory, on Windows it is `C:\Users\Starli0n`, you should have


`.npmrc`
````
analytics=false
proxy=http://user:pass@proxy.com:port
https-proxy=http://user:pass@proxy.com:port

````

**For bower**

Set the file manually at the same location

`.bowerrc`
````
{
    "analytics": false,
    "proxy": "http://user:pass@proxy.com:port",
    "https-proxy": "http://user:pass@proxy.com:port"
}
````


---

<a href="https://jquery.com"><img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/JQuery_logo.svg/220px-JQuery_logo.svg.png" width="100"></a>
<a href="https://nodejs.org"><img src="https://nodejs.org/static/images/logos/nodejs-new-pantone-black.png" width="100"></a>
<a href="https://www.npmjs.com"><img src="https://raw.githubusercontent.com/npm/logos/master/%22npm%22%20lockup/npm-logo-simplifed-with-white-space.png" width="100"></a>
<a href="https://bower.io"><img src="https://bower.io/img/bower-logo.svg" width="100"></a>
<a href="http://gulpjs.com"><img src="https://pbs.twimg.com/profile_images/417078109075034112/iruTC031_400x400.png" width="100"></a>

---

<a href="http://www.slimframework.com"><img src="https://d21ii91i3y6o6h.cloudfront.net/gallery_images/from_proof/11889/small/1461439198/slim-framework-sticker.png" width="100"></a>
<a href="https://getcomposer.org"><img src="https://getcomposer.org/img/logo-composer-transparent2.png" width="100"></a>

---

Powered by [Slim Framework 3 Skeleton Application](https://github.com/slimphp/Slim-Skeleton)
