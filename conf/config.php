; <?php

[Assetic]

; Set this to the path to your YUI Compressor
; http://developer.yahoo.com/yui/compressor/
yui_compressor = /Users/lux/Sites/pink/e2/yuicompressor-2.3.6.jar

; Set this to the path to your SASS parser
; http://sass-lang.com/
sass_parser = /usr/bin/sass

; Set this to the path to your CoffeeScript compiler
; http://jashkenas.github.com/coffee-script/
coffeescript = /usr/local/bin/coffee

; Filesystem path to save the compiled files into.
; Note: Omit the trailing slash.
save_path = "cache/assetic"

; Web path to the compiled files. Note: Omit the
; trailing slash.
web_path = "/cache/assetic"

[Admin]

handler = assetic/admin
name = Assetic

; */ ?>