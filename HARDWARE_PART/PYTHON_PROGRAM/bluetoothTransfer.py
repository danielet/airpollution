#!/usr/bin/python

#PUT LICENSE LGPL


import serial
import time
import random
import csv
import string
import hashlib


#THREAD PART
import threading 

#BLUETOOTH LIBRARY
from bluetooth import *
#MATTEO'LIBRARY
import readFiles

import random
#
#import signal
#GLOBAL VARIABLE
ctrlPrint 	= 1
ctrlLoop 	= 1
client_sock	= -1





def temperature(stepResolution, stepIntTemp, V20C):
	intTemp = (((((4096)/float(4096/2)/1000) * int(stepIntTemp))-V20C)*1000);
	temperatureValues= intTemp;
	return temperatureValues

def PM25Value(analogvalue):

	analogtotal = (analogvalue*(4096))/(float(4096/2)*1000);
    	hppcf = (240.0*pow(analogtotal,6) - 2491.3*pow(analogtotal,5) + 9448.7*pow(analogtotal,4) - 14840.0*pow(analogtotal,3) + 10684.0*pow(analogtotal,2) + 2211.8*(analogtotal) + 7.9623);
    	ugm3 = .518 + .00274 * hppcf;
	return ugm3

def volt2PPB(fileValues,stepResolution, stepWE, stepAE, pollution, temperature):

	zeroVoltageWE 	= 0;
	zeroVoltageAE	= 0;
	ppbOvermV	= 0;
	n 		= 0;
	for row in fileValues.readZeroVoltageOffset():
		if(row[0] == pollution):
			zeroVoltageWE 	= int(row[1]);
			zeroVoltageAE	= int(row[2]);
			ppbOvermV	= float(row[3]);
	for row in fileValues.readTemperatureTable():
		if(row[0]==pollution):
			index = (int(round(temperature)+30)/10)+1
			if(index < 10 and index >= 0):
				n = float(row[int(index)])
			else:
				n = 1;

	voltageWE 	= stepResolution * stepWE;
	voltageAE 	= stepResolution * stepAE;
	voltagePollution = (voltageWE - zeroVoltageWE) - n*(voltageAE - zeroVoltageAE);
	ppb	  = voltagePollution * ppbOvermV;
	return round(ppb,3)

def mainLoop(fileValuesa, ser, csv_file,  conf_values, lockLoop, lockCSVWriteEnable):

	global ctrlLoop	
	global ctrlPrint
	global client_sock
	timeinterval=1
	

	arrayLabel=["TIMESTAMP","CO_WE", "CO_AE", "O3_WE","O3_AE","NO2_WE", "NO2_AE","INT_TEMP"];
	Vref 	 		= int(conf_values[0]); 
	stepResolution 		= Vref/float(conf_values[1]);
	#TEMPERATURE SENSOR
	V20C			= float(conf_values[2])
	timesample2SendPacket 	= int(conf_values[-1])
	count = timesample2SendPacket;

	if(ser.isOpen()):
		print("SERIAL OPENED");
		ser.flushInput()

		csv_writer = csv.writer(csv_file)

		ctrlLoopTmp = 1;
		while(ctrlLoopTmp):
			ser.write("1");
			lineread 	= ser.readline().rstrip()
			arrayline 	= lineread.split(",")
			ts 		= time.time()
			listValue 	= [ts]
			print arrayline
			for valueSensor in arrayline:
				listValue.append(valueSensor);
			if((len(listValue) == 9) and not('' in listValue)):
				temp  = temperature(stepResolution, listValue[-1], V20C);
				CO_ppb=volt2PPB(fileValues, stepResolution, float(listValue[1]), float(listValue[2]),'COA4', temp);
				NO2_ppb=volt2PPB(fileValues, stepResolution, float(listValue[5]), float(listValue[6]), 'NO2A4', temp);
				NO2_O3_ppb=volt2PPB(fileValues, stepResolution, float(listValue[3]), float(listValue[4]), 'O3A4', temp);
				O3_ppb = NO2_O3_ppb - NO2_ppb
				#SO2_ppb=volt2PPB(fileValues, stepResolution, 1024, 1024, 'SO2A4', temp);
				SO2_ppb=random.randint(0, 20)
				PM25=PM25Value(float(listValue[-2]));
				PM10=1;
				print (arrayLabel);
				print (listValue);
				print("CO ppb:" + str(round(CO_ppb, 3)));			
				print("O3 ppb:" + str(round(O3_ppb,3)));			
				print("NO2 ppb:" + str(round(NO2_ppb,3)));			
				print("PM2.5 u/m3:" + str(round(PM25,3)));			
				print("TEMP:" + str(temp));			
				chemicalQuantities2Print = [ts,CO_ppb, O3_ppb, NO2_ppb,PM25 ,temp];
				#IF BT CONNECTION OPEN THEN WRITE	
				lockCSVWriteEnable.acquire()
				if(ctrlPrint == 1):
					csv_writer.writerow(chemicalQuantities2Print)
				lockCSVWriteEnable.release()	
				count = count -1;
				if(count == 0 ):
					string2Send = str(CO_ppb)+","+str(O3_ppb)+","+str(SO2_ppb)+","+str(NO2_ppb)+","+str(PM25)+","+str(PM10)+","+str(temp);
					print("SEND DATA: " + string2Send);
					count  = timesample2SendPacket;
					try:
						lockCSVWriteEnable.acquire()
						if(ctrlPrint == 1 and client_sock != -1):
							client_sock.send(string2Send)
							
						lockCSVWriteEnable.release()
					except BluetoothError as error:
						print "SOCKET CLOSE BY APP"
					#		ctrlLoopTmp = 0
					#		break	
			
			lockLoop.acquire()
			ctrlLoopTmp = ctrlLoop
			lockLoop.release()			
			time.sleep(timeinterval)
	else:
		print("SERIAL ERROR")
	print("OUT FROM MAIN")
	#ser.close()
