<?php
/**
 * pray-body.php
 * 
 * Display pray HTML body
 *
 * @author		Beit Hatfutsot
 * @package		prayer/includes
 * @version		1.0
 */

echo
  '<div data-collapse="medium" data-animation="default" data-duration="400" data-contain="1" class="w-nav navigation-bar">
    <div class="w-container">
      <h1 class="h1">' . ($lang == 'eng' ? 'MY PRAYER' : 'התפילה שלי') . '</h1>
    </div>
  </div>
  <div class="w-section hero-section centered">
    <div class="w-container main">
      <div class="cont">
        <h1 class="headline">' . $pray['person'] . '</h1>
        <p class="p">' . $pray['prayer'] . '</p>
      </div><img src="images/glow.png" class="glow">
      <a href="' . ($lang == 'eng' ? 'https://www.facebook.com/BeitHatfutsotEnglish' : 'https://www.facebook.com/BeitHatfutsot') . '" target="_blank" class="w-inline-block w-clearfix f">
        <div class="hero-subheading">' . ($lang == 'eng' ? 'What is your prayer? Share with us' : 'מה התפילה שלך? שתף אותנו') . '</div>
        <div class="hero-subheading toright">' . ($lang == 'eng' ? 'facebook.com/BeitHatfutsotEnglish' : 'facebook.com/BeitHatfutsot') . '</div><img src="images/f.png" class="icon">
      </a>
    </div>
  </div>
  <div class="w-section footer center">
    <div class="w-container">
      <a href="' . ($lang == 'eng' ? 'http://www.bh.org.il/' : 'http://www.bh.org.il/he/') . '" target="_blank" class="w-inline-block"><img src="images/logoBH.png">
      </a>
    </div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->';