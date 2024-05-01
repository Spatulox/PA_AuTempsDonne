package com.example.autempsdonnee.utils

import com.android.volley.VolleyError
import org.json.JSONException
import org.json.JSONObject

class Api {

    fun getErrorMessage(error: VolleyError): String {
        return if (error.networkResponse != null && error.networkResponse.data != null) {
            val errorResponse = String(error.networkResponse.data, Charsets.UTF_8)
            try {
                val jsonObject = JSONObject(errorResponse)
                jsonObject.getString("message")
            } catch (e: JSONException) {
                "Error when fetching API"
            }
        } else {
            "Error when fetching API"
        }
    }
}