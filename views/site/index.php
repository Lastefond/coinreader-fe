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
            first_name: $('#name').val(),
            coins: coins
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
<div class="site-index">

    <div class="jumbotron">
        <h1>Summa: <span id="sum">0.00 €</span> </h1>

        <form id="coinform" action="<?= Url::to(['/coin']) ?>" method="post">
            <div class="form-group">
                <label>Nimi</label>
                <input id="f_name" class="form-control" name="User[first_name]">
            </div>
            <button type="submit" class="btn btn-lg btn-success">Yes, send money!</button>
        </form>

    </div>
</div>
