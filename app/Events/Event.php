<?php

namespace AwebCore\App\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
