package com.example.autempsdonnee

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.widget.ListView
import com.example.autempsdonnee.Managers.PlanningAdapter
import com.example.autempsdonnee.api.endpoint.gestPlanning
import com.example.autempsdonnee.utils.Popup
import kotlinx.coroutines.GlobalScope
import org.json.JSONArray

class Planning : AppCompatActivity() {

    data class PlanningItem(
        val id_planning: String,
        val description: String,
        val date_activite: String,
        val id_index_planning: String,
        val nom_index_planning: String,
        val id_activite: String,
        val activity_desc: String,
        val address: List<String>?,
        val user: Any?
    )

    private val planningItems = mutableListOf<PlanningItem>()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_planning)

        val planningListView = findViewById<ListView>(R.id.planningListView)
        val adapter = PlanningAdapter(this, planningItems)
        planningListView.adapter = adapter

        gestPlanning().getPlanning(this) { result ->
            if (result == null) {
                Popup().showInformationDialog(this, "Error when retrieving your planning")
                return@getPlanning
            }
            val jsonString = result.toString()
            //Popup().showInformationDialog(this, jsonString)

            val jsonArray = JSONArray(jsonString)
            for (i in 0 until jsonArray.length()) {
                val jsonObject = jsonArray.getJSONObject(i)
                val id_planning = jsonObject.getString("id_planning")
                val description = jsonObject.getString("description")
                val date_activite = jsonObject.getString("date_activite")
                val id_index_planning = jsonObject.getString("id_index_planning")
                val nom_index_planning = jsonObject.getString("nom_index_planning")
                val id_activite = jsonObject.getString("id_activite")
                val activity_desc = jsonObject.getString("activity_desc")

                val addressList = if (jsonObject.has("address") && !jsonObject.isNull("address")) {
                    val addressArray = jsonObject.getJSONArray("address")
                    val addressList = mutableListOf<String>()
                    for (i in 0 until addressArray.length()) {
                        addressList.add(addressArray.getString(i))
                    }
                    addressList
                } else {
                    null
                }


                val user = if (jsonObject.has("user") && !jsonObject.isNull("user")) {
                    jsonObject.get("user")
                } else {
                    null
                }
                planningItems.add(PlanningItem(id_planning, description, date_activite, id_index_planning, nom_index_planning, id_activite, activity_desc, addressList, user))
            }

            adapter.notifyDataSetChanged()
        }



    }
}