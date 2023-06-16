import requests
import json
import pymysql
import base64
from datetime import date
import time
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
    query = "SELECT tno,idno,rdate,invoicenopaid, rmks, amount FROM recptrans WHERE sync = 0 limit 10"

    # Execute the SQL query
    cursor.execute(query)
    
    # Fetch all the rows returned by the query
    result = cursor.fetchall()
    if not result:
        break
    # Print the fetched data
    request=[]
    invoiceno=[]
    for row in result:
       
        receiptdate = row["rdate"]
        reference = row["invoicenopaid"]
        recpamount = row["amount"]
        customerId=row["idno"]
        tno=row["tno"]
        invoiceno.append(tno)
        datereceipt = receiptdate
        data2 = {
            "CustId": customerId,
            "TransactionRef": reference,
            "TransDate":datereceipt,
            "BankAcct": 20000001,
            "Amount": recpamount
        }
        
        request.append(data2)
    request = json.dumps(request, cls=CustomJSONEncoder)  # Use CustomJSONEncoder
    username = "api-user"
    password = "admin"
    headers = {
        'Authorization': 'Basic ' + base64.b64encode((username + ':' + password).encode()).decode()
    }
  
    url = "https://techsavanna.technology/river-court-palla/api/endpoints/payment.php?action=make-payment-bulk&company-id=RIVER"

    response = requests.post(url, data=request, headers=headers)
    
    if response.status_code == 200:
        response_text = response.text.strip()
    
        data_str = response_text.replace('}][{', '},{')
       
        json_array= json.loads(data_str)
        print(json_array)

        # Process each array separately
        for index, response in enumerate(json_array):
            print(response)
            # Accessing the response data
            if response:
                status = response['Status']
                success = response['Success']
                payment_id = response['id']

                if status == 'ok' and success == 'Payment added':
                    print('Payment added successfully. Payment ID:', payment_id)
                    
                    # Perform necessary database operations
                    cursor.execute(f"UPDATE `recptrans` SET `sync` = 1 WHERE `tno` = '{invoiceno[index]}'")
                    db.commit()
                else:
                    print('Payment addition failed.')
            else:
                print('Invalid response format.')
        time.sleep(10)
      