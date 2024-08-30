package com.example.nomorewaste.Adapter

//import RecipeDetailsActivity
import android.content.Context
import android.content.Intent
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.nomorewaste.BleuBleuBleu
import com.example.nomorewaste.Models.Recipe
import com.example.nomorewaste.R


class RecipeAdapter(
    private val context: Context,
    private val recipes: List<Recipe>,
) : RecyclerView.Adapter<RecipeAdapter.RecipeViewHolder>() {

    class RecipeViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val recipeName: TextView = itemView.findViewById(R.id.recipeName)
        val ingredientsCount: TextView = itemView.findViewById(R.id.ingredientsCount)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecipeViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.recipe_item, parent, false)
        return RecipeViewHolder(view)
    }

    override fun onBindViewHolder(holder: RecipeViewHolder, position: Int) {
        val recipe = recipes[position]
        holder.recipeName.text = recipe.nom_recette
        holder.ingredientsCount.text = "${recipe.nb_ingredients_matches} ingrédients correspondants"

        holder.itemView.setOnClickListener {
            // Ouvre une activity avec l'ID_recette
            val intent = Intent(context, BleuBleuBleu::class.java)
            intent.putExtra("RECIPE_ID", recipe.id_recette)
            context.startActivity(intent)
        }
    }


    override fun getItemCount() = recipes.size
}
