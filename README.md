# BoilerPlateDownloader

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

[<img src="https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/JQuery_logo.svg/220px-JQuery_logo.svg.png" style="width: 100px">](https://jquery.com)
[<img src="https://nodejs.org/static/images/logos/nodejs-new-pantone-black.png" style="width: 100px">](https://nodejs.org)
[<img src="https://raw.githubusercontent.com/npm/logos/master/%22npm%22%20lockup/npm-logo-simplifed-with-white-space.png" style="width: 100px">](https://www.npmjs.com)
[<img src="https://bower.io/img/bower-logo.svg" style="width: 100px">](https://bower.io)
[<img src="https://pbs.twimg.com/profile_images/417078109075034112/iruTC031_400x400.png" style="width: 100px">](http://gulpjs.com)

---


[<img src="https://d21ii91i3y6o6h.cloudfront.net/gallery_images/from_proof/11889/small/1461439198/slim-framework-sticker.png" style="width: 100px">](http://www.slimframework.com)
[<img src="https://getcomposer.org/img/logo-composer-transparent2.png" style="width: 100px">](https://getcomposer.org)

---

Powered by [Slim Framework 3 Skeleton Application](https://github.com/slimphp/Slim-Skeleton)
