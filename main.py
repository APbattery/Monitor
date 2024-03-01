from flask import Flask, jsonify, request
from flask_ngrok import run_with_ngrok
import pymssql

app = Flask(__name__)
run_with_ngrok(app)  # Dodaj tę linijkę, aby uruchomić ngrok z aplikacją

app = Flask(__name__)

# Ustawienia bazy danych - dostosuj do swoich danych
server = '192.168.0.19'
database = 'sl_Autopart'
username = 'jstawarz'
password = 'MAN,too!21'

@app.route('/')
def get_data():
    # Połączenie z bazą danych
    conn = pymssql.connect(server, username, password, database)
    cursor = conn.cursor()

    # Zapytanie SQL
    sql = "SELECT SUM(PWB_Montaz) AS PWB_MontazSum FROM wusr_vv_ap_PlanProdWysBro"
    sql2 = "SELECT celRocznyAkum_ApBatteryMonitor_test AS cel FROM wusr_ap_ApBatteryMonitorCel"

    # Wykonaj pierwsze zapytanie
    cursor.execute(sql)
    result = cursor.fetchone()

    # Wykonaj drugie zapytanie
    cursor.execute(sql2)
    row2 = cursor.fetchone()

    # Zamknij kursor i połączenie z bazą danych
    cursor.close()
    conn.close()

    # Przed utworzeniem słownika, przekonwertuj wartość Decimal na float


    response_data = {'PWB_MontazSum': float(result[0]), 'ThisYearGoal': float(row2[0]) if row2 else "Error"}

    # Ustaw nagłówek Access-Control-Allow-Origin
    response = jsonify(response_data)
    response.headers.add('Access-Control-Allow-Origin',
                         '*')  # Możesz ograniczyć do konkretnego źródła, np. 'http://localhost'

    return response

@app.route('/update_goal', methods=['POST'])
def update_goal():
    new_goal = request.form.get('new_goal')
    try:
        conn = pymssql.connect(server, username, password, database)
        cursor = conn.cursor()

        # Aktualizacja celu w bazie danych
        cursor.execute("UPDATE wusr_ap_ApBatteryMonitorCel SET celRocznyAkum_ApBatteryMonitor_test = %s", (new_goal,))
        conn.commit()

        cursor.close()
        conn.close()

        return "Cel zaktualizowany pomyślnie", 200
    except Exception as e:
        return f"Wystąpił błąd podczas aktualizacji celu: {str(e)}", 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
