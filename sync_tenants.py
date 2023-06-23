#!/usr/bin/env python3
import json
import requests
import pymysql
import base64
from datetime import date
import time
import sys

env = {}
with open('.env', 'r') as file:
    for line in file:
        line = line.strip()
        if line and '=' in line:
            key, value = line.split('=', 1)
            env[key] = value

# print(env)
# sys.exit("Something went wrong. Exiting...")
class CustomJSONEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, date):
            return obj.isoformat()
        return super().default(obj)


def sync_tenants():
    while True:
       
        try:
            db = pymysql.connect(
                host=env.get('HOST'),
                user=env.get('USER'),
                password=env.get('PASSWORD'),
                database=env.get('DATABASE'),
                cursorclass=pymysql.cursors.DictCursor
            )
            cursor = db.cursor()
            # Your sync_tenants logic here
            sql = "SELECT * FROM `tenants` WHERE `sync` = 0 limit 500"

            # Execute the query
           # Execute the SQL query
            cursor.execute(sql)

            # Fetch all the rows returned by the query
            result = cursor.fetchall()
            if not result:
                break
            # Loop through the results and prepare the data
            json_data = []
            for row in result:
                name = row['tenant_name']
                lastid = row['Id']
                physcaddress = row['physcaladdress']

                data = {
                    'CustName': name,
                    'CustId': lastid,
                    'Address': physcaddress,
                    'TaxId': '',
                    'CurrencyCode': 'KS',
                    'SalesType': '1',
                    'CreditStatus': '0',
                    'PaymentTerms': '7',
                    'Discount': '0',
                    'paymentDiscount': '0',
                    'CreditLimit': '0',
                    'Notes': ''
                }

                json_data.append(data)
                print(data)

            # Encode the data as JSON
            if len(json_data) > 0:
                json_str = json.dumps(json_data)

                # Set up the request headers and URL
                url = 'https://techsavanna.technology/river-court-palla/api/endpoints/customers.php?action=add-customer&company-id=RIVER'
                username = 'api-user'
                password = 'admin'
                headers = {
                    'Authorization': 'Basic ' + base64.b64encode((username + ':' + password).encode()).decode(),
                    'Content-Type': 'application/json'
                }

                # Make the request using requests library
                response = requests.post(url, data=json_str, headers=headers)

                if response.status_code == 200:
                    # If the request was successful, update the `sync` column in the database
                    for data in json_data:
                        lastid = data['CustId']
                        cursor.execute(f"UPDATE `tenants` SET `sync` = 1 WHERE `Id` = '{lastid}'")
                        db.commit()
                    message = 'Data synced successfully.'
                else:
                    # If the request failed, log the error message
                    cursor.execute(f"UPDATE `tenants` SET `sync` = -1 WHERE `Id` = '{lastid}'")
                    db.commit()
                    error_msg = response.text
                    message = f"Error syncing data: {error_msg}"
            else:
                message = "No data to sync"

            # Return the result message
                        # Sleep for a certain time before running the loop again
            time.sleep(2)  # Sleep for 60 seconds

        except Exception as e:
            print(f"An error occurred: {str(e)}")
        finally:
            db.close()

# Run the sync_invoices function


# Run the sync_tenants function
print(sync_tenants())
