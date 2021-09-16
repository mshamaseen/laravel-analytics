<?php

namespace Shamaseen\Analytics\Repositories;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Exception;
use Illuminate\Support\Collection;
use Shamaseen\Analytics\Models\LaConversion;

class Statistics
{
    private $name;
    private $start_at;
    private $end_at;

    public static $COLLECTION_RESPONSE = 'collection';
    public static $ARRAY_RESPONSE = 'array';

    /**
     * @var string
     */
    private $response;
    private $morph = null;

    public function __construct($name, $start_at = null, $end_at = null)
    {
        $this->start_at = $start_at ?? now()->startOfMonth();
        $this->end_at = $end_at ?? now()->endOfMonth();
        $this->name = $name;
        $this->response = 'collection';
    }

    /**
     * @param $response
     * @return Statistics
     * @throws Exception
     */
    function setResponse($response): Statistics
    {
        if($response !== self::$ARRAY_RESPONSE && $response !== self::$COLLECTION_RESPONSE)
            throw new Exception('Unknown response type');

        $this->response = $response;
        return $this;
    }

    /**
     * Bind the queries to a model
     * @param object| \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    function bind(object $model): Statistics
    {
        $this->morph = $model;

        return $this;
    }

    private function builder()
    {
        $builder = LaConversion::query();

        if($this->morph)
        {
            $builder->where([
                'conversionable_type' => get_class($this->morph),
                'conversionable_id' => $this->morph->id
            ]);
        }

        return $builder;
    }

    public function sourcesCount()
    {
        return $this->response(
            $this->builder()->whereBetween('created_at',[$this->start_at,$this->end_at])
            ->groupBy('source')->get(['source', DB::raw('count(*) as counts')])
        );
    }

    function countriesCount(){
        return $this->response(
            $this->builder()->
            join('la_device_info as df','la_conversions.id','=','df.conversion_id')
                ->whereBetween('la_conversions.created_at',[$this->start_at,$this->end_at])
                ->groupBy('country')->get(['country', DB::raw('count(*) as counts')])
        );
    }

    function citiesCount(){
        return $this->response(
            $this->builder()->
            join('la_device_info as df','la_conversions.id','=','df.conversion_id')
                ->whereBetween('la_conversions.created_at',[$this->start_at,$this->end_at])
                ->groupBy('city')->get(['city', DB::raw('count(*) as counts')])
        );
    }

    function continentCount(){
        return $this->response(
            $this->builder()->
            join('la_device_info as df','la_conversions.id','=','df.conversion_id')
                ->whereBetween('la_conversions.created_at',[$this->start_at,$this->end_at])
                ->groupBy('continent')->get(['continent', DB::raw('count(*) as counts')])
        );
    }

    function timezoneCount(){
        return $this->response(
            $this->builder()->
            join('la_device_info as df','la_conversions.id','=','df.conversion_id')
                ->whereBetween('la_conversions.created_at',[$this->start_at,$this->end_at])
                ->groupBy('timezone')->get(['timezone', DB::raw('count(*) as counts')])
        );
    }

    /**
     * @return array[]|string[][]
     */
    public function conversionsOverTime()
    {
        $conversions = $this->builder()->where('name',$this->name)->whereBetween('created_at',[$this->start_at,$this->end_at])->get();
        return $this->periodChart($conversions);
    }

    /**
     * @param Collection $conversions
     * @param array $keys
     * @return Collection
     */
    private function periodChart($conversions, $keys = [])
    {
        $data = $conversions->countBy(function ($conversion){
            return Carbon::parse($conversion->created_at)->format('Y-m-d');
        });

        $period = CarbonPeriod::create($this->start_at, $this->end_at);
        $keys = empty($keys) ? ['Date','Conversions'] : $keys;

        $formattedValues = collect([$keys]);

        foreach ($period as $date) {
            /**
             * @var Carbon $date
             */
            if(isset($data[$date->format('Y-m-d')])) {
                $formattedValues->push([
                    $date->format('Y-m-d'),
                    $data[$date->format('Y-m-d')]
                ]);
            }
            else{
                $formattedValues->push([
                    $date->format('Y-m-d'),
                    0,
                ]);
            }
        }

        return $this->response($formattedValues);
    }

    /**
     * @param Collection $collection
     * @return array|Collection
     */
    private function response($collection)
    {
        if($this->response === self::$ARRAY_RESPONSE)
        {
            return $collection->toArray();
        }

        return  $collection;
    }
}
