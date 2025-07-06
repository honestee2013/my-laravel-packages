<?php

namespace App\Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Modules\Access\Models\Role;
use App\Modules\Access\Models\Permission;
use App\Modules\Hr\Models\JobTitle;

use QuickerFaster\CodeGen\Services\AccessControl\AccessControlPermissionService;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */



    // Add these arrays inside your seeder class
    private $nigerianStates = [
        'Lagos', 'Kano', 'Abuja', 'Rivers', 'Oyo', 'Delta', 'Kaduna', 'Ogun', 'Enugu', 'Katsina'
    ];

    private $nigerianCities = [
        'Lagos', 'Kano', 'Abuja', 'Port Harcourt', 'Ibadan', 'Benin City', 
        'Kaduna', 'Abeokuta', 'Enugu', 'Katsina', 'Jos', 'Ilorin', 
        'Warri', 'Sokoto', 'Calabar', 'Uyo', 'Maiduguri', 'Awka'
    ];




    public function run()
    {
        $this->seedUsersAndBasicInfo();
        $this->seedUserProfiles();
        $this->assignAllPermissionToSuperAdmin(User::find(1));


    }






    private function seedUsersAndBasicInfo() {
        $users = [];
        $basicInfos = [];


        // Create Super Admin
        $users [] = [
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now(),
            'user_type' => 'staff',
        ];


        // Create Super Admin basic info
        $basicInfos[] = [
            'user_id' => 1,
            'phone_number' => '+2348135903838',
            'email' => 'alternative-email@somewhere.com',
            'address_line_1' => 'Some address no1',
            'address_line_2' => 'Some address no2',
            'city' => 'Kano',
            'state' => 'Fagge',
            'postal_code' => '123456',
            'country' => 'Nigeria',
            'date_of_birth' => '2025-06-28',
            'gender' => 'Male',
            'user_status' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Generate 19 additional staff users with basic info
        /*
        for ($id = 2; $id <= 20; $id++) {
            $staffNum = $id - 1;
            
            // Create user
            $users[] = [
                'id' => $id,
                'name' => 'Staff ' . $staffNum,
                'email' => 'staff' . $staffNum . '@softui.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'user_type' => 'staff',
            ];

            // Create basic info
            $basicInfos[] = [
                'user_id' => $id,
                'phone_number' => '+234' . mt_rand(7000000000, 9099999999),
                'email' => 'alt.staff' . $staffNum . '@softui.com',
                'address_line_1' => $staffNum . ' Staff Street',
                'address_line_2' => 'Block ' . chr(64 + $staffNum),
                'city' => $this->nigerianCities[array_rand($this->nigerianCities)],
                'state' => $this->nigerianStates[array_rand($this->nigerianStates)],
                'postal_code' => (string) mt_rand(100000, 999999),
                'country' => 'Nigeria',
                'date_of_birth' => now()->subYears(rand(25, 50))->subDays(rand(0, 365))->format('Y-m-d'),
                'gender' => (rand(0, 1) ? 'Male' : 'Female'),
                'user_status' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        */

        // Insert all data
        User::insert($users);
        DB::table('basic_infos')->insert($basicInfos);

    }



    private function seedUserProfiles()
    {
        

        // Now seed employee_profiles with job titles
        $profiles = [];
        $jobTitles = JobTitle::pluck('id', 'title')->toArray();

        // Define department to job title mapping
        $departmentTitles = [
            'Administration' => [
                'Chief Executive Officer', 'Chief Operating Officer', 
                'Executive Assistant', 'Office Manager', 'Administrative Assistant'
            ],
            'Human Resources' => [
                'HR Manager', 'HR Assistant', 'Recruiter', 'Training and Development Officer'
            ],
            'Finance' => [
                'Finance Manager', 'Accountant', 'Accounts Payable Specialist',
                'Accounts Receivable Specialist', 'Auditor'
            ],
            'IT' => [
                'IT Administrator', 'Software Developer', 'Systems Analyst',
                'DevOps Engineer', 'Data Analyst'
            ],
            'Marketing' => [
                'Marketing Manager', 'Digital Marketing Specialist'
            ],
            'Sales' => [
                'Sales Representative', 'Business Development Manager'
            ],
            'Operations' => [
                'Operations Manager', 'Production Supervisor', 'Warehouse Supervisor',
                'Procurement Officer', 'Logistics Coordinator'
            ]
        ];

        // Define departments and designations
        $departments = array_keys($departmentTitles);
        $designations = [
            'C.E.O', 'Human Resource Head', 'Finance Manager', 'IT Director', 
            'Marketing Lead', 'Sales Executive', 'Operations Specialist'
        ];

        // 1. Super Admin (user_id=1)
        $profiles[] = [
            'employee_id' => 'EMP-'.now()->format('Ymd').'-001',
            'user_id' => 1,
            'role_id' => 1, // First role is super admin
            'department' => 'Administration',
            'designation' => 'C.E.O',
            'shift_id' => 1,
            'employee_profile_id' => null,
            'job_title_id' => $jobTitles['Chief Executive Officer'],
            'employment_type' => 'Full-Time',
            'work_location' => 'Abuja',
            'joining_date' => now()->format('Y-m-d'),
            'termination_date' => null,
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        /*
        // 2. Staff 2 (user_id=2)
        $profiles[] = [
            'employee_id' => 'EMP-'.now()->format('Ymd').'-002',
            'user_id' => 2,
            'role_id' => 2,
            'department' => 'Human Resources',
            'designation' => 'Human Resource Head',
            'shift_id' => 1,
            'employee_profile_id' => 1,  // Reports to Super Admin
            'job_title_id' => $jobTitles['HR Manager'],
            'employment_type' => 'Full-Time',
            'work_location' => 'Abuja',
            'joining_date' => now()->format('Y-m-d'),
            'termination_date' => null,
            'notes' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        */

        /*

        // 3. Generate profiles for remaining staff (user_id 3-20)
        for ($userId = 3; $userId <= 20; $userId++) {
            $staffNum = $userId - 1;
            $dept = $departments[array_rand($departments)];
            
            // Get a random job title from the department
            $title = $departmentTitles[$dept][array_rand($departmentTitles[$dept])];
            
            // Determine reporting structure (alternate between CEO and HR Head)
            $managerId = ($userId % 3 == 0) ? 2 : 1; 

            $profiles[] = [
                'employee_id' => 'EMP-'.now()->format('Ymd').'-'.str_pad($userId, 3, '0', STR_PAD_LEFT),
                'user_id' => $userId,
                'role_id' => 2,
                'department' => $dept,
                'designation' => $designations[array_rand($designations)],
                'shift_id' => rand(1, 3),
                'employee_profile_id' => $managerId,
                'job_title_id' => $jobTitles[$title],
                'employment_type' => ['Full-Time', 'Part-Time', 'Contract'][rand(0, 2)],
                'work_location' => ['Abuja', 'Lagos', 'Kano', 'Port Harcourt'][rand(0, 3)],
                'joining_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
                'termination_date' => (rand(1, 10) > 8 ? now()->addMonths(rand(1, 6))->format('Y-m-d') : null),
                'notes' => (rand(1, 10) > 8 ? 'Sample employment notes' : null),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        */
        
        // Insert all employee profiles
        DB::table('employee_profiles')->insert($profiles);
    }



    private function assignAllPermissionToSuperAdmin($superAdmin) {
        // Seed permissions ready for assignment to roles
        AccessControlPermissionService::seedPermissionNames();
        

        // Create or get the role with correct guard
        $role = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );


        $permissions = Permission::where('guard_name', 'web')->get();

        // Assign permissions to role
        $role->syncPermissions($permissions);

        // Assign role to user using correct guard
        $superAdmin->assignRole($role);

    }











}
