<?php 
require('FlickrApi.php');


function photosets_getPhotos($photoset_id) {
    $options = get_option( 'mfpp_settings' );
    $flickr = new FlickrApi($options['mfpp_flickrKey']);

    $params = array(
        'method'    => 'flickr.photosets.getPhotos',
        'photoset_id' => $photoset_id,
        'user_id' => $options['mfpp_flickrUserID'],
        'extras' => 'url_l,url_m',
        'per_page' => 100
        
    );
    $photos = $flickr->api($params); // get photos
    return $photos;
}

//$tags = isset($_GET['tag']) ? $_GET['tag'] : 'landscape';
$res = photosets_getPhotos('72157657402272560');
$photos = $res['photoset']['photo'];
shuffle($photos);

?>
<html>
<head>
<script src="bower_components/modernizr/modernizr.js"></script>

<style>
#photos  {
  
}
#photos.loading {
  min-height: 500px;
  background: transparent url(images/loader.gif) no-repeat center center;
}
#photos .photo {
  padding: 8px;
  box-shadow: #222 3px 3px 3px;
  width: 240px;
  font-size: 87%;
  background: #F5F5F5;
  display: block;
  text-decoration: none;
  float: left;
  margin-bottom: 10px;
  display: none;
}
#photos .photo:hover {
  box-shadow: #000 3px 3px 3px;
  background: #FFFFFF;
}
#photos .photo img {
  max-width: 100%;
}
#photos .photo .title {
  padding: 8px 8px 0 0;
  font-style: italic;
  font-weight: bold;
  display: block;
  color: #333333;
}

#container {
  background: url("images/background.png") repeat scroll left top #eeeeee;
  box-shadow: 0 0 15px #000000 inset;
  min-height: 100px;
  padding: 20px;
 
}

</style>
<link href="bower_components/fancybox/source/jquery.fancybox.css" rel="stylesheet">
</head>    


<body>

<div id="container" >
  <div id="photos" class="loading">
  <?php
    foreach ($photos as $photo) {
      if(isset($photo['url_l'])) {
          print '<a class="photo fancybox" rel="flickr" title="'.$photo['title'].'" href="'.$photo['url_l'].'">'.
                  '<img src="'.$photo['url_m'].'" alt="'.$photo['title'].'"  />'.
                  '<span class="title">'.$photo['title'].'</span>'.
                '</a>';
      }
    }
  ?>

  </div>  
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="bower_components/fancybox/source/jquery.fancybox.pack.js"></script>
<script src="bower_components/masonry/dist/masonry.pkgd.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

    $('#photos .photo').hide();

    $('html').removeClass('no-js').addClass('js');

    var $container = $('#photos');
    
    $container.imagesLoaded(function(){
      $container.find('.photo').fadeIn('fast'); // Fade back in the thumbnails when the layout is complete
      $container.masonry({
        itemSelector : '.photo', 
        isAnimated : true, // Animate the layout when changing window size
        columnWidth: 260, // Width of the thumbnail including any padding and borders
        gutterWidth: 10 // The gap between thumbnails
      
      });
      $container.removeClass('loading'); // Remove the loading class from the container
    });
    
    $(".fancybox").fancybox({
      openEffect  : 'elastic',
      closeEffect : 'elastic',
      padding : 10,
      helpers : {
        title : {
          type : 'outside'
        }
      }
    });    
    
  });
</script>
</body>