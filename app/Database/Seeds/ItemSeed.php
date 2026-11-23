<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\CLI\CLI;
use App\Models\ItemModel;

class ItemSeed extends Seeder
{
    public function run()
    {
        $itemModel = new ItemModel();

        $items = [
            [
                'name' => 'HBW Ballpen',
                'description' => 'Standard 0.5mm black ballpoint pen for everyday writing and signing',
                'stock' => 100,
            ],
            [
                'name' => 'A4 Copy Paper Ream',
                'description' => '80gsm multipurpose bond paper for printers and copiers (500 sheets per ream)',
                'stock' => 200,
            ],
            [
                'name' => 'Sticky Notes Pad',
                'description' => '3x3 inch self-adhesive yellow memo pads for quick reminders and page flags',
                'stock' => 300,
            ],
            [
                'name' => 'Heavy-Duty Stapler',
                'description' => 'Desktop metal stapler compatible with standard 24/6 and 26/6 staples',
                'stock' => 45,
            ],
            [
                'name' => 'Correction Tape',
                'description' => 'Dry-line correction tape dispenser for clean and instant ink coverage',
                'stock' => 150,
            ],
            [
                'name' => 'Highlighter Marker',
                'description' => 'Chisel-tip fluorescent highlighter for marking key text and documents',
                'stock' => 120,
            ],
            [
                'name' => 'Binder Clips Assortment',
                'description' => 'Tempered steel foldback clips in mixed sizes for securing thick stacks of paper',
                'stock' => 250,
            ],
            [
                'name' => 'Long Folder (Manila)',
                'description' => 'Legal/long-size manila file folder with fastener for organizing records',
                'stock' => 180,
            ],
            [
                'name' => 'Whiteboard Marker',
                'description' => 'Low-odor dry-erase marker with bullet tip for glass and melamine boards',
                'stock' => 85,
            ],
            [
                'name' => '2-Hole Puncher',
                'description' => 'Adjustable paper punch with alignment guide for standard ring binders',
                'stock' => 30,
            ],
        ];

        foreach ($items as $item) {
            if (!$itemModel->where('name', $item['name'])->first()) {
                $itemModel->insert($item);
                CLI::write("Inserted item: {$item['name']}", 'green');
            } else {
                CLI::write("Item already exists: {$item['name']}", 'yellow');
            }
        }
    }
}