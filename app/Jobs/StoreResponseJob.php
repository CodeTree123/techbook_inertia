<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\TechDistance;
use Illuminate\Support\Facades\DB;

class StoreResponseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $lat;
    protected $lon;
    protected $address;
    protected $response;
    public function __construct($lat, $lon, $address, $response)
    {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->address = $address;
        $this->response = $response;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            $techDistance = new TechDistance();
            $techDistance->tech_id = $this->response['id'];
            $techDistance->destination_coordinates = DB::raw("ST_GeomFromText('POINT($this->lon $this->lat)', 4326)");
            $techDistance->distance = $this->response['distance'];
            $techDistance->destination_address = $this->address;
            $techDistance->duration = $this->response['duration'];
            $techDistance->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
