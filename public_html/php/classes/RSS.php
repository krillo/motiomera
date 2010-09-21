<?php

/**
 * The RSS class gets the feed from mabra.com.
 * It gets the feed, filters posts by category, selects x number of posts,
 * puts them in an array, resizes images and saves them all to disk, like a cache.
 *
 * Start the fetching by calling rss->getFeed()
 *
 * @author Kristian Erendi 10-09-29
 */
class RSS {
  const RSS_URL = "http://mabra.com/feed";
  const CACHE_PATH = "/files/rsscache";
  const IMG_WIDTH = 126;
  const IMG_HEIGHT = 95;
  const MAX_ARTICLES = 4;
  const TEXT_LENGTH = 170;


  public function __construct() {
    
  }

  /**
   * Kickstart the whole RSS fetching
   */
  function getFeed() {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::RSS_URL);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    $xml = simplexml_load_string($data);
    //print_r($xml);
    if (is_object($xml)) {
      $this->getPostsFromRSS($xml, self::MAX_ARTICLES);
    } else {
      Misc::logMotiomera("RSS->getFeed() something went wrong while fetching the rss. ", 'error');
    }
  }

  /**
   * This function selects x nbr of posts.
   * Saves them to an array and saves to disk
   *
   * @param <type> $xml
   * @param int $max
   */
  function getPostsFromRSS($xml, $max) {
    $ns = array("content" => "http://purl.org/rss/1.0/modules/content/");
    $motiofeed = array();
    $hitnbr = 0;
    foreach ($xml->channel->item as $i) {
      if (!$this->excludePost($i->category)) {
        $link = (string) $i->link;
        $excerpt = $this->getExcerpt((string) $i->description, $link, self::TEXT_LENGTH, '');

        $content = $i->children($ns['content']);
        $cont = (string) $content->encoded;
        //printf("%s\n\n", $cont);
        $imgurl = $this->getImage($cont, $link, '', $hitnbr);

        $html = '<a href="%s" target="_blank" id="%s" class="%s" >%s</a>';
        $title = sprintf($html, $link, 'rrs-title', '', (string) $i->title);

        $addPost = array('title' => $title, 'link' => $link, 'excerpt' => $excerpt, 'imageurl' => $imgurl);
        array_push($motiofeed, $addPost);
        $hitnbr++;
      }
      if (count($motiofeed) == $max) {
        break;
      }
    }
    print_r($motiofeed);
 
    //save array as file
    $smotiofeed = serialize($motiofeed);
    $fh = fopen(ROOT . self::CACHE_PATH . "/motiofeed.txt", 'wb') or die("can't open file");
    fwrite($fh, $smotiofeed);
    fclose($fh);
  }

  /**
   * This function truncates the description, removes obsolete string and puts an a-tag aroud the text
   * @param <type> $desc
   * @param <type> $link
   * @param <type> $length
   * @param <type> $style
   * @return string
   */
  function getExcerpt($desc, $link, $length=250, $style='nohover') {
    $desc = str_replace('[...]', '', $desc);
    $desc = substr($desc, 0, $length);
    $excerpt = '<a href="%s" target="_blank" class="rss-excerpt %s" >%s</a>';
    return sprintf($excerpt, $link, $style, $desc);
  }

  /**
   * Gets the first image from the the post and returns it, html formated with src from the local file system
   * if the image fetch doesn't succeed it returns the default image
   * @param array $motiofeed
   * @return string the link
   */
  function getImage($cont, $link, $style='', $i) {
    preg_match("/<img.*?\/>/", $cont, $matches);
    //print_r($cont);
    //print_r($matches);
    $imgtag = simplexml_load_string($matches[0]);
    //print_r($img);
    $img = '<a href="%s" target="_blank"><img alt="mabra.com" class="%s" src="%s" /></a>';
    if ($imgtag['src']) {
      if ($this->resizeAndStoreImage($imgtag['src'], $i)) {
        return sprintf($img, $link, $style, "/files/rsscache/$i.jpg");
      }
      return sprintf($img, $link, $style, "/files/rsscache/default.jpg");
    }
  }


  /**
   * Returns true if post should be excluded for its category or categorys
   * @param array $post
   * @return boolean
   */
  function excludePost($categorys) {
    $exclude_cats = array('Mat');  //array with categorys to exclude
    foreach ($categorys as $cat) {
      if (in_array((string) $cat, $exclude_cats)) {
        return true;
      }
      //echo $cat . " ";
    }
    return false;
  }

  
  /**
   * Gets the image from the submitted url, resizes it and stores it to disk with the name x.jpg.
   * the x is the submitted variable $i
   * Returns true or false wether it succeeds or not
   *
   * @param string $imglink
   * @param int $i
   * @return boolean
   */
  function resizeAndStoreImage($imglink, $i) {
    try {
      $filepath = ROOT . self::CACHE_PATH . "/temp.jpg";
      if ($f = curl_init($imglink)) {
        curl_setopt($f, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($f, CURLOPT_HEADER, 0);
        $bild = curl_exec($f);
      }
      $file = fopen($filepath, 'w');
      fwrite($file, $bild);
      fclose($file);

      //resize it before croping it
      list($width, $height) = getimagesize($filepath);
      $ratio = $this->getShrinkRatio($width, $height);  //find best shrink ratio
      $newwidth = $width * $ratio;
      $newheight = $height * $ratio;  //keep ratio
      $thumb = imagecreatetruecolor($newwidth, $newheight);
      $source = imagecreatefromjpeg($filepath);
      imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
      imagejpeg($thumb, ROOT . self::CACHE_PATH . "/temp_resized.jpg", 100);

      //crop, put overlay and save
      $image = imagecreatetruecolor(self::IMG_WIDTH, self::IMG_HEIGHT);
      $bg = imagecreatefromjpeg(ROOT . self::CACHE_PATH . "/temp_resized.jpg");
      $overlay = imagecreatefrompng(ROOT . self::CACHE_PATH . "/mask.png");
      imagecopy($image, $bg, 0, 0, 0, 0, self::IMG_WIDTH, self::IMG_HEIGHT);
      imagecopy($image, $overlay, 0, 0, 0, 0, self::IMG_WIDTH, self::IMG_HEIGHT);
      imagejpeg($image, ROOT . self::CACHE_PATH . "/$i.jpg", 100);
      return true;
    } catch (Exception $exc) {
       Misc::logMotiomera("RSS->resizeAndStoreImage() something went wrong with the image fetching. " . $exc->getTraceAsString(), 'error');
      return false;
    }
  }

  /**
   * Find the best ratio to shrink the image, so that it will not become smaller than
   * desired on both width and height
   *
   * @param <type> $width
   * @param <type> $height
   * @return <type>
   */
  function getShrinkRatio($width, $height){
    $w = self::IMG_WIDTH / $width; 
    $h = self::IMG_HEIGHT / $height;
    return $h > $w ? $h : $w;
  }

}


class RSSException extends Exception {
}

?>
