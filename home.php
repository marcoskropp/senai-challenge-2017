<?php
require_once './app/Config.inc.php';

session_start();
if (!isset($_SESSION['email']) || isset($_GET['sair'])) {
    session_destroy();
    header("location: index.php");
}

$read = new Read();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <style>
            #map{
                position: relative;
                height: 0;
                overflow: hidden;
                height: 400px;
                width: 100%;
            }
        </style>
    </head>
    <body class="bg-warning">
        <div id="conteudo">
            <ul class="nav nav-tabs info">
                <li role="presentation" class="active"><a href="home.php">Inicio</a></li>
                <li role="presentation"><a href="home.php?sair">Sair</a></li>
            </ul>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div class="col-md-12">
                            <br>
                            <h1>HOME</h1>
                            <img src="./drone.png">
                            <div class="btn-group btn-group-lg center-align" role="group">
                                <button type="button" class="btn btn-primary col-lg-12" id="botao" value="abrir">Abrir</button>
                            </div>
                        </div>
                        <br>
                        <?php
                        $read->fullRead("SELECT * FROM maps ORDER BY id DESC LIMIT 1");
                        $resultado = $read->getResult();
                        foreach ($resultado as $value) {
                            ?>
                            <input id = "lat" type="hidden" value="<?= $value['lat'] ?>">
                            <input id = "lng" type="hidden" value="<?= $value['lng'] ?>">
                            <?php
                        }
                        ?>
                    </div>
                    <div class="col-md-9">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            $(window).on("load", function () {
                var lat = $("#lat").val();
                var lng = $("#lng").val();
                initMap(lat, lng);
            });
            $(document).ready(function () {
                $("#botao").click(function () {
                    var botao = $("#botao");
                    var valor = $("#botao").val();
                    var comando = valor == "abrir" ? "abrir" : "0";
                    $.ajax({
                        url: 'http://10.1.93.150/?function=' + comando,
                        dataType: 'html',
                        type: 'get',
                        beforeSend: function () {
                            if (comando == "1") {
                                valor = "fechar";
                                $('#botao').removeClass('btn-primary');
                                $('#botao').addClass('btn-info');
                                botao.html("Abrindo...");
                            } else {
                                $('#botao').removeClass('btn-info');
                                $('#botao').addClass('btn-primary');
                                botao.html("Fechando...");
                                valor = "abrir";
                                setTimeout(function () {
                                    botao.html("Abrir");
                                }, 700);
                            }
                        },
                        success: function () {
                            botao.html("Fechar");
                        }
                    });
                });
                setInterval(function () {
                    var dados = {
                        "action": "read"
                    };
                    $.ajax({
                        url: 'ajax/geolocationAjax.php',
                        dataType: 'json',
                        type: 'post',
                        data: dados,
                        success: function (retorno) {
                            initMap(retorno.lat, retorno.lng);
                        }
                    });
                }, 5000);
            });
            function initMap(lat = 0, lng = 0) {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: new google.maps.LatLng(lat, lng),
                    zoom: 16,
                    disableDefaultUI: true,
                    ControlPosition: false,
                    styles: [
                        {
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#242f3e"
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#746855"
                                }
                            ]
                        },
                        {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                                {
                                    "color": "#242f3e"
                                }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "administrative.locality",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#d59563"
                                }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#d59563"
                                }
                            ]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#263c3f"
                                }
                            ]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#6b9a76"
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#38414e"
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#212a37"
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "labels.icon",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "road",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#9ca5b3"
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#746855"
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                {
                                    "color": "#1f2835"
                                }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#f3d19c"
                                }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "stylers": [
                                {
                                    "visibility": "off"
                                }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#2f3948"
                                }
                            ]
                        },
                        {
                            "featureType": "transit.station",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#d59563"
                                }
                            ]
                        },
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                                {
                                    "color": "#17263c"
                                }
                            ]
                        },
                        {
                            "featureType": "water",
                            "elementType": "labels.text.fill",
                            "stylers": [
                                {
                                    "color": "#515c6d"
                                }
                            ]
                        },
                        {
                            "featureType": "water",
                            "elementType": "labels.text.stroke",
                            "stylers": [
                                {
                                    "color": "#17263c"
                                }
                            ]
                        }
                    ]
                });
                var point = new google.maps.LatLng(
                        parseFloat(lat),
                        parseFloat(lng));
                var marker = new google.maps.Marker({
                    map: map,
                    position: point
                });
            }
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCou6ItbFZmgwxGEfi1ZNt6Dw51UA3iyMA">
        </script>
    </body>
</html>
