<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buyer;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = ['USA', 'UK', 'Germany', 'France', 'Italy', 'Spain', 'Netherlands', 'Sweden', 'Denmark', 'Canada', 'Australia', 'Japan', 'South Korea', 'China', 'UAE', 'Saudi Arabia'];

        $brandNames = [
            'USA' => ['Nike', 'Gap', 'Levi\'s', 'Tommy Hilfiger', 'Calvin Klein', 'Ralph Lauren', 'Michael Kors'],
            'UK' => ['Burberry', 'Topshop', 'River Island', 'Next', 'Marks & Spencer', 'ASOS'],
            'Germany' => ['Adidas', 'Puma', 'Hugo Boss', 'Zalando'],
            'France' => ['Chanel', 'Louis Vuitton', 'Dior', 'Lacoste'],
            'Italy' => ['Gucci', 'Prada', 'Armani', 'Versace', 'Benetton'],
            'Spain' => ['Zara', 'Mango', 'Massimo Dutti', 'Pull & Bear'],
            'Netherlands' => ['C&A', 'G-Star RAW'],
            'Sweden' => ['H&M'],
            'Denmark' => ['Bestseller', 'Only'],
            'Canada' => ['Lululemon', 'Roots'],
            'Australia' => ['Cotton On', 'Bonds'],
            'Japan' => ['Uniqlo', 'Muji'],
            'South Korea' => ['Hyundai Department Store', 'Shinsegae'],
            'China' => ['Anta', 'Li-Ning'],
            'UAE' => ['Splash', 'Max'],
            'Saudi Arabia' => ['Centrepoint', 'RedTag']
        ];

        $garmentTypes = ['Casual Wear', 'Sportswear', 'Formal Wear', 'Children\'s Wear', 'Lingerie', 'Denim', 'Knitwear', 'Outerwear', 'Uniforms', 'Accessories'];

        $buyers = [];

        for ($i = 1; $i <= 20; $i++) {
            $country = $countries[array_rand($countries)];

            // Get brands for the selected country or use a generic one
            $countryBrands = $brandNames[$country] ?? ['Global Fashion'];
            $brand = $countryBrands[array_rand($countryBrands)];

            // Add company type suffix
            $companyTypes = ['Retail', 'Fashion House', 'Brand', 'Outlets', 'Department Store', 'Boutique Chain'];
            $companyType = $companyTypes[array_rand($companyTypes)];

            // Generate company name
            $companyName = $brand . ' ' . $companyType;

            // Generate contact person
            $firstNames = ['John', 'Sarah', 'Michael', 'Emma', 'David', 'Sophia', 'Robert', 'Olivia', 'James', 'Isabella', 'William', 'Mia', 'Richard', 'Charlotte'];
            $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Wilson', 'Anderson', 'Taylor', 'Thomas'];
            $contactPerson = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];

            // Generate email
            $emailName = strtolower(str_replace([' ', '\''], ['', ''], $contactPerson));
            $email = $emailName . rand(1, 99) . '@' . strtolower(str_replace(' ', '', $brand)) . '.com';

            // Generate phone based on country
            $phonePrefixes = [
                'USA' => '+1',
                'UK' => '+44',
                'Germany' => '+49',
                'France' => '+33',
                'Italy' => '+39',
                'Spain' => '+34',
                'Netherlands' => '+31',
                'Sweden' => '+46',
                'Denmark' => '+45',
                'Canada' => '+1',
                'Australia' => '+61',
                'Japan' => '+81',
                'South Korea' => '+82',
                'China' => '+86',
                'UAE' => '+971',
                'Saudi Arabia' => '+966'
            ];
            $prefix = $phonePrefixes[$country] ?? '+1';
            $phone = $prefix . '-' . rand(200, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999);

            // Generate address
            $cities = [
                'USA' => ['New York', 'Los Angeles', 'Chicago', 'Miami'],
                'UK' => ['London', 'Manchester', 'Birmingham'],
                'Germany' => ['Berlin', 'Munich', 'Hamburg'],
                'France' => ['Paris', 'Lyon', 'Marseille'],
                'Italy' => ['Milan', 'Rome', 'Florence'],
                'Spain' => ['Madrid', 'Barcelona', 'Valencia'],
                'Netherlands' => ['Amsterdam', 'Rotterdam'],
                'Sweden' => ['Stockholm', 'Gothenburg'],
                'Denmark' => ['Copenhagen', 'Aarhus'],
                'Canada' => ['Toronto', 'Vancouver', 'Montreal'],
                'Australia' => ['Sydney', 'Melbourne', 'Brisbane'],
                'Japan' => ['Tokyo', 'Osaka', 'Kyoto'],
                'South Korea' => ['Seoul', 'Busan'],
                'China' => ['Shanghai', 'Beijing', 'Guangzhou'],
                'UAE' => ['Dubai', 'Abu Dhabi'],
                'Saudi Arabia' => ['Riyadh', 'Jeddah']
            ];

            $city = isset($cities[$country]) ? $cities[$country][array_rand($cities[$country])] : 'City';
            $address = rand(10, 999) . ' Fashion Street, ' . $city . ', ' . $country;

            // Generate garment-specific notes
            $garmentNotes = [
                'Specializes in ' . $garmentTypes[array_rand($garmentTypes)],
                'Bulk orders of ' . ['t-shirts', 'jeans', 'shirts', 'dresses', 'jackets', 'sweaters'][array_rand([0, 1, 2, 3, 4, 5])],
                'High-end fashion retailer',
                'Fast fashion chain with weekly new collections',
                'Requires eco-friendly/sustainable materials',
                'Needs quick turnaround (30 days max)',
                'Large order quantity: ' . rand(5000, 50000) . ' pcs per style',
                'Focus on ' . ['women\'s wear', 'men\'s wear', 'kids wear', 'unisex'][array_rand([0, 1, 2, 3])],
                'Quality focused with strict AQL standards',
                'Price sensitive - budget fashion',
                'Seasonal collections only',
                'Private label manufacturing',
                'Requires OEM/ODM services',
                'Needs sampling before bulk production',
                null
            ];

            $note = $garmentNotes[array_rand($garmentNotes)];

            // Set status - first 15 buyers active (true), last 5 buyers inactive (false)
            $status = $i <= 15 ? true : false;

            $buyers[] = [
                'company_name' => $companyName,
                'contact_person' => $contactPerson,
                'email' => $email,
                'phone' => $phone,
                'country' => $country,
                'address' => $address,
                'note' => $note,
                'status' => $status,
                'user_id' => null,
                'updated_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Buyer::insert($buyers);
    }
}
