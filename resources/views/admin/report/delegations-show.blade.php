@extends('layouts.admin_account', ['title' => __db('delegation_details')])

@section('content')
<div class="font-sans p-6 bg-white">

  <!-- Header Logos and Titles -->
  <div class="flex items-center justify-between mb-5">
    <!-- Right Logo -->
    <img src="dubai-airshow-logo.png" alt="Dubai Airshow Logo" class="h-12 mr-4">

    <!-- Center Titles -->
    <div class="flex-1 text-center">
      <div class="text-xl font-bold">United Arab Emirates</div>
      <div class="text-lg">Ministry of Defence</div>
      <div class="text-xl font-bold text-[#a12b2e] mt-2">Report Name</div>
    </div>

    <!-- Left Logo -->
    <img src="uae-logo.png" alt="UAE Logo" class="h-16 ml-4">
  </div>

  <hr class="border-t-4 border-black mb-6">

  <!-- Accommodation & Escort Info -->
  <div class="flex justify-between mb-5">
    <div class="text-right">
        <span class="font-bold">Escort:</span> Major Ahmed Ali Al Suwaidi-1001 <span> &nbsp; &nbsp; &nbsp;
        <span class="font-bold">Mobile :</span>050-1234567 </span> &nbsp;<br>
        <span class="mr-10">Capt Noora Salem Al Nuami-1002</span>
      
        <span class="font-bold">Mobile :</span>050-1234567 </span> &nbsp;<br>
    </div>

    <div>
      PR006-Armani Hotel <span class="font-bold">:Accommodation</span><br>
      PR007-Armani Hotel <span class="font-bold">:Accommodation</span>
    </div>
    
  </div>

  <!-- Invitation Info -->
  <div class="flex justify-between mb-6">
    <div>
      Navy <span class="font-bold">:Invitation From</span><br>
      Arrived <span class="font-bold">:Participation Status</span>
    </div>
    <div class="text-right">
      Oman <span class="font-bold">:Country</span><br>
      Accepted <span class="font-bold">:Invitation Status</span>
    </div>
  </div>

  <!-- Arrivals Table -->
  <div class="mb-6">
    <div class="text-lg font-bold text-center mb-3">Arrivals Details</div>
    <table class="w-full border-collapse border-2 border-black text-sm text-right">
      <thead>
        <tr class="bg-gray-300 text-base">
          <th class="border-2 border-black px-3 py-2">Time</th>
          <th class="border-2 border-black px-3 py-2">Date</th>
          <th class="border-2 border-black px-3 py-2">Airport</th>
          <th class="border-2 border-black px-3 py-2">Flight NUM</th>
          <th class="border-2 border-black px-3 py-2">Flight Name</th>
          <th class="border-2 border-black px-3 py-2">Room</th>
          <th class="border-2 border-black px-3 py-2">Position</th>
          <th class="border-2 border-black px-3 py-2">Delegations</th>
          <th class="border-2 border-black px-3 py-2">Ser</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border-2 border-black px-3 py-2">14:00</td>
          <td class="border-2 border-black px-3 py-2">17-11-2025</td>
          <td class="border-2 border-black px-3 py-2">DXB</td>
          <td class="border-2 border-black px-3 py-2">OMA005</td>
          <td class="border-2 border-black px-3 py-2">Oman Air</td>
          <td class="border-2 border-black px-3 py-2">S001</td>
          <td class="border-2 border-black px-3 py-2">Commander of the Navy</td>
          <td class="border-2 border-black px-3 py-2 text-red-600 font-bold">Lt.Colonel-Saif bin Nasser</td>
          <td class="border-2 border-black px-3 py-2">1</td>
        </tr>
        <tr>
          <td class="border-2 border-black px-3 py-2">14:00</td>
          <td class="border-2 border-black px-3 py-2">17-11-2025</td>
          <td class="border-2 border-black px-3 py-2">DXB</td>
          <td class="border-2 border-black px-3 py-2">OMA005</td>
          <td class="border-2 border-black px-3 py-2">Oman Air</td>
          <td class="border-2 border-black px-3 py-2">S001</td>
          <td class="border-2 border-black px-3 py-2">Wife of Commander</td>
          <td class="border-2 border-black px-3 py-2">Ms. Huda bin Salem</td>
          <td class="border-2 border-black px-3 py-2">2</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Departure Table -->
  <div class="mb-6">
    <div class="text-lg font-bold text-center mb-3">Departure Details of Head of Delegation</div>
    <table class="w-full border-collapse border-2 border-black text-sm text-right">
      <thead>
        <tr class="bg-gray-300 text-base">
          <th class="border-2 border-black px-3 py-2">Time</th>
          <th class="border-2 border-black px-3 py-2">Date</th>
          <th class="border-2 border-black px-3 py-2">Airport</th>
          <th class="border-2 border-black px-3 py-2">Flight NUM</th>
          <th class="border-2 border-black px-3 py-2">Flight Name</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="border-2 border-black px-3 py-2">22:00</td>
          <td class="border-2 border-black px-3 py-2">19-11-2025</td>
          <td class="border-2 border-black px-3 py-2">DXB</td>
          <td class="border-2 border-black px-3 py-2">OMA007</td>
          <td class="border-2 border-black px-3 py-2">Oman Air</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Hotel & Contacts -->
  <div class="flex justify-between my-9">
    <div class="max-w-sm text-right">
      04-1234567 <span class="font-bold">:Hotel Number</span><br>
      050-1234567 <span class="font-bold">:Mobile</span><br>
      050-1234567 <span class="font-bold">:Mobile</span>
    </div>
    <div class="max-w-md text-right">
      Armani <span class="font-bold">:Hotel</span><br>
      Mr.Darwish Ali <span class="font-bold">:Res1</span><br>
      Mr.Majid Ali <span class="font-bold">:Res1</span>
    </div>
  </div>

  <hr class="border-t-4 border-black my-7">

  <!-- Drivers & Interviews -->
  <div class="flex justify-between mb-2">
    <div class="text-lg font-bold">Drivers</div>
    <div class="text-lg font-bold text-right">Interviews</div>
  </div>

  <div class="flex">
    <!-- Drivers Table -->
    <div class="pr-5 w-1/2">
      <table class="w-full border-collapse border-2 border-black text-sm text-right">
        <thead>
          <tr class="bg-gray-300">
            <th class="border-2 border-black px-2 py-1">Car Number</th>
            <th class="border-2 border-black px-2 py-1">Car Type</th>
            <th class="border-2 border-black px-2 py-1">Mobile</th>
            <th class="border-2 border-black px-2 py-1">Driver Name</th>
            <th class="border-2 border-black px-2 py-1">Ser</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border-2 border-black px-2 py-1">J-1234</td>
            <td class="border-2 border-black px-2 py-1">GMC</td>
            <td class="border-2 border-black px-2 py-1">050-1234567</td>
            <td class="border-2 border-black px-2 py-1">Mr.Shehab Ahmed Ali</td>
            <td class="border-2 border-black px-2 py-1">1</td>
          </tr>
          <tr>
            <td class="border-2 border-black px-2 py-1">M-3242</td>
            <td class="border-2 border-black px-2 py-1">Nissan</td>
            <td class="border-2 border-black px-2 py-1">050-1234567</td>
            <td class="border-2 border-black px-2 py-1">Mr.Othman Abdullah</td>
            <td class="border-2 border-black px-2 py-1">2</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Interviews Table -->
    <div class="pl-5 w-1/2">
      <table class="w-full border-collapse border-2 border-black text-sm text-right">
        <thead>
          <tr class="bg-gray-300">
            <th class="border-2 border-black px-2 py-1">Notes</th>
            <th class="border-2 border-black px-2 py-1">Time</th>
            <th class="border-2 border-black px-2 py-1">Date</th>
            <th class="border-2 border-black px-2 py-1">Interview request with</th>
            <th class="border-2 border-black px-2 py-1">Ser</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="border-2 border-black px-2 py-1">in Airshow venu</td>
            <td class="border-2 border-black px-2 py-1">10:00</td>
            <td class="border-2 border-black px-2 py-1">18-11-2025</td>
            <td class="border-2 border-black px-2 py-1">Commander of UAE Navy</td>
            <td class="border-2 border-black px-2 py-1">1</td>
          </tr>
          <tr>
            <td class="border-2 border-black px-2 py-1">Note yet confirmed</td>
            <td class="border-2 border-black px-2 py-1">11:00</td>
            <td class="border-2 border-black px-2 py-1">19-11-2025</td>
            <td class="border-2 border-black px-2 py-1">Deputy Naval Operations</td>
            <td class="border-2 border-black px-2 py-1">2</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection