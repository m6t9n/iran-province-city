<?php

namespace Models\IranProvinceCity;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vendor\IranProvinceCity\Models\City;

/**
 * Class Province
 *
 * @property int $id
 * @property int $code کد استان
 * @property string $name نام استان
 *
 * @property-read Collection<int, City> $cities لیست شهرهای این استان
 */
class Province extends Model
{
    public const TABLE = 'provinces';
    public const CODE = 'code';
    public const NAME = 'name';

    /** @var string */
    protected $table = self::TABLE;

    /** @var array<string> */
    protected $fillable = [
        self::CODE,
        self::NAME,
    ];

    /** @var array<string, string> */
    protected $casts = [
        self::CODE => 'integer',
        self::NAME => 'string',
    ];

    /**
     * لیست شهرهای این استان
     *
     * @return HasMany<City>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(
            related: City::class,
            foreignKey: City::PROVINCE_CODE,
            localKey: self::CODE
        );
    }
}
