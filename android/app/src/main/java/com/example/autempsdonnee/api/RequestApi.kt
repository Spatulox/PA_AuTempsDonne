package com.example.autempsdonnee.api

import android.content.Context
import org.json.JSONObject
import com.android.volley.Request
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.autempsdonnee.Managers.ApiKeyManager
import com.example.autempsdonnee.constantes.ConstantesAPI.AppConstants
import com.example.autempsdonnee.utils.Popup
import org.json.JSONException

class RequestApi {

    var popup = Popup()

    fun Get(context: Context, endpoint: String, callback: (Any?) -> Unit) {
        if (ApiKeyManager.getApiKey(context) == null) {
            popup.makeToast(context, "API_KEY is null, plz connect you before")
            callback(null)
            return
        }

        val headers = mapOf(
            "Content-Type" to "application/json",
            "apikey" to ApiKeyManager.getApiKey(context)!!
        )

        val queue = Volley.newRequestQueue(context)

        val request = object : StringRequest(
            Request.Method.GET,
            AppConstants.API_BASE_URL + endpoint,
            { response ->
                val responseData = try {
                    JSONObject(response)
                } catch (e: JSONException) {
                    response
                }
                callback(responseData)
            },
            { error ->
                println("Error when fetching API:")
                error.printStackTrace()
                popup.makeToast(context, getErrorMessage(error))
                callback(null)
            }
        ) {
            override fun getHeaders(): Map<String, String> = headers
        }

        println("LA VULVEEEEEEEEEEEE")
        queue.add(request)
    }




    private fun getErrorMessage(error: VolleyError): String {
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


