import { useState, useEffect } from "react";
import { Link } from "@inertiajs/react"; // ใช้ของ Inertia.js แทน react-router-dom
import logo from "../image/logo.webp";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout"; // Import AuthenticatedLayout

// รูปโปรโมชั่น (สามารถเพิ่ม/เปลี่ยนเป็น URL จริงได้)
const promotions = [
  { id: 1, image: "/images/promo1.jpg", title: "ซื้อ 2 แถม 1" },
  { id: 2, image: "/images/promo2.jpg", title: "Beer Tower 699.-" },
  { id: 3, image: "/images/promo3.jpg", title: "ลด 20% ทุกเมนูหลัง 22.00 น." }
];

export default function Promotions() {
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    console.log(`Current promotion index: ${currentIndex}`);
  }, [currentIndex]);

  // ฟังก์ชันเลื่อนโปรโมชั่น
  const nextSlide = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex === promotions.length - 1 ? 0 : prevIndex + 1
    );
  };

  const prevSlide = () => {
    setCurrentIndex((prevIndex) =>
      prevIndex === 0 ? promotions.length - 1 : prevIndex - 1
    );
  };

  return (
    <AuthenticatedLayout> {/* Wrap with AuthenticatedLayout */}
      <div className="min-h-screen flex flex-col items-center bg-gray-900 text-white">
        {/* แถบด้านบน - โลโก้ร้าน */}
        <div className="w-full flex items-center justify-between p-4 bg-gray-800 shadow-md">
          <img src={logo} alt="ร้านของเรา" className="h-14" />
          <h1 className="text-2xl font-bold">The Most Bar & Bistro</h1>
        </div>

        {/* พื้นที่โปรโมชั่น */}
        <div className="relative w-full max-w-2xl mt-8">
          <img
            src={promotions[currentIndex].image}
            alt={promotions[currentIndex].title}
            className="w-full h-80 object-cover rounded-lg shadow-lg"
          />
          <button
            onClick={prevSlide}
            className="absolute left-4 top-1/2 transform -translate-y-1/2 bg-gray-700 p-3 rounded-full"
          >
            ◀
          </button>
          <button
            onClick={nextSlide}
            className="absolute right-4 top-1/2 transform -translate-y-1/2 bg-gray-700 p-3 rounded-full"
          >
            ▶
          </button>
        </div>

        {/* ชื่อโปรโมชั่น */}
        <h2 className="text-xl font-semibold mt-4">{promotions[currentIndex].title}</h2>

        <style>
          {`
            button:hover {
              background-color: #eab308;
            }
          `}
        </style>
      </div>
    </AuthenticatedLayout>
  );
}
