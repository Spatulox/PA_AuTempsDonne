package com.example.autempsdonnee.Managers

import android.content.Context

class ApiKeyManager {
    companion object {
        private const val PREF_NAME = "MyPrefs"
        private const val API_KEY = "apikey"

        fun getApiKey(context: Context): String? {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            return sharedPrefs.getString(API_KEY, null)
        }

        fun setApiKey(context: Context, apiKey: String) {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()
            editor.putString(API_KEY, apiKey)
            editor.apply()
        }

        fun clearApiKey(context: Context){
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            sharedPrefs.edit().remove("apikey").apply()
        }

        /*
        ## EDITER UNE CLEF

        val editor = sharedPrefs.edit()
        editor.putString(API_KEY, "votre_apikey_ici")
        editor.apply()

         */
    }
}
