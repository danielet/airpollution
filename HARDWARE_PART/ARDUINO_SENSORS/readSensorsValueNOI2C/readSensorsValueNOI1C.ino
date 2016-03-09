#include <Wire.h>
#include <Adafruit_ADS1015.h>

Adafruit_ADS1015 ads1015;



int NO2_WE = A0;
int NO2_AE = A5;

int CO_WE = A2;
int CO_AE = A3;

int O3_WE = A1;
int O3_AE = A4;


int LED = 13;
int LED2 = 12;

int ctrl ;
int delayLoop;





void setup() {
  Serial.begin(115200);
  while (!Serial);
  analogReadResolution(12);
  pinMode(LED , OUTPUT);  
  pinMode(LED2, OUTPUT);  
  digitalWrite(LED , LOW);
  digitalWrite(LED2, LOW);

  
//  ads1015.begin();
//  ads1015.setGain(GAIN_ONE);
  
}


int count = 0;
int delayADC = 10;
int check =1;
void loop() {
  digitalWrite(13, HIGH);
  delay(100);
  digitalWrite(13, LOW);
  delay(100);
  if (Serial.available() > 0 ) {              
        while(Serial.available() > 0 && count == 1){
          char ii= (char)Serial.read();         

          if(ii==49 && check == 1){

//            int16_t temp = ads1015.readADC_SingleEnded(0);
//            int16_t pm25 = ads1015.readADC_SingleEnded(1);
            int16_t CO_w = analogRead(CO_WE);
            delay(delayADC);
            int16_t CO_a = analogRead(CO_AE);
            delay(delayADC);
            int16_t O3_w = analogRead(O3_WE);
            delay(delayADC);
            int16_t O3_a = analogRead(O3_AE);
            delay(delayADC);
            int16_t NO2_w = analogRead(NO2_WE);
            delay(delayADC);
            int16_t NO2_a = analogRead(NO2_AE);
//            String ss = String(CO_w)+ ','+String(CO_a)+ ','+String(O3_w)+ ','+String(O3_a)+ ',' +String(NO2_w)+ ','+String(NO2_a)+ ',' + String(pm25)+ ','+String(temp);
            String ss = String(CO_w)+ ','+String(CO_a)+ ','+String(O3_w)+ ','+String(O3_a)+ ',' +String(NO2_w)+ ','+String(NO2_a);
            Serial.println(ss);
            
            digitalWrite(12, HIGH);
            delay(100);
            digitalWrite(12, LOW);
            Serial.flush();
            check =0;
          }
        }
        
  }
  check =1;
  if(count == 0 ){
          count = count + 1;
  }//if
}
     
