<?php

namespace Database\Seeders;

use App\Models\CompetencyCategory;
use App\Models\Module;
use App\Models\Pathway;
use App\Models\ResearchComponent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**7
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);


        $researchComponent1 = ResearchComponent::create([
            'name' => 'The Syllabus System',
        ]);

        $researchComponent2 = ResearchComponent::create([
            'name' => 'Foundations for CRFS Research',
        ]);

        $researchComponent1->modules()->createMany([[
            'name' => 'Theory of Change',
            'slug' => 'theory-of-change',
            'description' => 'Anim nisi minim ad ex fugiat. Pariatur duis non nostrud dolore est pariatur exercitation mollit eiusmod. Reprehenderit cupidatat qui exercitation id velit dolore non id. Ullamco enim elit adipisicing nisi aliqua culpa proident quis anim consectetur.',
            'time_estimate' => 3.5,
        ]]);

        $researchComponent2->modules()->createMany([
            [
                'name' => 'Agroecological principles',
                'slug' => 'agroecological-principles',
                'description' => 'Dolor amet commodo do adipisicing nostrud eiusmod cillum fugiat aliqua nulla elit commodo. Dolore fugiat eu aliquip ex. Elit ea deserunt adipisicing ut ad ullamco. Aliqua sit nulla amet duis enim in excepteur quis nostrud quis amet nisi duis adipisicing proident. Reprehenderit do labore irure consectetur tempor dolor officia esse nulla non. Labore deserunt pariatur non nulla aliqua quis est laboris nisi anim cupidatat officia.',
                'time_estimate' => 1.5,
            ]
        ]);

        $researchComponent2->modules()->createMany([
            [
                'name' => 'Agroecological principles',
                'slug' => 'agroecological-principles',
                'description' => 'Dolor amet commodo do adipisicing nostrud eiusmod cillum fugiat aliqua nulla elit commodo. Dolore fugiat eu aliquip ex. Elit ea deserunt adipisicing ut ad ullamco. Aliqua sit nulla amet duis enim in excepteur quis nostrud quis amet nisi duis adipisicing proident. Reprehenderit do labore irure consectetur tempor dolor officia esse nulla non. Labore deserunt pariatur non nulla aliqua quis est laboris nisi anim cupidatat officia.',
                'time_estimate' => 1.5,
            ]
        ]);

        $category1 = CompetencyCategory::create([
            'name' => 'Managing Research',
        ]);
        $category2 = CompetencyCategory::create([
            'name' => "Making an Impact",
        ]);
        CompetencyCategory::create([
            'name' => "Self Management",
        ]);
        CompetencyCategory::create([
            'name' => "Cognitive Abilities",
        ]);
        CompetencyCategory::create([
            'name' => "Working with Others",
        ]);
        CompetencyCategory::create([
            'name' => "Managing Research Tools",
        ]);
        CompetencyCategory::create([
            'name' => "Doing Research",
        ]);

        $category1->competencies()->createMany([
            [
                'name' => 'Research Design 1',
            ],
            [
                'name' => 'Research Design 2',
            ],
            [
                'name' => 'Research Design 3',
            ],
            [
                'name' => 'Research Design 4',
            ],
        ]);


        // Create a pathway and link all modules to it.
        $pathway = Pathway::create([
            'name' => 'Essential Research Methods for Agroecology',
            'slug' => 'essential-research-methods-for-agroecology',
        ]);

        // use the ID as the initial ordering
        $pathway->modules()->sync(Module::all()->pluck('id')->map(fn ($id) => ['module_order' => $id]));

        // Link competencies to modules
        $pathway->modules->each(function ($module) {
            $rand = random_int(1, 4);

            $module->competencies()->sync(CompetencyCategory::all()->random($rand)->pluck('id'));
        });
    }
}
