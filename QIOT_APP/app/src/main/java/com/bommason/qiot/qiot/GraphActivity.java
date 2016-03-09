package com.bommason.qiot.qiot;

import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.WindowManager;
import android.widget.TextView;
import android.widget.ToggleButton;

import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.Legend;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.Entry;
import com.github.mikephil.charting.data.LineData;
import com.github.mikephil.charting.data.LineDataSet;
import com.github.mikephil.charting.interfaces.datasets.ILineDataSet;
import com.github.mikephil.charting.utils.ColorTemplate;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;

public class GraphActivity extends AppCompatActivity{
    //TextView[] txt_first;
    LineChart mChart;
    TextView tv1, tv2;
    TextView[] txt_first, txt_second, txt_third;    //Low1, Low2, Low3
    ToggleButton tbn_start;
    private GpsInfo gps;
    Timer mTimer;
    int interval = 3000;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_graph);
        //txt_first = new TextView[9];
        String str1, str2;
        mChart = (LineChart) findViewById(R.id.chart2);
        tv1 = (TextView) findViewById(R.id.txt_LON);
        tv2 = (TextView) findViewById(R.id.txt_LAT);
        txt_first = new TextView[]{(TextView) findViewById(R.id.txt_time_1), (TextView) findViewById(R.id.txt_CO_1),
                (TextView) findViewById(R.id.txt_SO2_1), (TextView) findViewById(R.id.txt_NO2_1), (TextView) findViewById(R.id.txt_O3_1),
                (TextView) findViewById(R.id.txt_PM25_1), (TextView) findViewById(R.id.txt_PM10_1)};
        txt_second = new TextView[]{(TextView) findViewById(R.id.txt_time_2), (TextView) findViewById(R.id.txt_CO_2),
                (TextView) findViewById(R.id.txt_SO2_2), (TextView) findViewById(R.id.txt_NO2_2), (TextView) findViewById(R.id.txt_O3_2),
                (TextView) findViewById(R.id.txt_PM25_2), (TextView) findViewById(R.id.txt_PM10_2)};
        txt_third = new TextView[]{(TextView) findViewById(R.id.txt_time_3), (TextView) findViewById(R.id.txt_CO_3),
                (TextView) findViewById(R.id.txt_SO2_3), (TextView) findViewById(R.id.txt_NO2_3), (TextView) findViewById(R.id.txt_O3_3),
                (TextView) findViewById(R.id.txt_PM25_3), (TextView) findViewById(R.id.txt_PM10_3)};
        tbn_start = (ToggleButton)findViewById(R.id.tb_start_graph);
        tbn_start.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mTimer == null) {
                    //Timer make, start
                    mTimer = new Timer();
                    MainTimerTask timerTask = new MainTimerTask();
                    mTimer.schedule(timerTask, 500, interval);
                } else {  //Timer Stop, delete
                    mTimer.cancel();
                    mTimer = null;
                    //SaveData();
                }
            }
        });

        // Intent intent = new Intent(this.getIntent());
      //  str1 = intent.getStringExtra("Text1_0");
      //  str2 = intent.getStringExtra("Text1_1");
      //  tv1.setText(str1);
      //  tv2.setText(str2);
        SettingChart();
        //addEntry(txt_first[0].getText().toString(),txt_first[1].getText().toString());
        //addEntry(str1,str2);
        //addEntry("10","2000");
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
            addEntry(txt_first[0].getText().toString(),txt_first[1].getText().toString());
        }
    };//Runnable

    class MainTimerTask extends TimerTask {
        public void run() {
            mHandler.post(mUpdateTimeTask);
        }
    }

    public void RowDown() {
        for (int i = 0; i < 7; i++) {
            txt_third[i].setText(txt_second[i].getText());
            txt_second[i].setText(txt_first[i].getText());
        }
    }//RowDown

    public int RandomValue(int max, int min) {
        return (int) ((Math.random() * max) + min);
    }//RandomValue

    public void TextSetting() {
        double latitude = 0;
        double longitude = 0;

        int random_temp=0;
        String data_string="";  //for data save
        gps = new GpsInfo(GraphActivity.this);
        if (gps.isGetLocation()) {  // GPS On, NN.XX
            latitude = Math.round(gps.getLatitude() * 100.0) / 100.0;
            longitude = Math.round(gps.getLongitude() * 100.0) / 100.0;
        } else {
            gps.showSettingsAlert();     // GPS setting Alert
        }
        tv1.setText(latitude + "");
        tv2.setText(longitude + "");
        for (int i = 1; i < 7; i++) {   //Insert New Data
            random_temp = RandomValue(95,5);
            txt_first[i].setText(random_temp+ "");  //3st Low Color Change
            if (Integer.parseInt(txt_first[i].getText().toString()) > 75) {
                txt_first[i].setBackgroundColor(Color.RED);
            } else if (Integer.parseInt(txt_first[i].getText().toString()) > 50) {
                txt_first[i].setBackgroundColor(Color.YELLOW);
            } else if (Integer.parseInt(txt_first[i].getText().toString()) > 25) {
                txt_first[i].setBackgroundColor(Color.GREEN);
            } else {
                txt_first[i].setBackgroundColor(Color.CYAN);
            }
        }
        if (!(txt_second[0].getText().toString().equals(""))) {
            for (int i = 1; i < 7; i++) {   //2st Low Color Change
                if (Integer.parseInt(txt_second[i].getText().toString()) > 75) {
                    txt_second[i].setBackgroundColor(Color.RED);
                } else if (Integer.parseInt(txt_second[i].getText().toString()) > 50) {
                    txt_second[i].setBackgroundColor(Color.YELLOW);
                } else if (Integer.parseInt(txt_second[i].getText().toString()) > 25) {
                    txt_second[i].setBackgroundColor(Color.GREEN);
                } else {
                    txt_second[i].setBackgroundColor(Color.CYAN);
                }
            }
        }
        if(!(txt_third[0].getText().toString().equals(""))) {
            for (int i = 1; i < 7; i++) {   //3st Low Color Change
                if (Integer.parseInt(txt_third[i].getText().toString()) > 75) {
                    txt_third[i].setBackgroundColor(Color.RED);
                } else if (Integer.parseInt(txt_third[i].getText().toString()) > 50) {
                    txt_third[i].setBackgroundColor(Color.YELLOW);
                } else if (Integer.parseInt(txt_third[i].getText().toString()) > 25) {
                    txt_third[i].setBackgroundColor(Color.GREEN);
                } else {
                    txt_third[i].setBackgroundColor(Color.CYAN);
                }
            }
        }

    }

    /* GRAPH. Line Chart */
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
        leftAxis.setAxisMaxValue(100f);
        leftAxis.setAxisMinValue(0f);
        leftAxis.setStartAtZero(false);
        leftAxis.setDrawGridLines(true);
        YAxis rightAxis = mChart.getAxisRight();
        rightAxis.setEnabled(false);
    }

    private void addEntry(String str_time, String str_value) {
        LineData data = mChart.getData();
        if (data != null) {
            ILineDataSet set = data.getDataSetByIndex(0);
            // set.addEntry(...); // can be called as well
            if (set == null) {
                set = createSet();
                data.addDataSet(set);
            }
            // add a new x-value first
            data.addXValue(str_time);
            data.addEntry(new Entry(Integer.parseInt(str_value), set.getEntryCount()), 0);
            // let the chart know it's data has changed
            mChart.notifyDataSetChanged();
            // limit the number of visible entries
            mChart.setVisibleXRangeMaximum(120);
            // mChart.setVisibleYRange(30, AxisDependency.LEFT);
            // move to the latest entry
            mChart.moveViewToX(data.getXValCount() - 121);
            // this automatically refreshes the chart (calls invalidate())
            // mChart.moveViewTo(data.getXValCount()-7, 55f,
            // AxisDependency.LEFT);
        }
    }

    private LineDataSet createSet() {
        LineDataSet set = new LineDataSet(null, "Dynamic Data");
        set.setAxisDependency(YAxis.AxisDependency.LEFT);
        set.setColor(ColorTemplate.getHoloBlue());
        set.setCircleColor(Color.WHITE);
        set.setLineWidth(2f);
        set.setCircleRadius(4f);
        set.setFillAlpha(65);
        set.setFillColor(ColorTemplate.getHoloBlue());
        set.setHighLightColor(Color.rgb(244, 117, 117));
        set.setValueTextColor(Color.WHITE);
        set.setValueTextSize(9f);
        set.setDrawValues(false);
        return set;
    }
}
