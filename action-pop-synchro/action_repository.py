import mysql.connector
import os
import datetime
from datetime import datetime
from pytz import timezone

def create_connection():
        DB_HOST = os.getenv('DB_HOST')
        DB_DATABASE = os.getenv('DB_DATABASE')
        DB_USERNAME = os.getenv('DB_USERNAME')
        DB_PASSWORD = os.getenv('DB_PASSWORD')
        return mysql.connector.connect(host=DB_HOST, database=DB_DATABASE, user=DB_USERNAME, password=DB_PASSWORD)

def insert_action(action):
    try:
        connection = create_connection()
        insert_query = """INSERT INTO actions (created_at, updated_at, group_id, user_id, name, body, start, stop, location, latitude, longitude, external_ref) 
                            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s) """
        cursor = connection.cursor()
        current_time = datetime.now()
        cursor.execute(insert_query, (current_time, current_time) + action)
        connection.commit()
        cursor.close()

    except mysql.connector.Error as error:
        print("Failed to insert record into actions table {}".format(error))

    finally:
        if connection.is_connected():
            connection.close()

def update_action(action):
    try:
        connection = create_connection()
        update_query = "UPDATE actions SET updated_at = %s, group_id = %s, user_id = %s, name = %s, body = %s, start = %s, stop = %s, location = %s, latitude = %s, longitude = %s WHERE external_ref = %s"
        cursor = connection.cursor()
        current_time = datetime.now()
        cursor.execute(update_query, (current_time,) + action)
        connection.commit()
        cursor.close()

    except mysql.connector.Error as error:
        print("Failed to insert record into actions table {}".format(error))

    finally:
        if connection.is_connected():
            connection.close()

def get_ga_config_to_sync():
    try:
        connection = create_connection()
        cursor = connection.cursor()
        cursor.execute("SELECT id, user_id, ga_id FROM `groups` where ga_id is not null")
        ga_to_sync = cursor.fetchall()
        cursor.close()
        return ga_to_sync
    finally:
        if connection.is_connected():
            connection.close()

def get_action_by_external_ref(external_ref):
    try:
        connection = create_connection()
        cursor = connection.cursor()
        cursor.execute("""SELECT group_id, user_id, name, body, start, stop, location, latitude, longitude, external_ref FROM `actions` where external_ref = (%s)""", (external_ref,))
        action = cursor.fetchone()
        cursor.close()
        if action == None:
            return None
        else:
            action_list = list(action)
            action_list[4] = str(action[4])
            action_list[5] = str(action[5])
            return tuple(action_list)
    finally:
        if connection.is_connected():
            connection.close()