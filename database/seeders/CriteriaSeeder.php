<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\Criteria;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $area = [
            "ADMINISTRATION-Dean",
            "ADMINISTRATION-Program Head",
            "ADMINISTRATION-Program Head",
            "ADMINISTRATION-Program Head",
            "Compliance of HEIs",
            "Compliance of HEIs",
            "Compliance of HEIs",
            "Compliance of HEIs",
            "FACULTY-Employment Status",
            "FACULTY-Load",
            "FACULTY-Load",
            "FACULTY-Load",
            "FACULTY-Professional Education",
            "FACULTY-Professional Education",
            "FACULTY-Professional Education",
            "FACULTY-Support",
            "FACULTY-Support",
            "LIBRARY-Book Collection",
            "LIBRARY-Book Collection",
            "LIBRARY-Librarian",
            "LIBRARY-Librarian",
            "LIBRARY-Library Space",
            "PHYSICAL FACILITIES-Audio Visual", 
            "PHYSICAL FACILITIES-Audio Visual",
            "PHYSICAL FACILITIES-Classroom",
            "PHYSICAL FACILITIES-Laboratory",
            "PHYSICAL FACILITIES-Laboratory",
            "PHYSICAL FACILITIES-Laboratory",
            "Presence of Intellectual Property Rights Policy",
            "Presence of Intellectual Property Rights Policy",
        ];

        $minReq = [
            "A Higher Education Institution (HEI) offering any of these programs shall have a full-time academic administrator for each program. This administrator can be a Dean, Department Head, Director, Coordinator or equivalent depending on the organizational structure of the HEI.",
            "Doctorate Degree in Computer Science",
            "Master’s degree in Computer Science, plus: at least three (3) years of CS work, CS consultancy, CS research experience, or tertiary level CS teaching experience, within the last five (5) years",
            "At least master’s degree in CS allied program plus: completion of Bachelor’s degree in Computer Science; or completion of all coursework requirements for a master's degree in CS; and at least three (3) years of CS work, CS consultancy, CS research experience, or tertiary level CS teaching experience, within the last five (5) years.",
            "Using the CHED Implementation Handbook for OBE and ISA as reference, a HEI shall develop the following items which will be submitted to CHED when they apply for a permit for a new program or the approval of the transformation of existing programs to outcomes-based framework",
            "The complete set of program outcomes, including its proposed additional program outcomes.",
            "Its proposed curriculum and its justification including a curriculum map.",
            "Proposed performance indicators for each outcome. Proposed measurement system for the level of attainment of each indicator.",
            "A full-time faculty member should render at least twenty-four (24) hours in residence per week as certified by the HEI, have a minimum contract of one (1) year in the college/department and must not be employed full time elsewhere.",
            "Assignment - The regular load of a CS, IS, and IT faculty member is at most twenty-four (24) units or thirty (30) contact hours per week whichever is lesser, inclusive of lecture and laboratory.  Overload should not exceed six (6) hours per week. There should not be more than four (4) preparations per term.",
            "Teaching Load - As a general rule, in case the Dean has to teach, his or her teaching load should not exceed nine (9) contact hours per week.  This load takes into consideration the functions of the Dean. For the department chair, his/her teaching load should not exceed twelve (12) contact hours.",
            "Consultation Hours - Each full time faculty member shall render at least four (4) hours per week for student consultation.  This should be outside of the regular contact hours.",
            "There should be at least three (3) full time faculty members per program, one of whom could be the dean/program head/coordinator.",
            "At least forty percent (40%) of CS, IS, and IT core and professional courses should be taught by full-time CS, IS, and IT faculty members.", 
            "There shall be a career development and tenure track for full time faculty members.",  
            "a. Faculty Development Program- The college/department should have a written comprehensive faculty development program.",
            "There shall be a specific budget allocation to implement such program. HEIs are enjoined to send full-time faculty members to participate in various activities of computing professional organizations.",
            "The library should have at least five (5) titles per professional course, at least one (1) of which has been published within the last five (5) years.",
            "The total number of volumes per course should be such that, there should be one (1) volume for every ten (10) students enrolled (e.g. if there are 100 students enrolled in the course Introduction to Computing, then there should be 10 volumes of books on Introduction to Computing of which 5 titles should be distinct).",
            "HEIs offering the CS, IS, and IT programs should have at least one (1) full-time licensed librarian with at least one (1) year appointment.",
            "The librarian(s) shall participate in faculty meetings and activities and serve as (a) member(s) of the educational program planning committee.",
            "The library should have a seating capacity of at least five percent (5%) of the total students enrolled and a minimum floor area of at least two (2) sqm per seat.  This may include spaces provided as Learning Commons.                                                     *should there a library specific to the college/department, the total student enrolled should be based on the college/department.",
            "Every laboratory must be equipped with projection equipment or large display to demonstrate digital content.",
            "In addition, projectors must also be available for use in lecture rooms.",
            "There should be at least one classroom per one hundred fifty (150) students enrolled. Preferably, there should be no more than 50 students in a class.  In case of large classes with more than 50 students, preferably, there should be separate discussion classes with at most 50 students each.",
            "The number of terminals dedicated for computing students should be at least 1/5 of the total number of computing students. *the number of target students/current population should be used as basis in determining the number of computing terminals*",
            "The computer-to-student ratio in a laboratory class should be 1:1.",
            "The minimum dedicated bandwidth must be at least 4Mbps. There should be at least 4Mbps per 500 students.",
            "All Thesis and Projects must not infringe on existing IP. All prior works, including open source, open content, and creative commons content, shall be properly cited.",
            "Copyright and other Intellectual Property Rights arising from the Thesis or Capstone Project shall be bound by the IP Policies of the HEI, provided that any such policies shall not be grossly disadvantageous to the creators of IP.",
        ];

        $id = 1;

        for ($cmoId = 0; $cmoId < 596; $cmoId++) {
            for ($item = 0; $item < 30; $item++) {

                DB::table('criteria')->insert([
                    'id' => $id,
                    'cmoId' => $cmoId+1,
                    'itemNo' => $item+1,
                    'area' => $area[$item],
                    'minimumRequirement' => $minReq[$item],
                ]);

                $id = $id + 1;
            }
        }
    }
}
