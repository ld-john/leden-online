<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Company
 *
 * @property int $id
 * @property string $company_name
 * @property string $company_address1
 * @property string|null $company_address2
 * @property string $company_city
 * @property string $company_county
 * @property string|null $company_country
 * @property string $company_postcode
 * @property string $company_email
 * @property string|null $company_phone
 * @property string $company_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyAddress1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyPostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCompanyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    protected $table = 'company';
}
