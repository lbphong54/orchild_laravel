<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendReservationCancellationJob;
use App\Mail\ReservationSuccessMail;
use App\Models\Reservation;
use App\Models\ReservationTable;
use App\Models\Restaurant;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:500',
            'email' => 'required|email|max:500',
            'phone' => 'required|string|max:11',
            'restaurant_id' => 'required|exists:restaurants,id',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'reservation_time' => 'required|date|after:now',
            'special_request' => 'nullable|string|max:500',
            'table_ids' => 'required|array|exists:restaurant_tables,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // check table time is available
        $tableTime = ReservationTable::query()
            ->whereIn('restaurant_table_id', $request->table_ids)
            ->where('from_time', '<=', $request->reservation_time)
            ->where('to_time', '>=', Carbon::parse($request->reservation_time)->addHours(2))->first();
        if ($tableTime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bàn ' . $tableTime->table->name . ' đã được đặt trong khoảng thời gian bạn chọn.'
            ], 400);
        }

        // Create reservation
        $reservation = Reservation::create([
            'customer_id' => Auth::user()->id,
            'restaurant_id' => $request->restaurant_id,
            'reservation_time' => $request->reservation_time,
            'num_adults' => $request->adults,
            'num_children' => $request->children,
            'special_request' => $request->special_request,
            'status' => 'pending',
            'is_paid' => false
        ]);

        foreach ($request->table_ids as $tableId) {
            ReservationTable::create([
                'reservation_id' => $reservation->id,
                'restaurant_table_id' => $tableId,
                'from_time' => $request->reservation_time,
                'to_time' => Carbon::parse($request->reservation_time)->addHours(2)
            ]);
        }

        $tableNames = RestaurantTable::whereIn('id', $request->table_ids)->pluck('name')->toArray();
        $tableNameString = implode(', ', $tableNames);

        Mail::to($request->email)->send(new ReservationSuccessMail(
            $request->adults,
            $request->children,
            $reservation->id,
            $request->reservation_time,
            $tableNameString
        ));

        return response()->json([
            'status' => 'success',
            'message' => 'Reservation created successfully',
            'data' => $reservation
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        return response()->json($reservation);
    }

    /**
     * Hủy đơn đặt bàn
     */
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::with(['customer', 'restaurant'])
            ->where('id', $id)
            ->where('customer_id', Auth::user()->id)
            ->first();

        if (!$reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn đặt bàn không tồn tại hoặc bạn không có quyền hủy'
            ], 404);
        }

        // Kiểm tra trạng thái đơn hàng
        if ($reservation->status === 'cancelled') {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng đã được hủy trước đó'
            ], 400);
        }

        if ($reservation->status === 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể hủy đơn hàng đã hoàn thành'
            ], 400);
        }

        // Kiểm tra thời gian hủy
        $now = Carbon::now();
        // $createdDiff = $now->diffInMinutes($reservation->created_at);
        if ($createdDiff > 5) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn chỉ có thể hủy đơn trong vòng 5 phút sau khi đặt.'
            ], 403);
        }
        $hoursBeforeReservation = $now->diffInHours($reservation->reservation_time, false);
        
        // Không cho phép hủy nếu đã quá thời gian đặt bàn
        if ($hoursBeforeReservation < 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể hủy đơn hàng đã quá thời gian đặt bàn'
            ], 400);
        }

        // Cập nhật trạng thái đơn hàng
        $reservation->status = 'cancelled';
        $reservation->save();

        // Gửi email thông báo hủy đơn hàng
        SendReservationCancellationJob::dispatch($reservation, $now);

        // Xóa các bàn đã đặt
        $reservation->tables()->detach();

        $refundMessage = $hoursBeforeReservation >= 1 
            ? 'Đơn hàng sẽ được hoàn tiền trong vòng 3-5 ngày làm việc.'
            : 'Do hủy đơn trong vòng 1 giờ trước thời gian đặt bàn, đơn hàng không được hoàn tiền.';

        return response()->json([
            'status' => 'success',
            'message' => 'Hủy đơn hàng thành công',
            'data' => [
                'reservation_id' => $reservation->id,
                'cancellation_time' => $now->format('Y-m-d H:i:s'),
                'hours_before_reservation' => $hoursBeforeReservation,
                'is_refundable' => $hoursBeforeReservation >= 1,
                'refund_message' => $refundMessage
            ]
        ]);
    }

    /**
     * Lấy lịch sử đặt bàn của user đăng nhập, có phân trang.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $reservations = Reservation::where('customer_id', $user->id)->get();
        
        $reservations = Reservation::with(['restaurant:id,name,address,phone', 'tables:id,name'])
            ->where('customer_id', $user->id)
            ->orderBy('reservation_time', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $reservations
        ]);
    }

    // Lấy thông tin tạo mã QR để thanh toán
    public function getQrCode($id)
    {
        $reservation = Reservation::with(['restaurant:id,name,address,phone,bank_code,bank_account_number,deposit_adult,deposit_child'])
            ->where('id', $id)
            ->where('customer_id', Auth::user()->id)
            ->first();

        if (!$reservation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn đặt bàn không tồn tại hoặc bạn không có quyền tạo mã QR'
            ], 404);
        }

        $amount = $reservation->num_adults * $reservation->restaurant->deposit_adult + $reservation->num_children * $reservation->restaurant->deposit_child;

        return response()->json([
            'status' => 'success',
            'data' => [
                'amount' => $amount,
                'bank_code' => $reservation->restaurant->bank_code,
                'bank_account_number' => $reservation->restaurant->bank_account_number
            ]
        ]);
    }
}
