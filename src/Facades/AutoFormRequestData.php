<?php

namespace Vamsi\AutoFormRequestData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \VendorName\Skeleton\Skeleton
 */
class AutoFormRequestData extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Vamsi\AutoFormRequestData\AutoReqData::class;
    }
}
