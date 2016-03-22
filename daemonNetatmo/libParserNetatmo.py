# Author : Matteo Danieletto matteo.danieletto@gmail.com	
# Public domain source code

# This API provides access to the Netatmo (Internet weather station) devices
# This package can be used with Python2 or Python3 applications and do not
# require anything else than standard libraries

# PythonAPI Netatmo REST data access
# coding=utf-8


import json, time



class parser:

	def __init__(self):
		print ("INIT")

	def parseStation(self,rawmodules):
		# print len(rawmodules)
		numStation = 1;
		encodeValueModule = [1,2,4]
		stationsValue=[]
		for module in rawmodules:
			# print "Station ", numStation
			# print module
			idGeneralModule 	= module['_id']
			position 			= module['place']
			# print "ID ", idGeneralModule
			lat 				= position['location'][0]
			lon 				= position['location'][1]
			timezone 			= position['timezone']
			# print "(%s, %s)" % (lat, lon)

			# print module
			measuraments 		= module['measures']

			countSensor 		= 0
			# print measuraments
			varTimestampRain 	= -1
			varTimestampOutdoor = -1
			varTimestampIndoor 	= -1
			for idSignleMeasurement in measuraments:
				
				if('res' in measuraments[idSignleMeasurement]):
					if(measuraments[idSignleMeasurement]['res'] !=''):
						t = measuraments[idSignleMeasurement]['res']

						if(len(measuraments[idSignleMeasurement]['type']) == 1):
							
							countSensor = countSensor+encodeValueModule[0]
							
							for timestamp in t: 
								varTimestampIndoor = timestamp
								if (measuraments[idSignleMeasurement]['type'][0] == 'pressure'):
									pressure = measuraments[idSignleMeasurement]['res'][varTimestampIndoor][0]
									#this is for pressure		
									# print "(%s %f pressure)" % (varTimestampIndoor , pressure)							
							
						else:
							#temperature and humidity
							mac_outdoor_station = idSignleMeasurement
							
							countSensor 		= countSensor+encodeValueModule[1]

							for timestamp in t: 				
								ii = 0;
								varTimestampOutdoor = timestamp
								# print timestamp.toString()
								for values in measuraments[idSignleMeasurement]['type'] :
								
									if (measuraments[idSignleMeasurement]['type'][ii] == 'temperature'):										
										temperature = measuraments[idSignleMeasurement]['res'][timestamp][ii]										
										# print"(%s %f %s)"%(varTimestampOutdoor ,temperature ,measuraments[idSignleMeasurement]['type'][ii] )	
									else:
										
										humidity = measuraments[idSignleMeasurement]['res'][timestamp][ii]									
										# print"(%s %f %s)"%(varTimestampOutdoor ,humidity ,measuraments[idSignleMeasurement]['type'][ii] )										
									ii= ii + 1
							

				else:		
					ii=0	
					mac_rain_station = idSignleMeasurement				
					for value in measuraments[idSignleMeasurement]:	
						if(ii == 0):					
							rainLive 		 	= measuraments[idSignleMeasurement][value]
						if(ii == 1):
							varTimestampRain 	= measuraments[idSignleMeasurement][value]
						if(ii == 2):					
							rain24h 			= measuraments[idSignleMeasurement][value]
						if(ii == 3):					
							rain60min 			= measuraments[idSignleMeasurement][value]
						ii = ii+1

					if(varTimestampRain != -1):
						countSensor = countSensor + encodeValueModule[2];
					
					# print"(%s %f %f %f)"%(varTimestampRain , rainLive, rain60min, rain24h)
				
			tmpStationValue=[idGeneralModule,timezone,lat,lon,countSensor]

			if(varTimestampIndoor != -1):
				tmpStationValue.extend([int(varTimestampIndoor),pressure ])
			else:
				tmpStationValue.extend([-1,-1 ])
			
			if(varTimestampOutdoor != -1):	
					
				tmpStationValue.extend([mac_outdoor_station,int(varTimestampOutdoor),temperature,humidity])
			else:
				tmpStationValue.extend([ -1,-1,-270,-1 ])

			if(varTimestampRain != -1):
				tmpStationValue.extend([mac_rain_station, int(varTimestampRain),rainLive,rain60min,rain24h])

			else:
				tmpStationValue.extend([-1,-1,-1,-1,-1])

			#I HAVE TO BUILD THE LIST		
			numStation 		= numStation +1
			# print tmpStationValue
			stationsValue.append(tmpStationValue)
			# print stationsValue
			# raw_input("Press Enter to continue...")
		return stationsValue
