
# Author : Matteo Danieletto, mdanieletto@eng.ucsd.edu

from sys import version_info
import json, time



import pymysql

class mysqlLib:

#CREATE FIELD FOR CONNECTION

	def __init__(self, user, password, host, database):
		self.user  		= user
		self.password 	= password
		self.host 		= host
		self.database	= database	

	def openConnection(self):
		# print self.user
		self.connection = pymysql.connect(self.host, self.user, self.password, self.database)
		# print self.connection
	def closeConnection(self):		
		self.connection.close()


###########CHECK PART######################		
	def checkUser(self):
		#HERE I HAVE TO IMPROVE FOR THE SQL QUERY
		SQL_STRING = "SELECT id FROM Users"
		# print SQL_STRING
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)		
		result = cursor.fetchone()
		

	def checkStation(self, MAC_ADDRESS_STATION,DEBUG_SQL=0):
		SQL_STRING = "SELECT ID_STATION FROM Stations WHERE MAC_ADDRESS_STATION=\'%s\'"%MAC_ADDRESS_STATION
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		result = cursor.fetchone()
	
		if (cursor.rowcount == 0):
			return -1
		else:
			return result[0]

	def checkOwnStation(self, MAC_ADDRESS_STATION,DEBUG_SQL=0):
		SQL_STRING = "SELECT Ownership FROM Stations WHERE MAC_ADDRESS_STATION=\'%s\'"%MAC_ADDRESS_STATION
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		result = cursor.fetchone()	
		if (cursor.rowcount != 0):
			 
			ownership = result[0]
			if(ownership != 1):
				#CHANGE VALUE OWN
				SQL_STRING = "UPDATE Stations SET Ownership = 1   WHERE MAC_ADDRESS_STATION=\'%s\'"% MAC_ADDRESS_STATION
				if(DEBUG_SQL == 1):
					print (SQL_STRING)
				cursor = self.connection.cursor()
				cursor.execute(SQL_STRING)
				self.connection.commit()
				return 1

		return -1
	
	def checkOwnStationTimesample(self, MAC_ADDRESS_STATION,timestamp,DEBUG_SQL=0 ):
		SQL_STRING = "SELECT Timestamp FROM Stations WHERE MAC_ADDRESS_STATION=\'%s\'"%MAC_ADDRESS_STATION
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		result = cursor.fetchone()
	
		if (cursor.rowcount != 0):
			sqlTimestampt = result[0]
			if(sqlTimestampt != timestamp):			
				return 1
		
		return -1


########UPDATE PART##################	
	def updateTimestampStation(self, id_station ,MAC_ADDRESS_STATION,timestamp, DEBUG_SQL=0 ):
		SQL_STRING = "UPDATE Stations SET timestamp=\'%s\'  WHERE (ID_STATION=\'%s\' AND MAC_ADDRESS_STATION=\'%s\')" % (timestamp,id_station,MAC_ADDRESS_STATION)
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		self.connection.commit()

