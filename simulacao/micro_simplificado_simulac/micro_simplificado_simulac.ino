#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>  // Inclua a biblioteca ArduinoJson


byte mac[] = { 0x54, 0x34, 0x41, 0x30, 0x30, 0x31 };  // Endereço MAC
IPAddress ip(192, 168, 0, 145);                       // Endereço IP do Arduino
EthernetClient client;

const char* serverIP = "192.168.0.114";  // IP do servidor local
const int serverPort = 6008;             // Porta do servidor
int time = 0,time_ant=0;  
float temperatura=0; 
float setpoint=0,kp=0,ki=0,kd=0,mode=0,erro=0,P=0,I=0,D=0,PID=0,erro_ant=0;                         // Contagem de tempo

void setup() {
  Serial.begin(9600);
  Ethernet.begin(mac, ip);
  delay(10);
}

void loop() {
  time=millis()/1000;
  if(time-time_ant>=1){
  lerTemperatura();
  net();
  if(mode==1){Controlador_PID();}
  Serial.print("PID: ");
  Serial.println(PID);
  time_ant=time;
  }
}

void lerTemperatura() {
  temperatura = (analogRead(A5) * (5.0 / 1023.0)) * 100;  // Converte para graus Celsius
}

void Controlador_PID() {
  erro = setpoint - temperatura;
  P = kp * erro;
  I = I + (ki * erro);
  if (I > 255) {
    I = 255;
  }
  if (I < 0) {
    I = 0;
  }

  // Cálculo do termo derivativo
  D = kd * (erro - erro_ant);

  //Cálculo do PID 
  PID = P + I + D; erro_ant = erro; // Atualiza o erro anterior
  if (PID > 255) { PID = 255; } if (PID < 0) { PID = 0; }
}

void net() { 
  if (client.connect(serverIP, serverPort)) {
    String postData = String("t=") + time + "&tp=" + temperatura + "&s=" + setpoint + "&e=" + erro;
    String httpRequest = "POST /plant HTTP/1.1\r\n" 
                         "Host: " + String(serverIP) + "\r\n"
                         "Content-Type: application/x-www-form-urlencoded\r\n"
                         "Content-Length: " + String(postData.length()) + "\r\n"
                         "Connection: close\r\n\r\n" + postData;
    client.print(httpRequest);
    delay(1000);

    if (client.connected()) {
      String response = "";
      while (client.available()) {
        char c = client.read();
        response += c;
      }

      int jsonStart = response.indexOf('{');
      if (jsonStart != -1) {
        String jsonPart = response.substring(jsonStart);

        // Processa o JSON
        StaticJsonDocument<512> doc;  // Buffer maior para JSONs maiores
        DeserializationError error = deserializeJson(doc, jsonPart);
        // Extraindo os valores específicos do JSON
        kp = doc["kp"];         // Lê o valor de "kp"
        ki = doc["ki"];         // Lê o valor de "ki"
        kd = doc["kd"];
        mode = doc["m"];         // Lê o valor de "kd"
        setpoint = doc["s"];

  
      
        Serial.println("Dados JSON recebidos:");
        Serial.print("Kp: ");
        Serial.println(kp);
        Serial.print("Ki: ");
        Serial.println(ki);
        Serial.print("Kd: ");
        Serial.println(kd);
        Serial.print("MODE: ");
        Serial.println(mode);
        Serial.print("SET: ");
        Serial.println(setpoint);
      } 
    } 
    client.stop();  // Fecha a conexão
    Serial.println("Conexão fechada.");
  }
}