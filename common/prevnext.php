<?php
# Previous & Next Links Creator
# Version 1.0
# June 4, 2008
# Copyright 2008, Bontrager Connection, LLC
#
# Before installing or using, you must agree with the
# Multi-Domain Software License Agreement found at
# http://www.willmaster.com/software/MultiDomainLicenseAgreement.html
# or elsewhere on the willmaster.com website.

# Four places can be customized.

# Place 1 --
# Provide the location and name of the file with 
#    the list of page URLs.

$URLfileLocation = $_SERVER['DOCUMENT_ROOT'].'/common/work-list.txt';

# Place 2 --
# Specify the construction of the "previous" link. Use
#    [[URL]]
#    where the previous page URL shall be inserted.

$PreviousLinkConstruct = '<a class="previous" href="[[URL]]"><svg xmlns="http://www.w3.org/2000/svg" width="16.264" height="28.285" viewBox="0 0 16.264 28.285"><path d="M16.264 2.122l-2.121-2.122-14.143 14.143 14.143 14.142 2.121-2.122-12.021-12.02z"/></svg><span>Last</span></a>';

# Place 3 --
# Specify the construction of the "next" link. Use
#    [[URL]]
#    where the next page URL shall be inserted.

$NextLinkConstruct = '<a class="next" href="[[URL]]"><svg xmlns="http://www.w3.org/2000/svg" width="16.264" height="28.285" viewBox="0 0 16.264 28.285"><path d="M0 26.163l2.121 2.122 14.143-14.142-14.143-14.143-2.121 2.122 12.021 12.021z"/></svg><span>Next</span></a>';

# Place 4 --
# Type what shall between the "previous" and "next" links. 
#    If nothing, just the single-quotes with nothing between.

$PrevNextLinkSeparator = '';

 /* End of customization section. */
/*********************************/

$LinkConstruct = '';
$URLlines = array();
$URLlines = file($URLfileLocation);
if( count($URLlines) < 1 or ( count($URLlines) < 2 and strlen($URLlines[0]) < 2 ) )
{
   if( strpos('/',$URLfileLocation) === 0 ) { $URLfileLocation = substr($URLfileLocation,1); }
   $URLlines = file($_SERVER['DOCUMENT_ROOT'].'/'.$URLfileLocation);
	if( count($URLlines) < 1 or ( count($URLlines) < 2 and strlen($URLlines[0]) < 2 ) )
   {
      echo("Unable to open $URLfileLocation (or it is empty)");
      exit;
   }
}
$me = $_SERVER['REQUEST_URI'];
if( strpos('?',$me) > 0 ) { $me = preg_replace('/\?.*$/','',$me); }
$mehost = 'http://'.$_SERVER['HTTP_HOST'];
$meurl = "$mehost$me";
$beforeURL = $afterURL = '';
$tlines = array();
foreach($URLlines as $l) { if( preg_match('/\w/',$l) ) { array_push($tlines,$l); } }
$URLlines = $tlines;
$tlines = array();
$lines = count($URLlines);
for($i=0; $i<$lines; $i++)
{
   if( ! strpos($URLlines[$i],'://') ) { $URLlines[$i] = $mehost.$URLlines[$i]; }
   if( ! strpos($URLlines[$i],$me) ) { continue; }
   if( strpos($URLlines[$i],$meurl) === 0 )
   {
      if( preg_match('/<.*>/',$URLlines[$i]) ) { $URLlines[$i] = preg_replace('/<.*>/','',$URLlines[$i]); }
      $URLlines[$i] = preg_replace('/\s*$/','',$URLlines[$i]);
      if( $URLlines[$i] == $meurl )
      {
         if( isset($URLlines[$i-1]) )
         {
            $beforeURL = $URLlines[$i-1];
            if( preg_match('/<.*>/',$beforeURL) ) { $beforeURL = preg_replace('/<.*>/','',$beforeURL); }
            $beforeURL = preg_replace('/\s*$/','',$beforeURL);
            $PreviousLinkConstruct = preg_replace('/\[\[URL\]\]/',$beforeURL,$PreviousLinkConstruct);
            $beforeURL = $PreviousLinkConstruct;
         }
         if( isset($URLlines[$i+1]) )
         {
            $afterURL = $URLlines[$i+1];
            if( preg_match('/<.*>/',$afterURL) ) { $afterURL = preg_replace('/<.*>/','',$afterURL); }
            $afterURL = preg_replace('/\s*$/','',$afterURL);
            $NextLinkConstruct = preg_replace('/\[\[URL\]\]/',$afterURL,$NextLinkConstruct);
            $afterURL = $NextLinkConstruct;
         }
         break;
      }
   }
}
if( $beforeURL ) { echo $beforeURL; }
if( $beforeURL and $afterURL ) { echo $PrevNextLinkSeparator; }
if( $afterURL ) { echo $afterURL; }
?>

