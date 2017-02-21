<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
$coinreaderUrl = Yii::$app->params['coinReader']['url'];
$this->registerJs(<<<JS
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
JS
)
?>

<script src="js/jquery-latest.min.js"></script>
<script src="js/jquery-ui.min.js"></script>


<div class="wrapper animated bounceIn">
    <div class="content inserted-sum animated fadeIn">
      <div class="title animated fadeIn">
        Sisestatud summa
      </div>
      <div class="sum animated bounceIn">2.50€</div>
      <div class="button animated fadeIn next">
        Lõpeta sisestamine  
      </div>
    </div>
    <div class="content keyboard hidden">
      <input type="text" id="keyboard" placeholder="Sisesta nimi" / style="display: none;">
    </div>
  </div>


  <script>
  $('.inserted-sum .next').click(function(){
    $('.sum').addClass('fadeOut');
    $('.title').addClass('fadeOut');
    $(this).addClass('fadeOut');
    // Next step
    $('.keyboard').removeClass('hidden');
    // $('.ui-keyboard').addClass('animated bounceIn');
  });

    $('#keyboard').keyboard({
     alwaysOpen:true,     
     reposition : false,
     layout: 'custom',
     appendLocally: '.keyboard',
     stickyShift: false,
     display: {
      'shift'  : '&#8679;',
      'cancel' : 'Anonüümne annetus',
      'accept'   : 'Lõpeta nime sisestus'
    },
    accepted : function(event, keyboard, el) {
      alert('Tänan ' + el.value + '!');
    },
    customLayout : {
      'normal': [
      'Q W E R T Y U I O P Ü Õ {b}',
      'A S D F G H J K L Ö Ä',
      'Z X C V B N M - ',
      '{space}',
      '',
      ' {cancel} {accept}'
      ]
    }
  });

</script>

<div class="site-index hidden">

    <div class="jumbotron hidden">
        <h1>Summa: <span id="sum">0.00 €</span> </h1>

        <form id="coinform" action="<?= Url::to(['/coin']) ?>" method="post">
            <div class="form-group">
                <label>Eesnimi</label>
                <input id="f_name" class="form-control" name="User[first_name]">
            </div>
            <div class="form-group">
                <label>Perekonnanimi</label>
                <input id="l_name" class="form-control" name="User[last_name]">
            </div>

            <button type="submit" class="btn btn-lg btn-success">Yes, send money!</button>
        </form>

    </div>
</div>
