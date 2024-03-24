<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $allId = [
            11155, 11122, 11162, 11001, 11002, 11189, 11004, 11175, 11114, 11006,
            11007, 11008, 11181, 11136, 11191, 11188, 11012, 11014, 11173, 11176,
            11177, 11178, 11015, 11098, 11016, 11018, '11021c', '11021a', 11021, '11021b',
            11158, 11137, 11019, 11165, 11026, 11154, 11030, 11156, 11126, 11033,
            11144, 11035, 11174, 11115, 11100, 11179, 11131, 11142, 11164, 11169,
            11146, 11138, 11039, 11183, 11190, 11180, 11184, 11128, 11041, 11042,
            11125, 11044, 11161, 11168, 11049, 11050, 11170, 11124, 11055, 11056,
            11058, 11063, 11066, 11185, 11167, 11070, 11075, 11078, 11076, 11077,
            11129, 11101, 11123, 11140, 11034, 11064, 11160, 11130, 11186, 11182,
            11172, 11153, 11085, 11062, 11088, 11089, 11091, 11120, 11092, 11093,
            11099, 11094, 11096, 11095, 11107
        ];

        $allHEI = [
            'ACES Polytechnic College',
            'ACES Tagum College',
            'ACQ College of Ministries',
            'Agro-Industrial Foundation College of the Philippines',
            'AMA Computer College-Davao',
            'AMYA Polytechnic College, Inc.',
            'Arriesgado College Foundation Incorporated',
            'Asian International School of Aeronautics and Technology',
            'Assumption College of Davao',
            'Assumption College of Nabunturan',
            'Ateneo de Davao University',
            'Brokenshire College',
            'CARD-MRI Development Institute',
            'Christian Colleges of Southeast Asia',
            'City College of Davao',
            'Colegio de Caraga, Inc.',
            'Cor Jesu College',
            'Davao Central College',
            'Davao de Oro State College - Compostela (Main)',
            'Davao de Oro State College - Maragusan Branch',
            'Davao de Oro State College - Montevista Branch',
            'Davao de Oro State College - New Bataan Branch',
            'Davao del Norte State College',
            'Davao del Sur State College',
            'Davao Doctors College',
            'Davao Medical School Foundation, Inc.',
            'Davao Oriental State University - Banaybanay Extension',
            'Davao Oriental State University - Cateel Extension',
            'Davao Oriental State University - Mati City (Main)',
            'Davao Oriental State University - San Isidro Extension',
            'Davao Vision Colleges, Inc.',
            'Davao Winchester Colleges, Inc.',
            'DMMA College of Southern Philippines',
            'Evangelical Mission College',
            'Evelyn E. Fabie College',
            'Gabriel Taborin College of Davao Foundation',
            'General Baptist Bible College',
            'Governor Generoso College of Arts, Sciences and Technology',
            'Holy Child College of Davao',
            'Holy Cross College of Calinan',
            'Holy Cross College of Sasa',
            'Holy Cross of Davao College',
            'Institute of International Culinary and Hospitality Entrepreneurship',
            'Interface Computer College',
            'Joji Ilagan Career Center Foundation',
            'Joji Ilagan International Management School',
            'Jose Maria College Foundation, Inc.',
            'Kapalong College Of Agriculture, Sciences And Technology',
            'Koinonia Theological Seminary Foundation',
            'Kolehiyo ng Pantukan',
            'Laak Institute Foundation',
            'Legacy College Of Compostela',
            'Liceo De Davao',
            'Lyceum of the Philippines-Davao',
            'Maco de Oro College',
            'Mapua Malayan Colleges Mindanao',
            'Maryknoll College of Panabo, Inc.',
            'Mati Doctors College',
            'Mati Polytechnic College',
            'MATS College of Technology',
            'Mindanao Kokusai Daigaku',
            'Mindanao Medical Foundation College',
            'Monkayo College of Arts, Sciences and Technology',
            'Mt. Apo Science Foundation College',
            'NDC Tagum Foundation',
            'North Davao Colleges',
            'Northlink Technological College',
            'Philippine College of Technology',
            'Philippine Women\'s College of Davao',
            'Polytechnic College Of Davao Del Sur',
            'Queen Of Apostles College Seminary',
            'Saint Francis Xavier College Seminary',
            'Saint Peter\'s College of Toril',
            'Samal Island City College',
            'Samson Polytechnic College of Davao',
            'San Pedro College',
            'Serapion C. Basalo Memorial Colleges',
            'South Philippine Adventist College',
            'Southeastern College Of Padada',
            'Southern Philippines Agri-Business and Marine and Aquatic School of Technology',
            'Southern Philippines Baptist Theological Seminary, Inc.',
            'St. Francis Xavier Regional Major Seminary of Mindanao, Inc.',
            'St. John Paul II College of Davao',
            'St. Mary\'s College Baganga',
            'St. Mary\'s College of Bansalan',
            'St. Mary\'s College of Tagum',
            'St. Thomas More College of Law and Business',
            'STI College Davao',
            'STI College Tagum',
            'Sto. Tomas College of Agriculture, Sciences and Technology',
            'Tagum City College Of Science And Technology Foundation',
            'Tagum Doctors College',
            'Tecarro College Foundation',
            'The Rizal Memorial Colleges',
            'UM Bansalan College',
            'UM Digos College',
            'UM Panabo College',
            'UM Peñaplata College',
            'UM Tagum College',
            'University of Mindanao',
            'University of Southeastern Philippines, Mintal Campus',
            'University of Southeastern Philippines, Obrero (Main)',
            'University of Southeastern Philippines, Tagum-Mabini Campus',
            'University of the Immaculate Conception',
            'University of the Philippines-Mindanao'
        ];

        $allDiscipline = [
            'Business Administration and Related',
            'IT-Related Disciplines',
            'Education Science and Teacher Training',
            'Criminal Justice Education',
            'Religion and Theology',
            'Maritime',
            'Engineering and Technology',
            'Service Trades',
            'Medical and Allied',
            'Other Disciplines',
            'Humanities',
            'Natural Science',
            'Mathematics',
            'Social and Behavioral Sciences',
            'Law and Jurisprudence',
            'Mass Communication and Documentation',
            'Architecture and Town Planning',
            'Agriculture, Forestry, Fisheries',
            'Fine and Applied Arts',
            'Home Economics'
        ];

        foreach($allHEI as $index => $hei) {
            DB::table('institution')->insert([
                'code' => $allId[$index],
                'name' => $hei,
            ]);
        }

        foreach($allDiscipline as $discipline) {
            DB::table('discipline')->insert([
                'discipline' => $discipline,
            ]);
        }
		
        // PROGRAMS
        DB::table('program')->insert([
            'disciplineId' => 2,
            'program' => 'Bachelor of Science in Information Technology',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 2,
            'program' => 'Bachelor of Science in Computer Science',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 1,
            'program' => 'Bachelor of Science in Business Administration',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 1,
            'program' => 'Bachelor of Science in Hospitality Management',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 3,
            'program' => 'Bachelor of Elementary Education',
        ]);

        DB::table('program')->insert([
            'disciplineId' => 3,
            'program' => 'Bachelor of Secondary Education',
        ]);

        DB::table('admin_settings')->insert([
            'currentAcademicYear' => '2023-2024',
        ]);
    }
}
