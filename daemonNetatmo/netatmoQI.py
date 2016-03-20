#!/usr/bin/python3
# encoding=utf-8

import lnetatmo
import json
import libParserNetatmo
import time
import lmysqlNetatmo

import sys 
import getopt
import os
import signal



class netatmoQI:
	kill_now = False
	def __init__(self , StationsWOC=0, StationsUOC=0):
		signal.signal(signal.SIGINT, self.exit_gracefully)
		signal.signal(signal.SIGTERM, self.exit_gracefully)
		
		self.ENABLE_FIRST_PART = StationsWOC
		self.ENABLE_SECOND_PART = StationsUOC;
		####################
		self.user			= "mdaniele"
		self.password		= "Esedra#84"
		self.host			= "127.0.0.1"
		self.database		= "netatmo"
		####################
		####################
		self.lat_ne 		= '32.963163';
		self.lon_ne			= '-117.096405';
		self.lat_sw			= '32.836616';
		self.lon_sw 		= '-117.233047';
		####################


	def exit_gracefully(self,signum, frame):
		self.kill_now = True

	def stringFile(self):
		hour	= time.strftime("%H")
		minute	= time.strftime("%M")
		day 	= time.strftime("%d")
		month 	= time.strftime("%m")
		year 	= time.strftime("%y")
		cwd 	= os.getcwd()
		namefile = ("%s/log/NETATMOQI_%s_%s_%s_%s_%s.log")%(cwd,month,day,year,hour,minute)
		
		return namefile

	def main(self, argv):
		
		print (self.user)
		
		timesample 		= 600 #SECONDS TO SLEEP
		namefile =self.stringFile()
		f = open(namefile,'w')
		# print namefile
		stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:START APPLICATION\n"
		f.write(stringTOwrite)	

		try:
			opts, args = getopt.getopt(argv,"h:t:")
		except getopt.GetoptError:
			print ('test.py -t <time in seconds>')
			sys.exit(2)

		for opt, arg in opts:
			if opt == '-h':
				print ('netatmpoQI.py -t <time in seconds>')
				sys.exit()
			elif opt in ("-t", "--ifile"):
				timesample = arg

		
		stationsAlreadyAddTimestamp		= {}
		stationUnderControlTimestamp 	= {}
		modulUnderControlTimestamp 		= {}
		


		#CREATE DICTIONARY WITH MAC ADDRESS AS KEY AND TIMESTAMP as ARRAY VALUE


		#RUN A while a signal is triggered to close the program
		ctrlCHECKSAMPLE 	= 1;

		coldStartStation 	= 0;
		coldStartModule 	= 0;

		ctrlModuleStation 	= 0;
	#TRANSFORM IN while(true):
		# for indexEXEC in range(1,5) :
		indexEXEC = 0
		while(True):
			authorization 	= lnetatmo.ClientAuth()
			#OPEN DB CONNECTION
			mysqlObj 		= lmysqlNetatmo.mysqlLib( self.user,self.password, self.host, self.database)
			# mysqlObj.checkUser()
			stations 		= lnetatmo.StationsList(authorization)

			
			
			mysqlObj.openConnection()

			stringJSON 		= stations.getMeasureZone(self.lat_ne, self.lon_ne, self.lat_sw, self.lon_sw)
			rawmodules 		= stringJSON['body']
			extractSration	= libParserNetatmo.parser()
			stationsValue	= extractSration.parseStation(rawmodules)

			encodeValueTime = [1,2,4]
			# print len(stationsValue)
			samplesadded = 0

			if(self.ENABLE_FIRST_PART == 1):
				for station in stationsValue:
					# print station
					# raw_input("Press Enter to continue...")
					# print station
					checkTime = 0
					#CHECK IF STATION EXIST
					mac_address_station 	= station[0]
					timestamp_main_station 	= station[5]
					add_device  			= station[4] 	
					arrayTimestampRead 		= [station[5],station[8],station[12]] 

					if(mac_address_station in stationsAlreadyAddTimestamp):
						#check timestamp
						arrayTimestamp = stationsAlreadyAddTimestamp[mac_address_station]
						indexTime 	   = 0 
					
						for valueTimestamp in arrayTimestamp:

							if (valueTimestamp != arrayTimestampRead[indexTime] and (int(arrayTimestampRead[indexTime]) != -1)):
								#refresh with new value
								#DOVREBBE ESSERCI UN ERRORE!!!!
								arrayTimestamp[indexTime] = arrayTimestampRead[indexTime]
								checkTime = checkTime + encodeValueTime[indexTime]
							
							indexTime = indexTime + 1
						#DOVREI AVER CORRETTO L'ERRORE	
						stationsAlreadyAddTimestamp[mac_address_station] = arrayTimestamp
					else:
						# print arrayTimestampRead
						timestampArray=arrayTimestampRead
						indexTime 	   = 0
						for valueTimestamp in arrayTimestampRead:
							if valueTimestamp != -1:								
								checkTime = checkTime + encodeValueTime[indexTime]
							indexTime = indexTime + 1
						stationsAlreadyAddTimestamp[mac_address_station]=timestampArray
						

					#HERE I CHECK IF THE STATION WAS ALREADY INSERT INTO THE DB
					id_station = mysqlObj.checkStation(mac_address_station)
					if(id_station == -1):
						zone 					= station[1]
						lat 					= station[2]
						lon 					= station[3]
						typeStation 			= 'Main station'
						note 					= 'TBD'
						mysqlObj.insertStation(mac_address_station,zone,lat, lon,add_device,typeStation, arrayTimestampRead[0] ,note , 0)
						id_station = mysqlObj.checkStation(mac_address_station)
						#LOG ADDED STATION
						stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:ADDED STATION:" +mac_address_station+"\n"								
						f.write(stringTOwrite)
					else:
							#UPDATE TIMESTAMP STATION
							mysqlObj.updateTimestampStation(id_station,mac_address_station,arrayTimestampRead[0])
					if (checkTime > 0):
						
									
					#UPDATE ONLY PRESSURE		
						if(((checkTime % 2) == 1) and (int(arrayTimestampRead[0]) != -1)):
							pressure 					= station[6]
							mac_address_module 			= mac_address_station
							result = mysqlObj.insertStationValues(id_station,mac_address_station,arrayTimestampRead[0],pressure,ctrlCHECKSAMPLE)

							
					
							if(result == 1):
								samplesadded = samplesadded+1
					#UPDATE ONLY TEMPERATURE AND HUMIDITY
						if((checkTime > 2) and (checkTime != 4 ) and (checkTime != 5 ) and (int(arrayTimestampRead[1]) != -1)):
							mac_address_module 			= station[7]			
							temperature					= station[9]
							humidity 					= station[10]
							mysqlObj.insertStationOutdoorValues(id_station,mac_address_station,mac_address_module,arrayTimestampRead[1],temperature,humidity,ctrlCHECKSAMPLE)
							if(result == 1):
								samplesadded = samplesadded+1
					#UPDATE ONLY RAIN		
						if((checkTime > 3) and (int(arrayTimestampRead[2]) != -1)):
							mac_address_module 			= station[11]
							rainLive 					= station[13]
							rain60min					= station[14]
							rain24h						= station[15]
							result = mysqlObj.insertRainValues(id_station,mac_address_station,mac_address_module,arrayTimestampRead[2],rainLive, rain60min, rain24h, ctrlCHECKSAMPLE)
							if(result == 1):
								samplesadded = samplesadded+1
				
				if(samplesadded > 0):
					#LOG ADDED 
					stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:ADDED SAMPLES:" + str(samplesadded)+"\n"
					f.write(stringTOwrite)
				
						
				ctrlCHECKSAMPLE = 0


				# raw_input("Press Enter to continue...")
	##################STATION UNDER OUR CONTROL########
			if(self.ENABLE_SECOND_PART == 1):
				devList 			 = lnetatmo.DeviceList(authorization)
				stationsUnderControl = devList.stationByNameMatteo()
				if (stationsUnderControl!=None):

					for station in stationsUnderControl:
						print (station)
						mac_address_station = station[0];
						timestamp 			= station[1];
						ctrlownStatioValue = 0
						

						id_station=mysqlObj.checkStation(mac_address_station);
						if(id_station == -1):

							lat 					= station[-1]
							lon 					= station[-2]
							zone 					= station[-3]
							typeStation 			= 'Main station'
							note 					= 'TBD'
							mysqlObj.insertStation(mac_address_station,zone,lat, lon,add_device,typeStation, arrayTimestampRead[0] ,note , 0)
							id_station = mysqlObj.checkStation(mac_address_station)
							#LOG ADDED STATION
							stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:ADDED STATION:" +mac_address_station+"\n"								
							f.write(stringTOwrite)


							print (mac_address_station);
							print (id_station);
							# raw_input("2 Press Enter to continue...")

						if(coldStartStation):
							coldStartStation = 1;
							#CHECK DATABASE
							#CHECK IF EXIST STATION
							changeOwnership=mysqlObj.checkOwnStation(mac_address_station,0) # 1  the DEGUB is ON
							if(changeOwnership == 1):
								stationUnderControlTimestamp[mac_address_station] = timestamp
								ctrlownStatioValue = 1;
								ctrlModuleStation  = 1;
							else:
								#CHECK LA SAMPLES ADDED INTO THE DB
								if (mysqlObj.checkOwnStationTimesample(mac_address_station, timestamp,1) == 1):
									ctrlownStatioValue = 1;
						else:	
							if(mac_address_station in stationUnderControlTimestamp):								
								if(stationUnderControlTimestamp[mac_address_station]!= timestamp ):
									ctrlownStatioValue = 1;
									stationUnderControlTimestamp[mac_address_station] = timestamp
									ctrlModuleStation  = 0;
									#HERE CHECK OWNERSHIP

							else:
								mysqlObj.checkOwnStation(mac_address_station,1) # 1  the DEGUB is ON
								stationUnderControlTimestamp[mac_address_station] = timestamp
								if (mysqlObj.checkOwnStationTimesample(mac_address_station, timestamp,1) == 1):
									ctrlownStatioValue = 1;
									ctrlModuleStation  = 0;






						if(ctrlownStatioValue == 1):
							print (mac_address_station)
							#UPDATE STATION SAMPLE
							temperature 		= station[2]
							CO2 				= station[3]
							humidity 			= station[4]			
							pressure 			= station[5]
							absolute_pressure 	= station[6]
							noise 				= station[7]				
							mysqlObj.addStationOwnSample(id_station, mac_address_station, timestamp,temperature,humidity,CO2,noise,pressure,absolute_pressure,ctrlModuleStation)
							stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:ADDED VALUE OUR STATION:%s\n"% mac_address_station
							f.write(stringTOwrite)	

						module=devList.moduleByNameMatteo(mac_address_station)
						if module != None :
							#UPDATE EXTRA MODULE SAMPLE
							print (module)
							addSampleModule 	= 1
							mac_address_module 	= module[1]
							timestampModule 	= module[2]
							if (mac_address_module in modulUnderControlTimestamp ):
								#check timestamp
								if (modulUnderControlTimestamp[mac_address_module] != timestampModule ):
									modulUnderControlTimestamp[mac_address_module] = timestampModule
									ctrlModule = 0
								else:
									#altra variabile
									addSampleModule = 0
							else:
								modulUnderControlTimestamp[mac_address_module] = timestampModule
								ctrlModule = 1
							
							if(addSampleModule == 1):
								temperature 		= module[3]
								CO2 				= module[4]
								humidity 			= module[5]
								
								module=mysqlObj.addModuleOwnSample(mac_address_module,id_station, mac_address_station, timestamp,temperature,humidity,CO2 , ctrlModule,1)								
								stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:ADDED ADDITIONAL MODULE VALUE:%s\n"% mac_address_module
								f.write(stringTOwrite)	
				# raw_input("Press Enter to continue...")		
				
				
			#END STATIONS AND OWN STATIONS			
			print (("INTERATION %d") % indexEXEC)

			indexEXEC = indexEXEC + 1
			mysqlObj.closeConnection()			
			if(self.kill_now==True):				
				break
			time.sleep(float(timesample))	
		stringTOwrite = "["+time.strftime("%Y-%m-%d %H:%M:%S") + "]:END APPLICATION\n"
		f.write(stringTOwrite)	
		f.close()
		# print "END APPLICATION"

if __name__ == "__main__":
	netatmoRead = netatmoQI(1,1)
	netatmoRead.main(sys.argv[1:])
