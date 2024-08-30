package com.example.nomorewaste

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.nomorewaste.Adapter.RecipeAdapter
import com.example.nomorewaste.Models.IngredientRequest
import com.example.nomorewaste.Models.RecipeSearchRequest
import com.example.nomorewaste.Models.SelectedIngredient
import com.example.nomorewaste.api.endpoint.RecipeApi
import com.example.nomorewaste.utils.Popup
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch

class SearchRecipesActivity : AppCompatActivity() {

    private lateinit var recyclerView: RecyclerView
    private lateinit var recipeAdapter: RecipeAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_search_recipes)

        recyclerView = findViewById(R.id.recyclerView)
        recyclerView.layoutManager = LinearLayoutManager(this)

        val data = intent.getParcelableArrayListExtra<SelectedIngredient>("SELECTED_INGREDIENTS") ?: arrayListOf()

        val recipeSearchRequest = createRecipeSearchRequest(data)

        lifecycleScope.launch {
            try {
                Popup().makeToast(this@SearchRecipesActivity, "Loading Recipes... Can take a while")
                val returnData = RecipeApi().getRecipe(this@SearchRecipesActivity, recipeSearchRequest)
                Log.d("SearchRecipeActivity", returnData.toString())
                if(returnData === null) {
                    Popup().showInformationDialog(this@SearchRecipesActivity, "No Recipes")
                    return@launch
                }
                val recipeAdapter = RecipeAdapter(returnData)
                recyclerView.adapter = recipeAdapter

            } catch (e: Exception) {
                Log.e("SearchRecipeActivity", "Error loading ingredients", e)
                Popup().makeToast(this@SearchRecipesActivity, "Erreur lors du chargement des recettes")
            }
        }
    }

    private fun createRecipeSearchRequest(selectedIngredients: List<SelectedIngredient>?): RecipeSearchRequest {
        val ingredientRequests = selectedIngredients?.map { selected ->
            IngredientRequest(
                id_ingredient = selected.ingredient.id,
                quantite_ingredient = selected.quantity,
            )
        }
        return RecipeSearchRequest(ingredients = ingredientRequests)
    }
}
