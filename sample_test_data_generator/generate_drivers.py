#!/usr/bin/env python3
"""
Drivers Data Generator

This script generates sample data for drivers in Excel format
that can be used with the import functionality of the application.
"""
import pandas as pd
import random
from datetime import datetime, timedelta
import argparse


def generate_sample_data(num_drivers=20):
    """
    Generate sample drivers data for Excel import
    
    Args:
        num_drivers: Number of drivers to generate
    
    Returns:
        drivers_df: DataFrame with drivers data
    """
    
    # Dropdown value mappings based on the actual database values
    units = [
        {'code': '1', 'name': 'Military'},
        {'code': '2', 'name': 'DCA'}
    ]
    
    vehicle_types = [
        {'code': '1', 'name': 'SUV'},
        {'code': '2', 'name': 'Sedan'},
        {'code': '3', 'name': 'Limousine'},
        {'code': '4', 'name': 'Van'},
        {'code': '5', 'name': 'Armored Vehicle'},
        {'code': '6', 'name': 'Luxury Car'},
        {'code': '7', 'name': 'Mini Bus'}
    ]
    
    # Generate drivers data
    drivers_data = []
    
    # English names
    male_names = ['Mohammed', 'Ahmed', 'Omar', 'Ali', 'Khalid', 'Youssef', 'Karim', 'Hassan', 'Tariq', 'Nasser',
                  'John', 'Robert', 'Michael', 'David', 'James', 'William', 'Richard', 'Thomas', 'Charles', 'Christopher']
    
    last_names = ['Al-Saud', 'Al-Fahd', 'Al-Thani', 'Al-Maktoum', 'Al-Nahyan', 'Al-Balushi', 'Al-Harthy', 'Al-Zahiri',
                 'Al-Rumhi', 'Al-Busaidi', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
                 'Rodriguez', 'Martinez']
    
    # Arabic names
    arabic_male_names = ['محمد', 'أحمد', 'عمر', 'علي', 'خالد', 'يوسف', 'كريم', 'حسن', 'طارق', 'ناصر']
    arabic_last_names = ['السعود', 'الفهد', 'الثاني', 'المكتوم', 'النهيان', 'البلوشي', 'الحرثي', 'الزاهري', 'الrumhi', 'البوسعيدي']
    
    # Titles
    titles = [
        {'code': '1', 'name': 'Mr'},
        {'code': '2', 'name': 'Ms'},
        {'code': '3', 'name': 'Mrs'},
        {'code': '4', 'name': 'Miss'},
        {'code': '5', 'name': 'Dr.'},
        {'code': '6', 'name': 'Prof.'},
        {'code': 'H.H', 'name': 'HH'}
    ]
    
    for i in range(1, num_drivers + 1):
        # Generate names
        first_name_en = random.choice(male_names)
        last_name_en = random.choice(last_names)
        full_name_en = f"{first_name_en} {last_name_en}"
        
        first_name_ar = random.choice(arabic_male_names)
        last_name_ar = random.choice(arabic_last_names)
        full_name_ar = f"{first_name_ar} {last_name_ar}"
        
        # Select title
        title = random.choice(titles)
        
        # Generate military number
        military_number = f"MIL{random.randint(10000, 99999)}"
        
        # Generate phone number (UAE format)
        phone_number = f"971{random.randint(500000000, 599999999)}"
        
        # Select vehicle type
        vehicle_type = random.choice(vehicle_types)
        
        # Generate car number (UAE format)
        car_number = f"{random.choice(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Z'])}{random.randint(1000, 9999)}"
        
        # Generate capacity (1-50 passengers)
        capacity = random.randint(1, 50)
        
        # Select unit
        unit = random.choice(units)
        
        driver = {
            'name_en': full_name_en,
            'name_ar': full_name_ar,
            'military_number': military_number,
            'phone_number': phone_number,
            'title_en': title['name'],
            'title_ar': 'السيد',
            'car_type': vehicle_type['name'],
            'car_number': car_number,
            'capacity': capacity,
            'unit_code': unit['code'],
            'note1': f'Note for driver {i}',
            'note2': f'Additional note for driver {i}',
        }
        drivers_data.append(driver)
    
    return pd.DataFrame(drivers_data)


def main():
    parser = argparse.ArgumentParser(description='Generate drivers data for import')
    parser.add_argument('--drivers', type=int, default=20, help='Number of drivers to generate (default: 20)')
    parser.add_argument('--output', type=str, default='drivers_data.xlsx', help='Output Excel file name (default: drivers_data.xlsx)')
    
    args = parser.parse_args()
    
    print(f"Generating {args.drivers} drivers...")
    
    drivers_df = generate_sample_data(args.drivers)
    
    # Create Excel file
    drivers_df.to_excel(args.output, index=False, sheet_name='Drivers')
    
    print(f"Data generated successfully!")
    print(f"Output file: {args.output}")
    print(f"Drivers: {len(drivers_df)} records")
    
    # Print column information for reference
    print("\nDrivers columns:")
    for col in drivers_df.columns:
        print(f"  - {col}")


if __name__ == "__main__":
    main()