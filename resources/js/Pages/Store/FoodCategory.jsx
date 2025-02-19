import React, { useState, useEffect } from 'react';
import { usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import axios from 'axios';

const foodItems = [
    { id: 1, name: 'ข้าวผัดหมู', price: 50, image: '/images/fried_rice_pork.jpg' },
    { id: 2, name: 'ข้าวกะเพราหมู', price: 50, image: '/images/basil_pork.jpg' },
    { id: 3, name: 'ข้าวผัดกุ้ง', price: 50, image: '/images/fried_rice_shrimp.jpg' },
    { id: 4, name: 'ข้าวกระเพราเนื้อ', price: 50, image: '/images/basil_beef.jpg' },
    { id: 5, name: 'ข้าวผัดปลากระป๋อง', price: 50, image: '/images/fried_rice_canned_fish.jpg' },
];

export default function FoodCategory() {
    const { table } = usePage().props;
    const [cart, setCart] = useState([]);
    const [isSaved, setIsSaved] = useState(false);
    const [categories, setCategories] = useState([]);
    const [name, setName] = useState('');

    useEffect(() => {
        console.log('Table:', table);
        console.log('Cart:', cart);
        fetchCategories();
    }, [table, cart]);

    const fetchCategories = async () => {
        const response = await axios.get('/api/categories');
        setCategories(response.data);
    };

    const addToCart = (item) => {
        console.log('Adding to cart:', item);
        const existingItem = cart.find(cartItem => cartItem.id === item.id);
        if (existingItem) {
            setCart(cart.map(cartItem =>
                cartItem.id === item.id ? { ...cartItem, quantity: cartItem.quantity + 1 } : cartItem
            ));
        } else {
            setCart([...cart, { ...item, quantity: 1 }]);
        }
    };

    const totalPrice = cart.reduce((total, item) => total + item.price * item.quantity, 0);

    const handleSave = () => {
        console.log('Saving cart:', cart);
        router.post('/save-cart', { table, cart }, {
            onSuccess: () => {
                setIsSaved(true);
                console.log('Cart saved successfully');
            },
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const response = await axios.post('/api/categories', { name });
        setCategories([...categories, response.data]);
        setName('');
    };

    return (
        <AuthenticatedLayout>
            <div className="flex gap-4 p-4">
                {/* เมนูอาหาร */}
                <div className="w-3/4 grid grid-cols-4 gap-4 bg-white p-4 rounded-lg shadow">
                    {foodItems.map((item) => (
                        <div key={item.id} className="border p-2 rounded-lg text-center cursor-pointer" onClick={() => addToCart(item)}>
                            <img src={item.image} alt={item.name} className="w-full h-20 object-cover rounded" />
                            <p className="mt-2 font-bold">{item.name}</p>
                        </div>
                    ))}
                </div>

                {/* ตะกร้าสินค้า */}
                <div className={`w-1/4 p-4 rounded-lg shadow ${isSaved ? 'bg-green-100' : 'bg-yellow-100'}`}>
                    <h2 className="text-lg font-bold mb-4">โต๊ะ {table}</h2>
                    <ul>
                        {cart.map((item) => (
                            <li key={item.id} className="flex justify-between py-2 border-b">
                                <span>{item.name}</span>
                                <span>{item.price.toFixed(2)} ฿</span>
                            </li>
                        ))}
                    </ul>
                    <div className="border-t mt-4 pt-2 flex justify-between font-bold">
                        <span>ยอดรวม</span>
                        <span>{totalPrice.toFixed(2)} ฿</span>
                    </div>
                    <div className="flex mt-4 gap-2">
                        <button onClick={handleSave} className="w-1/3 bg-green-500 text-white p-2 rounded-lg">บันทึก</button>
                        <button className="w-1/3 bg-green-700 text-white p-2 rounded-lg">ชำระเงิน</button>
                    </div>
                </div>
            </div>
            {/* ปุ่มสีน้ำเงินด้านล่างสุด */}
            <div className="fixed bottom-0 left-0 w-full bg-blue-900 text-white p-4 flex justify-around items-center">
                <button className="flex flex-col items-center">
                    <span className="text-xl">🧾</span>
                    <span>บิล</span>
                </button>
                <button className="flex flex-col items-center">
                    <span className="text-xl">🍛</span>
                    <span>ข้าว</span>
                </button>
                <button className="flex flex-col items-center">
                    <span className="text-xl">🍜</span>
                    <span>เส้น</span>
                </button>
                <button className="flex flex-col items-center">
                    <span className="text-xl">🥤</span>
                    <span>เครื่องดื่ม</span>
                </button>
                <button className="flex flex-col items-center">
                    <span className="text-xl">📋</span>
                    <span>รายการ</span>
                </button>
            </div>
            {/* ฟอร์มเพิ่มหมวดหมู่อาหาร */}
            <div>
                <h1>Food Categories</h1>
                <form onSubmit={handleSubmit}>
                    <input
                        type="text"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        placeholder="Category Name"
                    />
                    <button type="submit">Add Category</button>
                </form>
                <ul>
                    {categories.map((category) => (
                        <li key={category.id}>{category.name}</li>
                    ))}
                </ul>
            </div>
        </AuthenticatedLayout>
    );
}
