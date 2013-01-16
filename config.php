<?php
/**
Times Of India - Chennai 
EPUB - Mobile/environment friendly paper

(C) 2010 - palaniraja
*/


/**
here goes the configuration vars

*/
//  http://mobiletoi.timesofindia.com/htmldbtoi/TOICH/20100812/TOICH_articles__20100812.html
//  TOICH - Chennai
$REGION = 'TOICH';
$FRONT_PAGE = 'http://mobiletoi.timesofindia.com/htmldbtoi/'.$REGION.'/'.date("Ymd").'/'.$REGION.'_articles__'.date("Ymd").'.html';

echo $FRONT_PAGE;

$ARTICLE_SECTION = '';