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
# def get_credit_amount(prop_id):
#     cursor = db.cursor()
#     query = "SELECT * FROM properties WHERE sync='0' LIMIT 500"
#     cursor.execute(query)
#     invoices = cursor.fetchall()

def sync_invoices():
    
    
    while True:   
        db = pymysql.connect(
        host= env.get('HOST'),
        user= env.get('USER'),
        password= env.get('PASSWORD'),
        database= env.get('DATABASE'),
        cursorclass=pymysql.cursors.DictCursor
        )
        cursor = db.cursor()
        query = "SELECT * FROM invoices WHERE sync='0' LIMIT 500"
        cursor.execute(query)
        invoices = cursor.fetchall()
        if not invoices:
            break
        final_data = []
        invoices_data = []
        # print(invoices)
        for invoice in invoices:
            id = invoice['idno']
            invoiceno = result2 = invoice['invoiceno']
            invoiceamount = invoice['amount']
            creditamount = invoice['creditamount']
            remarks = invoice['remarks']
            tablename = "tenants"
            entrydate = invoice['invoicedate']

            queryy = f"SELECT tenant_name FROM {tablename} WHERE id='{id}'"
            cursor.execute(queryy)
            rowtenant = cursor.fetchone()

            tnt = rowtenant['tenant_name']

            if creditamount > 0:
                items = [
                    {
                        'account_code': 30000001,
                        'amount': invoiceamount - creditamount,
                        'memo': id
                    },
                    {
                        'account_code': 40000001,
                        'amount': creditamount,
                        'memo': remarks
                    },
                    {
                        'account_code': 20000001,
                        'amount': invoiceamount,
                        'memo': remarks
                    }
                ]
            else:
                items = [
                    {
                        'account_code': 30000001,
                        'amount': invoiceamount,
                        'memo': remarks
                    },
                    {
                        'account_code': 20000001,
                        'amount': invoiceamount,
                        'memo': remarks
                    }
                ]

            json2 = {
                'checkoutid': invoiceno,
                'currency': 'KS',
                'source_ref': 30000001,
                'reference': 30000001,
                'memo': "Rent invoice",
                'amount': invoiceamount,
                'bank_act': 20000001,
                'items': items
            }

            final_data.append(json2)

            itms = [
                {
                    'product_id': 1,
                    'product_code': 1000,
                    'product_name': "Landlord amount and Chargables",
                    'unit_price': invoiceamount - creditamount,
                    'quantity': 1
                }
            ]

            items2 = [
                {
                    'product_id': 2,
                    'product_code': 1005,
                    'product_name': "Agent Commission",
                    'unit_price': creditamount,
                    'quantity': 1
                }
            ]

            itms.extend(items2)
            itm = itms

            data2 = {
                'InvoiceNo': result2,
                'CustId': id,
                'RefNo': result2,
                'comments': 'some comment',
                'OrderDate': entrydate,
                'DeliverTo': tnt,
                'DeliveryAddress': "River",
                'DeliveryCost': '0',
                'DeliveryDate': entrydate,
                'InvoiceTotal': invoiceamount,
                'agentamount': creditamount,
                'DueDate': entrydate,
                'items': itm
            }

            invoices_data.append(data2)

        invoices_data = json.dumps(invoices_data, cls=CustomJSONEncoder)
        json_data2 = json.dumps(final_data, cls=CustomJSONEncoder)

        headers = {
            'Authorization': 'Basic ' + base64.b64encode(b'api-user:admin').decode('utf-8')
        }

        url2 = "https://techsavanna.technology/river-court-palla/api/endpoints/journal.php?action=add-journal-bulk&company-id=RIVER"
        response2 = requests.post(url2, data=json_data2, headers=headers)
        response2 = response2.json()
        
        for response in response2:
            print(response)
            invoiceno = response['checkoutid']
            new_id2 = response['response']['id']

            if new_id2:
                update_query = "UPDATE invoices SET sync=1 WHERE invoiceno='%s'" % invoiceno
                response = cursor.execute(update_query)
                db.commit()
                print(response)
                response = {"success": "1", "message": "Success"}
            else:
                update_query = "UPDATE invoices SET sync=2 WHERE invoiceno='%s'" % invoiceno
                response = cursor.execute(update_query)
                db.commit()
                response = {"success": "0", "message": "Failed to add invoice to ERP"}

        url = "https://techsavanna.technology/river-court-palla/api/endpoints/invoice.php?action=add-invoice&company-id=RIVER"
        response = requests.post(url, data=invoices_data, headers=headers)

        db.close()
        time.sleep(5)
        # print(response.text)

sync_invoices()
