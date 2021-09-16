<?php
namespace Shamaseen\Analytics\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Config;
use Cookie;
use DeviceDetector\DeviceDetector;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Request;
use Shamaseen\Analytics\Parser;

/**
 * Shamaseen\Analytics\Models\LaConversion
 *
 * @property int $id
 * @property string $name
 * @property int $weight
 * @property int $is_unique
 * @property string $hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|Eloquent $conversionable
 * @property-read LaInfo|null $info
 * @method static Builder|LaConversion newModelQuery()
 * @method static Builder|LaConversion newQuery()
 * @method static Builder|LaConversion query()
 * @method static Builder|LaConversion whereCreatedAt($value)
 * @method static Builder|LaConversion whereHash($value)
 * @method static Builder|LaConversion whereId($value)
 * @method static Builder|LaConversion whereIsUnique($value)
 * @method static Builder|LaConversion whereName($value)
 * @method static Builder|LaConversion whereUpdatedAt($value)
 * @method static Builder|LaConversion whereWeight($value)
 * @mixin Eloquent
 */
class LaConversion extends Model
{
    protected $table = 'la_conversions';
    protected $forceInsert = false;

    protected $fillable = [
        'name','weight','hash','url','source'

        // just for forcing the creating
        ,'force'
    ];

    public function conversionable()
    {
        return $this->morphTo('conversion');
    }

    public function info(){
        return $this->hasOne(LaInfo::class,'conversion_id');
    }

    static function conversion($name,$weight = 100, $source = null,$force = false){
        self::create([
            'name' => $name,
            'weight' => $weight,
            'source' => $source,
            'force' => $force,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model){
            // we don't want to register duplicated entry within a threshold
            if(!$model->force && self::where('name',$model->name)
                ->where('created_at',">=",now()->subSeconds(Config::get('la_analytics.threshold')))
            ->exists())
                return false;

            unset($model->force);

            $cookie = Cookie::get('la_analytics',null);
            $uuid = $cookie ?? uniqid('',true);

            if(!$cookie)
            {
                Cookie::queue('la_analytics',$uuid);
                $model->is_unique = true;
            }
            else{
                $model->is_unique = LaConversion::where([
                    'name' => $model->name,
                    'hash' => $model->hash
                ])->exists();
            }

            $model->source = $model->source ?? Request::get('utm_source',null);
            $model->source = $model->url ?? Request::fullUrl();

            $model->hash = $uuid;
        });

        static::created(function ($model){
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $dd = new DeviceDetector($userAgent);
            $parser = new Parser();
            $dd->parse();

            $browser = $dd->getClient();
            $os = $dd->getOs();
            $type = $dd->getDeviceName();

            $locationData = $parser->ip_info();

            if(is_array($locationData))
            {
                $attributes['country'] = $locationData['country'];
                $attributes['city'] = $locationData['city'];
                $attributes['continent'] = $locationData['continent'];
                $attributes['timezone'] = $locationData['timezone'];
            }
            $languagesList = (array)$parser->parseLanguageList();

            $model->info()->create([
                "type" => $type,
                "os" => $os['name'],
                "browser" => $browser['name'],
                "version" => $browser['version'],
                "language" => reset($languagesList)[0],
                "user_agent" => $userAgent,
                "country" => $locationData['country'],
                "city" => $locationData['city'],
                "continent" => $locationData['continent'],
                "timezone" => $locationData['timezone'],
            ]);
        });
    }
}
