<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var array<int, array{name: string, description: string}> $categories */
        $categories = [
            ['name' => 'Retail', 'description' => 'Stores selling goods directly to consumers.'],
            ['name' => 'Wholesale and Distribution', 'description' => 'Bulk sales and product distribution.'],
            ['name' => 'Food and Beverage', 'description' => 'Restaurants, cafes, catering, and food production.'],
            ['name' => 'Grocery', 'description' => 'Supermarkets, minimarkets, and specialty food shops.'],
            ['name' => 'Fashion and Apparel', 'description' => 'Clothing, footwear, and accessories.'],
            ['name' => 'Beauty and Personal Care', 'description' => 'Salons, cosmetics, skincare, and wellness.'],
            ['name' => 'Health and Fitness', 'description' => 'Gyms, trainers, and wellness programs.'],
            ['name' => 'Healthcare and Medical', 'description' => 'Clinics, dental, labs, and medical services.'],
            ['name' => 'Pharmacy', 'description' => 'Retail pharmacies and drugstores.'],
            ['name' => 'Automotive', 'description' => 'Dealers, repair shops, detailing, and rentals.'],
            ['name' => 'Automotive Parts and Service', 'description' => 'Parts, accessories, and maintenance services.'],
            ['name' => 'Construction', 'description' => 'Builders, contractors, and materials.'],
            ['name' => 'Architecture and Engineering', 'description' => 'Design, engineering, and technical services.'],
            ['name' => 'Real Estate', 'description' => 'Property sales, rentals, and management.'],
            ['name' => 'Hospitality and Tourism', 'description' => 'Hotels, guesthouses, and tourist services.'],
            ['name' => 'Travel Agency', 'description' => 'Travel planning and tour operators.'],
            ['name' => 'Logistics and Transportation', 'description' => 'Shipping, courier, and freight services.'],
            ['name' => 'Manufacturing', 'description' => 'Factories, assembly, and production.'],
            ['name' => 'Information Technology', 'description' => 'IT services, support, and infrastructure.'],
            ['name' => 'Internet and Software SaaS', 'description' => 'Web, mobile apps, and SaaS products.'],
            ['name' => 'Telecommunications', 'description' => 'Network, ISP, and communication services.'],
            ['name' => 'Finance and Accounting', 'description' => 'Accounting, taxation, and financial services.'],
            ['name' => 'Legal Services', 'description' => 'Law firms, notaries, and legal consulting.'],
            ['name' => 'Human Resources', 'description' => 'Recruitment, outsourcing, and HR consulting.'],
            ['name' => 'Marketing & Advertising', 'description' => 'Digital marketing and creative agencies.'],
            ['name' => 'Media & Publishing', 'description' => 'News, magazines, and digital content.'],
            ['name' => 'Nonprofit & Charity', 'description' => 'NGOs and charitable organizations.'],
            ['name' => 'Education', 'description' => 'Schools, courses, and training centers.'],
            ['name' => 'Events and Wedding', 'description' => 'Event planners, venues, and vendors.'],
            ['name' => 'Entertainment', 'description' => 'Games, cinemas, and performers.'],
            ['name' => 'Sports and Recreation', 'description' => 'Clubs, activities, and facilities.'],
            ['name' => 'Home and Garden', 'description' => 'Home improvement and gardening.'],
            ['name' => 'Furniture', 'description' => 'Home and office furniture.'],
            ['name' => 'Electronics and Appliances', 'description' => 'Consumer electronics and appliances.'],
            ['name' => 'Office Supplies', 'description' => 'Stationery and office equipment.'],
            ['name' => 'Printing and Packaging', 'description' => 'Print shops and packaging services.'],
            ['name' => 'Cleaning Services', 'description' => 'Residential and commercial cleaning.'],
            ['name' => 'Security Services', 'description' => 'Security systems and personnel.'],
            ['name' => 'Pets and Veterinary', 'description' => 'Pet shops and veterinary services.'],
            ['name' => 'Art and Design', 'description' => 'Studios, galleries, and design services.'],
            ['name' => 'Photography and Videography', 'description' => 'Photo, video, and production.'],
            ['name' => 'Consulting', 'description' => 'Business and management consulting.'],
            ['name' => 'Agriculture', 'description' => 'Farming and agri-products.'],
            ['name' => 'Utilities and Energy', 'description' => 'Power, water, and renewables.'],
            ['name' => 'Repair and Maintenance', 'description' => 'Electronics, home, and equipment repairs.'],
            ['name' => 'Jewellery and Accessories', 'description' => 'Jewellery design and retail.'],
            ['name' => 'Children and Baby', 'description' => 'Toys, clothing, and services for kids.'],
            ['name' => 'Professional Services', 'description' => 'General professional services and advisors.'],
        ];

        foreach ($categories as $item) {
            $name = $item['name'];
            $description = $item['description'];
            $base = Str::slug($name);
            $slug = $base !== '' ? $base : strtolower(Str::random(6));
            $i = 1;
            while (BusinessCategory::query()->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i;
                $i++;
            }

            BusinessCategory::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $description,
                    'status' => 1,
                ],
            );
        }
    }
}
