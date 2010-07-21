<?php

if($_POST['longUrl'] && isset($_POST['longUrl']) && $_POST['submit']) {

     require_once('shortUrl.factory.class.php');

     $longUrl = $_POST['longUrl'];

     $site = $_POST['shorts'];

    if($site === 'bitly') {

           $user = "thinkphp";

           $apikey = "R_0cf8415f0c3f9fcfd867ce7613e43fc7";

    } else if($site == 'digg') {

           $apikey = "http://thinkphp.ro";
    }

                  try {
                         $ob = Factory::create($site,$longUrl,$user,$apikey);          

                         $shortUrl = $ob->getTinyUrl();

                      }catch(Exception $e) {$shortUrl = $e->getMessage();}

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Shorten, share and track your links</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style type="text/css">
html,body{background:#C5C7BE;margin:0;padding:0;}

#doc{background:#fff;border:1em solid #fff;}

h1{font-family:Calibri,Arial,Sans-serif;font-size:200%;margin:0 0 .5em 0; padding:0;}

h2{font-family:Calibri,Arial,Sans-serif;font-size:150%;margin:1em 0;padding:0;font-weight: bold}

select,input{border: 1px solid #ccc;padding: 4px;margin: 4px;font-size: 40px;text-align: center}

label{margin-right: 10px}

input{margin-left: 28px}

input:focus{background: #E2FFC1}

input[id=shortUrl]{margin-left: 17px;}

form{background: #F9FFD7;padding: 30px}

#shortUrl{background: #F9FF93;padding-left: 30px;margin-top: 10px}

#shortUrl input{background: #fff}

#shortUrl label{font-weight: bold}

#ft{ color:#ccc;margin: 4px;font-size: 10px;margin-top: 20px}

#ft a { color:#ccc;}

   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>Shorten, share and track your links</h1></div>
   <div id="bd" role="main">
	<div class="yui-g">
	
         <form action="<?php echo$_SERVER['PHP_SELF'];?>" method="POST">

           <p><label for="longUrl">Long URL</label><input type="text" name="longUrl" id="longUrl"></p><p><label for="shorts">Select service</label><select id="shorts" name="shorts"><option value="bitly">bit.ly</option><option value="tinyurl">tinyurl.com</option><option value="trim">tr.im</option><option value="isgd">is.gd</option><option value="unu">u.nu</option><option value="digg">digg.com</option></select><input type="submit" id="shortner" name="submit" value="shortUrl"></p>

         </form>

         <div id="shortUrl">

                 <label for="shortUrl">Short URL: </label><input id="shortUrl" type="text" value="<?php if(isset($shortUrl)) echo$shortUrl; ?>" >

         </div>


	</div>

	</div>
   <div id="ft" role="contentinfo"><p>Created By <a href="http://thinkphp.ro">Adrian Statescu</a></p></div>
</div>

</body>
</html>
