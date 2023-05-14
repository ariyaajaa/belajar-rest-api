<?php

namespace App\Observers;

use App\Models\Ingredients;
use App\Models\Log;

class IngredientsObserver
{
    /**
     * Handle the Ingredients "created" event.
     *
     * @param  \App\Models\Ingredients  $ingredients
     * @return void
     */
    public function created(Ingredients $ingredients)
    {
        Log::create([
            'module' => 'tambah bahan',
            'action' => 'tambah bahan untuk id resep '.$ingredients->resep_idresep.' dengan bahan '.$ingredients->nama,
            'useraccess' => '-'
        ]);
    }

    /**
     * Handle the Ingredients "updated" event.
     *
     * @param  \App\Models\Ingredients  $ingredients
     * @return void
     */
    public function updated(Ingredients $ingredients)
    {
        //
    }

    /**
     * Handle the Ingredients "deleted" event.
     *
     * @param  \App\Models\Ingredients  $ingredients
     * @return void
     */
    public function deleting(Ingredients $ingredients)
    {
        Log::create([
            'module' => 'hapus bahan',
            'action' => 'hapus bahan untuk id resep '.$ingredients->resep_idresep,
            'useraccess' => '-'
        ]);
    }

    /**
     * Handle the Ingredients "restored" event.
     *
     * @param  \App\Models\Ingredients  $ingredients
     * @return void
     */
    public function restored(Ingredients $ingredients)
    {
        //
    }

    /**
     * Handle the Ingredients "force deleted" event.
     *
     * @param  \App\Models\Ingredients  $ingredients
     * @return void
     */
    public function forceDeleted(Ingredients $ingredients)
    {
        //
    }
}
