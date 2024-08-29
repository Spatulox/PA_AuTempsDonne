package com.example.nomorewaste.Managers

import android.content.Context
import com.example.nomorewaste.api.endpoint.User
import com.example.nomorewaste.utils.Popup
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.launch
import org.json.JSONObject

class LocalUserManager {

    companion object {
        private const val PREF_NAME = "USER"
        private const val KEY_NOM = "nom"
        private const val KEY_PRENOM = "prenom"
        private const val KEY_EMAIL = "email"
        private const val KEY_TELEPHONE = "telephone"
        private const val KEY_ADDRESSE = "addresse"

        fun getData(context: Context): Map<String, String?> {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val nom = sharedPrefs.getString(KEY_NOM, null)
            val prenom = sharedPrefs.getString(KEY_PRENOM, null)
            val email = sharedPrefs.getString(KEY_EMAIL, null)
            val telephone = sharedPrefs.getString(KEY_TELEPHONE, null)
            val addresse = sharedPrefs.getString(KEY_ADDRESSE, null)

            return mapOf(
                "nom" to nom,
                "prenom" to prenom,
                "email" to email,
                "telephone" to telephone,
                "addresse" to addresse
            )
        }

        fun setData(context: Context, key: String, value: String?): Boolean  {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()

            when (key) {
                "nom" -> editor.putString(KEY_NOM, value)
                "prenom" -> editor.putString(KEY_PRENOM, value)
                "email" -> editor.putString(KEY_EMAIL, value)
                "telephone" -> editor.putString(KEY_TELEPHONE, value)
                "addresse" -> editor.putString(KEY_ADDRESSE, value)
            }

            return editor.commit()
        }

        fun setDataAll(context: Context, jsonData: JSONObject?): Boolean {
            if (jsonData == null) {
                Popup().makeToast(context, "L'objet JSON doit être rempli (pas être null)")
                return false
            }

            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()

            editor.putString(KEY_NOM, jsonData.optString(KEY_NOM))
            editor.putString(KEY_PRENOM, jsonData.optString(KEY_PRENOM))
            editor.putString(KEY_EMAIL, jsonData.optString(KEY_EMAIL))
            editor.putString(KEY_TELEPHONE, jsonData.optString(KEY_TELEPHONE))
            editor.putString(KEY_ADDRESSE, jsonData.optString(KEY_ADDRESSE))

            return editor.commit()
        }

        fun clearData(context: Context, key: String) {
            val sharedPrefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE)
            val editor = sharedPrefs.edit()

            when (key) {
                "nom" -> editor.remove(KEY_NOM)
                "prenom" -> editor.remove(KEY_PRENOM)
                "email" -> editor.remove(KEY_EMAIL)
                "telephone" -> editor.remove(KEY_TELEPHONE)
                "addresse" -> editor.remove(KEY_ADDRESSE)
            }

            editor.apply()
        }

        fun refreshData(context: Context) {
            var popup = Popup()
            val user = User(context)

            GlobalScope.launch {
                val data = user.refreshUser()

                if (data == null) {
                    popup.makeToast(context, "Erreur : Données nulles")
                    return@launch
                }

                try {
                    val jsonData = data as JSONObject
                    setDataAll(context, jsonData)
                } catch (e: ClassCastException) {
                    popup.makeToast(context, "Erreur : Impossible to cast data to a json object")
                    return@launch
                }
            }


        }
    }
}