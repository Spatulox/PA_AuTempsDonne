package com.example.nomorewaste.api.endpoint

import android.app.DownloadManager.Request
import android.content.Context
import android.util.Log
import com.example.nomorewaste.Models.Ingredient
import com.example.nomorewaste.Models.Recipe
import com.example.nomorewaste.Models.RecipeSearchRequest
import com.example.nomorewaste.api.RequestApi
import com.example.nomorewaste.utils.Popup
import org.json.JSONArray
import kotlin.coroutines.resume
import kotlin.coroutines.suspendCoroutine

class RecipeApi() {

    var popup = Popup()

    companion object {
        const val ENDPOINT = "/recette/search"
    }

    suspend fun getRecipe(context: Context, dataList: RecipeSearchRequest?): List<Recipe>? {

        return suspendCoroutine { continuation ->

            val requestData = dataList?.let { convertToMap(it) } ?: emptyMap()

            RequestApi().Post(context, ENDPOINT, requestData) { data ->
                Log.d("RecipeApi", "Raw response: $data")
                if (data is JSONArray) {
                    val recipeList = mutableListOf<Recipe>()
                    for (i in 0 until data.length()) {
                        try {
                            val jsonObject = data.getJSONObject(i)
                            Log.d("RecipeApi", "Parsing recipe: $jsonObject")
                            val idRecette = jsonObject.getInt("id_recette")
                            val nomRecette = jsonObject.getString("nom_recette")
                            val nbIngredientsMatches = jsonObject.getInt("nb_ingredients_matches")
                            val pourcentageIngredients = jsonObject.getInt("pourcentage_ingredients")

                            // Créer un objet Recipe et l'ajouter à la liste
                            recipeList.add(Recipe(idRecette, nomRecette, nbIngredientsMatches, pourcentageIngredients))
                        } catch (e: Exception) {
                            Log.e("RecipeApi", "Error parsing recipe", e)
                        }
                    }
                    Log.d("RecipeApi", "Parsed ${recipeList.size} recipes")
                    continuation.resume(recipeList)
                } else {
                    Log.e("RecipeApi", "Unexpected response type: ${data?.javaClass?.simpleName}")
                    continuation.resume(emptyList())
                }
            }
        }
    }

    private fun convertToMap(request: RecipeSearchRequest): Map<String, Any> {
        val ingredientsList = request.ingredients?.map { ingredient ->
            mapOf(
                "id_ingredient" to ingredient.id_ingredient,
                "quantite_ingredient" to ingredient.quantite_ingredient
            )
        } ?: emptyList()

        return mapOf("ingredients" to ingredientsList)
    }

}