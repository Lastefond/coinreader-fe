<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
$coinreaderUrl = Yii::$app->params['coinReader']['url'];
$this->registerJs(<<<JS

  $(document).ready(function(){
    $('.bxslider').bxSlider({
      auto: true,
      controls: false,
      easing: 'ease-in-out',
      touchEnabled: false
    });
    $('#sum').bind("DOMSubtreeModified",function(){
      $('.bx-wrapper').addClass('animated fadeOut');
    $('.wrapper').removeClass('hidden').addClass('bounceIn');
    });
  });


    var socketUrl = '{$coinreaderUrl}';
    var coins = [];
    var sum = 0;
    var coinMap = {
        1: 200,
        2: 100,
        3: 50,
        4: 20,
        5: 10,
        6: 5
    };

    var mapCoin = function (value) {
        return coinMap[value] || 0;
    };
    
    var resetCounters = function () {
        coins = [];
        sum = 0;
        addCoin(0);
    };
    
    var addCoin = function(coinValue) {
        coins.push(coinValue);
        sum += parseInt(coinValue);
        var eur = sum / 100;
        $('#sum').text(eur.toFixed(2) + ' €');
    };

    function start(websocketServerLocation){
        var connection = new WebSocket(websocketServerLocation);

        // When the connection is open, send some data to the server
        connection.onopen = function () {

        };

        // Log errors on connection open
        connection.onerror = function (error) {
            
        };

        // Log messages from the server
        connection.onmessage = function (e) {
            addCoin(mapCoin(e.data));
        };
    }

    start(socketUrl);
    
    $('body').on('submit', '#coinform', function (e) {
        e.preventDefault();
        var coinData = [];
        coins.forEach(function (coin, idx) {
            coinData[idx] = {coin_value: coin};
        });

        var formData = {
            User: {
                first_name: $('#f_name').val(),
                last_name: $('#l_name').val()
            },
            Coin: coinData
        };
        
        $.post( $('#coinform').attr('action'), formData, function( data, hue, xhr ) {
            if (xhr.status == 200) {
                resetCounters();
            }
            else if (xhr.status == 202) {
                // TODO no coins entered
                alert(data.message);
            }
        }).error(function(data) {
            // TODO handle better
            alert(data);
        });
    });

    $('.inserted-sum .next').click(function(){
      $('.sum').addClass('fadeOut');
      $('.sum').addClass('fadeOut');
      $('.inserted-sum').addClass('hidden');
      $(this).addClass('fadeOut');
    // Next step keyboard
      $('.keyboard').removeClass('hidden');
      $('.ui-keyboard').addClass('animated bounceIn');
    });


    function thankYouStep(name){
      $('.keyboard').addClass('fadeOut hidden');
      $('.ui-keyboard').addClass('fadeOut');
    // Next step keyboard
      $('.thank-you').removeClass('hidden');
      $('.thank-you .donator-name').html(name);
      $('.thank-you p').addClass('animated bounceIn');
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
      alert('Annetatud anonüümselt! Tänan!')
    };

    $('#keyboard').keyboard({
      validate: function(keyboard, value, isClosing){
        var regex_empty_two_chars = /\w{2,}/.test(value);
        if (!regex_empty_two_chars && isClosing) {
          modalError();
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
      var name  = el.value
      thankYouStep(name);
      setTimeout(location.reload.bind(location), 10000);
    },
    customLayout : {
      'normal': [
      'Q W E R T Y U I O P Ü Õ {b}',
      'A S D F G H J K L Ö Ä',
      'Z X C V B N M - ',
      '{space}',
      ' {donate_anonymous} {accept}'
      ]
    }
  });
JS
)
?>

<img src="img/logo-lastefond.png" alt="" class="img-left-corner">
<img src="img/content-flower.png" alt="" class="img-right-corner">

<div class="modals">
  <div class="modal modal-error hidden">
    Palun sisesta nimi või lõpeta annetus anonüümselt
  </div>
</div>

<ul class="bxslider">
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
      <input type="text" id="keyboard" placeholder="Sisesta nimi" / style="display: none;">
    </div>

    <div class="content thank-you hidden">
      <p>Tänan <span class="donator-name"></span>!</p>
    </div>


  </div>

<div class="site-index hidden">

    <div class="jumbotron">
        <h1>Summa: <span>0.00 €</span> </h1>

        <form id="coinform" action="<?= Url::to(['/coin']) ?>" method="post">
            <div class="form-group">
                <label>Eesnimi</label>
                <input id="name" class="form-control" name="name">
            </div>

            <button type="submit" class="btn btn-lg btn-success">Yes, send money!</button>
        </form>

    </div>
</div>
