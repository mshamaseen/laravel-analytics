<?php
namespace Shamaseen\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Shamaseen\Analytics\Models\LaInfo
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $os
 * @property string|null $browser
 * @property string|null $version
 * @property string|null $language
 * @property string|null $user_agent
 * @property string|null $is_touch
 * @property int $conversion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereConversionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereIsTouch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LaInfo whereVersion($value)
 * @mixin \Eloquent
 */
class LaInfo extends Model
{
    protected $table = 'la_device_info';
    protected $guarded = [];
}
