<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\RequiredConfig;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use App\Services\Gateway\GatewayManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AutomaticGatewayController extends Controller {

    protected GatewayManagerService $gatewayService;

    public function __construct(GatewayManagerService $gatewayService) {
        $this->gatewayService = $gatewayService;
    }

    public function index(Request $request) {
        $pageTitle = 'Automatic Gateways';
        $perPage = (int) $request->get('per_page', 4);
        if (!in_array($perPage, [4, 8, 12, 20, 50])) {
            $perPage = 4;
        }

        $query = Gateway::automatic()->with('currencies');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('alias', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('status', Status::ENABLE);
            } elseif ($status === 'inactive') {
                $query->where('status', Status::DISABLE);
            }
        }

        $gateways = $query->orderBy('name', 'asc')->paginate($perPage)->withQueryString();
        return view('admin.gateways.automatic.list', compact('pageTitle', 'gateways'));
    }

    public function create() {
        $pageTitle = 'Add New Automatic Gateway';
        $defaultCurrencies = [
            'USD' => '$',
            'TZS' => 'TSh',
            'KES' => 'KSh',
            'EUR' => '€',
            'GBP' => '£',
            'NGN' => '₦',
            'UGX' => 'USh',
            'GHS' => 'GH₵',
            'ZAR' => 'R',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'INR' => '₹',
        ];
        return view('admin.gateways.automatic.create', compact('pageTitle', 'defaultCurrencies'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'  => 'required|string|max:100',
            'alias' => 'required|string|max:100|alpha_dash|unique:gateways,alias',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        // Calculate unique code < 1000
        $maxCode = Gateway::automatic()->max(DB::raw('CAST(code AS UNSIGNED)'));
        $newCode = $maxCode ? max(800, (int)$maxCode + 1) : 800;
        while (Gateway::where('code', (string)$newCode)->exists()) {
            $newCode++;
        }

        // Process Dynamic Credentials / Parameters
        $paramList = [];
        if ($request->has('credentials') && is_array($request->credentials)) {
            foreach ($request->credentials as $item) {
                $key = strtolower(trim($item['key'] ?? ''));
                if (!empty($key)) {
                    $paramList[$key] = [
                        'title'  => trim($item['title'] ?? ucwords(str_replace('_', ' ', $key))),
                        'global' => !empty($item['global']),
                        'value'  => $item['value'] ?? '',
                    ];
                }
            }
        }

        // Process Supported Currencies
        $supported = [];
        if ($request->has('supported_currencies')) {
            $currenciesInput = is_array($request->supported_currencies) 
                ? $request->supported_currencies 
                : explode(',', $request->supported_currencies);

            foreach ($currenciesInput as $cur) {
                $cur = strtoupper(trim($cur));
                if (!empty($cur)) {
                    $supported[$cur] = $request->currency_symbols[$cur] ?? $cur;
                }
            }
        }
        if (empty($supported)) {
            $supported = ['USD' => '$', 'TZS' => 'TSh'];
        }

        $filename = null;
        if ($request->hasFile('image')) {
            try {
                $filename = fileUploader($request->image, getFilePath('gateway'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify)->withInput();
            }
        }

        // Package Dependencies and Custom Tables metadata
        $extra = [];
        if ($request->filled('packages')) {
            $extra['packages'] = array_values(array_filter(array_map('trim', explode(',', $request->packages))));
        }
        if ($request->filled('custom_tables')) {
            $extra['custom_tables'] = array_values(array_filter(array_map('trim', explode(',', $request->custom_tables))));
        }

        $gateway                       = new Gateway();
        $gateway->code                 = (string) $newCode;
        $gateway->name                 = $request->name;
        $gateway->alias                = strtolower($request->alias);
        $gateway->image                = $filename;
        $gateway->status               = Status::ENABLE;
        $gateway->gateway_parameters   = json_encode($paramList);
        $gateway->supported_currencies = json_encode($supported);
        $gateway->crypto               = Status::DISABLE;
        $gateway->description          = $request->description;
        $gateway->extra                = (object) $extra;
        $gateway->save();

        // Optional initial currency configuration
        if ($request->filled('init_currency')) {
            $curCode = strtoupper($request->init_currency);
            $gatewayCurrency                    = new GatewayCurrency();
            $gatewayCurrency->name              = $gateway->name . ' ' . $curCode;
            $gatewayCurrency->gateway_alias     = $gateway->alias;
            $gatewayCurrency->currency          = $curCode;
            $gatewayCurrency->min_amount        = $request->init_min_amount ?? 1;
            $gatewayCurrency->max_amount        = $request->init_max_amount ?? 1000000;
            $gatewayCurrency->fixed_charge      = $request->init_fixed_charge ?? 0;
            $gatewayCurrency->percent_charge    = $request->init_percent_charge ?? 0;
            $gatewayCurrency->rate              = $request->init_rate ?? 1;
            $gatewayCurrency->symbol            = $request->init_symbol ?? '$';
            $gatewayCurrency->method_code       = $gateway->code;

            $cParams = [];
            foreach ($paramList as $pk => $pv) {
                $cParams[$pk] = $pv['value'] ?? '';
            }
            $gatewayCurrency->gateway_parameter = json_encode($cParams);
            $gatewayCurrency->save();
        }

        // Auto-scaffold dedicated ProcessController via GatewayManagerService
        $this->gatewayService->scaffoldProcessController($gateway);

        $notify[] = ['success', $gateway->name . ' gateway created successfully'];
        return to_route('admin.gateway.automatic.edit', $gateway->alias)->withNotify($notify);
    }

    public function edit($alias) {
        $gateway   = Gateway::automatic()->with('currencies', 'currencies.method')->where('alias', $alias)->firstOrFail();
        $pageTitle = 'Update Gateway - ' . $gateway->name;

        $supportedCurrencies = collect($gateway->supported_currencies)->except($gateway->currencies->pluck('currency'));
        $rawParams           = json_decode($gateway->gateway_parameters);
        $paramList           = [];
        if ($rawParams) {
            foreach ($rawParams as $key => $param) {
                if (is_object($param) && isset($param->title)) {
                    $paramList[$key] = $param;
                } elseif (is_string($param)) {
                    $item = new \stdClass();
                    $item->title = ucwords(str_replace('_', ' ', $key));
                    $item->global = true;
                    $item->value = $param;
                    $paramList[$key] = $item;
                }
            }
        }
        $parameters          = collect($paramList);
        $globalParameters    = null;
        $hasCurrencies       = false;
        $currencyIndex       = 1;

        if ($gateway->currencies->count()) {
            $globalParameters = json_decode($gateway->currencies->first()->gateway_parameter);
            $hasCurrencies    = true;
        }

        return view('admin.gateways.automatic.edit', compact('pageTitle', 'gateway', 'supportedCurrencies', 'parameters', 'hasCurrencies', 'currencyIndex', 'globalParameters'));
    }

    public function update(Request $request, $code) {
        $gateway = Gateway::where('code', $code)->firstOrFail();
        $this->gatewayValidator($request)->validate();
        $this->gatewayCurrencyValidator($request, $gateway)->validate();

        $rawParams = json_decode($gateway->gateway_parameters);
        $paramList = [];
        if ($rawParams) {
            foreach ($rawParams as $key => $param) {
                if (is_object($param) && isset($param->title)) {
                    $paramList[$key] = $param;
                } elseif (is_string($param)) {
                    $item = new \stdClass();
                    $item->title = ucwords(str_replace('_', ' ', $key));
                    $item->global = true;
                    $item->value = $param;
                    $paramList[$key] = $item;
                }
            }
        }
        $parameters = collect($paramList);

        // Update values of global parameters
        foreach ($parameters->where('global', true) as $key => $pram) {
            $parameters[$key]->value = $request->global[$key] ?? $pram->value;
        }

        // Incorporate dynamically added or edited credential parameters from GUI
        if ($request->has('credentials') && is_array($request->credentials)) {
            $customMap = [];
            foreach ($request->credentials as $item) {
                $pKey = strtolower(trim($item['key'] ?? ''));
                if (!empty($pKey)) {
                    $obj = new \stdClass();
                    $obj->title  = trim($item['title'] ?? ucwords(str_replace('_', ' ', $pKey)));
                    $obj->global = !empty($item['global']);
                    $obj->value  = $item['value'] ?? ($request->global[$pKey] ?? '');
                    $customMap[$pKey] = $obj;
                }
            }
            if (!empty($customMap)) {
                $parameters = collect($customMap);
            }
        }

        $filename = $gateway->image;
        if ($request->hasFile('image')) {
            try {
                $filename = fileUploader($request->image, getFilePath('gateway'), old: $filename);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded'];
                return back()->withNotify($notify);
            }
        }

        if ($request->filled('name')) {
            $gateway->name = $request->name;
        }

        if ($request->has('description')) {
            $gateway->description = $request->description;
        }

        // Packages & Custom Tables
        $extra = is_object($gateway->extra) ? (array) $gateway->extra : ($gateway->extra ?? []);
        if ($request->has('packages')) {
            $extra['packages'] = array_values(array_filter(array_map('trim', explode(',', $request->packages))));
        }
        if ($request->has('custom_tables')) {
            $extra['custom_tables'] = array_values(array_filter(array_map('trim', explode(',', $request->custom_tables))));
        }
        $gateway->extra = (object) $extra;

        $gateway->alias              = $request->alias;
        $gateway->gateway_parameters = json_encode($parameters);
        $gateway->image              = $filename;
        $gateway->save();

        if ($request->has('currency')) {
            $gateway->currencies()->delete();

            foreach ($request->currency as $key => $currency) {
                $param = [];
                foreach ($parameters->where('global', true) as $pkey => $pram) {
                    $param[$pkey] = $pram->value;
                }

                foreach ($parameters->where('global', false) as $paramKey => $paramValue) {
                    $param[$paramKey] = $currency['param'][$paramKey] ?? '';
                }

                $gatewayCurrency                    = new GatewayCurrency();
                $gatewayCurrency->name              = $currency['name'];
                $gatewayCurrency->gateway_alias     = $gateway->alias;
                $gatewayCurrency->currency          = $currency['currency'];
                $gatewayCurrency->min_amount        = $currency['min_amount'];
                $gatewayCurrency->max_amount        = $currency['max_amount'];
                $gatewayCurrency->fixed_charge      = $currency['fixed_charge'];
                $gatewayCurrency->percent_charge    = $currency['percent_charge'];
                $gatewayCurrency->rate              = $currency['rate'];
                $gatewayCurrency->symbol            = $currency['symbol'];
                $gatewayCurrency->method_code       = $code;
                $gatewayCurrency->gateway_parameter = json_encode($param);
                $gatewayCurrency->save();
            }
        }

        // Ensure ProcessController exists
        $this->gatewayService->scaffoldProcessController($gateway);

        RequiredConfig::configured('deposit_method');

        $notify[] = ['success', $gateway->name . ' updated successfully'];
        return to_route('admin.gateway.automatic.edit', $gateway->alias)->withNotify($notify);
    }

    /**
     * Delete Gateway with complete Codebase, Dependency, and Table cleanup via GatewayManagerService
     */
    public function delete(Request $request, $id) {
        $gateway = Gateway::automatic()->findOrFail($id);

        $result = $this->gatewayService->deleteGateway($gateway, [
            'remove_files'    => (bool) $request->input('remove_files', 1),
            'remove_packages' => (bool) $request->input('remove_packages', 1),
            'remove_tables'   => (bool) $request->input('remove_tables', 1),
        ]);

        $notify[] = ['success', $result['message']];
        return back()->withNotify($notify);
    }

    public function remove($id) {
        $gatewayCurrency = GatewayCurrency::findOrFail($id);
        fileManager()->removeFile(getFilePath('gateway') . '/' . $gatewayCurrency->image);
        $gatewayCurrency->delete();
        $notify[] = ['success', 'Gateway currency removed successfully'];
        return back()->withNotify($notify);
    }

    public function status($id) {
        return Gateway::changeStatus($id);
    }

    public function gatewayValidator(Request $request) {
        $validationRule = [
            'alias' => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];
        return Validator::make($request->all(), $validationRule);
    }

    public function gatewayCurrencyValidator(Request $request, Gateway $gateway) {
        $customAttributes = [];
        $validationRule   = [];

        $rawParams    = json_decode($gateway->gateway_parameters);
        $paramListArr = [];
        if ($rawParams) {
            foreach ($rawParams as $key => $param) {
                if (is_object($param) && isset($param->title)) {
                    $paramListArr[$key] = $param;
                } elseif (is_string($param)) {
                    $item = new \stdClass();
                    $item->title = ucwords(str_replace('_', ' ', $key));
                    $item->global = true;
                    $item->value = $param;
                    $paramListArr[$key] = $item;
                }
            }
        }
        $paramList = collect($paramListArr);

        foreach ($paramList->where('global', true) as $key => $pram) {
            if ($request->has('global.' . $key)) {
                $validationRule['global.' . $key]   = 'required';
                $customAttributes['global.' . $key] = keyToTitle(is_object($pram) ? $pram->title : $key);
            }
        }

        if ($request->has('currency')) {
            foreach ($request->currency as $key => $currency) {
                $validationRule['currency.' . $key . '.currency']       = 'required|string';
                $validationRule['currency.' . $key . '.symbol']         = 'required|string';
                $validationRule['currency.' . $key . '.name']           = 'required';
                $validationRule['currency.' . $key . '.min_amount']     = 'required|numeric|gt:0|lte:currency.' . $key . '.max_amount';
                $validationRule['currency.' . $key . '.max_amount']     = 'required|numeric|gt:0|gte:currency.' . $key . '.min_amount';
                $validationRule['currency.' . $key . '.fixed_charge']   = 'required|numeric|gte:0';
                $validationRule['currency.' . $key . '.percent_charge'] = 'required|numeric|gte:0|max:100';
                $validationRule['currency.' . $key . '.rate']           = 'required|numeric|gt:0';

                $currencyIdentifier = $this->currencyIdentifier($currency['name'] ?? null, $gateway->name . ' ' . ($currency['currency'] ?? ''));

                $customAttributes['currency.' . $key . '.name']           = $currencyIdentifier . ' name';
                $customAttributes['currency.' . $key . '.min_amount']     = $currencyIdentifier . ' ' . keyToTitle('min_amount');
                $customAttributes['currency.' . $key . '.max_amount']     = $currencyIdentifier . ' ' . keyToTitle('max_amount');
                $customAttributes['currency.' . $key . '.fixed_charge']   = $currencyIdentifier . ' ' . keyToTitle('fixed_charge');
                $customAttributes['currency.' . $key . '.percent_charge'] = $currencyIdentifier . ' ' . keyToTitle('percent_charge');
                $customAttributes['currency.' . $key . '.rate']           = $currencyIdentifier . ' ' . keyToTitle('rate');
                $customAttributes['currency.' . $key . '.currency']       = $currencyIdentifier . ' ' . keyToTitle('currency');
                $customAttributes['currency.' . $key . '.symbol']         = $currencyIdentifier . ' ' . keyToTitle('symbol');

                foreach ($paramList->where('global', false) as $param_key => $param_value) {
                    $validationRule['currency.' . $key . '.param.' . $param_key]   = 'required';
                    $customAttributes['currency.' . $key . '.param.' . $param_key] = $currencyIdentifier . ' ' . keyToTitle(is_object($param_value) ? @$param_value->title : $param_key);
                }
            }
        }

        return Validator::make($request->all(), $validationRule, $customAttributes);
    }

    private function currencyIdentifier($name, $default = '') {
        return $name ?? $default;
    }
}