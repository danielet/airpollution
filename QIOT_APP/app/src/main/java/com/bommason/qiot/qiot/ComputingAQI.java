package com.bommason.qiot.qiot;

/**
 * Created by p on 2016-02-10.
 */
public class ComputingAQI {
    public double Computing(int flag,double change_data){
        // 1.CO  2.SO2  3.NO2  4.O3  5.PM2.5  6.PM10
        switch (flag){
            case 1: //CO
                if(change_data >50.4) return -1;
                else if(change_data>40.4)   return ((99/9.9)*(change_data-40.5)+401);
                else if(change_data>30.4)   return ((99/9.9)*(change_data-30.4)+301);
                else if(change_data>15.4)   return ((99/14.9)*(change_data-15.5)+201);
                else if(change_data>12.4)   return ((49/2.9)*(change_data-12.5)+151);
                else if(change_data>9.4)    return ((49/2.9)*(change_data-9.5)+101);
                else if(change_data>4.4)    return ((49/4.9)*(change_data-4.5)+51);
                else if(change_data>0)      return ((49/4.4)*(change_data-0)+0);
            case 2: //SO2
                if(change_data >1004) return -1;
                else if(change_data>804)   return ((99/199)*(change_data-805)+401);
                else if(change_data>604)   return ((99/199)*(change_data-605)+301);
                else if(change_data>304)   return ((99/299)*(change_data-305)+201);
                else if(change_data>185)   return ((49/118)*(change_data-186)+151);
                else if(change_data>75)    return ((49/109)*(change_data-76)+101);
                else if(change_data>35)    return ((49/39)*(change_data-36)+51);
                else if(change_data>0)     return ((49/35)*(change_data-0)+0);
            case 3://NO2
                if(change_data >2049) return -1;
                else if(change_data>1649)   return ((99/399)*(change_data-1650)+401);
                else if(change_data>1249)   return ((99/399)*(change_data-1250)+301);
                else if(change_data>649)    return ((99/599)*(change_data-650)+201);
                else if(change_data>360)    return ((49/288)*(change_data-361)+151);
                else if(change_data>100)    return ((49/259)*(change_data-101)+101);
                else if(change_data>53)     return ((49/46)*(change_data-54)+51);
                else if(change_data>0)      return ((49/53)*(change_data-0)+0);
            case 4: //O3
                if(change_data >604) return -1;
                else if(change_data>504)   return ((99/100)*(change_data-505)+401);
                else if(change_data>404)   return ((99/100)*(change_data-405)+301);
                else if(change_data>204)   return ((99/200)*(change_data-205)+201);
                else if(change_data>164)   return ((49/40)*(change_data-165)+151);
                else if(change_data>100)    return ((49/64)*(change_data-101)+101);
                else if(change_data>54)    return ((49/46)*(change_data-55)+51);
                else if(change_data>0)      return ((49/54)*(change_data-0)+0);
            case 5: //PM2.5
                if(change_data >500.4) return -1;
                else if(change_data>350.4)   return ((99/150)*(change_data-350.5)+401);
                else if(change_data>250.4)   return ((99/100)*(change_data-250.5)+301);
                else if(change_data>150.4)   return ((99/100)*(change_data-150.5)+201);
                else if(change_data>55.4)    return ((49/95)*(change_data-55.5)+151);
                else if(change_data>35.4)    return ((49/20)*(change_data-35.5)+101);
                else if(change_data>12.0)    return ((49/13.4)*(change_data-12.1)+51);
                else if(change_data>0)       return ((49/12)*(change_data-0)+0);
            case 6: //temp
                if(change_data >40) return -1;
                else if(change_data>34)   return ((99/100)*(change_data-505)+401);
                else if(change_data>30)   return ((99/80)*(change_data-425)+301);
                else if(change_data>354)   return ((99/70)*(change_data-355)+201);
        }
        return 0;
    }
}
