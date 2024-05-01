package com.example.autempsdonnee.api

import android.content.Context
import org.json.JSONObject
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.autempsdonnee.Managers.ApiKeyManager
import com.example.autempsdonnee.utils.Popup
import com.example.autempsdonnee.utils.Api

/**
 * Request the API to connect the user
 */
class ApiLogin {

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

                popup.makeToast(context, Api().getErrorMessage(error))
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

}