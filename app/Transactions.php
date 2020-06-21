<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transactions
 * @package App
 */
class Transactions extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'amount'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
