<?php

namespace App\Models\IranProvinceCity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vendor\IranProvinceCity\app\Models\Province;

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
 * @property-read Province $province استان مربوطه
 */
class City extends Model
{
    public const TABLE = 'cities';
    public const CODE = 'code';
    public const NAME = 'name';
    public const PROVINCE_CODE = 'province_code';
    public const LATITUDE = 'latitude';
    public const LONGITUDE = 'longitude';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array<string> */
    protected array $fillable = [
        self::CODE,
        self::NAME,
        self::PROVINCE_CODE,
        self::LATITUDE,
        self::LONGITUDE,
    ];

    /** @var array<string, string> */
    protected array $casts = [
        self::CODE => 'string',
        self::NAME => 'string',
        self::PROVINCE_CODE => 'string',
        self::LATITUDE => 'float',
        self::LONGITUDE => 'float',
    ];

    /**
     * استان مربوط به این شهر
     *
     * @return BelongsTo<Province, City>
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(
            related: Province::class,
            foreignKey: self::PROVINCE_CODE,
            ownerKey: Province::CODE
        );
    }
}
