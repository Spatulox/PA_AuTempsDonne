package com.example.nomorewaste.utils

import android.content.Context
import com.android.volley.AuthFailureError
import com.android.volley.NetworkError
import com.android.volley.ParseError
import com.android.volley.ServerError
import com.android.volley.TimeoutError
import com.android.volley.VolleyError
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject

class Api {

    /*fun getErrorMessage(error: VolleyError): String {
        return if (error.networkResponse != null && error.networkResponse.data != null) {
            val errorResponse = String(error.networkResponse.data, Charsets.UTF_8)
            try {
                val jsonObject = JSONObject(errorResponse)
                jsonObject.getString("message")
            } catch (e: JSONException) {
                "Error when fetching API1"
            }
        } else {
            "Error when fetching API2"
        }
    }*/

    fun getErrorMessage(error: VolleyError): String {
        return when {
            error.networkResponse != null && error.networkResponse.data != null -> {
                try {
                    val errorResponse = String(error.networkResponse.data, Charsets.UTF_8)
                    val jsonObject = JSONObject(errorResponse)
                    val message = jsonObject.optString("message", "No specific message provided")
                    "Server Error (${error.networkResponse.statusCode}): $message"
                } catch (e: JSONException) {
                    "JSON Parsing Error: ${e.message}"
                } catch (e: Exception) {
                    "Unexpected Error: ${e.message}"
                }
            }
            error.networkResponse != null -> {
                "Network Error (${error.networkResponse.statusCode}): ${error.networkResponse.statusCode.toHttpStatusMessage()}"
            }
            error is NetworkError -> "Network Error: No internet connection"
            error is TimeoutError -> "Timeout Error: The connection timed out"
            error is ParseError -> "Parsing Error: Unable to parse the response"
            error is AuthFailureError -> "Authentication Error: ${error.message}"
            error is ServerError -> "Server Error: ${error.message}"
            else -> "Unknown Error: ${error.message ?: "No details available"}"
        }
    }

    fun Int.toHttpStatusMessage(): String {
        return when (this) {
            400 -> "Bad Request"
            401 -> "Unauthorized"
            403 -> "Forbidden"
            404 -> "Not Found"
            500 -> "Internal Server Error"
            // Add more status codes as needed
            else -> "HTTP Error"
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