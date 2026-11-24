<?php

namespace App\Routing;

use App\Models\Company;
use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * Get the URL to a named route.
     *
     * @param  \BackedEnum|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $nameStr = $name instanceof \BackedEnum ? $name->value : (string) $name;

        if ($nameStr === 'company') {
            if ($parameters instanceof Company) {
                $parameters = $parameters->routeParams();
            } elseif (is_array($parameters) && count($parameters) === 1 && isset($parameters[0]) && $parameters[0] instanceof Company) {
                $parameters = $parameters[0]->routeParams();
            } elseif (is_string($parameters) || is_numeric($parameters)) {
                $raw = (string) $parameters;
                $decoded = urldecode($raw);
                $company = Company::where('slug', $raw)
                    ->orWhere('slug', $decoded)
                    ->when(is_numeric($raw), fn($q) => $q->orWhere('id', (int) $raw))
                    ->first();
                if ($company) {
                    $parameters = $company->routeParams();
                } else {
                    $parameters = [
                        'role' => 'شرکت',
                        'category' => 'عمومی',
                        'company' => $raw,
                    ];
                }
            }
        }

        return parent::route($name, $parameters, $absolute);
    }
}
