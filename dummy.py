import mysql.connector
from mysql.connector import Error

# Database configuration
config = {
    'user': 'root',
    'password': '',
    'host': 'localhost',
    'database': 'lms_server'
}

# Tables you want to modify
tables_to_modify = ['student', 'class', 'section','parents','sections_allocation','enroll','student_attendance','homework','homework_evaluation','homework_submit','subject','subject_assign']  # Replace with your actual table names

try:
    # Establish a database connection
    connection = mysql.connector.connect(**config)
    
    if connection.is_connected():
        cursor = connection.cursor()
        
        for table_name in tables_to_modify:
            # Check if the table exists
            cursor.execute(f"SHOW TABLES LIKE '{table_name}'")
            result = cursor.fetchone()
            
            if result:
                # Check if the column already exists
                cursor.execute(f"SHOW COLUMNS FROM {table_name} LIKE 'is_delete'")
                column_exists = cursor.fetchone()
                
                if not column_exists:
                    # Add the new column
                    cursor.execute(f"ALTER TABLE {table_name} CHANGE at_delete is_delete timestamp NULL")
                    print(f"Column 'at_delete' added to {table_name}")
                else:
                    print(f"Column 'at_delete' already exists in {table_name}")
            else:
                print(f"Table {table_name} does not exist in the database.")
        
        # Commit the changes
        connection.commit()

except Error as e:
    print(f"Error: {e}")

finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")
