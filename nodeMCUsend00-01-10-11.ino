#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>

#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <SoftwareSerial.h> 

#include <ESPping.h> // Use Ping.h for ESP8266

// const char* ssid = "FJDResidences 2.4GHz";         // Replace with your WiFi network name
// const char* password = "@NovFJDRecon1926"; // Replace with your WiFi password

// const char* ssid = "QTES";
// const char* password = "!QTESwifi2023";

const char* ssid = "Huy Ano Yan?";
const char* password = "!Nakodikoalam1234";

IPAddress remote_ip(192, 168, 1,116);  
const char* httplink = "http://192.168.1.116/rescuelink/config/device_connect.php";

unsigned long previousMillis = 0;        // Variable to store the last time a message was sent
const long intervalLoop = 30000;             //
const long intervalFor = 1000;             //

int messageCounter = 0;                  // Counter to change the message
const char * myCodes[] = {"00","01","10","11"};

int count=0;

  // put your setup code here, to run once:
void setup() {
  Serial.begin(9600);
  
  // Connect to Wi-Fi
  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");
  
  // Wait until the ESP connects to the Wi-Fi
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("\nConnected to WiFi");
  Serial.print("My IP address: ");
  Serial.println(WiFi.localIP());
  
  // Ping the IP address
  Serial.println("Pinging IP address: 8.8.8.8");
  if (Ping.ping(remote_ip)) {
    Serial.println("Ping successful!");
  } else {
    Serial.println("Ping failed.");
  }
  
}

void loop() {
  // put your main code here, to run repeatedly:
  unsigned long currentMillis = millis();  // Get the current time
  if (currentMillis - previousMillis >= intervalLoop) {
    count=count+1;
    Serial.println(count);
      previousMillis = currentMillis;  // Save the last time you sent the message
    // Check Wi-Fi connection
    if(WiFi.status() == WL_CONNECTED) {  
      WiFiClient client;     // Declare a WiFiClient object
      HTTPClient http;  // Declare an object of class HTTPClient
      
      // Specify the target URL
      http.begin(client, httplink);  
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");  // Set content type for POST

      for (int i = 0; i < sizeof(myCodes) / sizeof(myCodes[0]); i++) { 
        String postData = myCodes[i];  // Assign current code from array to postData
          
          // Specify the target URL
          http.begin(client, httplink);  
          http.addHeader("Content-Type", "application/x-www-form-urlencoded");  // Set content type for POST
          
          // Sending HTTP POST request
          int httpResponseCode = http.POST(postData);  // Send POST request and store response code
          
          // Print the HTTP response code
          if(httpResponseCode > 0) {
            Serial.printf("HTTP Response code for %s: %d\n", postData.c_str(), httpResponseCode);
            String response = http.getString();   // Get the response payload
            Serial.println("Response: " + response);
          } else {
            Serial.printf("Error in POST request for %s: %s\n", postData.c_str(), http.errorToString(httpResponseCode).c_str());
          }
          
          http.end();  // Close connection

          delay(intervalFor);  // Optional delay between each request (1 second in this case)
      }
      
    } else {
      Serial.println("WiFi not connected");
    }
  }
}
