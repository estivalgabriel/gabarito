  <footer>
    
  </footer>
    <?php
      $json = file_get_contents(ABSPATH.'/config_assets.json');
      $load_js = json_decode($json, true);
      foreach ($load_js['js'] as $value) {
          echo '<script src="'.PATH_JS.'/'.$value.'.js"></script>'."\n";
      }
    ?>
  </body>
  <script>
    $('.slider').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: true,
      infinite: true,
      cssEase: 'linear',
      infinite: true,
      speed: 800,
      autoplay: true,
      autoplaySpeed: 2000,
      dots: false
    });
  </script>
</html>
