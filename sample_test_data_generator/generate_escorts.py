#!/usr/bin/env python3
"""
Escorts Data Generator

This script generates sample data for escorts in Excel format
that can be used with the import functionality of the application.
"""
import pandas as pd
import random
from datetime import datetime, timedelta
import argparse


def generate_sample_data(num_escorts=20):
    """
    Generate sample escorts data for Excel import
    
    Args:
        num_escorts: Number of escorts to generate
    
    Returns:
        escorts_df: DataFrame with escorts data
    """
    
    # Dropdown value mappings based on the actual database values
    genders = [
        {'code': '1', 'name': 'Male'},
        {'code': '2', 'name': 'Female'},
        {'code': '3', 'name': 'notdefined'}
    ]
    
    titles = [
        {'code': '1', 'name': 'Mr'},
        {'code': '2', 'name': 'Ms'},
        {'code': '3', 'name': 'Mrs'},
        {'code': '4', 'name': 'Miss'},
        {'code': '5', 'name': 'Dr.'},
        {'code': '6', 'name': 'Prof.'},
        {'code': 'H.H', 'name': 'HH'}
    ]
    
    internal_rankings = [
        {'code': '1', 'name': 'Second Lieutenant'},
        {'code': '2', 'name': 'First Lieutenant'},
        {'code': '3', 'name': 'Captain'},
        {'code': '4', 'name': 'Major'},
        {'code': '5', 'name': 'Lieutenant Colonel'},
        {'code': '6', 'name': 'Colonel'},
        {'code': '7', 'name': 'Brigadier General'},
        {'code': '8', 'name': 'Major General'},
        {'code': '9', 'name': 'Lieutenant General'},
        {'code': '10', 'name': 'Field Marshal'},
        {'code': '11', 'name': 'Minister of Defence'},
        {'code': '12', 'name': 'Air Force Commanders'}
    ]
    
    units = [
        {'code': '1', 'name': 'Military'},
        {'code': '2', 'name': 'DCA'}
    ]
    
    spoken_languages = [
        {'code': '1', 'name': 'English'},
        {'code': '2', 'name': 'Arabic'},
        {'code': '3', 'name': 'Hindi'}
    ]
    
    # Generate escorts data
    escorts_data = []
    
    # English names
    male_names = ['Mohammed', 'Ahmed', 'Omar', 'Ali', 'Khalid', 'Youssef', 'Karim', 'Hassan', 'Tariq', 'Nasser',
                  'John', 'Robert', 'Michael', 'David', 'James', 'William', 'Richard', 'Thomas', 'Charles', 'Christopher']
    
    female_names = ['Sara', 'Fatima', 'Aisha', 'Layla', 'Noura', 'Reem', 'Maha', 'Hind', 'Amal', 'Dalal',
                   'Mary', 'Jennifer', 'Linda', 'Patricia', 'Elizabeth', 'Barbara', 'Susan', 'Jessica', 'Sarah', 'Karen']
    
    last_names = ['Al-Saud', 'Al-Fahd', 'Al-Thani', 'Al-Maktoum', 'Al-Nahyan', 'Al-Balushi', 'Al-Harthy', 'Al-Zahiri',
                 'Al-Rumhi', 'Al-Busaidi', 'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
                 'Rodriguez', 'Martinez']
    
    # Arabic names
    arabic_male_names = ['محمد', 'أحمد', 'عمر', 'علي', 'خالد', 'يوسف', 'كريم', 'حسن', 'طارق', 'ناصر']
    arabic_female_names = ['سارة', 'فاطمة', 'عائشة', 'ليلى', 'نورا', 'ريم', 'مها', 'هند', 'أمل', 'دلال']
    arabic_last_names = ['السعود', 'الفهد', 'الثاني', 'المكتوم', 'النهيان', 'البلوشي', 'الحرثي', 'الزاهري', 'الrumhi', 'البوسعيدي']
    
    for i in range(1, num_escorts + 1):
        # Randomly select gender
        gender = random.choice(genders)
        
        # Select appropriate name based on gender
        if gender['code'] == '1':  # Male
            first_name_en = random.choice(male_names)
            first_name_ar = random.choice(arabic_male_names)
        elif gender['code'] == '2':  # Female
            first_name_en = random.choice(female_names)
            first_name_ar = random.choice(arabic_female_names)
        else:  # Not defined or other
            first_name_en = random.choice(male_names + female_names)
            first_name_ar = random.choice(arabic_male_names + arabic_female_names)
        
        last_name_en = random.choice(last_names)
        last_name_ar = random.choice(arabic_last_names)
        
        full_name_en = f"{first_name_en} {last_name_en}"
        full_name_ar = f"{first_name_ar} {last_name_ar}"
        
        # Select title based on gender
        if gender['code'] == '1':  # Male
            title = random.choice([t for t in titles if t['code'] in ['1', '5', '6', 'H.H']])
        elif gender['code'] == '2':  # Female
            title = random.choice([t for t in titles if t['code'] in ['2', '3', '4', '5', '6']])
        else:
            title = random.choice(titles)
        
        # Generate military number
        military_number = f"MIL{random.randint(10000, 99999)}"
        
        # Generate phone number (UAE format)
        phone_number = f"971{random.randint(500000000, 599999999)}"
        
        # Generate email
        email = f"{first_name_en.lower()}.{last_name_en.lower()}{random.randint(1, 999)}@example.com"
        
        # Select internal ranking
        internal_ranking = random.choice(internal_rankings)
        
        # Select unit
        unit = random.choice(units)
        
        # Select spoken languages (1-3 languages)
        num_languages = random.randint(1, 3)
        selected_languages = random.sample(spoken_languages, min(num_languages, len(spoken_languages)))
        language_codes = ','.join([lang['code'] for lang in selected_languages])
        
        escort = {
            'name_en': full_name_en,
            'name_ar': full_name_ar,
            'military_number': military_number,
            'phone_number': phone_number,
            'email': email,
            'gender_code': gender['code'],
            'title_en': title['name'],
            'title_ar': 'السيد' if gender['code'] == '1' else 'السيدة' if gender['code'] == '2' else 'غير محدد',
            'internal_ranking_code': internal_ranking['code'],
            'unit_code': unit['code'],
            'spoken_languages_codes': language_codes,
            'note1': f'Note for escort {i}',
            'note2': f'Additional note for escort {i}',
        }
        escorts_data.append(escort)
    
    return pd.DataFrame(escorts_data)


def main():
    parser = argparse.ArgumentParser(description='Generate escorts data for import')
    parser.add_argument('--escorts', type=int, default=20, help='Number of escorts to generate (default: 20)')
    parser.add_argument('--output', type=str, default='escorts_data.xlsx', help='Output Excel file name (default: escorts_data.xlsx)')
    
    args = parser.parse_args()
    
    print(f"Generating {args.escorts} escorts...")
    
    escorts_df = generate_sample_data(args.escorts)
    
    # Create Excel file
    escorts_df.to_excel(args.output, index=False, sheet_name='Escorts')
    
    print(f"Data generated successfully!")
    print(f"Output file: {args.output}")
    print(f"Escorts: {len(escorts_df)} records")
    
    # Print column information for reference
    print("\nEscorts columns:")
    for col in escorts_df.columns:
        print(f"  - {col}")


if __name__ == "__main__":
    main()