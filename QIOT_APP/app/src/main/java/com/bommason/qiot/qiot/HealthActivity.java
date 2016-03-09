package com.bommason.qiot.qiot;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.ToggleButton;

import com.bommason.qiot.ble.BluetoothLeService;
import com.bommason.qiot.ble.PolarBleService;
import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.Legend;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.LineData;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.StringTokenizer;
import java.util.Timer;
import java.util.TimerTask;

public class HealthActivity extends AppCompatActivity {

    private final String TAG = "MainActivity";

    ArrayList<DataArray> data_to_array = new ArrayList<DataArray>();
    DataArray d_array;
    DrawGraph d_graph;
    Timer current_timer;
    ToggleButton tbtn_turn;
    TextView[] txt_first,txt_second,txt_third;
    int hr,rr,prr;
    LineChart mChart;
    PolarBleService mPolarBleService;
    //String mpolarBleDeviceAddress="00:22:DO:26:3O:27";
    private boolean bleServiceConnectionBounded=false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_health);

        tbtn_turn = (ToggleButton)findViewById(R.id.tbtn_health);
        txt_first = new TextView[]{(TextView)findViewById(R.id.txt_time1), (TextView)findViewById(R.id.txt_heart1), (TextView)findViewById(R.id.txt_rr1)};
        txt_second = new TextView[]{(TextView)findViewById(R.id.txt_time2), (TextView)findViewById(R.id.txt_heart2), (TextView)findViewById(R.id.txt_rr2)};
        txt_third = new TextView[]{(TextView)findViewById(R.id.txt_time3), (TextView)findViewById(R.id.txt_heart3), (TextView)findViewById(R.id.txt_rr3)};

        mChart = (LineChart)findViewById(R.id.chart_graph);

        tbtn_turn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (current_timer == null) {
                    //Timer make, start
                    current_timer = new Timer();
                    MainTimerTask timerTask = new MainTimerTask();
                    current_timer.schedule(timerTask, 500, 1000);
                } else {  //Timer Stop, delete
                    current_timer.cancel();
                    current_timer = null;
                }
            }
        });
        d_graph = new DrawGraph(mChart,data_to_array);
        SettingChart();
        registerReceiver(mPolarBleUpdateReceiver, makePolarGattUpdateIntentFilter());
    }
    private Handler mHandler = new Handler();
    private Runnable mUpdateTimeTask = new Runnable() {
        public void run() {
            Date rightNow = new Date();
            SimpleDateFormat formatter = new SimpleDateFormat("hh:mm:ss"); //  "hh:mm:ss dd.MM.yyyy"
            String dateString = formatter.format(rightNow);
            RowDown();
            txt_first[0].setText(dateString);
            TextSetting();
            d_array = new DataArray(txt_first[0].getText().toString(),txt_first[1].getText().toString(),txt_first[2].getText().toString());
            data_to_array.add(d_array); // 데이터를 ArrayList에 저장
            /* GRAPH */
            d_graph.AddHeartData(data_to_array);
            d_graph.DrawGraphLineCurrent();
        }
    };//Runnable

    class MainTimerTask extends TimerTask {
        public void run() {
            mHandler.post(mUpdateTimeTask);
        }
    }//TimerTask

    public void TextSetting(){
        try{
            txt_first[1].setText(hr+"");
            txt_first[2].setText(prr+"");
        }catch (Exception e){
        }
    }
    public void RowDown() {
        for (int i = 0; i < 3; i++) {
            txt_third[i].setText(txt_second[i].getText());
            txt_second[i].setText(txt_first[i].getText());
        }
    }//RowDown

    private void SettingChart(){
        // no description text
        mChart.setDescription("");
        mChart.setNoDataTextDescription("You need to provide data for the chart.");
        // enable touch gestures
        mChart.setTouchEnabled(true);
        // enable scaling and dragging
        mChart.setDragEnabled(true);
        mChart.setScaleEnabled(true);
        mChart.setDrawGridBackground(false);
        // if disabled, scaling can be done on x- and y-axis separately
        mChart.setPinchZoom(true);
        // set an alternative background color
        mChart.setBackgroundColor(Color.rgb(220, 220, 220));
        LineData data = new LineData();
        data.setValueTextColor(Color.WHITE);
        // add empty data
        mChart.setData(data);
        //Typeface tf = Typeface.createFromAsset(getAssets(), "OpenSans-Regular.ttf");
        // get the legend (only possible after setting data)
        Legend l = mChart.getLegend();
        // modify the legend ...
        // l.setPosition(LegendPosition.LEFT_OF_CHART);
        l.setForm(Legend.LegendForm.LINE);
        //l.setTypeface(tf);
        l.setTextColor(Color.WHITE);
        // X
        XAxis xl = mChart.getXAxis();
        //xl.setTypeface(tf);
        xl.setTextColor(Color.WHITE);
        xl.setDrawGridLines(false);
        xl.setAvoidFirstLastClipping(true);
        xl.setSpaceBetweenLabels(5);
        xl.setEnabled(true);
        // Y
        YAxis leftAxis = mChart.getAxisLeft();
        //leftAxis.setTypeface(tf);
        leftAxis.setTextColor(Color.WHITE);
        leftAxis.setAxisMaxValue(200f);
        leftAxis.setAxisMinValue(0f);
        leftAxis.setStartAtZero(false);
        leftAxis.setDrawGridLines(true);
        YAxis rightAxis = mChart.getAxisRight();
        rightAxis.setEnabled(false);
    }


    private final BroadcastReceiver mPolarBleUpdateReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context ctx, Intent intent) { // 센서에서 받은 거를 heart rate, prr이 우리가 원하는 rr...평균 심박수, rr은 몇번했는지 count
            final String action = intent.getAction();
            if (BluetoothLeService.ACTION_GATT_CONNECTED.equals(action)) {
            } else if (BluetoothLeService.ACTION_GATT_DISCONNECTED.equals(action)) {
            } else if (PolarBleService.ACTION_HR_DATA_AVAILABLE.equals(action)) {
                String data = intent.getStringExtra(BluetoothLeService.EXTRA_DATA);
                StringTokenizer tokens = new StringTokenizer(data, ";");
                hr = Integer.parseInt(tokens.nextToken());
                prr = Integer.parseInt(tokens.nextToken());
                rr = Integer.parseInt(tokens.nextToken());

                Log.w(TAG, "####Received HR: " + hr + " RR: " + rr + " pRR: " + prr);
            }
        }
    };

    private static IntentFilter makePolarGattUpdateIntentFilter() {
        final IntentFilter intentFilter = new IntentFilter();
        intentFilter.addAction(BluetoothLeService.ACTION_GATT_CONNECTED);
        intentFilter.addAction(BluetoothLeService.ACTION_GATT_DISCONNECTED);
        intentFilter.addAction(BluetoothLeService.ACTION_GATT_SERVICES_DISCOVERED);
        intentFilter.addAction(PolarBleService.ACTION_HR_DATA_AVAILABLE);
        return intentFilter;
    }

}
