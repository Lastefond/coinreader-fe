(function (window) {
    var mapCoin = function (value) {
        return window.coinMap[value] || 0;
    };

    var resetCounters = function () {
        window.coins = [];
        window.sum = 0;
        addCoin(0);
    };

    var addCoin = function (coinValue) {
        window.coins.push(coinValue);
        window.sum += parseInt(coinValue);
        var eur = window.sum / 100;
        $('#sum').text(eur.toFixed(2) + ' €');
    };

    function start(websocketServerLocation) {
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

    start(window.socketUrl);

    $('body').on('submit', '#coinform', function (e) {
        e.preventDefault();

        var formData = {
            first_name: $('#name').val(),
            coins: window.coins
        };

        $.post($('#coinform').attr('action'), formData)
            .success(function () {
                resetCounters();
            }).error(function (data) {
            console.log(data);
        });
    });
})(window);