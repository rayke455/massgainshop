<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

trait GetChildPanel
{
    public function childPanel()
    {
        try {
            $domain = request()->getHost();

            return DB::table('child_panels')->where('status', 1)
                ->where('domain', $domain)->first();

        } catch (QueryException $exception) {
        }
    }
}
