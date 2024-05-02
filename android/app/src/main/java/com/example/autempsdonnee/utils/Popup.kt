package com.example.autempsdonnee.utils

import android.app.AlertDialog
import android.widget.Toast
import android.content.Context

class Popup {

    fun makeToast(context: Context, message: String, long: Boolean = false) {
        Toast.makeText(context, message, if (long) Toast.LENGTH_LONG else Toast.LENGTH_SHORT).show()
    }

    /**
     * Affiche un message et attend une confirmation de l'utilisateur :
     * showConfirmationDialog(Context, "Question") {
     *     Si oui
     * } {
     *     Si non
     * }
     */
    fun showConfirmationDialog(context: Context, message: String, onConfirm: () -> Unit, onCancel: () -> Unit) {
        val builder = AlertDialog.Builder(context)
        builder.setMessage(message)
            .setPositiveButton("Oui") { _, _ -> onConfirm() }
            .setNegativeButton("Non") { _, _ -> onCancel() }
            .create()
            .show()
    }

    /**
     * Affiche un message :
     * showInformationDialog(Context, message)
     */
    fun showInformationDialog(context: Context, message: String) {
        val builder = AlertDialog.Builder(context)
        builder.setMessage(message)
            .setPositiveButton("OK") { dialog, _ ->
                dialog.dismiss()
            }
            .create()
            .show()
    }
}