##############################################

##############################################

def controlFromBT(client_sock, lockLoop):
	global ctrlLoop
	ctrlLoopTmp = 1
	while(ctrlLoopTmp):
		try:
			recv1 = client_sock.recv(1024)
			print("RECV: " + str(recv1))
			ctrlLoop = 0
			print("RECV: %s "% recv1)
			if(recv1 == "CLOSE"):
				lockLoop.acquire()
				ctrlLoop = 0;
				ctrlLoopTmp = ctrlLoop
				lockLoop.release()
				break
		#COMMAND PART

		except BluetoothError as error:
		 	lockLoop.acquire()
			ctrlLoop = 0
			lockLoop.release()
			break
##############################################


def BTThread(lockLoop, lockCSVWriteEnable):
	global ctrlPrint
	global client_sock
	uuid = "00001101-0000-1000-8000-00805F9B34FB";
	print("WAIT BLUETOOTH CONNECTION");
	while(1):
		server_sock	= BluetoothSocket(RFCOMM)
		server_sock.bind(("",1))
		server_sock.listen(1)
	
		port 		= server_sock.getsockname()[1]
		advertise_service(server_sock , "TEST", service_id= uuid);
		print("WAIT BLUETOOTH CONNECTION");
		client_sock_tmp,address = server_sock.accept()
		print "Accepted connection from ",address
	
		t=threading.Thread(target=controlFromBT, args=(client_sock_tmp,lockLoop))
		t.start()
#WRITE ON FILE	
		lockCSVWriteEnable.acquire()
		ctrlPrint = 1
		client_sock = client_sock_tmp
		lockCSVWriteEnable.release()
		t.join()
		lockCSVWriteEnable.acquire()
		ctrlPrint 	= 0
		client_sock.close()
		server_sock.close()
		client_sock 	= -1
		lockCSVWriteEnable.release()
##############################################
def waitBluetoothConnection(fileValues , lockLoop, lockCSVWrite):
	global ctrlLoop

	#ser 		= serial.Serial('/dev/ttyMCC', 115200, serial.EIGHTBITS , serial.PARITY_NONE ,timeout=0)
	ser 	= serial.Serial('/dev/ttyMCC', 115200,timeout=0)
	t=threading.Thread(target=BTThread,args=(lockLoop, lockCSVWrite))
	t.start()
	while(1):
		print "Wait"
		time.sleep(1)
		conf_values	= fileValues.readConfiguration();	


        	randomname      = ''.join(random.choice(string.ascii_uppercase + string.digits) for _ in range(4))
        	filename        = hashlib.md5(randomname).hexdigest() + '.csv'
        	dirName         = "/home/udooer/TeamA/HARDWARE_PROJECT/PYTHON_PROGRAM/OUTPUT_FILE/"
        	csv_file        = open(dirName+filename, 'w')

		t=threading.Thread(target=mainLoop,args=(fileValues, ser, csv_file, conf_values,lockLoop, lockCSVWrite))
		t.start()
		t.join()
		csv_file.close()
		lockLoop.acquire()
                ctrlLoop = 1;
		print "QUA GHE RIVO??%d" % ctrlLoop
                lockLoop.release()
	
	print("CLOSE SERIAL")
	ser.close()
######################################################################

if __name__ == "__main__":
	#READ FILES CONFIGURE
	pathconfig 	= '/home/udooer/TeamA/HARDWARE_PROJECT/PYTHON_PROGRAM/CONFIG_FILE';
	temperatureFile	= 'lookupTableSensors.csv';
	zeroOffsetFile 	= 'ZERO_A4_25000015.csv';
	MAIN_FILE_CONF 	= 'MAIN_CONF.csv'; 
	fileValues 	= readFiles.readFiles(pathconfig , temperatureFile,zeroOffsetFile, MAIN_FILE_CONF)
	lockLoop 	= threading.Lock()
	lockCSVWrite	= threading.Lock()
	
	#signal.signal(signal.SIGINT, signal_handler)
	
	waitBluetoothConnection(fileValues, lockLoop, lockCSVWrite)
	
