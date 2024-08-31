package com.example.nomorewaste

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.ProgressBar
import android.widget.TextView
import androidx.lifecycle.lifecycleScope
import com.example.nomorewaste.api.endpoint.RecipeApi
import com.example.nomorewaste.utils.Popup
import kotlinx.coroutines.launch

class BleuBleuBleu : AppCompatActivity() {

    private lateinit var recipeNameTextView: TextView
    private lateinit var recipeDescriptionTextView: TextView
    private lateinit var ingredientsTextView: TextView
    private lateinit var progressBar: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_bleu_bleu_bleu)

        // Initialiser les TextViews
        recipeNameTextView = findViewById(R.id.recipeNameTextView)
        recipeDescriptionTextView = findViewById(R.id.recipeDescriptionTextView)
        ingredientsTextView = findViewById(R.id.ingredientsTextView)
        progressBar = findViewById(R.id.progressBar)

        // Récupérer l'id_recette depuis l'intention
        val recipeId = intent.getIntExtra("RECIPE_ID", -1)
        if (recipeId != -1) {
            Log.d("RecipeDetailActivity", "Recipe ID: $recipeId")
            loadRecipe(recipeId)
        } else {
            Log.e("RecipeDetailActivity", "Invalid Recipe ID")
            Popup().showInformationDialog(this@BleuBleuBleu, "An error occurred...")
            finish()
        }
    }

    private fun loadRecipe(id: Int) {
        lifecycleScope.launch {
            val recipe = RecipeApi().getRecipeId(this@BleuBleuBleu, id)
            recipe?.let {
                recipeNameTextView.text = it.nom_recette
                recipeDescriptionTextView.text = it.description

                // Afficher les ingrédients
                val ingredientsList = it.ingredient.joinToString("\n") { ingredient ->
                    "${ingredient.name} - ${ingredient.quantite_recette} ${ingredient.unit_measure}"
                }
                ingredientsTextView.text = ingredientsList
            } ?: run {
                Log.e("RecipeDetailActivity", "Failed to load recipe details")
            }
            progressBar.visibility = View.GONE
            recipeNameTextView.visibility = View.VISIBLE
            recipeDescriptionTextView.visibility = View.VISIBLE
            ingredientsTextView.visibility = View.VISIBLE
        }
    }
}