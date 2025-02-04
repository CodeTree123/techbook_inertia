<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use App\Constants\Status;

class WorkOrder extends Model
{
    use Searchable;
    use HasFactory;
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'em_id', 'id');
    }
    public function site()
    {
        return $this->belongsTo(CustomerSite::class, 'site_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'slug', 'id');
    }
    public function invoice()
    {
        return $this->belongsTo(CustomerInvoice::class, 'id', 'work_order_id');
    }
    public function workPerform()
    {
        return $this->hasMany(workOrderPerformed::class, 'work_order_id', 'id');
    }
    public function technician()
    {
        return $this->belongsTo(Technician::class, 'ftech_id', 'id');
    }

    public function subTicket()
    {
        return $this->belongsTo(SubTicket::class, 'id', 'work_order_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'wo_id');
    }

    public function docsForTech()
    {
        return $this->hasMany(DocForTechnician::class, 'wo_id');
    }

    public function schedules()
    {
        return $this->hasMany(WorkOrderSchedule::class, 'wo_id');
    }

    public function checkInOut()
    {
        return $this->hasMany(CheckInOut::class, 'work_order_id');
    }

    public function timeLogs()
    {
        return $this->hasMany(WorkOrderTimeLog::class, 'wo_id');
    }

    public function techRemoveReasons()
    {
        return $this->hasMany(TechDeletionReason::class, 'wo_id');
    }


    public function tasks()
    {
        return $this->hasMany(Task::class, 'wo_id');
    }

    public function shipments()
    {
        return $this->hasMany(OrderShipment::class, 'wo_id');
    }

    public function techProvidedParts()
    {
        return $this->hasMany(TechProvidedPart::class, 'wo_id');
    }

    public function assignedTech()
    {
        return $this->hasMany(AssignedEngineer::class, 'wo_id');
    }

    public function otherExpenses()
    {
        return $this->hasMany(OtherExpense::class, 'wo_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'wo_id');
    }

    public function contactedTechs()
    {
        return $this->hasMany(ContactedTechnician::class, 'wo_id');
    }
    //scope
    //status 
    public function scopePendingTicket($query)
    {
        return $query->where('status', Status::PENDING);
    }
    public function scopeContactedTicket($query)
    {
        return $query->where('status', Status::CONTACTED);
    }
    public function scopeConfirmedTicket($query)
    {
        return $query->where('status', Status::CONFIRM);
    }
    public function scopeAtRiskTicket($query)
    {
        return $query->where('status', Status::AT_RISK);
    }
    public function scopeDelayedTicket($query)
    {
        return $query->where('status', Status::DELAYED);
    }
    public function scopeOnHoldTicket($query)
    {
        return $query->where('status', Status::ON_HOLD);
    }
    public function scopeEnRouteTicket($query)
    {
        return $query->where('status', Status::EN_ROUTE);
    }
    public function scopeCheckedInTicket($query)
    {
        return $query->where('status', Status::CHECKED_IN);
    }
    public function scopeCheckedOutTicket($query)
    {
        return $query->where('status', Status::CHECKED_OUT);
    }
    public function scopeNeedsApprovalTicket($query)
    {
        return $query->where('status', Status::NEEDS_APPROVAL);
    }
    public function scopeIssueTicket($query)
    {
        return $query->where('status', Status::ISSUE);
    }
    public function scopeApprovedTicket($query)
    {
        return $query->where('status', Status::APPROVED);
    }
    public function scopeInvoicedTicket($query)
    {
        return $query->where('status', Status::INVOICED);
    }
    public function scopePastDueTicket($query)
    {
        return $query->where('status', Status::PAST_DUE);
    }
    public function scopePaidTicket($query)
    {
        return $query->where('status', Status::PAID);
    }

    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class, 'wo_id');
    }

    // public function scopePaidInvoice($query)
    // {
    //     return $query->whereHas('invoice', function ($paid) {
    //         $paid->where('status', Status::PAID);
    //     });
    // }
    // public function scopeDueInvoice($query)
    // {
    //     return $query->whereHas('invoice', function ($paid) {
    //         $paid->where('status', Status::UNPAID);
    //     });
    // }
    //stage
    public function scopeNewStage($query)
    {
        return $query->where('stage', Status::STAGE_NEW);
    }
    public function scopeNeedDispatchStage($query)
    {
        return $query->where('stage', Status::STAGE_NEED_DISPATCH);
    }
    public function scopeDispatchedStage($query)
    {
        return $query->where('stage', Status::STAGE_DISPATCH);
    }
    public function scopeClosedStage($query)
    {
        return $query->where('stage', Status::STAGE_CLOSED);
    }
    public function scopeBillingStage($query)
    {
        return $query->where('stage', Status::STAGE_BILLING);
    }

    
    //type
    public function scopeService($query)
    {
        return $query->where('order_type', Status::SERVICE);
    }
    public function scopeProject($query)
    {
        return $query->where('order_type', Status::PROJECT);
    }
    public function scopeInstall($query)
    {
        return $query->where('order_type', Status::INSTALL);
    }
}