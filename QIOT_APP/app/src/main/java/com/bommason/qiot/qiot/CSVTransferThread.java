package com.bommason.qiot.qiot;

import android.os.AsyncTask;
import android.os.Environment;
import android.util.Log;

import com.opencsv.CSVWriter;

import org.json.JSONObject;

import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

/**
 * Created by p on 2016-02-17.
 */
public class CSVTransferThread extends AsyncTask<String, Void, String> {
    ArrayList<DataArray> dataArrays;
    Date csv_date =  new Date();
    int serverResponseCode = 0;
    String upLoadServerUri = null;
    SimpleDateFormat formatter = new SimpleDateFormat("yyyyMMdd_hhmmss"); //  "hh:mm:ss dd.MM.yyyy"
    String current_date = formatter.format(csv_date);


    public CSVTransferThread(ArrayList<DataArray> arrays){
        this.dataArrays = arrays;
    }
    @Override
    protected String doInBackground(String... params) {
        String file_name = params[2];
        String file_path = Environment.getExternalStorageDirectory().getAbsolutePath();
        String file_string = file_path+"/"+file_name;
        upLoadServerUri = params[0];
        try {
            CSVWriter writer = new CSVWriter(new FileWriter(file_string));
            List<String[]> data = new ArrayList<String[]>();
            for(int i = 0; i< dataArrays.size(); i++) {
                data.add(new String[] {dataArrays.get(i).time, dataArrays.get(i).co, dataArrays.get(i).so2, dataArrays.get(i).no2,
                        dataArrays.get(i).o3, dataArrays.get(i).pm25, dataArrays.get(i).temp, dataArrays.get(i).lon, dataArrays.get(i).lat,
                        dataArrays.get(i).h_rate, dataArrays.get(i).h_rr});
            }
            writer.writeAll(data);
            writer.close();

        }catch (Exception e){}

        HttpURLConnection conn = null;
        DataOutputStream dos = null;
        String lineEnd = "\r\n";
        String twoHyphens = "--";
        String boundary = "*****";
        int bytesRead, bytesAvailable, bufferSize;
        byte[] buffer;
        int maxBufferSize = 1 * 1024 * 1024;
        File CSV_file = new File(file_string);

        if (!CSV_file.isFile()) {
            return "0";
        } else {

            JSONObject json_csv= new JSONObject();
            try {
                // open a URL connection to the Servlet
                FileInputStream fileInputStream = new FileInputStream(CSV_file);
                URL url = new URL(upLoadServerUri);
                // Open a HTTP  connection to  the URL
                conn = (HttpURLConnection) url.openConnection();
                conn.setDoInput(true); // Allow Inputs
                conn.setDoOutput(true); // Allow Outputs
                conn.setUseCaches(false); // Don't use a Cached Copy
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Connection", "Keep-Alive");
                conn.setRequestProperty("ENCTYPE", "multipart/form-data");
                conn.setRequestProperty("Content-Type", "multipart/form-data;boundary=" + boundary);
                conn.setRequestProperty("uploaded_file", file_string);

                //Write
                dos = new DataOutputStream(conn.getOutputStream());
                dos.writeBytes(twoHyphens + boundary + lineEnd);
                dos.writeBytes("Content-Disposition: form-data; name=\"uploaded_file\";filename=\"" + file_string + "\"" + lineEnd);
                dos.writeBytes(lineEnd);
                // create a buffer of  maximum size
                bytesAvailable = fileInputStream.available();
                bufferSize = Math.min(bytesAvailable, maxBufferSize);
                buffer = new byte[bufferSize];
                // read file and write it into form...
                bytesRead = fileInputStream.read(buffer, 0, bufferSize);
                while (bytesRead > 0) {
                    dos.write(buffer, 0, bufferSize);
                    bytesAvailable = fileInputStream.available();
                    bufferSize = Math.min(bytesAvailable, maxBufferSize);
                    bytesRead = fileInputStream.read(buffer, 0, bufferSize);
                }
                // send multipart form data necesssary after file data...
                dos.writeBytes(lineEnd);
                dos.writeBytes(twoHyphens + boundary + twoHyphens + lineEnd);
                // Responses from the server (code and message)
                serverResponseCode = conn.getResponseCode();
                String serverResponseMessage = conn.getResponseMessage();
                Log.i("uploadFile", "HTTP Response is : " + serverResponseMessage + ": " + serverResponseCode);
                //close the streams //
                fileInputStream.close();
                dos.flush();
                dos.close();
                if (serverResponseCode == 200) {
                    return "200";
//                        runOnUiThread(new Runnable() {
//                            public void run() {
//                            String msg = "File Upload Completed.\n\n See uploaded file here : \n\n" + uploadFileName;
//                            messageText.setText(msg);
//                            makeText(TransferTestActivity.this, "File Upload Complete.",
//                                    LENGTH_SHORT).show();
//                            }
//                        });
                }
            } catch (MalformedURLException ex) {
                ex.printStackTrace();
                Log.e("Upload file to server", "error: " + ex.getMessage(), ex);
            } catch (Exception e) {
                e.printStackTrace();

//                    runOnUiThread(new Runnable() {
//                        public void run() {
//                        messageText.setText("Got Exception : see logcat ");
//                        makeText(TransferTestActivity.this, "Got Exception : see logcat ",
//                                LENGTH_SHORT).show();
//                        }
//                    });
                Log.e("Up", "Exception : " + e.getMessage(), e);
            }
           // return serverResponseCode;
        } // End else block
        return null;
    }
}