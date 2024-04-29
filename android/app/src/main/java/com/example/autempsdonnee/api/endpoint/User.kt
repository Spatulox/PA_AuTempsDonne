package com.example.autempsdonnee.api.endpoint

import android.content.Context
import com.example.autempsdonnee.api.RequestApi
import com.example.autempsdonnee.utils.Popup
import org.json.JSONObject

class User (private val context: Context) {

    var popup = Popup()

    companion object {
        const val ENDPOINT = "/user"
    }

    fun refreshUser(): Any? {
        var responseData: Any? = null
        RequestApi().Get(context, ENDPOINT) { data ->
            if (data !is JSONObject) {
                return@Get
            }
            responseData = data
        }
        return responseData
    }

}
