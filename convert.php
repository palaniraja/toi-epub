<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>TOi</title>
	<style type="text/css">
	.console{
		white-space:pre;
		color:white;
		background:black;
		font-family:Monaco;
		height:200px;
		overflow-y:auto;
	}
	.common{
		font-family:"Lucida Grande";
		
	}
	body{
		font-size:75%;
	}
	</style>
</head>
<body class="common">
<h3>Toi - EPub/Mobile/Env friendly</h3>
	<ol>
		<li>Load jQuery</li>
		<li>Load mpaper to find no. of pages</li>
		<li>Load thumbnails of all pages</li>
		<li>Load Mobile site front page</li>
		<li>Get the No. of sections and links</li>
		<li>Fetch each section and build pages with articles &amp; links </li>
		<li>Fetch all texts from each links created by above step</li>
	</ol>
	<input type="button" id="doit" onclick="doStuff();" value="Fetch.." />
	<input type="button" id="doit" onclick="step6();" value="Step6.." />
	<input type="button" id="doit" onclick="step7();" value="Step7.." />
	<p class="console"></p>
	
 <iframe id="ifr"></iframe>

<textarea id="txtJSON" rows="15" cols="100"></textarea>

<script type="text/javascript" src="lib/js/jquery.js"></script>
<script type="text/javascript" src="lib/js/jquery.urlencode.js"></script>
<script type="text/javascript" src="lib/js/jquery.iframe.js"></script>
<script type="text/javascript" src="lib/js/json2.js"></script>

<script type="text/javascript">
function clog(s, clr){
	if(clr)
		$('.console').html('');
	else	
		$('.console').html($('.console').html() + "\n" + s);
}

