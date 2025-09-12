<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportGame extends Model
{
    use HasFactory;

    
    protected $fillable = ['name', 'sport_category_id', 'image_path'];

    public function categories()
    {
        return $this->belongsToMany(SportCategory::class, 'category_sport_game')
                    ->using(CategorySportGame::class)
                    ->withPivot('image_path')
                    ->withTimestamps();
    }

    // get sport game name and image path
    public function getSportGameNameAndImagePath()
    {
        return [
            'sport_name' => $this->name,
            'sport_image' => $this->categories->first()->pivot->image_path,
        ];
    }

    // based on the userPreplan occupation, get the sport game name and image path
    public static function getUserPlanSportGameImagePath($occupation) {
        // Step 1: Try full match first (case-insensitive)
        $sportGame = self::with('categories')
            ->whereRaw('LOWER(name) = ?', [$occupation])
            ->first();

        // Step 2: If no full match, split into keywords and check each
        if (! $sportGame) {
            $keywords = explode(' ', $occupation);

            foreach ($keywords as $keyword) {
                $sportGame = self::with('categories')
                    ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->first();

                if ($sportGame) {
                    break; // first matching keyword wins
                }
            }
        }

        return $sportGame ? $sportGame->getSportGameNameAndImagePath() : null;
    }
}
