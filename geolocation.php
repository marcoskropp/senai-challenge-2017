<html>
    <head></head>
    <body>

        <div id="resposta"></div>
        <div id="lat"></div>
        <div id="lng"></div>
        <script src="js/jquery.min.js"></script>
        <script>
    setInterval(getLocation, 3000);

            function ajax() {
                var dados = {
                    'latitude': $('#lat').text(),
                    'longitude': $('#lng').text(),
                    'action': 'create'
                };
                console.log(dados);
                $.ajax({
                    url: './ajax/geolocationAjax.php',
                    dataType: 'html',
                    type: 'post',
                    data: dados
                });
            }

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                }
            }
            function showPosition(position) {
                $("#lat").html(position.coords.latitude);
                $("#lng").html(position.coords.longitude);
                ajax();
            }
        </script>
    </body>
</html>
