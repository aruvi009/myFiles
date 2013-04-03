<?php

/*

Taken From : http://shinylittlething.com/2010/01/20/css-minification-on-the-fly/

The Rewrite Rules

I saved my minifier PHP script into a directory named /m/ on my site’s root. 
All I want know is to minify all my css files without having to edit all the link tags and @import in my code. 
So I’ve written this rewrite rule to pass any request to a file ending in .css to my PHP minifier; 
It’s not a redirect it’s just a rewriting so I can keep my paths unmodified.

**********************************************************************
# Avoids infinite loops
RewriteCond %{REQUEST_URI}	!^/inc/$

# Pass the complete path to any css file to the php minifier
RewriteRule ^/(.*\.css)$	/inc/cssminify.php?css=$1 [NC]
**********************************************************************
*/

$time_start = microtime_float();

header("Content-type: text/css");
header("X-Powered-by: CSS Minifier v0.1", true);
header("Cache-Control: no-cache, must-revalidate");

$file = isset( $_GET[ 'css' ] ) ? $_GET[ 'css' ] : '';

$cachefile = 'cache/'.basename($file);
$cachetime = 24 *  60 * 60;

$age = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);

header("X-Original-Generated-At: " . nicetime($age));

if (file_exists($cachefile)
&& (time() - $cachetime < filemtime($cachefile))) {
        header("Expires: " .
			gmdate("D, d M Y H:i:s", filemtime($cachefile) + $cachetime) .
			" GMT", true);
 $time_end = microtime_float();
 $time = $time_end - $time_start;
 header("Cache-Status: cached");
 header("X-Runtime: ". $time . "sec");

 include($cachefile);
 exit;
}

header("Cache-Status: regenerated");
ob_start(); // start the output buffer
$fp = fopen($cachefile, 'w'); // open the cache file for writing

$content = file_get_contents( $_SERVER['DOCUMENT_ROOT'] . $file );
echo minify($content);
echo "/* Cached ".date('jS F Y H:i', filemtime($cachefile))." */";
fwrite($fp, ob_get_contents()); // save the contents of output buffer to the file
fclose($fp); // close the file

$time_end = microtime_float();
$time = $time_end - $time_start;
header( "X-Runtime: ". $time . "sec" );

ob_end_flush(); // Send the output to the browser

function minify($data) {
 $data = preg_replace( '#/\*.*?\*/#s', '', $data );
 // remove single line comments, like this, from // to \\n
 $data = preg_replace('/(\/\/.*\n)/', '', $data);
 // remove new lines \\n, tabs and \\r
 $data = preg_replace('/(\t|\r|\n)/', '', $data);
 // replace multi spaces with singles
 $data = preg_replace('/(\s+)/', ' ', $data);
 //Remove empty rules
 $data = preg_replace('/[^}{]+{\s?}/', '', $data);
 // Remove whitespace around selectors and braces
 $data = preg_replace('/\s*{\s*/', '{', $data);
 // Remove whitespace at end of rule
 $data = preg_replace('/\s*}\s*/', '}', $data);
 // Just for clarity, make every rules 1 line tall
 $data = preg_replace('/}/', "}\n", $data);
 $data = str_replace( ';}', '}', $data );
 $data = str_replace( ', ', ',', $data );
 $data = str_replace( '; ', ';', $data );
 $data = str_replace( ': ', ':', $data );
 $data = preg_replace( '#\s+#', ' ', $data );

 return $data;
}

function microtime_float() {
 list($usec, $sec) = explode(" ", microtime());
 return ((float)$usec + (float)$sec);
}

function nicetime($date)
{
 if(empty($date)) {
 return "No date provided";
 }

 $periods         = array(
							"second", "minute", "hour",
							"day", "week", "month", "year", "decade"
					);
 $lengths         = array(
							"60","60","24","7",
							"4.35","12","10"
					);

 $now             = time();
 $unix_date         = $date;

 // check validity of date
 if(empty($unix_date)) {
 return "Bad date";
 }

 // is it future date or past date
 if($now > $unix_date) {
 $difference     = $now - $unix_date;
 $tense         = "ago";

 } else {
 $difference     = $unix_date - $now;
 $tense         = "from now";
 }

 for($j = 0; $difference >= $lengths[$j]
		&& $j < count($lengths)-1; $j++) {
 	$difference /= $lengths[$j];
 }

 $difference = round($difference);

 if($difference != 1) {
 $periods[$j].= "s";
 }

 return "$difference $periods[$j] {$tense}";
}


?>