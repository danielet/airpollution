package com.bommason.qiot.qiot;

import android.app.Activity;
import android.bluetooth.BluetoothAdapter;
import android.bluetooth.BluetoothDevice;
import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.ServiceConnection;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.os.Message;
import android.support.design.widget.NavigationView;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ToggleButton;
import android.widget.ViewFlipper;

import com.bommason.qiot.ble.AppController;
import com.bommason.qiot.ble.BluetoothLeService;
import com.bommason.qiot.ble.PolarBleService;
import com.github.mikephil.charting.charts.LineChart;
import com.github.mikephil.charting.components.Legend;
import com.github.mikephil.charting.components.XAxis;
import com.github.mikephil.charting.components.YAxis;
import com.github.mikephil.charting.data.LineData;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.CircleOptions;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.StringTokenizer;
import java.util.Timer;
import java.util.TimerTask;

public class MainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    private final String TAG = "MainActivity";

    DataArray d_array;  //data save
    DrawGraph d_graph;  //draw graph
    ArrayList<DataArray> data_to_array = new ArrayList<DataArray>();

    TextView[] txt_first, txt_second, txt_third;    //Low1, Low2, Low3
    TextView btn_map, btn_graph, btn_co, btn_so2, btn_no2, btn_o3, btn_pm25, btn_temp, user_id;  //click and select data
    ViewFlipper flipper;    //1.map  2.graph
    Timer mTimer;   //current time
    private GpsInfo gps;    //custom class for gps
    Button buttonSession;    //toggle button (start, stop)
    GoogleMap map;     // current map
    int index; // Air pollutant selected by user..
    int data_count; // The number of data transmitted by sensor
    double select_value;    //for Convert Data
    LatLng current_location; // Latitude, Longitude to GPS
    final static int NUM_SENSOR = 6;
    static int MAP_ZOOM_LAEVEL=16;   //map camera zoom level
    static int update_interval = 5000;    // sensing interval
    LineChart mChart;
    ComputingAQI aqi;

    // ★ Bluetooth
    private BluetoothAdapter bt_adapter = null;
    private static final int REQUEST_ENABLE_BT = 3;
    private static final int REQUEST_CONNECT_DEVICE_SECURE = 1;
    private BluetoothDevice bt_device;
    private BluetoothConnection bt_connection;
    private Handler bt_status;
    TextView txt_bluetooth_state, txt_device_name;
    Intent main_to_devicelist;
    boolean connection_enable=false;

    // ★ Polar Sensor
    int hr=0,prr=0,rr=0;
    AppController app = AppController.getInstance();
    PolarBleService mPolarBleService;
    private boolean bleServiceConnectionBounded=false;

    //login
    String mac_add;
    String login_id = null;


    private int session_id;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_main);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        user_id = (TextView) findViewById(R.id.txt_userID);
        /* Bluetooth */
        // ★ Get local Bluetooth adapter
        bt_adapter = BluetoothAdapter.getDefaultAdapter();

        // ★ If the adapter is null, then Bluetooth is not supported
        if (!bt_adapter.isEnabled()) {
            Intent bt_enable = new Intent(BluetoothAdapter.ACTION_REQUEST_ENABLE);
            startActivityForResult(bt_enable, REQUEST_ENABLE_BT);
        }
        // ★ DeviceListActivity
        main_to_devicelist = new Intent(MainActivity.this, DeviceListActivity.class);
        // ★ Bluetooth scan button
        txt_bluetooth_state = (TextView)findViewById(R.id.txt_connect_status);
        txt_device_name = (TextView)findViewById(R.id.txt_device);


        buttonSession = (Button) findViewById(R.id.tbtn_onoff);
        // ★ Display Text about Bluetooth Connect Status and Connceted device name
        bt_status = new Handler(){
            @Override
            public void handleMessage(Message message){
                int s = message.what;   //message type -1,0,1
                switch(s)
                {

                    case -3:

                        if(connection_enable == true) {
                            Log.d("MATTEO", "REOPEN CONNECTION!!!");
                            txt_bluetooth_state.setText("Reconnect");
                            ConnectDevice(true);
                            buttonSession.setClickable(false);
                        }
                        break;

                    case -2:
                        txt_bluetooth_state.setText("Bluetooth connection error");
                        connection_enable = false;
                        mTimer            = null;
                        buttonSession.setText("START");
                    break;
                    case -1:
                        if(login_id != null) {
                            txt_bluetooth_state.setText("Connected to only Web");
                        }else{
                            txt_bluetooth_state.setText("Not Connected");
                        }
                        connection_enable = false;
                        buttonSession.setText("START");
                        break;
                    case 0:
                        txt_bluetooth_state.setText("CONNECTING");
                        buttonSession.setClickable(false);
                        break;
                    case 1:
                        if(login_id != null) {
                            txt_bluetooth_state.setText("CONNECTED TO FULLY");
                            txt_device_name.setText(bt_device.getName().toString());
                        }else{
                            txt_bluetooth_state.setText("Connected to only Sensors");
                            txt_device_name.setText(bt_device.getName().toString());
                        }
                        buttonSession.setClickable(true);
                        connection_enable = true;
                        buttonSession.setText("STOP");
                        break;
                    case 2:
                        txt_bluetooth_state.setText("BL Connection Closed");
                        txt_device_name.setText(bt_device.getName().toString());
                        connection_enable = false;
                        buttonSession.setText("START");
                        break;
                }
            }
        };

        /* View flipper */
        flipper = (ViewFlipper) findViewById(R.id.viewF);
        btn_map = (TextView) findViewById(R.id.btn_map);
        btn_graph = (TextView) findViewById(R.id.btn_graph);
        btn_map.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {   //Click the map tap
                btn_map.setBackgroundColor(0xFFaaccff);
                btn_graph.setBackgroundColor(0xFFbbddff);
                if (v == btn_map)
                    flipper.showPrevious();
            }
        });
        btn_graph.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {   //Click the graph tap
                btn_map.setBackgroundColor(0xFFbbddff);
                btn_graph.setBackgroundColor(0xFFaaccff);
                if (v == btn_graph)
                    flipper.showNext();
            }
        });
        /* MAP */
        map = ((MapFragment)getFragmentManager().findFragmentById(R.id.frg_map)).getMap();


        try {
            MapsInitializer.initialize(MainActivity.this);
        } catch (Exception e) {
            Log.d("MATTEO" , "MAP ERROR");
            e.printStackTrace();
        }


        gps = new GpsInfo(MainActivity.this);
        if (gps.isGetLocation())
        {  // GPS On, NN.XX
            current_location = new LatLng(gps.getLatitude(), gps.getLongitude());
        }
        else
        {
            Log.d("MATTEO" , "MAP ERROR");
            gps.showSettingsAlert();     // GPS setting Alert
        }
          /* Add a marker */
