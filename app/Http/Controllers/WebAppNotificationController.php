<?php

namespace App\Http\Controllers;

use App\Services\InAppNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppNotificationController extends Controller
{
    protected InAppNotificationService $notifService;

    public function __construct(InAppNotificationService $notifService)
    {
        $this->notifService = $notifService;
    }

    /**
     * Paginated notification list with optional category filter.
     */
    public function index(Request $request): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();

        $category = $request->input('category');
        $validCategories = ['lead', 'deal', 'booking', 'commission', 'property', 'admin'];
        if ($category && !in_array($category, $validCategories)) {
            $category = null;
        }

        $paginator = $this->notifService->getNotifications(
            $customer->id,
            $category,
            15
        );

        $notifications = collect($paginator->items())->map(fn ($n) => $n->toActivityArray());

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Unread notification count for badge.
     */
    public function unreadCount(): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();

        return response()->json([
            'success' => true,
            'count'   => $this->notifService->unreadCount($customer->id),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(int $id): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        $this->notifService->markAsRead($id, $customer->id);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read, optionally by category.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $customer = Auth::guard('webapp')->user();
        $category = $request->input('category');

        $count = $this->notifService->markAllAsRead($customer->id, $category);

        return response()->json(['success' => true, 'marked' => $count]);
    }
}
