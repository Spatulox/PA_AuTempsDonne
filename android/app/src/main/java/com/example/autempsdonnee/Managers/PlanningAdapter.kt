package com.example.autempsdonnee.Managers

import android.content.Context
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.BaseAdapter
import android.widget.TextView
import com.example.autempsdonnee.Planning
import com.example.autempsdonnee.R

class PlanningAdapter(private val context: Context, private val planningItems: List<Planning.PlanningItem>) : BaseAdapter() {

    override fun getCount(): Int {
        return planningItems.size
    }

    override fun getItem(position: Int): Any {
        return planningItems[position]
    }

    override fun getItemId(position: Int): Long {
        return position.toLong()
    }

    override fun getView(position: Int, convertView: View?, parent: ViewGroup?): View {
        val view = convertView ?: LayoutInflater.from(context).inflate(R.layout.item_planning, parent, false)
        val planningItem = planningItems[position]

        view.findViewById<TextView>(R.id.tvDescription).text = planningItem.description
        view.findViewById<TextView>(R.id.tvDate).text = planningItem.date_activite
        view.findViewById<TextView>(R.id.tvActivity).text = planningItem.activity_desc
        view.findViewById<TextView>(R.id.tvAddress).text = planningItem.address?.joinToString(", ") ?: "Aucune adresse"

        return view
    }
}
