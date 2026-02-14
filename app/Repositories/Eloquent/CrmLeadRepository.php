<?php

namespace App\Repositories\Eloquent;

use App\Models\CrmLead;
use App\Repositories\CrmLeadRepositoryInterface;

class CrmLeadRepository extends BaseRepository implements CrmLeadRepositoryInterface
{
    public function __construct(CrmLead $model)
    {
        parent::__construct($model);
    }

    public function getLeadsByUserId($userId, $perPage = 10, $filters = [])
    {
        $query = $this->model->where('user_id', $userId)
                             ->with('customer'); // Eager load customer

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['lead_type'])) {
            $query->where('lead_type', $filters['lead_type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
