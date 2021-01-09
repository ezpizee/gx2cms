<?php

class GX2CMSAdminHelper
{
    public static function assetRoot(): string {
        $arr = explode('/administrator/', dirname(__DIR__));
        return '/administrator/'.$arr[1].'/asset';
    }
}