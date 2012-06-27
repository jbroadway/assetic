This is an app for the [Elefant CMS](http://github.com/jbroadway/elefant)
that pre-compiles and compresses Javascript and CSS using the
[Assetic](https://github.com/kriswallsmith/assetic) library.

It also supports compiling the following formats

* [SASS](http://sass-lang.com/) -> CSS (.sass files)
* [LESS](http://lesscss.org/) -> CSS (.less files)
* [CoffeeScript](http://coffeescript.org/) -> Javascript (.cs or .coffee files)
* [Handlebars](http://handlebarsjs.com/) -> Compiled templates

### Installation

1. Drop this app into your `apps/` folder.
2. Open the file `apps/assetic/conf/config.php` and set the paths to your [YUI Compressor](http://developer.yahoo.com/yui/compressor/) (used for compressing output), SASS, CoffeeScript, and Handlebars compilers.

### Usage

To use Assetic on a single script:

```html
<script src="{# assetic/js/my_script.js #}"></script>
```

This will output something like:

```html
<script src="/cache/assetic/js_my_script.js?v=12"></script>
```

Similarly, you can do the same with CSS files:

```html
<link rel="stylesheet" link="{# assetic/css/style.css #}" />
```

Will produce:

```html
<link rel="styelsheet" link="/cache/assetic/css_style.css?v=12" />
```

To use Assetic on several scripts or stylesheets at a time:

```html
<head>
{# assetic/myscripts?js[]=js/jquery.js&js[]=js/jquery.verify_values.js #}
</head>
```

This will produce:

```html
<script src="/cache/assetic/myscripts.js?v=12"></script>
```

> Note: Change `myscripts` to the name to use to save the cache file as. Otherwise, `all.js` will be used.

File lists can also be written over multiple lines, like this:

```html
<head>
{# assetic/myscripts
	?js[]=js/jquery.js
	&js[]=js/jquery.verify_values.js #}
</head>
```

### Recompiling later

To regenerate the scripts, log into Elefant and go to `Tools > Assetic` and
click `Recompile Assets`. This will change the modification time on all templates,
so that they are regenerated the next time they are run in the browser.
The `?v=` number will also regenerate so that browsers will automatically
use the latest version at all times.

For development, you can also change the tags to use Elefant's `{! !}` tags instead,
which will load the Assetic compilation process anew on each request:

```html
<script src="{! assetic/js/my_script.js !}"></script>
```

> It is smart enough to not regenerate the cache if the original files haven't changed, to reduce page load times during development.

### How it works

The `{# #}` template tag will render the scripts the first time the layout is loaded
and hard-code the resulting HTML into the template for subsequent requests, so the
handler is only called the first time. This makes this plugin very fast for
serving your optimized CSS and Javascript, since after the first load, the
compiled scripts are called directly, bypassing the plugin entirely for subsequent
requests.

> You can achieve additional optimization by enabling GZIP output in your web server configuration.
