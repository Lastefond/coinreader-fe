<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
$coinreaderUrl = Yii::$app->params['coinReader']['url'];
$ajaxUrl = Url::to(['/donator']);

$this->registerJs(<<<JS
        
    $('.bxslider').bxSlider({
      auto: true,
      controls: false,
      easing: 'ease-in-out',
      touchEnabled: false
    });

    // $('.bxslider').click(function(){
    //   $('.bx-wrapper').addClass('animated fadeOut');
    //   $('.wrapper').removeClass('hidden').addClass('bounceIn');
    //   $('.footer-img').removeClass('hidden').addClass('bounceIn');
    // });


    $('.inserted-sum .next').click(function(){
      $('.sum').addClass('fadeOut');
      $('.inserted-sum').addClass('hidden');
      $(this).addClass('fadeOut');
      // Next step keyboard
      $('.keyboard').removeClass('hidden');
      $('.ui-keyboard').addClass('animated bounceIn');
    });


    function thankYouStep(name){
        $('.inserted-sum').addClass('hidden');
      $('.keyboard').addClass('fadeOut hidden');
      $('.ui-keyboard').addClass('fadeOut');
    // Next step keyboard
      $('.thank-you').removeClass('hidden');
      $('.thank-you p').addClass('animated bounceIn');
      
      if (name) {
        $('.thank-you .donator-name').html(' ' + name);
      }
    }

    function timeOut(){
      var time = new Date().getTime();
      var timeOutDuration = 1000;
      $(document.body).bind("mousemove keypress", function(e) {
       time = new Date().getTime();
     });

     function refresh() {
       if(new Date().getTime() - time >= 6000)
         window.location.reload(true);
       else
         setTimeout(refresh, timeOutDuration);
     }

     setTimeout(refresh, timeOutDuration);
    }

    function modalError(){
      $('.modal-error').removeClass('hidden');
      // timeOut();
    }

    // function thankYou(){
    //   $('.modal-error').removeClass('hidden');
    //   // timeOut();
    // }

    $.keyboard.keyaction.donate_anonymous = function(base){
        coinHandler.sendCoins('Anonüümne', function (data) {
            clearCoinTimeout();
            thankYouStep(null);
            setTimeout(location.reload.bind(location), 10000);
        });
    };

    $('#keyboard').keyboard({
      validate: function(keyboard, value, isClosing){
        var regex_empty_two_chars = /\w{2,}/.test(value);
        if (!regex_empty_two_chars && isClosing) {
          alert('Palun sisesta nimi või anneta anonüümselt!')
        }
        return regex_empty_two_chars;
      },
     alwaysOpen:true,
     reposition : false,
     layout: 'custom',
     appendLocally: '.keyboard',
     stickyShift: false,
     display: {
      'donate_anonymous' : 'Anonüümne annetus',
      'accept'   : 'Lõpeta nime sisestus'
    },
    accepted : function(event, keyboard, el) {
      var name  = el.value;
      console.log('sending now');
      coinHandler.sendCoins(name, function (data) {
          clearCoinTimeout();
        thankYouStep(name);
        setTimeout(location.reload.bind(location), 10000);
      });
      
    },
    change: function () {
          restartCoinTimeout();
    },
    customLayout : {
      'normal': [
      'Q W E R T Y U I O P Ü Õ {b}',
      'A S D F G H J K L Ö Ä',
      'Z X C V B N M - ',
      '{space}',
      '{donate_anonymous} {accept}'
      ]
    }
  });

    var sendAnonTimeout;
    var coinEntered = false;
    var coinHandler = new CoinHandler('{$coinreaderUrl}','{$ajaxUrl}',{
        1: 200,
        2: 100,
        3: 50,
        4: 20,
        5: 10,
        6: 5
    }, function(coins) {
        if (!coinEntered) {
            $('.bx-wrapper').addClass('animated fadeOut');
            $('.wrapper').removeClass('hidden').addClass('bounceIn');
            $('.footer-img').removeClass('hidden').addClass('bounceIn');
            coinEntered = true;
        }
        restartCoinTimeout();
        
        // coins elements are in cents, calculate the sum in €
        var sum = coins.reduce(add, 0) / 100;
        function add(a, b) {
            return a + b;
        }
        $('#sum').text(sum.toFixed(2) + ' €');
        $('#ty_sum').text(sum.toFixed(2) + ' €');
    });
    
    var clearCoinTimeout = function () {
        if (sendAnonTimeout) {
            clearTimeout(sendAnonTimeout);
        }
    }
    
    var restartCoinTimeout = function () {
        clearCoinTimeout();
       
        sendAnonTimeout = setTimeout(function() {
            coinHandler.sendCoins('Anonüümne', function (data) {
                thankYouStep(null);
                setTimeout(location.reload.bind(location), 10000);
            });
        }, 10000);
    }
    
    $('body').on('touchstart touchend', function() {
        restartCoinTimeout();
    });

JS
,\yii\web\View::POS_READY
);


?>

<div class="footer-img hidden animated">
  <img src="img/logo-lastefond.png" alt="" class="img-left-corner" height="130px">
  <img src="img/content-flower.png" alt="" class="img-right-corner" height="130px">
</div>

<div class="modals">
  <div class="modal modal-error hidden">
    Palun sisesta nimi või lõpeta annetus anonüümselt
  </div>
</div>

<ul class="bxslider">
  <li>
    <div class="box-total">
      <div class="box-total-content">
          See on uus tekst :) Selle kastiga on kogutud <br />
          <h1><?= Yii::$app->formatter->asDecimal($donations_sum, 2) ?> €</h1>
      </div>
    </div>
  </li>
  <li><img src="/img/slideshow/1.jpg" /></li>
  <li><img src="/img/slideshow/2.jpg" /></li>
</ul>


<div class="wrapper animated hidden">
    <div class="content inserted-sum animated fadeIn">
      <div class="title animated fadeIn">
        Sisestatud summa
      </div>
      <div id="sum" class="sum animated bounceIn">0.00 €</div>
      <div class="button animated fadeIn next">
        Lõpeta sisestamine  
      </div>
    </div>
    <div class="content keyboard hidden">
      <input type="text" id="keyboard" placeholder="Sisesta nimi" style="display: none;">
    </div>

    <div class="content thank-you hidden">
      <p>Tänan<span class="donator-name"></span>!</p>
        <p>Summa: <span id="ty_sum"></span></p>
        <div class="button animated fadeIn next" onclick="window.location.reload(true)">
            Alusta uut annetamist
        </div>
    </div>


  </div>
