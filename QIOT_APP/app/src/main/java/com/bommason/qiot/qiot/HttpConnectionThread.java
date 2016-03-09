package com.bommason.qiot.qiot;

import android.bluetooth.BluetoothAdapter;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Build;
import android.util.Log;
import android.widget.Toast;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

/**
 * Created by p on 2016-02-04.
 */

/*  HttpConnectionThread extends AsyncTask
    App transfer some data to Web
    use Post

*/
public class HttpConnectionThread extends AsyncTask<String, Void, String> { // asyncTask 꼭 들어가니까 오버라이딩 해야되는 게 doInBa... 쓰레드 돌아가는
    Context connect_context;
    String pass_id;
    String id;
    String echo_message;
    BluetoothAdapter adapter = null;
    public HttpConnectionThread(Context context) {  //this is Constructor
        connect_context = context;
    }
    String rep;
    @Override
    protected String doInBackground(String... str) {
        // URL 연결이 구현될 부분
        URL urls;
        String response = null;
        try {
            urls = new URL(str[0]);
            HttpURLConnection conn = (HttpURLConnection) urls.openConnection();
            conn.setReadTimeout(10000 /* milliseconds */);
            conn.setConnectTimeout(15000 /* milliseconds */);
            conn.setRequestMethod("POST");
            conn.setDoInput(true);
            conn.setDoOutput(true);
            adapter = BluetoothAdapter.getDefaultAdapter();
            String local_mac = adapter.getAddress().toString();
            pass_id = (str[1]+","+str[2]+","+local_mac+","+ Build.DEVICE +",+1,1,1"); // ID, PW, Device Info, Device Brand
            id = str[1];
            try (OutputStream out = conn.getOutputStream()) {
                out.write(("data=" + pass_id).getBytes());
            }catch (IOException e ){
            }
            try{
                InputStream in = conn.getInputStream();
                ByteArrayOutputStream out = new ByteArrayOutputStream();
                byte[] buf = new byte[1024 * 8];
                int length = 0;
                while ((length = in.read(buf)) != -1) {
                    out.write(buf, 0, length);
                }
                echo_message =  new String(out.toByteArray(), "UTF-8");
            }catch (Exception e){}
            response = conn.getResponseMessage();
            Log.d("RESPONSE", "The response is: " + echo_message);
        }
        catch (IOException e) {

        }

        return echo_message;
    }
    @Override
    protected void onPostExecute(String result) { // 쓰레드 끝나고 알아서 호출되는 애.
        // UI 업데이트가 구현될 부분
        if(echo_message.contains("T"))
        {
            Toast.makeText(connect_context,"Welcome!!!", Toast.LENGTH_SHORT).show();
//            Intent it_backActivity = new Intent(connect_context, MainActivity.class);
//            it_backActivity.putExtra("ok", id);
        }
        else {
            Toast.makeText(connect_context,"ID or Pw is wrong!!!", Toast.LENGTH_SHORT).show();
        }
        // 얘가 지금 어느 Activityㅔ서 생성됐을 거 아니야
        // 생성 됐으면 그 activity에서 띄워야지
        // 그것 때문에 얘에다가 context를 보내줘야되
        // 그걸 보내주면 아 어느 activity가 보냈구나 그걸 알 수 있어
        // 그걸 받아서, 거기다가 echo 메세지를 받아서 띠운다.
        // 내가 만약 어떤 activiry 를 만들어서 뭔가를 사용하려고 하는데 Class에 기능이 다 있는데... 써야되잖아!
        // 그냥 못쓰는게 있어 . 어떤 화면에 뭔가를 띄우는 거... 그 화면이 뭔지를 알아야 될 거 아니야. 그 때 사용하ㅡㄴ게 context
    }
}