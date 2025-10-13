import pandas as pd
import random
from datetime import datetime, timedelta
import argparse

def generate_sample_data(num_delegations=300, num_delegates_per_delegation=10, code_prefix="DA28", start_sequence=1):
    """
    Generate sample delegations and delegates data for Excel import
    
    Args:
        num_delegations: Number of delegations to generate
        num_delegates_per_delegation: Number of delegates per delegation
        code_prefix: Static prefix for delegation code (e.g., DA28)
    
    Returns:
        tuple: (delegations_df, delegates_df)
    """
    
    continents = [
        {'code': '1', 'name': 'Asia'},
        {'code': '2', 'name': 'Africa'},
    ]
    
    countries = [
        {'code': '1', 'name': 'Kingdom of Saudi Arabia', 'continent_code': '1'},
        {'code': '1', 'name': 'State of Kuwait', 'continent_code': '1'},
        {'code': '1', 'name': 'Sultanate of Oman', 'continent_code': '1'},
        {'code': '1', 'name': 'State of Qatar', 'continent_code': '1'},
        {'code': '1', 'name': 'Kingdom of Bahrain', 'continent_code': '1'},
        {'code': '2', 'name': 'Egypt Arab Republic', 'continent_code': '2'},
        {'code': '2', 'name': 'Republic of Tunisia', 'continent_code': '2'},
        {'code': '1', 'name': 'Hashimite Kingdom of Jordan', 'continent_code': '1'},
        {'code': '2', 'name': 'Democratic People\'s Republic of Algeria', 'continent_code': '2'},
        {'code': '1', 'name': 'Syrian Arab Republic', 'continent_code': '1'}
    ]
    
    invitation_statuses = [
        {'code': '1', 'name': 'Waiting'},
        {'code': '2', 'name': 'Accepted'},
        {'code': '3', 'name': 'Rejected'},  # Updated to match actual dropdown
        {'code': '10', 'name': 'Accepted with secretary'},
        {'code': '9', 'name': 'Accepted with acting person'}
    ]
    
    participation_statuses = [
        {'code': '1', 'name': 'Not Yet Arrived'},
        {'code': '2', 'name': 'Partially Arrived'},
        {'code': '3', 'name': 'Arrived'},
        {'code': '4', 'name': 'Partially Departured'},
        {'code': '5', 'name': 'Departured'}
    ]
    
    genders = [
        {'code': '1', 'name': 'Male'},
        {'code': '2', 'name': 'Female'},
        {'code': '3', 'name': 'notdefined'}  # Added from actual dropdown
    ]
    
    relationships = [
        {'code': '1', 'name': 'Father'},
        {'code': '2', 'name': 'Mother'},
        {'code': '3', 'name': 'Son'},
        {'code': '4', 'name': 'Daughter'},
        {'code': '5', 'name': 'Husband'},
        {'code': '6', 'name': 'Wife'},
        {'code': '7', 'name': 'Brother'},
        {'code': '8', 'name': 'Sister'},
        {'code': '9', 'name': 'Grandfather'},
        {'code': '10', 'name': 'Grandmother'},
        {'code': '11', 'name': 'Uncle'},
        {'code': '12', 'name': 'Aunt'},
        {'code': '13', 'name': 'Cousin'},
        {'code': '14', 'name': 'Legal Guardian'},
        {'code': '15', 'name': 'Friend'},
        {'code': '16', 'name': 'Other'}
    ]
    
    titles = [
        {'code': '1', 'name': 'Mr'},  # Updated to match actual dropdown
        {'code': '2', 'name': 'Ms'},
        {'code': '3', 'name': 'Mrs'},
        {'code': '4', 'name': 'Miss'},  # Added from actual dropdown
        {'code': '5', 'name': 'Dr.'},
        {'code': '6', 'name': 'Prof.'},
        {'code': 'H.H', 'name': 'HH'}  # Added from actual dropdown
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
        {'code': '11', 'name': 'Minister of Defence'},  # Added from actual dropdown
        {'code': '12', 'name': 'Air Force Commanders'}  # Added from actual dropdown
    ]
    
    # Generate invitation_from data (from departments dropdown)
    invitation_froms = [
        {'code': '1', 'name': 'Ministry of Defence (MOD)'},
        {'code': '2', 'name': 'UAE Land Forces'},
        {'code': '3', 'name': 'UAE Air Force and Air Defence'},
        {'code': '4', 'name': 'UAE Navy'},
        {'code': '5', 'name': 'National Guard'},
        {'code': '6', 'name': 'Presidential Guard Command'},
        {'code': '7', 'name': 'Joint Aviation Command'},
        {'code': '8', 'name': 'Military Intelligence'},
        {'code': '9', 'name': 'MOD Procurement Division'},
        {'code': '10', 'name': 'MOD Engineering & Infrastructure'},
        {'code': '11', 'name': 'Navy'},  # Added from actual dropdown
        {'code': '12', 'name': 'MOD Human Resources'}  # Added from actual dropdown
    ]
    
    
    # Generate delegations data
    delegations_data = []
    for i in range(1, num_delegations + 1):
        continent = random.choice(continents)
        continent_countries = [c for c in countries if c['continent_code'] == continent['code']]
        country = random.choice(continent_countries) if continent_countries else random.choice(countries)
        invitation_from = random.choice(invitation_froms)
        
        # Generate delegation code using prefix and sequence
        delegation_code = f"{code_prefix}-{i:04d}"
        import_code = i
        
        delegation = {
            'code': delegation_code, 
            'import_code': import_code,
            'invitation_from_code': invitation_from['code'],
            'continent_code': continent['code'],
            'country_code': country['code'],
            'invitation_status_code': random.choice(invitation_statuses)['code'],
            'participation_status_code': random.choice(participation_statuses)['code'],
            'note1': f'Note for delegation {i}',
            'note2': f'Additional note for delegation {i}',
        }
        delegations_data.append(delegation)
    
    # Generate delegates data
    delegates_data = []
    delegate_counter = 1
    
    for delegation in delegations_data:
        delegation_code = delegation['code']
        
        for j in range(num_delegates_per_delegation):
            gender = random.choice(genders)
            relationship = random.choice(relationships)
            title = random.choice(titles)
            internal_ranking = random.choice(internal_rankings)
            
            first_names = ['Mohammed', 'Ahmed', 'Omar', 'Ali', 'Khalid', 'Sara', 'Fatima', 'Aisha', 'Layla', 'Noura',
                          'John', 'Robert', 'Michael', 'David', 'James', 'Mary', 'Jennifer', 'Linda', 'Patricia', 'Elizabeth']
            last_names = ['Al-Saud', 'Al-Fahd', 'Al-Thani', 'Al-Maktoum', 'Smith', 'Johnson', 'Williams', 'Brown', 
                         'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez']
            
            first_name = random.choice(first_names)
            last_name = random.choice(last_names)
            full_name = f"{first_name} {last_name}"
            
            arabic_first_names = ['محمد', 'أحمد', 'عمر', 'علي', 'خالد', 'سارة', 'فاطمة', 'عائشة', 'ليلى', 'نورا']
            arabic_last_names = ['السعود', 'الفهد', 'الثاني', 'المكتوم', 'الملك', 'النمر', 'الراشد', 'الغامدي']
            arabic_full_name = f"{random.choice(arabic_first_names)} {random.choice(arabic_last_names)}"
            
            designations = ['Minister', 'Ambassador', 'Director', 'Advisor', 'Deputy Minister', 'Counselor', 
                           'Attaché', 'Consul', 'Representative', 'Head of Delegation', 'Deputy Head of Delegation',
                           'Technical Advisor', 'Policy Advisor', 'Legal Advisor', 'Economic Advisor']
            designation_en = random.choice(designations)
            designation_ar = 'وزير' if 'Minister' in designation_en else 'سفير' if 'Ambassador' in designation_en else 'ممثل'
            
            delegate_code = f"{delegation_code}-D{j+1:02d}"
            
            delegate = {
                'import_code': delegation['import_code'],
                'delegate_title_en': random.choice([t['name'] for t in titles]),
                'delegate_title_ar': 'السيد' if gender['name'] == 'Male' else 'السيدة',
                'delegate_name_en': full_name,
                'delegate_name_ar': arabic_full_name,
                'delegate_gender_code': gender['code'],
                'delegate_designation_en': designation_en,
                'delegate_designation_ar': designation_ar,
                'delegate_note': f'Note for delegate {delegate_counter}',
                'delegate_relationship_code': relationship['code'],
                'delegate_internal_ranking_code': internal_ranking['code'],
                'delegate_team_head': 'Yes' if j == 0 else 'No',
                'delegate_accommodation': 'Yes' if random.choice([True, False, False]) else 'No',
                'delegate_badge_printed': 'No',
                'delegate_parent_code': None,
            }
            
            if j > 0 and random.choice([True, False]):
                delegate['delegate_parent_code'] = f"{delegation_code}-D{random.randint(1, j):02d}"
            
            # Arrival and departure info same as before...
            if random.choice([True, False]):
                arrival_date = (datetime.now() + timedelta(days=random.randint(1, 10))).strftime('%Y-%m-%d %H:%M:%S')
                delegate.update({
                    'arrival_mode': 'flight',
                    'arrival_airport_code': f'{random.choice(["1", "2", "3", "4", "5", "6"])}',
                    'arrival_flight_no': f'{random.choice(["EK", "EY", "AA", "BA", "AF"])}{random.randint(100, 999)}',
                    'arrival_flight_name': f'Flight {random.randint(100, 999)}',
                    'arrival_date_time': arrival_date,
                    'arrival_comment': f'Arrival comment for delegate {delegate_counter}'
                })
            
            if random.choice([True, False]):
                departure_date = (datetime.now() + timedelta(days=random.randint(11, 20))).strftime('%Y-%m-%d %H:%M:%S')
                delegate.update({
                    'departure_mode': 'flight',
                    'departure_airport_code': f'{random.choice(["1", "2", "3", "4", "5", "6"])}',
                    'departure_flight_no': f'{random.choice(["EK", "EY", "AA", "BA", "AF"])}{random.randint(100, 999)}',
                    'departure_flight_name': f'Flight {random.randint(100, 999)}',
                    'departure_date_time': departure_date,
                    'departure_comment': f'Departure comment for delegate {delegate_counter}'
                })
            
            delegates_data.append(delegate)
            delegate_counter += 1
    
    return pd.DataFrame(delegations_data), pd.DataFrame(delegates_data)

