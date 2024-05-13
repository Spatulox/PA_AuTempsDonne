package com.example.autempsdonnee

import android.app.Activity
import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.Button
import androidx.activity.result.contract.ActivityResultContracts
import com.example.autempsdonnee.login.Login
import com.example.autempsdonnee.Managers.ApiKeyManager
import com.example.autempsdonnee.Managers.LocalUserManager
import com.example.autempsdonnee.login.Register
import com.example.autempsdonnee.utils.Popup

class MainActivity : AppCompatActivity() {

    var popup = Popup()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // If something went went wrong, there is still a posibiliti to connect
        var btn_connect = findViewById<Button>(R.id.connectingMain)
        //var btn_register = findViewById<Button>(R.id.registerMain)
        var btn_planning = findViewById<Button>(R.id.planning)
        var btn_deconnecter = findViewById<Button>(R.id.deconnecter)
        var btn_apikey = findViewById<Button>(R.id.apikey)

        btn_connect.setOnClickListener(){
            val intent = Intent(this, Login::class.java)
            ResultLauncher.launch(intent)
        }

        /*btn_register.setOnClickListener(){
            val intent = Intent(this, Register::class.java)
            ResultLauncher.launch(intent)
        }*/

        btn_deconnecter.setOnClickListener (){
            ApiKeyManager.clearApiKey(this)
            finish()
        }

        btn_planning.setOnClickListener(){
            val intent = Intent(this, Planning::class.java)
            ResultLauncher.launch(intent)
        }

        btn_apikey.setOnClickListener(){
            ApiKeyManager.seeApikey(this)
        }


        // Récupérer l'apikey pour afficher des truc différent
        val apiKey = ApiKeyManager.getApiKey(this)

        if (apiKey == null) {
            // Launch the connection activity
            val intent = Intent(this, Login::class.java)
            ResultLauncher.launch(intent)


        } else {
            popup.makeToast(this, "Loading informations")

                LocalUserManager.refreshData(this@MainActivity)
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
EXEMPLE POUR RÉCUPÉRER LES ENTREPOTS
Entrepot().getEntrepot(this){ response ->
    var json = Api().castToJson(this, response)

    when (json) {
        is JSONObject -> {
            val idEntrepot = json.getString("id_entrepot")
            popup.showInformationDialog(this, idEntrepot)
        }
        is JSONArray -> {
            if (json.length() > 0) {
                val firstObject = json.getJSONObject(0)
                val idEntrepot = firstObject.getString("id_entrepot")
                popup.showInformationDialog(this, idEntrepot)
                popup.showInformationDialog(this, json.toString())
            } else {
                popup.makeToast(this, "Le tableau JSON est vide")
            }
        }
        else -> {
            popup.makeToast(this, "Impossible de traiter les données JSON")
        }
    }
}


 */


/*

## EXEMPLE POUR GET ##

popup.makeToast(this, "Loading informations")
RequestApi().Get(this, "/user") { responseData ->
    if (responseData !is JSONObject) {
        return@Get
    }
    println(responseData)
}



## EXEMPLE POUR POST ##

val data = mapOf("key1" to "value1", "key2" to "value2")
RequestApi().Post(this, "/someEndpoint", data) { responseData ->
    if (responseData is JSONObject) {
        // Traiter les données au format JSON
        println(responseData)
    } else if (responseData is String) {
        // Traiter les données au format texte
        println(responseData)
    } else {
        // Gérer le cas où les données ne sont pas au format attendu
        println("Unexpected response format")
    }
}


## EXEMPLE POUR PUT ##

val data = mapOf("key1" to "value1", "key2" to "value2")
RequestApi().Put(this, "/someEndpoint", data) { responseData ->
    if (responseData is JSONObject) {
        // Traiter les données au format JSON
        println(responseData)
    } else if (responseData is String) {
        // Traiter les données au format texte
        println(responseData)
    } else {
        // Gérer le cas où les données ne sont pas au format attendu
        println("Unexpected response format")
    }
}


## EXEMPLE POUR DELETE ##

RequestApi().Delete(this, "/someEndpoint") { responseData ->
    if (responseData is JSONObject) {
        // Traiter les données au format JSON
        println(responseData)
    } else if (responseData is String) {
        // Traiter les données au format texte
        println(responseData)
    } else {
        // Gérer le cas où les données ne sont pas au format attendu
        println("Unexpected response format")
    }
}

 */