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
		font-size:80%;
	}
	</style>
</head>
<body class="common">
<h3>Sequential - queing of iframe test page</h3>

	<input type="button" id="doit" onclick="doStuff();" value="Fetch.." />
	<p class="console"></p>
	
 <iframe id="ifr"></iframe>

<script type="text/javascript" src="lib/js/jquery.js"></script>
<script type="text/javascript" src="lib/js/jquery.urlencode.js"></script>
<script type="text/javascript" src="lib/js/jquery.iframe.js"></script>

<script type="text/javascript">
function clog(s){
	$('.console').html($('.console').html() + "\n" + s);
}

/*
$(document).ready(
	function(){
	
	}
);
*/	

var nTimes = 5;

	
function doStuff()
{
	$("iframe").src('test1.php?q='+nTimes, function(p){
		clog('Times: '+nTimes);
		if(nTimes){
			doStuff(nTimes--);
		}
	});

}
</script>
</body>
</html>