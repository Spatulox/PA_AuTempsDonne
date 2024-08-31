package com.example.nomorewaste.Models;

data class Recipe(
    val id_recette: Int,
    val nom_recette: String,
    val nb_ingredients_matches: Int,
    val pourcentage_ingredients: Int
)

data class RecipeDetail(
    val id_recette: Int,
    val nom_recette: String,
    val description: String,
    val ingredient: List<Ingredient>
)