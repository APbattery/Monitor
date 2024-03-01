#!/usr/bin/env python3
import cgi
import json
import pymssql

# Ustawienia bazy danych - dostosuj do swoich danych
server = '192.168.0.19'
database = 'sl_Autopart'
username = 'jstawarz'
password = 'MAN,too!21'

# Połączenie z bazą danych
conn = pyodbc.connect('DRIVER={ODBC Driver 17 for SQL Server};SERVER='+server+';DATABASE='+database+';UID='+username+';PWD='+ password)

# Utwórz obiekt kursora
cursor = conn.cursor()

# Zapytanie SQL
sql = "SELECT SUM(PWB_Montaz) AS PWB_MontazSum FROM wusr_vv_ap_PlanProdWysBro"

# Wykonaj zapytanie
cursor.execute(sql)

# Pobierz wynik
result = cursor.fetchone()

# Zamknij kursor i połączenie z bazą danych
cursor.close()
conn.close()

# Utwórz słownik zawierający wynik
response_data = {'PWB_MontazSum': result[0]}

# Zwróć wynik jako JSON
print("Content-Type: application/json\n")
print(json.dumps(response_data))
