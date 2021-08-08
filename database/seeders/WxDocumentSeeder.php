<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\WxDocument;


use Storage;

class WxDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(WxDocument::class)->truncate();

        $filename = 'wxDocument';

        $path = storage_path() . "/app/public" ."/${filename}.json";

        $json = json_decode(file_get_contents($path), true); 
        logger($json);

        foreach($json as $data) {
            app(WxDocument::class)->create(['id' => uniqid(),'text' => $data['text'], 'value' => $data['value']]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