########INSERT PART##################


	def insertStation(self, id_station,zone ,lat,lon,adddevice,typeStation, Timestamp ,note,DEBUG_SQL=0 ):
		SQL_STRING = "INSERT INTO Stations (MAC_ADDRESS_STATION, Latitude, Longitude, Zone, Type,Additional_Device,Timestamp ,Note )  VALUES(\'%s\', \'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\' ,\'%s\')"% (id_station,lat,lon,zone,typeStation,adddevice,Timestamp,note)

		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		self.connection.commit()
		
								
	def insertStationOutdoorValues(self, id_station,mac_address_station,mac_address_module,timestamp,temperature,humidity,ctrl,DEBUG_SQL=0):
		countRow = 0
		if(ctrl == 1):
			SQL_STRING = "SELECT MAC_ADDRESS_MODULE, Timestamp FROM Data_Outdoor WHERE MAC_ADDRESS_MODULE=\'%s\' AND Timestamp=\'%s\' "% (mac_address_module, timestamp)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			countRow = cursor.rowcount
		if (countRow == 0):
			SQL_STRING = "INSERT INTO Data_Outdoor (MAC_ADDRESS_MODULE,ID_STATION,MAC_ADDRESS_STATION, Timestamp,Temperature, Humidity)  "\
				"VALUES(\'%s\', \'%s\',\'%s\',\'%s\',\'%s\',\'%s\' )"\
				%(mac_address_module,id_station,mac_address_station,timestamp,temperature,humidity)

			if(DEBUG_SQL == 1):
				print (SQL_STRING)

			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			return 1
		return 0

	def insertStationValues(self, id_station,mac_address_station,timestamp_main_station,pressure,ctrl,DEBUG_SQL=0):
		
		countRow = 0
		if(ctrl == 1):
			SQL_STRING = "SELECT MAC_ADDRESS_STATION, Timestamp FROM Data_Indoor_NoOwn WHERE MAC_ADDRESS_STATION=\'%s\' AND Timestamp=\'%s\' "% (mac_address_station, timestamp_main_station)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			countRow = cursor.rowcount
		if (countRow == 0):
			SQL_STRING = "INSERT INTO Data_Indoor_NoOwn (ID_STATION,MAC_ADDRESS_STATION, Timestamp,Barometric_Pressure )  "\
				"VALUES(\'%s\', \'%s\',\'%s\',\'%s\')"\
				%(id_station,mac_address_station,timestamp_main_station,pressure)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			return 1
		return 0

	def insertRainValues(self, id_station,mac_address_station,mac_address_module,timestamp_main_station,rainLive,rain60min,rain24h,ctrl, DEBUG_SQL=0):
		countRow = 0
		if(ctrl == 1):
			SQL_STRING = "SELECT MAC_ADDRESS_MODULE, Timestamp FROM Rain_modules WHERE MAC_ADDRESS_MODULE=\'%s\' AND Timestamp=\'%s\' "% (mac_address_module, timestamp_main_station)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			countRow = cursor.rowcount
		if (countRow == 0):
			SQL_STRING = "INSERT INTO Rain_modules (MAC_ADDRESS_MODULE,ID_STATION,MAC_ADDRESS_STATION, Timestamp,rainLive, rain60min,rain24h )  "\
				"VALUES(\'%s\', \'%s\',\'%s\',\'%s\',\'%s\',\'%s\' ,\'%s\')"\
				%(mac_address_module,id_station,mac_address_station,timestamp_main_station,rainLive,rain60min,rain24h)

			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			return 1
		return 0


	def addStationOwnSample(self, id_station,mac_address_station,timestamp_main_station,temperature,humidity,CO2,noise,pressure,absolute_pressure,ctrl,DEBUG_SQL=0):
		countRow = 0
		# if(ctrl == 1):
		SQL_STRING = "SELECT  Timestamp FROM Data_Indoor WHERE ID_STATION=\'%s\' AND MAC_ADDRESS_STATION=\'%s\' AND Timestamp=\'%s\' "% (id_station,mac_address_station, timestamp_main_station)
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		self.connection.commit()
		countRow = cursor.rowcount
		print (SQL_STRING);
		

		if(countRow == 0):
			SQL_STRING = "INSERT INTO Data_Indoor (ID_STATION,MAC_ADDRESS_STATION, Timestamp,CO2, Temperature, Humidity, Noise_Sound, Pressure, Absolute_pressure)"\
				"VALUES(\'%s\', \'%s\',\'%s\',\'%s\', \'%s\',\'%s\', \'%s\',\'%s\',\'%s\')"\
				%(id_station,mac_address_station,timestamp_main_station,CO2,temperature,humidity,noise,pressure,absolute_pressure)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			return 1
		return -1
	def addModuleOwnSample(self, mac_address_module ,id_station,mac_address_station, timestampInput,temperature,humidity,CO2,ctrl,DEBUG_SQL=0):
		countRow = 0
		# if(ctrl == 1):
		print ('CHECK IF THERE IS THE SAMPLE')
		SQL_STRING = "SELECT  Timestamp FROM Data_Indoor_NoMainStation WHERE MAC_ADDRESS_MODULE=\'%s\' AND Timestamp=\'%s\' "% (mac_address_module, timestampInput)
		if(DEBUG_SQL == 1):
			print (SQL_STRING)
		cursor = self.connection.cursor()
		cursor.execute(SQL_STRING)
		self.connection.commit()
		countRow = cursor.rowcount
		print ("QUA " + str(countRow));
		
		if(countRow == 0):
			SQL_STRING = "INSERT INTO Data_Indoor_NoMainStation (MAC_ADDRESS_MODULE,ID_STATION,MAC_ADDRESS_STATION, Timestamp,CO2, Temperature, Humidity)"\
				"VALUES(\'%s\', \'%s\',\'%s\',\'%s\',\'%s\',\'%s\',\'%s\')"%(mac_address_module, id_station, mac_address_station,timestampInput,CO2,temperature,humidity)
			if(DEBUG_SQL == 1):
				print (SQL_STRING)
			cursor = self.connection.cursor()
			cursor.execute(SQL_STRING)
			self.connection.commit()
			return 1
		
		return -1