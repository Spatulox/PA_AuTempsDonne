package com.example.nomorewaste.Models

import android.content.Context
import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import com.example.nomorewaste.api.endpoint.IngredientApi
import com.example.nomorewaste.utils.Popup

data class Ingredient(
    val id: Int,
    val name: String
)

data class SelectedIngredient(
    val ingredient: Ingredient,
    var quantity: Int
)

class IngredientViewModel : ViewModel() {

    private val _ingredients = MutableLiveData<List<Ingredient>>()
    val ingredients: LiveData<List<Ingredient>> = _ingredients

    private val _selectedIngredients = MutableLiveData<List<SelectedIngredient>>()
    val selectedIngredients: LiveData<List<SelectedIngredient>> = _selectedIngredients

    suspend fun getIngredients(context: Context) {
        Log.d("IngredientViewModel", "getIngredients called")
        try {
            val ingreApi = IngredientApi(context)
            val result = ingreApi.getIngredient(null)
            if (result != null) {
                Log.d("IngredientViewModel", "Ingredients fetched: ${result.size}")
                _ingredients.postValue(result)
            } else {
                Log.e("IngredientViewModel", "Error: result is null")
                Popup().makeToast(context, "Erreur lors de la récupération des ingrédients")
            }
        } catch (e: Exception) {
            Log.e("IngredientViewModel", "Error fetching ingredients", e)
            Popup().makeToast(context, "Erreur lors de la récupération des ingrédients: ${e.message}")
        }
    }

    suspend fun searchIngredients(context: Context, string: String){
        var IngreApi = IngredientApi(context)
        val result = IngreApi.getIngredient(string)
        if(result == null){
            Popup().makeToast(context, "Erreur when fetching ingredients")
        }
        _ingredients.value = result
    }

    fun addSelectedIngredient(ingredient: Ingredient, quantity: Int) {
        val currentList = _selectedIngredients.value ?: emptyList()
        val newList = currentList.toMutableList()
        newList.add(SelectedIngredient(ingredient, quantity))
        _selectedIngredients.value = newList
    }

    fun clearSelectedIngredients() {
        _selectedIngredients.value = emptyList()
    }
}
