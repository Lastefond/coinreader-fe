<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'My Yii Application';
$coinreaderUrl = Yii::$app->params['coinReader']['url'];
$this->registerJs(<<<JS
    socketUrl = '{$coinreaderUrl}';
    coins = [];
    sum = 0;
    coinMap = {
        1: 200,
        2: 100,
        3: 50,
        4: 20,
        5: 10,
        6: 5
    };
JS
, \yii\web\View::POS_HEAD)
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
