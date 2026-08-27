<?php

namespace App\Traits;

use App\Models\CustomFieldValue;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCustomFields
{
    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    public function setCustomField(int $customFieldId, $value)
    {
        $this->customFieldValues()->updateOrCreate(
            ['custom_field_id' => $customFieldId],
            ['value' => $value]
        );
    }

    public function getCustomField(int $customFieldId)
    {
        $cf = $this->customFieldValues()->where('custom_field_id', $customFieldId)->first();
        return $cf ? $cf->value : null;
    }
}
