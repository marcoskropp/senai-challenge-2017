
#include <Servo.h>
#include <ESP8266WiFi.h>

Servo servo_motor;

//Nome da sua rede Wifi
const char* ssid = "Industry1";

//Senha da rede
const char* password = "oisenailuzerna10";

//IP do ESP (para voce acessar pelo browser)
//10.3.119.253
IPAddress ip(10, 1, 93, 150);

//IP do roteador da sua rede wifi
IPAddress gateway(10, 1, 93, 254);

//Mascara de rede da sua rede wifi
IPAddress subnet(255, 255, 254, 0);

//Criando o servidor web na porta 80
WiFiServer server(80);

//Funcao que sera executada apenas ao ligar o ESP8266
void setup()
{
  //Preparando o GPIO2, que esta lidago ao LED
  pinMode(13, OUTPUT);
  digitalWrite(13, 1);

  //Conectando a� rede Wifi
  WiFi.config(ip, gateway, subnet);
  WiFi.begin(ssid, password);
  Serial.begin(9600);
  //Verificando se esta conectado,
  //caso contrario, espera um pouco e verifica de novo.
  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print("caiu");
    delay(500);
  }

  //Iniciando o servidor Web
  server.begin();

  servo_motor.attach(13);
}

//Funcao que sera executada indefinidamente enquanto o ESP8266 estiver ligado.
void loop()
{
  WiFiClient client = server.available();
  if (!client)
  {
    Serial.print("client\n");
    return;
  }

  //Verificando se o servidor recebeu alguma requisicao
  while (!client.available())
  {
    Serial.print("cl\n");
    delay(1);
  }
  //Obtendo a requisicao vinda do browser
  String req = client.readStringUntil('\r');
  client.flush();

  //Iniciando o buffer que ira conter a pagina HTML que sera enviada para o browser.
  String buf = "";

  buf += "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n<!DOCTYPE HTML>\r\n<html>\r\n";
  buf += "<head><meta http-equiv=''Acess-Control-Allow-Origin' content='*'><meta http-equiv=""refresh"" content=""3""></head><body>";
  buf += "<h3>ESP8266 Servidor Web</h3>";
  buf += "<p>LED <a href=\"?function=abrir\"><button>Abrir</button></a></p>";
  buf += "</body></html>\n";

  //Enviando para o browser a 'pagina' criada.
  client.print(buf);
  client.flush();


  //Analisando a requisicao recebida para decidir se liga ou desliga o LED
  if (req.indexOf("abrir") != -1) {
    servo_motor.write(0);
    delay(700);
    servo_motor.write(94);
    delay(1000);
    servo_motor.write(180);
    delay(700);
    servo_motor.write(94);
  } else
  {
    client.stop();
  }



}


