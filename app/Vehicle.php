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

    public function getFitOptions( $type = 'factory' )
    {
    	if ( $type == 'factory') {
    		$fitType = $this->factory_fit_options;
	    } elseif ( $type == 'dealer') {
            $fitType = $this->dealer_fit_options;
	    } else {
	            //
	    }


    	if ( isset ( $fitType) && $fitType !== '' ) {
		    $fitOptions = FitOption::select('option_name', 'option_price')->where('option_type', $type)->whereIn('id', json_decode($fitType))->get();
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
