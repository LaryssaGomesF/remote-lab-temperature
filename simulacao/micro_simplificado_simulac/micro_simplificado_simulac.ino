#include <SPI.h>
#include <Ethernet.h>
#include <ArduinoJson.h>

//configurações ethernet
byte mac[] = { 0x54, 0x34, 0x41, 0x30, 0x30, 0x31 }; 
IPAddress ip(192, 168, 1, 145);          
EthernetClient client;

//variaveis para ethernet
const char* serverIP = "192.168.1.25";  // IP do servidor local
const int serverPort = 80;             // Porta do servidor

//pinos 
#define pCONTROLE 3  // Define o pino digital 3 do Arduino como o pino de controle do sinal PWM do cooler
#define pinLamp 9    // Define o pino digital 9 do Arduino como o pino de controle PWM da lâmpada


float PWMCooler = 20,PWMLamp = 0,PID=0,temperatura = 0,kp=0,ki=0,E=0,mode=0; 
unsigned long previousMillis = 0,currentMillis=0;
const long interval = 850;
const int LM35_3 = A7; 


void setup() {
  Serial.begin(9600);
  Ethernet.begin(mac, ip);
  delay(10);
  pinMode(pCONTROLE, OUTPUT);
  pinMode(pinLamp, OUTPUT);
  pwm25kHzBegin();
}

void loop() {

    currentMillis = millis();
    if (currentMillis - previousMillis >= interval) {
        previousMillis = currentMillis;
        pwmDuty(PWMCooler);
        temperatura = Leitura3(LM35_3);
        if(mode==0){analogWrite(pinLamp, PWMLamp);}
        else{PID=controladorPI(PWMLamp,temperatura,kp,ki,(interval/1000));analogWrite(pinLamp,PID);}
        net();
    }
}

float Leitura3(int analogInPin3)
  {
  float sensorValue3 = 0;     
  float outputValue3 = 0;   
  float ReadValue3 = 0;
  float Valorlido3 = 0;
  int ReadTimes3 = 0;
  for (int k =0; k < 50; k++)
    {
    ReadValue3 = (float(analogRead(analogInPin3))*5/(1023))/0.01;
      Valorlido3 = round(ReadValue3*10)/10.0;
      sensorValue3 = sensorValue3 + Valorlido3;
      ReadTimes3++;         
    }
  outputValue3 =round((sensorValue3 / ReadTimes3)*10)/10.0;
  return outputValue3;
  }

// Função que aumenta a frequência do sinal PWM para 25kHz
void pwm25kHzBegin() {
  TCCR2A = 0;                                             // TC2 Control Register A
  TCCR2B = 0;                                             // TC2 Control Register B
  TIMSK2 = 0;                                             // TC2 Interrupt Mask Register
  TIFR2 = 0;                                              // TC2 Interrupt Flag Register
  TCCR2A |= (1 << COM2B1) | (1 << WGM21) | (1 << WGM20);  // OC2B cleared/set on match when up/down counting, fast PWM
  TCCR2B |= (1 << WGM22) | (1 << CS21);                   // prescaler 8
  OCR2A = 79;                                             // TOP overflow value (Hz)
  OCR2B = 26;
}
void pwmDuty(byte ocrb) {
  OCR2B = ocrb;  // PWM Width (duty)
}

float controladorPI(float setpoint, float feedback, float Kp, float Ki, float T) {
  static float E_prev = 0.0;
  static float U_prev = 0.0;
   E = setpoint - feedback;

  float U = U_prev + Kp * (E - E_prev) + Ki * T * E;
  if(U>255){U=255;}if(U<0){U=0;}
  //U = constrain(U, 0, 255);

  E_prev = E;
  U_prev = U;

  return U;
}

void net() { 
 
  if (client.connect(serverIP, serverPort)) {
    String postData = String("t=") + currentMillis + "&tp=" + temperatura + "&s=" + PWMLamp + "&e=" + E + "&a=" + PWMLamp;
    //String postData = String("t=") + currentMillis + "&tp=" + 100 + "&s=" + 100 + "&e=" + 100 +"&a=" + 100;
    String httpRequest = "POST /lab-remoto-temperatura/plant HTTP/1.1\r\n" 
                         "Host: " + String(serverIP) + "\r\n"
                         "Content-Type: application/x-www-form-urlencoded\r\n"
                         "Content-Length: " + String(postData.length()) + "\r\n"
                         "Connection: close\r\n\r\n" + postData;
    client.print(httpRequest);
    Serial.println(httpRequest);
    delay(1000);

    if (client.connected()) {
      String response = "";
      while (client.available()) {
        char c = client.read();
        response += c;
      }
    Serial.println("Resposta recebida:");
    Serial.println(response);

      int jsonStart = response.indexOf('{');
      if (jsonStart != -1) {
        String jsonPart = response.substring(jsonStart);

        // Processa o JSON
        StaticJsonDocument<512> doc;  // Buffer maior para JSONs maiores
        DeserializationError error = deserializeJson(doc, jsonPart);
        // Extraindo os valores específicos do JSON
        kp = doc["kp"];         // Lê o valor de "kp"
        ki = doc["ki"];         // Lê o valor de "ki"
        //kd = doc["kd"];
        mode = doc["m"];         // Lê o valor de "kd"
        PWMLamp = doc["s"];
        Serial.print("kp=");
        Serial.print(kp);
        Serial.print(" ki=");
        Serial.print(ki);
        Serial.print(" mode=");
        Serial.print(mode);
        Serial.print(" PWMLamp=");
        Serial.println(PWMLamp);


      } else {       
        Serial.print("Erro ao parsear JSON: ");      
      }
    } 
    client.stop();  // Fecha a conexão
    Serial.println("Conexão fechada.");
  }
}
