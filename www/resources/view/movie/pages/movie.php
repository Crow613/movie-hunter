
<?php
$movie = $arResult["movie"];
$check = !empty($movie);
if($check){
    $movieName = $movie["name"]?htmlspecialchars($movie["name"]) : "";
    $movieImg = $movie["image"]?htmlspecialchars($movie["image"]) : "";
    $movieLink = $movie["movie_link"]?htmlspecialchars($movie["movie_link"]) : "";
    $directorId = $movie['director_id'] ? htmlspecialchars($movie['director_id']) : "";
    $directorName = $movie["director_name"] ? htmlspecialchars($movie["director_name"]) : "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>MOVIE: <?=$movie["name"];?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="/css/style.css" media="all" />
    <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="/js/jquery-func.js"></script>
    <!--[if IE 6]>
              <link rel="stylesheet" href="/css/ie6.css" media="all"/>
    <![endif]-->
</head>
<body>
<!-- START PAGE SOURCE -->
<div id="shell">
    <div id="header">
        <h1 id="logo"><a href="#">MovieHunter</a></h1>
        <div class="social">
            <span>FOLLOW US ON:</span>
            <ul>
                <li><a class="twitter" href="#">twitter</a></li>
                <li><a class="facebook" href="#">facebook</a></li>
                <li><a class="vimeo" href="#">vimeo</a></li>
                <li><a class="rss" href="#">rss</a></li>
            </ul>
        </div>
        <div id="navigation">
            <ul>
                <li><a class="active" href="#">HOME</a></li>
                <li><a href="#">NEWS</a></li>
                <li><a href="#">IN THEATERS</a></li>
                <li><a href="#">COMING SOON</a></li>
                <li><a href="#">CONTACT</a></li>
                <li><a href="#">ADVERTISE</a></li>
            </ul>
        </div>
        <div id="sub-navigation">
            <ul>
                <li><a href="#">SHOW ALL</a></li>
                <li><a href="#">LATEST TRAILERS</a></li>
                <li><a href="#">TOP RATED</a></li>
                <li><a href="#">MOST COMMENTED</a></li>
            </ul>
            <div id="search">
                <form action="#" method="get" accept-charset="utf-8">
                    <label for="search-field">SEARCH</label>
                    <input type="text" name="search field" value="Enter search here" id="search-field" class="blink search-field"  />
                    <input type="submit" value="GO!" class="search-button" />
                </form>
            </div>
        </div>
    </div>
    <div id="main">
        <?if($check):?>
        <div style="width: 100%; height: 100% ;display: flex; justify-content: center; justify-items: center; justify-self: center">
            <div style="display: block">
                <img src="<?=$movieImg;?>" alt="img" width="100px" height="120px"/>
                <div style=" width: 100%">
                    <h3><?=$movieName;?></h3>
                    <span>Director:
                        <a href="/director/<?=$directorId;?>">
                             <?=$directorName;?>
                        </a>
                    </span></br>
                    <span>Producer:
                        <?foreach ($movie["producers"] as $producer):
                            ?>
                        <a href="/producer/<?=!empty($producer["id"]) ? htmlspecialchars($producer["id"]):"";?>">
                            <?=!empty($producer["name"]) ? htmlspecialchars($producer["name"]):"";?>
                        </a>,
                        <?endforeach;?>
                    </span>
                        </br>
                    <span>Actiors:
                        <?foreach ($movie["actors"] as $actor):?>
                            <a href="/actor/<?=!empty($actor["id"]) ? htmlspecialchars($actor["id"]) : "";?>">
                            <?=!empty($actor["name"]) ? htmlspecialchars($actor["name"]) : "";?>
                        </a>,
                        <?endforeach;?>
                    </span>
                </div>
                <p></p>
            </div>
            <iframe width="800px" height="600px"
                    src="<?=$movieLink;?>"
                    title="<?=$movieName;?>"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
            </iframe>

        </div>
         <? else:?>
         <h2>Movie note found </h2>
        <?endif;?>

    </div>
    <div id="footer">
        <p class="lf">Copyright &copy; 2010 <a href="#">SiteName</a> - All Rights Reserved</p>
        <p class="rf"><a href="http://all-free-download.com/free-website-templates/">Free CSS Templates</a> by <a href="http://chocotemplates.com/">ChocoTemplates.com</a></p>
        <div style="clear:both;"></div>
    </div>
</div>
<!-- END PAGE SOURCE -->
<div align=center>This template  downloaded form <a href='http://all-free-download.com/free-website-templates/'>free website templates</a></div>

</body>

</html>
