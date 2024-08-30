package com.example.nomorewaste.api.endpoint

import android.content.Context
import android.util.Log
import com.example.nomorewaste.Models.Ingredient
import com.example.nomorewaste.api.RequestApi
import com.example.nomorewaste.utils.Popup
import org.json.JSONArray
import org.json.JSONObject
import kotlin.coroutines.resume
import kotlin.coroutines.suspendCoroutine

class IngredientApi(private val context: Context) {
    var popup = Popup()

    companion object {
        const val ENDPOINT = "/ingredient/all"
        const val ENDPOINTSEARCH = "/ingredient/"
    }

    suspend fun getIngredient(string: String?): List<Ingredient>? {
        return suspendCoroutine { continuation ->
            RequestApi().Get(context, if (string == null) ENDPOINT else ENDPOINTSEARCH + string) { data ->
                Log.d("IngredientApi", "Raw response: $data")
                if (data is JSONArray) {
                    val ingredientList = mutableListOf<Ingredient>()
                    for (i in 0 until data.length()) {
                        try {
                            val jsonObject = data.getJSONObject(i)
                            Log.d("IngredientApi", "Parsing ingredient: $jsonObject")
                            val id = jsonObject.getInt("id_ingredient")
                            val name = jsonObject.getString("nom_ingredient")
                            val unit_mesure = jsonObject.getString("unit_mesure")
                            ingredientList.add(Ingredient(id, name, unit_mesure))
                        } catch (e: Exception) {
                            Log.e("IngredientApi", "Error parsing ingredient", e)
                        }
                    }
                    Log.d("IngredientApi", "Parsed ${ingredientList.size} ingredients")
                    continuation.resume(ingredientList)
                } else {
                    Log.e("IngredientApi", "Unexpected response type: ${data?.javaClass?.simpleName}")
                    continuation.resume(emptyList())
                }
            }
        }
    }

}