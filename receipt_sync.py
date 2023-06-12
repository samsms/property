import requests
import json
import pymysql
import base64
from datetime import date

env_file = '.env'

class CustomJSONEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, date):
            return obj.isoformat()
        return super().default(obj)

# Open and read the .env file
env = {}
with open('.env', 'r') as file:
    for line in file:
        line = line.strip()
        if line and '=' in line:
            key, value = line.split('=', 1)
            env[key] = value
while True:  
    db = pymysql.connect(
        host=env.get('HOST'),
        user=env.get('USER'),
        password=env.get('PASSWORD'),
        database=env.get('DATABASE'),
        cursorclass=pymysql.cursors.DictCursor
    )
    cursor = db.cursor()
    # Create a cursor to execute SQL queries with DictCursor
    # Define the SQL query
    query = "SELECT idno,rdate, rmks, amount FROM recptrans WHERE sync = 0 limit 500"

    # Execute the SQL query
    cursor.execute(query)
    
    # Fetch all the rows returned by the query
    result = cursor.fetchall()
    if not result:
        break
    # Print the fetched data
    for row in result:

        receiptdate = row["rdate"]
        reference = row["rmks"]
        recpamount = row["amount"]
        customerId=row["idno"]
        datereceipt = receiptdate
        data2 = {
            "CustId": customerId,
            "TransactionRef": customerId,
            "TransDate":datereceipt,
            "BankAcct": 20000001,
            "Amount": recpamount
        }
        # print(data2)
        json_data = json.dumps([data2], cls=CustomJSONEncoder)  # Use CustomJSONEncoder
        username = "api-user"
        password = "admin"
        headers = {
            'Authorization': 'Basic ' + base64.b64encode((username + ':' + password).encode()).decode()
        }

        url = "https://techsavanna.technology/river-court-palla/api/endpoints/payment.php?action=make-payment&company-id=RIVER"

        response = requests.post(url, data=json_data, headers=headers)
        print(response.text)
        break
