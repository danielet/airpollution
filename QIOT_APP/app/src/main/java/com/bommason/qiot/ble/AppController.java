package com.bommason.qiot.ble;

public class AppController {
	public String clickerBleDeviceAddress=null;
	public String clickerProfileName=null;
	public int clickerProfileId=-1;
	public boolean bleDeviceServiceConnected=false;
	public boolean clickerAudibleOn=false;
	public boolean fallDectionOn=true;   
	public String loggerTimeStamp=null;
	public String logFilePath;
	
	public String polarBleDeviceAddress="00:22:D0:26:30:27";
	public boolean polarBleDeviceServiceConnected=false;

	private static AppController instance = new AppController();
	public static AppController getInstance() {
		return instance;
	}	
	

}

