<?php

namespace App\Http\Controllers;

use App\Events\TableBooked;
use App\Models\DatBan;
use App\Models\Table;
use Illuminate\Http\Request;

class TableBookedController extends Controller
{
    public function broadcastTableBooking(Request $request)
    {
        $table = DatBan::find($request->table_id);

        if (!$table) {
            return response()->json(['message' => 'Bàn không tồn tại!'], 404);
        }

        // Phát sự kiện realtime
        broadcast(new TableBooked($table));

        return response()->json(['message' => 'Đã phát sự kiện đặt bàn!', 'table' => $table]);
    }
}
