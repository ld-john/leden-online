<?php

namespace App\Http\Livewire;

use App\Company;
use App\FitOption;
use App\Manufacturer;
use App\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderForm extends Component
{
	use WithFileUploads;

	public $name;
	public $company;
	public $preferred;
	public $make = 1;
	public $model;
	public $type;
	public $registration;
	public $derivative;
	public $engine;
	public $transmission;
	public $fuel_type;
	public $colour;
	public $body;
	public $trim;
	public $broker;
	public $broker_ref;
	public $order_ref;
	public $chassis_prefix;
	public $chassis;
	public $status;
	public $due_date;
	public $delivery_date;
	public $model_year;
	public $registered_date;
	public $ford_pipeline;
	public $factory_fit_options;
	public $factory_fit_name_manual_add;
	public $factory_fit_price_manual_add;
	public $dealer_fit_options;
	public $dealer_fit_name_manual_add;
	public $dealer_fit_price_manual_add;
	public $dealership;
	public $registration_company;
	public $invoice_company;
	public $list_price;
	public $metallic_paint;
	public $dealer_discount;
	public $manufacturer_discount;
	public $manufacturer_delivery_cost;
	public $first_reg_fee;
	public $rfl_cost;
	public $onward_delivery;
	public $invoice_funder_for;
	public $show_discount = "0";
	public $show_offer = "0";
	public $hide_from_broker = "0";
	public $hide_from_dealer = "0";
	public $invoice_finance;
	public $invoice_finance_number;
	public $finance_commission_paid;
	public $invoice_broker;
	public $invoice_broker_number;
	public $invoice_broker_paid;
	public $commission_broker;
	public $commission_broker_number;
	public $commission_broker_paid;
	public $customer_phone;
	public $delivery_address_1;
	public $delivery_address_2;
	public $delivery_town;
	public $delivery_city;
	public $delivery_county;
	public $delivery_postcode;
	public $comments;
	public $attachments = [];
	protected $rules = [
		'name' => 'sometimes',
		'company' => 'sometimes',
		'preferred' => 'sometimes',
	];

	public function newFactoryFit() {
		$factory_fit_option = new FitOption();
		$factory_fit_option->option_name = $this->factory_fit_name_manual_add;
		$factory_fit_option->option_price = $this->factory_fit_price_manual_add;
		$factory_fit_option->option_type = 'factory';
		$factory_fit_option->save();

		$this->factory_fit_options[] = strval( $factory_fit_option->id );

	}

	public function newDealerFit() {
		$dealer_fit_option = new FitOption();
		$dealer_fit_option->option_name = $this->dealer_fit_name_manual_add;
		$dealer_fit_option->option_price = $this->dealer_fit_price_manual_add;
		$dealer_fit_option->option_type = 'dealer';
		$dealer_fit_option->save();

		$this->dealer_fit_options[] = strval( $dealer_fit_option->id );
	}

	public function orderFormSubmit()
	{
		$order = $this->validate();

		// do magic here...

		dd($order);
	}

    public function render()
    {

	    $vehicles = Vehicle::latest()->get();
	    $companies = Company::latest()->get();
	    $fitoptions = FitOption::latest()->get();

	    $options = [
		    'manufacturers'     => Manufacturer::latest()->get(),
		    'types'             => $vehicles->pluck('type')->unique(),
		    'derivatives'       => $vehicles->pluck('derivative')->unique(),
	        'engines'           => $vehicles->pluck('engine')->unique(),
	        'transmissions'     => $vehicles->pluck('transmission')->unique(),
	        'fuel_types'        => $vehicles->pluck('fuel_type')->unique(),
		    'colours'           => $vehicles->pluck('colour')->unique(),
		    'bodies'            => $vehicles->pluck('body')->unique(),
		    'trims'             => $vehicles->pluck('trim')->unique(),

		    'brokers'                => $companies->where('company_type', 'broker'),
		    'dealers'                => $companies->where('company_type', 'dealer'),
		    'registration_companies' => $companies->where('company_type', 'registration'),
		    'invoice_companies'      => $companies->where('company_type', 'invoice'),

		    'factory_options'   => $fitoptions->where('option_type', 'factory'),
		    'dealer_options'    => $fitoptions->where('option_type', 'dealer')
	    ];
        return view('livewire.order-form', $options );
    }
}
