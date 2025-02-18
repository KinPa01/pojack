<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * แสดงรายการสินค้าทั้งหมด
     * ดึงข้อมูลจากฐานข้อมูลและส่งไปยังหน้าแสดงผล
     */
    public function index()
    {
        return view('products.index', ['products' => Product::with('category')->get()]);
    }

    /**
     * แสดงฟอร์มสำหรับสร้างสินค้าใหม่
     * ดึงรายการหมวดหมู่เพื่อให้ผู้ใช้สามารถเลือกหมวดหมู่ของสินค้าได้
     */
    public function create()
    {
        return view('products.create', ['categories' => Category::all()]);
    }

    /**
     * บันทึกข้อมูลสินค้าใหม่ลงฐานข้อมูล
     * ตรวจสอบความถูกต้องของข้อมูลก่อนบันทึก
     */
    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากฟอร์ม
        $validated = $request->validate([
            'name' => 'required|string|unique:products', // ชื่อสินค้าต้องไม่ซ้ำ
            'category_id' => 'required|exists:categories,id', // ตรวจสอบว่าหมวดหมู่มีอยู่จริง
            'price' => 'required|numeric|min:0', // ราคาต้องเป็นตัวเลขและไม่ติดลบ
            'stock' => 'required|integer|min:0', // จำนวนสินค้าต้องเป็นจำนวนเต็มและไม่ติดลบ
        ]);

        // สร้างสินค้าใหม่และบันทึกลงฐานข้อมูล
        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'เพิ่มสินค้าใหม่สำเร็จ');
    }

    /**
     * แสดงรายละเอียดสินค้าตาม ID ที่ระบุ
     */
    public function show(string $id)
    {
        return view('products.show', ['product' => Product::with('category')->findOrFail($id)]);
    }

    /**
     * แสดงฟอร์มสำหรับแก้ไขข้อมูลสินค้า
     * ดึงข้อมูลสินค้าที่ต้องการแก้ไข และหมวดหมู่ทั้งหมดเพื่อให้เลือกเปลี่ยนหมวดหมู่ได้
     */
    public function edit(string $id)
    {
        return view('products.edit', [
            'product' => Product::findOrFail($id), // ตรวจสอบว่าสินค้ามีอยู่จริง
            'categories' => Category::all() // ดึงรายการหมวดหมู่ทั้งหมด
        ]);
    }

    /**
     * อัปเดตข้อมูลสินค้าในฐานข้อมูล
     * ตรวจสอบข้อมูลก่อนการอัปเดต
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id); // ค้นหาสินค้าที่ต้องการอัปเดต

        // ตรวจสอบข้อมูลก่อนการอัปเดต
        $validated = $request->validate([
            'name' => 'required|string|unique:products,name,' . $id, // ชื่อสินค้าต้องไม่ซ้ำ ยกเว้นตัวเอง
            'category_id' => 'required|exists:categories,id', // ตรวจสอบหมวดหมู่
            'price' => 'required|numeric|min:0', // ราคาต้องเป็นตัวเลข
            'stock' => 'required|integer|min:0', // จำนวนสินค้าต้องเป็นตัวเลขไม่ติดลบ
        ]);

        // บันทึกข้อมูลการอัปเดตสินค้า
        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'อัปเดตข้อมูลสินค้าสำเร็จ');
    }

    /**
     * ลบข้อมูลสินค้าออกจากฐานข้อมูล
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id); // ตรวจสอบว่าสินค้ามีอยู่จริง
        $product->delete(); // ลบข้อมูลสินค้า
        return redirect()->route('products.index')->with('success', 'ลบสินค้าสำเร็จ');
    }
}
