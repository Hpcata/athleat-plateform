<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportInquires extends Model
{
    use HasFactory;
    /**
     * Table Name
     */
    protected $table = 'sport_inquires';

    /**
     * Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * Mass Assignable Fields
     */
    protected $fillable = [
        'category',
        'sport_game',
        'state',
        'email'
    ];

        /**
     * Data Type Casting
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }
}
