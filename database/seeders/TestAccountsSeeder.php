<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestAccountsSeeder extends Seeder
{
    /**
     * Create test accounts with dummy data for client testing.
     * All dummy data is linked ONLY to test accounts.
     */
    public function run(): void
    {
        $this->command->info('Creating test accounts with dummy data...');
        
        // Create test sales rep
        $salesRepId = $this->createTestSalesRep();
        
        // Create test doctor
        $doctorId = $this->createTestDoctor();
        
        // Create test patient
        $patientId = $this->createTestPatient();
        
        // Create marketplace products for test sales rep
        $this->createTestProducts($salesRepId);
        
        // Add products to doctor's storefront
        $this->addProductsToDoctorStorefront($doctorId, $salesRepId);
        
        // Create patient-doctor relationship and vitals
        $this->createPatientDoctorRelationship($patientId, $doctorId);
        $this->createPatientVitals($patientId);
        
        // Create sample orders
        $this->createTestOrders($patientId, $doctorId, $salesRepId);
        
        // Create notifications
        $this->createTestNotifications($salesRepId, $doctorId, $patientId);
        
        $this->printCredentials();
    }

    private function createTestSalesRep(): int
    {
        $email = 'test.salesrep@myvitalz.com';
        $existing = DB::table('users')->where('email', $email)->first();
        
        if ($existing) {
            $this->command->info('Test sales rep already exists, updating...');
            DB::table('users')->where('id', $existing->id)->update([
                'password' => Hash::make('Test@123'),
                'updated_at' => now(),
            ]);
            return $existing->id;
        }
        
        return DB::table('users')->insertGetId([
            'name' => 'Test Sales Rep',
            'first_name' => 'Test',
            'last_name' => 'SalesRep',
            'email' => $email,
            'password' => Hash::make('Test@123'),
            'authen' => Hash::make('Test@123'),
            'phone' => '08099999901',
            'state' => 'Lagos',
            'city' => 'Ikeja',
            'address' => '123 Test Street, Ikeja',
            'company_name' => 'Test Pharma Ltd',
            'company_license' => 'TPL-2024-001',
            'ref_code' => 'MVSR' . mt_rand(100000, 999999),
            'sales_rep' => 1,
            'doctor' => 0,
            'pharmacy' => 0,
            'hospital' => 0,
            'storefront_tagline' => 'Quality Pharmaceuticals for Testing',
            'storefront_description' => 'This is a test sales rep account with sample pharmaceutical products for demonstration purposes.',
            'storefront_primary_color' => '#2563EB',
            'storefront_secondary_color' => '#1D4ED8',
            'storefront_phone' => '08099999901',
            'storefront_email' => $email,
            'storefront_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createTestDoctor(): int
    {
        $email = 'test.doctor@myvitalz.com';
        $existing = DB::table('users')->where('email', $email)->first();
        
        if ($existing) {
            $this->command->info('Test doctor already exists, updating...');
            DB::table('users')->where('id', $existing->id)->update([
                'password' => Hash::make('Test@123'),
                'updated_at' => now(),
            ]);
            return $existing->id;
        }
        
        return DB::table('users')->insertGetId([
            'name' => 'Dr. Test Doctor',
            'first_name' => 'Test',
            'last_name' => 'Doctor',
            'email' => $email,
            'password' => Hash::make('Test@123'),
            'authen' => Hash::make('Test@123'),
            'phone' => '08099999902',
            'state' => 'Lagos',
            'city' => 'Ikeja',
            'address' => '456 Medical Plaza, Ikeja',
            'about' => 'This is a test doctor account for demonstration purposes. Specializing in General Practice with 10 years of experience.',
            'specialization' => 'General Practice',
            'license_type' => 'MDCN',
            'doctor' => 1,
            'pharmacy' => 0,
            'hospital' => 0,
            'sales_rep' => 0,
            'public' => 1,
            'storefront_tagline' => 'Quality Healthcare Services',
            'storefront_description' => 'Test doctor storefront for demonstration.',
            'storefront_primary_color' => '#059669',
            'storefront_secondary_color' => '#047857',
            'storefront_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createTestPatient(): int
    {
        $email = 'test.patient@myvitalz.com';
        $existing = DB::table('users')->where('email', $email)->first();
        
        if ($existing) {
            $this->command->info('Test patient already exists, updating...');
            DB::table('users')->where('id', $existing->id)->update([
                'password' => Hash::make('Test@123'),
                'updated_at' => now(),
            ]);
            return $existing->id;
        }
        
        return DB::table('users')->insertGetId([
            'name' => 'Test Patient',
            'first_name' => 'Test',
            'last_name' => 'Patient',
            'email' => $email,
            'password' => Hash::make('Test@123'),
            'authen' => Hash::make('Test@123'),
            'phone' => '08099999903',
            'state' => 'Lagos',
            'city' => 'Ikeja',
            'address' => '789 Patient Avenue, Ikeja',
            'gender' => 'male',
            'dob' => '1985-05-15',
            'doctor' => 0,
            'pharmacy' => 0,
            'hospital' => 0,
            'sales_rep' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createTestProducts(int $salesRepId): void
    {
        $products = [
            [
                'drug_name' => 'Test Paracetamol 500mg',
                'generic_name' => 'Paracetamol',
                'category' => 'Analgesics',
                'description' => 'Test product - Pain reliever and fever reducer for demonstration',
                'wholesale_price' => 500,
                'suggested_retail_price' => 800,
                'stock_quantity' => 1000,
                'unit' => 'tablets',
            ],
            [
                'drug_name' => 'Test Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'category' => 'Antibiotics',
                'description' => 'Test product - Broad-spectrum antibiotic for demonstration',
                'wholesale_price' => 1200,
                'suggested_retail_price' => 1800,
                'stock_quantity' => 500,
                'unit' => 'capsules',
            ],
            [
                'drug_name' => 'Test Metformin 500mg',
                'generic_name' => 'Metformin HCl',
                'category' => 'Antidiabetics',
                'description' => 'Test product - Diabetes medication for demonstration',
                'wholesale_price' => 1500,
                'suggested_retail_price' => 2300,
                'stock_quantity' => 700,
                'unit' => 'tablets',
            ],
            [
                'drug_name' => 'Test Amlodipine 5mg',
                'generic_name' => 'Amlodipine',
                'category' => 'Cardiovascular',
                'description' => 'Test product - Blood pressure medication for demonstration',
                'wholesale_price' => 2000,
                'suggested_retail_price' => 3000,
                'stock_quantity' => 500,
                'unit' => 'tablets',
            ],
            [
                'drug_name' => 'Test Vitamin C 1000mg',
                'generic_name' => 'Ascorbic Acid',
                'category' => 'Vitamins',
                'description' => 'Test product - Immune support supplement for demonstration',
                'wholesale_price' => 1500,
                'suggested_retail_price' => 2200,
                'stock_quantity' => 800,
                'unit' => 'tablets',
            ],
            [
                'drug_name' => 'Test Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'category' => 'Gastrointestinal',
                'description' => 'Test product - Acid reflux medication for demonstration',
                'wholesale_price' => 2000,
                'suggested_retail_price' => 3000,
                'stock_quantity' => 400,
                'unit' => 'capsules',
            ],
        ];

        foreach ($products as $product) {
            $exists = DB::table('marketplace_drugs')
                ->where('sales_rep_id', $salesRepId)
                ->where('drug_name', $product['drug_name'])
                ->exists();

            if (!$exists) {
                DB::table('marketplace_drugs')->insert([
                    'sales_rep_id' => $salesRepId,
                    'drug_name' => $product['drug_name'],
                    'generic_name' => $product['generic_name'],
                    'category' => $product['category'],
                    'description' => $product['description'],
                    'wholesale_price' => $product['wholesale_price'],
                    'suggested_retail_price' => $product['suggested_retail_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'reorder_level' => 10,
                    'unit' => $product['unit'],
                    'photo' => null,
                    'state' => 'Lagos',
                    'city' => 'Ikeja',
                    'status' => 'active',
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Created test products for sales rep');
    }

    private function addProductsToDoctorStorefront(int $doctorId, int $salesRepId): void
    {
        // Get test products from sales rep
        $products = DB::table('marketplace_drugs')
            ->where('sales_rep_id', $salesRepId)
            ->where('drug_name', 'like', 'Test %')
            ->get();

        foreach ($products as $product) {
            $exists = DB::table('doctor_storefront_inventory')
                ->where('doctor_id', $doctorId)
                ->where('marketplace_drug_id', $product->id)
                ->exists();

            if (!$exists) {
                DB::table('doctor_storefront_inventory')->insert([
                    'doctor_id' => $doctorId,
                    'marketplace_drug_id' => $product->id,
                    'retail_price' => $product->suggested_retail_price,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Added products to doctor storefront');
    }

    private function createPatientDoctorRelationship(int $patientId, int $doctorId): void
    {
        $exists = DB::table('patients')
            ->where('user', $patientId)
            ->where('doctor', $doctorId)
            ->exists();

        if (!$exists) {
            DB::table('patients')->insert([
                'user' => $patientId,
                'doctor' => $doctorId,
                'doctor_approve' => 1,
                'user_approve' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created patient-doctor relationship');
    }

    private function createPatientVitals(int $patientId): void
    {
        // Check if vitals already exist for this patient
        $existingVitals = DB::table('vital_readings')
            ->where('user', $patientId)
            ->count();

        if ($existingVitals > 0) {
            $this->command->info('Patient vitals already exist, skipping...');
            return;
        }

        // Get all vital types
        $vitals = DB::table('allvitalz')->get();

        if ($vitals->isEmpty()) {
            $this->command->info('No vital types found in allvitalz table, skipping vitals creation...');
            return;
        }

        // Create 30 days of vital readings
        for ($day = 30; $day >= 0; $day--) {
            $date = now()->subDays($day);

            foreach ($vitals as $vital) {
                $reading = $this->generateVitalReading($vital->id);

                DB::table('vital_readings')->insert([
                    'user' => $patientId,
                    'vitalz' => $vital->id,
                    'reading' => $reading,
                    'si_unit' => $vital->si_unit ?? '',
                    'date' => $date->timestamp,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        $this->command->info('Created 30 days of vital readings for test patient');
    }

    private function generateVitalReading(int $vitalId): string
    {
        // Generate realistic readings based on vital type
        $readings = [
            1 => rand(65, 85),              // Heart Rate
            2 => rand(110, 130) . '/' . rand(70, 85), // Blood Pressure
            3 => rand(95, 99),              // Oxygen Saturation
            4 => rand(2, 5),                // Stress Level
            5 => rand(85, 110),             // Blood Glucose
            6 => rand(160, 200),            // Lipids
            7 => number_format(rand(50, 60) / 10, 1), // HbA1c
            8 => rand(70, 85),              // IHRA
            9 => number_format(rand(365, 375) / 10, 1), // Temperature
        ];

        return (string) ($readings[$vitalId] ?? rand(50, 100));
    }

    private function createTestOrders(int $patientId, int $doctorId, int $salesRepId): void
    {
        // Check if test orders already exist
        $existingOrders = DB::table('storefront_orders')
            ->where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->count();

        if ($existingOrders > 0) {
            $this->command->info('Test orders already exist, skipping...');
            return;
        }

        // Get test products from doctor's storefront
        $products = DB::table('doctor_storefront_inventory as dsi')
            ->join('marketplace_drugs as md', 'dsi.marketplace_drug_id', '=', 'md.id')
            ->where('dsi.doctor_id', $doctorId)
            ->where('md.drug_name', 'like', 'Test %')
            ->select('dsi.*', 'md.drug_name', 'md.wholesale_price', 'md.sales_rep_id')
            ->limit(3)
            ->get();

        if ($products->isEmpty()) {
            $this->command->info('No products in doctor storefront, skipping orders...');
            return;
        }

        // Create sample orders with different statuses
        $orderStatuses = ['pending', 'confirmed', 'delivered'];
        $paymentStatuses = ['pending', 'paid', 'paid'];

        foreach ($orderStatuses as $index => $status) {
            $orderNumber = 'ORD-TEST-' . strtoupper(substr(md5(uniqid()), 0, 8));
            $totalAmount = 0;
            $doctorCommission = 0;
            $salesRepAmount = 0;

            // Calculate totals
            foreach ($products as $product) {
                $totalAmount += $product->retail_price;
                $doctorCommission += $product->retail_price * 0.15; // 15% commission
                $salesRepAmount += $product->wholesale_price;
            }

            $orderId = DB::table('storefront_orders')->insertGetId([
                'order_number' => $orderNumber,
                'patient_id' => $patientId,
                'doctor_id' => $doctorId,
                'sales_rep_id' => $salesRepId,
                'total_amount' => $totalAmount,
                'doctor_commission' => $doctorCommission,
                'sales_rep_amount' => $salesRepAmount,
                'payment_status' => $paymentStatuses[$index],
                'order_status' => $status,
                'delivery_address' => '789 Patient Avenue, Ikeja',
                'delivery_state' => 'Lagos',
                'delivery_city' => 'Ikeja',
                'delivery_phone' => '08099999903',
                'notes' => 'Test order for demonstration - ' . ucfirst($status),
                'created_at' => now()->subDays(30 - ($index * 10)),
                'updated_at' => now()->subDays(30 - ($index * 10)),
            ]);

            // Create order items
            foreach ($products as $product) {
                DB::table('storefront_order_items')->insert([
                    'order_id' => $orderId,
                    'marketplace_drug_id' => $product->marketplace_drug_id,
                    'quantity' => 1,
                    'unit_price' => $product->retail_price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Created test orders');
    }

    private function createTestNotifications(int $salesRepId, int $doctorId, int $patientId): void
    {
        $notifications = [
            [
                'user_id' => $salesRepId,
                'title' => 'Welcome to My Vitalz!',
                'message' => 'Your test sales rep account is ready. Explore the dashboard to see your products and orders.',
                'type' => 'info',
            ],
            [
                'user_id' => $salesRepId,
                'title' => 'New Order Received',
                'message' => 'A test order has been placed for your products.',
                'type' => 'order',
            ],
            [
                'user_id' => $doctorId,
                'title' => 'Welcome to My Vitalz!',
                'message' => 'Your test doctor account is ready. You can manage patients and your storefront.',
                'type' => 'info',
            ],
            [
                'user_id' => $doctorId,
                'title' => 'New Patient Added',
                'message' => 'Test Patient has been added to your patient list.',
                'type' => 'patient',
            ],
            [
                'user_id' => $patientId,
                'title' => 'Welcome to My Vitalz!',
                'message' => 'Your test patient account is ready. Track your vitals and connect with doctors.',
                'type' => 'info',
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert([
                'user_id' => $notification['user_id'],
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => $notification['type'] ?? 'info',
                'seen' => 0,
                'date' => date('d-M-Y'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created test notifications');
    }

    private function printCredentials(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('   TEST ACCOUNTS CREATED SUCCESSFULLY   ');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('All test accounts use the same password: Test@123');
        $this->command->info('');
        $this->command->info('SALES REP ACCOUNT:');
        $this->command->info('  Email: test.salesrep@myvitalz.com');
        $this->command->info('  Password: Test@123');
        $this->command->info('  Features: Products, Orders, Analytics, Payout');
        $this->command->info('');
        $this->command->info('DOCTOR ACCOUNT:');
        $this->command->info('  Email: test.doctor@myvitalz.com');
        $this->command->info('  Password: Test@123');
        $this->command->info('  Features: Patients, Vitals, Storefront, Prescriptions');
        $this->command->info('');
        $this->command->info('PATIENT ACCOUNT:');
        $this->command->info('  Email: test.patient@myvitalz.com');
        $this->command->info('  Password: Test@123');
        $this->command->info('  Features: Vitals Tracking, Doctor Connection, Orders');
        $this->command->info('');
        $this->command->info('NOTE: All dummy data is linked ONLY to these test accounts.');
        $this->command->info('========================================');
    }
}
