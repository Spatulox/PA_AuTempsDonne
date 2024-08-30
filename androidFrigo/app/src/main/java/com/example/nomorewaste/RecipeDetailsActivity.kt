import android.os.Bundle
import android.util.Log
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import androidx.lifecycle.lifecycleScope
import kotlinx.coroutines.launch
import com.example.nomorewaste.R
import com.example.nomorewaste.api.endpoint.RecipeApi
import com.example.nomorewaste.utils.Popup

class RecipeDetailsActivity : AppCompatActivity() {

    private lateinit var recipeNameTextView: TextView
    private lateinit var recipeDescriptionTextView: TextView
    private lateinit var ingredientsTextView: TextView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_recipe_details)

        // Initialiser les TextViews
        recipeNameTextView = findViewById(R.id.recipeNameTextView)
        recipeDescriptionTextView = findViewById(R.id.recipeDescriptionTextView)
        ingredientsTextView = findViewById(R.id.ingredientsTextView)

        // Récupérer l'id_recette depuis l'intention
        val recipeId = intent.getIntExtra("RECIPE_ID", -1)
        if (recipeId != -1) {
            Log.d("RecipeDetailActivity", "Recipe ID: $recipeId")
            loadRecipe(recipeId)
        } else {
            Log.e("RecipeDetailActivity", "Invalid Recipe ID")
            Popup().showInformationDialog(this@RecipeDetailsActivityBLEUBLEUBLEU, "An error occurred...")
            finish()
        }
    }

    private fun loadRecipe(id: Int) {
        lifecycleScope.launch {
            val recipe = RecipeApi().getRecipeId(this@RecipeDetailsActivityBLEUBLEUBLEU, id)
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
        }
    }
}
