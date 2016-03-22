package com.bommason.qiot.qiot;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;

/*  LoginActivity
edt_id: input id,  edt_pw: input password
btn: Sign in and Sign up
*/
public class LoginActivity extends AppCompatActivity {
    /*
    ImageSwitcher imageSwitcher;
    int img_resource[] = {R.drawable.img1,R.drawable.img2,R.drawable.img3};
    Animation slide_in_left, slide_out_right;
    int index;
    */
    EditText edt_id, edt_pw;
    Button btn_in, btn_out;
    String address;
    //137.110.84.1/bootstrap/login.php      ucsd
    //137.110.83.62/bootstrap/login.php     hayatt

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_login);
        address = getString(R.string.logIn_url);
        /*// slide
        slide_in_left = AnimationUtils.loadAnimation(this, android.R.anim.slide_in_left);
        slide_out_right = AnimationUtils.loadAnimation(this, android.R.anim.slide_out_right);
        imageSwitcher.setInAnimation(slide_in_left);
        imageSwitcher.setOutAnimation(slide_out_right);
        index=0;
        */
        btn_in = (Button) findViewById(R.id.btn_in);
        btn_out = (Button) findViewById(R.id.btn_up);
        edt_id = (EditText) findViewById(R.id.txt_id);
        edt_pw = (EditText) findViewById(R.id.txt_pw);

        //Button Click Event
        btn_in.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {   //Sign In. Transfer Data to Web
                try {

                    String check = new HttpConnectionThread(LoginActivity.this).execute(address, edt_id.getText().toString(), edt_pw.getText().toString()).get();

                    if (check.contains("T")) {
                        return_id();
                        finish();
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        });
        btn_out.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {   //Sign up.

//                Intent it_backActivity = new Intent(LoginActivity.this, MainActivity.class);
//                it_backActivity.putExtra("ok", "on");
//                startActivity(it_backActivity);
                Intent it_createID = new Intent(LoginActivity.this, CreateIDActivity.class);
                startActivity(it_createID);
            }
        });
    }

    public void return_id(){
        ((GlobalVariable)this.getApplication()).setData(edt_id.getText().toString());
    }

}
