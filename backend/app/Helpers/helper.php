<?php

use App\Common\Encrypter;
use Aws\S3\S3Client;
use Carbon\Carbon;

// if (! file_exists('image_path')) {
//     function image_path($value)
//     {
//         if (!$value) {
//             return null;
//         }

//         if (filter_var($value, FILTER_VALIDATE_URL)) {
//             return $value;
//         }

//         if (config('filesystems.default') == 'public') {
//             return \Storage::url($value);
//         }

//         $exploadePath = explode('/', $value);
//         unset($exploadePath[0]);
//         $implodePath = implode('/', $exploadePath);

//         $replaced = preg_replace('/\s+/', '+', $implodePath);
//         $path = preg_replace('/\:+/', '_', $replaced);

//         // $cloudfrontEndpoint = config('filesystems.disks.s3.cloudfront_endpoint');
//         $s3_url = config('filesystems.disks.s3.url');

//         $cloudfront_domain = config('filesystems.disks.s3.cloudfront_domain');

//         $s3_prefix = config('filesystems.disks.s3.s3_prefix');
//         return "{$s3_url}/{$s3_prefix}/{$path}";
//     }
// }

if (! function_exists('image_path')) {
    function image_path(string | null $filePath): string | null
    {
        $expiryMinutes = 1;
        if (!$filePath) {
            return null;
        }

        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return $filePath;
        }

        $s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret'),
            ],
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key'    => $filePath,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+' . $expiryMinutes . ' minutes');

        return (string) $request->getUri();
    }
}

if (!function_exists('startIndex')) {
    function startIndex($limit = 10)
    {
        return ((request()->page ?? 1) - 1) * $limit; // for pagination limit default 10
    }
}

if (!function_exists('get_today')) {
    function get_today($format = null)
    {
        return $format ? date($format) : date('Y');
    }
}


if (!function_exists('get_item_number')) {
    function get_item_number($key, $perPage = 15)
    {
        $currentPage = request()->page;
        $itemPerPage = $perPage;
        $key = $key + 1;

        if (!$currentPage || $currentPage == 1) {
            $start = 0;
        } else {
            $start = ($itemPerPage * request()->page) - $itemPerPage;
        }

        return $start + $key;
    }
}


if (!function_exists('is_valid_for_delete')) {
    function is_valid_for_delete($itemDate): bool
    {
        $month8th = Carbon::now()->startOfMonth()->addDays(7)->getTimestamp();
        $currentMonthDate = Carbon::now()->getTimestamp();

        $saleDateMonth = Carbon::parse($itemDate)->format('m');
        $previousMonth = Carbon::now()->subMonth()->format('m');

        if ($currentMonthDate >= $month8th && $saleDateMonth > $previousMonth) {
            return true;
        }

        if ($currentMonthDate < $month8th && $saleDateMonth >= $previousMonth) {
            return true;
        }

        return false;
    }
}

if (! function_exists('decrypt_api_request')) {
	
	function decrypt_api_request($response)
	{
		return (new Encrypter)->decrypt($response);
	}
}

if (! function_exists('encrypt_api_response')) {
	function encrypt_api_response($response)
	{
		$response = is_string($response) ? $response : $response->getContent();
		return (new Encrypter)->encrypt($response);
	}
}

if (! function_exists('toLocalTime')) {
    function toLocalTime($datetime, $timezone = null): Carbon | null
    {
        if (! $datetime) {
            return null;
        }

        if (is_string($datetime)) {
            $datetime = Carbon::parse($datetime);
        }

        $tz = $timezone ?? auth()->user()?->timezone() ?? config('app.timezone');

        return $datetime->timezone($tz);
    }
}

if (! function_exists('formatLocalTime')) {
    function formatLocalTime($datetime){
        return Carbon::parse(toLocalTime($datetime))?->format('M d, Y h:i A');
    }
}

if (! function_exists('formatDate')) {
    function formatDate($datetime, ?string $format = 'Y-m-d H:i:s'){
        return toLocalTime($datetime)?->format($format);
    }
}

if (! function_exists('formatRate')) {
    function formatRate($rate){
        $rate = (string) $rate;

        if (! str_contains($rate, '.')) {
            return number_format((float) $rate, 0, '.', ',');
        }

        [$whole, $fraction] = explode('.', $rate, 2);

        return number_format((float) $whole, 0, '.', ',') . '.' . $fraction;
    }
}