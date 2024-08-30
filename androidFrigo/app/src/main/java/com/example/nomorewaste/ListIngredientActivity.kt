package com.example.nomorewaste

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.text.Editable
import android.text.InputType
import android.text.TextWatcher
import android.widget.EditText
import androidx.appcompat.app.AlertDialog
import androidx.lifecycle.ViewModelProvider
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.nomorewaste.Adapter.IngredientAdapter
import com.example.nomorewaste.Models.Ingredient
import com.example.nomorewaste.Models.IngredientViewModel
import com.example.nomorewaste.utils.Popup
import kotlinx.coroutines.launch

import android.util.Log
import android.widget.Button
import androidx.lifecycle.Observer
import kotlinx.coroutines.Job
import kotlinx.coroutines.delay

class ListIngredientActivity : AppCompatActivity() {

    private lateinit var viewModel: IngredientViewModel
    private lateinit var adapter: IngredientAdapter
    private lateinit var searchEditText: EditText

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_list_ingredient)

        Log.d("ListIngredientActivity", "onCreate called")

        viewModel = ViewModelProvider(this).get(IngredientViewModel::class.java)
        adapter = IngredientAdapter { ingredient -> showQuantityDialog(ingredient) }

        val recyclerView = findViewById<RecyclerView>(R.id.ingredientsRecyclerView)
        recyclerView.layoutManager = LinearLayoutManager(this)
        recyclerView.adapter = adapter

        searchEditText = findViewById(R.id.searchEditText)
        setupSearch()

        viewModel.ingredients.observe(this, Observer { ingredients ->
            Log.d("ListIngredientActivity", "Ingredients updated: ${ingredients.size}")
            adapter.updateIngredients(ingredients)
        })

        findViewById<Button>(R.id.searchRecipesButton).setOnClickListener {
            val intent = Intent(this, SearchRecipesActivity::class.java)
            startActivity(intent)
        }

        findViewById<Button>(R.id.clearIngredientsButton).setOnClickListener {
            viewModel.clearSelectedIngredients()
        }

        loadIngredients()
    }

    private fun loadIngredients() {
        Log.d("ListIngredientActivity", "loadIngredients called")
        lifecycleScope.launch {
            try {
                viewModel.getIngredients(this@ListIngredientActivity)
            } catch (e: Exception) {
                Log.e("ListIngredientActivity", "Error loading ingredients", e)
                Popup().makeToast(this@ListIngredientActivity, "Erreur lors du chargement des ingrédients")
            }
        }
    }

    private fun setupSearch() {
        searchEditText.addTextChangedListener(object : TextWatcher {
            private var searchJob: Job? = null

            override fun afterTextChanged(s: Editable?) {
                searchJob?.cancel()
                searchJob = lifecycleScope.launch {
                    delay(100) // Délai pour éviter les appels pdt la frappe
                    viewModel.searchIngredients(this@ListIngredientActivity, s.toString())
                }
            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {}
        })
    }


    private fun showQuantityDialog(ingredient: Ingredient) {
        val input = EditText(this)
        input.inputType = InputType.TYPE_CLASS_NUMBER

        AlertDialog.Builder(this)
            .setTitle("Quantité pour ${ingredient.name}")
            .setView(input)
            .setPositiveButton("OK") { _, _ ->
                val quantity = input.text.toString().toIntOrNull() ?: 0
                viewModel.addSelectedIngredient(ingredient, quantity)
            }
            .setNegativeButton("Annuler", null)
            .show()

        val selectedIngredientsString = viewModel.selectedIngredients.value?.toString() ?: "Liste vide"
        Popup().showInformationDialog(this@ListIngredientActivity, selectedIngredientsString)
    }
}


