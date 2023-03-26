<?php

namespace KoalaFacade\DiamondConsole\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use KoalaFacade\DiamondConsole\Foundation\Models\Observable;

class Model extends BaseModel
{
    use Observable;
}
