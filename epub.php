<?php

include_once('toi.json.php');

//echo $demoJSON;
//var_dump(json_decode($demoJSON));

/**
1. Configure the Meta data
2. Loop thru all the pages.
3. Add a chapter for each page.
4. Each article of a page will be a chapter array EPUB class
*/


ob_start();

$fileDir = './';

include_once("lib/epub/EPub.php");
$fileTime = date("D, d M Y H:i:s T");


$book = new EPub();

// Title and Identifier are mandatory!
$book->setTitle("Times of India - Chennai");
$book->setIdentifier("http://timesofindia.indiatimes.com", "URI"); // Could also be the ISBN number, prefered for published books, or a UUID.
$book->setLanguage("en"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
$book->setDescription("Times of India -  Chennai | EPUB Edition for Nook, Kindle, iBooks");
$book->setAuthor("Dr.Palaniraja", "Palani, raja"); 
$book->setPublisher("Times of India", "http://timesofindia.indiatimes.com"); // I hope this is a non existant address :) 
$book->setDate(time()); // Strictly not needed as the book date defaults to time().
$book->setRights("(C) Times of India"); // As this is generated, this _could_ contain the name or licence information of the user who purchased the book, if needed. If this is used that way, the identifier must also be made unique for the book.
$book->setSourceURL("http://localhost/toi/");

$cssData = "";

//$book->addCSSFile("styles.css", "css1", $cssData);

// ePub uses XHTML 1.1, preferably strict.
$content_start =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
	. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
	. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
	. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
	. "<head>"
	. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
	. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
	. "<title>Test Book</title>\n"
	. "</head>\n"
	. "<body>\n";
	
	$content_end = "</body>\n</html>";

$cover = $content_start . "<h1>Times of India - Chennai</h1>\n<h2>".date("D d-M-Y")."</h2>\n\n\n<h5>By: Dr.Palaniraja</h5>"
	. "</body>\n</html>\n";
$book->addChapter("Cover", "Cover.html", $cover);


$toi = json_decode($demoJSON);

$totPages = $toi->totalPages;

//echo 'Total Pages: '.$totPages.'<br>';

$i=1;
	$pgContents = '';
//foreach($toi->sections as $sec)
foreach($toi->page as $page){
	//var_dump($sec);
	//echo $i; echo ' --> ';
	$j = 0;
	unset($pgContents);
	$pgContents = array();
	//$pgContents = '';
	
	foreach($page as $k => $v){
	//	echo $j; echo ', ';
		
		if($k == 'links'){
			//var_dump($v);
			foreach($v as $k1 => $v1){
				//echo '<br />';
				//echo $v1->title.'<hr />'.$v1->post;
				//echo '<hr />';
				$titl = $v1->title;// str_replace('<br>', '', $v1->post);
				$articl = str_replace('<br>', '', $v1->post);	// br tag is not allowed :( also need to remove repeatative title
				$pgContents[] = $content_start . '<h1>'.$titl.'</h1>'.''.$articl.''.$content_end;
				//$pgContents .= '<h1>'.$v1->post.'</h1>'.'<p>'.$v1->post.'</p> <hr/><br /><br /><br /><br />';
			}
			
		}
		$j++;
	}
	//$pgContents = $content_start.$pgContents;
	
	$book->addChapter('Page '.$i, 'Page0'.$i.'.html', $pgContents);
	//echo "<br/>";
	$i++;
}

//$book->addChapter('Page '.$i, 'Page0'.$i.'.html', $pgContents);



$book->setIgnoreEmptyBuffer(true);

$book->finalize(); // Finalize the book, and build the archive.
$zipData = $book->sendBook('TimeOfIndia-Chennai-'.date('d M Y'));