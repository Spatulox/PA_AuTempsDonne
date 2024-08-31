package com.example.nomorewaste.Models


data class IngredientRequest(
    val id_ingredient: Int,
    val quantite_ingredient: Int
)

data class RecipeSearchRequest(
    val ingredients: List<IngredientRequest>?
)
