<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ContactGroup;
use App\Models\Contact;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ContactManager extends Component
{
    public $tenant;
    public $currentView = 'groups'; // 'groups', 'contacts', 'form_builder'
    public $activeGroupId = null;
    
    // Groups CRUD
    public $isEditingGroup = false;
    public $groupName = '';

    // Contact form (dynamic)
    public $isEditingContact = false;
    public $contactId = null;
    public $contactName = '';
    public $contactPhone = '';
    public $dynamicFieldsData = []; // [custom_field_id => value]

    // Form Builder
    public $newFieldName = '';
    public $newFieldType = 'string';
    public $newFieldRequired = false;
    public $newFieldOptions = ''; // Comma separated

    public function mount()
    {
        $this->tenant = Tenant::findOrFail(Auth::user()->current_tenant_id);
    }

    public function setView($view, $groupId = null)
    {
        $this->currentView = $view;
        if ($groupId) {
            $this->activeGroupId = $groupId;
        }
    }

    // --- Groups Management ---
    public function saveGroup()
    {
        $this->validate(['groupName' => 'required|string|max:255']);
        
        ContactGroup::create([
            'name' => $this->groupName,
            'tenant_id' => $this->tenant->id
        ]);
        
        $this->isEditingGroup = false;
        $this->groupName = '';
    }

    public function deleteGroup($id)
    {
        ContactGroup::findOrFail($id)->delete();
        if ($this->activeGroupId == $id) {
            $this->activeGroupId = null;
            $this->currentView = 'groups';
        }
    }

    // --- Form Builder ---
    public function saveCustomField()
    {
        $this->validate([
            'newFieldName' => 'required|string|max:255',
            'newFieldType' => 'required|in:string,text,number,date,boolean,select',
        ]);

        $options = null;
        if ($this->newFieldType === 'select' && !empty($this->newFieldOptions)) {
            $options = array_map('trim', explode(',', $this->newFieldOptions));
        }

        CustomField::create([
            'tenant_id' => $this->tenant->id,
            'model_type' => 'App\Models\Contact',
            'contact_group_id' => $this->activeGroupId,
            'name' => $this->newFieldName,
            'type' => $this->newFieldType,
            'is_required' => $this->newFieldRequired,
            'options' => $options ? json_encode($options, JSON_UNESCAPED_UNICODE) : null,
        ]);

        $this->reset(['newFieldName', 'newFieldType', 'newFieldRequired', 'newFieldOptions']);
    }

    public function deleteCustomField($id)
    {
        CustomField::findOrFail($id)->delete();
    }

    // --- Contacts Management ---
    public function editContact($id = null)
    {
        $this->isEditingContact = true;
        if ($id) {
            $contact = Contact::findOrFail($id);
            $this->contactId = $contact->id;
            $this->contactName = $contact->name;
            $this->contactPhone = $contact->phone;
            
            $this->dynamicFieldsData = [];
            foreach ($contact->customFieldValues as $cfv) {
                $this->dynamicFieldsData[$cfv->custom_field_id] = $cfv->value;
            }
        } else {
            $this->contactId = null;
            $this->contactName = '';
            $this->contactPhone = '';
            $this->dynamicFieldsData = [];
            
            if ($this->activeGroupId) {
                $fields = CustomField::where('contact_group_id', $this->activeGroupId)->get();
                foreach($fields as $f) {
                    $this->dynamicFieldsData[$f->id] = '';
                }
            }
        }
    }

    public function saveContact()
    {
        $rules = [
            'contactName' => 'required|string|max:255',
            'contactPhone' => 'required|string|max:255',
        ];

        // Validate custom fields
        $fields = CustomField::where('contact_group_id', $this->activeGroupId)->get();
        foreach ($fields as $field) {
            if ($field->is_required) {
                $rules['dynamicFieldsData.'.$field->id] = 'required';
            }
        }

        $this->validate($rules);

        $contact = Contact::updateOrCreate(
            ['id' => $this->contactId],
            [
                'tenant_id' => $this->tenant->id,
                'contact_group_id' => $this->activeGroupId,
                'name' => $this->contactName,
                'phone' => $this->contactPhone,
            ]
        );

        foreach ($this->dynamicFieldsData as $fieldId => $value) {
            $contact->setCustomField($fieldId, $value);
        }

        $this->isEditingContact = false;
        $this->editContact(null); // Reset
    }

    public function deleteContact($id)
    {
        Contact::findOrFail($id)->delete();
    }

    public function render()
    {
        $groups = ContactGroup::where('tenant_id', $this->tenant->id)->get();
        
        $activeGroup = null;
        $customFields = collect();
        $contacts = collect();
        
        if ($this->activeGroupId) {
            $activeGroup = ContactGroup::find($this->activeGroupId);
            $customFields = CustomField::where('contact_group_id', $this->activeGroupId)->get();
            $contacts = Contact::where('contact_group_id', $this->activeGroupId)->get();
        }

        return view('livewire.contact-manager', compact('groups', 'activeGroup', 'customFields', 'contacts'))
            ->layout('layouts.app');
    }
}
