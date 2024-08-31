package com.example.nomorewaste.api.endpoint

import android.content.Context
import com.example.nomorewaste.api.RequestApi
import com.example.nomorewaste.utils.Popup
import org.json.JSONObject
import kotlin.coroutines.resume
import kotlin.coroutines.suspendCoroutine

class User (private val context: Context) {

    var popup = Popup()

    companion object {
        const val ENDPOINT = "/user"
    }

    suspend fun refreshUser(): Any? {
        return suspendCoroutine { continuation ->
            RequestApi().Get(context, ENDPOINT) { data ->
                if (data is JSONObject) {
                    continuation.resume(data)
                } else {
                    continuation.resume(null)
                }
            }
        }
    }

}
