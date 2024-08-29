package com.example.nomorewaste.login

import android.app.Activity
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import com.example.nomorewaste.Managers.ApiKeyManager
import com.example.nomorewaste.R
import com.example.nomorewaste.api.ApiLogin
import com.example.nomorewaste.utils.Popup


/**
 * Show the login page, then call the apiLogin class
 */
class Login : AppCompatActivity() {

    var popup = Popup()

    override fun onCreate(savedInstanceState: Bundle?) {

        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        var btn_connect = findViewById<Button>(R.id.connecting)

        var mail = findViewById<EditText>(R.id.login)
        var password = findViewById<EditText>(R.id.password)


        btn_connect.setOnClickListener(){
            var mailText = mail.text.toString()
            var passwordText = password.text.toString()

            if(mailText == "" || passwordText == ""){
                popup.makeToast(this, "Vous devez remplir les deux champs")
                return@setOnClickListener
            }
            else{
                var apiLogin = ApiLogin()
                var apiiiii = ApiKeyManager.getApiKey(this)
                if(apiLogin.login(this, mailText, passwordText)){
                    popup.makeToast(this, apiiiii.toString())
                }

            }
            // Définir le résultat avec un booléen
            val resultOk = true // ou false selon le cas
            val intent = Intent()
            intent.putExtra("result", resultOk)
            setResult(Activity.RESULT_OK, intent)
            finish()
        }

    }
}
