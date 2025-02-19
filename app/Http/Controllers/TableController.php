<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use App\Models\Cart;

class TableController extends Controller
{
    /**
     * แสดงรายการโต๊ะทั้งหมด
     * ดึงข้อมูลจากฐานข้อมูลและส่งไปยังหน้าแสดงผล
     */
    public function index()
    {
        return view('tables.index', ['tables' => Table::all()]);
    }

    /**
     * แสดงฟอร์มสำหรับสร้างโต๊ะใหม่
     */
    public function create()
    {
        return view('tables.create');
    }

    /**
     * บันทึกข้อมูลโต๊ะใหม่ลงฐานข้อมูล
     * ตรวจสอบความถูกต้องของข้อมูลก่อนบันทึก
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $validated = $request->validate([
            'number' => 'required|integer|unique:tables', // หมายเลขโต๊ะต้องไม่ซ้ำ
            'status' => 'required|in:available,occupied', // สถานะต้องเป็นค่าที่กำหนด
        ]);

        // สร้างโต๊ะใหม่และบันทึกลงฐานข้อมูล
        Table::create($validated);
        return redirect()->route('tables.index')->with('success', 'เพิ่มโต๊ะใหม่สำเร็จ');
    }

    /**
     * แสดงรายละเอียดโต๊ะตาม ID ที่ระบุ
     */
    public function show(string $id)
    {
        return view('tables.show', ['table' => Table::findOrFail($id)]);
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขข้อมูลโต๊ะ
     * ดึงข้อมูลโต๊ะที่ต้องการแก้ไข
     */
    public function edit(string $id)
    {
        return view('tables.edit', ['table' => Table::findOrFail($id)]);
    }

    /**
     * อัปเดตข้อมูลโต๊ะในฐานข้อมูล
     * ตรวจสอบข้อมูลก่อนการอัปเดต
     */
    public function update(Request $request, string $id)
    {
        $table = Table::findOrFail($id); // ค้นหาโต๊ะที่ต้องการอัปเดต

        // ตรวจสอบข้อมูลก่อนการอัปเดต
        $validated = $request->validate([
            'number' => 'required|integer|unique:tables,number,' . $id, // หมายเลขโต๊ะต้องไม่ซ้ำ ยกเว้นตัวเอง
            'status' => 'required|in:available,occupied', // ต้องเป็นสถานะที่ถูกต้อง
        ]);

        // บันทึกข้อมูลการอัปเดตโต๊ะ
        $table->update($validated);
        return redirect()->route('tables.index')->with('success', 'อัปเดตข้อมูลโต๊ะสำเร็จ');
    }

    /**
     * ลบข้อมูลโต๊ะออกจากฐานข้อมูล
     */
    public function destroy(string $id)
    {
        $table = Table::findOrFail($id); // ตรวจสอบว่าโต๊ะมีอยู่จริง
        $table->delete(); // ลบข้อมูลโต๊ะ
        return redirect()->route('tables.index')->with('success', 'ลบโต๊ะสำเร็จ');
    }

    public function manage()
    {
        return Inertia::render('Store/TableManagement');
    }

    public function saveCart(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|integer',
            'cart' => 'required|array',
            'cart.*.id' => 'required|integer',
            'cart.*.name' => 'required|string',
            'cart.*.price' => 'required|numeric',
            'cart.*.quantity' => 'required|integer',
        ]);

        // Save the cart to the database
        foreach ($validated['cart'] as $item) {
            Cart::create([
                'table_id' => $validated['table'],
                'food_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return redirect()->back()->with('success', 'Cart saved successfully.');
    }
}
