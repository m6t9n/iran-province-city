<?php

namespace App\Models\IranProvinceCity;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\IranProvinceCity\City;


/**
 * Class Province
 *
 * @property int $id
 * @property string $code کد استان
 * @property string $name نام استان
 *
 * @property-read Collection|City[] $cities
 */
class Province extends Model
{
    public const TABLE = 'provinces';
    public const CODE = 'code';
    public const NAME = 'name';

    /**
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * @var array<string>
     */
    protected $fillable = [
        self::CODE,
        self::NAME,
    ];

    /**
     * دریافت شهرهای مربوط به این استان
     *
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, City::PROVINCE_CODE, self::CODE);
    }
}
