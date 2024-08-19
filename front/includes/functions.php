<?php


function createDateField(){

    echo ('
    <div class="containerDate">
            <div class="testDate">
                <div class="date-field">
                    <label for="start-date">Date de début :</label>
                    <input type="date" id="start-date" class="date-input" onchange="startDateChange()" required>
                </div>
                <div class="date-field">
                    <label for="end-date">Date de fin :</label>
                    <input type="date" id="end-date" class="date-input" onchange="endDateChange()" required>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        
            function setDefaultDates() {
                const today = new Date();
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1); // Définit demain

                // Formate les dates au format YYYY-MM-DD
                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, "0"); // Mois de 0 à 11
                    const day = String(date.getDate()).padStart(2, "0");
                    return `${year}-${month}-${day}`;
                };

                // Définit les valeurs des champs de date
                document.getElementById("start-date").value = formatDate(today);
                document.getElementById("end-date").value = formatDate(tomorrow);
            }

            setDefaultDates()
            </script>

        <style>

            .containerDate {
        background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                padding: 20px;
                width: 100%;
                box-sizing: border-box;
                margin-bottom: 10px;
            }

            .testDate{
        display: flex;
        justify-content: space-evenly;
            }

            .date-field {
        margin-bottom: 15px;
                width: 20%;
            }

            label {
        display: block;
        margin-bottom: 5px;
                font-weight: bold;
                color: #555;
            }

            .date-input {
        width: 100%;
        padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 16px;
                transition: border-color 0.3s;
                box-sizing: border-box;
            }

            .date-input:focus {
        border-color: #007bff;
                outline: none;
            }

            .submit-button {
        width: 100%;
        padding: 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .submit-button:hover {
        background-color: #0056b3;
            }

        </style>');
    
}