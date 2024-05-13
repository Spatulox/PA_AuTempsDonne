package com.example.autempsdonnee.api.endpoint

import android.content.Context
import com.example.autempsdonnee.api.RequestApi
import com.example.autempsdonnee.utils.Api


class gestPlanning {

    fun getPlanning(context: Context, callback: (Any?) -> Unit) {
        var endpoint = "/planning/me"

        RequestApi().Get(context, endpoint) { responseData ->
            val data = Api().castToJson(context, responseData)
            callback(data)
        }
    }

}