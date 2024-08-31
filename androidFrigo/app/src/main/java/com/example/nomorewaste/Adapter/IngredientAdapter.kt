package com.example.nomorewaste.Adapter

import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.nomorewaste.Models.Ingredient

class IngredientAdapter(private val onItemClick: (Ingredient) -> Unit) :
    RecyclerView.Adapter<IngredientAdapter.ViewHolder>() {

    private var ingredients: List<Ingredient> = emptyList()

    fun updateIngredients(newIngredients: List<Ingredient>) {
        Log.d("IngredientAdapter", "Updating ingredients: ${newIngredients.size}")
        ingredients = newIngredients
        notifyDataSetChanged()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(android.R.layout.simple_list_item_1, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val ingredient = ingredients[position]
        holder.bind(ingredient)
    }

    override fun getItemCount() = ingredients.size

    inner class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        fun bind(ingredient: Ingredient) {
            itemView.findViewById<TextView>(android.R.id.text1).text = ingredient.name
            itemView.setOnClickListener { onItemClick(ingredient) }
        }
    }
}
