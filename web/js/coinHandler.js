;(function (global) {
    var CoinHandler = function(wsLocation, ajaxUrl, restartUrl, coinmap, coinAddedCallback) {
        this.wsLocation = wsLocation;
        this.callback = coinAddedCallback;
        this.coinMap = coinmap;
        this.ajaxUrl = ajaxUrl;
        this.restartUrl = restartUrl;
        this.coins = [];
        this._retries = 10;
        this._ws;

        this.init();
    };
    CoinHandler.prototype.init = function () {
        this.startWs();
    };
    CoinHandler.prototype.startWs = function () {
        this._ws = new WebSocket(this.wsLocation);
        this._ws.onmessage = CoinHandler.prototype.addCoin.bind(this);
        this._ws.onmessage = function (message) {
            this.addCoin(message.data);
        }.bind(this);
        this._ws.onclose = this.tryConnect.bind(this);
    };
    CoinHandler.prototype.tryConnect = function () {
        if (this._retries <= 0) {
            // reboot here
            $.get(this.restartUrl);
        }
        setTimeout(CoinHandler.prototype.startWs.bind(this), 10000);
        console.error('error connecting to socket, reconnecting in 10 seconds');
        this._retries--;
    };
    CoinHandler.prototype.addCoin = function (coin) {
        this.coins.push(this.mapCoin(coin));
        this.callback(this.coins);
    };
    CoinHandler.prototype.mapCoin = function (coin) {
        return this.coinMap[coin] || 0;
    };
    CoinHandler.prototype.sendCoins = function (name, callback) {
        global.jQuery.ajax({
            url: this.ajaxUrl,
            data: {
                name: name,
                coins: this.coins
            },
            type: 'post',
            success: callback
        });

        // reset
        this.coins = [];
    };


    global.CoinHandler = CoinHandler;
})(this);