//        map.moveCamera(CameraUpdateFactory.newLatLng(current_location)); // focus current location
        Log.d("MATTEO" , "LAT" + current_location.latitude);
        LatLng startingPoint = new LatLng(33.1422, -117.3308);   //initialize start point
//
        map.moveCamera(CameraUpdateFactory.newLatLngZoom(startingPoint, 5));   //initialize zoom level
//        map.setMyLocationEnabled(true);
        /* Table Text View Setting */
        txt_first = new TextView[]{(TextView) findViewById(R.id.txt_time1), (TextView) findViewById(R.id.txt_co1),
                (TextView) findViewById(R.id.txt_so21), (TextView) findViewById(R.id.txt_no21), (TextView) findViewById(R.id.txt_o31),
                (TextView) findViewById(R.id.txt_pm251), (TextView) findViewById(R.id.txt_temp1)};
        txt_second = new TextView[]{(TextView) findViewById(R.id.txt_time2), (TextView) findViewById(R.id.txt_co2),
                (TextView) findViewById(R.id.txt_so22), (TextView) findViewById(R.id.txt_no22), (TextView) findViewById(R.id.txt_o32),
                (TextView) findViewById(R.id.txt_pm252), (TextView) findViewById(R.id.txt_temp2)};
        txt_third = new TextView[]{(TextView) findViewById(R.id.txt_time3), (TextView) findViewById(R.id.txt_co3),
                (TextView) findViewById(R.id.txt_so23), (TextView) findViewById(R.id.txt_no23), (TextView) findViewById(R.id.txt_o33),
                (TextView) findViewById(R.id.txt_pm253), (TextView) findViewById(R.id.txt_temp3)};

        /* Connecting Graph Value */
        btn_co = (TextView) findViewById(R.id.title_co);
        btn_so2 = (TextView) findViewById(R.id.title_so2);
        btn_no2 = (TextView) findViewById(R.id.title_no2);
        btn_o3 = (TextView) findViewById(R.id.title_o3);
        btn_pm25 = (TextView) findViewById(R.id.title_pm25);
        btn_temp = (TextView) findViewById(R.id.title_temp);
        mChart = (LineChart)findViewById(R.id.chart_graph);
        index = 0; // default
        data_count =0;
        aqi = new ComputingAQI();


        /* if Click Title TextView, This method can run. */
        SetDrawSelectedValueInGraph();
        /* Draw Graph axis */
        SettingChart();
        /* DrawerLayout */
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(
                this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        drawer.setDrawerListener(toggle);
        toggle.syncState();
        /* Navigation View */
        NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
    }//onCreate


    String addressBT ;
    public void onActivityResult(int requestCode, int resultCode, Intent data){

        if (resultCode == Activity.RESULT_OK) {
            JSONObject session = new JSONObject();
            login_id = ((GlobalVariable) this.getApplication()).getData();
            addressBT = data.getExtras().getString(DeviceListActivity.EXTRA_DEVICE_ADDRESS);
            Date csv_date = new Date();
            SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss"); //  "hh:mm:ss dd.MM.yyyy"
            String current_date = formatter.format(csv_date);
            mac_add = bt_adapter.getAddress().toString();

            if (login_id != null) {
                try
                {
                /* If user log in, change textview */

                    session.put("uid", login_id);
                    session.put("mac", mac_add);
                    session.put("time", current_date);
                    session.put("smac", addressBT);
                    String str_session = session.toString();
                    JsonTransfer aa = new JsonTransfer();
                    String ss = aa.execute(getString(R.string.receive_start_url), str_session).get();

                    JSONObject jObject = new JSONObject(ss);
                    session_id = jObject.getInt("session_id");
                    Log.d("MATTEO" , "RETURN " + session_id);
                    if (session_id != -1)
                    {
                        ConnectDevice(true);
//                        connection_enable = true;
                    }
                }
                catch (Exception e)
                {
                    Log.d("MATTEO",  e.toString());
                    connection_enable = false;

                }
            } else {
                Toast.makeText(MainActivity.this, "Please Login", Toast.LENGTH_SHORT).show();
                connection_enable = false;

            }
        }
    }
    private void ConnectDevice(boolean secure)
    {
        // Get the device MAC address
//        String address = data.getExtras().getString(DeviceListActivity.EXTRA_DEVICE_ADDRESS);

        // Get the BluetoothDevice object
        bt_device = bt_adapter.getRemoteDevice(addressBT);
        // Attempt to connect to the device
        bt_connection = new BluetoothConnection(bt_device, bt_adapter, bt_status, bt_receivemsg);
        bt_connection.start();

    }



    public void opencloseBTConnection(View v){
        Log.d("MATTEO", "CONNECTION BUTTON");


        if (mTimer == null) {
            //Timer make, start
            startActivityForResult(main_to_devicelist, REQUEST_CONNECT_DEVICE_SECURE);
            mTimer = new Timer();
            if (connection_enable == true) {
                MainTimerTask timerTask = new MainTimerTask();
                mTimer.schedule(timerTask, 500, update_interval);
            }
        }
        else
        {
            if (connection_enable == true) {
                mTimer.cancel();
                mTimer = null;
                connection_enable = false;
                bt_connection.StopConnection();
                closeSession();
                //CHANGE LABEL BUTTON
            }
        }

    }


    private void closeSession(){


        JSONObject json_csv = new JSONObject();
        Date csv_date = new Date();
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss"); //  "hh:mm:ss dd.MM.yyyy"
        SimpleDateFormat formatter2 = new SimpleDateFormat("yyyyMMdd_hhmmss");
        String current_date = formatter.format(csv_date);
        String current_date_file = formatter2.format(csv_date);
        try {
            //json  uid,mac,filename,time
            json_csv.put("uid", login_id);
            json_csv.put("mac", mac_add);
            json_csv.put("time", current_date);
            json_csv.put("session_id", session_id);
            json_csv.put("filename", login_id + "_" + current_date_file + ".csv");
            String json_data = json_csv.toString();
            // Send session information (uid, mac, current date, file name) to WEB using JSON
            new JsonTransfer().execute(getString(R.string.receive_end_url), json_data);

        } catch (Exception e) {
            Log.d("MATTEO", "TOOGLE BOTTOM");
        }

        // Send CSV file to WEB using JSON
        CSVTransferThread csv_transfer = new CSVTransferThread(data_to_array);
        csv_transfer.execute(getString(R.string.csv_transfer_url), login_id, login_id + "_" + current_date_file + ".csv");


    }

   private Handler bt_receivemsg = new Handler(){ // ★ 메세지
        @Override
        public void handleMessage(Message message){
            Bundle data = message.getData();
            RowDown();
            TextSetting(data);
            data_count++;
            d_array = new DataArray(txt_first[0].getText().toString(),
                    txt_first[1].getText().toString(), txt_first[2].getText().toString(), txt_first[3].getText().toString(),
                    txt_first[4].getText().toString(), txt_first[5].getText().toString(), txt_first[6].getText().toString(),
                    (current_location.latitude+""), (current_location.longitude+""), (hr+""),(prr+""));
            d_array.CsvDataMaker();
            data_to_array.add(d_array); //
            /* GRAPH */
            d_graph = new DrawGraph(mChart,data_to_array);
            d_graph.DrawGraphLineCurrent(index);
            JsonDataInput();
        }
    };

    private Runnable mUpdateTimeTask = new Runnable() {
        public void run() {
        }
    };//Runnable

    /*  -JsonDataInput
        Convert the real-time data to Jason format and transfer it to web server
        Json - user_id, mac, time, lat, lng, co, so2, no2, o3
        JsonTransfer().execute(URL,Data);
    */
    public void JsonDataInput(){
        try {
            JsonTransfer data_transfer = new JsonTransfer();
            // 1.CO  2.SO2  3.NO2  4.O3  5.PM2.5  6.PM10
            JSONObject json_dataTransfer = new JSONObject();
            json_dataTransfer.put("user_id", login_id);
            json_dataTransfer.put("mac", mac_add); // ★ MAC address 주소 받는 거 넣어주기!!!!!! ★
            json_dataTransfer.put("time", txt_first[0].getText().toString());
            json_dataTransfer.put("lat", current_location.latitude);
            json_dataTransfer.put("lng", current_location.longitude);
            json_dataTransfer.put("co", txt_first[1].getText().toString());
            json_dataTransfer.put("so2", txt_first[2].getText().toString());
            json_dataTransfer.put("no2", txt_first[3].getText().toString());
            json_dataTransfer.put("o3", txt_first[4].getText().toString());
            json_dataTransfer.put("pm2.5", txt_first[5].getText().toString());
            json_dataTransfer.put("temp", txt_first[6].getText().toString());
            json_dataTransfer.put("hb", hr);
            //MATTEO TO ADD SESSION_ID
            json_dataTransfer.put("session_id", session_id);
            String json_string = json_dataTransfer.toString();
            new JsonTransfer().execute(getString(R.string.realtime_url),json_string);


        }catch (Exception e){

            Log.d("MATTEO" , "ERROR JSON DATA INPUT");

        }
    }

    class MainTimerTask extends TimerTask {
        public void run() {

            bt_receivemsg.post(mUpdateTimeTask);

        } //
    }//TimerTask

    //RowDown - Shift previous data
    public void RowDown() {
        for (int i = 0; i <= NUM_SENSOR; i++) {
            txt_third[i].setText(txt_second[i].getText());
            txt_second[i].setText(txt_first[i].getText());
        }
    }//RowDown
    public void TextSetting(Bundle data) {
        map.clear(); // clear map because of showing just current sensor location

        gps = new GpsInfo(MainActivity.this);
        if (gps.isGetLocation()) {  // GPS On, NN.XX
            current_location = new LatLng(gps.getLatitude(), gps.getLongitude());
        } else {
            gps.showSettingsAlert();     // GPS setting Alert
        }
          /* Add a marker */
        map.moveCamera(CameraUpdateFactory.newLatLng(current_location)); // focus current location


        Date input_date =  new Date();
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss"); //  "hh:mm:ss dd.MM.yyyy"
        String current_date = formatter.format(input_date);
        txt_first[0].setText(current_date);
        txt_first[1].setText(data.getString("CO"));
        txt_first[2].setText(data.getString("SO2"));
        txt_first[3].setText(data.getString("NO2"));
        txt_first[4].setText(data.getString("O3"));
        txt_first[5].setText(data.getString("PM2.5"));
        txt_first[6].setText(data.getString("Temp"));

        if(data_count > 0) {
            ColorChanger();
            addMarker();
        }
    }//TextSetting

    /*  AddMarker()
        Initializing map. And add new marker.
        neighboring circle indicating the AQI
    */
    public void addMarker() {
        map.clear();       //map initialize
        map.addMarker((new MarkerOptions().icon(BitmapDescriptorFactory.fromResource(R.drawable.marker_new))).position((current_location)).title("Current!"));
        //index == 0 -> Nothing selected,
        if(index!=0)    select_value = aqi.Computing(index,Double.parseDouble(txt_first[index].getText().toString()));
        if ( select_value> 301)
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(126, 00, 23)).fillColor(Color.argb(50, 126, 00, 23)));
        else if (select_value > 201)
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(99, 04, 76)).fillColor(Color.argb(50, 99, 04, 76)));
        else if (select_value > 151)
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(255, 00, 00)).fillColor(Color.argb(50, 255, 00, 00)));
        else if (select_value > 101)
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(255, 126, 00)).fillColor(Color.argb(50, 255, 126, 00)));
        else if (select_value > 51)
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(255, 255, 0)).fillColor(Color.argb(50, 255, 255, 0)));
        else
            map.addCircle(new CircleOptions().center(current_location).radius(100).strokeColor(Color.rgb(00, 228, 00)).fillColor(Color.argb(50, 00, 228, 00)));
        select_value = 0; // average init

    }

    /*
       ColorChanger()
       Initializing TextView Background Color on previously index
       if(data_count > 0 && index!=0)  compute the data to aqi value and textView[index] color change
   */



    /*  onBackPressed()
        pushed the back button
     */
    @Override
    public void onBackPressed() {
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
/*
            FragmentManager fragmentManager = getFragmentManager();
            GraphFragment grp_fragment = new GraphFragment();
            Bundle bundle = new Bundle();
            grp_fragment.setArguments(bundle);

            FragmentTransaction fragmentTransaction = fragmentManager.beginTransaction();
            fragmentTransaction.replace(R.id.container, grp_fragment);
            fragmentTransaction.commit();
*/
//            Toast.makeText(MainActivity.this, "SETTING 관련 메뉴?", Toast.LENGTH_SHORT).show();
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    /*  onNavigationItemSelected    navigation menu we want*/
    @SuppressWarnings("StatementWithEmptyBody")
    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id = item.getItemId();

        if (id == R.id.login) {
            Intent it_login = new Intent(MainActivity.this,LoginActivity.class);
            startActivity(it_login);
        } else if (id == R.id.health) {
            if(login_id!=null) {
                Intent it_health = new Intent(MainActivity.this, HealthActivity.class);
                startActivity(it_health);
            }else{
                Toast.makeText(MainActivity.this, "Please Login", Toast.LENGTH_SHORT).show();
            }
        } else if (id == R.id.graph) {
            if(login_id!=null) {
                Intent it_graph = new Intent(MainActivity.this,GraphActivity.class);
                startActivity(it_graph);
            }else{
                Toast.makeText(MainActivity.this, "Please Login", Toast.LENGTH_SHORT).show();
            }
        } else if (id == R.id.nav_manage) {
        } else if (id == R.id.nav_share) {
        } else if (id == R.id.nav_send) {
        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        drawer.closeDrawer(GravityCompat.START);
        return true;
    }

    /* ★ Reset selected textView tab color */
    private void ResetSelectedTabColor(){
        btn_co.setBackgroundColor(0xFFbbddff);
        btn_so2.setBackgroundColor(0xFFbbddff);
        btn_no2.setBackgroundColor(0xFFbbddff);
        btn_o3.setBackgroundColor(0xFFbbddff);
        btn_pm25.setBackgroundColor(0xFFbbddff);
        btn_temp.setBackgroundColor(0xFFbbddff);
    }
    public void SetDrawSelectedValueInGraph(){
        /* Draw Graph */
        btn_co.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 1;
                if(data_count>0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_co.setBackgroundColor(0xFFaaccff);
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw CO graph
        btn_so2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 2;
                if (data_count > 0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_so2.setBackgroundColor(0xFFaaccff);
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw SO2 graph
        btn_no2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 3;
                if (data_count > 0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_no2.setBackgroundColor(0xFFaaccff);
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw NO2 graph
        btn_o3.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 4;
                if (data_count > 0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_o3.setBackgroundColor(0xFFaaccff);
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw O3 graph
        btn_pm25.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 5;
                if (data_count > 0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_pm25.setBackgroundColor(0xFFaaccff);
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw PM2.5 graph
        btn_temp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                index = 6;
                if (data_count > 0) {
                    ColorChanger();
                    addMarker();
                    ResetSelectedTabColor();
                    btn_temp.setBackgroundColor(0xFFaaccff);//
                    d_graph.DrawGraphLinePrevious(index);
                }
            }
        }); // Draw TEMP graph
    }





    ////////////////////////////////////////////////////////////////////////////////////////
    //-------------------Polar Sensor-----------------------------------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////
    @Override
    protected void onResume() { // lifeCycle! 
        super.onResume();
        if(app.polarBleDeviceServiceConnected && app.polarBleDeviceAddress == null){
            deactivatePolar();
        }else if(app.polarBleDeviceAddress != null && !app.polarBleDeviceServiceConnected){
            activatePolar();
        }
    }

    protected void activatePolar() {
        Log.w(this.getClass().getName(), "activatePolar()");
        if (!app.polarBleDeviceServiceConnected && app.polarBleDeviceAddress!=null && app.polarBleDeviceAddress.length()>1){
            Intent gattactivateClickerServiceIntent = new Intent(this, PolarBleService.class);
            bindService(gattactivateClickerServiceIntent, mPolarBleServiceConnection, BIND_AUTO_CREATE);
        }
        registerReceiver(mPolarBleUpdateReceiver, makePolarGattUpdateIntentFilter());
    }

    protected void deactivatePolar() {
        Log.w(this.getClass().getName(), "deactivatePolar()");
        if(app.polarBleDeviceServiceConnected){
            if(mPolarBleService!=null)
                unbindService(mPolarBleServiceConnection);
        }
        app.bleDeviceServiceConnected=false;

        if(bleServiceConnectionBounded)
            unregisterReceiver(mPolarBleUpdateReceiver);
        bleServiceConnectionBounded=false;
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

                Log.w(TAG, "####Received HR: " +hr+" RR: "+rr+" pRR: "+prr);
            }
        }
    };

    private final ServiceConnection mPolarBleServiceConnection = new ServiceConnection() {
        @Override
        public void onServiceConnected(ComponentName componentName, IBinder service) {
            mPolarBleService = ((PolarBleService.LocalBinder) service).getService();
            if (!mPolarBleService.initialize()) {
                Log.e(TAG, "Unable to initialize Bluetooth");
                finish();
            }
            // Automatically connects to the device upon successful start-up initialization.
            mPolarBleService.connect(app.polarBleDeviceAddress);
            app.polarBleDeviceServiceConnected = true;
        }
        @Override
        public void onServiceDisconnected(ComponentName componentName) {
            mPolarBleService = null;
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




    public void ColorChanger(){
        //initialize BackgroundColor
        for (int i = 1; i <=NUM_SENSOR; i++) {
            txt_first[i].setBackgroundColor(Color.WHITE);
            txt_second[i].setBackgroundColor(Color.WHITE);
            txt_third[i].setBackgroundColor(Color.WHITE);
        }
        double aqi_value;
        if(data_count>0 && index !=0) {
            aqi_value = aqi.Computing(index, Double.parseDouble(txt_first[index].getText().toString()));
            if (aqi_value > 301) {
                txt_first[index].setBackgroundColor(Color.rgb(126, 00, 23));
            } else if (aqi_value> 201) {
                txt_first[index].setBackgroundColor(Color.rgb(99, 04, 76));
            } else if (aqi_value > 151) {
                txt_first[index].setBackgroundColor(Color.rgb(255, 00, 00));
            } else if (aqi_value > 101) {
                txt_first[index].setBackgroundColor(Color.rgb(255, 126, 00));
            } else if (aqi_value > 51) {
                txt_first[index].setBackgroundColor(Color.rgb(255, 255, 00));
            } else {
                txt_first[index].setBackgroundColor(Color.rgb(00, 228, 00));
            }
        }
        if(data_count>1 && index !=0) {
            aqi_value = aqi.Computing(index, Double.parseDouble(txt_second[index].getText().toString()));
            if (aqi_value > 301) {
                txt_second[index].setBackgroundColor(Color.rgb(126, 00, 23));
            } else if (aqi_value > 201) {
                txt_second[index].setBackgroundColor(Color.rgb(99, 04, 76));
            } else if (aqi_value > 151) {
                txt_second[index].setBackgroundColor(Color.rgb(255, 00, 00));
            } else if (aqi_value > 101) {
                txt_second[index].setBackgroundColor(Color.rgb(255, 126, 00));
            } else if (aqi_value > 51) {
                txt_second[index].setBackgroundColor(Color.rgb(255, 255, 00));
            } else {
                txt_second[index].setBackgroundColor(Color.rgb(00, 228, 00));
            }
        }
        if(data_count>2 && index !=0) {
            aqi_value = aqi.Computing(index, Double.parseDouble(txt_third[index].getText().toString()));
            if (aqi_value > 301) {
                txt_third[index].setBackgroundColor(Color.rgb(126, 00, 23));
            } else if (aqi_value > 201) {
                txt_third[index].setBackgroundColor(Color.rgb(99, 04, 76));
            } else if (aqi_value > 151) {
                txt_third[index].setBackgroundColor(Color.rgb(255, 00, 00));
            } else if (aqi_value > 101) {
                txt_third[index].setBackgroundColor(Color.rgb(255, 126, 00));
            } else if (aqi_value > 51) {
                txt_third[index].setBackgroundColor(Color.rgb(255, 255, 00));
            } else {
                txt_third[index].setBackgroundColor(Color.rgb(00, 228, 00));
            }
        }
    }//ColorChanger

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
        leftAxis.setAxisMaxValue(500f);
        leftAxis.setAxisMinValue(0f);
        leftAxis.setStartAtZero(false);
        leftAxis.setDrawGridLines(true);
        YAxis rightAxis = mChart.getAxisRight();
        rightAxis.setEnabled(false);
    }//SettingChart
}

       /* START-STOP toggle button */
//        tbtn_start_stop = (ToggleButton)findViewById(R.id.tbtn_onoff);

//        tbtn_start_stop.setOnClickListener(new View.OnClickListener() {
//
//            @Override
//            public void onClick(View v) {
//
//                if (mTimer == null) {
//                    //Timer make, start
//                    startActivityForResult(main_to_devicelist, REQUEST_CONNECT_DEVICE_SECURE);
//                    mTimer = new Timer();
//
//                    if (connection_enable == true) {
//                        MainTimerTask timerTask = new MainTimerTask();
//                        mTimer.schedule(timerTask, 500, update_interval);
//
//                    }
//
//                }
//                else
//                {  //Timer Stop, delete
//                    if (connection_enable == true)
//                    {
////                        tbtn_start_stop.setChecked(true);
//                        mTimer.cancel();
//                        mTimer = null;
//                        connection_enable = false;
//                        bt_connection.StopConnection();
//
//                        JSONObject json_csv = new JSONObject();
//                        Date csv_date = new Date();
//                        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss"); //  "hh:mm:ss dd.MM.yyyy"
//                        SimpleDateFormat formatter2 = new SimpleDateFormat("yyyyMMdd_hhmmss");
//                        String current_date = formatter.format(csv_date);
//                        String current_date_file = formatter2.format(csv_date);
//                        try
//                        {
//                            //json  uid,mac,filename,time
//                            json_csv.put("uid", login_id);
//                            json_csv.put("mac", mac_add);
//                            json_csv.put("time", current_date);
//                            json_csv.put("session_id", session_id);
//                            json_csv.put("filename", login_id + "_" + current_date_file + ".csv");
//                            String json_data = json_csv.toString();
//                            // Send session information (uid, mac, current date, file name) to WEB using JSON
//                            new JsonTransfer().execute(getString(R.string.receive_end_url), json_data);
//
//                        }
//                        catch (Exception e)
//                        {
//                            Log.d("MATTEO", "TOOGLE BOTTOM");
//                        }
//                        // Send CSV file to WEB using JSON
//                        CSVTransferThread csv_transfer = new CSVTransferThread(data_to_array);
//                        csv_transfer.execute(getString(R.string.csv_transfer_url), login_id, login_id + "_" + current_date_file + ".csv");
//                    }
////                    else
////                    {
////                        tbtn_start_stop.setChecked(false);
////                    }
//                }
//            }
//        });