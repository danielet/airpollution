package com.bommason.qiot.qiot;

/**
 * Created by BOM2 on 2016-02-08.
 */
public class DataArray {
    String time, co, so2, no2, o3, pm25, temp, lat, lon;
    String h_time,h_rate,h_rr;
    String csv_data;
    String split = "\",\"";

    public DataArray(){
    }

    public DataArray(String time, String rate, String rr){
        this.h_time = time;
        this.h_rate = rate;
        this.h_rr = rr;
    }

    public DataArray(String param_time, String param_co, String param_so2, String param_no2, String param_o3, String param_pm25,
                     String param_temp, String param_lat, String param_lon, String param_hr, String param_rr){
        this.time = param_time;
        this.co = param_co;
        this.so2 = param_so2;
        this.no2 = param_no2;
        this.o3 = param_o3;
        this.pm25 = param_pm25;
        this.temp = param_temp;
        this.lat = param_lat;
        this.lon = param_lon;
        this.h_rate = param_hr;
        this.h_rr = param_rr;
    }
    public void CsvDataMaker(){
        csv_data = time +split+ co +split+ so2 +split+ no2 +split+ o3 +split+ pm25
                    +split+ temp +split+ lat +split+ lon +split+ h_rate +split+ h_rr;
    }

}
