<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCategoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'app_category_translations';

    protected $connection = 'mysql';

}
