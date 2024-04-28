package com.example.autempsdonnee

import android.app.Activity
import android.app.AlertDialog
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import androidx.activity.result.contract.ActivityResultContracts
import com.example.autempsdonnee.login.Login
import com.example.autempsdonnee.Managers.ApiKeyManager
import com.example.autempsdonnee.login.Register
import com.example.autempsdonnee.utils.Popup
import com.example.autempsdonnee.api.RequestApi
import org.json.JSONObject

class MainActivity : AppCompatActivity() {

    var popup = Popup()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        var btn_connect = findViewById<Button>(R.id.connectingMain)
        var btn_register = findViewById<Button>(R.id.registerMain)

        btn_connect.setOnClickListener(){
            val intent = Intent(this, Login::class.java)
            ResultLauncher.launch(intent)
        }

        btn_register.setOnClickListener(){
            val intent = Intent(this, Register::class.java)
            ResultLauncher.launch(intent)
        }


        // Récupérer l'apikey
        val apiKey = ApiKeyManager.getApiKey(this)

        if (apiKey == null) {
            // Launch the connection activity
            val intent = Intent(this, Login::class.java)
            ResultLauncher.launch(intent)
        } else {
            var requestApi = RequestApi()
            popup.makeToast(this, "Loading informations")
            RequestApi().Get(this, "/user") { responseData ->
                if (responseData !is JSONObject) {
                    return@Get
                }
                println(responseData)
            }
        }
    }

    // Launch another activity (login or register) and "wait" a response from it
    private val ResultLauncher = registerForActivityResult(
        ActivityResultContracts.StartActivityForResult()
    ) { result ->
        if (result.resultCode == Activity.RESULT_OK) {
            val data = result.data
            val resultOk = data?.getBooleanExtra("result", false) ?: false
            if (resultOk) {
                // Show things
                popup.makeToast(this, "YEY")
            } else {
                // Error occured, retenter
                popup.makeToast(this, "An error occured")
            }
        }
    }
}



/*

## EXEMPLE POUR GET
var requestApi = RequestApi()
popup.makeToast(this, "Loading informations")
RequestApi().Get(this, "/user") { responseData ->
    if (responseData !is JSONObject) {
        return@Get
    }
    println(responseData)
}




 */