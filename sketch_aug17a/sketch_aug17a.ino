#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>  // Inclua a biblioteca ArduinoJson

byte mac[] = { 0x54, 0x34, 0x41, 0x30, 0x30, 0x31 };  // Endereço MAC
IPAddress ip(192, 168, 0, 145);                       // Endereço IP do Arduino
EthernetClient client;

const char* serverIP = "192.168.1.156";  // IP do servidor local
const int serverPort = 6008;             // Porta do servidor
int time = 0;                            // Contagem de tempo

void setup() {
  Serial.begin(9600);
  Ethernet.begin(mac, ip);
  delay(10);

  // Verifica se o Arduino obteve o IP corretamente
  Serial.print("IP obtido: ");
  Serial.println(Ethernet.localIP());
}

void loop() {
  net();
  time++;
  delay(1000);
}

void net() {
  Serial.println("Tentando conectar ao servidor...");
  
  if (client.connect(serverIP, serverPort)) {
    Serial.println("Conexão estabelecida!");

    // Formatar a requisição POST
    String postData = String("time=") + time + "&temperature=" + 15 + "&erro=" + 20;
    String httpRequest = "POST /plant HTTP/1.1\r\n" 
                         "Host: " + String(serverIP) + "\r\n"
                         "Content-Type: application/x-www-form-urlencoded\r\n"
                         "Content-Length: " + String(postData.length()) + "\r\n"
                         "Connection: close\r\n\r\n" + postData;
    
    // Enviar a requisição
    client.print(httpRequest);

    // Esperar um pequeno tempo para garantir que os dados sejam recebidos
    delay(1000);

    // Verifica se há resposta disponível
    if (client.connected()) {
      Serial.println("Aguardando resposta do servidor...");
      String response = "";
      while (client.available()) {
        char c = client.read();
        response += c;
      }

      // Exibe a resposta recebida
      if (response.length() > 0) {
        Serial.println("Resposta do servidor:");
        Serial.println(response);
      } else {
        Serial.println("Nenhuma resposta recebida.");
      }

      // Acha o início do corpo do JSON na resposta
      int jsonStart = response.indexOf('{');
      if (jsonStart != -1) {
        String jsonPart = response.substring(jsonStart);

        // Processa o JSON
        StaticJsonDocument<512> doc;  // Buffer maior para JSONs maiores
        DeserializationError error = deserializeJson(doc, jsonPart);

        // Verifica se houve erro ao analisar o JSON
        if (error) {
          Serial.print("Erro ao analisar JSON: ");
          Serial.println(error.f_str());
          return;
        }

        // Extraindo os valores específicos do JSON
        float kp = doc["kp"];         // Lê o valor de "kp"
        float ki = doc["ki"];         // Lê o valor de "ki"
        float kd = doc["kd"];
        int mode = doc["mode"];         // Lê o valor de "kd"

        // Exibindo os valores recebidos
        Serial.println("Dados JSON recebidos:");
        Serial.print("Kp: ");
        Serial.println(kp);
        Serial.print("Ki: ");
        Serial.println(ki);
        Serial.print("Kd: ");
        Serial.println(kd);
        Serial.print("MODE: ");
        Serial.println(mode);
      } else {
        Serial.println("JSON não encontrado na resposta!");
      }
    } else {
      Serial.println("Conexão não está disponível!");
    }

    client.stop();  // Fecha a conexão
    Serial.println("Conexão fechada.");
  } else {
    Serial.println("Falha na conexão com o servidor!");
  }
}