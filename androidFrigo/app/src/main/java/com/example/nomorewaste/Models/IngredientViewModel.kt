package com.example.nomorewaste.Models

import android.content.Context
import android.os.Parcel
import android.os.Parcelable
import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import com.example.nomorewaste.api.endpoint.IngredientApi
import com.example.nomorewaste.utils.Popup
import java.io.Serializable

data class Ingredient(
    val id: Int,
    val name: String,
    val quantite_recette: Int = 0,
    val unit_measure: String? = "null"
) : Parcelable {
    constructor(parcel: Parcel) : this(
        parcel.readInt(),
        parcel.readString() ?: "", // Assurez-vous de gérer les valeurs nulles
        parcel.readInt(),
        parcel.readString() ?: "" // Lire le champ unit_measure
    )

    override fun writeToParcel(parcel: Parcel, flags: Int) {
        parcel.writeInt(id)
        parcel.writeString(name)
        parcel.writeInt(quantite_recette)
        parcel.writeString(unit_measure)
    }

    override fun describeContents(): Int {
        return 0
    }

    companion object CREATOR : Parcelable.Creator<Ingredient> {
        override fun createFromParcel(parcel: Parcel): Ingredient {
            return Ingredient(parcel)
        }

        override fun newArray(size: Int): Array<Ingredient?> {
            return arrayOfNulls(size)
        }
    }
}


data class SelectedIngredient(
    val ingredient: Ingredient,
    var quantity: Int
) : Parcelable {
    constructor(parcel: Parcel) : this(
        parcel.readParcelable(Ingredient::class.java.classLoader)!!,
        parcel.readInt()
    )

    override fun writeToParcel(parcel: Parcel, flags: Int) {
        parcel.writeParcelable(ingredient, flags)
        parcel.writeInt(quantity)
    }

    override fun describeContents(): Int {
        return 0
    }

    companion object CREATOR : Parcelable.Creator<SelectedIngredient> {
        override fun createFromParcel(parcel: Parcel): SelectedIngredient {
            return SelectedIngredient(parcel)
        }

        override fun newArray(size: Int): Array<SelectedIngredient?> {
            return arrayOfNulls(size)
        }
    }
}

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

    fun clearSelectedIngredients(context: Context) {
        Popup().makeToast(context, "Liste d'ingrédients vidé")
        _selectedIngredients.value = emptyList()
    }
}
