<?php

namespace App\Livewire;

use App\Models\SmsTemplate;
use Livewire\Component;
use Codepagol\SmsBridge\Facades\SmsBridge;

class SMSSetup extends Component
{
    public $smsTemps;

    public $smsTempList = [];

    public $profile;

    public $balance;

    // Load SMS templates initially
    public function mount()
    {
        if (! hasAccess(['Super Admin'], ['sms-setup'])) {
            abort(403, 'Unauthorized action.');
        }

        $this->profile = SmsBridge::profile();
        $this->balance = SmsBridge::balance();
        $this->smsTemps = SmsTemplate::all();
        // Map SMS template content for Livewire binding
        $this->smsTempList = SmsTemplate::pluck('template', 'id')->toArray();
    }

    // Toggle SMS template active status
    public function setSmsActive($id)
    {
        $smsTemplate = SmsTemplate::find($id);

        if ($smsTemplate) {
            $smsTemplate->is_active = ! $smsTemplate->is_active;
            $smsTemplate->save();
            flash()->success('SMS template status updated successfully.');
            // Refresh the templates list
            $this->smsTemps = SmsTemplate::all();
        }
    }

    // Update SMS template message
    public function updateSms($id)
    {
        $smsTemplate = SmsTemplate::find($id);

        if ($smsTemplate) {
            // Update with new content
            $smsTemplate->template = $this->smsTempList[$id] ?? $smsTemplate->template;
            $smsTemplate->save();
            flash()->success('SMS template updated successfully.');

            // Refresh the templates list
            $this->smsTemps = SmsTemplate::all();
        } else {
            flash()->error('SMS template not found.');
        }
    }

    public function render()
    {
        return view('livewire.s-m-s-setup')->layout('layouts.app');
    }
}
