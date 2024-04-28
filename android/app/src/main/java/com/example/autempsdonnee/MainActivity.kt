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
            //Do others things

            // Tenter se de connecter à internet
            // Si oui :
            //  Prendre les informations d'internet et les afficher
            // Si non :
            //  Prendre les informations d'un truc (fichier, BDD locale) et les afficher

            //printInformations()
        }
    }

    // Launch another activity and "wait" a response from it
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