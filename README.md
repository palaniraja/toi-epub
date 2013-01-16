toi-epub
========

Times of India - Printed edition epub generator


###How does it work

* It loads the mpaper in an iframe (test.php)
* Uses jQuery to parse the iframe's html and get the article url.
* Links of articles are combined as a JSON object
* JSON dictionary then POST'd to (convert.php) which uses an opensource epub library to create the ebook version.

_**Note**:
I wrote this script in Aug'2010 and I haven't tested the script recently. Kindly check the 
[Dev Notes text][devnotes] for more info._

[devnotes]: https://github.com/palaniraja/toi-epub/blob/master/Dev-Notes.txt


####Update your hosts file

    127.0.0.1	timeslog.timesofindia.com
    127.0.0.1	timeslog.indiatimes.com

####Preview

![Preview](https://github.com/palaniraja/toi-epub/blob/master/photo/IMG_0566.JPG?raw=true "Preview")

