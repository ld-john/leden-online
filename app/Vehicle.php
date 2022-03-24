<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $attributes = [
        'factory_fit_options' => [],
        'dealer_fit_options' => [],
    ];


    /**
     * @var mixed|string
     */

    public function order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Order::class, 'vehicle_id', 'id' );
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class, 'id', 'make');
    }

    public function dealer()
    {
        return $this->belongsTo(Company::class, 'dealer_id', 'id');
    }

    public function broker()
    {
        return $this->belongsTo(Company::class, 'broker_id', 'id');
    }

    public function status()
    {
        switch($this->vehicle_status) {
            case (1):
                return 'In Stock';
            case(3):
                return 'Ready for Delivery';
            case(4):
                return 'Factory Order';
            case(6) :
                return 'Delivery Booked';
            case(7):
                return 'Completed Orders';
            case(10):
                return 'Europe VHC';
            case (12):
                return 'At Converter';
            case (13):
                return 'Awaiting Ship';
            default :
                return 'UK VHC';
        }
    }

    public function getFitOptions( $type = 'factory' )
    {
        if ( $type == 'factory') {
            $fitType = $this->factory_fit_options;
        } elseif ( $type == 'dealer') {
            $fitType = $this->dealer_fit_options;
        }

        if ( isset ( $fitType) && $fitType !== '' ) {

            while (gettype($fitType) === 'string') {
                $fitType = json_decode($fitType);
            }
            $fitOptions = FitOption::select('option_name', 'option_price')->where('option_type', $type)->whereIn('id', $fitType)->get();
        } else {
            return [];
        }

        return $fitOptions;
    }

    public function niceName()
    {
        return $this->manufacturer->name . ' '.  $this->model . ' ' . $this->derivative;
    }

}
