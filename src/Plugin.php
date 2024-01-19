<?php

namespace NawrasBukhari\Postpay;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('postpays');
        Schema::dropIfExists('postpays_translations');
    }
}
