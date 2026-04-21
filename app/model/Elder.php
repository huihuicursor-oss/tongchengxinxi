<?php
declare(strict_types=1);

namespace app\model;

use think\Model;
use think\model\relation\HasMany;

class Elder extends Model
{
    protected $name = 'elders';
    protected $pk = 'id';
    protected $autoWriteTimestamp = 'datetime';

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class, 'elder_id', 'id');
    }

    public function serviceOrders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'elder_id', 'id');
    }

    public function familyContacts(): HasMany
    {
        return $this->hasMany(FamilyContact::class, 'elder_id', 'id');
    }

    public function medicationPlans(): HasMany
    {
        return $this->hasMany(MedicationPlan::class, 'elder_id', 'id');
    }

    public function careTasks(): HasMany
    {
        return $this->hasMany(CareTask::class, 'elder_id', 'id');
    }

    public function billingRecords(): HasMany
    {
        return $this->hasMany(BillingRecord::class, 'elder_id', 'id');
    }
}