def main():
    parser = argparse.ArgumentParser(description='Generate delegations and delegates data for import')
    parser.add_argument('--delegations', type=int, default=10, help='Number of delegations to generate')
    parser.add_argument('--delegates-per-delegation', type=int, default=5, help='Number of delegates per delegation')
    parser.add_argument('--prefix', type=str, default='DA28', help='Delegation code prefix (e.g., DA28)')
    parser.add_argument('--start-sequence', type=int, default=1, help='Starting sequence number for delegation codes')
    parser.add_argument('--output', type=str, default='delegations_delegates_data.xlsx', help='Output Excel file name')

    args = parser.parse_args()

    print(f"Generating {args.delegations} delegations with {args.delegates_per_delegation} delegates each...")
    print(f"Using code prefix: {args.prefix} (starting from {args.start_sequence:04d})")

    delegations_df, delegates_df = generate_sample_data(
        args.delegations,
        args.delegates_per_delegation,
        args.prefix,
        args.start_sequence
    )

    with pd.ExcelWriter(args.output, engine='openpyxl') as writer:
        delegations_df.to_excel(writer, sheet_name='Delegations', index=False)
        delegates_df.to_excel(writer, sheet_name='Delegates', index=False)

    print(f"✅ Data generated successfully!")
    print(f"📁 Output file: {args.output}")
    print(f"🧾 Delegations: {len(delegations_df)} records")
    print(f"👥 Delegates: {len(delegates_df)} records")


if __name__ == "__main__":
    main()
