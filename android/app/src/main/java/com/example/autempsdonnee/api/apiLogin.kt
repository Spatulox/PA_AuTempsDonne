package com.example.autempsdonnee.api

import android.content.Context
import android.widget.Toast
import org.json.JSONObject
import com.android.volley.Request
import com.android.volley.VolleyError
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.autempsdonnee.Managers.ApiKeyManager
//import com.example.autempsdonnee.constantes.ConstantesAPI.AppConstants
import com.example.autempsdonnee.utils.Popup
import org.json.JSONException

/**
 * Request the API to connect the user
 */
class apiLogin {

    var popup = Popup()

    fun login(context: Context, email: String, password: String): Boolean {
        val queue = Volley.newRequestQueue(context)

        val jsonBody = JSONObject()
        jsonBody.put("email", email)
        jsonBody.put("mdp", password)

        val requestBody = jsonBody.toString()

        var isLoginSuccessful = false

        val request = object : StringRequest(
            Request.Method.POST,
            ConstantesAPI.AppConstants.API_BASE_URL + "/login",
            { response ->
                val jsonObject = JSONObject(response)
                ApiKeyManager.setApiKey(context, jsonObject.getString("apikey"))
                isLoginSuccessful = true
            },
            { error ->
                println("Error when fetching API:")

                val errorResponse = String(error.networkResponse?.data ?: ByteArray(0), Charsets.UTF_8)
                println("Error response: $errorResponse")

                popup.makeToast(context, getErrorMessage(error))
            }
        ) {
            override fun getBody(): ByteArray {
                return requestBody.toByteArray(Charsets.UTF_8)
            }

            override fun getBodyContentType(): String {
                return "application/json; charset=utf-8"
            }
        }

        queue.add(request)

        return isLoginSuccessful
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