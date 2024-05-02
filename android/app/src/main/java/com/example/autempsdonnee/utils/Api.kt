package com.example.autempsdonnee.utils

import android.content.Context
import com.android.volley.VolleyError
import org.json.JSONArray
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

    fun castToJson(context: Context, response: Any?): Any? {

        if(response == null){
            Popup().makeToast(context, "Data to cast are null")
            return null
        }

        return try {
            when (response) {
                is JSONObject -> response
                is JSONArray -> {
                    if (response.length() == 1) {
                        response.getJSONObject(0)
                    } else {
                        response
                    }
                }
                else -> {
                    val jsonData = JSONObject(response.toString())
                    jsonData
                }
            }
        } catch (e: JSONException) {
            return try {
                val jsonArray = JSONArray(response.toString())

                if (jsonArray.length() == 1) {
                    jsonArray.getJSONObject(0)
                } else {
                    jsonArray
                }
            } catch (e2: JSONException) {
                Popup().makeToast(context, "Impossible to cast the data into JSON")
                null
            }
        }
    }
}