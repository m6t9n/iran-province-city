<?php

namespace App\Models\IranProvinceCity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\IranProvinceCity\Province;

/**
 * Class City
 *
 * @property int $id
 * @property string $code کد شهر
 * @property string $name نام شهر
 * @property int $province_code کد استان
 * @property float|null $latitude
 * @property float|null $longitude
 *
 * @property-read Province $province
 */
class City extends Model
{
    public const TABLE = 'cities';
    public const CODE = 'code';
    public const NAME = 'name';
    public const PROVINCE_CODE = 'province_code';
    public const LATITUDE = 'latitude';
    public const LONGITUDE = 'longitude';

    protected $table = self::TABLE;

    protected $fillable = [
        self::CODE,
        self::NAME,
        self::PROVINCE_CODE,
        self::LATITUDE,
        self::LONGITUDE,
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, self::PROVINCE_CODE, Province::CODE);
    }
}
