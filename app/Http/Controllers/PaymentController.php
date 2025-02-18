<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * แสดงรายการการชำระเงินทั้งหมด
     * ดึงข้อมูลจากฐานข้อมูลและส่งไปยังหน้าแสดงผล
     */
    public function index()
    {
        return view('payments.index', ['payments' => Payment::all()]);
    }

    /**
     * แสดงฟอร์มสำหรับสร้างข้อมูลการชำระเงินใหม่
     * ดึงคำสั่งซื้อที่ยังรอดำเนินการ เพื่อให้เลือกคำสั่งซื้อที่ต้องการชำระ
     */
    public function create()
    {
        return view('payments.create', ['orders' => Order::where('status', 'pending')->get()]);
    }

    /**
     * บันทึกข้อมูลการชำระเงินใหม่ลงฐานข้อมูล
     * ตรวจสอบความถูกต้องของข้อมูลก่อนบันทึก
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id', // ตรวจสอบว่ามีคำสั่งซื้ออยู่จริง
            'amount' => 'required|numeric|min:0', // จำนวนเงินต้องเป็นตัวเลขและไม่ติดลบ
            'method' => 'required|in:bank_transfer,qr_code,cash', // จำกัดช่องทางการชำระเงิน
            'status' => 'required|in:pending,paid,failed', // สถานะต้องเป็นค่าที่กำหนดไว้เท่านั้น
        ]);

        // สร้างข้อมูลการชำระเงินใหม่และบันทึกลงฐานข้อมูล
        $payment = Payment::create($validated);
        return redirect()->route('payments.index')->with('success', 'บันทึกการชำระเงินสำเร็จ');
    }

    /**
     * แสดงรายละเอียดของการชำระเงินตาม ID ที่ระบุ
     */
    public function show(string $id)
    {
        return view('payments.show', ['payment' => Payment::findOrFail($id)]);
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขข้อมูลการชำระเงิน
     * ดึงข้อมูลการชำระเงินที่ต้องการแก้ไขและแสดงคำสั่งซื้อที่รอดำเนินการ
     */
    public function edit(string $id)
    {
        return view('payments.edit', [
            'payment' => Payment::findOrFail($id), // ตรวจสอบว่าข้อมูลมีอยู่จริง
            'orders' => Order::where('status', 'pending')->get() // ดึงรายการคำสั่งซื้อที่ยังไม่ได้ชำระ
        ]);
    }

    /**
     * อัปเดตข้อมูลการชำระเงินในฐานข้อมูล
     * ตรวจสอบข้อมูลก่อนการอัปเดต
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id); // ค้นหาข้อมูลที่ต้องการอัปเดต

        // ตรวจสอบข้อมูลก่อนการอัปเดต
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,failed', // ต้องเป็นสถานะที่ถูกต้อง
        ]);

        // บันทึกข้อมูลการอัปเดต
        $payment->update($validated);
        return redirect()->route('payments.index')->with('success', 'อัปเดตสถานะการชำระเงินสำเร็จ');
    }

    /**
     * ลบข้อมูลการชำระเงินออกจากฐานข้อมูล
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id); // ตรวจสอบว่าข้อมูลมีอยู่จริง
        $payment->delete(); // ลบข้อมูล
        return redirect()->route('payments.index')->with('success', 'ลบข้อมูลการชำระเงินสำเร็จ');
    }
}
