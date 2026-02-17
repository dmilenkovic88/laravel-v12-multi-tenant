<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

abstract class BaseTenantModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Svi modeli koji nasleđuju ovu klasu koriste klijentsku bazu.
     * Ova konekcija se dinamički menja u SwitchTenantDatabase akciji.
     */
    protected $connection = 'tenant';

    /**
     * Sprečava mass-assignment promenu primarnog ključa.
     *
     * Sva ostala polja su dozvoljena za masovno popunjavanje,
     * ali se oslanjamo na validaciju i eksplicitno mapiranje
     * podataka unutar Action/Service sloja.
     */
    // protected $guarded = ['id'];


}
