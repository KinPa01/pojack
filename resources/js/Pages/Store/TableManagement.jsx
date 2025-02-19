import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function TableManagement() {
    const tableIcons = Array.from({ length: 45 }, (_, i) => i + 1);
    const [savedTables, setSavedTables] = useState([]);

    const handleSave = (tableNumber) => {
        setSavedTables([...savedTables, tableNumber]);
    };

    return (
        <AuthenticatedLayout>
            <div className="container mx-auto px-4 py-8">
                <h2 className="text-3xl font-bold mb-8">Table Management</h2>
                <div className="grid grid-cols-5 gap-4 mt-8">
                    {tableIcons.map((number) => (
                        <Link
                            key={number}
                            href={route('store.foodCategory', { table: number })}
                            className={`text-white text-center p-4 rounded-lg ${savedTables.includes(number) ? 'bg-green-500' : 'bg-gray-700'}`}
                            onClick={() => handleSave(number)}
                        >
                            {number}
                        </Link>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
