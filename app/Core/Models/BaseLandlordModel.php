<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseLandlordModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Centralna baza je uvek na podrazumevanoj mysql konekciji.
     */
    protected $connection = 'landlord';

    /**
     * Sprečava mass-assignment promenu primarnog ključa.
     *
     * Sva ostala polja su dozvoljena za masovno popunjavanje,
     * ali se oslanjamo na validaciju i eksplicitno mapiranje
     * podataka unutar Action/Service sloja.
     */
    // protected $guarded = ['id'];
}
