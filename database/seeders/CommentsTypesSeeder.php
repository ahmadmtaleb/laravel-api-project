<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comments_types;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class CommentsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments_types')->delete();
        $json = File::get("database/data/comments_types.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          Comments_types::create(array(
            'id' => $obj->id,
            'type_name' => $obj->type_name,
          ));
        }
    }
}
