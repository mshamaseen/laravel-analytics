<?php
namespace Shamaseen\Analytics\Traits;

use Shamaseen\Analytics\Models\LaConversion;
use Shamaseen\Analytics\Repositories\Statistics;

/**
 * @extends \Illuminate\Database\Eloquent\Model
 */
trait Conversionable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function la_conversions()
    {
        return $this->morphMany(LaConversion::class,'conversionable');
    }

    public function la_statistics($name,$start_at = null, $end_at = null): Statistics
    {
        $statistics = new Statistics($name,$start_at, $end_at);
        $statistics->bind($this);

        return $statistics;
    }
}
