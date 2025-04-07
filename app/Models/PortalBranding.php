<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalBranding extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logo_light',
        'light_path',
        'logo_dark',
        'dark_path',
        'branding_name',
        'favicon',
        'favicon_path',
        'status',
    ];

    /**
     * Get the URL for the light mode logo
     *
     * @return string|null
     */
    public function getLightLogoUrlAttribute()
    {
        return $this->light_path ? asset('storage/' . $this->light_path) : null;
    }

    /**
     * Get the URL for the dark mode logo
     *
     * @return string|null
     */
    public function getDarkLogoUrlAttribute()
    {
        return $this->dark_path ? asset('storage/' . $this->dark_path) : null;
    }

    /**
     * Get the URL for the favicon
     *
     * @return string|null
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon_path ? asset('storage/' . $this->favicon_path) : null;
    }
}