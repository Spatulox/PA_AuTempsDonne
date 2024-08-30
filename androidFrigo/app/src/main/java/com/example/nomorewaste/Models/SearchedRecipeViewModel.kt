package com.example.nomorewaste.Models;

data class Recipe(
    val id_recette: Int,
    val nom_recette: String,
    val nb_ingredients_matches: Int,
    val pourcentage_ingredients: Int
)