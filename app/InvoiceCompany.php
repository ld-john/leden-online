<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InvoiceCompany
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InvoiceCompany whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceCompany extends Model
{
    //
}
