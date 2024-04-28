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

    //
    //----------------------------------------------------------------------------------------------
    //

    fun Post(context: Context, endpoint: String, data: Map<String, Any>, callback: (Any?) -> Unit) {
        if (ApiKeyManager.getApiKey(context) == null) {
            popup.makeToast(context, "API_KEY is null, plz connect you before")
            callback(null)
            return
        }

        if (data.isEmpty()) {
            popup.makeToast(context, "Need data to do a post request :/")
            callback(null)
            return
        }

        val headers = mapOf(
            "Content-Type" to "application/json",
            "apikey" to ApiKeyManager.getApiKey(context)!!
        )

        val jsonBody = JSONObject(data)
        val requestBody = jsonBody.toString()

        val queue = Volley.newRequestQueue(context)

        val request = object : StringRequest(
            Request.Method.POST,
            AppConstants.API_BASE_URL + endpoint,
            { response ->
                println(response)
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
            override fun getBody(): ByteArray = requestBody.toByteArray(Charsets.UTF_8)
            override fun getHeaders(): Map<String, String> = headers
            override fun getBodyContentType(): String = "application/json; charset=utf-8"
        }

        queue.add(request)
    }

    //
    //----------------------------------------------------------------------------------------------
    //

    fun Put(context: Context, endpoint: String, data: Map<String, Any>, callback: (Any?) -> Unit) {
        if (ApiKeyManager.getApiKey(context) == null) {
            popup.makeToast(context, "API_KEY is null, plz connect you before")
            callback(null)
            return
        }

        if (data.isEmpty()) {
            popup.makeToast(context, "Need data to do a put request :/")
            callback(null)
            return
        }

        val headers = mapOf(
            "Content-Type" to "application/json",
            "apikey" to ApiKeyManager.getApiKey(context)!!
        )

        val jsonBody = JSONObject(data)
        val requestBody = jsonBody.toString()

        val queue = Volley.newRequestQueue(context)

        val request = object : StringRequest(
            Request.Method.PUT,
            AppConstants.API_BASE_URL + endpoint,
            { response ->
                println(response)
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
            override fun getBody(): ByteArray = requestBody.toByteArray(Charsets.UTF_8)
            override fun getHeaders(): Map<String, String> = headers
            override fun getBodyContentType(): String = "application/json; charset=utf-8"
        }

        queue.add(request)
    }

    //
    //----------------------------------------------------------------------------------------------
    //

    fun Delete(context: Context, endpoint: String, data: Map<String, Any>, callback: (Any?) -> Unit) {
        if (ApiKeyManager.getApiKey(context) == null) {
            popup.makeToast(context, "API_KEY is null, plz connect you before")
            callback(null)
            return
        }

        if (data.isEmpty()) {
            popup.makeToast(context, "Need data to do a post request :/")
            callback(null)
            return
        }

        val headers = mapOf(
            "Content-Type" to "application/json",
            "apikey" to ApiKeyManager.getApiKey(context)!!
        )

        val jsonBody = JSONObject(data)
        val requestBody = jsonBody.toString()

        val queue = Volley.newRequestQueue(context)

        val request = object : StringRequest(
            Request.Method.DELETE,
            AppConstants.API_BASE_URL + endpoint,
            { response ->
                println(response)
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
            override fun getBody(): ByteArray = requestBody.toByteArray(Charsets.UTF_8)
            override fun getHeaders(): Map<String, String> = headers
            override fun getBodyContentType(): String = "application/json; charset=utf-8"
        }

        queue.add(request)
    }

    //
    //----------------------------------------------------------------------------------------------
    //

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


