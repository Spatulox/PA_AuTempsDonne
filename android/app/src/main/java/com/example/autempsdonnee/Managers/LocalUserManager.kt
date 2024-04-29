package com.example.autempsdonnee.Managers

import android.content.Context
import com.example.autempsdonnee.api.endpoint.User
import com.example.autempsdonnee.utils.Popup
import org.json.JSONObject

class LocalUserManager {

    companion object {
        private const val PREF_NAME = "USER"
        private const val NOM = ""
        private const val PRENOM = ""
        private const val EMAIL = ""
        private const val TELEPHONE = ""
        private const val ADDRESSE = ""

        fun getData(context: Context): Map<String, String?> {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val nom = sharedPrefs.getString(NOM, null)
            val prenom = sharedPrefs.getString(PRENOM, null)
            val email = sharedPrefs.getString(EMAIL, null)
            val telephone = sharedPrefs.getString(TELEPHONE, null)
            val addresse = sharedPrefs.getString(ADDRESSE, null)

            return mapOf(
                "nom" to nom,
                "prenom" to prenom,
                "email" to email,
                "telephone" to telephone,
                "addresse" to addresse
            )
        }

        fun setData(context: Context, key: String, value: String?) {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()

            when (key) {
                "nom" -> editor.putString(NOM, value)
                "prenom" -> editor.putString(PRENOM, value)
                "email" -> editor.putString(EMAIL, value)
                "telephone" -> editor.putString(TELEPHONE, value)
                "addresse" -> editor.putString(ADDRESSE, value)
            }

            editor.apply()
        }

        fun setDataAll(context: Context, jsonData: JSONObject?) {
            var popup = Popup()

            if(jsonData == null){
                popup.makeToast(context, "L'object json doit être rempli (pas être null)")
                return
            }
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()

            editor.putString(NOM, jsonData.optString("nom", null))
            editor.putString(PRENOM, jsonData.optString("prenom", null))
            editor.putString(EMAIL, jsonData.optString("email", null))
            editor.putString(TELEPHONE, jsonData.optString("telephone", null))
            editor.putString(ADDRESSE, jsonData.optString("addresse", null))

            editor.apply()
        }



        fun clearData(context: Context, key: String){
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            sharedPrefs.edit().remove(key).apply()
        }

        fun refreshData(context: Context){
            var user = User(context)
            var data = user.refreshUser()
            println(data)
        }
    }
}