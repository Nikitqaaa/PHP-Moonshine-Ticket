<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('archive-tickets')
    ->yearlyOn(12, 31, '23:59:59');
