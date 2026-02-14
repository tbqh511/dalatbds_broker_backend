<?php

namespace App\Services;

use App\Repositories\CrmLeadRepositoryInterface;
use App\Models\CrmCustomer;
use Illuminate\Support\Facades\DB;
use Exception;

class CrmLeadService
{
    protected $leadRepository;

    public function __construct(CrmLeadRepositoryInterface $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function getLeads($userId, $perPage = 10, $filters = [])
    {
        return $this->leadRepository->getLeadsByUserId($userId, $perPage, $filters);
    }

    public function createLead($data, $userId)
    {
        DB::beginTransaction();
        try {
            // Check if customer exists or create new one
            // Assuming data contains customer info: name, phone
            $customerData = [
                'full_name' => $data['name'] ?? 'Unknown',
                'contact' => $data['phone'],
                // Add other customer fields if available
            ];
            
            // Simple logic to find or create customer by phone
            $customer = CrmCustomer::firstOrCreate(
                ['contact' => $data['phone']],
                $customerData
            );

            $leadData = [
                'user_id' => $userId,
                'customer_id' => $customer->id,
                'lead_type' => $data['lead_type'],
                'status' => 'new',
                'source_note' => $data['note'] ?? '',
                'demand_rate_min' => $data['price_min'] ?? 0,
                'demand_rate_max' => $data['price_max'] ?? 0,
                // Add other fields
            ];

            $lead = $this->leadRepository->create($leadData);
            
            DB::commit();
            return $lead;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateLead($id, $data)
    {
        DB::beginTransaction();
        try {
            $lead = $this->leadRepository->find($id);
            if (!$lead) return null;

            // Update Customer if data provided
            if (isset($data['customer'])) {
                if ($lead->customer) {
                    $lead->customer->update($data['customer']);
                }
                unset($data['customer']);
            }

            // Update Lead
            $updatedLead = $this->leadRepository->update($id, $data);
            
            DB::commit();
            return $updatedLead;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteLead($id)
    {
        return $this->leadRepository->delete($id);
    }
    
    public function getLead($id)
    {
        return $this->leadRepository->find($id);
    }
}
