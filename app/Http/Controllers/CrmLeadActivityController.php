<?php

namespace App\Http\Controllers;

use App\Models\CrmLeadActivity;
use App\Services\CrmLeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmLeadActivityController extends Controller
{
    protected $leadService;

    public function __construct(CrmLeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function store(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        $lead = $this->leadService->getLead($id);

        $canAct = $lead && (
            $lead->sale_id == $customer->id ||
            $customer->isSaleAdmin()
        );

        if (!$canAct) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'type'    => 'required|in:call,note,assignment,status_change',
            'content' => 'nullable|string|max:1000',
        ]);

        $activity = CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $customer->id,
            'type'     => $request->input('type'),
            'content'  => $request->input('content'),
            'metadata' => $request->input('metadata'),
        ]);

        $activity->load('actor');

        return response()->json([
            'success'  => true,
            'activity' => [
                'id'         => $activity->id,
                'type'       => $activity->type,
                'type_label' => $activity->getTypeLabel(),
                'type_icon'  => $activity->getTypeIcon(),
                'content'    => $activity->content,
                'actor_name' => $activity->actor->name ?? '',
                'time'       => $activity->created_at->format('H:i d/m/Y'),
            ],
        ]);
    }
}
