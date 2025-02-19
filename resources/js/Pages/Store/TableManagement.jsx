import React, { useState, useEffect } from 'react';
import axios from 'axios';

const TableManagement = () => {
    const [tables, setTables] = useState([]);
    const [number, setNumber] = useState('');

    useEffect(() => {
        fetchTables();
    }, []);

    const fetchTables = async () => {
        const response = await axios.get('/api/tables');
        setTables(response.data);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        const response = await axios.post('/api/tables', { number });
        setTables([...tables, response.data]);
        setNumber('');
    };

    return (
        <div>
            <h1>Table Management</h1>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={number}
                    onChange={(e) => setNumber(e.target.value)}
                    placeholder="Table Number"
                />
                <button type="submit">Add Table</button>
            </form>
            <ul>
                {tables.map((table) => (
                    <li key={table.id}>{table.number}</li>
                ))}
            </ul>
        </div>
    );
};

export default TableManagement;