function getParamValue(u)
{
    var vars = [], hash;
    var hashes = u.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

var toi = {};

toi.sections = {};

var curSection = 0;

toi.page = {};

var today = new Date();
var year = today.getYear()+1900;
var date = today.getDate();
if(date<10)
	date = '0'+date;
var month = today.getMonth()+1;
if(month < 10)
	month = '0'+month;
	
	
var phpProxy = 'ba-simple-php-proxy.php?mode=native&url=';

$(document).ready(
	function(){
		//step 1
		clog('Working on issue dated '+year+'-'+month+'-'+date);
		clog('Step 1 done.');		
	}
);
	

	
	function callIframe(url, callback) {
	    $(document.body).append('<iframe id="if"></iframe>');
	    $('#if').attr('src', url);

	    $('#if').load(function() 
	    {
	        callback(this);
	    });
	}
	
	
function doStuff(){
	//step 2
	pUrl = 'http://mobilepaper.timesofindia.com/touch/getpage.aspx?pageid=1&pagesize=20&sections=yes&edid=TOICH&edlabel=TOICH&mydateHid='+date+'-'+month+'-'+year+'&pubname=Times+of+India+-+Chennai&edname=Chennai&publabel=TOI';
pUrl = $.URLEncode(pUrl);

pUrl = phpProxy+pUrl;

$("iframe").src(pUrl,function (dur){

	totPages = $('.divbox', $('iframe').get(0).contentDocument).length - 2;  //there are 2 "next" links in first page
	toi.totalPages = totPages;
	clog('Step 2 done.');
	for(i=1;i<=totPages;i++){
		i1 = i;
		if(i<10)
			i1 = '0'+i;
		thumb = 'http://mobilepaper.timesofindia.com/Repository/TOICH/'+year+'/'+month+'/'+date+'/'+i+'/Img/Pg0'+i1+'_20.png';
		//build the array
		toi.page[i] = {};
		toi.page[i].index = i;
		toi.page[i].thumb = thumb;
		toi.page[i].articles = {};
		toi.page[i].links = new Array();
	}
		clog('Step 3 done.');
		
		step4();
});

}


function step4(){
	pUrl2 = 'http://mobiletoi.timesofindia.com/htmldbtoi/TOICH/'+year+''+month+''+date+'/TOICH_articles__'+year+''+month+''+date+'.html';
	pUrl2 = $.URLEncode(pUrl2);
	pUrl2 = phpProxy+pUrl2;
	//callIframe('ba-simple-php-proxy.php?mode=native&url='+pUrl2, function(p){
	$("iframe").src(pUrl2, function(p){

		clog('Step 4 done.');
		toi.totSections = $('a.pda[href*="sectname"]',  $('iframe').get(0).contentDocument).length/2 -1; //sections are repeated twice, one at the top another in the bottom. and ignore the last section "special report"

		$('a.pda[href*="sectname"]', $('iframe').get(0).contentDocument).each(function(i, elem){
	
			if( i < toi.totSections ){ //traverse first set only.
				toi.sections[i] = {};
				toi.sections[i].title = $(elem).html();
				toi.sections[i].link = $(elem).attr('href');
			}
			else
				return; //done once all top nav are traversed

});


	clog('Step 5 done.');
	//step6();

	
	});
	//step6(0);
}


function step6(curSec) {

		// use the existing 
	if(curSection < toi.totSections){
	
	//	clog('Start loading section: '+ (curSection+1));
			pUrl3 = toi.sections[curSection].link;
			pUrl3 = $.URLEncode(pUrl3);
			//clog(pUrl3);
			pUrl3 = phpProxy+pUrl3;
			$("iframe").src(pUrl3, fetchSection);
	
	}
	else{
		clog('Step 6 done.');
		//step7();
	}
		
		
}	


function fetchSection(p){
	//clog('Section '+(curSection+1)+' is loaded.');
	fetchPagesAndArticles();

}


function fetchPagesAndArticles(){
	
	prevpage = 1;
	j = 0;
	
	//clog('Fetching PagesAndArticles for section:'+(curSection+1));

	//get article titles and links for the first page
	$('a.pda[href*="articleid"]', $('iframe').get(0).contentDocument).each(function(i, elem){
		
		u = $(elem).attr('href');
		t = $(elem).html();
		//pag = u.match(/pageid=(\d)+/)[1];
		pag = getParamValue(u)['pageid'];
		pag = parseInt(pag);	//	ee = 'toi.page['+pag+'].links['+i+']';	//clog(ee);
		
		if(pag != prevpage){
			j=0;
			prevpage = pag;
		}
		
		toi.page[pag].links[j] ={};
		toi.page[pag].links[j].href = $.URLEncode(u);
		toi.page[pag].links[j].title = t;
		j++;
		});
		
	//	clog('fetching done.');
		curSection++;
		step6(curSection);


}

var curPage = 1;
var curArticle = 0;
var uc = 0;

function step7(){
	//loop all pages var and  find article links,
	//load each article links and fetch the text of the page.
	if(curPage <= 5){	//toi.totalPages //2
		//console.log('Page: ' + curPage + ' Article: ' + curArticle);
		u = toi.page[curPage].links[curArticle].href;
		u = phpProxy+u;
		//$("iframe").src('test1.php?q='+uc, fetchPost);
		$("iframe").src(u, fetchPost);
		//uc++;
	}else{
		clog('Step 7 done.');
	}	
}




function fetchPost(p){
	pgContent = '';
	//console.log($('iframe').get(0).contentDocument);
	$('font.pda', $('iframe').get(0).contentDocument).each(function(k, elem){
		pgContent = pgContent + $(this).html();
		//console.log(pgContent);
	});

	toi.page[curPage].links[curArticle].post = Array();
	toi.page[curPage].links[curArticle].post = pgContent;
	//clog('fetching done for uc:' + uc);
	if(curArticle < (toi.page[curPage].links.length-1)){
		curArticle++;
	}
	else{
		curArticle=0;
		curPage++;
		do{	//check if the next page has atleast 1 article or forward to next 
			if(toi.page[curPage].links.length==0){
				clog('Page: '+curPage+' has no articles');
				curPage++;
			}else{
				break;
			}
		}while(1);
	}
	step7();
	
}
</script>
</body>
</html>