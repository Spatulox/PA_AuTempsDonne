package com.example.autempsdonnee.api.endpoint

import android.content.Context
import com.example.autempsdonnee.api.RequestApi
import com.example.autempsdonnee.utils.Api
import com.example.autempsdonnee.utils.Popup
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.async

class Entrepot {

    var popup = Popup()

    fun getEntrepot(context: Context, idEntrepot: Int = -1, onEntrepotLoaded: (Any?) -> Unit){
        var endpoint = "/entrepot"
        if(idEntrepot != -1){
            endpoint+="/$idEntrepot"
        }

        GlobalScope.async {
            RequestApi().Get(context, endpoint) { responseData ->
                val data = Api().castToJson(context, responseData)
                onEntrepotLoaded(data)
            }
        }
    }

}