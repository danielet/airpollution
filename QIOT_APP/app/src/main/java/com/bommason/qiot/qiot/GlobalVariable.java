package com.bommason.qiot.qiot;

import android.app.Application;

/**
 * Created by p on 2016-02-20.
 */
public class GlobalVariable extends Application {
    private String LOGIN_ID;

    public String getData(){
        return LOGIN_ID;
    }
    public void setData(String ID){
        this.LOGIN_ID = ID;
    }
}
